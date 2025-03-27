<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'location_name',
        'temperature',
        'feels_like',
        'humidity',
        'wind_speed',
        'pressure',
        'visibility',
        'weather_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}