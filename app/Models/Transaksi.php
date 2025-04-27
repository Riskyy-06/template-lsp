<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = ['total_harga'];

    use HasFactory;

    public function items()
    {
        return $this->hasMany(ItemTransaksi::class);
    }
}
