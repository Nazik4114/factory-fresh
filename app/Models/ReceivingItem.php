<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceivingItem extends Model
{

    protected $table = 'receiving_item';

    protected $fillable = [
        'receiving_id',
        'width',
        'height',
        'area',
        'price',
        'sum',
    ];

}
