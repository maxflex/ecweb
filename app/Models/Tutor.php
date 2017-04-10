<?php

namespace App\Models;

use App\Scopes\TutorScope;
use DB;
use App\Service\Cacher;

class Tutor extends Service\Model
{
    protected $connection = 'egerep';

    static $phone_fields = ['phone', 'phone2', 'phone3', 'phone4'];

    protected $appends = [
        'subjects_string',
        'subjects_string_common',
    ];

    const USER_TYPE  = 'TEACHER';

    const URL = 'tutors';

    protected $commaSeparated = ['subjects', 'grades', 'branches'];

    protected $multiLine = ['public_desc', 'education', 'achievements', 'experience', 'preferences'];

    public function departure()
    {
        return $this->hasMany(TutorDeparture::class);
    }

    public function markers()
    {
        return $this->morphMany(Marker::class, 'markerable')->where('type', 'green');
    }

    public function accounts()
    {
        return $this->hasMany(Account::class)->latest()->take(3);
    }

    public function plannedAccount()
    {
        return $this->hasOne(PlannedAccount::class);
    }

    public function getSubjectsStringAttribute()
    {
        return implode(', ', array_map(function($subject_id) {
            return Cacher::getSubjectName($subject_id, 'dative');
        }, $this->subjects));
    }

    public function getSubjectsStringCommonAttribute()
    {
        return implode(', ', array_map(function($subject_id) {
            return Cacher::getSubjectName($subject_id, 'name');
        }, $this->subjects));
    }

    public static function boot()
    {
        static::addGlobalScope(new TutorScope);
    }

    /**
     * Search tutors by params
     */
    public static function search($search)
    {
        @extract($search);

        $query = Tutor::query();

        if (isset($subject_id) && $subject_id) {
            $query->whereRaw("FIND_IN_SET($subject_id, subjects)");;
        }

        $query->selectDefault()->orderBy('clients_count', 'desc');

        return $query;
    }

    /**
     * @todo: проанализировать где какие поля используются и вынести в Global Scope
     */
    public function scopeSelectDefault($query)
    {
        return $query->select([
            'tutors.id',
            'first_name',
            'middle_name',
            'last_name',
            'subjects',
            'public_desc',
            'photo_extension',
            'start_career_year',
            'birth_year',
            'lesson_duration',
            'public_price',
            'departure_price',
            'comment_extended',
            'education',
            'achievements',
            'preferences',
            'experience',
            'tutoring_experience',
            'grades',
            'gender',
            'lk',
            'tb',
            'js',
            'video_link',
            'tutor_data.clients_count',
            'tutor_data.reviews_count_egecrm as reviews_count',
            'tutor_data.first_attachment_date',
            'tutor_data.review_avg',
            'tutor_data.svg_map',
            'tutor_data.photo_exists',
        ])->join('tutor_data', 'tutor_data.tutor_id', '=', 'tutors.id');
    }
}
