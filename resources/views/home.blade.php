<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DonasiKita - Platform Crowdfunding & Donasi Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --success: #4CAF50;
            --light: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="white"/></svg>');
            background-size: cover;
            opacity: 0.1;
        }

        .campaign-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }

        .campaign-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .campaign-card img {
            height: 200px;
            object-fit: cover;
        }

        .progress {
            height: 8px;
            border-radius: 4px;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .stat-box {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .stat-number {
            font-size: 48px;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        footer {
            background: #2c3e50;
            color: white;
            padding: 50px 0 20px;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
                💝 DonasiKita
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#campaigns">Kampanye</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Bersama Wujudkan Kebaikan</h1>
                    <p class="lead mb-4">Platform crowdfunding dan donasi online yang aman, transparan, dan terpercaya untuk membantu sesama yang membutuhkan.</p>
                    <a href="#campaigns" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-heart me-2"></i>Mulai Donasi
                    </a>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=600"
                         alt="Hero" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="stat-number" id="totalDonations">0</div>
                        <p class="text-muted">Total Donasi</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="stat-number" id="totalCampaigns">0</div>
                        <p class="text-muted">Kampanye</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="stat-number" id="totalDonors">0</div>
                        <p class="text-muted">Donatur</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Campaigns -->
    <section id="campaigns" class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="fw-bold mb-3">Kampanye Terbaru</h2>
                    <p class="text-muted">Pilih kampanye yang ingin Anda dukung</p>
                </div>
            </div>

            <div class="row" id="campaignsContainer">
                <!-- Campaigns will be loaded here -->
            </div>

            <div class="text-center mt-5">
                <a href="#" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-list me-2"></i>Lihat Semua Kampanye
                </a>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-5 bg-light" id="about">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="fw-bold mb-3">Kenapa Memilih DonasiKita?</h2>
                    <p class="text-muted">Platform donasi terpercaya dengan berbagai keunggulan</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="fw-bold mt-3">Aman & Terpercaya</h4>
                        <p class="text-muted">Transaksi aman dengan sistem pembayaran terpercaya dan transparan.</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h4 class="fw-bold mt-3">Cepat & Mudah</h4>
                        <p class="text-muted">Donasi hanya dalam 3 langkah mudah tanpa proses yang rumit.</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4 class="fw-bold mt-3">Update Real-time</h4>
                        <p class="text-muted">Pantau perkembangan donasi secara real-time dan transparan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h4 class="mb-4">💝 DonasiKita</h4>
                    <p>Platform crowdfunding dan donasi online berbasis cloud untuk membantu sesama dengan cara yang mudah, transparan, dan terpercaya.</p>
                    <div class="social-links mt-4">
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-youtube fa-lg"></i></a>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <h5 class="mb-4">Kontak Kami</h5>
                    <p><i class="fas fa-envelope me-2"></i> admin@donasikita.id</p>
                    <p><i class="fas fa-phone me-2"></i> +62 812-3456-7890</p>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Jl. Contoh No. 123, Jakarta</p>
                </div>

                <div class="col-lg-4 mb-4">
                    <h5 class="mb-4">Menu Cepat</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/') }}" class="text-light text-decoration-none">Beranda</a></li>
                        <li class="mb-2"><a href="#campaigns" class="text-light text-decoration-none">Kampanye</a></li>
                        <li class="mb-2"><a href="#about" class="text-light text-decoration-none">Tentang</a></li>
                        <li class="mb-2"><a href="{{ route('admin.login') }}" class="text-light text-decoration-none">Admin Login</a></li>
                    </ul>
                </div>
            </div>

            <hr class="bg-light my-4">

            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2024 DonasiKita. Hak cipta dilindungi.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">Dibuat dengan ❤️ untuk kebaikan bersama</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animated counter
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target.toLocaleString();
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current).toLocaleString();
                }
            }, 20);
        }

        // Fetch home data from API
        async function loadHomeData() {
            try {
                const response = await fetch('/api/');
                const data = await response.json();

                if (data.success) {
                    // Update statistics
                    animateCounter(document.getElementById('totalDonations'),
                                  data.data.statistics.total_donations);
                    animateCounter(document.getElementById('totalCampaigns'),
                                  data.data.statistics.total_campaigns);
                    animateCounter(document.getElementById('totalDonors'),
                                  data.data.statistics.total_donors);

                    // Load campaigns
                    const container = document.getElementById('campaignsContainer');
                    container.innerHTML = '';

                    data.data.featured_campaigns.forEach(campaign => {
                        const campaignCard = `
                            <div class="col-md-4 mb-4">
                                <div class="campaign-card card shadow">
                                    <img src="${campaign.image_url || 'https://via.placeholder.com/600x400/667eea/ffffff?text=DonasiKita'}"
                                         class="card-img-top" alt="${campaign.title}">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold">${campaign.title}</h5>
                                        <p class="card-text text-muted">${campaign.description}</p>

                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Terkumpul</span>
                                                <span>${campaign.progress_percentage.toFixed(1)}%</span>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: ${campaign.progress_percentage}%"></div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <small class="text-muted">Target</small>
                                                <p class="mb-0 fw-bold">Rp ${campaign.target_amount.toLocaleString('id-ID')}</p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <small class="text-muted">Terkumpul</small>
                                                <p class="mb-0 fw-bold text-success">Rp ${campaign.current_amount.toLocaleString('id-ID')}</p>
                                            </div>
                                        </div>

                                        <a href="/donation/create?campaign_id=${campaign.id}" class="btn btn-primary w-100">
                                            <i class="fas fa-donate me-2"></i>Donasi Sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.innerHTML += campaignCard;
                    });
                }
            } catch (error) {
                console.error('Error loading home data:', error);
            }
        }

        // Load data when page loads
        document.addEventListener('DOMContentLoaded', loadHomeData);

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
