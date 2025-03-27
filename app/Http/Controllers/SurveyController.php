<?php

namespace App\Http\Controllers;

use App\Models\ParentSurvey;
use App\Models\ChildSurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SurveyController extends Controller
{
    /**
     * 保護者アンケートフォームを表示
     */
    public function showParentSurvey(): View
    {
        logger('Session Keys:', session()->all());

        // フォームをリセットしたいだけなら、個別にキーを削除
        session()->forget([
            'parent_survey_completed',
            'child_basic_1',
            'child_basic_2',
            'child_basic_3',
            'child_basic_4'
    ]);
        
        return view('parent');
    }

    /**
     * 保護者アンケートの送信処理
     */
    public function submitParentSurvey(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female,no_answer',
            'health_concern' => 'required|boolean',
            'health_rating' => 'required|integer|min:1|max:5',
            'concerns' => 'nullable|array',
            'consultants' => 'nullable|array',
            'other_consultant' => 'nullable|string|max:255',
            'regular_hospital_visit' => 'required|boolean',
            'medical_departments' => 'nullable|array',
            'other_department' => 'nullable|string|max:255',
            'menstruation_tracking' => 'required|boolean',
            'last_period_date' => 'nullable|date',
        ]);

        // ユーザーIDを追加（ログイン済みの場合はAuth::id()、未ログインの場合はセッションID）
        $validated['user_id'] = Auth::id() ?? session()->getId();

        // データを保存
        ParentSurvey::updateOrCreate(
            ['user_id' => $validated['user_id']],
            $validated
        );

        // セッションに保護者データを保存
        session()->put('parent_survey_completed', true);
        session()->save();

        // お子さまアンケートにリダイレクト
        return redirect()->route('surveys.child', ['siblingOrder' => 1]);
    }

    /**
     * 子供のアンケートフォームを表示
     */
    public function showChildSurvey(Request $request, int $siblingOrder = 1): View
    {
        // 兄弟姉妹の順番が1〜4の範囲内かチェック
        if ($siblingOrder < 1 || $siblingOrder > 4) {
            abort(404);
        }

        // セッションが有効かチェック
        if (!session()->has('parent_survey_completed')) {
            // セッションが無効な場合は保護者アンケートにリダイレクト
            return redirect()->route('surveys.parent')->with('error', 'セッションが無効です。もう一度お試しください。');
        }

        return view('child', [
            'siblingOrder' => $siblingOrder
        ]);
    }

    /**
     * 子供のアンケート基本情報の送信処理
     */
    public function submitChildSurvey(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|string',
            'menstruation_tracking' => 'required|boolean',
            'last_period_date' => 'nullable|date',
            'sibling_order' => 'required|integer|min:1|max:4',
        ]);

        // セッションに一時保存
        session()->put('child_basic_' . $validated['sibling_order'], $validated);

        // 詳細質問ページにリダイレクト
        return redirect()->route('surveys.child.details', ['siblingOrder' => $validated['sibling_order']]);
    }

    /**
     * 子供のアンケート詳細質問フォームを表示
     */
    public function showChildDetailsSurvey(Request $request, int $siblingOrder = 1): View
    {
        // 兄弟姉妹の順番が1〜4の範囲内かチェック
        if ($siblingOrder < 1 || $siblingOrder > 4) {
            abort(404);
        }

        // 基本情報がセッションにあるかチェック
        $basicInfo = session()->get('child_basic_' . $siblingOrder);
        if (!$basicInfo) {
            return redirect()->route('surveys.child', ['siblingOrder' => $siblingOrder]);
        }

        return view('child_details', [
            'siblingOrder' => $siblingOrder,
            'childName' => $basicInfo['name']
        ]);
    }

    /**
     * 子供のアンケート詳細質問の送信処理
     */
    public function submitChildDetailsSurvey(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'sibling_order' => 'required|integer|min:1|max:4',
            'school_attendance' => 'required|string',
            'regular_hospital_visit' => 'required|boolean',
            'medical_departments' => 'nullable|array',
            'other_department' => 'nullable|string',
            'diagnoses' => 'nullable|array',
            'other_diagnosis' => 'nullable|string',
        ]);

        // 基本情報をセッションから取得
        $siblingOrder = $validated['sibling_order'];
        $basicInfo = session()->get('child_basic_' . $siblingOrder);
        
        if (!$basicInfo) {
            return redirect()->route('surveys.child', ['siblingOrder' => $siblingOrder]);
        }

        // 基本情報と詳細情報をマージ
        $childData = array_merge($basicInfo, $validated);

        // ユーザーIDを追加（ログイン済みの場合はAuth::id()、未ログインの場合はセッションID）
        $childData['user_id'] = Auth::id() ?? session()->getId();

        // データを保存
        ChildSurvey::updateOrCreate(
            [
                'user_id' => $childData['user_id'],
                'sibling_order' => $siblingOrder
            ],
            $childData
        );

        // セッションから基本情報を削除
        session()->forget('child_basic_' . $siblingOrder);

        // 次の兄弟姉妹登録か確認画面へ
        if ($request->has('add_sibling') && $siblingOrder < 4) {
            return redirect()->route('surveys.child', ['siblingOrder' => $siblingOrder + 1]);
        } else {
            return redirect()->route('surveys.confirmation');
        }
    }

    /**
     * 登録内容確認画面を表示（保護者情報）
     */
    public function showConfirmation(): View
    {
        $userId = Auth::id() ?? session()->getId();

        // 保護者情報を取得
        $parentSurvey = ParentSurvey::where('user_id', $userId)->first();

        return view('confirmation', [
            'parentSurvey' => $parentSurvey
        ]);
    }

    /**
     * 特定のお子さま情報確認画面を表示
     */
    public function showChildConfirmation(int $siblingOrder): View
    {
        $userId = Auth::id() ?? session()->getId();

        // 特定の子供情報を取得
        $childSurvey = ChildSurvey::where('user_id', $userId)
            ->where('sibling_order', $siblingOrder)
            ->first();
        
        if (!$childSurvey) {
            return redirect()->route('surveys.confirmation');
        }

        // 子供の総数を取得（ページネーション用）
        $totalChildren = ChildSurvey::where('user_id', $userId)->count();

        return view('confirmation_child', [
            'child' => $childSurvey,
            'siblingOrder' => $siblingOrder,
            'totalChildren' => $totalChildren
        ]);
    }

    /**
     * 保護者アンケート編集画面を表示
     */
    public function editParentSurvey(): View
    {
        $userId = Auth::id() ?? session()->getId();
        $parentSurvey = ParentSurvey::where('user_id', $userId)->first();

        if (!$parentSurvey) {
            return redirect()->route('surveys.parent');
        }

        return view('surveys.parent_edit', [
            'parentSurvey' => $parentSurvey,
            'isEditing' => true
        ]);
    }

    /**
     * 子供アンケート編集画面を表示
     */
    public function editChildSurvey(int $siblingOrder): View
    {
        $userId = Auth::id() ?? session()->getId();
        $childSurvey = ChildSurvey::where('user_id', $userId)
            ->where('sibling_order', $siblingOrder)
            ->first();

        if (!$childSurvey) {
            return redirect()->route('surveys.child', ['siblingOrder' => $siblingOrder]);
        }

        return view('surveys.child_edit', [
            'childSurvey' => $childSurvey,
            'siblingOrder' => $siblingOrder
        ]);
    }

    /**
     * アンケート完了ページを表示
     */
    public function showCompletedPage(): View
    {
        return view('completed');
    }

    /**
     * 保護者アンケートの更新処理
     */
    public function updateParentSurvey(Request $request)
    {
        $validated = $request->validate([
            'birth_date' => 'required|date',
            'gender' => 'required|string',
            'health_concern' => 'required|boolean',
            'health_rating' => 'required|integer|min:1|max:5',
            'concerns' => 'nullable|array',
            'consultants' => 'nullable|array',
            'other_consultant' => 'nullable|string',
            'regular_hospital_visit' => 'required|boolean',
            'medical_departments' => 'nullable|array',
            'other_department' => 'nullable|string',
            'menstruation_tracking' => 'required|boolean',
            'last_period_date' => 'nullable|date',
        ]);

        $validated['user_id'] = Auth::id();

        ParentSurvey::updateOrCreate(
            ['user_id' => $validated['user_id']],
            $validated
        );

        return redirect()->route('surveys.confirmation')->with('success', '保護者情報を更新しました。');
    }

    /**
     * 子供アンケートの更新処理
     */
    public function updateChildSurvey(Request $request)
    {
        $validated = $request->validate([
            'sibling_order' => 'required|integer|min:1|max:4',
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|string|in:male,female,other',
            'menstruation_tracking' => 'required|boolean',
            'last_period_date' => 'nullable|date',
        ]);

        $userId = Auth::id() ?? session()->getId();
        $siblingOrder = $validated['sibling_order'];

        ChildSurvey::updateOrCreate(
            [
                'user_id' => $userId,
                'sibling_order' => $siblingOrder
            ],
            $validated
        );

        return redirect()->route('surveys.confirmation.child', ['siblingOrder' => $siblingOrder])
            ->with('success', 'お子さまの情報を更新しました。');
    }
}
