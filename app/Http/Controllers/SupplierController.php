<?php
namespace App\Http\Controllers;

use App\Models\Supplier;

class SupplierController extends CrudController
{
    protected string $model = Supplier::class;
    protected string $title = 'Supplier';
    protected string $sub = 'Kelola data pemasok, kontak, dan rekening pembayaran.';
    protected string $route = 'suppliers';
    protected array $fields = ['kode' => ['Kode', 'text'], 'nama' => ['Nama supplier', 'text'], 'nama_kontak' => ['Nama kontak', 'text'], 'telepon' => ['Telepon', 'text'],
        'email' => ['Email', 'email'], 'bank' => ['Bank', 'text'], 'no_rekening' => ['No. rekening', 'text'], 'alamat' => ['Alamat', 'text'], 'status' => ['Status', 'select']];
    protected array $cols = ['kode' => 'Kode', 'nama' => 'Nama supplier', 'nama_kontak' => 'Kontak', 'telepon' => 'Telepon', 'status' => 'Status'];

    protected function options(): array { return ['status' => ['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif']]; }

    protected function rules(?int $id = null): array
    {
        return ['kode' => "required|unique:suppliers,kode,$id", 'nama' => 'required', 'nama_kontak' => 'nullable', 'telepon' => 'nullable', 'email' => 'nullable|email',
            'bank' => 'nullable', 'no_rekening' => 'nullable', 'alamat' => 'nullable', 'status' => 'required|in:aktif,nonaktif'];
    }
}
