<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KiDzUKu - お子さまの健康管理をサポート</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <x-common-style />
    
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo-container">
                <img src="{{ asset('images/kidzuku_logo.png') }}" alt="KiDzUKu Logo" class="logo-img">
            </div>
            <!-- <div class="tagline">気づいて築く 母娘の DAILY CARE APP</div> -->
        </header>
        
        <h1 class="headline">きづくでお子さまの「なんとなく不調」に気づける毎日を</h1>
        <p class="subheadline">体調の変化と気象、スケジュールを同時に管理できるみまもりアプリ</p>
        
        <div class="widgets">
            <div class="widget weather-widget">
                <h3 class="widget-title">天気情報</h3>
                @if(isset($weather['weather']))
                    <div class="mb-2 text-sm text-gray-500">
                        {{ \Carbon\Carbon::now()->format('n月j日 H:i') }} ｜ {{ $weather['name'] ?? '不明' }}, {{ $weather['sys']['country'] ?? 'JP' }}
                    </div>
                    <div class="flex items-center space-x-4">
                        <img src="http://openweathermap.org/img/wn/{{ $weather['weather'][0]['icon'] }}@2x.png" alt="天気アイコン">
                        <div>
                            <p class="text-xl font-bold">{{ $weather['main']['temp'] }}℃</p>
                            <p class="text-sm text-gray-600">{{ $weather['weather'][0]['description'] }}</p>
                            <p class="text-sm">体感温度：{{ $weather['main']['feels_like'] }}℃</p>
                            <p class="text-sm">湿度：{{ $weather['main']['humidity'] }}%｜風速：{{ $weather['wind']['speed'] }} m/s</p>
                            <p class="text-sm">気圧：{{ $weather['main']['pressure'] }} hPa｜視程：{{ $weather['visibility'] / 1000 }} km</p>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-red-500">天気情報を取得できませんでした。</p>
                @endif
            </div>
            
            <div class="widget calendar-widget">
                <h3 class="widget-title">カレンダー</h3>
                <p>体調の記録と一緒に確認できるので、無理のないスケジュール調整に役立ちます。</p>
                <p>※ ログイン後、スケジュールの追加・編集が可能になります。</p>
            </div>
        </div>
        
        <div class="features">
            <div class="feature-card">
                <h3 class="feature-title">体調記録</h3>
                <p>低血圧、めまい、月経のつらさなど、「言葉にしづらい体調の変化」を簡単に記録できます。</p>
            </div>
            
            <div class="feature-card">
                <h3 class="feature-title">データ分析</h3>
                <p>記録したデータをグラフで可視化。体調の変化や傾向を簡単に把握できます。</p>
            </div>
            
            <div class="feature-card">
                <h3 class="feature-title">情報共有</h3>
                <p>学校や医療機関と必要な情報を簡単に共有。お子さまの日常をサポートします。</p>
            </div>
        </div>
        
        <div class="cta-container">
            <a href="{{ route('register') }}" class="btn-primary">無料ではじめる</a>
            <a href="{{ route('login') }}" class="login-link">すでにアカウントをお持ちの方はこちら</a>
        </div>
    </div>
</body>
</html>