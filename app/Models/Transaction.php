<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * MODUL TRANSAKSI: Barang Masuk & Barang Keluar.
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'user_id', 'jenis', 'jumlah', 'keterangan', 'tanggal'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
