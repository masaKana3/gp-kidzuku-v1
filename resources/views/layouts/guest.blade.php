<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'KiDzUKu') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .kidzuki-bg {
                background-color: #f5f5f7;
                background-image: linear-gradient(to bottom right, #f5f5f7, #e8e8f0);
            }
            .kidzuki-card {
                background-color: white;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            }
            .kidzuki-btn {
                background-color: #4a6f8a;
                color: white;
                transition: all 0.3s ease;
            }
            .kidzuki-btn:hover {
                background-color: #385978;
                transform: translateY(-1px);
            }
            .kidzuki-link {
                color: #4A7AAC;
                transition: all 0.3s ease;
            }
            .kidzuki-link:hover {
                color: #3A6A9C;
            }
            .kidzuki-logo {
                max-width: 200px;
                height: auto;
                display: flex;
                justify-content: center;
            }
            .kidzuki-logo img {
                max-width: 100%;
                height: auto;
            }
            .kidzuki-tagline {
                color: #6c757d;
                font-size: 0.9rem;
                text-align: center;
                margin-top: 0.5rem;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 kidzuki-bg">
            <div class="mt-6 mb-2">
                <a href="/" class="flex flex-col items-center">
                    <div class="kidzuki-logo">
                        <img src="{{ asset('images/kidzuku_logo.png') }}" alt="KiDzUKu Logo">
                    </div>
                    <!-- <div class="kidzuki-tagline mt-1">
                        <p>築く</p>
                        <p>母娘の DAILY CARE APP</p>
                    </div> -->
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-2 px-6 py-6 kidzuki-card">
                {{ $slot }}
            </div>
            
            <div class="w-full sm:max-w-md mt-4 text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} KiDzUKu. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>
