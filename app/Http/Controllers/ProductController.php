<?php
namespace App\Http\Controllers;

use App\Models\{Category, Product};

class ProductController extends CrudController
{
    protected string $model = Product::class;
    protected string $title = 'Barang';
    protected string $sub = 'Data barang, kategori, harga beli, dan stok (stok bertambah otomatis dari pembelian).';
    protected string $route = 'products';
    protected array $fields = ['kode' => ['Kode', 'text'], 'nama' => ['Nama barang', 'text'], 'category_id' => ['Kategori', 'select'], 'satuan' => ['Satuan', 'text'], 'harga_beli' => ['Harga beli', 'number']];
    protected array $cols = ['kode' => 'Kode', 'nama' => 'Nama barang', 'category_id' => 'Kategori', 'satuan' => 'Satuan', 'harga_beli' => 'Harga beli', 'stok' => 'Stok'];

    protected function options(): array { return ['category_id' => Category::pluck('nama', 'id')->all()]; }

    protected function rules(?int $id = null): array
    {
        return ['kode' => "required|unique:products,kode,$id", 'nama' => 'required', 'category_id' => 'required|exists:categories,id', 'satuan' => 'required', 'harga_beli' => 'required|numeric|min:0'];
    }
}
