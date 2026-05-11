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
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </div>

                {{-- Status --}}
                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success border-0 rounded-3 mb-4" role="alert">
                        <small class="fw-medium">
                            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                        </small>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="d-flex justify-content-between align-items-center">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf

                        <button type="submit"
                            class="btn rounded-3 py-2 fw-semibold shadow-lg text-white border-0"
                            style="background: linear-gradient(90deg, #fb923c, #ec4899);">
                            {{ __('Resend Verification Email') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                            class="btn btn-link text-decoration-none p-0 fw-semibold"
                            style="color: #fb923c;">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>

            </div>
        </div>

        {{-- Footer --}}
        <p class="text-center text-muted mt-4" style="font-size: 0.75rem;">
            🐾 Verify your email to access your account
        </p>

    </div>
</x-guest-layout>
