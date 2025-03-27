<x-guest-layout>
    <div class="max-w-4xl mx-auto py-10 px-4">
        <h2 class="text-2xl font-bold mb-4">{{ $placeName }} の天気予報（気圧グラフ）</h2>

        <!-- グラフ表示コンポーネント -->
        <x-pressure-chart :pressures="$pressures" />

        <!-- 3時間ごとの天気アイコン一覧（おまけ・あとで拡張可能） -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold mb-2">3時間ごとの天気</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                @foreach($pressures->take(15) as $item)
                    <div class="border rounded p-2 text-center text-sm">
                        <div>{{ \Carbon\Carbon::parse($item['time'])->format('M/d H:i') }}</div>
                        <img src="http://openweathermap.org/img/wn/{{ $item['icon'] }}@2x.png" alt="icon" class="mx-auto w-10 h-10">
                        <div>{{ $item['weather'] }}</div>
                        <div class="text-xs text-gray-500">気温: {{ $item['temp'] }}℃</div>
                        <div class="text-xs text-gray-500">気圧: {{ $item['pressure'] }} hPa</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Chart.js 読み込み -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</x-guest-layout>