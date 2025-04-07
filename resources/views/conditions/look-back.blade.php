@include('components.auth-header')
<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $childSurvey->name }}さんの体調記録の1日の振り返り
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium mb-4">{{ $today->format('Y年n月j日') }}（{{ $dayOfWeek }}）</h3>
                    
                    @if(isset($weatherData))
                        <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                </svg>
                                <span class="text-sm">
                                    天気: {{ $weatherData['weather'] ?? '不明' }} / 
                                    気温: {{ isset($weatherData['temperature']) ? $weatherData['temperature'] . '°C' : '不明' }} / 
                                    気圧: {{ isset($weatherData['pressure']) ? $weatherData['pressure'] . 'hPa' : '不明' }}
                                </span>
                            </div>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('conditions.store', ['childSurvey' => $childSurvey->id]) }}">
                        @csrf
                        
                        <!-- 気分評価 -->
                        <div class="mb-6">
                            <label for="evening_mood_rating" class="block text-sm font-medium text-gray-700 mb-2">今日の気分</label>
                            <div class="flex items-center">
                                <input type="range" id="evening_mood_rating" name="evening_mood_rating" min="1" max="5" value="3" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            </div>
                            <div class="flex justify-between mt-1 text-xs text-gray-500">
                                <span>😞</span>
                                <span>😐</span>
                                <span>😊</span>
                            </div>
                        </div>
                        
                        <!-- 共通の質問項目 -->
                        <div class="mb-6">
                            <h4 class="font-medium mb-3">体調チェック</h4>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input id="evening_woke_up_well" name="evening_woke_up_well" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    <label for="evening_woke_up_well" class="ml-2 block text-sm text-gray-700">朝すっきり起きられた</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="evening_body_fatigue" name="evening_body_fatigue" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    <label for="evening_body_fatigue" class="ml-2 block text-sm text-gray-700">身体がだるい</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="evening_sleep_quality" name="evening_sleep_quality" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    <label for="evening_sleep_quality" class="ml-2 block text-sm text-gray-700">よく眠れた</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="evening_headache" name="evening_headache" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    <label for="evening_headache" class="ml-2 block text-sm text-gray-700">頭痛がある</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="evening_stomachache" name="evening_stomachache" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    <label for="evening_stomachache" class="ml-2 block text-sm text-gray-700">腹痛がある</label>
                                </div>
                                
                                <!-- ODS・生理周期管理の人向け質問 -->
                                @if($showOdsQuestions || $showMenstrualQuestions)
                                    <div class="flex items-center">
                                        <input id="evening_dizziness" name="evening_dizziness" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        <label for="evening_dizziness" class="ml-2 block text-sm text-gray-700">めまい・立ちくらみがある</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        
                        
                        
                        
                        <!-- 備考 -->
                        <div class="mb-6">
                            <label for="evening_notes" class="block text-sm font-medium text-gray-700 mb-1">今日の1日を振り返って</label>
                            <textarea id="evening_notes" name="evening_notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                記録する
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isMenstruatingCheckbox = document.getElementById('is_menstruating');
            const menstruationDetails = document.getElementById('menstruation_details');
            
            if (isMenstruatingCheckbox && menstruationDetails) {
                isMenstruatingCheckbox.addEventListener('change', function() {
                    menstruationDetails.classList.toggle('hidden', !this.checked);
                });
            }
        });
    </script>
</x-guest-layout>