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

  <style>
    /* Custom gradient for modern look */
    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .card-hover {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
  </style>
</head>

<body class="bg-gray-100">

  <?php include "../../include/layouts/sidebar.php"; ?>
  <?php include "../../include/layouts/header.php"; ?>

  <div class="ml-64 pt-20 p-8">
    <!-- Header -->
    <div class="gradient-bg text-white p-6 rounded-xl shadow-xl mb-8">
      <h1 class="text-4xl font-bold mb-2 flex items-center gap-2">
        <span>Selamat Datang, Admin</span>
        <span class="animate-bounce">👋</span>
      </h1>
      <p class="text-lg opacity-90">Hari ini <?php echo $formattedTanggal; ?></p>
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
    <div class="bg-white p-6 rounded-xl shadow-lg card-hover mb-8">
      <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center">
        <i data-feather="list" class="mr-3 text-blue-500"></i>Transaksi Terbaru
      </h2>
      <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse">
          <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
            <tr class="text-left text-gray-700">
              <th class="p-4 font-semibold">No</th>
              <th class="p-4 font-semibold">Tanggal</th>
              <th class="p-4 font-semibold">Kasir</th>
              <th class="p-4 font-semibold">Total</th>
              <th class="p-4 font-semibold">Metode</th>
              <th class="p-4 font-semibold">Aksi</th>
            </tr>
          </thead>
          <tbody class="text-gray-700">
            <?php foreach ($recentTransactions as $i => $trx): ?>
              <tr class="border-b hover:bg-gray-50 transition">
                <td class="p-4"><?php echo $i + 1; ?></td>
                <td class="p-4"><?php echo $trx['created_at']; ?></td>
                <td class="p-4"><?php echo $trx['kasir']; ?></td>
                <td class="p-4">Rp <?php echo number_format($trx['total'], 0, ',', '.'); ?></td>
                <td class="p-4">
                  <span class="px-3 py-1 rounded-full text-sm font-medium 
                    <?php echo $trx['method'] == 'cash' ? 'bg-green-100 text-green-800' : ($trx['method'] == 'qris' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'); ?>">
                    <?php echo ucfirst($trx['method']); ?>
                  </span>
                </td>
                <td class="p-4">
                  <button
                    onclick="openDetailModal(<?php echo $trx['id']; ?>)"
                    class="text-blue-600 hover:underline font-medium transition">
                    Detail
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <!-- Produk Terlaris -->
      <div class="bg-white p-6 rounded-xl shadow-lg card-hover">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center">
          <i data-feather="star" class="mr-3 text-yellow-500"></i>Produk Terlaris
        </h2>
        <div class="space-y-4">
          <?php
          // Cari total penjualan produk terlaris untuk progress bar
          $maxSold = !empty($bestSellingProducts) ? max(array_column($bestSellingProducts, 'total_sold')) : 1;
          ?>
          <?php foreach ($bestSellingProducts as $prod): ?>
            <?php
            $percentage = ($prod['total_sold'] / $maxSold) * 100;
            ?>
            <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
              <div class="flex justify-between mb-2">
                <span class="font-medium text-gray-700"><?php echo $prod['name']; ?></span>
                <span class="text-sm text-gray-600"><?php echo $prod['total_sold']; ?> terjual</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-green-500 h-3 rounded-full transition-all duration-500" style="width: <?php echo $percentage; ?>%;"></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Produk Stok Rendah -->
      <div class="bg-white p-6 rounded-xl shadow-lg card-hover">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center">
          <i data-feather="alert-triangle" class="mr-3 text-red-500"></i>Produk Stok Rendah
        </h2>
        <div class="space-y-3">
          <?php if (count($lowStockProducts) > 0): ?>
            <?php foreach ($lowStockProducts as $prod): ?>
              <div class="flex justify-between items-center p-4 border rounded-lg hover:bg-red-50 transition">
                <span class="font-medium text-red-600"><?php echo $prod['name']; ?></span>
                <span class="text-sm text-red-700 font-semibold"><?php echo $prod['stock']; ?> tersisa</span>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="p-4 text-green-600 font-medium bg-green-50 rounded-lg text-center">Semua produk aman</div>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>

  <script>
    feather.replace();
  </script>

  <!-- ============================ -->
  <!-- MODAL DETAIL TRANSAKSI NEW UI -->
  <!-- ============================ -->
  <style>
    @keyframes fadeSlideUp {
      0% {
        opacity: 0;
        transform: translateY(25px) scale(0.97);
      }

      100% {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    .animate-modal {
      animation: fadeSlideUp 0.35s ease forwards;
    }

    /* Scrollbar Stylish */
    #itemList::-webkit-scrollbar {
      width: 6px;
    }

    #itemList::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 10px;
    }
  </style>

  <div id="detailModal"
    class="hidden fixed inset-0 bg-black bg-opacity-40 backdrop-blur-md flex items-center justify-center z-50 p-4">

    <div class="bg-white w-full max-w-xl p-7 rounded-3xl shadow-2xl animate-modal relative border border-gray-100">

      <!-- Close Button -->
      <button onclick="closeDetailModal()"
        class="absolute top-3 right-3 text-gray-500 hover:text-red-500 transition text-2xl font-bold">
        ✕
      </button>

      <h2 class="text-3xl  mb-4 font-bold text-blue-800 tracking-tight">
        Detail Transaksi
      </h2>

      <!-- Loading -->
      <div id="modalLoading" class="hidden">
        <div class="animate-pulse space-y-3">
          <div class="h-4 bg-gray-200 rounded"></div>
          <div class="h-4 bg-gray-200 rounded w-1/2"></div>
          <div class="h-4 bg-gray-200 rounded w-3/4"></div>
        </div>
      </div>

      <!-- Content -->
      <div id="modalContent" class="hidden">

        <!-- Transaction Info -->
        <div class="space-y-2 text-gray-700 mb-5 bg-gray-50 rounded-xl p-4 border border-gray-200">
          <p><strong>ID Transaksi:</strong> <span id="trxId"></span></p>
          <p><strong>Tanggal:</strong> <span id="trxDate"></span></p>
          <p><strong>Kasir:</strong> <span id="trxKasir"></span></p>
          <p><strong>Metode:</strong> <span id="trxMethod" class="uppercase font-semibold text-indigo-600"></span></p>
          <p class="text-lg font-bold text-gray-900">
            Total: <span id="trxTotal"></span>
          </p>
        </div>

        <!-- Product List -->
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Daftar Produk</h3>

        <div id="itemList" class="space-y-3 max-h-64 overflow-y-auto pr-2"></div>
      </div>

    </div>
  </div>

  <script>
    function openDetailModal(id) {
      const modal = document.getElementById("detailModal");
      const loading = document.getElementById("modalLoading");
      const content = document.getElementById("modalContent");

      modal.classList.remove("hidden");
      loading.classList.remove("hidden");
      content.classList.add("hidden");

      fetch("dashboard/logic/get-transaction.php?id=" + id)
        .then(res => res.json())
        .then(data => {
          loading.classList.add("hidden");
          content.classList.remove("hidden");

          const trx = data.transaction;

          document.getElementById("trxId").textContent = trx.id;
          document.getElementById("trxDate").textContent = trx.created_at;
          document.getElementById("trxKasir").textContent = trx.kasir;
          document.getElementById("trxMethod").textContent = trx.method;
          document.getElementById("trxTotal").textContent =
            "Rp " + parseInt(trx.total).toLocaleString("id-ID");

          let html = "";

          data.items.forEach(item => {
            html += `
            <div class="border p-4 rounded-2xl bg-gray-50 hover:bg-gray-100 transition shadow-sm hover:shadow-md">
              <div class="flex justify-between items-center">
                <div>
                  <p class="font-semibold text-gray-800 text-lg">${item.product_name}</p>
                  <p class="text-sm text-gray-600">
                    ${item.qty} × Rp ${parseInt(item.price).toLocaleString("id-ID")}
                  </p>
                </div>
                <div class="font-bold text-gray-900 text-lg">
                  Rp ${parseInt(item.subtotal).toLocaleString("id-ID")}
                </div>
              </div>
            </div>
          `;
          });

          document.getElementById("itemList").innerHTML = html;
        });
    }

    function closeDetailModal() {
      document.getElementById("detailModal").classList.add("hidden");
    }
  </script>


</body>

</html>