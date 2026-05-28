@extends('layout.app')

@section('title', 'Login - Sistem Informasi Nilai')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold mb-3">🔐 Login</h1>
                <p class="lead text-muted">Masukkan email dan password Anda</p>
            </div>

            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label h6 mb-3">Email</label>
                            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                                   placeholder="contoh@school.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label h6 mb-3">Password</label>
                            <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="current-password"
                                   placeholder="Masukkan password Anda">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Ingat saya
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold fs-5 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk ke Sistem
                        </button>
                    </form>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <hr>

                    <div class="text-center">
                        <small class="text-muted">
                            Belum punya akun? Hubungi administrator
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 20px;
}
@media (max-width: 768px) {
    .container {
        padding: 20px 15px;
    }
}
</style>
@endsection

