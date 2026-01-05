<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - DonasiKita</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-bottom: 5px solid rgba(0, 0, 0, 0.1);
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .input-group-text {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .form-control {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 20px;
            width: 100%;
            border-radius: 25px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .alert {
            border-radius: 10px;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="login-header">
            <h3><i class="fas fa-user-shield me-2"></i>Admin Panel</h3>
            <p class="mb-0 opacity-75">DonasiKita Platform</p>
        </div>

        <div class="login-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-1"></i> Periksa kembali inputan Anda.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}" autocomplete="off">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label text-muted small fw-bold">EMAIL ADDRESS</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-secondary"><i
                                class="fas fa-envelope"></i></span>
                        <input type="email"
                            class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" placeholder="nama@email.com" required
                            autocomplete="off" autofocus>
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label text-muted small fw-bold">PASSWORD</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-secondary"><i
                                class="fas fa-lock"></i></span>
                        <input type="password"
                            class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                            id="password" name="password" placeholder="••••••••" required autocomplete="new-password">
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label small text-secondary" for="remember">Ingat saya di perangkat
                        ini</label>
                </div>

                <button type="submit" class="btn btn-login mb-3">
                    MASUK SEKARANG <i class="fas fa-arrow-right ms-2"></i>
                </button>

                <div class="text-center">
                    <a href="{{ route('home') }}" class="text-decoration-none text-secondary small">
                        <i class="fas fa-home me-1"></i> Kembali ke Beranda Utama
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
