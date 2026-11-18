@extends('layouts.app')
@section('title', 'Catat Pembelian')
@section('content')
<div class="top"><div><h1>Catat Pembelian</h1><p class="sub">Setelah disimpan, stok bertambah dan tagihan hutang terbentuk otomatis.</p></div></div>
<form method="post" action="{{ route('purchases.store') }}" class="card w">@csrf
<div class="f"><div><label>Supplier</label><select name="supplier_id">@foreach($suppliers as $s)<option value="{{ $s->id }}" @selected(old('supplier_id') == $s->id)>{{ $s->nama }}</option>@endforeach</select></div>
<div><label>No. faktur supplier</label><input name="no_faktur" value="{{ old('no_faktur') }}" required></div>
<div><label>Tanggal pembelian</label><input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}"></div>
<div><label>Jatuh tempo pembayaran</label><input type="date" name="jatuh_tempo" value="{{ old('jatuh_tempo', now()->addDays(14)->toDateString()) }}"></div></div>
<h2>Item barang <button type="button" class="btn l" onclick="add()">+ Tambah baris</button></h2>
<div class="row h f3"><span>Barang</span><span>Jumlah</span><span>Harga</span><span>Subtotal</span></div><div id="items"></div>
<div class="tot"><span>Total pembelian</span><span id="tot">Rp 0</span></div>
<div style="display:flex;gap:10px;justify-content:flex-end"><a class="btn l" style="text-decoration:none" href="{{ route('purchases.index') }}">Batal</a><button class="btn">Simpan Pembelian</button></div></form>
<script>
const P=@json($products->map(fn($p)=>['id'=>$p->id,'n'=>$p->nama,'h'=>(float)$p->harga_beli]));let i=0;
function add(){const d=document.createElement('div');d.className='f3';d.innerHTML=`<select name="items[${i}][product_id]" onchange="pick(this)">${P.map(p=>`<option value="${p.id}" data-h="${p.h}">${p.n}</option>`).join('')}</select><input type="number" min="1" name="items[${i}][qty]" value="1" oninput="calc()"><input type="number" step="any" min="0" name="items[${i}][harga]" value="${P[0]?.h||0}" oninput="calc()"><span></span>`;document.getElementById('items').append(d);i++;calc()}
function pick(s){s.parentNode.children[2].value=s.selectedOptions[0].dataset.h;calc()}
function calc(){let t=0;document.querySelectorAll('#items .f3').forEach(r=>{const s=r.children[1].value*r.children[2].value;r.children[3].textContent='Rp '+s.toLocaleString('id-ID');t+=s});document.getElementById('tot').textContent='Rp '+t.toLocaleString('id-ID')}
add();
</script>
@endsection
