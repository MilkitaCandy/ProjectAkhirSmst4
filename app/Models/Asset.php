<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = ['nama_aset', 'serial_number', 'category_id', 'location_id', 'status'];

    // Relasi ke Master Kategori
    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Relasi ke Master Lokasi
    public function location() {
        return $this->belongsTo(Location::class, 'location_id');
    }
}