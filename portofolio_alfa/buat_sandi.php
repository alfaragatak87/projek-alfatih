<?php
// Ganti 'admin123' dengan password baru yang kamu inginkan
$password_baru_saya = 'Alfatih123'; // GANTI INI

// Proses enkripsi password
$hash_password = password_hash($password_baru_saya, PASSWORD_DEFAULT);

// Tampilkan hasilnya
echo "Password Asli: " . $password_baru_saya . "<br>";
echo "Kode Hash Baru: " . $hash_password;
?>