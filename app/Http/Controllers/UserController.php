<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends CrudController
{
    protected string $model = User::class;
    protected string $title = 'Pengguna';
    protected string $sub = 'Kelola akun dan peran pengguna Velora.';
    protected string $route = 'users';
    protected string $search = 'name';
    protected array $fields = ['name' => ['Nama', 'text'], 'email' => ['Email', 'email'], 'role' => ['Peran', 'select'], 'password' => ['Kata sandi', 'password']];
    protected array $cols = ['name' => 'Nama', 'email' => 'Email', 'role' => 'Peran'];

    protected function options(): array { return ['role' => ['admin' => 'Admin', 'pembelian' => 'Staf Pembelian', 'keuangan' => 'Staf Keuangan', 'pimpinan' => 'Pimpinan']]; }

    protected function rules(?int $id = null): array
    {
        return ['name' => 'required', 'email' => "required|email|unique:users,email,$id", 'role' => 'required|in:admin,pembelian,keuangan,pimpinan',
            'password' => $id ? 'nullable|min:6' : 'required|min:6'];
    }

    protected function data(Request $r, ?int $id = null): array
    {
        $d = parent::data($r, $id);
        if (empty($d['password'])) unset($d['password']); // di-hash otomatis oleh cast pada model User
        return $d;
    }
}
