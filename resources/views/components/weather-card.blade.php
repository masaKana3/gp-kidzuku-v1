<!-- 天気情報ウィジェット -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="widget-title">天気情報</h3>
    @if(isset($weather['weather']))
        <div class="mb-2 text-sm text-gray-500">
            {{ \Carbon\Carbon::now()->format('n月j日 H:i') }} ｜ {{ $weather['name'] ?? '不明' }}, {{ $weather['sys']['country'] ?? 'JP' }}
        </div>
        <div class="flex items-center space-x-4 mb-4">
            <img src="http://openweathermap.org/img/wn/{{ $weather['weather'][0]['icon'] }}@2x.png" alt="天気アイコン">
            <div>
                <p class="text-xl font-bold">{{ $weather['main']['temp'] }}℃</p>
                <p class="text-sm text-gray-600">{{ $weather['weather'][0]['description'] }}</p>
                <p class="text-sm">体感温度：{{ $weather['main']['feels_like'] }}℃</p>
                <p class="text-sm">湿度：{{ $weather['main']['humidity'] }}%｜風速：{{ $weather['wind']['speed'] }} m/s</p>
                <p class="text-sm">気圧：{{ $weather['main']['pressure'] }} hPa｜視程：{{ $weather['visibility'] / 1000 }} km</p>
            </div>
        </div>
        
        <!-- 天気予報（気圧グラフ用エリア）を内包 -->
        <div class="border-t pt-4">
            <!-- <h4 class="text-lg font-bold mb-2">天気予報</h4> -->
            <x-pressure-alert :pressures="$pressures" />
        </div>
    @else
        <p class="text-sm text-red-500">天気情報を取得できませんでした。</p>
    @endif
</div>