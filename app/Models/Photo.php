<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    public $timestamps = false;
    const STORAGE_DIR = 'storage/images/';

    public $appends = [
        'url'
    ];

    public function getUrlAttribute()
    {
        return config('app.crm-url') . self::STORAGE_DIR . $this->filename;
    }

    public static function parse($ids)
    {
        return view('gallery.index')->with([
            'urls' => Photo::whereIn('id', $ids)->orderBy('position')->get()
        ]);
    }
}
