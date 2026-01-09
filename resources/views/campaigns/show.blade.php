{{-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $campaign->title }} - DonasiKita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .hero-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 0 100px;
            color: white;
            margin-bottom: -50px;
        }
        .campaign-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .progress { height: 10px; border-radius: 5px; background-color: #e9ecef; }
        .progress-bar { background: linear-gradient(90deg, #667eea 0%, #764ba2 100%); }
        .donor-item { border-bottom: 1px solid #f0f0f0; padding: 15px 0; }
        .donor-item:last-child { border-bottom: none; }
        .donor-avatar {
            width: 45px; height: 45px; background: #e9ecef; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; color: #764ba2; font-weight: bold;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent absolute-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">💝 DonasiKita</a>
        </div>
    </nav>

    <div class="hero-header">
        <div class="container text-center">
            <h1 class="fw-bold">{{ $campaign->title }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Beranda</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Detail Kampanye</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="card campaign-card mb-4">
                    <img src="{{ $campaign->image_url ?? 'https://via.placeholder.com/800x400?text=No+Image' }}" class="card-img-top" alt="{{ $campaign->title }}">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3">Tentang Kampanye</h4>
                        <div class="text-muted" style="line-height: 1.8;">
                            {!! nl2br(e($campaign->description)) !!}
                        </div>
                    </div>
                </div>

                <div class="card campaign-card">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Donatur Terbaru ({{ $campaign->donations->count() }})</h5>

                        @forelse($campaign->donations as $donation)
                            <div class="donor-item d-flex align-items-center">
                                <div class="donor-avatar me-3">
                                    {{ substr($donation->is_anonymous ? 'H' : $donation->donor_name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">
                                        {{ $donation->is_anonymous ? 'Hamba Allah' : $donation->donor_name }}
                                    </h6>
                                    <small class="text-muted">
                                        Berdonasi <span class="text-success fw-bold">Rp {{ number_format($donation->amount, 0, ',', '.') }}</span>
                                        • {{ $donation->created_at->diffForHumans() }}
                                    </small>
                                    @if($donation->message)
                                        <p class="mb-0 mt-1 small text-muted fst-italic">"{{ $donation->message }}"</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-heart-broken fa-3x mb-3"></i>
                                <p>Belum ada donasi. Jadilah yang pertama!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card campaign-card sticky-top" style="top: 20px; z-index: 1;">
                    <div class="card-body p-4">
                        @php
                            $percentage = ($campaign->current_amount / $campaign->target_amount) * 100;
                        @endphp

                        <h3 class="fw-bold text-primary mb-1">
                            Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}
                        </h3>
                        <p class="text-muted small mb-3">
                            terkumpul dari target <strong>Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</strong>
                        </p>

                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"></div>
                        </div>

                        <a href="{{ route('donation.create', ['campaign_id' => $campaign->id]) }}" class="btn btn-primary w-100 btn-lg rounded-pill mb-3">
                            <i class="fas fa-heart me-2"></i> Donasi Sekarang
                        </a>

                        <div class="d-flex justify-content-between text-center mt-4">
                            <div>
                                <h5 class="fw-bold mb-0">{{ $campaign->backer_count ?? 0 }}</h5>
                                <small class="text-muted">Donatur</small>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">∞</h5>
                                <small class="text-muted">Hari Lagi</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> --}}
