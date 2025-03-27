<?php

namespace App\Http\Controllers;

use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Str;
use App\Models\AiConsultation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AiConsultController extends Controller
{
    // 相談履歴の一覧表示
    public function index()
    {
        $consultations = AiConsultation::where('user_id', Auth::id())
                            ->orderBy('created_at', 'asc')
                            ->get();

        return view('ai-consult.index', compact('consultations'));
    }

    // 相談を受け取り、DBに保存してリダイレクト
    public function store(Request $request)
    {
        $request->validate([
        'question' => 'required|string',
    ]);

        $question = $request->input('question');

        // ⭐️ Gemini呼び出し処理をここで実行
        $gemini = Gemini::generativeModel(model: 'models/gemini-1.5-pro-002');
        $result = $gemini->generateContent($question);
        $markdownText = $result->text();
        $answer = Str::markdown($markdownText); // HTMLに変換されたもの

        // ⭐️ DBに保存
        AiConsultation::create([
            'user_id' => Auth::id(),
            'question' => $question,
            'answer' => $answer, // HTMLで保存しておくと表示も簡単
        ]);

        // 表示ページへリダイレクト（responseの渡しはBlade側で処理）
        return redirect()->route('ai-consult.index');
    }
}