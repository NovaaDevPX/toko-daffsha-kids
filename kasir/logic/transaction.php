<?php
session_start();
require "../../include/conn.php";

header('Content-Type: application/json');

/* ===============================
   CEK METHOD
================================ */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
  exit;
}

/* ===============================
   CEK LOGIN
================================ */
if (!isset($_SESSION['user_id'])) {
  echo json_encode(['status' => 'unauthorized']);
  exit;
}

$user_id = (int) $_SESSION['user_id'];

/* ===============================
   AMBIL DATA
================================ */
$total   = (int) $_POST['total'];
$payment = (int) $_POST['payment'];
$change  = (int) $_POST['change'];

if ($total <= 0 || empty($_POST['product_id'])) {
  echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
  exit;
}

/* ===============================
   SIMPAN TRANSAKSI
================================ */
$conn->query("
  INSERT INTO transactions (user_id, total, payment, change_money, method)
  VALUES ($user_id, $total, $payment, $change, 'cash')
");

$transaction_id = $conn->insert_id;

/* ===============================
   ITEM TRANSAKSI
================================ */
$product_ids = $_POST['product_id'];
$qtys        = $_POST['qty'];
$prices      = $_POST['price'];
$subs        = $_POST['subtotal'];

for ($i = 0; $i < count($product_ids); $i++) {

  $pid = (int) $product_ids[$i];
  $qty = (int) $qtys[$i];

  // Cek stok
  $stok = $conn->query("
    SELECT stock FROM products WHERE id = $pid
  ")->fetch_assoc();

  if ($qty > $stok['stock']) {
    echo json_encode(['status' => 'stock_low']);
    exit;
  }

  // Simpan item transaksi
  $conn->query("
    INSERT INTO transaction_items
    (transaction_id, product_id, qty, price, subtotal)
    VALUES
    ($transaction_id, $pid, $qty, {$prices[$i]}, {$subs[$i]})
  ");

  // Kurangi stok
  $conn->query("
    UPDATE products
    SET stock = stock - $qty
    WHERE id = $pid
  ");
}

echo json_encode([
  'status' => 'success',
  'transaction_id' => $transaction_id
]);
exit;
