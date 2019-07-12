<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewComment extends Model
{
    protected $connection = 'lk2';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('review-comments-scope', function ($query) {
           $query->where('type', 'final'); 
        });
    }
}
