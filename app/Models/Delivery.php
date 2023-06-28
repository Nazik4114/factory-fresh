<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    public const PENDING = 'pending';
    public const RECEIVED = 'received';
    public const CLEANING = 'cleaning';
    public const DONE = 'done';
    public const GIVEN_TO_CLIENT = 'given_to_client';
    public const CANCELED = 'canceled';
    public const ORDER_TYPE_ONLINE = 'online';
    public const ORDER_TYPE_RECEPTION = 'reception';

    protected $table = 'deliveries';

    protected $fillable = [
        'delivery_point_id',
        'delivery_point_locality_id',
        'delivery_point_hour_id',
        'phone',
        'first_name',
        'last_name',
        'street',
        'house',
        'entrance',
        'apartment',
        'comment',
        'day',
        'delivery_price',
        'start',
        'finish',
        'qty',
        'total_area',
        'total_items_amount',
        'overlock_price',
        'repair_price',
        'repair',
        'total_price',
        'overlock_length',
        'status',
        'order_type',
        'issuance_day',
        'issuance_point_hour_id',
        'issuance_start',
        'issuance_finish',
        'issuance_date',
        'payment_method',
        'sum',
        'city_id'
    ];

    protected $casts = [
        'repair' => 'boolean'
    ];

    public static function getStatusName($status)
    {
        switch ($status) {
            case "pending":
                echo "В очікуванні";
                return;
            case "received":
                echo "Отримано";
                return;
            case "cleaning":
                echo "В чистці";
                return;
            case "done":
                echo "Готово";
                return;
            case "given_to_client":
                echo "Видано";
                return;
            case "canceled":
                echo "Скасовано";
                return;
        }
    }

    public function deliveryPoint()
    {
        return $this->belongsTo(DeliveryPoint::class, 'delivery_point_id');
    }

    public function locality()
    {
        return $this->belongsTo(DeliveryPointLocality::class, 'delivery_point_locality_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function items()
    {
        return $this->hasMany(DeliveryItem::class, 'deliveries_id');
    }

    public function deliveryPointHour()
    {
        return $this->hasMany(DeliveryPointHour::class, 'delivery_point_hour_id');
    }


    public static function getStatusList()
    {
        return [
            self::PENDING,
            self::RECEIVED,
            self::CLEANING,
            self::DONE,
            self::GIVEN_TO_CLIENT,
            self::CANCELED,
        ];
    }

    public static function getTypeList()
    {
        return [
            self::ORDER_TYPE_ONLINE,
            self::ORDER_TYPE_RECEPTION
        ];
    }
}
