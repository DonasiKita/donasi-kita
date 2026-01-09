<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - DonasiKita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .nav-link {
            color: #666;
            padding: 12px 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .nav-link:hover,
        .nav-link.active {
            background: #f0f2f5;
            color: #667eea;
            border-right: 3px solid #667eea;
        }

        .nav-link i {
            width: 25px;
        }

        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 d-none d-md-block sidebar px-0">
                <div class="py-4 px-3 mb-4 text-center border-bottom">
                    <h5 class="fw-bold text-primary">🛡️ Admin Panel</h5>
                </div>

                <div class="d-flex flex-column">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.donations.index') }}" class="nav-link">
                        <i class="fas fa-hand-holding-heart"></i> Data Donasi
                    </a>
                    <a href="{{ route('admin.campaigns.index') }}" class="nav-link">
                        <i class="fas fa-bullhorn"></i> Kelola Kampanye
                    </a>
                    <a href="{{ route('admin.bgdn') }}" class="nav-link">
                        <i class="fas fa-users"></i> Data BGDN
                    </a>

                    <form action="{{ route('admin.logout') }}" method="POST" class="mt-4 px-3">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-md-9 col-lg-10 ms-sm-auto px-md-4 py-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                    <h1 class="h2">Dashboard Overview</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <span class="text-muted">Halo, {{ auth()->user()->name ?? 'Admin' }}!</span>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card stat-card">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Total Donasi Masuk</p>
                                        <h3 class="fw-bold text-success">Rp
                                            {{ number_format($totalDonations, 0, ',', '.') }}</h3>
                                    </div>
                                    <div class="icon-box bg-success">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card stat-card">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Total Kampanye</p>
                                        <h3 class="fw-bold text-primary">{{ $totalCampaigns }}</h3>
                                    </div>
                                    <div class="icon-box bg-primary">
                                        <i class="fas fa-bullhorn"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card stat-card">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1">Total Transaksi</p>
                                        <h3 class="fw-bold text-warning">{{ $totalDonors }}</h3>
                                    </div>
                                    <div class="icon-box bg-warning">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold">Akses Cepat</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <a href="{{ route('admin.donations.index') }}"
                                            class="btn btn-outline-primary w-100 p-3">
                                            <i class="fas fa-list fa-2x mb-2 d-block"></i>
                                            Cek Donasi Masuk
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('admin.campaigns.index') }}"
                                            class="btn btn-outline-success w-100 p-3">
                                            <i class="fas fa-plus-circle fa-2x mb-2 d-block"></i>
                                            Buat Kampanye Baru
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
