pipeline {
    agent any // Menjalankan pipeline ini di agent/node Jenkins mana pun yang tersedia

    stages {
        stage('Checkout') {
            steps {
                // Jenkins otomatis akan mengambil kode dari repo yang dikonfigurasi
                echo 'Mengambil kode dari GitHub...'
            }
        }
        stage('Build') {
            steps {
                // Ganti perintah ini sesuai dengan teknologimu
                // Contoh untuk proyek Node.js: sh 'npm install'
                // Contoh untuk proyek Java (Maven): sh 'mvn package'
                echo 'Tahap build sedang berjalan...'
                sh 'node --version' // Contoh perintah sederhana
            }
        }
        stage('Test') {
            steps {
                // Ganti perintah ini dengan perintah testing-mu
                // Contoh untuk proyek Node.js: sh 'npm test'
                echo 'Tahap testing sedang berjalan...'
            }
        }
    }
}