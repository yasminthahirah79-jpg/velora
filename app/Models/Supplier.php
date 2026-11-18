<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];
    public function purchases() { return $this->hasMany(Purchase::class); }
    public function payables() { return $this->hasMany(Payable::class); }
}
