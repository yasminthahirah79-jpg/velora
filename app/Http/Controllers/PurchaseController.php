<?php
namespace App\Http\Controllers;

use App\Models\{ActivityLog, Payable, Product, Purchase, Supplier};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index() { return view('purchases.index', ['purchases' => Purchase::with('supplier', 'payable')->latest()->paginate(15)]); }

    public function create() { return view('purchases.create', ['suppliers' => Supplier::where('status', 'aktif')->get(), 'products' => Product::all()]); }

    // FR-05..FR-07: simpan pembelian -> stok bertambah -> tagihan hutang terbentuk (satu transaksi database)
    public function store(Request $r)
    {
        $d = $r->validate([
            'supplier_id' => 'required|exists:suppliers,id', 'no_faktur' => 'required|string|max:50',
            'tanggal' => 'required|date', 'jatuh_tempo' => 'required|date|after_or_equal:tanggal',
            'items' => 'required|array|min:1', 'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1', 'items.*.harga' => 'required|numeric|min:0',
        ]);

        $purchase = DB::transaction(function () use ($d, $r) {
            $seq = str_pad(Purchase::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count() + 1, 3, '0', STR_PAD_LEFT);
            $ym = now()->format('ym');
            $p = Purchase::create(['no_pembelian' => "PB-$ym-$seq", 'no_faktur' => $d['no_faktur'], 'supplier_id' => $d['supplier_id'],
                'tanggal' => $d['tanggal'], 'jatuh_tempo' => $d['jatuh_tempo'], 'created_by' => $r->user()->id]);
            $total = 0;
            foreach ($d['items'] as $i) {
                $sub = $i['qty'] * $i['harga'];
                $p->items()->create(['product_id' => $i['product_id'], 'qty' => $i['qty'], 'harga' => $i['harga'], 'subtotal' => $sub]);
                Product::whereKey($i['product_id'])->increment('stok', $i['qty']);
                $total += $sub;
            }
            $p->update(['total' => $total]);
            Payable::create(['no_tagihan' => "TG-$ym-$seq", 'purchase_id' => $p->id, 'supplier_id' => $p->supplier_id, 'total' => $total, 'jatuh_tempo' => $p->jatuh_tempo]);
            ActivityLog::create(['user_id' => $r->user()->id, 'aktivitas' => "Mencatat pembelian {$p->no_pembelian}"]);
            return $p;
        });

        return redirect()->route('purchases.show', $purchase)->with('ok', 'Pembelian tersimpan, stok dan hutang diperbarui.');
    }

    public function show(Purchase $purchase) { return view('purchases.show', ['purchase' => $purchase->load('supplier', 'items.product', 'payable.payments')]); }

    // Pembatalan hanya boleh jika belum ada pembayaran
    public function cancel(Request $r, Purchase $purchase)
    {
        abort_if($purchase->status === 'dibatalkan' || $purchase->payable->payments()->exists(), 422, 'Pembelian tidak dapat dibatalkan.');
        DB::transaction(function () use ($purchase, $r) {
            foreach ($purchase->items as $i) Product::whereKey($i->product_id)->decrement('stok', $i->qty);
            $purchase->payable->delete();
            $purchase->update(['status' => 'dibatalkan']);
            ActivityLog::create(['user_id' => $r->user()->id, 'aktivitas' => "Membatalkan pembelian {$purchase->no_pembelian}"]);
        });
        return back()->with('ok', 'Pembelian dibatalkan.');
    }
}
