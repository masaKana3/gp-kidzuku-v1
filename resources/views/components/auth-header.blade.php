<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}">
                    <div class="w-[150px]">
                        <img src="{{ asset('images/kidzuku_logo_200.png') }}" alt="KiDzUKu Logo" class="w-full h-auto">
                    </div>
                </a>
            </div>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                    <span>{{ Auth::user()->name }}さん</span>
                    <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">プロフィール設定</a>
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ダッシュボード</a>
                    <a href="{{ route('schedule') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">スケジュール</a>
                    <a href="{{ route('weather') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">天気情報</a>
                    <a href="{{ route('conditions.history') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">体調記録</a>
                    <a href="{{ route('surveys.parent') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gr    ay-100">データ分析</a>
                    <a href="{{ route('ai-consult.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">AIコンサルト</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ログアウト</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>