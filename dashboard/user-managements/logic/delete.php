<?php
include "../../../include/conn.php";
include "../../../include/base-url.php";

// Ambil ID user dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Jika ID tidak valid
if ($id <= 0) {
  header("Location: /toko-daffsha-kids/dashboard/user-managements?error=invalid_id");
  exit;
}

// Cek apakah user ada
$checkUser = mysqli_query($conn, "SELECT id FROM users WHERE id = $id LIMIT 1");

if (mysqli_num_rows($checkUser) === 0) {
  header("Location: /toko-daffsha-kids/dashboard/user-managements?error=user_not_found");
  exit;
}

// Eksekusi delete
$query = mysqli_query($conn, "DELETE FROM users WHERE id = $id");

if ($query) {
  header("Location: /toko-daffsha-kids/dashboard/user-managements?success=deleted");
  exit;
} else {
  header("Location: /toko-daffsha-kids/dashboard/user-managements?error=delete_failed");
  exit;
}
