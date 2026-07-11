<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * MODUL DATA MASTER: Kategori Barang.
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kategori'];

    public function category() {
        return $this->belongsTo(Item::class);
    }
}
