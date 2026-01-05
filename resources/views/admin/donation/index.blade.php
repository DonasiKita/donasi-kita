@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-0">Dashboard</h1>
                <p class="text-muted">Selamat datang, {{ auth()->user()->name }}!</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Kampanye
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCampaigns }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-bullhorn fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Donasi
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDonations }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-donate fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Dana Terkumpul
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Kampanye Aktif
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ \App\Models\Campaign::where('is_active', true)->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Donations -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Donasi Terbaru</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Donatur</th>
                                        <th>Kampanye</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentDonations as $donation)
                                        <tr>
                                            <td>
                                                <strong>{{ $donation->donor_name }}</strong><br>
                                                <small class="text-muted">{{ $donation->donor_email }}</small>
                                            </td>
                                            <td>{{ Str::limit($donation->campaign->title, 20) }}</td>
                                            <td class="text-success font-weight-bold">
                                                Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                            </td>
                                            <td>{{ $donation->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Belum ada donasi</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campaign Progress -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Progress Kampanye</h6>
                    </div>
                    <div class="card-body">
                        @foreach ($campaignProgress as $campaign)
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>{{ Str::limit($campaign->title, 25) }}</span>
                                    <span
                                        class="font-weight-bold">{{ number_format($campaign->progress_percentage, 1) }}%</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ $campaign->progress_percentage }}%">
                                    </div>
                                </div>
                                <div class="small text-muted mt-1">
                                    Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}
                                    dari Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-plus me-2"></i> Tambah Kampanye
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.campaigns.index') }}" class="btn btn-success btn-block">
                                    <i class="fas fa-list me-2"></i> Lihat Kampanye
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="#" class="btn btn-info btn-block">
                                    <i class="fas fa-users me-2"></i> Lihat Donatur
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="#" class="btn btn-warning btn-block">
                                    <i class="fas fa-chart-bar me-2"></i> Laporan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
