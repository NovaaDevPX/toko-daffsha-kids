

<?php
require "../../include/conn.php";
require "../../include/base-url.php";

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
  header("Location: ../ui/index.php?error=transaction_failed");
  exit;
}

// ===============================
// 1. Simpan transaksi utama
// ===============================
$sql = "INSERT INTO transactions (user_id, total, payment, change_money, method)
        VALUES ($user_id, $total, $payment, $change, 'cash')";

if (!$conn->query($sql)) {
  header("Location: ../ui/index.php?error=transaction_failed");
  exit;
}

// Ambil ID transaksi baru
$transaction_id = $conn->insert_id;

// ===============================
// 2. Simpan item transaksi & kurangi stok
// ===============================
$product_ids = $_POST['product_id'];
$qtys        = $_POST['qty'];
$prices      = $_POST['price'];
$subtotals   = $_POST['subtotal'];

// Loop untuk tiap item
for ($i = 0; $i < count($product_ids); $i++) {

  $pid  = $product_ids[$i];
  $qty  = $qtys[$i];
  $price = $prices[$i];
  $sub  = $subtotals[$i];

  // Cek stok dulu
  $res = $conn->query("SELECT stock FROM products WHERE id = $pid");
  if (!$res || $res->num_rows == 0) {
    header("Location: ../ui/index.php?error=transaction_failed");
    exit;
  }

  $product = $res->fetch_assoc();
  if ($qty > $product['stock']) {
    header("Location: ../ui/index.php?error=stock_low");
    exit;
  }

  // Simpan ke transaction_items
  $sql_item = "INSERT INTO transaction_items (transaction_id, product_id, qty, price, subtotal)
                 VALUES ($transaction_id, $pid, $qty, $price, $sub)";

  if (!$conn->query($sql_item)) {
    header("Location: ../ui/index.php?error=transaction_failed");
    exit;
  }

  // Kurangi stok produk
  $sql_stok = "UPDATE products SET stock = stock - $qty WHERE id = $pid";
  if (!$conn->query($sql_stok)) {
    header("Location: ../ui/index.php?error=transaction_failed");
    exit;
  }
}

// ===============================
// 3. Redirect sukses
// ===============================
header("Location: /toko-daffsha-kids/kasir?success=transaction_success");
exit;
