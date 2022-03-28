<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model 
{
    protected $table = 'item';

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
}
