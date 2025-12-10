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

    <div class="flex items-center justify-between mb-6">
      <div></div> <!-- Placeholder for alignment -->

      <a href="dashboard/products/create"
        class="flex items-center gap-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-blue-600 hover:to-purple-700 transition transform hover:scale-105 shadow-lg">
        <i data-feather="plus" class="w-5"></i>
        Tambah Produk
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

          <tbody class="text-gray-700">
            <?php
            $no = 1;
            $result = $conn->query("SELECT * FROM products ORDER BY name ASC");

            if ($result->num_rows > 0) {
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
                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                      <?php echo $row['stock'] < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>">
                      <?php echo $row['stock']; ?>
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
                      class="hidden absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-lg border z-20 transition">
                      <!-- Edit -->
                      <a href="dashboard/products/edit/<?php echo $row['id']; ?>"
                        class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 text-blue-600 rounded-t-lg transition">
                        <i data-feather="edit" class="w-4"></i> Edit
                      </a>
                      <!-- Delete -->
                      <a href="dashboard/products/delete/<?php echo $row['id']; ?>"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"
                        class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 text-red-600 rounded-b-lg transition">
                        <i data-feather='trash' class='w-4'></i> Hapus
                      </a>
                    </div>
                  </td>
                </tr>
              <?php }
            } else { ?>
              <tr>
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

    feather.replace();
  </script>

</body>

</html>