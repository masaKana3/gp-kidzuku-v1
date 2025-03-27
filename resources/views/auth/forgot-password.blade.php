<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700">パスワードをお忘れですか？</h2>
    </div>

    <div class="mb-4 text-sm text-gray-600 text-center">
        {{ __('ご登録のメールアドレスを入力してください。パスワードリセット用のリンクをメールでお送りします。') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('メールアドレス')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="example@mail.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3 kidzuki-btn">
                {{ __('リセットリンクを送信') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm kidzuki-link">
                {{ __('ログイン画面に戻る') }}
            </a>
        </div>
    </form>
</x-guest-layout>
