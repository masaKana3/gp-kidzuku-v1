<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        <h2 class="text-lg font-medium text-gray-900">{{ $childName }}さんについて</h2>
        <p>お子さまの健康状態について教えてください。</p>
    </div>

    <form method="POST" action="{{ route('surveys.child.details.submit') }}">
        @csrf
        <input type="hidden" name="sibling_order" value="{{ $siblingOrder }}">

        <!-- Q1: 学校への通学状況 -->
        <div class="mt-4">
            <x-input-label for="school_attendance" value="Q1. お子さまは学校に通えていますか？最も当てはまるものをお選びください。" />
            <div class="mt-2 space-y-2">
                <div class="flex items-center">
                    <input id="school_everyday" name="school_attendance" type="radio" value="everyday" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('school_attendance') == 'everyday' ? 'checked' : '' }} required>
                    <label for="school_everyday" class="ml-3 block text-sm font-medium text-gray-700">毎日通学している</label>
                </div>
                <div class="flex items-center">
                    <input id="school_sometimes_absent" name="school_attendance" type="radio" value="sometimes_absent" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('school_attendance') == 'sometimes_absent' ? 'checked' : '' }}>
                    <label for="school_sometimes_absent" class="ml-3 block text-sm font-medium text-gray-700">たまに休む</label>
                </div>
                <div class="flex items-center">
                    <input id="school_often_absent" name="school_attendance" type="radio" value="often_absent" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('school_attendance') == 'often_absent' ? 'checked' : '' }}>
                    <label for="school_often_absent" class="ml-3 block text-sm font-medium text-gray-700">欠席が多い</label>
                </div>
                <div class="flex items-center">
                    <input id="school_separate_room" name="school_attendance" type="radio" value="separate_room" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('school_attendance') == 'separate_room' ? 'checked' : '' }}>
                    <label for="school_separate_room" class="ml-3 block text-sm font-medium text-gray-700">別室登校をしている</label>
                </div>
                <div class="flex items-center">
                    <input id="school_late_afternoon" name="school_attendance" type="radio" value="late_afternoon" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('school_attendance') == 'late_afternoon' ? 'checked' : '' }}>
                    <label for="school_late_afternoon" class="ml-3 block text-sm font-medium text-gray-700">遅刻もしくは午後から登校する</label>
                </div>
                <div class="flex items-center">
                    <input id="school_not_recently" name="school_attendance" type="radio" value="not_recently" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('school_attendance') == 'not_recently' ? 'checked' : '' }}>
                    <label for="school_not_recently" class="ml-3 block text-sm font-medium text-gray-700">最近通えていない</label>
                </div>
                <div class="flex items-center">
                    <input id="school_free_school" name="school_attendance" type="radio" value="free_school" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('school_attendance') == 'free_school' ? 'checked' : '' }}>
                    <label for="school_free_school" class="ml-3 block text-sm font-medium text-gray-700">フリースクールに通っている</label>
                </div>
                <div class="flex items-center">
                    <input id="school_transfer_experience" name="school_attendance" type="radio" value="transfer_experience" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('school_attendance') == 'transfer_experience' ? 'checked' : '' }}>
                    <label for="school_transfer_experience" class="ml-3 block text-sm font-medium text-gray-700">転校・退学・編入の経験がある</label>
                </div>
                <div class="flex items-center">
                    <input id="school_correspondence" name="school_attendance" type="radio" value="correspondence" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('school_attendance') == 'correspondence' ? 'checked' : '' }}>
                    <label for="school_correspondence" class="ml-3 block text-sm font-medium text-gray-700">通信制・定時制に通学している（高校生のみ）</label>
                </div>
            </div>
            <x-input-error :messages="$errors->get('school_attendance')" class="mt-2" />
        </div>

        <!-- Q2: 定期的な通院の有無 -->
        <div class="mt-6">
            <x-input-label for="regular_hospital_visit" value="Q2. 病院・クリニックに定期的に通院していますか？" />
            <div class="mt-2 space-y-2">
                <div class="flex items-center">
                    <input id="regular_hospital_visit_yes" name="regular_hospital_visit" type="radio" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('regular_hospital_visit') == '1' ? 'checked' : '' }} required>
                    <label for="regular_hospital_visit_yes" class="ml-3 block text-sm font-medium text-gray-700">はい</label>
                </div>
                <div class="flex items-center">
                    <input id="regular_hospital_visit_no" name="regular_hospital_visit" type="radio" value="0" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('regular_hospital_visit') == '0' ? 'checked' : '' }}>
                    <label for="regular_hospital_visit_no" class="ml-3 block text-sm font-medium text-gray-700">いいえ</label>
                </div>
            </div>
            <x-input-error :messages="$errors->get('regular_hospital_visit')" class="mt-2" />
        </div>

        <!-- Q3: かかりつけの診療科 (定期的な通院が「はい」の場合のみ表示) -->
        <div id="medical_departments_section" class="mt-6 hidden">
            <x-input-label for="medical_departments" value="Q3. かかりつけの診療科を教えてください（該当するものを選択）" />
            <div class="mt-2 grid grid-cols-2 gap-2">
                <div class="flex items-center">
                    <input id="dept_pediatrics" name="medical_departments[]" type="checkbox" value="pediatrics" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ is_array(old('medical_departments')) && in_array('pediatrics', old('medical_departments')) ? 'checked' : '' }}>
                    <label for="dept_pediatrics" class="ml-3 block text-sm font-medium text-gray-700">小児科</label>
                </div>
                <div class="flex items-center">
                    <input id="dept_child_adolescent" name="medical_departments[]" type="checkbox" value="child_adolescent" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ is_array(old('medical_departments')) && in_array('child_adolescent', old('medical_departments')) ? 'checked' : '' }}>
                    <label for="dept_child_adolescent" class="ml-3 block text-sm font-medium text-gray-700">児童・思春期発達外来</label>
                </div>
                <div class="flex items-center">
                    <input id="dept_internal" name="medical_departments[]" type="checkbox" value="internal" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ is_array(old('medical_departments')) && in_array('internal', old('medical_departments')) ? 'checked' : '' }}>
                    <label for="dept_internal" class="ml-3 block text-sm font-medium text-gray-700">内科</label>
                </div>
                <div class="flex items-center">
                    <input id="dept_ophthalmology" name="medical_departments[]" type="checkbox" value="ophthalmology" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ is_array(old('medical_departments')) && in_array('ophthalmology', old('medical_departments')) ? 'checked' : '' }}>
                    <label for="dept_ophthalmology" class="ml-3 block text-sm font-medium text-gray-700">眼科</label>
                </div>
                <div class="flex items-center">
                    <input id="dept_dermatology" name="medical_departments[]" type="checkbox" value="dermatology" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ is_array(old('medical_departments')) && in_array('dermatology', old('medical_departments')) ? 'checked' : '' }}>
                    <label for="dept_dermatology" class="ml-3 block text-sm font-medium text-gray-700">皮膚科</label>
                </div>
                <div class="flex items-center">
                    <input id="dept_otolaryngology" name="medical_departments[]" type="checkbox" value="otolaryngology" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ is_array(old('medical_departments')) && in_array('otolaryngology', old('medical_departments')) ? 'checked' : '' }}>
                    <label for="dept_otolaryngology" class="ml-3 block text-sm font-medium text-gray-700">耳鼻咽喉科</label>
                </div>
                <div class="flex items-center">
                    <input id="dept_gynecology" name="medical_departments[]" type="checkbox" value="gynecology" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ is_array(old('medical_departments')) && in_array('gynecology', old('medical_departments')) ? 'checked' : '' }}>
                    <label for="dept_gynecology" class="ml-3 block text-sm font-medium text-gray-700">産婦人科</label>
                </div>
                <div class="flex items-center">
                    <input id="dept_psychosomatic" name="medical_departments[]" type="checkbox" value="psychosomatic" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ is_array(old('medical_departments')) && in_array('psychosomatic', old('medical_departments')) ? 'checked' : '' }}>
                    <label for="dept_psychosomatic" class="ml-3 block text-sm font-medium text-gray-700">心療内科・精神科</label>
                </div>
                <div class="flex items-center">
                    <input id="dept_neurosurgery" name="medical_departments[]" type="checkbox" value="neurosurgery" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ is_array(old('medical_departments')) && in_array('neurosurgery', old('medical_departments')) ? 'checked' : '' }}>
                    <label for="dept_neurosurgery" class="ml-3 block text-sm font-medium text-gray-700">脳神経外科</label>
                </div>
                <div class="flex items-center">
                    <input id="dept_dentistry" name="medical_departments[]" type="checkbox" value="dentistry" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ is_array(old('medical_departments')) && in_array('dentistry', old('medical_departments')) ? 'checked' : '' }}>
                    <label for="dept_dentistry" class="ml-3 block text-sm font-medium text-gray-700">歯科</label>
                </div>
                <div class="flex items-center">
                    <input id="dept_other" name="medical_departments[]" type="checkbox" value="other" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ is_array(old('medical_departments')) && in_array('other', old('medical_departments')) ? 'checked' : '' }}>
                    <label for="dept_other" class="ml-3 block text-sm font-medium text-gray-700">その他</label>
                </div>
                <div class="flex items-center">
                    <input id="dept_no_answer" name="medical_departments[]" type="checkbox" value="no_answer" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ is_array(old('medical_departments')) && in_array('no_answer', old('medical_departments')) ? 'checked' : '' }}>
                    <label for="dept_no_answer" class="ml-3 block text-sm font-medium text-gray-700">回答したくない</label>
                </div>
            </div>

            <!-- その他の診療科（自由記述） -->
            <div id="other_department_section" class="mt-2 hidden">
                <x-input-label for="other_department" value="その他の診療科" />
                <x-text-input id="other_department" class="block mt-1 w-full" type="text" name="other_department" :value="old('other_department')" />
                <x-input-error :messages="$errors->get('other_department')" class="mt-2" />
            </div>
        </div>

        <!-- Q4: 診断名 (定期的な通院が「はい」の場合のみ表示) -->
        <div id="diagnoses_section" class="mt-6 hidden">
            <x-input-label for="diagnoses" value="Q4. Q2ではいと回答した方にお聞きします。診断名はついていますか。（複数回答可）" />
            <div class="mt-2 grid grid-cols-2 gap-2">
                <div class="flex items-center">
                    <input id="diagnosis_ods" name="diagnoses[]" type="checkbox" value="ods" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ is_array(old('diagnoses')) && in_array('ods', old('diagnoses')) ? 'checked' : '' }}>
                    <label for="diagnosis_ods" class="ml-3 block text-sm font-medium text-gray-700">起立性調節障害</label>
                </div>
                <div class="flex items-center">
                    <input id="diagnosis_menstrual" name="diagnoses[]" type="checkbox" value="menstrual" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ is_array(old('diagnoses')) && in_array('menstrual', old('diagnoses')) ? 'checked' : '' }}>
                    <label for="diagnosis_menstrual" class="ml-3 block text-sm font-medium text-gray-700">月経困難症・PMS</label>
                </div>
                <div class="flex items-center">
                    <input id="diagnosis_mental" name="diagnoses[]" type="checkbox" value="mental" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ is_array(old('diagnoses')) && in_array('mental', old('diagnoses')) ? 'checked' : '' }}>
                    <label for="diagnosis_mental" class="ml-3 block text-sm font-medium text-gray-700">メンタルに関する診断</label>
                </div>
                <div class="flex items-center">
                    <input id="diagnosis_development" name="diagnoses[]" type="checkbox" value="development" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ is_array(old('diagnoses')) && in_array('development', old('diagnoses')) ? 'checked' : '' }}>
                    <label for="diagnosis_development" class="ml-3 block text-sm font-medium text-gray-700">発達に関する診断</label>
                </div>
                <div class="flex items-center">
                    <input id="diagnosis_other" name="diagnoses[]" type="checkbox" value="other" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ is_array(old('diagnoses')) && in_array('other', old('diagnoses')) ? 'checked' : '' }}>
                    <label for="diagnosis_other" class="ml-3 block text-sm font-medium text-gray-700">その他</label>
                </div>
                <div class="flex items-center">
                    <input id="diagnosis_none" name="diagnoses[]" type="checkbox" value="none" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ is_array(old('diagnoses')) && in_array('none', old('diagnoses')) ? 'checked' : '' }}>
                    <label for="diagnosis_none" class="ml-3 block text-sm font-medium text-gray-700">診断はついていない</label>
                </div>
                <div class="flex items-center">
                    <input id="diagnosis_no_answer" name="diagnoses[]" type="checkbox" value="no_answer" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ is_array(old('diagnoses')) && in_array('no_answer', old('diagnoses')) ? 'checked' : '' }}>
                    <label for="diagnosis_no_answer" class="ml-3 block text-sm font-medium text-gray-700">回答したくない</label>
                </div>
            </div>

            <!-- その他の診断名（自由記述） -->
            <div id="other_diagnosis_section" class="mt-2 hidden">
                <x-input-label for="other_diagnosis" value="その他の診断名" />
                <x-text-input id="other_diagnosis" class="block mt-1 w-full" type="text" name="other_diagnosis" :value="old('other_diagnosis')" />
                <x-input-error :messages="$errors->get('other_diagnosis')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-between mt-8">
            <div>
                @if($siblingOrder < 4)
                <button type="submit" name="add_sibling" value="1" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    兄弟姉妹を追加する
                </button>
                @endif
            </div>
            <x-primary-button class="ml-4">
                確認画面へ
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 要素の取得
            const regularHospitalVisitYes = document.getElementById('regular_hospital_visit_yes');
            const regularHospitalVisitNo = document.getElementById('regular_hospital_visit_no');
            const medicalDepartmentsSection = document.getElementById('medical_departments_section');
            const diagnosesSection = document.getElementById('diagnoses_section');

            // その他の診療科のチェックボックスと入力フィールド
            const deptOther = document.getElementById('dept_other');
            const otherDepartmentSection = document.getElementById('other_department_section');

            // その他の診断名のチェックボックスと入力フィールド
            const diagnosisOther = document.getElementById('diagnosis_other');
            const otherDiagnosisSection = document.getElementById('other_diagnosis_section');

            // 「回答したくない」チェックボックス
            const deptNoAnswer = document.getElementById('dept_no_answer');
            const deptCheckboxes = document.querySelectorAll('input[name="medical_departments[]"]:not(#dept_no_answer)');
            
            // 診断名チェックボックス
            const diagnosisNoAnswer = document.getElementById('diagnosis_no_answer');
            const diagnosisNone = document.getElementById('diagnosis_none');
            const diagnosisCheckboxes = document.querySelectorAll('input[name="diagnoses[]"]:not(#diagnosis_no_answer):not(#diagnosis_none)');
            const diagnosisCheckboxesAll = document.querySelectorAll('input[name="diagnoses[]"]');

            // 初期表示の設定
            if (regularHospitalVisitYes.checked) {
                medicalDepartmentsSection.classList.remove('hidden');
                diagnosesSection.classList.remove('hidden');
            }

            if (deptOther.checked) {
                otherDepartmentSection.classList.remove('hidden');
            }

            if (diagnosisOther.checked) {
                otherDiagnosisSection.classList.remove('hidden');
            }

            // 「回答したくない」の動作設定
            if (deptNoAnswer) {
                deptNoAnswer.addEventListener('change', function() {
                    if (this.checked) {
                        deptCheckboxes.forEach(function(checkbox) {
                            checkbox.checked = false;
                            checkbox.disabled = true;
                        });
                        otherDepartmentSection.classList.add('hidden');
                        document.getElementById('other_department').value = '';
                    } else {
                        deptCheckboxes.forEach(function(checkbox) {
                            checkbox.disabled = false;
                        });
                    }
                });

                // 他の診療科チェックボックスの変更時
                deptCheckboxes.forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        if (this.checked && deptNoAnswer.checked) {
                            deptNoAnswer.checked = false;
                            deptCheckboxes.forEach(function(cb) {
                                cb.disabled = false;
                            });
                        }
                    });
                });
            }

            // 「診断はついていない」と「回答したくない」の動作設定
            if (diagnosisNoAnswer) {
                diagnosisNoAnswer.addEventListener('change', function() {
                    if (this.checked) {
                        diagnosisCheckboxes.forEach(function(checkbox) {
                            checkbox.checked = false;
                            checkbox.disabled = true;
                        });
                        if (diagnosisNone) {
                            diagnosisNone.checked = false;
                            diagnosisNone.disabled = true;
                        }
                        otherDiagnosisSection.classList.add('hidden');
                        document.getElementById('other_diagnosis').value = '';
                    } else {
                        diagnosisCheckboxes.forEach(function(checkbox) {
                            checkbox.disabled = false;
                        });
                        if (diagnosisNone) {
                            diagnosisNone.disabled = false;
                        }
                    }
                });
            }

            if (diagnosisNone) {
                diagnosisNone.addEventListener('change', function() {
                    if (this.checked) {
                        diagnosisCheckboxes.forEach(function(checkbox) {
                            checkbox.checked = false;
                            checkbox.disabled = true;
                        });
                        if (diagnosisNoAnswer) {
                            diagnosisNoAnswer.checked = false;
                            diagnosisNoAnswer.disabled = true;
                        }
                        otherDiagnosisSection.classList.add('hidden');
                        document.getElementById('other_diagnosis').value = '';
                    } else {
                        diagnosisCheckboxes.forEach(function(checkbox) {
                            checkbox.disabled = false;
                        });
                        if (diagnosisNoAnswer) {
                            diagnosisNoAnswer.disabled = false;
                        }
                    }
                });

                // 他の診断名チェックボックスの変更時
                diagnosisCheckboxes.forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        if (this.checked) {
                            if (diagnosisNoAnswer && diagnosisNoAnswer.checked) {
                                diagnosisNoAnswer.checked = false;
                                if (diagnosisNone) diagnosisNone.disabled = false;
                                diagnosisCheckboxes.forEach(function(cb) {
                                    cb.disabled = false;
                                });
                            }
                            if (diagnosisNone && diagnosisNone.checked) {
                                diagnosisNone.checked = false;
                                if (diagnosisNoAnswer) diagnosisNoAnswer.disabled = false;
                                diagnosisCheckboxes.forEach(function(cb) {
                                    cb.disabled = false;
                                });
                            }
                        }
                    });
                });
            }

            // 通院状況による表示切替
            regularHospitalVisitYes.addEventListener('change', function() {
                if (this.checked) {
                    medicalDepartmentsSection.classList.remove('hidden');
                    diagnosesSection.classList.remove('hidden');
                }
            });

            regularHospitalVisitNo.addEventListener('change', function() {
                if (this.checked) {
                    medicalDepartmentsSection.classList.add('hidden');
                    diagnosesSection.classList.add('hidden');
                    document.querySelectorAll('input[name="medical_departments[]"]').forEach(function(checkbox) {
                        checkbox.checked = false;
                        checkbox.disabled = false;
                    });
                    diagnosisCheckboxesAll.forEach(function(checkbox) {
                        checkbox.checked = false;
                        checkbox.disabled = false;
                    });
                    document.getElementById('other_department').value = '';
                    document.getElementById('other_diagnosis').value = '';
                    otherDepartmentSection.classList.add('hidden');
                    otherDiagnosisSection.classList.add('hidden');
                }
            });

            // その他の診療科
            deptOther.addEventListener('change', function() {
                if (this.checked) {
                    otherDepartmentSection.classList.remove('hidden');
                } else {
                    otherDepartmentSection.classList.add('hidden');
                    document.getElementById('other_department').value = '';
                }
            });

            // その他の診断名
            diagnosisOther.addEventListener('change', function() {
                if (this.checked) {
                    otherDiagnosisSection.classList.remove('hidden');
                } else {
                    otherDiagnosisSection.classList.add('hidden');
                    document.getElementById('other_diagnosis').value = '';
                }
            });
        });
    </script>
</x-guest-layout>