<?php include "../../../include/auth-admin.php"; ?>

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

    /* Pagination Styles */
    .pagination-btn {
      transition: all 0.3s ease;
    }

    .pagination-btn:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .pagination-active {
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
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
          <input type="text" id="searchInput" onkeyup="liveSearch()"
            placeholder="Cari produk..."
            class="w-full sm:w-72 pl-10 pr-4 py-3 rounded-xl border border-gray-300 bg-white shadow-sm
           focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none
           hover:shadow-md placeholder-gray-400">
        </div>

        <!-- Filter -->
        <form method="GET" action="" class="flex items-center">
          <select name="filter" id="filterStock"
            onchange="this.form.submit()"
            class="w-full sm:w-56 p-3 pr-10 rounded-xl border border-gray-300 bg-white shadow-sm
               focus:ring-2 focus:ring-blue-500 outline-none focus:border-transparent transition-all
               hover:shadow-md appearance-none">
            <option value="all" <?php echo (!isset($_GET['filter']) || $_GET['filter'] == 'all') ? 'selected' : ''; ?>>Semua Produk</option>
            <option value="available" <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'available') ? 'selected' : ''; ?>>Stok Tersedia</option>
            <option value="out" <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'out') ? 'selected' : ''; ?>>Stok Habis</option>
            <option value="active" <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'active') ? 'selected' : ''; ?>>Status Aktif</option>
            <option value="inactive" <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'inactive') ? 'selected' : ''; ?>>Status Tidak Aktif</option>
          </select>

          <!-- Dropdown Icon -->
          <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
            </svg>
          </span>
        </form>

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
              <th class="p-4 font-semibold">Status</th>
              <th class="p-4 font-semibold">Aksi</th>
            </tr>
          </thead>

          <tbody class="text-gray-700" id="productTableBody">
            <?php
            // Ambil parameter dari URL
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

            // Pagination Setup
            $itemsPerPage = 10; // Jumlah produk per halaman
            $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($currentPage - 1) * $itemsPerPage;

            // Bangun WHERE clause berdasarkan search dan filter
            $whereClauses = [];
            if (!empty($search)) {
              $whereClauses[] = "name LIKE '%" . $conn->real_escape_string($search) . "%'";
            }
            if ($filter == 'available') {
              $whereClauses[] = "stock > 0";
            } elseif ($filter == 'out') {
              $whereClauses[] = "stock = 0";
            } elseif ($filter == 'active') {
              $whereClauses[] = "is_deleted = 0";
            } elseif ($filter == 'inactive') {
              $whereClauses[] = "is_deleted = 1";
            }
            $whereSQL = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";

            // Query untuk total produk (dengan filter/search)
            $totalQuery = "SELECT COUNT(*) as total FROM products $whereSQL";
            $totalResult = $conn->query($totalQuery);
            $totalProducts = $totalResult->fetch_assoc()['total'];
            $totalPages = ceil($totalProducts / $itemsPerPage);

            // Query untuk produk dengan LIMIT dan OFFSET (dengan filter/search)
            $result = $conn->query("SELECT * FROM products $whereSQL ORDER BY name ASC LIMIT $itemsPerPage OFFSET $offset");

            if ($result->num_rows > 0) {
              $no = $offset + 1; // Nomor urut berdasarkan halaman
              while ($row = $result->fetch_assoc()) { ?>
                <tr class="border-b hover:bg-gray-50 transition">

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
                    if ($stock == 0) $color = "bg-red-100 text-red-800";
                    elseif ($stock < 10) $color = "bg-yellow-100 text-yellow-800";
                    else $color = "bg-green-100 text-green-800";
                    ?>
                    <span class="px-3 py-1 rounded-full text-sm font-medium <?= $color ?>">
                      <?= $stock ?>
                    </span>
                  </td>

                  <!-- STATUS -->
                  <td class="p-4">
                    <?php if ($row['is_deleted'] == 0): ?>
                      <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        Aktif
                      </span>
                    <?php else: ?>
                      <span class="px-3 py-1 rounded-full text-sm font-medium bg-gray-200 text-gray-600">
                        Tidak Aktif
                      </span>
                    <?php endif; ?>
                  </td>

                  <!-- Aksi -->
                  <td class="p-4 relative">
                    <button onclick="toggleMenu(<?php echo $row['id']; ?>)"
                      class="p-2 rounded hover:bg-gray-200 transition">
                      <i data-feather="more-vertical"></i>
                    </button>

                    <div id="menu-<?php echo $row['id']; ?>"
                      class="hidden absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg border z-20 transition">

                      <?php if ($row['is_deleted'] == 0): ?>

                        <!-- Produk AKTIF -->
                        <a href="dashboard/products/edit/<?php echo $row['id']; ?>"
                          class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 text-blue-600">
                          <i data-feather="edit" class="w-4"></i> Edit
                        </a>

                        <a href="dashboard/products/edit-stock/<?php echo $row['id']; ?>"
                          class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 text-green-600">
                          <i data-feather="package" class="w-4"></i> Update Stock
                        </a>

                        <!-- Form NONAKTIFKAN -->
                        <form action="dashboard/products/deactivate" method="POST"
                          onsubmit="return confirm('Nonaktifkan produk ini?')">
                          <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                          <button type="submit"
                            class="w-full flex items-center gap-2 px-4 py-3 text-left hover:bg-gray-100 text-red-600">
                            <i data-feather="slash" class="w-4"></i> Nonaktifkan
                          </button>
                        </form>

                      <?php else: ?>

                        <!-- Form AKTIFKAN -->
                        <form action="dashboard/products/activate" method="POST"
                          onsubmit="return confirm('Aktifkan produk ini?')">
                          <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                          <button type="submit"
                            class="w-full flex items-center gap-2 px-4 py-3 text-left hover:bg-gray-100 text-green-600">
                            <i data-feather="check-circle" class="w-4"></i> Aktifkan
                          </button>
                        </form>

                      <?php endif; ?>
                    </div>
                  </td>

                </tr>
              <?php }
            } else { ?>
              <tr>
                <td colspan="7" class="text-center py-8 text-gray-500">
                  <?php if (!empty($search) || $filter != 'all'): ?>
                    Tidak ada produk yang cocok dengan pencarian/filter Anda.
                  <?php else: ?>
                    Belum ada produk. Tambahkan produk baru.
                  <?php endif; ?>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if ($totalPages > 1): ?>
        <div class="flex justify-center items-center mt-8 space-x-2">
          <!-- Previous Button -->
          <?php
          $prevParams = http_build_query(array_merge($_GET, ['page' => $currentPage - 1]));
          if ($currentPage > 1): ?>
            <a href="dashboard/products/?<?php echo $prevParams; ?>"
              class="pagination-btn px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
              <i data-feather="chevron-left" class="w-4 h-4 inline"></i> Previous
            </a>
          <?php else: ?>
            <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
              <i data-feather="chevron-left" class="w-4 h-4 inline"></i> Previous
            </span>
          <?php endif; ?>

          <!-- Page Numbers -->
          <?php for ($i = 1; $i <= $totalPages; $i++):
            $pageParams = http_build_query(array_merge($_GET, ['page' => $i])); ?>
            <a href="?<?php echo $pageParams; ?>"
              class="pagination-btn px-4 py-2 rounded-lg <?php echo $i == $currentPage ? 'pagination-active' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50'; ?>">
              <?php echo $i; ?>
            </a>
          <?php endfor; ?>

          <!-- Next Button -->
          <?php
          $nextParams = http_build_query(array_merge($_GET, ['page' => $currentPage + 1]));
          if ($currentPage < $totalPages): ?>
            <a href="dashboard/products/?<?php echo $nextParams; ?>"
              class="pagination-btn px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
              Next <i data-feather="chevron-right" class="w-4 h-4 inline"></i>
            </a>
          <?php else: ?>
            <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
              Next <i data-feather="chevron-right" class="w-4 h-4 inline"></i>
            </span>
          <?php endif; ?>
        </div>
      <?php endif; ?>
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

    function liveSearch() {
      let search = document.getElementById("searchInput").value;
      fetch("dashboard/products/logic/search-products.php?search=" + search)
        .then(res => res.text())
        .then(data => {
          document.getElementById("productTableBody").innerHTML = data;
          feather.replace();
        });

    }

    feather.replace();
  </script>

</body>

</html>