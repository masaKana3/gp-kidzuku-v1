<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>KiDzUKu AI相談</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<style>
        .chat-container {
            max-width: 700px;
            margin: 0 auto;
        }

        .chat-message {
            display: flex;
            margin: 10px 0;
        }

        .chat-message.user {
            justify-content: flex-end;
        }

        .chat-message.ai {
            justify-content: flex-start;
        }

        .bubble {
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 15px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            font-size: 15px;
            line-height: 1.4;
        }

        .chat-message.user .bubble {
            background-color: #DCF8C6; /* LINE風の緑 */
            text-align: left;
        }

        .chat-message.ai .bubble {
            background-color: #F1F0F0;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        @yield('content')
    </div>
</body>
</html>