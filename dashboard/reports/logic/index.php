<?php
include "../../../include/conn.php"; // Koneksi database

// Default filter: bulan ini
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
$payment_method = isset($_GET['method']) ? $_GET['method'] : '';

// Query untuk ringkasan penjualan
$query_summary = "
    SELECT 
        COUNT(t.id) AS total_transactions,
        SUM(t.total) AS total_revenue,
        SUM(t.payment) AS total_payment,
        SUM(t.change_money) AS total_change
    FROM transactions t
    WHERE DATE(t.created_at) BETWEEN '$start_date' AND '$end_date'
";
if ($payment_method) {
  $query_summary .= " AND t.method = '$payment_method'";
}
$result_summary = mysqli_query($conn, $query_summary);
$summary = mysqli_fetch_assoc($result_summary);

// Query untuk produk terlaris
$query_top_products = "
    SELECT 
        p.name AS product_name,
        SUM(ti.qty) AS total_qty_sold,
        SUM(ti.subtotal) AS total_revenue
    FROM transaction_items ti
    JOIN products p ON ti.product_id = p.id
    JOIN transactions t ON ti.transaction_id = t.id
    WHERE DATE(t.created_at) BETWEEN '$start_date' AND '$end_date'
    GROUP BY p.id
    ORDER BY total_qty_sold DESC
    LIMIT 10
";
$result_top_products = mysqli_query($conn, $query_top_products);

// Query untuk daftar transaksi
$query_transactions = "
    SELECT 
        t.id,
        t.created_at,
        u.name AS user_name,
        t.total,
        t.method,
        GROUP_CONCAT(CONCAT(p.name, ' (', ti.qty, 'x)') SEPARATOR ', ') AS items
    FROM transactions t
    JOIN users u ON t.user_id = u.id
    LEFT JOIN transaction_items ti ON t.id = ti.transaction_id
    LEFT JOIN products p ON ti.product_id = p.id
    WHERE DATE(t.created_at) BETWEEN '$start_date' AND '$end_date'
";
if ($payment_method) {
  $query_transactions .= " AND t.method = '$payment_method'";
}
$query_transactions .= " GROUP BY t.id ORDER BY t.created_at DESC";
$result_transactions = mysqli_query($conn, $query_transactions);

// Query untuk stok produk rendah (opsional, ambil produk dengan stock < 10)
$query_low_stock = "
    SELECT name, stock
    FROM products
    WHERE stock < 10
    ORDER BY stock ASC
";
$result_low_stock = mysqli_query($conn, $query_low_stock);
