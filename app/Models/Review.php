<?php

namespace App\Models;

use App\Scopes\ReviewScope;
use Illuminate\Database\Eloquent\Model;
use DB;

class Review extends Model
{
    protected $connection = 'egecrm';
    protected $table = 'teacher_reviews';

    public static function scopeDefaultSelect($query)
    {
        return $query->select(DB::raw('admin_comment_final as comment, IF(admin_rating_final=6, 0, admin_rating_final) as rating,
            score, signature, id_subject, id_teacher, year'));
    }

    public static function boot()
    {
        static::addGlobalScope(new ReviewScope);
    }
}
