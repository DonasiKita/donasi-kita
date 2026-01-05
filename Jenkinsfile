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
                    // Gunakan docker-compose (dengan tanda hubung)
                    sh "docker-compose down || true"
                    sh "docker-compose up -d"

                    // Memberi waktu database untuk booting
                    sh "sleep 15"

                    // Jalankan migrasi
                    sh "docker exec running-donasi php artisan migrate --force"
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
