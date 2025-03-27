@include('components.auth-header')
<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $childSurvey->name }}さんの体調記録を編集
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium mb-4">{{ $recordDate->format('Y年n月j日') }}（{{ $dayOfWeek }}）</h3>
                    
                    <form method="POST" action="{{ route('conditions.update', ['childSurvey' => $childSurvey->id, 'dailyConditionRecord' => $dailyConditionRecord->id]) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- 気分評価 -->
                        <div class="mb-6">
                            <label for="mood_rating" class="block text-sm font-medium text-gray-700 mb-2">今日の気分</label>
                            <div class="flex items-center">
                                <input type="range" id="mood_rating" name="mood_rating" min="1" max="5" value="{{ $dailyConditionRecord->mood_rating }}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
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
                                    <input id="woke_up_well" name="woke_up_well" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ $dailyConditionRecord->woke_up_well ? 'checked' : '' }}>
                                    <label for="woke_up_well" class="ml-2 block text-sm text-gray-700">朝すっきり起きられた</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="body_fatigue" name="body_fatigue" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ $dailyConditionRecord->body_fatigue ? 'checked' : '' }}>
                                    <label for="body_fatigue" class="ml-2 block text-sm text-gray-700">身体がだるい</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="sleep_quality" name="sleep_quality" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ $dailyConditionRecord->sleep_quality ? 'checked' : '' }}>
                                    <label for="sleep_quality" class="ml-2 block text-sm text-gray-700">よく眠れた</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="headache" name="headache" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ $dailyConditionRecord->headache ? 'checked' : '' }}>
                                    <label for="headache" class="ml-2 block text-sm text-gray-700">頭痛がある</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="stomachache" name="stomachache" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ $dailyConditionRecord->stomachache ? 'checked' : '' }}>
                                    <label for="stomachache" class="ml-2 block text-sm text-gray-700">腹痛がある</label>
                                </div>
                                
                                <!-- ODS・生理周期管理の人向け質問 -->
                                @if($showOdsQuestions || $showMenstrualQuestions)
                                    <div class="flex items-center">
                                        <input id="dizziness" name="dizziness" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ $dailyConditionRecord->dizziness ? 'checked' : '' }}>
                                        <label for="dizziness" class="ml-2 block text-sm text-gray-700">めまい・立ちくらみがある</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- ODS向け質問 -->
                        @if($showOdsQuestions)
                            <div class="mb-6">
                                <h4 class="font-medium mb-3">血圧</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="blood_pressure_high" class="block text-sm font-medium text-gray-700 mb-1">上（mmHg）</label>
                                        <input type="number" id="blood_pressure_high" name="blood_pressure_high" min="70" max="150" value="{{ $dailyConditionRecord->blood_pressure_high }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label for="blood_pressure_low" class="block text-sm font-medium text-gray-700 mb-1">下（mmHg）</label>
                                        <input type="number" id="blood_pressure_low" name="blood_pressure_low" min="30" max="100" value="{{ $dailyConditionRecord->blood_pressure_low }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- 生理周期管理向け質問 -->
                        @if($showMenstrualQuestions)
                            <div class="mb-6">
                                <h4 class="font-medium mb-3">心の状態</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <input id="irritability" name="irritability" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ $dailyConditionRecord->irritability ? 'checked' : '' }}>
                                        <label for="irritability" class="ml-2 block text-sm text-gray-700">イライラ・怒りっぽい</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="depression" name="depression" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ $dailyConditionRecord->depression ? 'checked' : '' }}>
                                        <label for="depression" class="ml-2 block text-sm text-gray-700">気分が落ち込む</label>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- 生理周期管理かつ女性向け質問 -->
                        @if($showMenstruationTrackingQuestions)
                            <div class="mb-6">
                                <h4 class="font-medium mb-3">月経</h4>
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <input id="is_menstruating" name="is_menstruating" type="checkbox" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ $dailyConditionRecord->is_menstruating ? 'checked' : '' }}>
                                        <label for="is_menstruating" class="ml-2 block text-sm text-gray-700">月経中</label>
                                    </div>
                                    
                                    <div id="menstruation_details" class="{{ $dailyConditionRecord->is_menstruating ? '' : 'hidden' }} pl-6">
                                        <label for="blood_amount" class="block text-sm font-medium text-gray-700 mb-1">血液量</label>
                                        <select id="blood_amount" name="blood_amount" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                            <option value="少ない" {{ $dailyConditionRecord->blood_amount == '少ない' ? 'selected' : '' }}>少ない</option>
                                            <option value="普通" {{ $dailyConditionRecord->blood_amount == '普通' ? 'selected' : '' }}>普通</option>
                                            <option value="多い" {{ $dailyConditionRecord->blood_amount == '多い' ? 'selected' : '' }}>多い</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- 備考 -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">備考（その他気になることなど）</label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $dailyConditionRecord->notes }}</textarea>
                        </div>
                        
                        <div class="flex justify-between">
                            <a href="{{ route('dashboard') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                キャンセル
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                更新する
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