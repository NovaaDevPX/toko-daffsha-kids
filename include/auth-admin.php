<?php
session_start();

// Cek jika user belum login atau bukan admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  // Jika kasir, redirect ke kasir; jika belum login, ke login
  if (isset($_SESSION['role']) && $_SESSION['role'] === 'kasir') {
    header("Location: /toko-daffsha-kids/kasir/");
  } else {
    header("Location: /toko-daffsha-kids/login/");
  }
  exit();
}
