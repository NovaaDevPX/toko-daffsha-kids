<?php
include "../../../include/base-url.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Tambah User</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/feather-icons"></script>

  <?php include "../../../include/layouts/notification.php"; ?>
</head>

<body class="bg-gray-100">

  <?php include "../../../include/layouts/sidebar.php"; ?>
  <?php include "../../../include/layouts/header.php"; ?>

  <!-- MAIN CONTENT -->
  <div class="ml-64 pt-20 p-8">

    <!-- Header Title -->
    <div class="flex items-center justify-between mb-10">
      <h1 class="text-3xl font-bold text-gray-800">Tambah User</h1>

      <a href="dashboard/user-managements"
        class="flex items-center gap-2 bg-gray-200 text-gray-800 px-4 py-2 rounded-lg border hover:bg-gray-300 transition">
        <i data-feather="arrow-left" class="w-4"></i>
        Kembali
      </a>
    </div>

    <!-- FORM WRAPPER -->
    <div class="bg-white border rounded-xl shadow p-10 max-w-full">

      <form action="dashboard/user-managements/store" method="POST" class="space-y-6">

        <!-- Nama -->
        <div>
          <label class="block mb-2 text-gray-700 font-medium">Nama User</label>
          <input type="text" name="name" required
            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Masukkan nama user...">
        </div>

        <!-- Email -->
        <div>
          <label class="block mb-2 text-gray-700 font-medium">Email</label>
          <input type="email" name="email" required
            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Masukkan email user...">
        </div>

        <!-- Password -->
        <div>
          <label class="block mb-2 text-gray-700 font-medium">Password</label>
          <input type="password" name="password" required minlength="4"
            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Minimal 4 karakter">
        </div>

        <!-- Role -->
        <div>
          <label class="block mb-2 text-gray-700 font-medium">Role</label>
          <select name="role" required
            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <option value="admin">Admin</option>
            <option value="kasir" selected>Kasir</option>
          </select>
        </div>

        <!-- Submit -->
        <button type="submit"
          class="w-full py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition">
          Tambah User
        </button>

      </form>

    </div>

  </div>

  <script>
    feather.replace();
  </script>

</body>

</html>