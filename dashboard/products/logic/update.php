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

// Ambil data produk lama dari DB
$result = $conn->query("SELECT * FROM products WHERE id = $id");
if ($result->num_rows === 0) {
  die("Produk tidak ditemukan.");
}
$product = $result->fetch_assoc();

// Default path gambar tetap gambar lama
$imagePath = $product['image'];

// Jika ada file gambar baru yang diupload
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
  $fileTmp  = $_FILES['image']['tmp_name'];
  $fileName = uniqid() . "_" . basename($_FILES['image']['name']);
  $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/toko-daffsha-kids/uploads/products/";
  $targetFile = $targetDir . $fileName;

  // Pastikan folder tujuan ada
  if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

  // Pindahkan file baru
  if (move_uploaded_file($fileTmp, $targetFile)) {
    // Hapus file lama jika ada
    if ($product['image'] && file_exists($_SERVER['DOCUMENT_ROOT'] . "/" . $product['image'])) {
      unlink($_SERVER['DOCUMENT_ROOT'] . "/" . $product['image']);
    }

    // Update path gambar baru
    $imagePath = "uploads/products/" . $fileName;
  } else {
    die("Gagal mengunggah gambar.");
  }
}

// Update data ke database
$stmt = $conn->prepare("
    UPDATE products 
    SET name = ?, price = ?, stock = ?, image = ?
    WHERE id = ?
");

$stmt->bind_param("siisi", $name, $price, $stock, $imagePath, $id);

if ($stmt->execute()) {
  header("Location: /toko-daffsha-kids/dashboard/products?success=updated");
  exit;
} else {
  echo "Gagal mengupdate data: " . $conn->error;
}

$stmt->close();
$conn->close();
