<?php
include "../../include/base-url.php";
include "../logic/index.php"; // ambil data dari logic
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="bg-gray-100">

  <?php include "../../include/layouts/sidebar.php"; ?>
  <?php include "../../include/layouts/header.php"; ?>

  <div class="ml-64 pt-20 p-8">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-100 to-blue-50 p-6 rounded-xl shadow flex flex-col md:flex-row md:items-center md:justify-between mb-8 animate-fadeIn">
      <div>
        <h1 class="text-3xl md:text-4xl font-bold text-blue-800 mb-2 flex items-center gap-2">
          <span>Selamat Datang, Admin</span>
          <span class="animate-bounce">👋</span>
        </h1>
        <p class="text-gray-600">Hari ini <?= $formattedTanggal; ?></p>
      </div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

      <!-- Total Produk -->
      <div class="p-5 bg-blue-50 rounded-xl shadow hover:shadow-lg transition flex items-center gap-4">
        <div class="bg-blue-200 p-3 rounded-full">
          <i data-feather="box" class="w-6 h-6 text-blue-700"></i>
        </div>
        <div>
          <p class="text-gray-600 font-medium">Total Produk</p>
          <p class="text-2xl md:text-2xl font-bold text-blue-700"><?= $totalProducts; ?></p>
        </div>
      </div>

      <!-- Total Transaksi -->
      <div class="p-5 bg-yellow-50 rounded-xl shadow hover:shadow-lg transition flex items-center gap-4">
        <div class="bg-yellow-200 p-3 rounded-full">
          <i data-feather="shopping-cart" class="w-6 h-6 text-yellow-700"></i>
        </div>
        <div>
          <p class="text-gray-600 font-medium">Total Transaksi</p>
          <p class="text-2xl md:text-2xl font-bold text-yellow-700"><?= $totalTransactions; ?></p>
        </div>
      </div>

      <!-- Total Pendapatan -->
      <div class="p-5 bg-green-50 rounded-xl shadow hover:shadow-lg transition flex items-center gap-4">
        <div class="bg-green-200 p-3 rounded-full">
          <i data-feather="dollar-sign" class="w-6 h-6 text-green-700"></i>
        </div>
        <div>
          <p class="text-gray-600 font-medium">Total Pendapatan</p>
          <p class="text-2xl md:text-2xl font-bold text-green-700">Rp <?= number_format($totalRevenue, 0, ',', '.'); ?></p>
        </div>
      </div>

      <!-- Total Users -->
      <div class="p-5 bg-purple-50 rounded-xl shadow hover:shadow-lg transition flex items-center gap-4">
        <div class="bg-purple-200 p-3 rounded-full">
          <i data-feather="users" class="w-6 h-6 text-purple-700"></i>
        </div>
        <div>
          <p class="text-gray-600 font-medium">Total Users</p>
          <p class="text-2xl md:text-2xl font-bold text-purple-700"><?= $totalUsers; ?></p>
        </div>
      </div>

    </div>

    <!-- Transaksi Terbaru -->
    <div class="bg-white rounded-xl shadow p-6 mb-8">
      <h2 class="text-xl font-semibold mb-4">Transaksi Terbaru</h2>
      <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse">
          <thead>
            <tr class="bg-gray-100 text-left text-gray-700">
              <th class="p-3 font-semibold border-b">No</th>
              <th class="p-3 font-semibold border-b">Tanggal</th>
              <th class="p-3 font-semibold border-b">Kasir</th>
              <th class="p-3 font-semibold border-b">Total</th>
              <th class="p-3 font-semibold border-b">Metode</th>
              <th class="p-3 font-semibold border-b">Aksi</th>
            </tr>
          </thead>
          <tbody class="text-gray-700">
            <?php foreach ($recentTransactions as $i => $trx): ?>
              <tr class="border-b hover:bg-gray-50 transition">
                <td class="p-3"><?= $i + 1; ?></td>
                <td class="p-3"><?= $trx['created_at']; ?></td>
                <td class="p-3"><?= $trx['kasir']; ?></td>
                <td class="p-3">Rp <?= number_format($trx['total'], 0, ',', '.'); ?></td>
                <td class="p-3"><?= ucfirst($trx['method']); ?></td>
                <td class="p-3">
                  <a href="transactions/view.php?id=<?= $trx['id']; ?>" class="text-blue-600 hover:underline">Detail</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <!-- Produk Terlaris -->
      <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-semibold mb-4">🔥 Produk Terlaris</h2>
        <div class="space-y-4">
          <?php
          // Cari total penjualan produk terlaris untuk progress bar
          $maxSold = !empty($bestSellingProducts) ? max(array_column($bestSellingProducts, 'total_sold')) : 1;
          ?>
          <?php foreach ($bestSellingProducts as $prod): ?>
            <?php
            $percentage = ($prod['total_sold'] / $maxSold) * 100;
            ?>
            <div>
              <div class="flex justify-between mb-1">
                <span class="font-medium text-gray-700"><?= $prod['name']; ?></span>
                <span class="text-sm text-gray-600"><?= $prod['total_sold']; ?> terjual</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-green-500 h-3 rounded-full" style="width: <?= $percentage; ?>%;"></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Produk Stok Rendah -->
      <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-semibold mb-4">⚠️ Produk Stok Rendah</h2>
        <div class="space-y-3">
          <?php if (count($lowStockProducts) > 0): ?>
            <?php foreach ($lowStockProducts as $prod): ?>
              <div class="flex justify-between items-center p-3 border rounded-lg hover:bg-red-50 transition">
                <span class="font-medium text-red-600"><?= $prod['name']; ?></span>
                <span class="text-sm text-red-700 font-semibold"><?= $prod['stock']; ?> tersisa</span>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="p-3 text-green-600 font-medium bg-green-50 rounded-lg text-center">Semua produk aman</div>
          <?php endif; ?>
        </div>
      </div>

    </div>

  </div>

  <script>
    feather.replace();
  </script>

</body>

</html>