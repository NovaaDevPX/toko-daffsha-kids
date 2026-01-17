<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Update Stok Produk</title>
  <?php include __DIR__ . '/../../../include/module.php'; ?>
  <?php include "../../../include/layouts/notification.php"; ?>
  <?php include "../logic/update-stock.php"; ?>
  <style>
    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .card-hover {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    }

    .fade-in {
      animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .bounce-in {
      animation: bounceIn 0.8s ease-out;
    }

    @keyframes bounceIn {
      0% {
        opacity: 0;
        transform: scale(0.3);
      }

      50% {
        opacity: 1;
        transform: scale(1.05);
      }

      70% {
        transform: scale(0.9);
      }

      100% {
        opacity: 1;
        transform: scale(1);
      }
    }
  </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen">
  <?php include "../../../include/layouts/sidebar.php"; ?>
  <?php include "../../../include/layouts/header.php"; ?>

  <div class="ml-64 pt-20 p-8">
    <!-- Header -->
    <div class="gradient-bg text-white p-8 rounded-2xl shadow-2xl mb-8 fade-in">
      <h1 class="text-4xl font-bold mb-2 flex items-center gap-3">
        Update Stok Produk
      </h1>
      <p class="text-lg opacity-90">Kelola inventaris dengan mudah untuk produk: <strong><?php echo htmlspecialchars($product['name']); ?></strong></p>
    </div>

    <!-- Form -->
    <div class="bg-white p-8 rounded-2xl shadow-2xl card-hover max-w-full mx-auto bounce-in">
      <form method="POST" id="stockForm">
        <!-- Info Produk -->
        <div class="mb-8 bg-gradient-to-r from-blue-50 to-purple-50 p-6 rounded-xl border border-blue-200">
          <div class="flex items-center gap-4 mb-4">
            <?php if ($product['image'] && file_exists($_SERVER['DOCUMENT_ROOT'] . '/toko-daffsha-kids/' . $product['image'])): ?>
              <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="w-20 h-20 object-cover rounded-xl border-2 border-white shadow-lg">
            <?php else: ?>
              <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl border-2 border-white shadow-lg flex items-center justify-center text-2xl">📦</div>
            <?php endif; ?>
            <div>
              <h3 class="font-bold text-gray-800 text-xl"><?php echo htmlspecialchars($product['name']); ?></h3>
              <p class="text-sm text-gray-600">Harga: <span class="font-semibold text-blue-600">Rp <?php echo number_format($product['price']); ?></span></p>
              <p class="text-sm text-gray-600">Stok Saat Ini: <span class="font-semibold text-green-600" id="currentStock"><?php echo $product['stock']; ?></span></p>
            </div>
          </div>
        </div>

        <!-- Input Stok -->
        <div class="mb-6">
          <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
            <i data-feather="plus-circle" class="text-blue-500"></i> Jumlah Perubahan Stok
          </label>
          <input type="number" name="stock_change" id="stockChange" placeholder="Contoh: 10 (tambah) atau -5 (kurang)" required
            class="w-full outline-none p-4 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 transition-all text-center text-lg font-medium shadow-sm">
          <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
            <i data-feather="info" class="w-4 h-4"></i> Masukkan angka positif untuk menambah, negatif untuk mengurangi.
          </p>
        </div>

        <!-- Preview Stok Baru -->
        <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-blue-50 rounded-xl border border-green-200">
          <p class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
            <i data-feather="eye" class="text-green-500"></i> Preview Stok Baru
          </p>
          <p class="text-2xl font-bold text-green-600" id="newStock"><?php echo $product['stock']; ?></p>
          <p class="text-xs text-gray-500">Stok akan berubah saat Anda mengetik.</p>
        </div>

        <!-- Error Message -->
        <?php if (isset($error)): ?>
          <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-xl border border-red-200 flex items-center gap-2">
            <i data-feather="alert-triangle" class="w-5 h-5"></i> <?php echo $error; ?>
          </div>
        <?php endif; ?>

        <!-- Buttons -->
        <div class="flex gap-4">
          <button type="submit" class="flex-1 bg-gradient-to-r from-green-500 to-blue-600 text-white py-4 rounded-xl hover:from-green-600 hover:to-blue-700 transition-all transform hover:scale-105 shadow-xl font-bold text-lg flex items-center justify-center gap-2">
            <i data-feather="save" class="w-5 h-5"></i> Update Stok
          </button>
          <a href="dashboard/products" class="flex-1 bg-gradient-to-r from-gray-500 to-gray-600 text-white py-4 rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all text-center font-bold text-lg flex items-center justify-center gap-2">
            <i data-feather="x" class="w-5 h-5"></i> Batal
          </a>
        </div>
      </form>
    </div>
  </div>

  <script>
    feather.replace();

    // Real-time preview stok baru
    const stockChangeInput = document.getElementById('stockChange');
    const newStockDisplay = document.getElementById('newStock');
    const currentStock = <?php echo $product['stock']; ?>;

    stockChangeInput.addEventListener('input', function() {
      const change = parseInt(this.value) || 0;
      const newStock = currentStock + change;
      newStockDisplay.textContent = newStock >= 0 ? newStock : 'Tidak valid';
      newStockDisplay.className = newStock >= 0 ? 'text-2xl font-bold text-green-600' : 'text-2xl font-bold text-red-600';
    });

    // Animasi submit
    document.getElementById('stockForm').addEventListener('submit', function() {
      const button = this.querySelector('button[type="submit"]');
      button.innerHTML = '<i data-feather="loader" class="w-5 h-5 animate-spin"></i> Memproses...';
      button.disabled = true;
      feather.replace();
    });
  </script>
</body>

</html>