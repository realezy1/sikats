<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'SiKats') }} - Login</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=montserrat:400,500,600,700,800,900&display=swap" rel="stylesheet" />
        
        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        
        <style>
            body {
                font-family: 'Montserrat', sans-serif;
                background-image: url('https://www.jagel.id/api/listimage/v/Katsukai-0-4485f13aee06838d.jpg');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
            }
            .glass-overlay {
                background-color: rgba(0, 0, 0, 0.4);
                backdrop-filter: blur(10px);
                min-height: 100vh;
            }
            .login-card {
                max-width: 1024px;
                border-radius: 2rem;
                min-height: 600px;
            }
            .login-left-panel {
                border-radius: 1.5rem;
                background-image: url('{{ asset('images/background-login.png') }}');
                background-size: cover;
                background-position: center;
            }
            .custom-btn {
                background-color: #0c4a52;
                border-color: #0c4a52;
                color: white;
            }
            .custom-btn:hover {
                background-color: #083339;
                border-color: #083339;
                color: white;
            }
            .custom-link {
                color: #0c4a52;
                font-weight: 700;
                text-decoration: none;
            }
            .custom-link:hover {
                color: #083339;
                text-decoration: underline;
            }
            .form-control:focus {
                border-color: #0c4a52;
                box-shadow: 0 0 0 0.25rem rgba(12, 74, 82, 0.25);
            }
        </style>
    </head>
    <body>
        <div class="glass-overlay d-flex align-items-center justify-content-center p-3 p-md-4">
            
            <!-- Main Card -->
            <div class="bg-white w-100 login-card shadow-lg d-flex flex-column flex-md-row p-2">
                
                <!-- Left Side (Image & Text) -->
                <div class="w-100 w-md-50 login-left-panel position-relative d-none d-md-flex flex-column justify-content-between p-5 text-white" style="flex: 1;">
                    <div class="position-relative z-1">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSv5RduS7YytGoiUJpNP8eJUkOXaCJSRdfanA&s" alt="Logo" height="50" class="object-fit-contain">
                    </div>
                </div>

                <!-- Right Side (Login Form) -->
                <div class="w-100 w-md-50 d-flex flex-column justify-content-center px-4 py-5 px-lg-5" style="flex: 1;">
                    
                    <div class="mb-4">
                        <h2 class="fs-2 fw-bolder text-dark mb-2 text-uppercase">Selamat Datang !</h2>
                        <p class="text-secondary small fw-medium">Selamat datang kembali! Silakan masukkan kredensial Anda.</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success small mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-semibold text-dark">Alamat Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="form-control form-control-lg fs-6" placeholder="Masukkan email Anda" />
                            @error('email')
                                <div class="text-danger small mt-1 fw-medium">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label small fw-semibold text-dark">Kata Sandi</label>
                            <div class="position-relative">
                                <input id="password" type="password" name="password" required 
                                    class="form-control form-control-lg fs-6 pe-5" placeholder="••••••••" />
                                <div class="position-absolute top-50 end-0 translate-middle-y pe-3">
                                    <button type="button" id="togglePassword" class="btn btn-link text-secondary p-0 text-decoration-none">
                                        <svg id="eyeIcon" height="20" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <!-- Eye Open (Default) -->
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1 fw-medium">{{ $message }}</div>
                            @enderror
                        </div>



                        <!-- Sign In Button -->
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn custom-btn btn-lg fs-6 fw-bold py-2">
                                Masuk
                            </button>
                        </div>

                    </form>
                    
                </div>
            </div>
        </div>

        <script>
            document.getElementById('togglePassword').addEventListener('click', function () {
                const passwordInput = document.getElementById('password');
                const eyeIcon = document.getElementById('eyeIcon');
                
                // Toggle the type attribute
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle the SVG icon
                if (type === 'text') {
                    // Eye Slashed (Hidden)
                    eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`;
                } else {
                    // Eye Open (Visible)
                    eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
                }
            });
        </script>
    </body>
</html>
