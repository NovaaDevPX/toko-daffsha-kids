<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/feather-icons"></script>

  <?php include "../../include/base-url.php"; ?>
</head>

<body class="bg-gray-100">

  <?php include "../../include/layouts/sidebar.php"; ?>
  <?php include "../../include/layouts/header.php"; ?>

  <!-- Main Content -->
  <div class="ml-64 pt-20 p-8">
    <h1 class="text-3xl font-bold mb-6">Selamat Datang, Admin 👋</h1>

    <div class="grid grid-cols-3 gap-6">

      <div class="p-5 bg-white rounded-xl shadow">
        <p class="text-gray-600">Total Produk</p>
        <p class="text-3xl font-bold">120</p>
      </div>

      <div class="p-5 bg-white rounded-xl shadow">
        <p class="text-gray-600">Transaksi Hari Ini</p>
        <p class="text-3xl font-bold">34</p>
      </div>

      <div class="p-5 bg-white rounded-xl shadow">
        <p class="text-gray-600">Pendapatan Hari Ini</p>
        <p class="text-3xl font-bold">Rp 1.250.000</p>
      </div>

    </div>
  </div>

  <script>
    feather.replace();
  </script>

</body>

</html>