@extends('admin.layouts.app')

@section('title', 'Detail Kampanye: ' . $campaign->title)

@section('content')
<div class="container-fluid">
    {{-- Header Page --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-1 text-gray-800">Detail Kampanye</h1>
                        <p class="text-muted mb-0">Informasi lengkap mengenai kampanye donasi.</p>
                    </div>
                    <div class="d-flex gap-2">
                        {{-- Tombol Edit --}}
                        <a href="{{ route('admin.campaigns.edit', $campaign->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        {{-- Tombol Kembali --}}
                        <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Kolom Kiri: Detail Utama --}}
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                {{-- Gambar Kampanye --}}
                @if($campaign->image)
                    <img src="{{ asset('storage/' . $campaign->image) }}"
                         class="card-img-top"
                         alt="{{ $campaign->title }}"
                         style="max-height: 400px; object-fit: cover;">
                @else
                    <div class="bg-light text-center py-5">
                        <i class="fas fa-image fa-3x text-secondary"></i>
                        <p class="text-secondary mt-2">Tidak ada gambar</p>
                    </div>
                @endif

                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-{{ $campaign->is_active ? 'success' : 'secondary' }} me-2 px-3 py-2">
                            {{ $campaign->is_active ? 'Status: AKTIF' : 'Status: NONAKTIF' }}
                        </span>
                        <span class="text-muted small">
                            <i class="far fa-calendar-alt me-1"></i>
                            Dibuat pada: {{ \Carbon\Carbon::parse($campaign->created_at)->translatedFormat('d F Y H:i') }}
                        </span>
                    </div>

                    <h2 class="card-title fw-bold text-dark mb-3">{{ $campaign->title }}</h2>

                    <hr>

                    <h5 class="fw-bold text-primary">Deskripsi Kampanye</h5>
                    <div class="campaign-description text-muted" style="line-height: 1.8; white-space: pre-line;">
                        {{ $campaign->description }}
                    </div>
                </div>
            </div>

            {{-- Tombol Link ke Halaman Donasi (Front End) --}}
            <div class="alert alert-info d-flex justify-content-between align-items-center" role="alert">
                <span><i class="fas fa-info-circle me-2"></i> Ingin melihat tampilan publik atau mencoba donasi?</span>
                <a href="{{ route('donation.create', ['campaign_id' => $campaign->id]) }}" target="_blank" class="btn btn-primary btn-sm">
                    <i class="fas fa-external-link-alt me-1"></i> Halaman Donasi
                </a>
            </div>
        </div>

        {{-- Kolom Kanan: Statistik & Info --}}
        <div class="col-lg-4">

            {{-- Card Statistik --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="fas fa-chart-line me-2"></i>Statistik Donasi</h5>
                </div>
                <div class="card-body">
                    {{-- Progress Bar --}}
                    @php
                        // Menghitung persentase agar tidak error division by zero
                        $percentage = $campaign->goal_amount > 0 ? ($campaign->collected_amount / $campaign->goal_amount) * 100 : 0;
                    @endphp

                    <div class="mb-2 d-flex justify-content-between">
                        <span class="fw-bold">Progress</span>
                        <span class="fw-bold text-primary">{{ number_format($percentage, 1) }}%</span>
                    </div>
                    <div class="progress mb-4" style="height: 25px;">
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                             role="progressbar"
                             style="width: {{ $percentage }}%">
                        </div>
                    </div>

                    {{-- Kotak Angka --}}
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="p-3 border rounded bg-light text-center h-100">
                                <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem;">Terkumpul</small>
                                <h6 class="text-success fw-bold mb-0">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded bg-light text-center h-100">
                                <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem;">Target</small>
                                <h6 class="text-dark fw-bold mb-0">Rp {{ number_format($campaign->goal_amount, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 border rounded bg-light text-center">
                                <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem;">Total Donatur</small>
                                <h4 class="text-primary fw-bold mb-0">{{ $campaign->donations->count() }}</h4>
                            </div>
                        </div>
                    </div>

                    <ul class="list-group list-group-flush mt-3">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Kekurangan</span>
                            <span class="text-danger fw-bold">
                                Rp {{ number_format(max(0, $campaign->goal_amount - $campaign->collected_amount), 0, ',', '.') }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Card Donasi Terbaru --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="fas fa-history me-2"></i>5 Donasi Terakhir</h5>
                </div>
                <div class="card-body p-0">
                    @forelse($campaign->donations->take(5) as $donation)
                        <div class
