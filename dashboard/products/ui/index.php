<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Kelola Produk</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/feather-icons"></script>

  <?php include "../../../include/base-url.php"; ?>
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
  <?php include "../../../include/conn.php"; ?>

  <!-- Main Content -->
  <div class="ml-64 pt-20 p-8">

    <!-- Header with Gradient -->
    <div class="gradient-bg text-white p-6 rounded-xl shadow-xl mb-8">
      <h1 class="text-4xl font-bold mb-2">Kelola Produk</h1>
      <p class="text-lg opacity-90">Kelola inventaris produk Anda dengan mudah.</p>
    </div>

    <!-- Filter + Search + Add Button -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-8">

      <!-- Search + Filter -->
      <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">

        <!-- Search Box -->
        <div class="relative group">
          <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
            </svg>
          </span>
          <input type="text" id="searchInput"
            placeholder="Cari produk..."
            class="w-full sm:w-72 pl-10 pr-4 py-3 rounded-xl border border-gray-300 bg-white shadow-sm
               focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none
               hover:shadow-md placeholder-gray-400">
        </div>

        <!-- Filter -->
        <div class="relative group text-slate-600">
          <select id="filterStock"
            class="w-full sm:w-56 p-3 pr-10 rounded-xl border border-gray-300 bg-white shadow-sm
               focus:ring-2 focus:ring-blue-500 outline-none focus:border-transparent transition-all
               hover:shadow-md appearance-none">
            <option value="all">Semua Produk</option>
            <option value="available">Stok Tersedia</option>
            <option value="out">Stok Habis</option>
          </select>

          <!-- Dropdown Icon -->
          <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
            </svg>
          </span>
        </div>

      </div>

      <!-- Add Button -->
      <a href="dashboard/products/create"
        class="flex items-center gap-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-blue-600 hover:to-purple-700 transition transform hover:scale-105 shadow-lg">
        <i data-feather="plus" class="w-5"></i>
        Tambah Produk Baru
      </a>

    </div>


    <!-- Table -->
    <div class="bg-white p-6 rounded-xl shadow-lg card-hover">
      <div class="overflow-x-auto">
        <table class="w-full border-collapse">
          <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
            <tr class="text-left text-gray-700">
              <th class="p-4 font-semibold">#</th>
              <th class="p-4 font-semibold">Gambar</th>
              <th class="p-4 font-semibold">Nama Produk</th>
              <th class="p-4 font-semibold">Harga</th>
              <th class="p-4 font-semibold">Stok</th>
              <th class="p-4 font-semibold">Aksi</th>
            </tr>
          </thead>

          <tbody class="text-gray-700" id="productTableBody">
            <?php
            $no = 1;
            $result = $conn->query("SELECT * FROM products ORDER BY name ASC");

            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) { ?>
                <tr class="border-b hover:bg-gray-50 transition product-row"
                  data-name="<?php echo strtolower($row['name']); ?>"
                  data-stock="<?php echo $row['stock']; ?>">
                  <td class="p-4"><?php echo $no++; ?></td>
                  <!-- Gambar -->
                  <td class="p-4">
                    <?php if ($row['image'] && file_exists($_SERVER['DOCUMENT_ROOT'] . '/toko-daffsha-kids/' . $row['image'])): ?>
                      <img src="<?php echo $row['image']; ?>"
                        alt="<?php echo $row['name']; ?>"
                        class="w-16 h-16 object-cover rounded-lg border shadow-sm">
                    <?php else: ?>
                      <div class="w-16 h-16 flex items-center justify-center bg-gray-100 rounded-lg border text-gray-400 shadow-sm">
                        <span>No Image</span>
                      </div>
                    <?php endif; ?>
                  </td>
                  <td class="p-4"><?php echo $row['name']; ?></td>
                  <td class="p-4">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                  <td class="p-4">
                    <?php
                    $stock = (int)$row['stock'];

                    if ($stock == 0) {
                      $color = "bg-red-100 text-red-800";
                    } elseif ($stock < 10) {
                      $color = "bg-yellow-100 text-yellow-800";
                    } else {
                      $color = "bg-green-100 text-green-800";
                    }
                    ?>
                    <span class="px-3 py-1 rounded-full text-sm font-medium <?= $color ?>">
                      <?= $stock ?>
                    </span>
                  </td>

                  <!-- Aksi -->
                  <td class="p-4 relative">
                    <!-- Tombol tiga titik -->
                    <button onclick="toggleMenu(<?php echo $row['id']; ?>)"
                      class="p-2 rounded hover:bg-gray-200 transition">
                      <i data-feather="more-vertical"></i>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="menu-<?php echo $row['id']; ?>"
                      class="hidden absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg border z-20 transition">
                      <!-- Edit -->
                      <a href="dashboard/products/edit/<?php echo $row['id']; ?>"
                        class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 text-blue-600 transition">
                        <i data-feather="edit" class="w-4"></i> Edit
                      </a>
                      <!-- Update Stock -->
                      <a href="dashboard/products/edit-stock/<?php echo $row['id']; ?>"
                        class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 text-green-600 transition">
                        <i data-feather="package" class="w-4"></i> Update Stock
                      </a>
                      <!-- Delete -->
                      <a href="dashboard/products/delete/<?php echo $row['id']; ?>"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"
                        class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 text-red-600 transition">
                        <i data-feather='trash' class='w-4'></i> Hapus
                      </a>
                    </div>

                  </td>
                </tr>
              <?php }
            } else { ?>
              <tr class="product-row" data-name="" data-stock="0">
                <td colspan="6" class="text-center py-8 text-gray-500">
                  Belum ada produk. Tambahkan produk baru.
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- SCRIPT -->
  <script>
    // Toggle dropdown per baris
    function toggleMenu(id) {
      const menu = document.getElementById(`menu-${id}`);
      menu.classList.toggle("hidden");
    }

    // Klik di luar → close semua menu
    document.addEventListener("click", function(e) {
      document.querySelectorAll("[id^='menu-']").forEach(menu => {
        if (!menu.contains(e.target) && !menu.previousElementSibling.contains(e.target)) {
          menu.classList.add("hidden");
        }
      });
    });

    // Search and Filter Functions
    function filterProducts() {
      const searchQuery = document.getElementById('searchInput').value.toLowerCase();
      const filterValue = document.getElementById('filterStock').value;
      const rows = document.querySelectorAll('.product-row');

      rows.forEach(row => {
        const name = row.getAttribute('data-name') || '';
        const stock = parseInt(row.getAttribute('data-stock') || 0);
        let show = true;

        // Filter by search
        if (!name.includes(searchQuery)) {
          show = false;
        }

        // Filter by stock
        if (filterValue === 'available' && stock <= 0) {
          show = false;
        } else if (filterValue === 'out' && stock > 0) {
          show = false;
        }

        row.style.display = show ? '' : 'none';
      });
    }

    // Event listeners
    document.getElementById('searchInput').addEventListener('input', filterProducts);
    document.getElementById('filterStock').addEventListener('change', filterProducts);

    feather.replace();
  </script>

</body>

</html>