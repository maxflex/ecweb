<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TutorScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // Отсеять преподов:
        // поле "подпись под фото на сайте ЕГЭ-Центра" пусто
        // поле опубликованное описание на сайте ЕГЭ-Центра" пусто
        // отсутствует обрезанное фото
        return $builder
            ->where('in_egecentr', 2)
            ->where('description', '<>', '')
            ->where('photo_desc', '<>', '')
            ->whereRaw('(select photo_exists from tutor_data where tutor_data.tutor_id = tutors.id) = 1');
    }
}
