@if($pressures->count() >= 9)
    @php
        $pressuresArray = $pressures->pluck('pressure')->toArray();
        $initial = $pressuresArray[0];
        $after24h = $pressuresArray[8]; // 3時間 x 8 = 24h
        $diff = $initial - $after24h;
    @endphp

    @if($diff >= 8)
        <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            ⚠️ 24時間以内に気圧が<strong>{{ round($diff, 1) }} hPa</strong>下がる予報です。<br>
            頭痛や体調変化にご注意ください。
        </div>
    @else
        <div class="p-4 bg-green-100 text-green-800 rounded-lg">
            😊 現在、急激な気圧の変化は予想されていません。
        </div>
    @endif
@else
    <div class="p-4 bg-gray-100 text-gray-600 rounded-lg">
        ⏳ 十分な気圧データがまだ取得できていません。
    </div>
@endif

{{-- ✅ どの状況でも常に表示されるリンク --}}
<div class="mt-2 text-right">
    <a href="{{ route('weather') }}" class="text-blue-600 underline text-sm">→ 天気の詳細を見る</a>
</div>
