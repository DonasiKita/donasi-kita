@extends('admin.layouts.app')

@section('title', 'Kelola Donasi')

@section('content')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard Donasi</h1>
        </div>

        <div class="row">

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Dana Masuk</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Transaksi</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDonations }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Donatur</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDonors }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Kampanye Aktif</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCampaigns }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-bullhorn fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">5 Transaksi Terakhir</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Donatur</th>
                                        <th>Kampanye</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentDonations as $recent)
                                        <tr>
                                            <td>
                                                <strong>{{ $recent->is_anonymous ? 'Hamba Allah' : $recent->donor_name }}</strong>
                                                <div class="small text-muted">{{ $recent->created_at->diffForHumans() }}
                                                </div>
                                            </td>
                                            <td>{{ Str::limit($recent->campaign->title, 20) }}</td>
                                            <td>Rp {{ number_format($recent->amount, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($recent->payment_status == 'paid')
                                                    <span class="badge bg-success">Berhasil</span>
                                                @elseif($recent->payment_status == 'pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @else
                                                    <span class="badge bg-danger">Gagal</span>
                                                @endif
                                            </td>
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

            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Progress Kampanye</h6>
                    </div>
                    <div class="card-body">
                        @foreach ($campaignProgress as $prog)
                            <h4 class="small font-weight-bold">
                                {{ Str::limit($prog->title, 25) }}
                                <span class="float-end">{{ $prog->progress_percentage }}%</span>
                            </h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-info" role="progressbar"
                                    style="width: {{ $prog->progress_percentage }}%"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Riwayat Semua Donasi</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Tanggal</th>
                                <th>Donatur</th>
                                <th>Kampanye</th>
                                <th>Nominal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($donations as $donation)
                                <tr>
                                    <td><code>{{ $donation->midtrans_order_id }}</code></td>
                                    <td>{{ $donation->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        {{ $donation->is_anonymous ? 'Hamba Allah' : $donation->donor_name }}
                                        <br><small class="text-muted">{{ $donation->donor_email }}</small>
                                    </td>
                                    <td>{{ Str::limit($donation->campaign->title, 30) }}</td>
                                    <td>Rp {{ number_format($donation->amount, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if ($donation->payment_status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($donation->payment_status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Failed</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 d-flex justify-content-end">
                    {{ $donations->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

    </div>
@endsection
