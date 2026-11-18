<?php
namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends CrudController
{
    protected string $model = Category::class;
    protected string $title = 'Kategori Barang';
    protected string $sub = 'Pengelompokan barang.';
    protected string $route = 'categories';
    protected array $fields = ['nama' => ['Nama kategori', 'text']];
    protected array $cols = ['nama' => 'Nama kategori'];

    protected function rules(?int $id = null): array { return ['nama' => 'required']; }
}
