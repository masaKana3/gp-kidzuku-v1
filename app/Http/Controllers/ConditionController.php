<?php

namespace App\Http\Controllers;

use App\Models\ChildSurvey;
use App\Models\DailyConditionRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View; // 上部でインポート済でなければ追加

class ConditionController extends Controller
{
    /**
     * 体調記録フォームを表示
     */
    public function create(ChildSurvey $childSurvey)
    {
        // 今日の日付を取得
        $today = Carbon::today();
        $dayOfWeek = ['日曜', '月曜', '火曜', '水曜', '木曜', '金曜', '土曜'][$today->dayOfWeek];
        
        // 天気情報を取得（OpenWeatherAPIを使用）
        $weatherData = $this->getWeatherData();
        
        // 既に今日の記録があるか確認
        $existingRecord = DailyConditionRecord::where('child_survey_id', $childSurvey->id)
            ->where('record_date', $today->format('Y-m-d'))
            ->first();
            
        if ($existingRecord) {
            return redirect()->route('conditions.edit', ['childSurvey' => $childSurvey->id, 'dailyConditionRecord' => $existingRecord->id])
                ->with('info', '今日の記録は既に登録されています。編集画面に移動しました。');
        }
        
        // 診断名を取得
        $diagnoses = $childSurvey->diagnoses;
        
        // 表示する質問パターンを決定
        $showOdsQuestions = in_array('ods', $diagnoses);
        $showMenstrualQuestions = in_array('menstrual', $diagnoses);
        $showMenstruationTrackingQuestions = $showMenstrualQuestions && $childSurvey->gender === '女性';
        
        return view('conditions.create', compact(
            'childSurvey', 
            'today', 
            'dayOfWeek', 
            'weatherData',
            'showOdsQuestions',
            'showMenstrualQuestions',
            'showMenstruationTrackingQuestions'
        ));
    }
    
    /**
     * 体調記録を保存
     */
    public function store(Request $request, ChildSurvey $childSurvey)
    {
        // バリデーション
        $validated = $request->validate([
            'mood_rating' => 'required|integer|min:1|max:5',
            'woke_up_well' => 'nullable|boolean',
            'body_fatigue' => 'nullable|boolean',
            'sleep_quality' => 'nullable|boolean',
            'headache' => 'nullable|boolean',
            'stomachache' => 'nullable|boolean',
            'dizziness' => 'nullable|boolean',
            'blood_pressure_high' => 'nullable|integer|min:0|max:300',
            'blood_pressure_low' => 'nullable|integer|min:0|max:300',
            'irritability' => 'nullable|boolean',
            'depression' => 'nullable|boolean',
            'is_menstruating' => 'nullable|boolean',
            'blood_amount' => 'nullable|in:少ない,普通,多い',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // 天気情報を取得
        $weatherData = $this->getWeatherData();
        
        // 記録を作成
        $record = new DailyConditionRecord();
        $record->child_survey_id = $childSurvey->id;
        $record->record_date = Carbon::today()->format('Y-m-d');
        
        // 全員共通の質問項目
        $record->mood_rating = $validated['mood_rating'] ?? null;
        $record->woke_up_well = $validated['woke_up_well'] ?? null;
        $record->body_fatigue = $validated['body_fatigue'] ?? null;
        $record->sleep_quality = $validated['sleep_quality'] ?? null;
        $record->headache = $validated['headache'] ?? null;
        $record->stomachache = $validated['stomachache'] ?? null;
        
        // 条件付き質問項目
        if (isset($validated['dizziness'])) {
            $record->dizziness = $validated['dizziness'];
        }
        
        if (isset($validated['blood_pressure_high']) && isset($validated['blood_pressure_low'])) {
            $record->blood_pressure_high = $validated['blood_pressure_high'];
            $record->blood_pressure_low = $validated['blood_pressure_low'];
        }
        
        if (isset($validated['irritability'])) {
            $record->irritability = $validated['irritability'];
        }
        
        if (isset($validated['depression'])) {
            $record->depression = $validated['depression'];
        }
        
        if (isset($validated['is_menstruating'])) {
            $record->is_menstruating = $validated['is_menstruating'];
            
            if ($validated['is_menstruating'] && isset($validated['blood_amount'])) {
                $record->blood_amount = $validated['blood_amount'];
            }
        }
        
        // 天気情報
        if ($weatherData) {
            $record->weather = $weatherData['weather'] ?? null;
            $record->temperature = $weatherData['temperature'] ?? null;
            $record->pressure = $weatherData['pressure'] ?? null;
        }
        
        $record->notes = $validated['notes'] ?? null;
        $record->save();
        
        return redirect()->route('dashboard')
            ->with('success', $childSurvey->name . 'さんの体調記録を保存しました。');
    }
    
    /**
     * 体調記録編集フォームを表示
     */
    public function edit(ChildSurvey $childSurvey, DailyConditionRecord $dailyConditionRecord)
    {
        // 記録の日付を取得
        $recordDate = Carbon::parse($dailyConditionRecord->record_date);
        $dayOfWeek = ['日曜', '月曜', '火曜', '水曜', '木曜', '金曜', '土曜'][$recordDate->dayOfWeek];
        
        // 診断名を取得
        $diagnoses = is_array($childSurvey->diagnoses) 
    ? $childSurvey->diagnoses 
    : json_decode($childSurvey->diagnoses, true);
        
        // 表示する質問パターンを決定
        $showOdsQuestions = in_array('ods', $diagnoses);
        $showMenstrualQuestions = in_array('menstrual', $diagnoses);
        $showMenstruationTrackingQuestions = $showMenstrualQuestions && $childSurvey->gender === '女性';
        
        return view('conditions.edit', compact(
            'childSurvey', 
            'dailyConditionRecord', 
            'recordDate', 
            'dayOfWeek',
            'showOdsQuestions',
            'showMenstrualQuestions',
            'showMenstruationTrackingQuestions'
        ));
    }
    
    /**
     * 体調記録を更新
     */
    public function update(Request $request, ChildSurvey $childSurvey, DailyConditionRecord $dailyConditionRecord)
    {
        // バリデーション
        $validated = $request->validate([
            'mood_rating' => 'required|integer|min:1|max:5',
            'woke_up_well' => 'nullable|boolean',
            'body_fatigue' => 'nullable|boolean',
            'sleep_quality' => 'nullable|boolean',
            'headache' => 'nullable|boolean',
            'stomachache' => 'nullable|boolean',
            'dizziness' => 'nullable|boolean',
            'blood_pressure_high' => 'nullable|integer|min:0|max:300',
            'blood_pressure_low' => 'nullable|integer|min:0|max:300',
            'irritability' => 'nullable|boolean',
            'depression' => 'nullable|boolean',
            'is_menstruating' => 'nullable|boolean',
            'blood_amount' => 'nullable|in:少ない,普通,多い',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // 全員共通の質問項目
        $dailyConditionRecord->mood_rating = $validated['mood_rating'] ?? null  ;
        $dailyConditionRecord->woke_up_well = $validated['woke_up_well'] ?? null;
        $dailyConditionRecord->body_fatigue = $validated['body_fatigue'] ?? null;
        $dailyConditionRecord->sleep_quality = $validated['sleep_quality'] ?? null;
        $dailyConditionRecord->headache = $validated['headache'] ?? null;
        $dailyConditionRecord->stomachache = $validated['stomachache'] ?? null;
        
        // 条件付き質問項目
        if (isset($validated['dizziness'])) {
            $dailyConditionRecord->dizziness = $validated['dizziness'];
        }
        
        if (isset($validated['blood_pressure_high']) && isset($validated['blood_pressure_low'])) {
            $dailyConditionRecord->blood_pressure_high = $validated['blood_pressure_high'];
            $dailyConditionRecord->blood_pressure_low = $validated['blood_pressure_low'];
        } else {
            $dailyConditionRecord->blood_pressure_high = null;
            $dailyConditionRecord->blood_pressure_low = null;
        }
        
        if (isset($validated['irritability'])) {
            $dailyConditionRecord->irritability = $validated['irritability'];
        }
        
        if (isset($validated['depression'])) {
            $dailyConditionRecord->depression = $validated['depression'];
        }
        
        if (isset($validated['is_menstruating'])) {
            $dailyConditionRecord->is_menstruating = $validated['is_menstruating'];
            
            if ($validated['is_menstruating'] && isset($validated['blood_amount'])) {
                $dailyConditionRecord->blood_amount = $validated['blood_amount'];
            } else {
                $dailyConditionRecord->blood_amount = null;
            }
        }
        
        $dailyConditionRecord->notes = $validated['notes'] ?? null;
        $dailyConditionRecord->save();
        
        return redirect()->route('dashboard')
            ->with('success', $childSurvey->name . 'さんの体調記録を更新しました。');
    }
    
    /**
     * 体調記録の履歴を表示
     */
    public function history()
    {
        // ログインユーザーの子どもを取得
        $children = ChildSurvey::where('user_id', Auth::id())->get();
        
        // 子どもごとの体調記録を取得
        $conditionRecords = [];
        foreach ($children as $child) {
            $records = DailyConditionRecord::where('child_survey_id', $child->id)
                ->orderBy('record_date', 'desc')
                ->get();
                
            $conditionRecords[$child->id] = $records;
        }
        
        return view('conditions.condition-history', [
            'children' => $children,
            'conditionRecords' => $conditionRecords
        ]);
    }
    
    /**
     * OpenWeather APIから天気情報を取得
     */
    private function getWeatherData()
    {
        try {
            $apiKey = config('services.openweather.key');
            $city = 'Tokyo'; // デフォルトは東京
            
            $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'ja'
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'weather' => $data['weather'][0]['description'] ?? null,
                    'temperature' => $data['main']['temp'] ?? null,
                    'pressure' => $data['main']['pressure'] ?? null,
                ];
            }
            
            return null;
        } catch (\Exception $e) {
            // APIエラー時は天気情報なしで続行
            return null;
        }
    }

    /**
     * 1日の振り返り入力フォームを表示
     */
    public function lookBackCreate(ChildSurvey $childSurvey)
    {
        $today = Carbon::today();
        $dayOfWeek = ['日曜', '月曜', '火曜', '水曜', '木曜', '金曜', '土曜'][$today->dayOfWeek];

        return view('conditions.look-back', compact('childSurvey', 'today', 'dayOfWeek'));
    }

    /**
     * 1日の振り返りを保存
     */
    public function lookBackStore(Request $request, ChildSurvey $childSurvey)
    {
        $validated = $request->validate([
            'evening_mood_rating' => 'required|integer|min:1|max:5',
            'evening_health_rating' => 'required|integer|min:1|max:5',
            'evening_headache' => 'nullable|boolean',
            'evening_stomachache' => 'nullable|boolean',
            'evening_fatigue' => 'nullable|boolean',
            'evening_sleepiness' => 'nullable|boolean',
            'evening_dizziness' => 'nullable|boolean',
            'evening_appetite' => 'nullable|boolean',
            'evening_notes' => 'nullable|string|max:1000',
        ]);

        $record = new DailyConditionRecord();
        $record->child_survey_id = $childSurvey->id;
        $record->record_date = Carbon::today()->format('Y-m-d');
        $record->is_look_back = true;

        // フォームからの値を保存
        foreach ($validated as $key => $value) {
            $record->$key = $value;
        }

        $record->save();

        return redirect()->route('dashboard')->with('success', '1日の振り返りを記録しました。');
    }
}
