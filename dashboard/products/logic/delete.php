<?php
include "../../../include/conn.php";
include "../../../include/base-url.php";

if (!isset($_GET['id'])) {
  header("Location: /toko-daffsha-kids/dashboard/products/");
  exit;
}

$id = intval($_GET['id']);

// Soft delete → tandai produk sebagai terhapus
$query = "UPDATE products SET is_deleted = 1 WHERE id = $id";

if ($conn->query($query)) {
  // Redirect kembali ke product page
  header("Location: /toko-daffsha-kids/dashboard/products/?deleted=success");
} else {
  header("Location: /toko-daffsha-kids/dashboard/products/?deleted=failed");
}
exit;
