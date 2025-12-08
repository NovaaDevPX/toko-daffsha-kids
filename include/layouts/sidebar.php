<?php
// contoh cek role (opsional)
// if ($_SESSION['role'] !== 'admin') {
//   header("Location: ../kasir/index.php");
//   exit;
// }
?>

<!-- SIDEBAR -->
<aside class="fixed top-0 left-0 w-64 bg-white border-r border-gray-200 h-full">
  <div class="p-6 border-b border-gray-200">
    <h1 class="text-2xl font-semibold text-gray-800">Admin Panel</h1>
  </div>

  <nav class="mt-4 px-4">
    <ul class="space-y-1">

      <!-- Dashboard -->
      <li>
        <a href="dashboard"
          class="flex items-center gap-3 p-3 text-gray-700 rounded-xl hover:bg-blue-100 hover:text-blue-600 transition">
          <i data-feather="home" class="w-5"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <li>
        <a href="dashboard/products"
          class="flex items-center gap-3 p-3 text-gray-700 rounded-xl hover:bg-blue-100 hover:text-blue-600 transition">
          <i data-feather="package" class="w-5"></i>
          <span>Produk</span>
        </a>
      </li>

      <!-- Users -->
      <li>
        <a href="/admin/users/index.php"
          class="flex items-center gap-3 p-3 text-gray-700 rounded-xl hover:bg-blue-100 hover:text-blue-600 transition">
          <i data-feather="users" class="w-5"></i>
          <span>Pengguna</span>
        </a>
      </li>

      <!-- Laporan -->
      <li>
        <a href="/admin/laporan.php"
          class="flex items-center gap-3 p-3 text-gray-700 rounded-xl hover:bg-blue-100 hover:text-blue-600 transition">
          <i data-feather="bar-chart-2" class="w-5"></i>
          <span>Laporan</span>
        </a>
      </li>

      <!-- Logout -->
      <li class="">
        <a href="/logout.php"
          class="flex items-center gap-3 p-3 text-gray-600 rounded-xl hover:bg-red-100 hover:text-red-600 transition">
          <i data-feather="log-out" class="w-5"></i>
          <span>Logout</span>
        </a>
      </li>

    </ul>
  </nav>
</aside>