<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'item_id', 'item_type', 'quantity', 'price'];

    public function item(): MorphTo
    {
        return $this->morphTo();
    }
}
