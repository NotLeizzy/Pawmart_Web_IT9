<x-guest-layout>
    <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg border-0 rounded-4" style="max-width: 28rem; border: 1px solid #fed7aa !important;">

            <div class="card-body p-4">

                {{-- Logo --}}
                <div class="text-center mb-4">
                    <div class="mx-auto mb-4 d-flex justify-content-center align-items-center rounded-circle shadow-lg" style="width: 2.5rem; height: 2.5rem; background: linear-gradient(135deg, #fb923c, #ec4899);">
                        <img src="{{ asset('images/PawMartLogo.png') }}"
                            alt="Pawmart logo"
                            class="img-fluid p-1" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    </div>

                    <h1 class="h3 fw-bold text-dark mb-2">
                        Pawmart
                    </h1>

                    <p class="text-muted small">
                        Your Trusted Pet Shop
                    </p>
                </div>

                {{-- Session Status --}}
                @if (session('status'))
                    <div class="alert alert-success border-0 rounded-3 mb-4" role="alert">
                        <small class="fw-medium">
                            {{ session('status') }}
                        </small>
                    </div>
                @endif

                {{-- Title --}}
                <div class="mb-4">
                    <h2 class="h4 fw-bold text-dark mb-1">
                        Welcome Back! 🐾
                    </h2>

                    <p class="text-muted small">
                        Sign in to your Pawmart account
                    </p>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('login') }}" class="mb-4">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark small mb-2">
                            Email Address
                        </label>

                        <input type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="form-control border rounded-3 bg-light px-3 py-2"
                            style="border-color: #e5e7eb !important;"
                            placeholder="you@example.com">

                        @error('email')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark small mb-2">
                            Password
                        </label>

                        <input type="password"
                            name="password"
                            required
                            class="form-control border rounded-3 bg-light px-3 py-2"
                            style="border-color: #e5e7eb !important;"
                            placeholder="••••••••">

                        @error('password')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox"
                                name="remember"
                                class="form-check-input"
                                id="remember">

                            <label class="form-check-label text-muted small" for="remember">
                                Remember me
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-decoration-none small fw-medium"
                                style="color: #fb923c;">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    {{-- Button --}}
                    <button type="submit"
                        class="btn w-100 rounded-3 py-2 fw-semibold shadow-lg text-white border-0"
                        style="background: linear-gradient(90deg, #fb923c, #ec4899);">
                        Log In
                    </button>
                </form>

                {{-- Register --}}
                <div class="text-center text-muted small">
                    New to Pawmart?

                    <a href="{{ route('register') }}"
                        class="text-decoration-none fw-semibold ms-1"
                        style="color: #fb923c;">
                        Create an Account
                    </a>
                </div>

            </div>
        </div>

        {{-- Footer --}}
        <p class="text-center text-muted mt-4" style="font-size: 0.75rem;">
            🐾 Securely login to manage your pets and orders
        </p>

    </div>
</x-guest-layout>