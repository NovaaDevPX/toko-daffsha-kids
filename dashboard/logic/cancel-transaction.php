<?php
include "../../include/conn.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(["success" => false, "message" => "Invalid request"]);
  exit;
}

$id = intval($_POST['id']);

// Ambil transaksi
$trx = $conn->query("
  SELECT * FROM transactions WHERE id = $id
")->fetch_assoc();

if (!$trx) {
  echo json_encode(["success" => false, "message" => "Transaksi tidak ditemukan"]);
  exit;
}

if ($trx['status'] === 'cancelled') {
  echo json_encode(["success" => false, "message" => "Transaksi sudah dibatalkan"]);
  exit;
}

// Ambil item transaksi
$items = $conn->query("
  SELECT product_id, qty 
  FROM transaction_items 
  WHERE transaction_id = $id
");

$conn->begin_transaction();

try {
  // Kembalikan stok
  while ($item = $items->fetch_assoc()) {
    $pid = $item['product_id'];
    $qty = $item['qty'];

    $conn->query("
      UPDATE products 
      SET stock = stock + $qty 
      WHERE id = $pid
    ");
  }

  // Update status transaksi
  $conn->query("
    UPDATE transactions 
    SET status = 'cancelled' 
    WHERE id = $id
  ");

  $conn->commit();

  echo json_encode(["success" => true]);
} catch (Exception $e) {
  $conn->rollback();
  echo json_encode(["success" => false, "message" => "Gagal membatalkan"]);
}
