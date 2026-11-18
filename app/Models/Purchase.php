<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = [];
    protected $casts = ['tanggal' => 'date', 'jatuh_tempo' => 'date'];
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function items() { return $this->hasMany(PurchaseItem::class); }
    public function payable() { return $this->hasOne(Payable::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
