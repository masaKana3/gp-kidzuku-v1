<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        <h2 class="text-lg font-medium text-gray-900">保護者アンケート（編集）</h2>
        <p>最適な状態での利用のために、回答をお願いしております。</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('surveys.parent.update') }}">
        @csrf
        @method('PATCH')

        <!-- Q1. 生年月日 -->
        <div class="mt-4">
            <x-input-label for="birth_date" :value="__('Q1. 生年月日（西暦で記入）')" />
            <div class="flex">
                <x-text-input id="birth_date" class="block mt-1 w-full text-gray-400" type="date" name="birth_date" :value="old('birth_date', $parentSurvey->birth_date)" placeholder="1980-01-01" required />
            </div>
            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
        </div>

        <!-- Q2. 性別 -->
        <div class="mt-4">
            <x-input-label :value="__('Q2. 性別')" />
            <div class="mt-2 space-y-2">
                <div class="flex items-center">
                    <input id="gender_male" type="radio" name="gender" value="male" class="mr-2" {{ old('gender', $parentSurvey->gender) == 'male' ? 'checked' : '' }} required>
                    <label for="gender_male">男性</label>
                </div>
                <div class="flex items-center">
                    <input id="gender_female" type="radio" name="gender" value="female" class="mr-2" {{ old('gender', $parentSurvey->gender) == 'female' ? 'checked' : '' }}>
                    <label for="gender_female">女性</label>
                </div>
                <div class="flex items-center">
                    <input id="gender_no_answer" type="radio" name="gender" value="no_answer" class="mr-2" {{ old('gender', $parentSurvey->gender) == 'no_answer' ? 'checked' : '' }}>
                    <label for="gender_no_answer">回答しない</label>
                </div>
            </div>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <!-- Q3. 体調について -->
        <div class="mt-4">
            <x-input-label :value="__('Q3. ご自身の体調について')" />
            <div class="mt-2">
                <p>ご自身の体調で気になることはありますか？</p>
                <div class="flex items-center mt-1 space-x-4">
                    <div class="flex items-center">
                        <input id="health_concern_yes" type="radio" name="health_concern" value="1" class="mr-2" {{ old('health_concern', $parentSurvey->health_concern) == '1' ? 'checked' : '' }} required>
                        <label for="health_concern_yes">はい</label>
                    </div>
                    <div class="flex items-center">
                        <input id="health_concern_no" type="radio" name="health_concern" value="0" class="mr-2" {{ old('health_concern', $parentSurvey->health_concern) == '0' ? 'checked' : '' }}>
                        <label for="health_concern_no">いいえ</label>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('health_concern')" class="mt-2" />
            </div>

            <div class="mt-4">
                <p>現在の調子を5段階で評価（1～5）</p>
                <div class="flex items-center mt-2">
                    <span class="mr-2">悪い</span>
                    <input type="range" id="health_rating" name="health_rating" min="1" max="5" value="{{ old('health_rating', $parentSurvey->health_rating) }}" class="w-full" required>
                    <span class="ml-2">良い</span>
                </div>
                <div class="flex justify-between text-xs text-gray-500">
                    <span>1</span>
                    <span>2</span>
                    <span>3</span>
                    <span>4</span>
                    <span>5</span>
                </div>
                <x-input-error :messages="$errors->get('health_rating')" class="mt-2" />
            </div>
        </div>

        <!-- Q4. 特に気になること -->
        <div class="mt-4">
            <x-input-label :value="__('Q4. 特に気になること（複数選択可）')" />
            <div class="mt-2 space-y-2">
                @php
                    $concerns = old('concerns', $parentSurvey->concerns ?? []);
                    if (!is_array($concerns)) {
                        $concerns = [];
                    }
                @endphp
                <div class="flex items-center">
                    <input id="concern_physical" type="checkbox" name="concerns[]" value="physical" class="mr-2" {{ in_array('physical', $concerns) ? 'checked' : '' }}>
                    <label for="concern_physical">身体の調子がすぐれない</label>
                </div>
                <div class="flex items-center">
                    <input id="concern_mental" type="checkbox" name="concerns[]" value="mental" class="mr-2" {{ in_array('mental', $concerns) ? 'checked' : '' }}>
                    <label for="concern_mental">心の調子がすぐれない</label>
                </div>
                <div class="flex items-center">
                    <input id="concern_tired" type="checkbox" name="concerns[]" value="tired" class="mr-2" {{ in_array('tired', $concerns) ? 'checked' : '' }}>
                    <label for="concern_tired">なんとなく疲れが取れない</label>
                </div>
                <div class="flex items-center">
                    <input id="concern_motivation" type="checkbox" name="concerns[]" value="motivation" class="mr-2" {{ in_array('motivation', $concerns) ? 'checked' : '' }}>
                    <label for="concern_motivation">やる気が起きない</label>
                </div>
                <div class="flex items-center">
                    <input id="concern_none" type="checkbox" name="concerns[]" value="none" class="mr-2" {{ in_array('none', $concerns) ? 'checked' : '' }}>
                    <label for="concern_none">この中にはない</label>
                </div>
            </div>
            <x-input-error :messages="$errors->get('concerns')" class="mt-2" />
        </div>

        <!-- Q5. 相談相手 -->
        <div class="mt-4">
            <x-input-label :value="__('Q5. お子さまのことをどなたに相談していますか（複数選択可）')" />
            <div class="mt-2 space-y-2">
                @php
                    $consultants = old('consultants', $parentSurvey->consultants ?? []);
                    if (!is_array($consultants)) {
                        $consultants = [];
                    }
                @endphp
                <div class="flex items-center">
                    <input id="consultant_spouse" type="checkbox" name="consultants[]" value="spouse" class="mr-2" {{ in_array('spouse', $consultants) ? 'checked' : '' }}>
                    <label for="consultant_spouse">配偶者・パートナー</label>
                </div>
                <div class="flex items-center">
                    <input id="consultant_family" type="checkbox" name="consultants[]" value="family" class="mr-2" {{ in_array('family', $consultants) ? 'checked' : '' }}>
                    <label for="consultant_family">家族</label>
                </div>
                <div class="flex items-center">
                    <input id="consultant_siblings" type="checkbox" name="consultants[]" value="siblings" class="mr-2" {{ in_array('siblings', $consultants) ? 'checked' : '' }}>
                    <label for="consultant_siblings">自分の兄弟・姉妹</label>
                </div>
                <div class="flex items-center">
                    <input id="consultant_friend" type="checkbox" name="consultants[]" value="friend" class="mr-2" {{ in_array('friend', $consultants) ? 'checked' : '' }}>
                    <label for="consultant_friend">友人</label>
                </div>
                <div class="flex items-center">
                    <input id="consultant_teacher" type="checkbox" name="consultants[]" value="teacher" class="mr-2" {{ in_array('teacher', $consultants) ? 'checked' : '' }}>
                    <label for="consultant_teacher">学校の先生</label>
                </div>
                <div class="flex items-center">
                    <input id="consultant_counselor" type="checkbox" name="consultants[]" value="counselor" class="mr-2" {{ in_array('counselor', $consultants) ? 'checked' : '' }}>
                    <label for="consultant_counselor">スクールカウンセラー</label>
                </div>
                <div class="flex items-center">
                    <input id="consultant_cram_teacher" type="checkbox" name="consultants[]" value="cram_teacher" class="mr-2" {{ in_array('cram_teacher', $consultants) ? 'checked' : '' }}>
                    <label for="consultant_cram_teacher">塾の先生</label>
                </div>
                <div class="flex items-center">
                    <input id="consultant_doctor" type="checkbox" name="consultants[]" value="doctor" class="mr-2" {{ in_array('doctor', $consultants) ? 'checked' : '' }}>
                    <label for="consultant_doctor">専門医</label>
                </div>
                <div class="flex items-center">
                    <input id="consultant_psychologist" type="checkbox" name="consultants[]" value="psychologist" class="mr-2" {{ in_array('psychologist', $consultants) ? 'checked' : '' }}>
                    <label for="consultant_psychologist">臨床心理士・カウンセラー</label>
                </div>
                <div class="flex items-center">
                    <input id="consultant_none" type="checkbox" name="consultants[]" value="none" class="mr-2" {{ in_array('none', $consultants) ? 'checked' : '' }}>
                    <label for="consultant_none">いない</label>
                </div>
                <div class="flex items-center">
                    <input id="consultant_other" type="checkbox" name="consultants[]" value="other" class="mr-2" {{ in_array('other', $consultants) ? 'checked' : '' }}>
                    <label for="consultant_other">その他</label>
                </div>
            </div>
            <div class="mt-2" id="other_consultant_section" style="{{ in_array('other', $consultants) ? '' : 'display: none;' }}">
                <x-text-input id="other_consultant" class="block mt-1 w-full" type="text" name="other_consultant" :value="old('other_consultant', $parentSurvey->other_consultant)" placeholder="その他の相談相手を入力してください" />
            </div>
            <x-input-error :messages="$errors->get('consultants')" class="mt-2" />
            <x-input-error :messages="$errors->get('other_consultant')" class="mt-2" />
        </div>

        <!-- Q6. 通院 -->
        <div class="mt-4">
            <x-input-label :value="__('Q6. ご自身は定期的に通院していますか？')" />
            <div class="flex items-center mt-2 space-x-4">
                <div class="flex items-center">
                    <input id="regular_hospital_visit_yes" type="radio" name="regular_hospital_visit" value="1" class="mr-2" {{ old('regular_hospital_visit', $parentSurvey->regular_hospital_visit) == '1' ? 'checked' : '' }} required>
                    <label for="regular_hospital_visit_yes">はい</label>
                </div>
                <div class="flex items-center">
                    <input id="regular_hospital_visit_no" type="radio" name="regular_hospital_visit" value="0" class="mr-2" {{ old('regular_hospital_visit', $parentSurvey->regular_hospital_visit) == '0' ? 'checked' : '' }}>
                    <label for="regular_hospital_visit_no">いいえ</label>
                </div>
            </div>
            <x-input-error :messages="$errors->get('regular_hospital_visit')" class="mt-2" />
        </div>

        <!-- Q7. かかりつけの診療科 -->
        <div class="mt-4" id="medical_departments_section" style="{{ old('regular_hospital_visit', $parentSurvey->regular_hospital_visit) == '1' ? '' : 'display: none;' }}">
            <x-input-label :value="__('Q7. かかりつけの診療科を教えてください（該当するものを選択）')" />
            <div class="mt-2 space-y-2">
                @php
                    $medical_departments = old('medical_departments', $parentSurvey->medical_departments ?? []);
                    if (!is_array($medical_departments)) {
                        $medical_departments = [];
                    }
                @endphp
                <div class="flex items-center">
                    <input id="dept_internal" type="checkbox" name="medical_departments[]" value="internal" class="mr-2" {{ in_array('internal', $medical_departments) ? 'checked' : '' }}>
                    <label for="dept_internal">内科</label>
                </div>
                <div class="flex items-center">
                    <input id="dept_eye" type="checkbox" name="medical_departments[]" value="eye" class="mr-2" {{ in_array('eye', $medical_departments) ? 'checked' : '' }}>
                    <label for="dept_eye">眼科</label>
                </div>
                <div class="flex items-center">
                    <input id="dept_dermatology" type="checkbox" name="medical_departments[]" value="dermatology" class="mr-2" {{ in_array('dermatology', $medical_departments) ? 'checked' : '' }}>
                    <label for="dept_dermatology">皮膚科</label>
                </div>
                <div class="flex items-center">
                    <input id="dept_ent" type="checkbox" name="medical_departments[]" value="ent" class="mr-2" {{ in_array('ent', $medical_departments) ? 'checked' : '' }}>
                    <label for="dept_ent">耳鼻咽喉科</label>
                </div>
                <div class="flex items-center">
                    <input id="dept_obstetrics" type="checkbox" name="medical_departments[]" value="obstetrics" class="mr-2" {{ in_array('obstetrics', $medical_departments) ? 'checked' : '' }}>
                    <label for="dept_obstetrics">産婦人科</label>
                </div>
                <div class="flex items-center">
                    <input id="dept_psychiatry" type="checkbox" name="medical_departments[]" value="psychiatry" class="mr-2" {{ in_array('psychiatry', $medical_departments) ? 'checked' : '' }}>
                    <label for="dept_psychiatry">心療内科・精神科</label>
                </div>
                <div class="flex items-center">
                    <input id="dept_dental" type="checkbox" name="medical_departments[]" value="dental" class="mr-2" {{ in_array('dental', $medical_departments) ? 'checked' : '' }}>
                    <label for="dept_dental">歯科</label>
                </div>
                <div class="flex items-center">
                    <input id="dept_other" type="checkbox" name="medical_departments[]" value="other" class="mr-2" {{ in_array('other', $medical_departments) ? 'checked' : '' }}>
                    <label for="dept_other">その他</label>
                </div>
                <div class="flex items-center">
                    <input id="dept_no_answer" type="checkbox" name="medical_departments[]" value="no_answer" class="mr-2" {{ in_array('no_answer', $medical_departments) ? 'checked' : '' }}>
                    <label for="dept_no_answer">回答したくない</label>
                </div>
            </div>
            <div class="mt-2" id="other_department_section" style="{{ in_array('other', $medical_departments) ? '' : 'display: none;' }}">
                <x-text-input id="other_department" class="block mt-1 w-full" type="text" name="other_department" :value="old('other_department', $parentSurvey->other_department)" placeholder="その他の診療科を入力してください" />
            </div>
            <x-input-error :messages="$errors->get('medical_departments')" class="mt-2" />
            <x-input-error :messages="$errors->get('other_department')" class="mt-2" />
        </div>

        <!-- Q8. 月経周期 -->
        <div class="mt-4">
            <x-input-label :value="__('Q8. 最近の月経日を登録しますか')" />
            <div class="flex items-center mt-2 space-x-4">
                <div class="flex items-center">
                    <input id="menstruation_tracking_yes" type="radio" name="menstruation_tracking" value="1" class="mr-2" {{ old('menstruation_tracking', $parentSurvey->menstruation_tracking) == '1' ? 'checked' : '' }} required>
                    <label for="menstruation_tracking_yes">はい</label>
                </div>
                <div class="flex items-center">
                    <input id="menstruation_tracking_no" type="radio" name="menstruation_tracking" value="0" class="mr-2" {{ old('menstruation_tracking', $parentSurvey->menstruation_tracking) == '0' ? 'checked' : '' }}>
                    <label for="menstruation_tracking_no">いいえ</label>
                </div>
            </div>
            <x-input-error :messages="$errors->get('menstruation_tracking')" class="mt-2" />
        </div>

        <div class="mt-4" id="last_period_date_section" style="{{ old('menstruation_tracking', $parentSurvey->menstruation_tracking) == '1' ? '' : 'display: none;' }}">
            <x-input-label for="last_period_date" :value="__('最近の月経開始日')" />
            <x-text-input id="last_period_date" class="block mt-1 w-full" type="date" name="last_period_date" :value="old('last_period_date', $parentSurvey->last_period_date)" />
            <x-input-error :messages="$errors->get('last_period_date')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="ml-4">
                {{ __('更新する') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // カレンダーのデフォルト年を1980年に設定
            const birthDateInput = document.getElementById('birth_date');
            if (birthDateInput) {
                // フォーカス時にテキストカラーを変更
                birthDateInput.addEventListener('focus', function() {
                    this.classList.remove('text-gray-400');
                    this.classList.add('text-gray-900');
                });
            }

            // 診療科セクションの表示/非表示
            const regularHospitalVisitYes = document.getElementById('regular_hospital_visit_yes');
            const regularHospitalVisitNo = document.getElementById('regular_hospital_visit_no');
            const medicalDepartmentsSection = document.getElementById('medical_departments_section');

            if (regularHospitalVisitYes && regularHospitalVisitNo && medicalDepartmentsSection) {
                regularHospitalVisitYes.addEventListener('change', function() {
                    medicalDepartmentsSection.style.display = this.checked ? '' : 'none';
                });
                
                regularHospitalVisitNo.addEventListener('change', function() {
                    medicalDepartmentsSection.style.display = this.checked ? 'none' : '';
                });
            }

            // その他の診療科の表示/非表示
            const deptOther = document.getElementById('dept_other');
            const otherDepartmentSection = document.getElementById('other_department_section');

            if (deptOther && otherDepartmentSection) {
                deptOther.addEventListener('change', function() {
                    otherDepartmentSection.style.display = this.checked ? '' : 'none';
                });
            }

            // その他の相談相手の表示/非表示
            const consultantOther = document.getElementById('consultant_other');
            const otherConsultantSection = document.getElementById('other_consultant_section');

            if (consultantOther && otherConsultantSection) {
                consultantOther.addEventListener('change', function() {
                    otherConsultantSection.style.display = this.checked ? '' : 'none';
                });
            }

            // 月経日セクションの表示/非表示
            const menstruationTrackingYes = document.getElementById('menstruation_tracking_yes');
            const menstruationTrackingNo = document.getElementById('menstruation_tracking_no');
            const lastPeriodDateSection = document.getElementById('last_period_date_section');

            if (menstruationTrackingYes && menstruationTrackingNo && lastPeriodDateSection) {
                menstruationTrackingYes.addEventListener('change', function() {
                    lastPeriodDateSection.style.display = this.checked ? '' : 'none';
                });
                
                menstruationTrackingNo.addEventListener('change', function() {
                    lastPeriodDateSection.style.display = this.checked ? 'none' : '';
                });
            }
        });
    </script>
</x-guest-layout>
