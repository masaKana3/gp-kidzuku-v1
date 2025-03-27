<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        <h2 class="text-lg font-medium text-gray-900">アンケート完了</h2>
        <p>アンケートにご回答いただき、ありがとうございました。</p>
    </div>

    <div class="mt-4">
        <p>保護者アンケートが保存されました。</p>
        <p class="mt-2">お子さまのアンケートは準備中です。</p>
    </div>

    <div class="flex items-center justify-end mt-6">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            ダッシュボードへ
        </a>
    </div>
</x-guest-layout>