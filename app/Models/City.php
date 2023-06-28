<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

    protected $fillable = [
        'name',
    ];

    public function hours()
    {
        return $this->hasMany(CityHour::class, 'city_id');
    }

    public function getGroupedHours()
    {

        return $this->hours->groupBy('day');
    }

    public function availableDays()
    {
        $days = [];
        $daysNumbers = $this->hours->pluck('day')->unique()->all();
        foreach ($daysNumbers as $dayNumber) {
            $days [] = [
                'day' => $dayNumber,
                'name' => trans('cities_hours.days.' . $dayNumber),
                'hours' => $this->hours->where('day', $dayNumber)
            ];
        }
        return $days;
    }
}
