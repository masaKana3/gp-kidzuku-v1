<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// ⭐️ 2行追加
use App\Models\Schedule; // ⭐️ScheduleModelを利用するためここで呼び出す。
use Illuminate\Support\Facades\Auth; // ⭐️Auth::id()を利用するためここで呼び出す。
use Carbon\Carbon; // ← これが必要！

class ScheduleController extends Controller
{
    public function scheduleAdd(Request $request)
    {
	  // バリデーション設定。
        $request->validate([
            'start_date' => 'required|integer', // 必須。integer型。
            'end_date' => 'required|integer', // 必須。integer型。
            'event_name' => 'required|max:32', // 必須。最大32文字
        ]);

        // 登録処理
        $schedule = new Schedule;
        // 日付に変換。JavaScriptのタイムスタンプはミリ秒なので秒に変換
        $schedule->start_date = date('Y-m-d H:i:s', $request->input('start_date') / 1000);
        $schedule->end_date = date('Y-m-d H:i:s', $request->input('end_date') / 1000);
        $schedule->event_name = $request->input('event_name');
        $schedule->user_id = Auth::id(); // ログインユーザーのIDを設定
        $schedule->save();

        return; // viewの返答はいらないので、returnだけ書いて終わり。
    }

    public function scheduleGet(Request $request)
    {
        // バリデーション
        $request->validate([
            'start_date' => 'required|integer',
            'end_date' => 'required|integer'
        ]);

        // カレンダー表示期間
        // JSから送られてきた形式を下記で利用できる用に変形している
        $start_date = date('Y-m-d', $request->input('start_date') / 1000);
        $end_date = date('Y-m-d', $request->input('end_date') / 1000);

        // 取得処理
        $schedules = Schedule::query()
            ->select(
            // FullCalendarの形式に合わせる
                'start_date as start',
                'end_date as end',
                'event_name as title'
            )
            // FullCalendarの表示範囲のみ表示
            ->where('end_date', '>', $start_date)
            ->where('start_date', '<', $end_date)
            ->where('user_id', Auth::id()) // ログインユーザーのスケジュールのみ取得
            ->get();

            // 終日の予定を判別して変換
            $schedules = $schedules->map(function ($event) {
            $startDate = new \DateTime($event->start);
            $endDate = new \DateTime($event->end);

            if ($startDate->format('H:i:s') === '00:00:00' && $endDate->format('H:i:s') === '00:00:00') {
                $event->start = $startDate->format('Y-m-d');
                $event->end = $endDate->format('Y-m-d');
            }

            return $event;
        });

        return $schedules;
    }

    // ⭐️ スケジュールデータを取得する
    public function scheduleList()
    {
        $userId = Auth::id();
        $today = date('Y-m-d');

        // 本日の予定
        $todayEvents = Schedule::where('user_id', $userId)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderBy('start_date', 'asc')
            ->get();

        // 全ての予定（既存）
        $schedules = Schedule::where('user_id', $userId)
            ->orderBy('start_date', 'asc')
            ->get();

        // ① ここが追加部分：今後1週間の予定
        $upcomingSchedules = Schedule::where('user_id', $userId)
            ->whereDate('start_date', '>=', $today)
            ->whereDate('start_date', '<=', now()->addWeek())
            ->orderBy('start_date', 'asc')
            ->get();

        // Blade に渡す
        return view('schedule-list', compact('schedules', 'todayEvents', 'upcomingSchedules'));
}

    // ⭐️追加！！！
    // show($id)の$idは、URLで渡される'/schedules/{id}'のidの部分です。URLが`/schedules/19`だったら、この引数にも19が渡されます。
    public function show($id)
    {
        // findOrFail()は、データベースからレコードを取得し、もしそのレコードが存在しない場合は404エラー（Not Found）を出してくれる
        $schedule = Schedule::findOrFail($id);
        return view('schedule-show', ['schedule' => $schedule]);
    }

    // ⭐️追加。
    public function destroy($id)
    {
        // showメソッドと同じ。Scheduleからひとつ抜き出す。
        $schedule = Schedule::findOrFail($id);
        
        // delete()で文字通り削除。
        $schedule->delete();
        
        // 'schedule-list'にリダイレクト。'schedule-list'はrouteでつけた「あだ名」の部分です。
        // 
        return redirect()->route('schedule-list');
    }
    
    // ⭐️ 修正画面表示 追加。
    public function edit($id)
    {
        // showメソッドと同じ。Scheduleからひとつ抜き出す。
        $schedule = Schedule::findOrFail($id);
        
        return view('schedule-edit', ['schedule' => $schedule]);
    }

    // ⭐️追加
    public function update(Request $request, $id)
    {
        // ブラウザから送られる内容 = formのinputの内容に対してバリデーションの処理
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'event_name' => 'required|max:32',
            'details' => 'nullable|string|max:1024',
        ]);

        // URLで与えられたIDでスケジュールを検索
        $schedule = Schedule::findOrFail($id);
        
        // $request->inputでブラウザから送られてきた内容を各カラムに設定
        $schedule->start_date = $request->input('start_date');
        $schedule->end_date = $request->input('end_date');
        $schedule->event_name = $request->input('event_name');
        $schedule->details = $request->input('details');
        
        // 保存！
        $schedule->save();

        // 完了したら、リダイレクト。
        return redirect()->route('schedules.show', $schedule->id);
    }

    public function index(Request $request)
    {
        $selectedDate = $request->date;
        $formattedDate = $selectedDate 
            ? Carbon::parse($selectedDate)->format('Y年m月d日')
            : null;

        // 今日と1週間後
        $now = Carbon::now();
        $oneWeekLater = $now->copy()->addDays(7);

        // 今後1週間の予定を取得
        $upcomingSchedules = Schedule::where('user_id', Auth::id())
            ->whereBetween('start_date', [$now, $oneWeekLater])
            ->orderBy('start_date')
            ->get();

        // 本日の予定を取得
        $today = Carbon::today()->format('Y-m-d');
        $todayEvents = Schedule::where('user_id', Auth::id())
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderBy('start_date', 'asc')
            ->get();

        return view('schedule', compact('selectedDate', 'formattedDate', 'todayEvents'));
    }

    /**
     * スケジュールを保存する
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $allDay = $request->has('all_day');

        $startDate = $allDay
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::parse($request->start_date);

        $endDate = $allDay
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::parse($request->end_date);

        Schedule::create([
            'user_id' => Auth::id(),
            'event_name' => $request->event_name,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'all_day' => $allDay,
        ]);

        return redirect()->route('schedule-list')->with('success', 'スケジュールを追加しました');
    }
}
