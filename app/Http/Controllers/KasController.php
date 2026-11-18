<?php
namespace App\Http\Controllers;

use App\Models\CashTransaction;
use Illuminate\Http\Request;

class KasController extends Controller
{
    public function index()
    {
        $masuk = CashTransaction::where('jenis', 'masuk')->sum('jumlah');
        $keluar = CashTransaction::where('jenis', 'keluar')->sum('jumlah');
        return view('kas', ['items' => CashTransaction::latest('tanggal')->latest('id')->paginate(15), 'masuk' => $masuk, 'keluar' => $keluar]);
    }

    // FR-09: kas masuk/keluar manual (kas keluar dari pembayaran hutang dicatat otomatis)
    public function store(Request $r)
    {
        CashTransaction::create($r->validate(['tanggal' => 'required|date', 'jenis' => 'required|in:masuk,keluar', 'kategori' => 'required', 'jumlah' => 'required|numeric|min:1', 'keterangan' => 'nullable']));
        return back()->with('ok', 'Transaksi kas tersimpan.');
    }
}
