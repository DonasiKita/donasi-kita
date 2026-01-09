<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Donasi - DonasiKita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .payment-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border: none;
        }
        .amount-display {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 10px 0;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card payment-card">
                <div class="card-header">
                    <h5 class="mb-0 text-white-50">Total Pembayaran</h5>
                    <div class="amount-display">
                        Rp {{ number_format($donation->amount, 0, ',', '.') }}
                    </div>
                    <p class="mb-0">ID Order: #{{ $donation->midtrans_order_id }}</p>
                </div>

                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="text-muted small fw-bold text-uppercase">Detail Donatur</label>
                        <div class="d-flex justify-content-between mt-2">
                            <span>Nama</span>
                            <span class="fw-bold">{{ $donation->donor_name }}</span>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>Email</span>
                            <span class="fw-bold">{{ $donation->donor_email }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small fw-bold text-uppercase">Tujuan Donasi</label>
                        <p class="fw-bold mt-2 text-primary">
                            {{ $donation->campaign->title ?? 'Nama Kampanye' }}
                        </p>
                    </div>

                    <div class="d-grid gap-2">
                        <button id="pay-button" class="btn btn-primary btn-lg py-3 rounded-pill">
                            <i class="fas fa-wallet me-2"></i> Bayar Sekarang
                        </button>

                        <a href="{{ url('/') }}" class="btn btn-outline-secondary rounded-pill mt-2">
                            Batalkan
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4 text-muted small">
                <i class="fas fa-lock me-1"></i> Pembayaran diamankan oleh Midtrans
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        // SnapToken dikirim dari Controller
        snap.pay('{{ $donation->snap_token }}', {
            // Callback jika sukses
            onSuccess: function(result){
                // Arahkan ke halaman sukses (pastikan route ini ada)
                window.location.href = '/donasi/sukses?order_id=' + result.order_id;
            },
            // Callback jika pending (user menutup popup tanpa bayar/pilih metode)
            onPending: function(result){
                alert("Menunggu pembayaran! Silakan selesaikan pembayaran Anda.");
            },
            // Callback jika error
            onError: function(result){
                alert("Pembayaran gagal! Silakan coba lagi.");
                console.log(result);
            }
        });
    };
</script>

</body>
</html>
