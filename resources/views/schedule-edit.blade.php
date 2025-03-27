@include('components.auth-header')
<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('スケジュール編集') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <a href="{{ route('schedule-list') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            スケジュール一覧に戻る
                        </a>
                    </div>
                
                    <form method="POST" action="{{ route('schedules.update', $schedule->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="event_name" class="block text-sm font-medium text-gray-700">イベント名</label>
                            <input type="text" id="event_name" name="event_name" value="{{ $schedule->event_name }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div class="mb-4">
                            <label for="all_day" class="flex items-center">
                                @php
                                    $start = \Carbon\Carbon::parse($schedule->start_date);
                                    $end = \Carbon\Carbon::parse($schedule->end_date);
                                    $isAllDay = $start->format('H:i') === '00:00' && $end->format('H:i') === '23:59';
                                @endphp
                                <input type="checkbox" id="all_day" name="all_day" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ $isAllDay ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">終日</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">開始日時</label>
                            <input type="datetime-local" id="start_date" name="start_date" value="{{ \Carbon\Carbon::parse($schedule->start_date)->format('Y-m-d\TH:i') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div class="mb-4">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">終了日時</label>
                            <input type="datetime-local" id="end_date" name="end_date" value="{{ \Carbon\Carbon::parse($schedule->end_date)->format('Y-m-d\TH:i') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div class="mb-4">
                            <label for="details" class="block text-sm font-medium text-gray-700">詳細</label>
                            <textarea id="details" name="details" rows="5" maxlength="1024" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $schedule->details }}</textarea>
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">更新する</button>
                        </div>
                    </form>
                    
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <!-- <h3 class="text-lg font-medium text-gray-900 mb-4">危険な操作</h3> -->
                        <form method="POST" action="{{ route('schedules.destroy', $schedule->id) }}" onsubmit="return confirm('このイベントを削除してもよろしいですか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">このイベントを削除する</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
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