<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programm extends Model
{
    const URL = 'programm';

    protected $casts = [
        'content' => 'collection',
    ];
}
