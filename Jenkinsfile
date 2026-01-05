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
                    // 1. Matikan compose dan hapus kontainer bernama sama secara paksa
                    sh "docker-compose down || true"
                    sh "docker rm -f mysql-donasi running-donasi || true"

                    // 2. Jalankan ulang dengan kondisi bersih
                    sh "docker-compose up -d"

                    // 3. Jeda 15 detik agar MySQL siap
                    sh "sleep 15"

                    // 4. Jalankan migrasi database
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
