@include('components.auth-header')
<x-guest-layout>
    @php
        $today = date('Y-m-d');
        $todayEvents = App\Models\Schedule::where('user_id', Auth::id())
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderBy('start_date', 'asc')
            ->get();
        $selectedDate = request('date');
        $today = \Carbon\Carbon::now();
        $dayOfWeek = ['日', '月', '火', '水', '木', '金', '土'][$today->dayOfWeek];
    @endphp
    <div class="container">
        <header class="header">
            <div class="logo-container">
                <!-- ヘッダーのロゴは既にauth-headerコンポーネントに含まれているため省略 -->
            </div>
        </header>
        
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ $today->format('Y年n月j日') }}（{{ $dayOfWeek }}）</h1>
            <p class="text-sm text-gray-600">スケジュールを確認・管理できます</p>
        </div>
        
        <!-- 本日の予定と体調表示（縦並び） -->
        <div class="max-w-7xl mx-auto mb-6 space-y-6">
            <!-- 本日の予定 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold mb-4 border-b pb-2">本日の予定</h3>
                <div class="today-events">
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
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold mb-4 border-b pb-2">本日の体調</h3>
                <div class="health-status">
                    <p class="text-gray-500">体調記録機能は現在準備中です</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">新しいスケジュールを追加</h2>
            <form id="schedule-form" method="POST" action="{{ route('schedules.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-medium">イベント名</label>
                    <input type="text" id="event_name" name="event_name" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="all_day" name="all_day" class="form-checkbox text-blue-500">
                        <span class="ml-2 text-gray-700">終日</span>
                    </label>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium">開始日時</label>
                        <input 
                            type="datetime-local" 
                            id="start_date" 
                            name="start_date" 
                            class="w-full border border-gray-300 rounded px-3 py-2" 
                            value="{{ $selectedDate ? $selectedDate . 'T00:00' : '' }}"
                            required
                        >
                    </div>
                    <div>
                        <label class="block font-medium">終了日時</label>
                        <input type="datetime-local" id="end_date" name="end_date" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    </div>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    追加する
                </button>
            </form>
        </div>
        
        <!-- ナビゲーションボタン -->
        <div class="flex justify-center space-x-4 mt-6">
            <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                ダッシュボードへ戻る
            </a>
            <a href="{{ route('schedule-list') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                スケジュール一覧を表示
            </a>
        </div>
    </div>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const allDayCheckbox = document.getElementById('all_day');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            function toggleTimeInputs() {
                const isAllDay = allDayCheckbox.checked;
                if (isAllDay) {
                    startDateInput.type = 'date';
                    endDateInput.type = 'date';
                } else {
                    startDateInput.type = 'datetime-local';
                    endDateInput.type = 'datetime-local';
                }
            }

            // 初期実行＆イベント登録
            toggleTimeInputs();
            allDayCheckbox.addEventListener('change', toggleTimeInputs);
        });
    </script>
</x-guest-layout>