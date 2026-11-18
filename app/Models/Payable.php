<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payable extends Model
{
    protected $guarded = [];
    protected $casts = ['jatuh_tempo' => 'date'];
    public function purchase() { return $this->belongsTo(Purchase::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function payments() { return $this->hasMany(Payment::class); }
    public function getSisaAttribute() { return $this->total - $this->payments()->sum('jumlah'); }
}
