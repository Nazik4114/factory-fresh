<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryPoint extends Model
{

    protected $table = 'delivery_points';

    protected $fillable = [
        'name',
        'order',
        'address',
        'city_id'
    ];


    public function hours(){
        return $this->hasMany(DeliveryPointHour::class,'delivery_point_id');
    }

    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }

    public function localities(){
        return $this->hasMany(DeliveryPointLocality::class, 'delivery_point_id');
    }


    public function getGroupedHours(){

        return $this->hours->groupBy('day');
    }


    public function availableDays(){
        $days = [];
        $daysNumbers = $this->hours->pluck('day')->unique()->all();
        foreach ( $daysNumbers as $dayNumber){
            $days [] = [
                'day' => $dayNumber,
                'name' => trans('delivery_point_hours.days.'.$dayNumber),
                'hours' => $this->hours->where('day',$dayNumber )
            ];
       }
        return $days;
    }


}
