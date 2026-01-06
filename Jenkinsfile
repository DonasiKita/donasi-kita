pipeline {
    agent any
    stages {
        stage('Clone Repository') {
            steps {
                git branch: 'main', credentialsId: 'github-token-donasikita', url: 'https://github.com/DonasiKita/donasi-kita.git'
            }
        }

        // TAHAP INSTALL DEPENDENCIES DIHAPUS DARI SINI
        // Karena sudah ada di dalam Dockerfile

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
                    // 1. Bersihkan kontainer lama
                    sh "docker-compose down || true"
                    sh "docker rm -f mysql-donasi running-donasi || true"

                    // 2. Jalankan ulang
                    sh "docker-compose up -d"

                    // 3. Jeda lebih lama agar MySQL benar-benar siap (30 detik)
                    echo "Menunggu MySQL booting..."
                    sh "sleep 30"

                    // 4. Jalankan migrasi DAN seeder
                    // Gunakan migrate:fresh --seed agar database bersih dan data baru masuk
                    retry(3) {
                        sh "docker exec running-donasi php artisan migrate:fresh --seed --force"
                    }
                }
            }
        }

        stage('Cleanup Old Data') {
            steps {
                script {
                    // Membersihkan sisa build lama agar disk VM Azure tidak penuh
                    sh "docker system prune -f"
                }
            }
        }
    }
}
