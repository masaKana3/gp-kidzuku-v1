<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AI Consultation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">詳細内容をAIに相談しよう！</h3>

                    {{-- ⭐️formの作成 --}}
                    <form method="POST" action="{{ route('gemini.response') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="ai_query" class="block text-sm font-medium text-gray-700">相談内容</label>
                            <textarea id="ai_query" name="ai_query" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">相談する</button>
                        </div>
                    </form>

                    {{-- controllerで生成AIからの回答を受け取ったらここに出力する。 --}}
                    @isset($response)
                        <div class="mt-8">
                            <h3 class="text-lg font-bold mb-4">AIの回答</h3>
                            <div class="prose max-w-none">
                                {!! $response !!}
                            </div>
                        </div>
                    @endisset

                    <div class="mt-8 text-center">
                        <a href="{{ route('conditions.history') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            体調記録履歴に戻る
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
