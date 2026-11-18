@extends('layouts.app')
@section('title', 'Hutang & Pembayaran')
@section('content')
@php $rp = fn ($v) => 'Rp ' . number_format($v, 0, ',', '.'); @endphp
<div class="top"><div><h1>Hutang & Pembayaran</h1><p class="sub">Catat pembayaran hutang supplier. Status hutang dan kas keluar diperbarui otomatis.</p></div></div>
<div class="two"><form method="post" action="{{ route('payments.store') }}" enctype="multipart/form-data" class="card w"><h3>Catat pembayaran</h3>@csrf
<div class="f"><div><label>Tagihan</label><select name="payable_id">@foreach($payables as $p)<option value="{{ $p->id }}">{{ $p->no_tagihan }} · {{ $p->supplier->nama }} · sisa {{ $rp($p->sisa) }}</option>@endforeach</select></div>
<div><label>Tanggal bayar</label><input type="date" name="tanggal" value="{{ now()->toDateString() }}"></div>
<div><label>Jumlah dibayar</label><input type="number" step="any" min="1" name="jumlah" required></div>
<div><label>Metode</label><select name="metode"><option value="transfer">Transfer</option><option value="tunai">Tunai</option></select></div>
<div><label>Bukti pembayaran (gambar)</label><input type="file" name="bukti_bayar" accept="image/*"></div></div>
<div style="text-align:right;margin-top:14px"><button class="btn">Simpan Pembayaran</button></div></form>
<div><h2 style="margin-top:0">Riwayat terakhir</h2>@forelse($payments as $p)<div class="tl"><i>✓</i><div><b>{{ $rp($p->jumlah) }} · {{ $p->payable->supplier->nama }}</b><p>{{ $p->tanggal }} · {{ ucfirst($p->metode) }}</p></div></div>@empty<p class="sub">Belum ada pembayaran.</p>@endforelse</div></div>
<h2>Daftar tagihan</h2><div class="row h" style="grid-template-columns:1fr 1.5fr 1fr 1fr 1fr"><span>No. Tagihan</span><span>Supplier</span><span>Total</span><span>Jatuh tempo</span><span>Status</span></div>
@foreach($semua as $h)<div class="row" style="grid-template-columns:1fr 1.5fr 1fr 1fr 1fr"><span>{{ $h->no_tagihan }}</span><span>{{ $h->supplier->nama }}</span><span>{{ $rp($h->total) }}</span><span>{{ $h->jatuh_tempo->format('d M Y') }}</span>
<span><span class="pill {{ $h->status == 'lunas' ? 'ok' : ($h->jatuh_tempo->isPast() ? 'bd' : ($h->status == 'sebagian' ? 'in' : 'wn')) }}">{{ $h->status != 'lunas' && $h->jatuh_tempo->isPast() ? 'Jatuh tempo' : ucfirst($h->status) }}</span></span></div>@endforeach
@endsection
