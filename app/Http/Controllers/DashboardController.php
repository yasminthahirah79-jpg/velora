<?php
namespace App\Http\Controllers;

use App\Models\{CashTransaction, Payable, Purchase};

class DashboardController extends Controller
{
    public function index()
    {
        $awal = now()->startOfMonth();
        $beli = Purchase::where('status', 'tercatat');
        return view('dashboard', [
            'totalPembelian' => (clone $beli)->where('tanggal', '>=', $awal)->sum('total'),
            'jumlahPembelian' => (clone $beli)->where('tanggal', '>=', $awal)->count(),
            'hutangBelumLunas' => Payable::where('status', '!=', 'lunas')->get()->sum->sisa,
            'status' => Payable::selectRaw('status, count(*) c')->groupBy('status')->pluck('c', 'status'),
            'jatuhTempo' => Payable::with('supplier')->where('status', '!=', 'lunas')->whereDate('jatuh_tempo', '<=', now()->addDays(7))->orderBy('jatuh_tempo')->take(4)->get(),
            'kasMasuk' => CashTransaction::where('jenis', 'masuk')->where('tanggal', '>=', $awal)->sum('jumlah'),
            'kasKeluar' => CashTransaction::where('jenis', 'keluar')->where('tanggal', '>=', $awal)->sum('jumlah'),
            'terbaru' => Purchase::with('supplier', 'payable')->latest()->take(3)->get(),
            'grafik' => collect(range(9, 0))->map(fn ($i) => (float) Purchase::where('status', 'tercatat')->whereDate('tanggal', now()->subDays($i))->sum('total')),
        ]);
    }
}
