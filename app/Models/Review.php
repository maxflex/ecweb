<?php

namespace App\Models;

use App\Scopes\ReviewScope;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $connection = 'egecrm';
    protected $table = 'teacher_reviews';

    public static function boot()
    {
        static::addGlobalScope(new ReviewScope);
    }
}
