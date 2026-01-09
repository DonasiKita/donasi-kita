<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi Berhasil - DonasiKita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .success-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            padding: 50px;
            text-align: center;
            max-width: 500px;
            width: 90%;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .success-icon {
            font-size: 80px;
            color: #4CAF50;
            margin-bottom: 20px;
            animation: pop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes pop {
            0% {
                transform: scale(0);
            }

            100% {
                transform: scale(1);
            }
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            font-weight: 700;
        }

        p {
            color: #666;
            margin-bottom: 30px;
        }

        .btn-success {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(76, 175, 80, 0.3);
        }

        .btn-outline {
            border: 2px solid #667eea;
            color: #667eea;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline:hover {
            background: #667eea;
            color: white;
        }

        .info-box {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
            border: 1px solid #eee;
        }
    </style>
</head>

<body>
    <div class="success-card">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>Terima Kasih!</h1>
        <p>Donasi Anda untuk <strong>{{ $donation->campaign->title ?? 'Kampanye' }}</strong> telah berhasil.</p>

        <div class="info-box">
            <div class="row mb-3">
                <div class="col-6">
                    <small class="text-muted d-block">ID Transaksi</small>
                    <span class="fw-bold text-dark">{{ $donation->midtrans_order_id }}</span>
                </div>
                <div class="col-6 text-end">
                    <small class="text-muted d-block">Tanggal</small>
                    <span class="text-dark">{{ $donation->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <small class="text-muted d-block">Metode</small>
                    <span class="badge bg-secondary">Midtrans</span>
                </div>
                <div class="col-6 text-end">
                    <small class="text-muted d-block">Nominal Donasi</small>
                    <span class="fw-bold text-success fs-5">Rp
                        {{ number_format($donation->amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <p class="text-muted small mb-4">
            Bukti pembayaran dan konfirmasi donasi telah dikirim ke email <strong>{{ $donation->donor_email }}</strong>.
        </p>

        <div class="d-grid gap-2">
            <a href="{{ url('/') }}" class="btn btn-success text-white">
                <i class="fas fa-home me-2"></i>Kembali ke Beranda
            </a>
            <a href="{{ route('donation.create', ['campaign_id' => $donation->campaign_id]) }}" class="btn btn-outline">
                <i class="fas fa-heart me-2"></i>Donasi Lagi
            </a>
        </div>

        <div class="mt-4">
            <small class="text-muted">
