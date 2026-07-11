<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara mass-assignment (dipakai oleh
     * ItemController::store() dan ItemController::update()).
     */
    protected $fillable = ['nama', 'kode', 'stok', 'category_id'];

    public function items() {
        return $this->hasMany(Category::class);
    }
    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }
}