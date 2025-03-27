<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        <h2 class="text-lg font-medium text-gray-900">登録内容の確認（保護者情報）</h2>
        <p>登録した情報を確認してください。修正が必要な場合は「修正する」ボタンを押してください。</p>
    </div>

    <div class="mt-6">
        <h3 class="text-md font-medium text-gray-800">保護者情報</h3>
        <div class="mt-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                @if($parentSurvey)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">生年月日</p>
                            <p>{{ $parentSurvey->birth_date }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">性別</p>
                            <p>
                                @if($parentSurvey->gender == 'male')
                                    男性
                                @elseif($parentSurvey->gender == 'female')
                                    女性
                                @else
                                    回答しない
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-600">健康上の悩みや不安</p>
                        <p>{{ $parentSurvey->health_concern ? 'あり' : 'なし' }}</p>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-600">健康状態の自己評価</p>
                        <p>{{ $parentSurvey->health_rating }} / 5</p>
                    </div>
                    
                    @if($parentSurvey->concerns)
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-600">特に気になること</p>
                        <ul class="list-disc pl-5">
                            @foreach($parentSurvey->concerns as $concern)
                                <li>
                                    @if($concern == 'physical')
                                        身体の調子がすぐれない
                                    @elseif($concern == 'mental')
                                        心の調子がすぐれない
                                    @elseif($concern == 'tired')
                                        なんとなく疲れが取れない
                                    @elseif($concern == 'motivation')
                                        やる気が起きない
                                    @elseif($concern == 'none')
                                        この中にはない
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    @if($parentSurvey->consultants)
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-600">お子さまのことをどなたに相談していますか</p>
                        <ul class="list-disc pl-5">
                            @foreach($parentSurvey->consultants as $consultant)
                                <li>
                                    @if($consultant == 'spouse')
                                        配偶者・パートナー
                                    @elseif($consultant == 'family')
                                        家族
                                    @elseif($consultant == 'siblings')
                                        自分の兄弟・姉妹
                                    @elseif($consultant == 'friend')
                                        友人
                                    @elseif($consultant == 'teacher')
                                        学校の先生
                                    @elseif($consultant == 'counselor')
                                        スクールカウンセラー
                                    @elseif($consultant == 'cram_teacher')
                                        塾の先生
                                    @elseif($consultant == 'doctor')
                                        専門医
                                    @elseif($consultant == 'psychologist')
                                        臨床心理士・カウンセラー
                                    @elseif($consultant == 'none')
                                        いない
                                    @elseif($consultant == 'other')
                                        その他: {{ $parentSurvey->other_consultant }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-600">定期的な通院</p>
                        <p>{{ $parentSurvey->regular_hospital_visit ? 'はい' : 'いいえ' }}</p>
                    </div>
                    
                    @if($parentSurvey->medical_departments)
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-600">かかりつけの診療科</p>
                        <ul class="list-disc pl-5">
                            @foreach($parentSurvey->medical_departments as $department)
                                <li>
                                    @if($department == 'internal')
                                        内科
                                    @elseif($department == 'eye')
                                        眼科
                                    @elseif($department == 'dermatology')
                                        皮膚科
                                    @elseif($department == 'ent')
                                        耳鼻咽喉科
                                    @elseif($department == 'obstetrics')
                                        産婦人科
                                    @elseif($department == 'psychiatry')
                                        心療内科・精神科
                                    @elseif($department == 'dental')
                                        歯科
                                    @elseif($department == 'other')
                                        その他: {{ $parentSurvey->other_department }}
                                    @elseif($department == 'no_answer')
                                        回答したくない
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-600">月経周期登録</p>
                        <p>{{ $parentSurvey->menstruation_tracking ? '登録する' : '登録しない' }}</p>
                    </div>
                    
                    @if($parentSurvey->menstruation_tracking && $parentSurvey->last_period_date)
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-600">最近の月経開始日</p>
                        <p>{{ $parentSurvey->last_period_date }}</p>
                    </div>
                    @endif
                    
                    <div class="mt-6 flex justify-center">
                        <a href="{{ route('surveys.parent.edit') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            修正する
                        </a>
                    </div>
                @else
                    <p class="text-sm text-gray-500">保護者情報が登録されていません。</p>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-center space-x-4">
        <a href="{{ route('surveys.confirmation.child', ['siblingOrder' => 1]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
            お子さま情報の確認へ
        </a>
    </div>
</x-guest-layout>