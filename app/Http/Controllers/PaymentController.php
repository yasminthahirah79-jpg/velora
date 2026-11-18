<?php
namespace App\Http\Controllers;

use App\Models\{ActivityLog, CashTransaction, Payable, Payment};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function index() { return view('payments.index', ['semua' => Payable::with('supplier')->orderByRaw("status = 'lunas'")->orderBy('jatuh_tempo')->get(), 'payables' => Payable::with('supplier')->where('status', '!=', 'lunas')->orderBy('jatuh_tempo')->get(), 'payments' => Payment::with('payable.supplier')->latest()->take(10)->get()]); }

    // FR-08: pembayaran -> status hutang diperbarui -> kas keluar tercatat otomatis
    public function store(Request $r)
    {
        $d = $r->validate(['payable_id' => 'required|exists:payables,id', 'tanggal' => 'required|date', 'jumlah' => 'required|numeric|min:1',
            'metode' => 'required|in:tunai,transfer', 'bukti_bayar' => 'nullable|image|max:2048']);

        DB::transaction(function () use ($d, $r) {
            $hutang = Payable::lockForUpdate()->findOrFail($d['payable_id']);
            if ($d['jumlah'] > $hutang->sisa) throw ValidationException::withMessages(['jumlah' => 'Jumlah melebihi sisa hutang.']);
            $bukti = $r->file('bukti_bayar')?->store('bukti', 'public');
            $pay = Payment::create([...collect($d)->except('bukti_bayar')->all(), 'bukti_bayar' => $bukti]);
            $hutang->update(['status' => $hutang->sisa <= 0.009 ? 'lunas' : 'sebagian']); // sisa sudah memperhitungkan pembayaran baru
            CashTransaction::create(['tanggal' => $d['tanggal'], 'jenis' => 'keluar', 'kategori' => 'Pembayaran hutang', 'jumlah' => $d['jumlah'],
                'payment_id' => $pay->id, 'keterangan' => "Pembayaran {$hutang->no_tagihan}"]);
            ActivityLog::create(['user_id' => $r->user()->id, 'aktivitas' => "Mencatat pembayaran {$hutang->no_tagihan}"]);
        });

        return back()->with('ok', 'Pembayaran tercatat.');
    }
}
