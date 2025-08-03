<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Anseyo Restaurant</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/mazer/svg/favicon.svg') }}">
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/mazer/css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/mazer/css/fonts/Iconly---Bold.css') }}">
</head>

<body>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="auth-logo">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('assets/mazer/svg/logo.svg') }}" alt="Logo">
                        </a>
                    </div>
                    <h1 class="auth-title">Log in.</h1>
                    <p class="auth-subtitle mb-5">Login dengan akun Anda untuk mengakses dashboard admin.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="email" 
                                   class="form-control form-control-xl @error('email') is-invalid @enderror" 
                                   placeholder="Email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email" 
                                   autofocus>
                            <div class="form-control-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" 
                                   class="form-control form-control-xl @error('password') is-invalid @enderror" 
                                   placeholder="Password" 
                                   name="password" 
                                   required 
                                   autocomplete="current-password">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-check form-check-lg d-flex align-items-end">
                            <input class="form-check-input me-2" 
                                   type="checkbox" 
                                   name="remember" 
                                   id="remember" 
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-gray-600" for="remember">
                                Keep me logged in
                            </label>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5" type="submit">
                            Log in
                        </button>
                    </form>
                    <div class="text-center mt-5 text-lg fs-4">
                        <p class="text-gray-600">Don't have an account? 
                            <a href="{{ route('register') }}" class="font-bold">Sign up</a>.
                        </p>
                        <p>
                            <a class="font-bold" href="{{ route('password.request') }}">Forgot password?</a>.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                    <div class="auth-logo text-center mt-5">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('assets/mazer/svg/logo.svg') }}" alt="Logo" height="100">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
