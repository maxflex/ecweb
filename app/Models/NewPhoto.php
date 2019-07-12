<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewPhoto extends Model
{
    protected $connection = 'lk2';
    protected $table = 'photos';

    // в этом формате сохраняются все фотки
    const EXTENSION = 'jpg';

    public function getUrlAttribute()
    {
        return config('app.lk2-url') . 'storage/img/avatar/' . $this->id . '_cropped.' . self::EXTENSION;
    }

    // public static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope('photos-scope', function ($query) {
    //         $query->where('has_cropped', 1);
    //     });
    // }
}
