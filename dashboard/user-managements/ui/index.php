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

  <div class="ml-64 pt-20 p-8">

    <!-- Header with Gradient -->
    <div class="gradient-bg text-white p-6 rounded-xl shadow-xl mb-8">
      <h1 class="text-4xl font-bold mb-2">Manajemen User</h1>
      <p class="text-lg opacity-90">Kelola pengguna sistem dengan mudah.</p>
    </div>

    <div class="flex items-center justify-between mb-6">
      <div></div> <!-- Placeholder for alignment -->

      <a href="dashboard/user-managements/add"
        class="flex items-center gap-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-blue-600 hover:to-purple-700 transition transform hover:scale-105 shadow-lg">
        <i data-feather="plus" class="w-5"></i>
        Tambah User
      </a>
    </div>

    <?php include "../../../include/conn.php"; ?>

    <!-- TABLE CARD -->
    <div class="bg-white p-6 rounded-xl shadow-lg card-hover">
      <div class="overflow-x-auto">
        <table class="w-full border-collapse">
          <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
            <tr class="text-left text-gray-700">
              <th class="p-4 font-semibold">#</th>
              <th class="p-4 font-semibold">Username</th>
              <th class="p-4 font-semibold">Email</th>
              <th class="p-4 font-semibold">Role</th>
              <th class="p-4 font-semibold text-center">Aksi</th>
            </tr>
          </thead>

          <tbody class="text-gray-700">
            <?php
            $no = 1;
            $query = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");

            while ($row = mysqli_fetch_assoc($query)) :
            ?>
              <tr class="border-b hover:bg-gray-50 transition">
                <td class="p-4"><?php echo $no++; ?></td>

                <td class="p-4 font-medium">
                  <?php echo htmlspecialchars($row['name']); ?>
                </td>

                <td class="p-4">
                  <?php echo htmlspecialchars($row['email']); ?>
                </td>

                <td class="p-4">
                  <?php if ($row['role'] == 'admin') : ?>
                    <span class="px-3 py-1 text-sm font-semibold bg-blue-100 text-blue-800 rounded-full shadow-sm">
                      Admin
                    </span>
                  <?php else : ?>
                    <span class="px-3 py-1 text-sm font-semibold bg-green-100 text-green-800 rounded-full shadow-sm">
                      Kasir
                    </span>
                  <?php endif; ?>
                </td>

                <!-- Aksi -->
                <td class="p-4 relative text-center">
                  <!-- Tombol tiga titik -->
                  <button onclick="toggleMenu(<?php echo $row['id']; ?>)"
                    class="p-2 rounded hover:bg-gray-200 transition">
                    <i data-feather="more-vertical"></i>
                  </button>

                  <!-- Dropdown menu -->
                  <div id="menu-<?php echo $row['id']; ?>"
                    class="hidden absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-lg border z-20 transition">
                    <!-- Edit -->
                    <a href="dashboard/user-managements/edit/<?php echo $row['id']; ?>"
                      class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 text-blue-600 rounded-t-lg transition">
                      <i data-feather="edit" class="w-4"></i> Edit
                    </a>
                    <!-- Delete -->
                    <a href="dashboard/user-managements/delete/<?php echo $row['id']; ?>"
                      onclick="return confirm('Yakin ingin menghapus user ini?')"
                      class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 text-red-600 rounded-b-lg transition">
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