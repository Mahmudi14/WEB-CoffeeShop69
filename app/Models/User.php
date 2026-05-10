<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }
    public function cashierShifts()
    {
        return $this->hasMany(\App\Models\CashierShift::class);
    }

    public function activeCashierShift()
    {
        return $this->hasOne(\App\Models\CashierShift::class)
            ->where('status', \App\Models\CashierShift::STATUS_OPEN);
    }
}