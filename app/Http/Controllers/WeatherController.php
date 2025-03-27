<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\WeatherInfo;
use App\Models\Schedule;
use App\Models\ChildSurvey;
use App\Models\DailyConditionRecord;

class WeatherController extends Controller
{
    public function dashboard()
    {
        $apiKey = config('services.openweather.key');
        $zipcode = Auth::user()->area ?? '1000001';

        // 位置情報取得
        $geoUrl = "http://api.openweathermap.org/geo/1.0/zip?zip={$zipcode},JP&appid={$apiKey}";
        $geoData = Http::get($geoUrl)->json();
        $lat = $geoData['lat'] ?? 35.6895;
        $lon = $geoData['lon'] ?? 139.6917;
        $placeName = $geoData['name'] ?? '不明な地域';

        // 現在の天気
        $weatherUrl = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric&lang=ja";
        $weather = Http::get($weatherUrl)->json();

        // 天気保存
        WeatherInfo::create([
            'user_id' => Auth::id(),
            'location_name' => $placeName,
            'temperature' => $weather['main']['temp'],
            'feels_like' => $weather['main']['feels_like'],
            'humidity' => $weather['main']['humidity'],
            'wind_speed' => $weather['wind']['speed'],
            'pressure' => $weather['main']['pressure'],
            'visibility' => $weather['visibility'],
            'weather_time' => now('Asia/Tokyo'),
        ]);

        // 5日間予報
        $forecastUrl = "https://api.openweathermap.org/data/2.5/forecast?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric&lang=ja";
        $forecast = Http::get($forecastUrl)->json();

        $pressures = collect($forecast['list'])->map(function ($item) {
            return [
                'time' => $item['dt_txt'],
                'pressure' => $item['main']['pressure'],
                'temp' => $item['main']['temp'],
                'weather' => $item['weather'][0]['description'],
                'icon' => $item['weather'][0]['icon'],
            ];
        });

        $today = date('Y-m-d');
        $todayEvents = Schedule::where('user_id', Auth::id())
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderBy('start_date', 'asc')
            ->get();
            
        // 子どもの情報を取得
        $children = ChildSurvey::where('user_id', Auth::id())->get();
        
        // 今日の体調記録を取得
        $todayConditions = [];
        foreach ($children as $child) {
            $condition = DailyConditionRecord::where('child_survey_id', $child->id)
                ->where('record_date', $today)
                ->first();
                
            $todayConditions[$child->id] = $condition;
        }

        return view('dashboard', [
            'weather' => $weather,
            'placeName' => $placeName,
            'pressures' => $pressures,
            'todayEvents' => $todayEvents,
            'children' => $children,
            'todayConditions' => $todayConditions,
        ]);
    }

    public function weather()
    {
        $apiKey = config('services.openweather.key');
        $zipcode = Auth::user()->area ?? '1000001';

        $geoUrl = "http://api.openweathermap.org/geo/1.0/zip?zip={$zipcode},JP&appid={$apiKey}";
        $geoData = Http::get($geoUrl)->json();
        $lat = $geoData['lat'] ?? 35.6895;
        $lon = $geoData['lon'] ?? 139.6917;
        $placeName = $geoData['name'] ?? '不明な地域';

        $forecastUrl = "https://api.openweathermap.org/data/2.5/forecast?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric&lang=ja";
        $forecast = Http::get($forecastUrl)->json();

        $pressures = collect($forecast['list'])->map(function ($item) {
            return [
                'time' => $item['dt_txt'],
                'pressure' => $item['main']['pressure'],
                'temp' => $item['main']['temp'],
                'weather' => $item['weather'][0]['description'],
                'icon' => $item['weather'][0]['icon'],
            ];
        });

        return view('weather', [
            'pressures' => $pressures,
            'placeName' => $placeName,
        ]);
    }
}
