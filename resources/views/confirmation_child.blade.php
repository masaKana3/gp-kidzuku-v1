<<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        <h2 class="text-lg font-medium text-gray-900">登録内容の確認（お子さま情報 {{ $siblingOrder }}/{{ $totalChildren }}）</h2>
        <p>登録した情報を確認してください。修正が必要な場合は「修正する」ボタンを押してください。</p>
    </div>

    <div class="mt-6">
        <h3 class="text-md font-medium text-gray-800">お子さま情報 ({{ $siblingOrder }}人目)</h3>
        <div class="mt-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600">お名前</p>
                        <p>{{ $child->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">生年月日</p>
                        <p>{{ $child->birth_date }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">性別</p>
                        <p>
                            @if($child->gender == 'male')
                                男性
                            @elseif($child->gender == 'female')
                                女性
                            @else
                                回答しない
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-600">月経周期登録</p>
                    <p>{{ $child->menstruation_tracking ? '登録する' : '登録しない' }}</p>
                </div>
                
                @if($child->menstruation_tracking && $child->last_period_date)
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-600">最近の月経開始日</p>
                    <p>{{ $child->last_period_date }}</p>
                </div>
                @endif
                
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-600">通学状況</p>
                    <p>
                        @if($child->school_attendance == 'everyday')
                            毎日通学している
                        @elseif($child->school_attendance == 'sometimes_absent')
                            たまに休む
                        @elseif($child->school_attendance == 'often_absent')
                            欠席が多い
                        @elseif($child->school_attendance == 'separate_room')
                            別室登校をしている
                        @elseif($child->school_attendance == 'late_afternoon')
                            遅刻もしくは午後から登校する
                        @elseif($child->school_attendance == 'not_recently')
                            最近通えていない
                        @elseif($child->school_attendance == 'free_school')
                            フリースクールに通っている
                        @elseif($child->school_attendance == 'transfer_experience')
                            転校・退学・編入の経験がある
                        @elseif($child->school_attendance == 'correspondence')
                            通信制・定時制に通学している（高校生のみ）
                        @endif
                    </p>
                </div>
                
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-600">定期的な通院</p>
                    <p>{{ $child->regular_hospital_visit ? 'あり' : 'なし' }}</p>
                </div>
                
                @if($child->regular_hospital_visit && $child->medical_departments)
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-600">かかりつけの診療科</p>
                    <ul class="list-disc pl-5">
                        @foreach($child->medical_departments as $department)
                            <li>
                                @if($department == 'pediatrics')
                                    小児科
                                @elseif($department == 'child_adolescent')
                                    児童・思春期発達外来
                                @elseif($department == 'internal')
                                    内科
                                @elseif($department == 'ophthalmology')
                                    眼科
                                @elseif($department == 'dermatology')
                                    皮膚科
                                @elseif($department == 'otolaryngology')
                                    耳鼻咽喉科
                                @elseif($department == 'gynecology')
                                    婦人科
                                @elseif($department == 'orthopedics')
                                    整形外科
                                @elseif($department == 'dentistry')
                                    歯科
                                @elseif($department == 'psychiatry')
                                    精神科
                                @elseif($department == 'neurology')
                                    神経内科
                                @elseif($department == 'other')
                                    その他: {{ $child->other_department }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                @if($child->regular_hospital_visit && $child->diagnoses)
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-600">診断名</p>
                    <ul class="list-disc pl-5">
                        @foreach($child->diagnoses as $diagnosis)
                            <li>
                                @if($diagnosis == 'ods')
                                    起立性調節障害
                                @elseif($diagnosis == 'menstrual')
                                    月経困難症・PMS
                                @elseif($diagnosis == 'mental')
                                    メンタルに関する診断
                                @elseif($diagnosis == 'development')
                                    発達に関する診断
                                @elseif($diagnosis == 'other')
                                    その他: {{ $child->other_diagnosis }}
                                @elseif($diagnosis == 'none')
                                    診断はついていない
                                @elseif($diagnosis == 'no_answer')
                                    回答したくない
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('surveys.child.edit', ['siblingOrder' => $child->sibling_order]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        修正する
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-center space-x-4">
        @if($siblingOrder > 1)
            <a href="{{ route('surveys.confirmation.child', ['siblingOrder' => $siblingOrder - 1]) }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-400 active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                前のお子さま
            </a>
        @else
            <a href="{{ route('surveys.confirmation') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-400 active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                保護者情報に戻る
            </a>
        @endif
        
        @if($siblingOrder < $totalChildren)
            <a href="{{ route('surveys.confirmation.child', ['siblingOrder' => $siblingOrder + 1]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                次のお子さま
            </a>
        @else
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                登録を完了する
            </a>
        @endif
    </div>
</x-guest-layout>