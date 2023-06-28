<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryItem extends Model
{


    protected $table = 'delivery_items';

    protected $fillable = [
        'deliveries_id',
        'width',
        'height',
        'area',
        'price',
        'sum',
    ];


}
