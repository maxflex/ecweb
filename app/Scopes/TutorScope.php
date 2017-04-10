<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TutorScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        return $builder->where('in_egecentr', 2);
    }
}
