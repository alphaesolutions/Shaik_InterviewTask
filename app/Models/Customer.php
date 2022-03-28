<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model 
{
    protected $table = 'customer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public function order()
    {
        return $this->hasMany(Orders::class);
    }
}
