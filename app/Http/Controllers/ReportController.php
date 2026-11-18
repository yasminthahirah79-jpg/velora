<?php
namespace App\Http\Controllers;

use App\Models\{CashTransaction, Payable, Payment, Purchase};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $r)
    {
        $jenis = $r->input('jenis', 'pembelian');
        $dari = $r->input('dari', now()->startOfMonth()->toDateString());
        $sampai = $r->input('sampai', now()->toDateString());
        [$head, $rows, $total] = $this->build($jenis, $dari, $sampai);
        $data = compact('jenis', 'dari', 'sampai', 'head', 'rows', 'total');

        if ($r->export === 'pdf') return Pdf::loadView('laporan-pdf', $data)->download("laporan-$jenis.pdf");
        if ($r->export === 'excel') {
            return response()->streamDownload(function () use ($head, $rows) {
                $f = fopen('php://output', 'w'); fwrite($f, "\xEF\xBB\xBF"); // BOM agar terbaca benar di Excel
                fputcsv($f, $head, ';'); foreach ($rows as $row) fputcsv($f, $row, ';'); fclose($f);
            }, "laporan-$jenis.csv", ['Content-Type' => 'text/csv']);
        }
        return view('laporan', $data);
    }

    private function build(string $jenis, string $dari, string $sampai): array
    {
        $rp = fn ($v) => 'Rp ' . number_format($v, 0, ',', '.');
        $tgl = fn ($v) => $v->format('d/m/Y');
        return match ($jenis) {
            'hutang' => (function () use ($dari, $sampai, $rp, $tgl) {
                $d = Payable::with('supplier')->whereBetween('jatuh_tempo', [$dari, $sampai])->orderBy('jatuh_tempo')->get();
                return [['No. Tagihan', 'Supplier', 'Jatuh Tempo', 'Total', 'Status'], $d->map(fn ($x) => [$x->no_tagihan, $x->supplier->nama, $tgl($x->jatuh_tempo), $rp($x->total), $x->status])->all(), $d->sum('total')];
            })(),
            'pembayaran' => (function () use ($dari, $sampai, $rp) {
                $d = Payment::with('payable.supplier')->whereBetween('tanggal', [$dari, $sampai])->orderBy('tanggal')->get();
                return [['Tanggal', 'Tagihan', 'Supplier', 'Metode', 'Jumlah'], $d->map(fn ($x) => [$x->tanggal, $x->payable->no_tagihan, $x->payable->supplier->nama, $x->metode, $rp($x->jumlah)])->all(), $d->sum('jumlah')];
            })(),
            'kas' => (function () use ($dari, $sampai, $rp) {
                $d = CashTransaction::whereBetween('tanggal', [$dari, $sampai])->orderBy('tanggal')->get();
                return [['Tanggal', 'Jenis', 'Kategori', 'Keterangan', 'Jumlah'], $d->map(fn ($x) => [$x->tanggal, $x->jenis, $x->kategori, $x->keterangan, $rp($x->jumlah)])->all(), $d->sum(fn ($x) => $x->jenis === 'masuk' ? $x->jumlah : -$x->jumlah)];
            })(),
            default => (function () use ($dari, $sampai, $rp, $tgl) {
                $d = Purchase::with('supplier')->where('status', 'tercatat')->whereBetween('tanggal', [$dari, $sampai])->orderBy('tanggal')->get();
                return [['No. Pembelian', 'Tanggal', 'Supplier', 'No. Faktur', 'Total'], $d->map(fn ($x) => [$x->no_pembelian, $tgl($x->tanggal), $x->supplier->nama, $x->no_faktur, $rp($x->total)])->all(), $d->sum('total')];
            })(),
        };
    }
}
