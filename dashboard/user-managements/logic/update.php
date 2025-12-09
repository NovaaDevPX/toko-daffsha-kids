<?php
include "../../../include/conn.php";
include "../../../include/base-url.php";

// Ambil ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query user untuk ditampilkan
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
$user = mysqli_fetch_assoc($query);

if (!$user) {
  echo "<script>alert('User tidak ditemukan!'); window.location='../../user-managements';</script>";
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $role = trim($_POST['role']);
  $password = trim($_POST['password']);

  // Jika password baru diisi → hash
  if (!empty($password)) {
    $passwordHashed = md5($password);
    $query = "UPDATE users SET name='$name', email='$email', role='$role', password='$passwordHashed' WHERE id=$id";
  } else {
    // Password tidak diubah
    $query = "UPDATE users SET name='$name', email='$email', role='$role' WHERE id=$id";
  }

  if (mysqli_query($conn, $query)) {
    header("Location: /toko-daffsha-kids/dashboard/user-managements?success=updated");
    exit;
  } else {
    header("Location: /toko-daffsha-kids/dashboard/user-managements/edit/$id?error=failed");
    exit;
  }
}
