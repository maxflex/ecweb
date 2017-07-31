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
    public static function getStudent($limit = 2, $min_score = null, $grade = null, $subject_id = null)
    {
        $query = self::withStudent()->where('users.photo_extension', '<>', '')->take($limit)->inRandomOrder();

        if ($min_score) {
            @list($min_score_ege, $min_score_oge) = explode(',', $min_score);
            if ($min_score_oge) {
                $query->whereRaw("((teacher_reviews.score >= {$min_score_ege} AND teacher_reviews.grade=11) OR (teacher_reviews.score >= {$min_score_oge} AND teacher_reviews.grade=9))");
            } else {
                $query->where('teacher_reviews.score', '>=', $min_score);
            }
        }

        if ($grade) {
            $query->where('teacher_reviews.grade', '=', $grade);
        }

        if ($subject_id) {
            $query->where('teacher_reviews.id_subject', '=', $subject_id);
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
