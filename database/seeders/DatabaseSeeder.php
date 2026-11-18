<?php
namespace Database\Seeders;

use App\Models\{Category, Product, Supplier, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin' => 'Admin', 'pembelian' => 'Staf Pembelian', 'keuangan' => 'Staf Keuangan', 'pimpinan' => 'Pimpinan'] as $role => $nama) {
            User::create(['name' => $nama, 'email' => "$role@velora.com", 'password' => Hash::make('password'), 'role' => $role]);
        }
        $pokok = Category::create(['nama' => 'Bahan Pokok']);
        $tekstil = Category::create(['nama' => 'Tekstil']);
        foreach ([['SUP-001', 'CV Sumber Makmur', 'Budi Santoso'], ['SUP-002', 'PT Aneka Pangan Nusantara', 'Rina Lestari'], ['SUP-003', 'PT Mitra Tekstil', 'Dewi Anggraini']] as [$k, $n, $c]) {
            Supplier::create(['kode' => $k, 'nama' => $n, 'nama_kontak' => $c]);
        }
        foreach ([['BRG-001', 'Beras Premium 5 kg', $pokok, 'Karung', 68000], ['BRG-002', 'Minyak Goreng 2 L', $pokok, 'Botol', 34500], ['BRG-003', 'Kain Katun Putih', $tekstil, 'Meter', 22000]] as [$k, $n, $c, $s, $h]) {
            Product::create(['kode' => $k, 'nama' => $n, 'category_id' => $c->id, 'satuan' => $s, 'harga_beli' => $h]);
        }
    }
}
