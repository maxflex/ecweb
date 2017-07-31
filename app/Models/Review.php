<?php

namespace App\Models;

use App\Scopes\ReviewScope;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $connection = 'egecrm';
    protected $table = 'teacher_reviews';

    /**
     * Отзывы учеников с фотографиями
     */
    public static function getStudent($limit = 2, $min_score = null, $grade = null)
    {
        $query = self::withStudent()->where('users.photo_extension', '<>', '')->take($limit)->inRandomOrder();

        if ($min_score) {
            $query->where('teacher_reviews.score', '>=', $min_score);
        }

        if ($grade) {
            $query->where('teacher_reviews.grade', '=', $grade);
        }

        return $query->get();
    }

    public function scopeWithStudent($query)
    {
        return $query->join('students', 'students.id', '=', 'teacher_reviews.id_student')
            ->join('users', function($join) {
                $join->on('users.id_entity', '=', 'students.id')->on('users.type', '=', \DB::raw("'STUDENT'"));
            })
            ->addSelect('students.first_name as student_first_name',
                        'students.last_name as student_last_name',
                        'students.middle_name as student_middle_name',
                        'users.photo_extension');
    }

    public static function boot()
    {
        static::addGlobalScope(new ReviewScope);
    }
}
