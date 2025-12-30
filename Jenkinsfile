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
                    // Menggunakan Dockerfile yang sudah Anda buat sebelumnya
                    sh "docker build -t donasi-app ."
                }
            }
        }
        stage('Deploy to Server') {
            steps {
                script {
                    // Menghapus container lama jika ada dan menjalankan yang baru di port 80
                    sh "docker stop running-donasi || true && docker rm running-donasi || true"
                    sh "docker run -d -p 80:80 --name running-donasi donasi-app"
                }
            }
        }
    }
}
