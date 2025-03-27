@include('components.auth-header')
<x-guest-layout>
    <div class="w-full xl:max-w-7xl mx-auto py-10 px-4 overflow-x-auto">
        <h2 class="text-2xl font-bold mb-4">{{ $placeName }} の天気予報（気圧グラフ）</h2>

        <!-- グラフ表示コンポーネント -->
        <x-pressure-chart :pressures="$pressures" />

        <!-- アラート（差し替え） -->
        <div class="mt-10">
            <x-pressure-alert :pressures="$pressures" />
        </div>
        
        <div class="mt-8 text-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                ダッシュボードに戻る
            </a>
        </div>
    </div>

    <!-- Chart.js 読み込み -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</x-guest-layout>