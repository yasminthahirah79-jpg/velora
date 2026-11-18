<?php
namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

abstract class CrudController extends Controller
{
    protected string $model;
    protected string $title;
    protected string $sub = '';
    protected string $route;
    protected string $search = 'nama';
    protected array $fields = []; // kolom => [label, tipe]
    protected array $cols = [];   // kolom => label (tabel)

    protected function rules(?int $id = null): array { return []; }
    protected function options(): array { return []; }
    protected function data(Request $r, ?int $id = null): array { return $r->validate($this->rules($id)); }

    public function index(Request $r)
    {
        $items = ($this->model)::query()->when($r->q, fn ($q, $s) => $q->where($this->search, 'like', "%$s%"))->latest('id')->paginate(15)->withQueryString();
        return view('crud', ['items' => $items, 'cfg' => ['title' => $this->title, 'sub' => $this->sub, 'route' => $this->route,
            'fields' => $this->fields, 'cols' => $this->cols, 'opts' => $this->options()]]);
    }

    public function store(Request $r) { ($this->model)::create($this->data($r)); return back()->with('ok', 'Data tersimpan.'); }

    public function update(Request $r, int $id) { ($this->model)::findOrFail($id)->update($this->data($r, $id)); return back()->with('ok', 'Data diperbarui.'); }

    public function destroy(int $id)
    {
        try { ($this->model)::findOrFail($id)->delete(); } catch (QueryException) { return back()->withErrors('Data tidak dapat dihapus karena sudah dipakai.'); }
        return back()->with('ok', 'Data dihapus.');
    }
}
