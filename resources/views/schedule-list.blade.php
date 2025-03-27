@include('components.auth-header')
<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('スケジュール一覧') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto mt-8">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-xl font-bold mb-4 border-b pb-2">今月の予定</h3>
            
            @php
                $today = \Carbon\Carbon::today();
                $endOfMonth = \Carbon\Carbon::now()->endOfMonth();

                $upcomingSchedules = $schedules->filter(function ($schedule) use ($today, $endOfMonth) {
                    return \Carbon\Carbon::parse($schedule->start_date)->gte($today) &&
                           \Carbon\Carbon::parse($schedule->start_date)->lte($endOfMonth);
                });
            @endphp

            @if($upcomingSchedules->count() > 0)
                <div class="space-y-4">
                    @foreach($upcomingSchedules as $schedule)
                        @php
                            $start = \Carbon\Carbon::parse($schedule->start_date);
                            $end = \Carbon\Carbon::parse($schedule->end_date);
                            $isAllDay = $start->format('H:i') === '00:00' && $end->format('H:i') === '23:59';
                            $dayOfWeekStart = ['日', '月', '火', '水', '木', '金', '土'][$start->dayOfWeek];
                            $dayOfWeekEnd = ['日', '月', '火', '水', '木', '金', '土'][$end->dayOfWeek];
                        @endphp
                        <div class="border-b pb-3">
                            <div class="font-medium text-lg">
                                <a href="{{ route('schedules.edit', $schedule->id) }}" class="text-gray-800 hover:underline">
                                    {{ $schedule->event_name }}
                                </a>
                            </div>
                            <div class="text-sm text-gray-600">
                                @if($start->format('Y-m-d') === $end->format('Y-m-d'))
                                    <span>{{ $start->format('Y年m月d日') }}（{{ $dayOfWeekStart }}）</span>
                                @else
                                    <span>{{ $start->format('Y年m月d日') }}（{{ $dayOfWeekStart }}）〜 {{ $end->format('Y年m月d日') }}（{{ $dayOfWeekEnd }}）</span>
                                @endif
                                
                                <span class="ml-2">
                                    @if($isAllDay)
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-0.5 rounded">終日</span>
                                    @else
                                        {{ $start->format('H:i') }} - {{ $end->format('H:i') }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">今月の予定はありません</p>
            @endif
        </div>
    </div>

    <!-- ナビゲーションボタン -->
    <div class="flex justify-center space-x-4 mt-8">
        <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            ダッシュボードへ戻る
        </a>
        <a href="{{ route('schedule') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            カレンダーを表示
        </a>
    </div>

    <!-- スケジュール一覧の表示/非表示切り替えボタン -->
    <div class="text-right mt-8 px-6">
        <button id="toggleSchedule" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded">
            ▼ 全スケジュール一覧を表示
        </button>
    </div>

    <!-- スケジュール一覧 -->
    <div id="scheduleList" class="py-12 hidden">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @php
                        // 月ごとにスケジュールをグループ化
                        $groupedSchedules = $schedules->groupBy(function($schedule) {
                            return \Carbon\Carbon::parse($schedule->start_date)->format('Y-m');
                        })->sortKeysDesc(); // 新しい月順に並べ替え
                    @endphp

                    @foreach($groupedSchedules as $yearMonth => $monthSchedules)
                        @php
                            $date = \Carbon\Carbon::createFromFormat('Y-m', $yearMonth);
                            $monthTitle = $date->format('Y年n月').'の予定';
                        @endphp
                        
                        <div class="mb-8">
                            <h3 class="text-xl font-bold mb-4 border-b pb-2">{{ $monthTitle }}</h3>
                            
                            <div class="space-y-4">
                                @foreach($monthSchedules as $schedule)
                                    @php
                                        $start = \Carbon\Carbon::parse($schedule->start_date);
                                        $end = \Carbon\Carbon::parse($schedule->end_date);
                                        $isAllDay = $start->format('H:i') === '00:00' && $end->format('H:i') === '23:59';
                                        $dayOfWeekStart = ['日', '月', '火', '水', '木', '金', '土'][$start->dayOfWeek];
                                        $dayOfWeekEnd = ['日', '月', '火', '水', '木', '金', '土'][$end->dayOfWeek];
                                    @endphp
                                    <div class="border-b pb-3">
                                        <div class="font-medium text-lg">
                                            <a href="{{ route('schedules.edit', $schedule->id) }}" class="text-gray-800 hover:underline">
                                                {{ $schedule->event_name }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            @if($start->format('Y-m-d') === $end->format('Y-m-d'))
                                                <span>{{ $start->format('Y年m月d日') }}（{{ $dayOfWeekStart }}）</span>
                                            @else
                                                <span>{{ $start->format('Y年m月d日') }}（{{ $dayOfWeekStart }}）〜 {{ $end->format('Y年m月d日') }}（{{ $dayOfWeekEnd }}）</span>
                                            @endif
                                            
                                            <span class="ml-2">
                                                @if($isAllDay)
                                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-0.5 rounded">終日</span>
                                                @else
                                                    {{ $start->format('H:i') }} - {{ $end->format('H:i') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript：表示切り替え -->
    <script>
        document.getElementById('toggleSchedule').addEventListener('click', function () {
            const list = document.getElementById('scheduleList');
            const isHidden = list.classList.contains('hidden');

            if (isHidden) {
                list.classList.remove('hidden');
                this.textContent = '▲ 全スケジュール一覧を閉じる';
            } else {
                list.classList.add('hidden');
                this.textContent = '▼ 全スケジュール一覧を表示';
            }
        });
    </script>
</x-guest-layout>