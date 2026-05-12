<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
        <p class="text-green-700 text-sm font-medium flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('status') }}
        </p>
    </div>
    @endif

    @if ($errors->has('email'))
    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
        <p class="text-red-700 text-sm font-medium flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first('email') }}
        </p>
    </div>
    @endif

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-2">Welcome Back!</h2>
        <p class="text-center text-gray-600 text-sm">Sign in to your Pawmart account</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 transition-all duration-300 bg-gray-50 hover:bg-white"
                placeholder="you@mail.com">
            @if ($errors->has('email'))
            <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first('email') }}
            </p>
            @endif
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-100 transition-all duration-300 bg-gray-50 hover:bg-white"
                placeholder="••••••••">
            @if ($errors->has('password'))
            <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first('password') }}
            </p>
            @endif
        </div>

        <!-- Remember Me -->
        <label class="flex items-center gap-2 text-sm">
            <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-orange-500 focus:ring-orange-400 cursor-pointer">
            <span class="text-gray-700">Remember me on this device</span>
        </label>

        <!-- Forgot Password & Login -->
        <div class="flex items-center justify-between pt-2">
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm text-orange-500 hover:text-orange-600 font-semibold hover:underline flex items-center gap-1">
                <i class="fas fa-key"></i> Forgot password?
            </a>
            @else
            <div></div>
            @endif

            <button type="submit" class="px-6 py-2.5 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 focus:outline-none focus:ring-4 focus:ring-orange-200 transition-all duration-300 shadow-sm flex items-center gap-2">
                <i class="fas fa-sign-in-alt"></i> Log In
            </button>
        </div>

        <!-- Divider -->
        <div class="relative pt-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t-2 border-gray-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">New to Pawmart?</span>
            </div>
        </div>

        <!-- Register Link -->
        <a href="{{ route('register') }}" class="w-full block text-center px-4 py-3 mt-4 border-2 border-orange-400 text-orange-500 font-semibold rounded-xl hover:bg-orange-50 focus:outline-none focus:ring-4 focus:ring-orange-200 transition-all duration-300 hover:border-orange-500">
            <i class="fas fa-user-plus"></i> Create an Account
        </a>
    </form>

    <div class="mt-6 pt-6 border-t-2 border-gray-100 text-center">
        <p class="text-xs text-gray-500">🐾 Securely login to manage your pets and orders</p>
    </div>
</x-guest-layout>