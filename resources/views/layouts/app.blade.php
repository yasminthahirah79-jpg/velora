<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title', 'Velora') — Velora</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/velora.css') }}"></head><body>
@php
  $role = auth()->user()->role;
  $nav = [['Umum', 'dashboard', '▦', 'Dashboard', 'admin,pembelian,keuangan,pimpinan'], ['Umum', 'suppliers.index', '◍', 'Supplier', 'admin,pembelian'],
    ['Umum', 'products.index', '▣', 'Barang', 'admin,pembelian'], ['Umum', 'categories.index', '▤', 'Kategori', 'admin,pembelian'], ['Umum', 'purchases.index', '⊕', 'Pembelian', 'admin,pembelian'],
    ['Keuangan', 'payments.index', '◔', 'Hutang & Pembayaran', 'admin,keuangan'], ['Keuangan', 'kas.index', '◈', 'Kas', 'admin,keuangan'],
    ['Keuangan', 'laporan', '▥', 'Laporan', 'admin,keuangan,pimpinan'], ['Pengaturan', 'users.index', '☺', 'Pengguna', 'admin']];
  $group = null;
@endphp
<div class="frame"><aside><div class="logo">velora<i>.</i></div>
@foreach($nav as [$g, $rt, $ic, $lb, $roles])
  @if(in_array($role, explode(',', $roles)))
    @if($g !== $group)<small>{{ $g }}</small>@php $group = $g; @endphp @endif
    <a href="{{ route($rt) }}" class="{{ request()->routeIs(explode('.', $rt)[0] . '*') ? 'on' : '' }}"><span>{{ $ic }}</span>{{ $lb }}</a>
  @endif
@endforeach
<div class="sp"></div><form method="post" action="{{ route('logout') }}">@csrf<button>⇥ Keluar ({{ auth()->user()->name }})</button></form></aside>
<main>
@if(session('ok'))<div class="alert">{{ session('ok') }}</div>@endif
@if($errors->any())<div class="alert e">@foreach($errors->all() as $e){{ $e }} @endforeach</div>@endif
@yield('content')
</main></div></body></html>
