<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Laporan Penjualan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/feather-icons"></script>
  <?php include "../../../include/base-url.php"; ?>
  <?php include "../logic/index.php"; ?>
  <?php include "../../../include/layouts/notification.php"; ?>
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

<body class="bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen">

  <?php include "../../../include/layouts/sidebar.php"; ?>
  <?php include "../../../include/layouts/header.php"; ?>

  <!-- Main Content -->
  <div class="ml-64 pt-20 p-8">
    <!-- Header with Gradient -->
    <div class="gradient-bg text-white p-6 rounded-xl shadow-xl mb-8">
      <h1 class="text-4xl font-bold mb-2">Laporan Penjualan</h1>
      <p class="text-lg opacity-90">Pantau performa bisnis Anda dengan data real-time.</p>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-6 rounded-xl shadow-lg mb-8 card-hover">
      <h2 class="text-2xl font-semibold mb-4 text-gray-800">Filter Laporan</h2>
      <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
          <input type="date" name="start_date" value="<?php echo $start_date; ?>" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
          <input type="date" name="end_date" value="<?php echo $end_date; ?>" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
          <select name="method" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            <option value="">Semua</option>
            <option value="cash" <?php echo $payment_method == 'cash' ? 'selected' : ''; ?>>Cash</option>
            <option value="qris" <?php echo $payment_method == 'qris' ? 'selected' : ''; ?>>QRIS</option>
            <option value="transfer" <?php echo $payment_method == 'transfer' ? 'selected' : ''; ?>>Transfer</option>
          </select>
        </div>
        <div class="flex items-end">
          <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-blue-600 hover:to-purple-700 transition transform hover:scale-105 shadow-lg">
            <i data-feather="search" class="inline mr-2"></i>Generate Laporan
          </button>
        </div>
      </form>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
      <div class="bg-white p-6 rounded-xl shadow-lg card-hover">
        <div class="flex items-center mb-4">
          <div class="bg-blue-100 p-3 rounded-full">
            <i data-feather="shopping-cart" class="text-blue-600"></i>
          </div>
          <div class="ml-4">
            <h3 class="text-lg font-semibold text-gray-800">Total Transaksi</h3>
            <p class="text-3xl font-bold text-blue-600"><?php echo $summary['total_transactions'] ?? 0; ?></p>
          </div>
        </div>
      </div>
      <div class="bg-white p-6 rounded-xl shadow-lg card-hover">
        <div class="flex items-center mb-4">
          <div class="bg-green-100 p-3 rounded-full">
            <i data-feather="dollar-sign" class="text-green-600"></i>
          </div>
          <div class="ml-4">
            <h3 class="text-lg font-semibold text-gray-800">Total Pendapatan</h3>
            <p class="text-3xl font-bold text-green-600">Rp <?php echo number_format($summary['total_revenue'] ?? 0); ?></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Produk Terlaris -->
    <div class="bg-white p-6 rounded-xl shadow-lg mb-8 card-hover">
      <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center">
        <i data-feather="star" class="mr-3 text-yellow-500"></i>Produk Terlaris
      </h2>
      <div class="overflow-x-auto">
        <table class="w-full table-auto">
          <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
            <tr>
              <th class="p-4 text-left font-semibold text-gray-700">Produk</th>
              <th class="p-4 text-left font-semibold text-gray-700">Qty Terjual</th>
              <th class="p-4 text-left font-semibold text-gray-700">Pendapatan</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($result_top_products)): ?>
              <tr class="hover:bg-gray-50 transition">
                <td class="p-4"><?php echo $row['product_name']; ?></td>
                <td class="p-4"><?php echo $row['total_qty_sold']; ?></td>
                <td class="p-4">Rp <?php echo number_format($row['total_revenue']); ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Daftar Transaksi -->
    <div class="bg-white p-6 rounded-xl shadow-lg mb-8 card-hover">
      <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center">
        <i data-feather="list" class="mr-3 text-blue-500"></i>Daftar Transaksi
      </h2>
      <div class="overflow-x-auto">
        <table class="w-full table-auto">
          <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
            <tr>
              <th class="p-4 text-left font-semibold text-gray-700">ID</th>
              <th class="p-4 text-left font-semibold text-gray-700">Tanggal</th>
              <th class="p-4 text-left font-semibold text-gray-700">Kasir</th>
              <th class="p-4 text-left font-semibold text-gray-700">Total</th>
              <th class="p-4 text-left font-semibold text-gray-700">Metode</th>
              <th class="p-4 text-left font-semibold text-gray-700">Item</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($result_transactions)): ?>
              <tr class="hover:bg-gray-50 transition">
                <td class="p-4"><?php echo $row['id']; ?></td>
                <td class="p-4"><?php echo $row['created_at']; ?></td>
                <td class="p-4"><?php echo $row['user_name']; ?></td>
                <td class="p-4">Rp <?php echo number_format($row['total']); ?></td>
                <td class="p-4">
                  <span class="px-3 py-1 rounded-full text-sm font-medium 
                                        <?php echo $row['method'] == 'cash' ? 'bg-green-100 text-green-800' : ($row['method'] == 'qris' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'); ?>">
                    <?php echo ucfirst($row['method']); ?>
                  </span>
                </td>
                <td class="p-4"><?php echo $row['items']; ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Stok Produk Rendah (Opsional) -->
    <div class="bg-white p-6 rounded-xl shadow-lg card-hover">
      <h2 class="text-2xl font-semibold mb-6 text-gray-800 flex items-center">
        <i data-feather="alert-triangle" class="mr-3 text-red-500"></i>Produk Stok Rendah (< 10)
          </h2>
          <div class="overflow-x-auto">
            <table class="w-full table-auto">
              <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                <tr>
                  <th class="p-4 text-left font-semibold text-gray-700">Produk</th>
                  <th class="p-4 text-left font-semibold text-gray-700">Stok</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = mysqli_fetch_assoc($result_low_stock)): ?>
                  <tr class="hover:bg-gray-50 transition">
                    <td class="p-4"><?php echo $row['name']; ?></td>
                    <td class="p-4">
                      <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        <?php echo $row['stock']; ?>
                      </span>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
    </div>
  </div>

  <!-- SCRIPT -->
  <script>
    feather.replace();
  </script>
</body>

</html>