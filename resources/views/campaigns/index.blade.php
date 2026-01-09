<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Kampanye - DonasiKita</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
        }

        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar Style */
        .navbar-brand {
            font-size: 1.5rem;
        }

        /* Card Style */
        .campaign-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            background: white;
        }

        .campaign-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
        }

        .campaign-image-wrapper {
            height: 200px;
            position: relative;
            overflow: hidden;
        }

        .campaign-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .campaign-card:hover img {
            transform: scale(1.05);
        }

        .badge-days {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            backdrop-filter: blur(4px);
        }

        /* Progress Bar */
        .progress {
            height: 8px;
            border-radius: 4px;
            background-color: #e9ecef;
            margin: 15px 0;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 4px;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border: none;
        }

        .btn-primary:hover {
            opacity: 0.9;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        footer {
            margin-top: auto;
            background: #2c3e50;
            color: white;
            padding: 30px 0;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
                💝 DonasiKita
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active fw-bold text-primary"
                            href="{{ route('campaigns.index') }}">Kampanye</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-primary rounded-pill px-4" href="{{ route('admin.login') }}">Login
                            Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="bg-primary text-white py-5"
        style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3">Bantu Mereka yang Membutuhkan</h1>
                    <p class="lead opacity-75">Pilih kampanye kebaikan dan salurkan donasi Anda dengan mudah dan
                        transparan.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        @if ($campaigns->count() > 0)
            <div class="row g-4">
                @foreach ($campaigns as $campaign)
                    <div class="col-md-6 col-lg-4">
                        <div class="campaign-card card shadow-sm h-100">
                            <div class="campaign-image-wrapper">
                                <img src="{{ $campaign->image_url }}" alt="{{ $campaign->title }}">

                                <div class="badge-days">
                                    <i class="fas fa-clock me-1"></i>
                                    @if ($campaign->days_left > 0)
                                        Sisa {{ $campaign->days_left }} hari
                                    @else
                                        Berakhir
                                    @endif
                                </div>
                            </div>

                            <div class="card-body d-flex flex-column p-4">
                                <h5 class="card-title fw-bold mb-2">
                                    <a href="{{ route('campaigns.show', $campaign->id) }}"
                                        class="text-decoration-none text-dark stretched-link">
                                        {{ Str::limit($campaign->title, 50) }}
                                    </a>
                                </h5>

                                <p class="card-text text-muted small flex-grow-1">
                                    {{ Str::limit($campaign->description, 90) }}
                                </p>

                                <div class="mt-3">
                                    <div class="d-flex justify-content-between mb-1 small">
                                        <span class="text-muted">Terkumpul</span>
                                        <span class="fw-bold text-primary">{{ $campaign->progress_percentage }}%</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ $campaign->progress_percentage }}%">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2 small">
                                        <div>
                                            <div class="text-muted" style="font-size: 0.75rem;">Terkumpul</div>
                                            <div class="fw-bold text-success">{{ $campaign->formatted_current }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="text-muted" style="font-size: 0.75rem;">Target</div>
                                            <div class="fw-bold">{{ $campaign->formatted_target }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mt-4 position-relative" style="z-index: 2;">

                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('campaigns.show', $campaign->id) }}"
                                        class="btn btn-outline-primary w-50 rounded-pill">
                                        Detail
                                    </a>

                                    {{-- Tombol Donasi (Langsung ke Form Bayar) --}}
                                    <a href="{{ route('donation.create', ['campaign_id' => $campaign->id]) }}"
                                        class="btn btn-primary w-50 rounded-pill">
                                        Donasi <i class="fas fa-heart ms-1"></i>
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $campaigns->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center py-5 my-5">
                <div class="mb-3">
                    <i class="fas fa-hand-holding-heart fa-4x text-muted opacity-25"></i>
                </div>
                <h3 class="text-muted fw-bold">Belum ada kampanye aktif</h3>
                <p class="text-muted">Nantikan program kebaikan selanjutnya dari kami.</p>
                <a href="{{ url('/') }}" class="btn btn-primary mt-3 rounded-pill px-4">Kembali ke Beranda</a>
            </div>
        @endif
    </div>

    <footer>
        <div class="container text-center">
            <p class="mb-2">&copy; {{ date('Y') }} DonasiKita. Platform Donasi Terpercaya.</p>
            <small class="opacity-50">Dibuat dengan ❤️ untuk kebaikan bersama.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
