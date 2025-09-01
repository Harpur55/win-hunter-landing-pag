<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $table = 'gallery_images';
    protected $fillable = ['gallery_id', 'image_path'];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
    
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}
