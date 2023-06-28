<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryPointLocality extends Model
{

    protected $table = 'delivery_point_localities';

    protected $fillable = [
        'delivery_point_id',
        'name'
    ];


    public function deliveryPoint(){
        return $this->belongsTo(DeliveryPoint::class,'delivery_point_id');
    }



}
