<?php
include "../../../include/base-url.php";
include "../../../include/conn.php";

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data produk
$query = $conn->prepare("SELECT * FROM products WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$product = $result->fetch_assoc();

if (!$product) {
  header("Location: dashboard/products");
  exit();
}

// Proses update stok
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stock_change = (int)$_POST['stock_change'];
  $new_stock = $product['stock'] + $stock_change;

  if ($new_stock < 0) {
    $error = "Stok tidak boleh negatif!";
  } else {
    $update = $conn->prepare("UPDATE products SET stock = ?, updated_at = NOW() WHERE id = ?");
    $update->bind_param("ii", $new_stock, $id);
    if ($update->execute()) {
      header("Location: /toko-daffsha-kids/dashboard/products?success=stock_updated");
      exit();
    } else {
      $error = "Gagal update stok!";
    }
  }
}
