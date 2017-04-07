<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    const PAGE_URL = 'program';

    protected $casts = [
        'content' => 'collection',
    ];
}
