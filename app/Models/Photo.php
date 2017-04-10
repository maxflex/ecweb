<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    public $timestamps = false;
    const STORAGE_DIR = 'storage/images/';

    public static function parse($ids)
    {
        $photos = Photo::whereIn('id', $ids)->get();
        $urls = [];
        foreach($photos as $photo) {
            $urls[] = (object)[
                'id' => $photo->id,
                'url' => config('app.crm-url') . self::STORAGE_DIR . $photo->filename
            ];
        }
        return view('gallery.index')->with(compact('urls'));
    }
}
