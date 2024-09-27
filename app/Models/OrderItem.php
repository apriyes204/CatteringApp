<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_details_id',
        'grand_price',
        'status',
        'address',
        'longtitude',
        'latitude',
        'shipping_price',
    ];

    public function itemdetail():HasMany {
        return $this->hasMany(OrderDetail::class);
    }
}
