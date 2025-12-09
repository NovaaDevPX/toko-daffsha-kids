<?php
include "../../../include/base-url.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/feather-icons"></script>

  <?php include "../../../include/layouts/notification.php"; ?>
</head>

<body class="bg-gray-100">

  <?php include "../../../include/layouts/sidebar.php"; ?>
  <?php include "../../../include/layouts/header.php"; ?>

  <div class="ml-64 pt-20 p-8">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-3xl font-bold">Manajemen User </h1>

      <a href="dashboard/user-managements/add"
        class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
        <i data-feather="plus" class="w-4"></i>
        Tambah User
      </a>
    </div>

    <?php include "../../../include/conn.php"; ?>

    <!-- TABLE CARD -->
    <div class="bg-white p-6 rounded-xl shadow">

      <table class="w-full border-collapse">
        <thead>
          <tr class="bg-gray-100 text-left text-gray-700">
            <th class="p-3 font-semibold">#</th>
            <th class="p-3 font-semibold">Username</th>
            <th class="p-3 font-semibold">Email</th>
            <th class="p-3 font-semibold">Role</th>
            <th class="p-3 font-semibold text-center">Aksi</th>
          </tr>
        </thead>

        <tbody class="text-gray-700">
          <?php
          $no = 1;
          $query = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");

          while ($row = mysqli_fetch_assoc($query)) :
          ?>
            <tr class="border-b hover:bg-gray-50 transition">
              <td class="p-3"><?= $no++; ?></td>

              <td class="p-3 font-medium">
                <?= htmlspecialchars($row['name']); ?>
              </td>

              <td class="p-3">
                <?= htmlspecialchars($row['email']); ?>
              </td>

              <td class="p-3">
                <?php if ($row['role'] == 'admin') : ?>
                  <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">
                    Admin
                  </span>
                <?php else : ?>
                  <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                    Kasir
                  </span>
                <?php endif; ?>
              </td>

              <!-- Aksi -->
              <td class="p-3 relative text-center">

                <!-- Tombol tiga titik -->
                <button onclick="toggleMenu(<?= $row['id']; ?>)"
                  class="p-2 rounded hover:bg-gray-200 transition">
                  <i data-feather="more-vertical"></i>
                </button>

                <!-- Dropdown menu -->
                <div id="menu-<?= $row['id']; ?>"
                  class="hidden absolute right-0 mt-2 w-32 bg-white shadow-lg rounded-lg border z-20">

                  <!-- Edit -->
                  <a href="dashboard/user-managements/edit/<?= $row['id']; ?>"
                    class="flex items-center gap-2 px-3 py-2 hover:bg-gray-100 text-blue-600">
                    <i data-feather="edit" class="w-4"></i> Edit
                  </a>

                  <!-- Delete -->
                  <a href="dashboard/user-managements/delete/<?= $row['id']; ?>"
                    onclick="return confirm('Yakin ingin menghapus user ini?')"
                    class="flex items-center gap-2 px-3 py-2 hover:bg-gray-100 text-red-600">
                    <i data-feather="trash" class="w-4"></i> Hapus
                  </a>

                </div>

              </td>
            </tr>

          <?php endwhile; ?>
        </tbody>
      </table>

    </div>

  </div>

  <script>
    function toggleMenu(id) {
      const menu = document.getElementById(`menu-${id}`);
      menu.classList.toggle("hidden");
    }

    // klik luar menutup dropdown
    document.addEventListener("click", (e) => {
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