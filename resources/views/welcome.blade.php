<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DonasiKita - Platform Crowdfunding & Donasi Online</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .welcome-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 50px;
            max-width: 800px;
            width: 100%;
            text-align: center;
        }

        .logo {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #2d3436;
        }

        .tagline {
            font-size: 1.2rem;
            color: #636e72;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 40px 0;
        }

        .feature {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: transform 0.3s;
        }

        .feature:hover {
            transform: translateY(-5px);
            background: #edf2f7;
        }

        .feature-icon {
            font-size: 32px;
            color: #667eea;
            margin-bottom: 15px;
        }

        .feature h3 {
            font-size: 1.1rem;
            margin-bottom: 10px;
            color: #2d3436;
        }

        .feature p {
            color: #636e72;
            font-size: 0.9rem;
        }

        .buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .btn {
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }

        .stats {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin: 40px 0;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #636e72;
            font-size: 0.9rem;
        }

        footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #636e72;
            font-size: 0.9rem;
        }

        .tech-stack {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .tech-item {
            background: #f8f9fa;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            color: #667eea;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .welcome-container {
                padding: 30px 20px;
            }

            h1 {
                font-size: 2rem;
            }

            .features {
                grid-template-columns: 1fr;
            }

            .buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="logo">💝</div>

        <h1>DonasiKita</h1>

        <p class="tagline">
            Platform crowdfunding & donasi online berbasis cloud yang menghubungkan
            para donatur dengan penerima manfaat secara mudah, transparan, dan terpercaya.
        </p>

        <div class="stats">
            <div class="stat-item">
                <div class="stat-number" id="totalCampaigns">0</div>
                <div class="stat-label">Kampanye</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="totalDonations">0</div>
                <div class="stat-label">Donasi</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="totalAmount">0</div>
                <div class="stat-label">Terkumpul</div>
            </div>
        </div>

        <div class="features">
            <div class="feature">
                <div class="feature-icon">🚀</div>
                <h3>Cloud-Based</h3>
                <p>Berjalan di infrastruktur cloud yang scalable dan handal</p>
            </div>
            <div class="feature">
                <div class="feature-icon">🔒</div>
                <h3>Aman & Transparan</h3>
                <p>Transaksi aman dengan laporan real-time yang transparan</p>
            </div>
            <div class="feature">
                <div class="feature-icon">💳</div>
                <h3>Multi Payment</h3>
                <p>Berbagai metode pembayaran digital yang lengkap</p>
            </div>
            <div class="feature">
                <div class="feature-icon">📱</div>
                <h3>Responsive</h3>
                <p>Akses mudah dari desktop maupun mobile</p>
            </div>
        </div>

        <div class="buttons">
            <a href="/home" class="btn btn-primary">
                <span>🏠 Masuk ke Beranda</span>
            </a>
            <a href="/admin/login" class="btn btn-secondary">
                <span>🔐 Admin Login</span>
            </a>
        </div>

        <div class="tech-stack">
            <span class="tech-item">Laravel</span>
            <span class="tech-item">Bootstrap</span>
            <span class="tech-item">Midtrans</span>
            <span class="tech-item">MySQL</span>
            <span class="tech-item">Docker</span>
            <span class="tech-item">Azure</span>
        </div>

        <footer>
            <p>© 2024 DonasiKita. Tugas Besar Mata Kuliah Komputasi Awan.</p>
            <p>Anggota: Irfan Fathonia, M. Fahrul Firman, Danny, Rama Galih T.S.</p>
        </footer>
    </div>

    <script>
        // Animated counters
        function animateCounter(element, target, prefix = '', suffix = '') {
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = prefix + Math.floor(target).toLocaleString() + suffix;
                    clearInterval(timer);
                } else {
                    element.textContent = prefix + Math.floor(current).toLocaleString() + suffix;
                }
            }, 20);
        }

        // Load statistics from API
        async function loadStatistics() {
            try {
                const response = await fetch('/api/');
                const data = await response.json();

                if (data.success) {
                    animateCounter(
                        document.getElementById('totalCampaigns'),
                        data.data.statistics.total_campaigns
                    );

                    animateCounter(
                        document.getElementById('totalDonations'),
                        data.data.statistics.total_donors
                    );

                    animateCounter(
                        document.getElementById('totalAmount'),
                        data.data.statistics.total_donations / 1000000,
                        'Rp ',
                        ' Jt'
                    );
                }
            } catch (error) {
                console.log('Using fallback statistics');
                // Fallback numbers
                animateCounter(document.getElementById('totalCampaigns'), 25);
                animateCounter(document.getElementById('totalDonations'), 150);
                animateCounter(document.getElementById('totalAmount'), 350, 'Rp ', ' Jt');
            }
        }

        // Load statistics when page loads
        document.addEventListener('DOMContentLoaded', loadStatistics);
    </script>
</body>
</html>
