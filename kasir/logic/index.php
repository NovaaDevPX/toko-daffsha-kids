<?php
require "../../include/conn.php";

// Ambil semua produk
$products = $conn->query("SELECT * FROM products ORDER BY name ASC");
