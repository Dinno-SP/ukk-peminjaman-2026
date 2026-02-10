-- CARA MENJALANKAN 1--
1. Ekstrak ke dalam folder htdocs
2. Buat database di phpMyAdmin (ukk_peminjaman)
3. Buka terminal vscode:
Lalu jalankan:
php artisan migrate:fresh --seed
php artisan serve
npm install
npm run build

-- CARA MENJALANKAN 2--

1. Nyalakan XAMPP (Start Apache & MySQL).

2. Buka Terminal (CMD / Git Bash).

3. Masuk ke folder penyimpanan htdocs:
cd C:\xampp\htdocs

4. Jalankan di dalam terminal:
git clone https://github.com/Dinno-SP/ukk-peminjaman-2026.git
cd ukk-peminjaman-2026
composer install
copy .env.example .env
php artisan key:generate

5. Buka browser, ketik: localhost/phpmyadmin

6. Buat Database Baru. Beri nama persis: ukk_peminjaman

7. edit .env:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ukk_peminjaman
DB_USERNAME=root
DB_PASSWORD=

8. Ketik perintah ini di terminal:
php artisan migrate:fresh --seed
php artisan storage:link

9. Ketik perintah ini di terminal vscode:
npm install
npm run build
php artisan serve

10. Buka browser dan akses: http://127.0.0.1:8000

11. Login User:
admin@sekolah.com / password
petugas@sekolah.com / password
siswa@sekolah.com / password

-- SELESAI --
