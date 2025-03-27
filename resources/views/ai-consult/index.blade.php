<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>KiDzUKu AI相談</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f7;
            font-family: 'Arial', sans-serif;
            padding: 30px 15px;
        }

        .card-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            height: 90vh;
            overflow: hidden;
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        .card-header h2 {
            font-size: 20px;
            margin: 0;
        }

        /* ↓ ここにロゴ画像を後で追加できます */
        /*
        <img src="{{ asset('images/logo.png') }}" alt="ロゴ" style="height: 50px;">
        */

        .chat-area {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
        }

        .chat-message {
            display: flex;
            margin: 12px 0;
        }

        .chat-message.user {
            justify-content: flex-end;
        }

        .chat-message.ai {
            justify-content: flex-start;
        }

        .bubble {
            max-width: 75%;
            padding: 12px 16px;
            border-radius: 16px;
            font-size: 15px;
            line-height: 1.5;
            position: relative;
        }

        .chat-message.user .bubble {
            background-color: #dcf8c6;
            text-align: left;
        }

        .chat-message.ai .bubble {
            background-color: #f1f0f0;
            text-align: left;
        }

        .timestamp {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
            text-align: right;
        }

        .chat-form {
            padding: 20px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="card-wrapper">
        <div class="card-header">
            {{-- ロゴ画像を追加したい場合は以下のコメントを参考に --}}
            {{-- <img src="{{ asset('images/kidzuku_logo.png') }}" alt="KiDzUKu Logo" style="height: 50px;"> --}}
            <h2>詳細内容をAIに相談しよう！</h2>
        </div>

        <div class="chat-area" id="chat-area">
            {{-- チャット履歴 --}}
            @foreach ($consultations as $chat)
                <div class="chat-message user">
                    <div class="bubble">
                        👩‍👧 <strong>あなた：</strong><br>
                        {{ $chat->question }}
                        <div class="timestamp">{{ $chat->created_at->format('Y/m/d H:i') }}</div>
                    </div>
                </div>

                <div class="chat-message ai">
                    <div class="bubble">
                        🤖 <strong>AI：</strong><br>
                        {!! $chat->answer !!}
                        <div class="timestamp">{{ $chat->created_at->format('Y/m/d H:i') }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="chat-form">
            {{-- 入力フォーム --}}
            <form method="POST" action="{{ route('ai-consult.store') }}">
                @csrf
                <textarea name="question" rows="3" class="form-control" placeholder="相談内容を入力..." required></textarea>
                <button type="submit" class="btn btn-primary mt-2">相談する</button>
            </form>

            {{-- 戻るボタン --}}
            <div class="text-center mt-3">
                <a href="{{ route('conditions.history') }}" class="btn btn-secondary">
                    ← 体調記録履歴に戻る
                </a>
            </div>
        </div>
    </div>

    {{-- 自動スクロール --}}
    <script>
        window.onload = function () {
            const chat = document.getElementById('chat-area');
            chat.scrollTop = chat.scrollHeight;
        };
    </script>
</body>
</html>