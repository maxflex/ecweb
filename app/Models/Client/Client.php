<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use App\Models\NewPhoto;

class Client extends Model
{
    protected $connection = 'lk2';
    protected $appends = ['photo_url'];

    public function photo()
    {
        return $this->morphOne(NewPhoto::class, 'entity');
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? $this->photo->url : null;
    }
}
