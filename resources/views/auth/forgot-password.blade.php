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

                <div class="mb-4 text-muted small">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </div>

                {{-- Session Status --}}
                @if (session('status'))
                    <div class="alert alert-success border-0 rounded-3 mb-4" role="alert">
                        <small class="fw-medium">
                            {{ session('status') }}
                        </small>
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('password.email') }}" class="mb-4">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark small mb-2">
                            {{ __('Email') }}
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

                    {{-- Button --}}
                    <button type="submit"
                        class="btn w-100 rounded-3 py-2 fw-semibold shadow-lg text-white border-0"
                        style="background: linear-gradient(90deg, #fb923c, #ec4899);">
                        {{ __('Email Password Reset Link') }}
                    </button>
                </form>

            </div>
        </div>

        {{-- Footer --}}
        <p class="text-center text-muted mt-4" style="font-size: 0.75rem;">
            🐾 Secure password recovery for your account
        </p>

    </div>
</x-guest-layout>
