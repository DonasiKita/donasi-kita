<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Donasi - DonasiKita</title>

    <!-- Midtrans Snap JS -->
    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>

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
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .payment-container {
            max-width: 600px;
            width: 100%;
        }

        .payment-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 40px;
            text-align: center;
        }

        .payment-icon {
            font-size: 80px;
            color: var(--primary);
            margin-bottom: 20px;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .info-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .info-label {
            color: #666;
            font-weight: 500;
        }

        .info-value {
            font-weight: 600;
            color: #333;
        }

        .amount {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary);
            margin: 10px 0;
        }

        .btn-pay {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border: none;
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s;
            width: 100%;
        }

        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-pay:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s linear infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }

        .back-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            margin-top: 20px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .payment-methods {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .payment-method {
            display: inline-block;
            background: #f8f9fa;
            padding: 8px 15px;
            border-radius: 20px;
            margin: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="payment-card">
            <div class="payment-icon">💳</div>
            <h1>Lanjutkan Pembayaran</h1>
            <p class="text-muted">Selesaikan pembayaran untuk donasi Anda</p>

            <!-- Donation Info -->
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Kampanye:</span>
                    <span class="info-value">{{ $donation->campaign->title }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nama Donatur:</span>
                    <span class="info-value">{{ $donation->donor_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $donation->donor_email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">ID Transaksi:</span>
                    <span class="info-value">{{ $donation->midtrans_order_id }}</span>
                </div>
            </div>

            <!-- Amount -->
            <div class="amount">
                Rp {{ number_format($donation->amount, 0, ',', '.') }}
            </div>

            <!-- Payment Button -->
            <button id="payButton" class="btn btn-pay">
                <span id="btnText">
                    <i class="fas fa-credit-card me-2"></i>Bayar Sekarang
                </span>
                <span class="spinner" id="loadingSpinner" style="display: none;"></span>
            </button>

            <!-- Payment Methods Info -->
            <div class="payment-methods">
                <small class="text-muted">Metode pembayaran yang tersedia:</small>
                <div class="mt-2">
                    <span class="payment-method">💳 Kartu Kredit</span>
                    <span class="payment-method">🏦 Transfer Bank</span>
                    <span class="payment-method">📱 E-Wallet</span>
                    <span class="payment-method">🔲 QRIS</span>
                </div>
            </div>

            <!-- Back Link -->
            <a href="{{ route('donation.create', ['campaign_id' => $donation->campaign_id]) }}" class="back-link">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Form Donasi
            </a>
        </div>

        <!-- Status Messages -->
        <div id="statusMessages" style="display: none;"></div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Payment data
        const snapToken = "{{ $donation->midtrans_snap_token }}";
        const orderId = "{{ $donation->midtrans_order_id }}";
        const donationId = "{{ $donation->id }}";

        // DOM elements
        const payButton = document.getElementById('payButton');
        const btnText = document.getElementById('btnText');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const statusMessages = document.getElementById('statusMessages');

        // Function to show message
        function showMessage(type, message) {
            statusMessages.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show mt-3">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            statusMessages.style.display = 'block';
        }

        // Function to redirect with delay
        function redirectWithDelay(url, delay = 2000, message = 'Mengalihkan...') {
            showMessage('info', `${message} (${delay/1000} detik)`);
            setTimeout(() => {
                window.location.href = url;
            }, delay);
        }

        // Open Midtrans Snap when pay button is clicked
        payButton.addEventListener('click', function() {
            // Show loading
            btnText.innerHTML = '<i class="fas fa-spinner me-2"></i>Membuka Pembayaran';
            loadingSpinner.style.display = 'inline-block';
            payButton.disabled = true;

            // Open Midtrans Snap
            window.snap.pay(snapToken, {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    showMessage('success', '✅ Pembayaran berhasil! Terima kasih atas donasi Anda.');

                    // Redirect to success page
                    redirectWithDelay(
                        `/donation/success?order_id=${orderId}`,
                        3000,
                        'Pembayaran berhasil. Mengalihkan...'
                    );
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    showMessage('warning', '⏳ Pembayaran tertunda. Silakan selesaikan pembayaran Anda.');

                    // Re-enable button
                    btnText.innerHTML = '<i class="fas fa-credit-card me-2"></i>Coba Lagi';
                    loadingSpinner.style.display = 'none';
                    payButton.disabled = false;

                    // Option: redirect to status page
                    setTimeout(() => {
                        window.location.href = `/donation/status/${orderId}`;
                    }, 5000);
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    showMessage('danger', '❌ Pembayaran gagal. Silakan coba lagi.');

                    // Re-enable button
                    btnText.innerHTML = '<i class="fas fa-credit-card me-2"></i>Coba Lagi';
                    loadingSpinner.style.display = 'none';
                    payButton.disabled = false;
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    showMessage('info', 'ℹ️ Popup pembayaran ditutup. Anda dapat mencoba lagi.');

                    // Re-enable button
                    btnText.innerHTML = '<i class="fas fa-credit-card me-2"></i>Bayar Sekarang';
                    loadingSpinner.style.display = 'none';
                    payButton.disabled = false;
                }
            });
        });

        // Auto-open payment popup after 2 seconds (optional)
        setTimeout(() => {
            // Uncomment below to auto-open payment popup
            // payButton.click();
        }, 2000);

        // Check payment status periodically
        let checkCount = 0;
        const maxChecks = 30; // Check for 5 minutes (30 * 10 seconds)

        function checkPaymentStatus() {
            if (checkCount >= maxChecks) {
                console.log('Stopped checking payment status');
                return;
            }

            fetch(`/api/donations/status/${orderId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const status = data.data.status;

                        if (status === 'success') {
                            showMessage('success', '✅ Pembayaran berhasil terdeteksi!');
                            redirectWithDelay(
                                `/donation/success?order_id=${orderId}`,
                                2000
                            );
                        } else if (status === 'failed') {
                            showMessage('danger', '❌ Pembayaran gagal.');
                        }
                        // If still pending, continue checking
                    }
                })
                .catch(error => {
                    console.error('Error checking payment status:', error);
                })
                .finally(() => {
                    checkCount++;
                    // Check every 10 seconds
                    setTimeout(checkPaymentStatus, 10000);
                });
        }

        // Start checking payment status
        // checkPaymentStatus();
    </script>
</body>
</html>
