pipeline {
    agent any
    stages {
        stage('Clone Repository') {
            steps {
                git branch: 'main', credentialsId: 'github-token-donasikita', url: 'https://github.com/DonasiKita/donasi-kita.git'
            }
        }

        stage('Install Dependencies') {
            steps {
                script {
                    // Membuat folder 'vendor' agar tidak error "Failed to open stream"
                    sh 'composer install --no-dev --optimize-autoloader'
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    // Membangun image 'donasi-app' dari Dockerfile terbaru
                    sh "docker build -t donasi-app ."
                }
            }
        }

        stage('Deploy & Database Migration') {
            steps {
                script {
                    // 1. Jalankan container aplikasi dan database menggunakan docker-compose
                    // Ini memastikan container 'running-donasi' terhubung ke 'mysql-donasi'
                    sh "docker-compose down || true"
                    sh "docker-compose up -d"

                    // 2. Jeda 10 detik agar container MySQL siap menerima koneksi
                    sh "sleep 10"

                    // 3. Jalankan migrasi database otomatis ke dalam container
                    // Menggunakan --force karena berjalan di environment production (Azure)
                    sh "docker exec running-donasi php artisan migrate --force"
                }
            }
        }
    }
}
