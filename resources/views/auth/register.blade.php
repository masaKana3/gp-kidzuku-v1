<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700">新規登録</h2>
        <p class="text-sm text-gray-500 mt-1">アカウントを作成して、お子さまの健康管理を始めましょう</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('お名前')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="山田 花子" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('メールアドレス')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="example@mail.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Zipcode（areaを使う） -->
        <div class="mt-4">
            <x-input-label for="area" :value="__('郵便番号（ハイフンなし）')" />
            <x-text-input id="area" class="block mt-1 w-full" type="text" name="area"
                :value="old('area')" required autocomplete="postal-code"
                placeholder="例：1000001" />
            <x-input-error :messages="$errors->get('area')" class="mt-2" />
            <p class="text-xs text-gray-500 mt-1">※ 郵便番号7桁を半角数字で入力してください（例：1000001）</p>
        </div>

        <!-- Password -->   
        <div class="mt-4">
            <x-input-label for="password" :value="__('パスワード')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                            placeholder="8文字以上の英数字" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <p class="text-xs text-gray-500 mt-1">※ 8文字以上の英数字を組み合わせてください</p>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('パスワード（確認用）')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="パスワードを再入力" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3 kidzuki-btn">
                {{ __('登録する') }}
            </x-primary-button>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-200 text-center">
            <p class="text-sm text-gray-600">すでにアカウントをお持ちの方は</p>
            <a class="mt-1 inline-block text-sm font-medium kidzuki-link" href="{{ route('login') }}">
                {{ __('ログインはこちら') }} &rarr;
            </a>
        </div>

        <div class="mt-6 text-xs text-gray-500 text-center">
            <p>登録することで、<a href="#" class="kidzuki-link">利用規約</a>および<a href="#" class="kidzuki-link">プライバシーポリシー</a>に同意したことになります。</p>
        </div>
    </form>
</x-guest-layout>
