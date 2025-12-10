<?php
require "../../include/conn.php";

// Ambil semua produk
$products = $result = $conn->query("SELECT * FROM products WHERE is_deleted = 0 ORDER BY name ASC");
