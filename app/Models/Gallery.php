<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

   public function scopeActive($query)
{
    return $query->where('status', 'aktif');
}

protected static function booted()
{
    static::updating(function ($gallery) {
        if ($gallery->isDirty('images_path')) {
            foreach ($gallery->getOriginal('images_path') ?? [] as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
        }
    });

    static::deleting(function ($gallery) {
        foreach ($gallery->images_path ?? [] as $image) {
            Storage::disk('public')->delete($image);
        }
    });
}
}



