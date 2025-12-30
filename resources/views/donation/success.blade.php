<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi Berhasil - DonasiKita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 50px;
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        .success-icon {
            font-size: 80px;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
        }

        p {
            color: #666;
            margin-bottom: 30px;
        }

        .btn-success {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
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
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-outline:hover {
            background: #667eea;
            color: white;
        }

        .info-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="success-card">
        <div class="success-icon">✅</div>
        <h1>Terima Kasih!</h1>
        <p>Donasi Anda telah berhasil diproses.</p>

        <div class="info-box">
            <div class="row">
                <div class="col-6">
                    <small class="text-muted">ID Transaksi</small>
                    <p class="mb-0 fw-bold">{{ request()->get('order_id', 'TRX-' . time()) }}</p>
                </div>
                <div class="col-6 text-end">
                    <small class="text-muted">Tanggal</small>
                    <p class="mb-0">{{ now()->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <hr>
            <div>
                <small class="text-muted">Status</small>
                <p class="mb-0 text-success fw-bold">
                    <i class="fas fa-check-circle me-2"></i>Pembayaran Berhasil
                </p>
            </div>
        </div>

        <p class="text-muted">
            Konfirmasi donasi telah dikirim ke email Anda.
            Terima kasih telah berpartisipasi dalam kebaikan.
        </p>

        <div class="d-grid gap-2">
            <a href="{{ url('/') }}" class="btn btn-success">
                <i class="fas fa-home me-2"></i>Kembali ke Beranda
            </a>
            <a href="{{ url('/donation/create') }}" class="btn btn-outline">
                <i class="fas fa-donate me-2"></i>Donasi Lagi
            </a>
        </div>

        <div class="mt-4">
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Butuh bantuan? Hubungi kami di admin@donasikita.id
            </small>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
