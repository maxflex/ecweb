<?php

namespace App\Models;

use Shared\Model;
use App\Models\Service\Parser;

class Variable extends Model
{
    protected $attributes = [
        'name' => 'новая переменная'
    ];

    protected $fillable = [
        'name',
        'html',
        'desc'
    ];

    public function shallowReplace($field, $values)
    {
        foreach($values as $value) {
            list($var_name, $var_val) = explode('=', $value);
            Parser::replace($this->attributes[$field], $var_name, $var_val, Parser::START_VAR_CALC, Parser::END_VAR_CALC);
        }
    }

    public function getHtmlAttribute($value)
    {
        return Parser::compileVars($value);
    }

    public function scopeFindByName($query, $name)
    {
        return $query->where('name', $name);
    }

    public static function display($name, $useful_block = false)
    {
        if (isMobile() && self::findByName($name . '-mobile')->exists()) {
            $name .= '-mobile';
        }
        $html = self::findByName($name)->first()->html;
        if (! $useful_block) {
            Parser::replace($html, 'useful', '');
        }
        return $html;
    }
}
