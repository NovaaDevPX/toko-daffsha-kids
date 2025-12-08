<?php
include "../../../include/conn.php";
include "../../../include/base-url.php";

// Pastikan method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  die("Invalid request method.");
}

// Pastikan ID ada di URL
if (!isset($_GET['id'])) {
  die("ID produk tidak ditemukan.");
}

$id = intval($_GET['id']);

// Ambil data POST
$name  = trim($_POST['name']);
$price = intval($_POST['price']);
$stock = intval($_POST['stock']);

// Validasi sederhana
if ($name === "" || $price <= 0 || $stock < 0) {
  die("Input tidak valid.");
}

// Query UPDATE menggunakan prepared statement
$stmt = $conn->prepare("
    UPDATE products 
    SET name = ?, price = ?, stock = ?
    WHERE id = ?
");

$stmt->bind_param("siii", $name, $price, $stock, $id);

if ($stmt->execute()) {
  header("Location: /toko-daffsha-kids/dashboard/products?success=updated");
  exit;
} else {
  echo "Gagal mengupdate data: " . $conn->error;
}

$stmt->close();
$conn->close();
