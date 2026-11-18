@extends('layouts.app')
@section('title', $cfg['title'])
@section('content')
<div class="top"><div><h1>{{ $cfg['title'] }}</h1><p class="sub">{{ $cfg['sub'] }}</p></div>
<form class="search" method="get"><b>⌕</b><input name="q" value="{{ request('q') }}" placeholder="Cari..."></form></div>
<details class="card w" style="margin-bottom:14px;min-height:0"><summary class="btn" style="display:inline-block">+ Tambah</summary>
<form method="post" action="{{ route($cfg['route'] . '.store') }}" style="margin-top:14px">@csrf @include('_fields', ['cfg' => $cfg, 'item' => null])
<div style="text-align:right;margin-top:12px"><button class="btn">Simpan</button></div></form></details>
@php $g = 'repeat(' . count($cfg['cols']) . ',1fr) 60px'; @endphp
<div class="row h" style="grid-template-columns:{{ $g }}">@foreach($cfg['cols'] as $l)<span>{{ $l }}</span>@endforeach<span></span></div>
@foreach($items as $it)
<details><summary class="row" style="grid-template-columns:{{ $g }}">@foreach($cfg['cols'] as $k => $l)
<span>@if($k == 'harga_beli')Rp {{ number_format($it->$k, 0, ',', '.') }}@else{{ $cfg['opts'][$k][$it->$k] ?? $it->$k }}@endif</span>@endforeach<span class="pill">Edit</span></summary>
<div class="card w" style="min-height:0;margin-bottom:8px"><form method="post" action="{{ route($cfg['route'] . '.update', $it->id) }}">@csrf @method('PUT')
@include('_fields', ['cfg' => $cfg, 'item' => $it])<button class="btn" style="margin-top:12px">Perbarui</button></form>
<form method="post" action="{{ route($cfg['route'] . '.destroy', $it->id) }}" onsubmit="return confirm('Hapus data ini?')" style="margin-top:8px">@csrf @method('DELETE')<button class="btn l">Hapus</button></form></div></details>
@endforeach
<div style="margin-top:12px">{{ $items->onEachSide(1)->links('pagination::simple-default') }}</div>
@endsection
