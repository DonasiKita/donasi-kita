<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi - DonasiKita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        .donation-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }

        .donation-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .donation-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .donation-body {
            padding: 30px;
        }

        .campaign-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .amount-button {
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 10px 20px;
            margin: 5px;
            transition: all 0.3s;
            background: white;
        }

        .amount-button:hover, .amount-button.active {
            border-color: #667eea;
            background: #667eea;
            color: white;
        }

        .btn-donate {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 15px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-donate:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .progress-bar {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body>
    <div class="donation-container">
        <div class="donation-card">
            <div class="donation-header">
                <h1 class="mb-3">💝 Donasi Sekarang</h1>
                <p class="mb-0">Berbagi kebaikan, wujudkan harapan</p>
            </div>

            <div class="donation-body">
                @if($campaign)
                <div class="campaign-info">
                    <h4>{{ $campaign->title }}</h4>
                    <p class="text-muted mb-2">{{ Str::limit($campaign->description, 100) }}</p>

                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Terkumpul</span>
                            <span>{{ number_format($campaign->progress_percentage, 1) }}%</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar" style="width: {{ $campaign->progress_percentage }}%"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Target</small>
                            <p class="mb-0 fw-bold">Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted">Terkumpul</small>
                            <p class="mb-0 fw-bold text-success">Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <form id="donationForm">
                    @csrf
                    <input type="hidden" id="campaign_id" value="{{ $campaign->id ?? '' }}">

                    <div class="mb-4">
                        <label class="form-label fw-bold">Pilih Nominal Donasi</label>
                        <div class="d-flex flex-wrap justify-content-center">
                            <button type="button" class="amount-button" data-amount="25000">Rp 25.000</button>
                            <button type="button" class="amount-button" data-amount="50000">Rp 50.000</button>
                            <button type="button" class="amount-button" data-amount="100000">Rp 100.000</button>
                            <button type="button" class="amount-button" data-amount="250000">Rp 250.000</button>
                            <button type="button" class="amount-button" data-amount="500000">Rp 500.000</button>
                        </div>
                        <div class="mt-3">
                            <label for="custom_amount" class="form-label">Atau masukkan nominal lain</label>
                            <input type="number" class="form-control" id="custom_amount"
                                   placeholder="Masukkan jumlah (minimum Rp 10.000)" min="10000">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="donor_name" class="form-label fw-bold">Nama Donatur *</label>
                        <input type="text" class="form-control" id="donor_name"
                               placeholder="Nama lengkap atau anonim" required>
                    </div>

                    <div class="mb-4">
                        <label for="donor_email" class="form-label fw-bold">Email *</label>
                        <input type="email" class="form-control" id="donor_email"
                               placeholder="email@contoh.com" required>
                    </div>

                    <div class="mb-4">
                        <label for="note" class="form-label">Pesan (Opsional)</label>
                        <textarea class="form-control" id="note" rows="3"
                                  placeholder="Tulis pesan atau doa untuk penerima donasi..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-donate mt-3">
                        <span id="btnText">💳 Lanjut ke Pembayaran</span>
                        <div class="spinner-border spinner-border-sm text-light ms-2"
                             id="loadingSpinner" style="display: none;"></div>
                    </button>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ url('/') }}" class="text-decoration-none">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

    <!-- Midtrans Snap JS -->
    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        // Amount button selection
        document.querySelectorAll('.amount-button').forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.amount-button').forEach(btn => {
                    btn.classList.remove('active');
                });

                // Add active class to clicked button
                this.classList.add('active');

                // Set custom amount input
                document.getElementById('custom_amount').value = this.dataset.amount;
            });
        });

        // Custom amount input clears button selection
        document.getElementById('custom_amount').addEventListener('input', function() {
            document.querySelectorAll('.amount-button').forEach(btn => {
                btn.classList.remove('active');
            });
        });

        // Form submission
        document.getElementById('donationForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.querySelector('.btn-donate');
            const btnText = document.getElementById('btnText');
            const spinner = document.getElementById('loadingSpinner');

            // Get amount
            let amount = document.getElementById('custom_amount').value;
            if (!amount || amount < 10000) {
                alert('Masukkan nominal minimum Rp 10.000');
                return;
            }

            // Show loading
            btnText.textContent = 'Memproses...';
            spinner.style.display = 'inline-block';
            submitBtn.disabled = true;

            // Prepare data
            const formData = {
                campaign_id: document.getElementById('campaign_id').value,
                donor_name: document.getElementById('donor_name').value.trim(),
                donor_email: document.getElementById('donor_email').value.trim(),
                amount: parseInt(amount),
                note: document.getElementById('note').value.trim(),
                _token: document.querySelector('input[name="_token"]').value
            };

            try {
                // Send to API
                const response = await fetch('/api/donations', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.success) {
                    // Open Midtrans Snap
                    window.snap.pay(result.data.snap_token, {
                        onSuccess: function(paymentResult) {
                            window.location.href = '/donation/success?order_id=' + result.data.order_id;
                        },
                        onPending: function(paymentResult) {
                            alert('Pembayaran tertunda. Silakan selesaikan pembayaran Anda.');
                        },
                        onError: function(paymentResult) {
                            alert('Pembayaran gagal. Silakan coba lagi.');
                        }
                    });
                } else {
                    throw new Error(result.message || 'Gagal membuat donasi');
                }
            } catch (error) {
                alert('Error: ' + error.message);
                console.error('Donation error:', error);
            } finally {
                // Reset button
                btnText.textContent = '💳 Lanjut ke Pembayaran';
                spinner.style.display = 'none';
                submitBtn.disabled = false;
            }
        });
    </script>
</body>
</html>
