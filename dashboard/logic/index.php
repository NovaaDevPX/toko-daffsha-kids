<?php
include "../../include/conn.php";

// --- Ringkasan Statistik ---
// Total Produk
$totalProducts = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];

// Total Transaksi
$totalTransactions = $conn->query("SELECT COUNT(*) as total FROM transactions")->fetch_assoc()['total'];

$totalRevenue = $conn->query("
  SELECT SUM(total) as total 
  FROM transactions 
  WHERE status = 'completed'
")->fetch_assoc()['total'];

$totalRevenue = $totalRevenue ?? 0;


// Total Users (Admin + Kasir)
$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];

// ================= FILTER STATUS =================
$statusFilter = $_GET['status'] ?? 'all';

$whereClause = "";
if ($statusFilter === 'completed') {
  $whereClause = "WHERE t.status = 'completed'";
} elseif ($statusFilter === 'cancelled') {
  $whereClause = "WHERE t.status = 'cancelled'";
}

// ================= QUERY TRANSAKSI =================
$recentTransactions = [];
$sql = "
    SELECT t.id, t.total, t.method, t.created_at, t.status, u.name as kasir
    FROM transactions t
    JOIN users u ON t.user_id = u.id
    $whereClause
    ORDER BY t.created_at DESC
    LIMIT 10
";

$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
  $recentTransactions[] = $row;
}


// --- Produk Terlaris ---
$bestSellingProducts = [];
$res = $conn->query("
    SELECT p.id, p.name, p.stock, SUM(ti.qty) as total_sold
    FROM transaction_items ti
    JOIN products p ON ti.product_id = p.id
    GROUP BY p.id
    ORDER BY total_sold DESC
    LIMIT 5
");
while ($row = $res->fetch_assoc()) {
  $bestSellingProducts[] = $row;
}

// --- Produk Stok Rendah (threshold < 10) ---
$lowStockProducts = [];
$res = $conn->query("
    SELECT id, name, stock
    FROM products
    WHERE stock < 10
    ORDER BY stock ASC
");
while ($row = $res->fetch_assoc()) {
  $lowStockProducts[] = $row;
}

$hariIndo = [
  'Sunday' => 'Minggu',
  'Monday' => 'Senin',
  'Tuesday' => 'Selasa',
  'Wednesday' => 'Rabu',
  'Thursday' => 'Kamis',
  'Friday' => 'Jumat',
  'Saturday' => 'Sabtu'
];

$bulanIndo = [
  1 => 'Januari',
  2 => 'Februari',
  3 => 'Maret',
  4 => 'April',
  5 => 'Mei',
  6 => 'Juni',
  7 => 'Juli',
  8 => 'Agustus',
  9 => 'September',
  10 => 'Oktober',
  11 => 'November',
  12 => 'Desember'
];

$hari = $hariIndo[date('l')];
$tanggal = date('d');
$bulan = $bulanIndo[intval(date('m'))];
$tahun = date('Y');

$formattedTanggal = "$hari, $tanggal $bulan $tahun";
