<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class DeliveryPointHour extends Model
{
    public const  MONDAY = 1;
    public const TUESDAY  = 2;
    public const  WEDNESDAY = 3;
    public const  THURSDAY = 4;
    public const  FRIDAY = 5;
    public const  SATURDAY = 6;
    public const  SUNDAY = 7;

    protected $table = 'delivery_point_hours';

    protected $primaryKey = 'id';

    protected $fillable = [
        'delivery_point_id',
        'day',
        'start',
        'finish',

    ];
   public static function getDays(){
        return [
            self::MONDAY,
            self::TUESDAY,
            self::WEDNESDAY,
            self::THURSDAY,
            self::FRIDAY,
            self::SATURDAY,
            self::SUNDAY,
        ];
    }



    public static function getDaysForSelect(){
       $days = collect();
       foreach (self::getDays() as $day){
           $days[$day] = trans('delivery_point_hours.days.'.$day);
       }
       return  $days;

    }



}
