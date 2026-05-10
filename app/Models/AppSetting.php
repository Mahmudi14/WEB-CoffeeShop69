<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $guarded = ['id'];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        return static::where('key', $key)->value('value') ?? $default;
    }
}