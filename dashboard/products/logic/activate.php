<?php
include "../../../include/conn.php";

if (!isset($_REQUEST['id'])) {
  die("ID produk tidak ditemukan.");
}

$id = intval($_REQUEST['id']);

// Aktifkan produk kembali
$query = "UPDATE products SET is_deleted = 0 WHERE id = $id";
if ($conn->query($query)) {
  // Redirect dengan success
  header("Location: /toko-daffsha-kids/dashboard/products?success=active");
} else {
  // Jika gagal, redirect dengan error
  header("Location: /toko-daffsha-kids/dashboard/products?error=failed");
}
exit;
