<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Kelola Produk</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/feather-icons"></script>

  <?php include "../../../include/base-url.php"; ?>
  <?php include "../../../include/layouts/notification.php"; ?>
</head>

<body class="bg-gray-100">

  <?php include "../../../include/layouts/sidebar.php"; ?>
  <?php include "../../../include/layouts/header.php"; ?>
  <?php include "../../../include/conn.php"; ?>

  <!-- Main Content -->
  <div class="ml-64 pt-20 p-8">

    <div class="flex items-center justify-between mb-6">
      <h1 class="text-3xl font-bold">Kelola Produk</h1>

      <a href="dashboard/products/create"
        class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
        <i data-feather="plus" class="w-4"></i>
        Tambah Produk
      </a>
    </div>

    <!-- Table -->
    <div class="bg-white p-6 rounded-xl shadow">
      <table class="w-full border-collapse">
        <thead>
          <tr class="bg-gray-100 text-left text-gray-700">
            <th class="p-3 font-semibold">#</th>
            <th class="p-3 font-semibold">Gambar</th>
            <th class="p-3 font-semibold">Nama Produk</th>
            <th class="p-3 font-semibold">Harga</th>
            <th class="p-3 font-semibold">Stok</th>
            <th class="p-3 font-semibold">Aksi</th>
          </tr>
        </thead>

        <tbody class="text-gray-700">
          <?php
          $no = 1;
          $result = $conn->query("SELECT * FROM products ORDER BY name ASC");

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { ?>
              <tr class="border-b hover:bg-gray-50 transition">
                <td class="p-3"><?= $no++; ?></td>
                <!-- Gambar -->
                <td class="p-3">
                  <?php if ($row['image'] && file_exists($_SERVER['DOCUMENT_ROOT'] . '/toko-daffsha-kids/' . $row['image'])): ?>
                    <img src="<?= $row['image']; ?>"
                      alt="<?= $row['name']; ?>"
                      class="w-16 h-16 object-cover rounded-lg border">
                  <?php else: ?>
                    <div class="w-16 h-16 flex items-center justify-center bg-gray-100 rounded-lg border text-gray-400">
                      <span>No Image</span>
                    </div>
                  <?php endif; ?>
                </td>
                <td class="p-3"><?= $row['name']; ?></td>
                <td class="p-3">Rp <?= number_format($row['price'], 0, ',', '.'); ?></td>
                <td class="p-3"><?= $row['stock']; ?></td>

                <!-- Aksi -->
                <td class="p-3 relative">

                  <!-- Tombol tiga titik -->
                  <button onclick="toggleMenu(<?= $row['id']; ?>)"
                    class="p-2 rounded hover:bg-gray-200 transition">
                    <i data-feather="more-vertical"></i>
                  </button>

                  <!-- Dropdown menu -->
                  <div id="menu-<?= $row['id']; ?>"
                    class="hidden absolute right-0 mt-2 w-32 bg-white shadow-lg rounded-lg border z-20">

                    <!-- Edit -->
                    <a href="dashboard/products/edit/<?= $row['id']; ?>" class="flex items-center gap-2 px-3 py-2 hover:bg-gray-100 text-blue-600">
                      <i data-feather="edit" class="w-4"></i> Edit
                    </a>

                    <!-- Delete -->
                    <a href="dashboard/products/delete/<?= $row['id']; ?>"
                      onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"
                      class="flex items-center gap-2 px-3 py-2 hover:bg-gray-100 text-red-600">
                      <i data-feather='trash' class='w-4'></i> Hapus
                    </a>
                  </div>

                </td>

              </tr>
            <?php }
          } else { ?>
            <tr>
              <td colspan="5" class="text-center py-4 text-gray-500">
                Belum ada produk. Tambahkan produk baru.
              </td>
            </tr>
          <?php } ?>
        </tbody>

      </table>
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