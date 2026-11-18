@extends('layouts.app')
@section('title', $purchase->no_pembelian)
@section('content')
@php $h = $purchase->payable; $rp = fn ($v) => 'Rp ' . number_format($v, 0, ',', '.'); @endphp
<div class="top"><div><h1>{{ $purchase->no_pembelian }}</h1><p class="sub">{{ $purchase->supplier->nama }} · {{ $purchase->tanggal->format('d M Y') }} · Faktur {{ $purchase->no_faktur }}</p></div>
<div style="display:flex;gap:10px">@if($purchase->status == 'tercatat' && !$h->payments->count())<form method="post" action="{{ route('purchases.cancel', $purchase) }}" onsubmit="return confirm('Batalkan pembelian ini?')">@csrf<button class="btn l">Batalkan</button></form>@endif
@if(in_array(auth()->user()->role, ['admin', 'keuangan']) && $purchase->status == 'tercatat')<a class="btn" style="text-decoration:none" href="{{ route('payments.index') }}">Bayar Hutang</a>@endif</div></div>
<div class="two"><div><h2 style="margin-top:0">Item pembelian</h2><div class="row h" style="grid-template-columns:2fr .6fr 1fr 1fr"><span>Barang</span><span>Qty</span><span>Harga</span><span>Subtotal</span></div>
@foreach($purchase->items as $i)<div class="row" style="grid-template-columns:2fr .6fr 1fr 1fr"><span>{{ $i->product->nama }}</span><span>{{ $i->qty }}</span><span>{{ $rp($i->harga) }}</span><span>{{ $rp($i->subtotal) }}</span></div>@endforeach
<div class="tot"><span>Total</span><span>{{ $rp($purchase->total) }}</span></div></div>
<div>@if($h)<div class="card p" style="margin-bottom:12px"><h3>Tagihan hutang {{ $h->no_tagihan }}</h3><div class="st"><div><b>{{ $rp($h->total) }}</b>Total</div><div><b>{{ $rp($h->total - $h->sisa) }}</b>Dibayar</div></div>
<div class="bar"><i style="width:{{ $h->total ? ($h->total - $h->sisa) / $h->total * 100 : 0 }}%"></i></div><p style="margin-top:8px">Jatuh tempo {{ $h->jatuh_tempo->format('d M Y') }} · <span class="pill">{{ ucfirst($h->status) }}</span></p></div>
<div class="card w"><b>Riwayat pembayaran</b>@forelse($h->payments as $p)<div class="tl" style="margin-top:10px"><i>✓</i><div><b>{{ $rp($p->jumlah) }}</b><p>{{ $p->tanggal }} · {{ ucfirst($p->metode) }}</p></div></div>@empty<p class="sub">Belum ada pembayaran.</p>@endforelse</div>
@else<div class="card w"><b>Pembelian dibatalkan</b></div>@endif</div></div>
@endsection
