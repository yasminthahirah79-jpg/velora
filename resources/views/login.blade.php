<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Masuk — Velora</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/velora.css') }}"></head><body>
<div class="frame login"><div class="lg"><div class="l"><div class="logo" style="font-size:30px">velora<i>.</i></div>
<div><h1>Supplier, pembelian,<br>dan keuangan<br>dalam satu tempat.</h1><p style="color:#aaa;margin-top:14px;max-width:340px">Catat pembelian, pantau hutang, dan lihat laporan tanpa buku catatan.</p></div><span></span></div>
<form class="r" method="post" action="{{ route('login') }}">@csrf<h1>Selamat datang</h1><p class="sub">Masuk untuk melanjutkan ke dashboard Velora.</p>
@if($errors->any())<div class="alert e">{{ $errors->first() }}</div>@endif
<div><label style="font-size:11px;color:var(--mut)">Email</label><input type="email" name="email" value="{{ old('email') }}" required autofocus></div>
<div><label style="font-size:11px;color:var(--mut)">Kata sandi</label><input type="password" name="password" required></div>
<button class="btn" style="padding:13px">Masuk</button></form></div></div></body></html>
