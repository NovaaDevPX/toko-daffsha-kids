<?php
session_start();
include "../../include/conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = "Email dan password harus diisi!";
    header("Location: ../ui/index.php");
    exit();
  }

  // Query untuk cek user
  $query = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
  $query->bind_param("s", $email);
  $query->execute();
  $result = $query->get_result();
  $user = $result->fetch_assoc();

  if ($user && md5($password) === $user['password']) { // Hash input dengan MD5 dan bandingkan
    // Set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['role'] = $user['role'];

    // Redirect berdasarkan role
    if ($user['role'] === 'admin') {
      header("Location: /toko-daffsha-kids/dashboard/");
    } else {
      header("Location: /toko-daffsha-kids/kasir/");
    }
    exit();
  } else {
    $_SESSION['login_error'] = "Email atau password salah!";
    header("Location: /toko-daffsha-kids/login/");
    exit();
  }
} else {
  header("Location: /toko-daffsha-kids/login/");
  exit();
}
