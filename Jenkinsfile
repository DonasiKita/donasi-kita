pipeline {
    agent any
    stages {
        stage('Clone Repository') {
            steps {
                git branch: 'main', credentialsId: 'github-token-donasikita', url: 'https://github.com/DonasiKita/donasi-kita.git'
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    sh "docker build -t donasi-app ."
                }
            }
        }

        stage('Deploy & Database Migration') {
            steps {
                script {
                    // 1. STOP & REMOVE HANYA KONTAINER APLIKASI (Sesuai Syarat Issue #14)
                    // Kita tidak menggunakan 'docker-compose down' agar database (MySQL) TETAP MENYALA
                    // sehingga downtime website menjadi sangat singkat.
                    sh "docker stop running-donasi || true"
                    sh "docker rm running-donasi || true"

                    // 2. JALANKAN ULANG MENGGUNAKAN COMPOSE
                    // Compose akan mendeteksi kontainer aplikasi hilang dan membuatnya baru,
                    // sementara kontainer database tetap berjalan tanpa gangguan.
                    sh "docker-compose up -d"

                    // 3. Jeda singkat (10 detik cukup karena DB tidak restart)
                    echo "Menunggu aplikasi siap..."
                    sh "sleep 10"

                    // 4. Jalankan migrasi dan seeder
                    // Tetap menggunakan migrate:fresh --seed sesuai permintaan Anda
                    retry(3) {
                        sh "docker exec running-donasi php artisan migrate:fresh --seed --force"
                    }
                }
            }
        }

        stage('Cleanup Old Data') {
            steps {
                script {
                    sh "docker system prune -f"
                }
            }
        }
    }
}
