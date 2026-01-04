<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi - {{ $campaign->title }} - DonasiKita</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --success: #4CAF50;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            padding-top: 20px;
        }

        .donation-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .campaign-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .campaign-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 20px;
        }

        .campaign-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .progress {
            height: 10px;
            border-radius: 5px;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
        }

        .donation-form-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            background: white;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .amount-button {
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 12px 20px;
            margin: 5px;
            background: white;
            transition: all 0.3s;
            font-weight: 600;
        }

        .amount-button:hover, .amount-button.active {
            border-color: var(--primary);
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .btn-donate {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border: none;
            color: white;
            padding: 15px 30px;
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

        .btn-donate:disabled {
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
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .back-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="donation-container">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ url('/campaigns/' . $campaign->id) }}" class="back-link">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Kampanye
            </a>
        </div>

        <!-- Campaign Info -->
        <div class="campaign-card">
            <div class="campaign-header">
                <h2 class="mb-2">{{ $campaign->title }}</h2>
                <p class="mb-0">{{ Str::limit($campaign->description, 150) }}</p>
            </div>

            @if($campaign->image_url)
            <div class="text-center">
                <img src="{{ $campaign->image_url }}" alt="{{ $campaign->title }}" class="campaign-image">
            </div>
            @endif

            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Terkumpul</span>
                        <span>{{ number_format($campaign->progress_percentage, 1) }}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" style="width: {{ $campaign->progress_percentage }}%"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">Target</small>
                        <p class="mb-0 fw-bold">Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <small class="text-muted">Terkumpul</small>
                        <p class="mb-0 fw-bold text-success">Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Donation Form -->
        <div class="donation-form-card p-4">
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

            <h3 class="mb-4">💝 Form Donasi</h3>

            <form id="donationForm" action="{{ route('donation.store') }}" method="POST">
                @csrf
                <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">

                <!-- Amount Selection -->
                <div class="mb-4">
                    <label class="form-label fw-bold mb-3">Pilih Nominal Donasi</label>
                    <div class="d-flex flex-wrap justify-content-center mb-3">
                        <button type="button" class="amount-button" data-amount="10000">Rp 10.000</button>
                        <button type="button" class="amount-button" data-amount="25000">Rp 25.000</button>
                        <button type="button" class="amount-button" data-amount="50000">Rp 50.000</button>
                        <button type="button" class="amount-button" data-amount="100000">Rp 100.000</button>
                        <button type="button" class="amount-button" data-amount="250000">Rp 250.000</button>
                    </div>

                    <div>
                        <label for="custom_amount" class="form-label">Atau masukkan nominal lain</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="custom_amount" name="amount"
                                   placeholder="Masukkan jumlah (minimum Rp 10.000)" min="10000" required>
                        </div>
                        <small class="text-muted">Minimum donasi Rp 10.000</small>
                    </div>
                </div>

                <!-- Donor Information -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label for="donor_name" class="form-label fw-bold">Nama Donatur *</label>
                        <input type="text" class="form-control" id="donor_name" name="donor_name"
                               placeholder="Nama lengkap atau anonim" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="donor_email" class="form-label fw-bold">Email *</label>
                        <input type="email" class="form-control" id="donor_email" name="donor_email"
                               placeholder="email@contoh.com" required>
                        <small class="text-muted">Konfirmasi akan dikirim ke email ini</small>
                    </div>
                </div>

                <!-- Optional Note -->
                <div class="mb-4">
                    <label for="note" class="form-label">Pesan (Opsional)</label>
                    <textarea class="form-control" id="note" name="note" rows="3"
                              placeholder="Tulis pesan atau doa untuk penerima donasi..."></textarea>
                </div>

                <!-- Terms and Conditions -->
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label" for="terms">
                            Saya setuju dengan <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">syarat dan ketentuan</a> donasi
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-donate" id="submitBtn">
                    <span id="btnText">💳 Lanjut ke Pembayaran</span>
                    <span class="spinner" id="loadingSpinner" style="display: none;"></span>
                </button>
            </form>
        </div>

        <!-- Info -->
        <div class="text-center mt-4 text-muted">
            <p class="mb-1">
                <i class="fas fa-lock me-1"></i> Pembayaran aman melalui Midtrans
            </p>
            <p class="mb-0">
                <i class="fas fa-shield-alt me-1"></i> Data Anda terlindungi dan aman
            </p>
        </div>
    </div>

    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Syarat dan Ketentuan Donasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>1. Donasi yang sudah diberikan tidak dapat dikembalikan.</p>
                    <p>2. Dana donasi akan disalurkan sesuai dengan tujuan kampanye.</p>
                    <p>3. Platform DonasiKita tidak memungut biaya administrasi.</p>
                    <p>4. Data pribadi donatur akan dijaga kerahasiaannya.</p>
                    <p>5. Laporan penggunaan dana akan dipublikasikan secara transparan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Amount button selection
        const amountButtons = document.querySelectorAll('.amount-button');
        const customAmountInput = document.getElementById('custom_amount');

        amountButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                amountButtons.forEach(btn => {
                    btn.classList.remove('active');
                });

                // Add active class to clicked button
                this.classList.add('active');

                // Set custom amount input
                customAmountInput.value = this.dataset.amount;
            });
        });

        // Custom amount input clears button selection
        customAmountInput.addEventListener('input', function() {
            amountButtons.forEach(btn => {
                btn.classList.remove('active');
            });
        });

        // Form validation and submission
        document.getElementById('donationForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const spinner = document.getElementById('loadingSpinner');

            // Get amount
            let amount = parseInt(customAmountInput.value);
            if (!amount || amount < 10000) {
                alert('Masukkan nominal minimum Rp 10.000');
                customAmountInput.focus();
                return;
            }

            // Validate name
            const donorName = document.getElementById('donor_name').value.trim();
            if (!donorName) {
                alert('Masukkan nama donatur');
                document.getElementById('donor_name').focus();
                return;
            }

            // Validate email
            const donorEmail = document.getElementById('donor_email').value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(donorEmail)) {
                alert('Masukkan email yang valid');
                document.getElementById('donor_email').focus();
                return;
            }

            // Show loading
            btnText.textContent = 'Memproses...';
            spinner.style.display = 'inline-block';
            submitBtn.disabled = true;

            // Submit form
            this.submit();
        });

        // Auto-format amount input
        customAmountInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });

        // Auto-select first amount button
        if (amountButtons.length > 0 && !customAmountInput.value) {
            amountButtons[2].click(); // Select Rp 50.000 by default
        }
    </script>
</body>
</html>
