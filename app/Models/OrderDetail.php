<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'menus_id',
        'users_id',
        'amount',
        'price',
        'total_price',
    ];

    public function menu(): BelongsToMany{
        return $this->belongsToMany(Menu::class);
    }

    public function pembeli(): BelongsToMany  {
        return $this->belongsToMany(User::class);
    }
    public function orderItem():BelongsTo {
        return $this->belongsTo(OrderItem::class);
    }
}
