<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'phone_number',
        'description',
        'address',
        'longtitude',
        'latitude'
    ];

    public function pembeli():HasMany{
        return $this->hasMany(Menu::class);
    }

    public function menu():BelongsTo {
        return $this->belongsTo(User::class);
    }

}
