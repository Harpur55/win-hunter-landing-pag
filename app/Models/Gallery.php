<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'gallery'; 
    protected $fillable = ['title', 'description','images_path','status'];

      protected $casts = [
        'images_path' => 'array',
    ];
    
    // Jika Anda menggunakan penyimpanan file Laravel, Anda mungkin ingin menambahkan accessor untuk mendapatkan URL gambar
   public function getImagesUrlAttribute()
    {
        if (is_array($this->images_path)) {
            return collect($this->images_path)
                ->map(fn ($img) => asset('storage/' . $img))
                ->toArray();
        }

        return [];
    }
}



