<x-guest-layout>
    <div class="w-full max-w-md mx-auto px-4 py-8">
        <div class="bg-white/90 backdrop-blur-md shadow-2xl rounded-3xl border border-orange-100 overflow-hidden">
            <div class="px-8 py-10">
                <div class="text-center mb-8">
                    <div class="mx-auto mb-4 w-16 h-16 rounded-full bg-gradient-to-br from-orange-400 to-pink-400 flex items-center justify-center shadow-xl">
                        <i class="fas fa-paw text-white text-2xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Create Your Account</h2>
                    <p class="mt-2 text-sm text-gray-500">Register with your name, email, and password.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                            class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-orange-400 focus:ring-2 focus:ring-orange-100"
                            placeholder="Your name">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                            class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-orange-400 focus:ring-2 focus:ring-orange-100"
                            placeholder="you@example.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-orange-400 focus:ring-2 focus:ring-orange-100"
                            placeholder="••••••••">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                            class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-900 outline-none transition focus:border-orange-400 focus:ring-2 focus:ring-orange-100"
                            placeholder="••••••••">
                    </div>

                    <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-orange-400 to-pink-400 px-5 py-3 text-white font-semibold shadow-lg shadow-orange-200/50 transition hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-orange-100">
                        Create Account
                    </button>
                </form>

                <div class="mt-6 text-center text-sm text-gray-500">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-semibold text-orange-500 hover:text-orange-600">Log in</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>