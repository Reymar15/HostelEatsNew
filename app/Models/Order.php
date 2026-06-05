<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'branch_id',
        'customer_name',
        'food_item',
        'quantity',
        'total',
        'status',
        'notes',
        'order_date',
    ];

    protected function casts(): array
    {
        return [
            'total'      => 'decimal:2',
            'order_date' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
