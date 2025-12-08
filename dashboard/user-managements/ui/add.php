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

  <div class="ml-64 pt-20 p-8">

    <div class="flex items-center justify-between mb-6">
      <h1 class="text-3xl font-bold">Tambah User </h1>

      <a href="dashboard/user-managements"
        class="flex items-center gap-2 bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition">
        <i data-feather="arrow-left" class="w-4"></i>
        Kembali
      </a>
    </div>

    <!-- CARD -->
    <div class="bg-white p-6 rounded-xl shadow max-w-xl">

      <form action="dashboard/user-managements/store" method="POST">

        <!-- Nama -->
        <div class="mb-4">
          <label class="block text-gray-700 font-medium mb-1">Nama User</label>
          <input type="text" name="name" required
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Email -->
        <div class="mb-4">
          <label class="block text-gray-700 font-medium mb-1">Email</label>
          <input type="email" name="email" required
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Password -->
        <div class="mb-4">
          <label class="block text-gray-700 font-medium mb-1">Password</label>
          <input type="password" name="password" required minlength="4"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Role -->
        <div class="mb-4">
          <label class="block text-gray-700 font-medium mb-1">Role</label>
          <select name="role" required
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <option value="admin">Admin</option>
            <option value="kasir" selected>Kasir</option>
          </select>
        </div>

        <!-- Submit -->
        <button type="submit"
          class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold shadow hover:bg-blue-700 transition">
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