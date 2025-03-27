<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        <h2 class="text-lg font-medium text-gray-900">お子さまの情報編集 ({{ $siblingOrder }}人目)</h2>
        <p>お子さまの基本情報を編集してください。</p>
    </div>

    @if ($errors->any())
        <div class="text-red-600 text-sm mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>・{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('surveys.child.update') }}">
        @csrf
        @method('PATCH')
        <input type="hidden" name="sibling_order" value="{{ $siblingOrder }}">

        <!-- 名前 -->
        <div class="mt-4">
            <x-input-label for="name" value="お名前（ニックネーム可）" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $childSurvey->name)" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- 生年月日 -->
        <div class="mt-4">
            <x-input-label for="birth_date" value="生年月日" />
            <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" :value="old('birth_date', $childSurvey->birth_date)" required />
            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
        </div>

        <!-- 性別 -->
        <div class="mt-4">
            <x-input-label for="gender" value="性別" />
            <div class="mt-2 space-y-2">
                <div class="flex items-center">
                    <input id="gender_male" name="gender" type="radio" value="male" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('gender', $childSurvey->gender) == 'male' ? 'checked' : '' }} required>
                    <label for="gender_male" class="ml-3 block text-sm font-medium text-gray-700">男性</label>
                </div>
                <div class="flex items-center">
                    <input id="gender_female" name="gender" type="radio" value="female" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('gender', $childSurvey->gender) == 'female' ? 'checked' : '' }}>
                    <label for="gender_female" class="ml-3 block text-sm font-medium text-gray-700">女性</label>
                </div>
                <div class="flex items-center">
                    <input id="gender_other" name="gender" type="radio" value="other" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('gender', $childSurvey->gender) == 'other' ? 'checked' : '' }}>
                    <label for="gender_other" class="ml-3 block text-sm font-medium text-gray-700">回答したくない</label>
                </div>
            </div>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <!-- 月経周期登録 -->
        <div class="mt-4">
            <x-input-label for="menstruation_tracking" value="月経周期を登録しますか？" />
            <div class="mt-2 space-y-2">
                <div class="flex items-center">
                    <input id="menstruation_tracking_yes" name="menstruation_tracking" type="radio" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('menstruation_tracking', $childSurvey->menstruation_tracking) == '1' ? 'checked' : '' }} required>
                    <label for="menstruation_tracking_yes" class="ml-3 block text-sm font-medium text-gray-700">はい</label>
                </div>
                <div class="flex items-center">
                    <input id="menstruation_tracking_no" name="menstruation_tracking" type="radio" value="0" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('menstruation_tracking', $childSurvey->menstruation_tracking) == '0' ? 'checked' : '' }}>
                    <label for="menstruation_tracking_no" class="ml-3 block text-sm font-medium text-gray-700">いいえ</label>
                </div>
            </div>
            <x-input-error :messages="$errors->get('menstruation_tracking')" class="mt-2" />
        </div>

        <!-- 最近の月経開始日 (月経周期登録が「はい」の場合のみ表示) -->
        <div id="last_period_date_section" class="mt-4" style="{{ old('menstruation_tracking', $childSurvey->menstruation_tracking) == '1' ? '' : 'display: none;' }}">
            <x-input-label for="last_period_date" value="最近の月経開始日" />
            <x-text-input id="last_period_date" class="block mt-1 w-full" type="date" name="last_period_date" :value="old('last_period_date', $childSurvey->last_period_date)" />
            <div class="mt-2">
                <input id="input_later" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ empty(old('last_period_date', $childSurvey->last_period_date)) ? 'checked' : '' }}>
                <label for="input_later" class="ml-2 text-sm text-gray-600">後から入力する</label>
            </div>
            <x-input-error :messages="$errors->get('last_period_date')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="ml-4">
                更新する
            </x-primary-button>
        </div>
    </form>

    <script>
        // 月経周期登録の選択に応じて最近の月経開始日セクションの表示/非表示を切り替える
        document.addEventListener('DOMContentLoaded', function() {
            const registerMenstrualCycleYes = document.getElementById('menstruation_tracking_yes');
            const registerMenstrualCycleNo = document.getElementById('menstruation_tracking_no');
            const lastPeriodDateSection = document.getElementById('last_period_date_section');
            const lastPeriodDateInput = document.getElementById('last_period_date');
            const inputLaterCheckbox = document.getElementById('input_later');

            // 初期表示の設定
            if (registerMenstrualCycleYes.checked) {
                lastPeriodDateSection.style.display = '';
                if (inputLaterCheckbox.checked) {
                    lastPeriodDateInput.disabled = true;
                }
            }

            // ラジオボタンの変更イベント
            registerMenstrualCycleYes.addEventListener('change', function() {
                if (this.checked) {
                    lastPeriodDateSection.style.display = '';
                }
            });

            registerMenstrualCycleNo.addEventListener('change', function() {
                if (this.checked) {
                    lastPeriodDateSection.style.display = 'none';
                    lastPeriodDateInput.value = '';
                }
            });

            // 「後から入力する」チェックボックスの変更イベント
            inputLaterCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    lastPeriodDateInput.value = '';
                    lastPeriodDateInput.disabled = true;
                } else {
                    lastPeriodDateInput.disabled = false;
                }
            });
        });
    </script>
</x-guest-layout>