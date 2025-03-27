<div class="overflow-x-auto">
    <div class="min-w-[1300px]">
        <!-- 👇 高さをCSSで設定 -->
        <div class="relative" style="height: 200px;">
            <canvas id="pressureChart"></canvas>
        </div>

        <!-- 天気カード（そのままでOK） -->
        <div class="mt-6 grid grid-cols-12 gap-4 text-center text-sm">
            @php
                $displayTimes = ['03:00', '09:00', '15:00', '21:00'];
                $filtered = $pressures->filter(function ($item) use ($displayTimes) {
                    return in_array(substr($item['time'], 11, 5), $displayTimes);
                })->take(12);
            @endphp

            @foreach($filtered as $item)
                <div class="border rounded p-2 bg-white shadow">
                    <div class="font-medium text-xs mb-1">{{ \Carbon\Carbon::parse($item['time'])->format('M/d H:i') }}</div>
                    <img src="http://openweathermap.org/img/wn/{{ $item['icon'] }}@2x.png" alt="icon" class="mx-auto w-10 h-10">
                    <div>{{ $item['weather'] }}</div>
                    <div class="text-xs text-gray-500">{{ $item['temp'] }}℃</div>
                    <div class="text-xs text-gray-500">{{ $item['pressure'] }} hPa</div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('pressureChart').getContext('2d');

        const data = {
            labels: @json($pressures->pluck('time')->map(fn($t) => \Carbon\Carbon::parse($t)->format('M/d H:i'))->take(24)),
            datasets: [{
                label: '気圧 (hPa)',
                data: @json($pressures->pluck('pressure')->take(24)),
                fill: true,
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                tension: 0.4
            }]
        };

        // 👇 このログを追加（デバッグ用）
        console.log("圧力グラフを描画します...");
        console.log("データ:", data);

        new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 90,
                            minRotation: 45
                        }
                    },
                    y: {
                        beginAtZero: false,
                        min: 980,
                        max: 1030,
                        ticks: {
                            stepSize: 5
                        }
                    }
                }
            }
        });
    });
</script>
