<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;


class CartItem extends Model
{
    protected $fillable = ['user_id', 'item_id', 'item_type', 'quantity'];

    public function item(): MorphTo
    {
        return $this->morphTo();
    }
}
