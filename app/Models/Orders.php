<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model 
{
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public function order_detail()
    {
        return $this->hasMany(Order_detail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
