<?php
include "../../../include/conn.php";
include "../../../include/base-url.php";

// Cek ID
if (!isset($_GET['id'])) {
  die("ID tidak ditemukan.");
}

$id = intval($_GET['id']);

// Query DELETE
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
  // Redirect aman, tidak terpengaruh <base>
  header("Location: /toko-daffsha-kids/dashboard/products?success=deleted");
  exit;
} else {
  echo "Gagal menghapus produk: " . $conn->error;
}

$stmt->close();
$conn->close();
