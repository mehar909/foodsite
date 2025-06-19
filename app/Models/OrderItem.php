<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\MenuItem;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'menu_item_id', 'quantity'];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
