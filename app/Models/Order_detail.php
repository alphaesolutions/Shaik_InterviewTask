<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model 
{
    protected $table = 'order_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public function order()
    {
        return $this->belongsTo(Orders::class);
    }

    
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
