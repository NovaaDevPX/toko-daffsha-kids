<?php
require "../../include/conn.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['status' => 'error']);
  exit;
}

$total   = $_POST['total'];
$payment = $_POST['payment'];
$change  = $_POST['change'];
$user_id = 1;

if ($total <= 0 || empty($_POST['product_id'])) {
  echo json_encode(['status' => 'error']);
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
  $pid = $product_ids[$i];
  $qty = $qtys[$i];

  $stok = $conn->query("SELECT stock FROM products WHERE id=$pid")->fetch_assoc();
  if ($qty > $stok['stock']) {
    echo json_encode(['status' => 'stock_low']);
    exit;
  }

  $conn->query("
    INSERT INTO transaction_items (transaction_id, product_id, qty, price, subtotal)
    VALUES ($transaction_id, $pid, $qty, {$prices[$i]}, {$subs[$i]})
  ");

  $conn->query("UPDATE products SET stock = stock - $qty WHERE id=$pid");
}

echo json_encode([
  'status' => 'success',
  'transaction_id' => $transaction_id
]);
exit;
