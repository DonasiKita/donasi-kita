@extends('admin.layouts.app')

@section('title', 'Detail Kampanye: ' . $campaign->title)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Detail Kampanye</h1>
                <div>
                    <a href="{{ route('admin.campaigns.edit', $campaign->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i> Edit
                    </a>
                    <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title">{{ $campaign->title }}</h2>
                    <div class="mb-3">
                        <span class="badge bg-{{ $campaign->is_active ? 'success' : 'secondary' }} me-2">
                            {{ $campaign->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        <span class="text-muted">
                            <i class="far fa-calendar me-1"></i>
                            {{ $campaign->created_at->format('d M Y H:i') }}
                        </span>
                    </div>

                    @if($campaign->image_url)
                        <div class="mb-4">
                            <img src="{{ $campaign->image_url }}"
                                 alt="{{ $campaign->title }}"
                                 class="img-fluid rounded">
                        </div>
                    @endif

                    <div class="mb-4">
                        <h5>Deskripsi</h5>
                        <p style="white-space: pre-line;">{{ $campaign->description }}</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statistik Donasi</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded">
                                <h3 class="text-primary">{{ $campaign->donations->count() }}</h3>
                                <p class="mb-0 text-muted">Total Donasi</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded">
                                <h3 class="text-success">{{ $campaign->formatted_current }}</h3>
                                <p class="mb-0 text-muted">Terkumpul</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded">
                                <h3 class="text-info">{{ $campaign->formatted_target }}</h3>
                                <p class="mb-0 text-muted">Target</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6>Progress</h6>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-success"
                                 role="progressbar"
                                 style="width: {{ $campaign->progress_percentage }}%">
                                {{ number_format($campaign->progress_percentage, 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Info Kampanye</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Status</span>
                            <span class="badge bg-{{ $campaign->is_active ? 'success' : 'secondary' }}">
                                {{ $campaign->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Target Donasi</span>
                            <strong>{{ $campaign->formatted_target }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Terkumpul</span>
                            <strong class="text-success">{{ $campaign->formatted_current }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Kekurangan</span>
                            <strong class="text-danger">
                                Rp {{ number_format(max(0, $campaign->target_amount - $campaign->current_amount), 0, ',', '.') }}
                            </strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Tanggal Dibuat</span>
                            <span>{{ $campaign->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Terakhir Diupdate</span>
                            <span>{{ $campaign->updated_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Donasi Terbaru</h5>
                </div>
                <div class="card-body">
                    @if($campaign->donations->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($campaign->donations->take(5) as $donation)
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $donation->donor_name }}</h6>
                                            <small class="text-muted">{{ $donation->created_at->format('d M H:i') }}</small>
                                        </div>
                                        <div class="text-end">
                                            <strong class="text-success">{{ $donation->formatted_amount }}</strong><br>
                                            <span class="badge {{ $donation->status_badge }}">
                                                {{ $donation->status_text }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($campaign->donations->count() > 5)
                            <div class="text-center mt-3">
                                <a href="#" class="text-decoration-none">Lihat semua donasi</a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-donate fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada donasi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
