<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Service\Cacher;
use App\Service\Months;
use Cache;
use DB;

class NewReview extends Model
{
    protected $connection = 'lk2';
    protected $table = 'reviews';

    protected $appends = ['subject_string', 'date_string'];
    protected $with = ['comment', 'client'];

    public function comment()
    {
        return $this->hasOne(ReviewComment::class, 'review_id');
    }

    public function client()
    {
        return $this->belongsTo(Client\Client::class);
    }
    
    public function getSubjectStringAttribute()
    {
        $subject_id = $this->attributes['subject_id'];
        return Cache::remember(cacheKey('subject-dative', $subject_id), 60 * 24, function() use ($subject_id) {
            return Cacher::getSubjectName($subject_id, 'dative');
        });
    }

    public function getDateStringAttribute()
    {
        $date = $this->comment->created_at;
        return date('j ', strtotime($date)) . Months::SHORT[date('n', strtotime($date))] . date(' Y', strtotime($date));
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('reviews-scope', function ($query) {
           $query->where('is_published', 1); 
        });
    }
}
