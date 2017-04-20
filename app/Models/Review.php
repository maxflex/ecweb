<?php

namespace App\Models;

use App\Scopes\ReviewScope;
use Illuminate\Database\Eloquent\Model;
use DB;

class Review extends Model
{
    protected $connection = 'egecrm';
    protected $table = 'teacher_reviews';

    protected $appends = ['grade'];

    public function getGradeAttribute()
    {
        // dump([$this->id_teacher, $this->id_student, $this->id_subject, $this->year]);
        return egecrm('visit_journal')->where('id_teacher', $this->id_teacher)->where('id_entity', $this->id_student)
            ->where('id_subject', $this->id_subject)->where('year', $this->year)->where('type_entity', 'STUDENT')->value('grade');
    }

    public static function boot()
    {
        static::addGlobalScope(new ReviewScope);
    }
}
