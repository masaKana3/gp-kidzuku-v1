<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KiDzUKu - お子さまの健康管理をサポート</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <x-common-style />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    <!-- ヘッダーコンポーネントを追加 -->
    <x-auth-header />
    <div class="container">
        <header class="header">
            <div class="logo-container">
                <img src="{{ asset('images/kidzuku_logo.png') }}" alt="KiDzUKu Logo" class="logo-img">
            </div>
        </header>
        
        <h2 class="headline">きづくで、お子さまの健康管理をサポート</h2>
        <p class="subheadline">毎日の体調とスケジュールの管理を、もっと簡単に</p>
        
        <div class="flex flex-col lg:flex-row gap-6 items-start">
            <!-- 左側カラム：天気情報と機能カード -->
            <div class="w-full lg:w-1/4 flex flex-col space-y-6">
                <!-- ✅ weather-card に pressures も渡す！ -->
                <x-weather-card :weather="$weather" :place-name="$placeName" :pressures="$pressures" />

                <!-- 機能カード：縦並び -->
                <div class="flex-grow flex flex-col">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6 flex-grow">
                        <h3 class="text-lg font-bold mb-4">体調記録</h3>
                        <p class="mb-4">お子さまの日々の体調を簡単に記録できます。気分、症状、血圧などを管理しましょう。</p>
                        
                        @if(isset($children) && $children->count() > 0)
                            <div class="mt-4">
                                <h4 class="text-md font-medium mb-2">お子さまを選択</h4>
                                <div class="space-y-2">
                                    @foreach($children as $child)
                                        <a href="{{ route('conditions.create', ['childSurvey' => $child->id]) }}" 
                                           class="block w-full py-2 px-3 bg-indigo-50 hover:bg-indigo-100 rounded-md text-indigo-700 transition">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                {{ $child->name }}さんの体調を記録
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="mt-4 p-3 bg-yellow-50 rounded-md text-yellow-700">
                                <p>お子さまの情報が登録されていません。アンケートから登録してください。</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-md p-6 flex-grow">
                        <h3 class="text-lg font-bold mb-4">データ分析</h3>
                        <p>記録したデータをグラフで可視化。体調の変化や傾向を簡単に把握できます。</p>
                        
                        @if(isset($children) && $children->count() > 0)
                            <div class="mt-4">
                                <a href="{{ route('conditions.history') }}" 
                                   class="block w-full py-2 px-3 bg-indigo-50 hover:bg-indigo-100 rounded-md text-indigo-700 transition">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        体調記録を一覧で見る
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- 右側カラム：カレンダーと予定・体調表示 -->
            <div class="w-full lg:w-3/4">
                <!-- カレンダーと予定・体調表示エリア -->
                <div class="bg-white rounded-lg shadow-md p-6 h-full">
                    <h3 class="widget-title">カレンダー</h3>
                    <div id='calendar' style="height: 350px;"></div>
                    
                    <!-- 本日の予定と体調表示 -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- 本日の予定 -->
                        <div class="border-t pt-4">
                            <h4 class="text-lg font-bold mb-4 border-b pb-2">本日の予定</h4>
                            <div class="today-events">
                                @php
                                    $today = date('Y-m-d');
                                    $todayEvents = App\Models\Schedule::where('user_id', Auth::id())
                                        ->whereDate('start_date', '<=', $today)
                                        ->whereDate('end_date', '>=', $today)
                                        ->orderBy('start_date', 'asc')
                                        ->get();
                                @endphp
                                
                                @if(isset($todayEvents) && $todayEvents->count() > 0)
                                    <ul class="divide-y">
                                        @foreach($todayEvents as $event)
                                            <li class="py-2">
                                                <div class="flex justify-between">
                                                    <span class="font-medium">{{ $event->event_name }}</span>
                                                    <span class="text-sm text-gray-500">
                                                        {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }} - 
                                                        {{ \Carbon\Carbon::parse($event->end_date)->format('H:i') }}
                                                    </span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-500">本日の予定はありません</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- 本日の体調 -->
                        <div class="border-t pt-4">
                            <h4 class="text-lg font-bold mb-4 border-b pb-2">本日の体調</h4>
                            <div class="health-status">
                                @if(isset($children) && $children->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($children as $child)
                                            <div class="p-3 bg-gray-50 rounded-md">
                                                <h5 class="font-medium mb-2">{{ $child->name }}さん</h5>
                                                
                                                @if(isset($todayConditions[$child->id]))
                                                    @php $condition = $todayConditions[$child->id]; @endphp
                                                    <div class="flex items-center mb-2">
                                                        <span class="mr-2">気分:</span>
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <span class="text-lg {{ $i <= $condition->mood_rating ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                                                        @endfor
                                                    </div>
                                                    <div class="text-sm">
                                                        <ul class="space-y-1">
                                                            @if($condition->woke_up_well)
                                                                <li class="text-green-600">✓ 朝すっきり起きられた</li>
                                                            @endif
                                                            @if($condition->body_fatigue)
                                                                <li class="text-red-600">✓ 身体がだるい</li>
                                                            @endif
                                                            @if($condition->headache)
                                                                <li class="text-red-600">✓ 頭痛あり</li>
                                                            @endif
                                                            @if($condition->stomachache)
                                                                <li class="text-red-600">✓ 腹痛あり</li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                    <div class="mt-2 text-right">
                                                        <a href="{{ route('conditions.edit', ['childSurvey' => $child->id, 'dailyConditionRecord' => $condition->id]) }}" class="text-sm text-blue-600 hover:underline">
                                                            詳細・編集
                                                        </a>
                                                    </div>
                                                @else
                                                    <p class="text-gray-500">今日の記録はまだありません</p>
                                                    <div class="mt-2 text-right">
                                                        <a href="{{ route('conditions.create', ['childSurvey' => $child->id]) }}" class="text-sm text-blue-600 hover:underline">
                                                            記録する
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500">お子さまの情報が登録されていません</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-right">
                        <a href="{{ route('schedule-list') }}" class="text-sm text-blue-600 hover:underline">スケジュール一覧を表示 →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FullCalendarのJSを読み込み -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js"></script>
    
    <!-- カレンダー初期化スクリプト -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'ja',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                height: 'auto',
                events: '{{ route('schedule-get') }}',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                // 日付クリックで/scheduleに遷移するよう設定（日付情報をクエリパラメータとして渡す）
                dateClick: function(info) {
                    window.location.href = '{{ route('schedule') }}?date=' + info.dateStr;
                }
            });
            calendar.render();
        });
    </script>
</body>
</html>
