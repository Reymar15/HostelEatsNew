<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'logo'
    ];

    public function foods()
    {
        return $this->hasMany(Food::class);
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    public function admins()
    {
        return $this->hasMany(\App\Models\User::class);
    }
}
