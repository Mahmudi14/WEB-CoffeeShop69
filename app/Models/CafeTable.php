<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CafeTable extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'table_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}