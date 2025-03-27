<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700">ログイン</h2>
        <p class="text-sm text-gray-500 mt-1">アカウントにログインして、お子さまの健康管理を始めましょう</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('メールアドレス')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="example@mail.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('パスワード')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-pink-400 shadow-sm focus:ring-pink-300" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('ログイン状態を保持する') }}</span>
            </label>
        </div>

        <div class="flex flex-col space-y-4 mt-6">
            <x-primary-button class="w-full justify-center py-3 kidzuki-btn">
                {{ __('ログイン') }}
            </x-primary-button>

            <div class="text-center">
                @if (Route::has('password.request'))
                    <a class="text-sm kidzuki-link" href="{{ route('password.request') }}">
                        {{ __('パスワードをお忘れですか？') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-200 text-center">
            <p class="text-sm text-gray-600">アカウントをお持ちでない方は</p>
            <a href="{{ route('register') }}" class="mt-1 inline-block text-sm font-medium kidzuki-link">
                {{ __('新規登録はこちら') }} &rarr;
            </a>
        </div>
    </form>
</x-guest-layout>
