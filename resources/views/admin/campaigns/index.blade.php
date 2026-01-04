<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Kampanye - DonasiKita</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
        }

        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .campaign-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s;
            height: 100%;
        }

        .campaign-card:hover {
            transform: translateY(-5px);
        }

        .campaign-card img {
            height: 200px;
            object-fit: cover;
        }

        .progress {
            height: 8px;
            border-radius: 4px;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
        }

        .filter-buttons .btn {
            border-radius: 25px;
            padding: 8px 20px;
            margin: 5px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
                💝 DonasiKita
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('campaigns.index') }}">Kampanye</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <div class="bg-primary text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="display-5 fw-bold mb-3">Semua Kampanye</h1>
                    <p class="lead">Temukan kampanye yang ingin Anda dukung</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Campaign List -->
    <div class="container py-5">
        @if($campaigns->count() > 0)
        <div class="row">
            @foreach($campaigns as $campaign)
            <div class="col-md-4 mb-4">
                <div class="campaign-card card shadow">
                    @if($campaign->image_url)
                    <img src="{{ $campaign->image_url }}"
                         class="card-img-top" alt="{{ $campaign->title }}">
                    @endif

                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ Str::limit($campaign->title, 50) }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($campaign->description, 100) }}</p>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Terkumpul</span>
                                <span>{{ number_format($campaign->progress_percentage, 1) }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $campaign->progress_percentage }}%"></div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('campaigns.show', $campaign->id) }}" class="btn btn-outline-primary w-50">
                                Detail
                            </a>
                            <a href="{{ route('donation.create', ['campaign_id' => $campaign->id]) }}" class="btn btn-primary w-50">
                                Donasi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $campaigns->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">Belum ada kampanye</h4>
            <p class="text-muted">Kampanye akan segera hadir</p>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
