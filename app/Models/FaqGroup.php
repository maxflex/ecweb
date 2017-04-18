<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Faq;

class FaqGroup extends Model
{
    public $fillable = ['faq'];

    public function faq()
    {
        return $this->hasMany(Faq::class, 'group_id')->orderBy('position', 'asc');
    }

    public static function getAll()
    {
        $groups = self::with('faq')->orderBy('position', 'asc')->get();
        $groups->add(new FaqGroup([
            'faq'   => Faq::whereNull('group_id')->orderBy('position', 'asc')->get()
        ]));

        return $groups;
    }
}
