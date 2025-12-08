<?php
// KONFIGURASI DATABASE
$host     = "localhost";
$user     = "root";
$password = "";
$dbname   = "db_pos";

// KONEKSI
$conn = new mysqli($host, $user, $password, $dbname);

// CEK KONEKSI
if ($conn->connect_error) {
  die("Koneksi database gagal: " . $conn->connect_error);
}

// Set agar hasil fetch UTF-8 (menghindari karakter aneh)
$conn->set_charset("utf8");
