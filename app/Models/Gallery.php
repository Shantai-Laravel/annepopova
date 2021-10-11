<?php

namespace App\Models;

use App\Base as Model;

class Gallery extends Model
{
    protected $table = 'galleries';

    protected $fillable = ['alias'];

    public function MainImage()
    {
        return $this->hasOne(GalleryImage::class, 'gallery_id', 'id')->where('gallery_id', $this->id)->where('type', 'image');
    }

    public function Images()
    {
        return $this->hasMany(GalleryImage::class);
    }

}
