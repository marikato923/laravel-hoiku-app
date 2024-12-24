<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="container mx-auto p-4">
        <h2 class="text-center text-2xl font-semibold mb-6">{{ __('会員ログイン') }}</h2>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('メールアドレス')" />
                <x-text-input id="email" class="block w-full p-2 border border-gray-300 rounded" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('パスワード')" />
                <x-text-input id="password" class="block w-full p-2 border border-gray-300 rounded" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div>
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('ログイン状態を保持する') }}</span>
                </label>
            </div>

            <div class="flex justify-between items-center">
                <div>
                    <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('register') }}">
                        {{ __('会員登録') }}
                    </a>
                    @if (Route::has('password.request'))
                        <a class="text-sm text-gray-600 hover:text-gray-900 ml-3" href="{{ route('password.request') }}">
                            {{ __('パスワードをお忘れですか？') }}
                        </a>
                    @endif
                </div>

                <x-primary-button>
                    {{ __('ログイン') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>