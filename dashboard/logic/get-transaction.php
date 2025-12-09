<?php
include "../../include/conn.php";

if (!isset($_GET['id'])) {
  echo json_encode(["error" => "ID tidak ditemukan"]);
  exit;
}

$id = intval($_GET['id']);

// Ambil data transaksi
$trxQuery = $conn->query("
  SELECT t.*, u.name AS kasir 
  FROM transactions t
  LEFT JOIN users u ON u.id = t.user_id
  WHERE t.id = $id
");

$transaction = $trxQuery->fetch_assoc();

// Ambil item transaksi
$itemQuery = $conn->query("
  SELECT ti.*, p.name AS product_name 
  FROM transaction_items ti
  LEFT JOIN products p ON p.id = ti.product_id
  WHERE ti.transaction_id = $id
");

$items = [];
while ($row = $itemQuery->fetch_assoc()) {
  $items[] = $row;
}

echo json_encode([
  "transaction" => $transaction,
  "items" => $items
]);
exit;
