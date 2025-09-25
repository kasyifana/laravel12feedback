pipeline {
    // Jalankan pipeline ini di dalam container Docker yang memiliki Node.js v18
    agent {
        docker { image 'node:18-alpine' }
    }

    stages {
        stage('Checkout') {
            steps {
                // Perintah 'git checkout' akan otomatis dijalankan oleh Jenkins di awal
                echo 'Mengambil kode dari GitHub...'
            }
        }
        stage('Build') {
            steps {
                echo 'Tahap build sedang berjalan...'
                // Sekarang perintah 'node' dan 'npm' akan ditemukan
                sh 'node --version'
                sh 'npm --version'
                // Kamu bisa tambahkan perintah build-mu di sini, contoh:
                // sh 'npm install'
            }
        }
        stage('Test') {
            steps {
                echo 'Tahap testing sedang berjalan...'
                // Contoh:
                // sh 'npm test'
            }
        }
        // Tahap Deploy belum kita sertakan lagi agar fokus memperbaiki build dulu
    }
}