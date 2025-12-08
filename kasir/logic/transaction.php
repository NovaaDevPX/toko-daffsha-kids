<?php
require "../../include/conn.php";

// Pastikan form di-submit
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ../ui/index.php");
  exit;
}

// Ambil data utama transaksi
$total      = $_POST['total'];
$payment    = $_POST['payment'];
$change     = $_POST['change'];

// Untuk sekarang user_id kita buat static (nanti bisa pakai session)
$user_id = 1;

// Validasi sederhana
if ($total <= 0 || empty($_POST['product_id'])) {
  die("Transaksi gagal: Tidak ada barang yang dibeli.");
}

// ===============================
// 1. Simpan transaksi utama
// ===============================
$sql = "INSERT INTO transactions (user_id, total, payment, change_money, method)
        VALUES ($user_id, $total, $payment, $change, 'cash')";

if (!$conn->query($sql)) {
  die("Gagal menyimpan transaksi: " . $conn->error);
}

// Ambil ID transaksi baru
$transaction_id = $conn->insert_id;


// ===============================
// 2. Simpan item transaksi
// ===============================
$product_ids = $_POST['product_id'];
$qtys        = $_POST['qty'];
$prices      = $_POST['price'];
$subtotals   = $_POST['subtotal'];

for ($i = 0; $i < count($product_ids); $i++) {

  $pid  = $product_ids[$i];
  $qty  = $qtys[$i];
  $price = $prices[$i];
  $sub  = $subtotals[$i];

  // Simpan ke transaction_items
  $sql_item = "INSERT INTO transaction_items (transaction_id, product_id, qty, price, subtotal)
                 VALUES ($transaction_id, $pid, $qty, $price, $sub)";

  if (!$conn->query($sql_item)) {
    die("Gagal menyimpan item transaksi: " . $conn->error);
  }

  // ===============================
  // 3. Kurangi stok produk
  // ===============================
  $sql_stok = "UPDATE products SET stock = stock - $qty WHERE id = $pid";

  if (!$conn->query($sql_stok)) {
    die("Gagal update stok: " . $conn->error);
  }
}


// ===============================
// 4. Redirect dengan pesan sukses
// ===============================
header("Location: ../ui/index.php?success=1");
exit;
