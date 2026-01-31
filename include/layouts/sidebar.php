<?php include "sidebar-active-logic.php"; ?>

<!-- SIDEBAR -->
<aside class="fixed top-0 left-0 w-64 h-full bg-white border-r border-gray-200">
  <div class="p-6 border-b border-gray-200">
    <div class="mb-8 text-center">
      <img
        src="/toko-daffsha-kids/assets/img/logo.png"
        alt="Toko Daffsha Kids"
        class="justify-center object-contain d-flex" style="width: 440px;">
    </div>
  </div>

  <nav class="px-4 mt-4">
    <ul class="space-y-1">

      <!-- Dashboard -->
      <li>
        <a href="/toko-daffsha-kids/dashboard"
          class="flex items-center gap-3 p-3 rounded-xl transition <?php echo isActive('/toko-daffsha-kids/dashboard'); ?>">
          <i data-feather="home" class="w-5"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <!-- Produk -->
      <li>
        <a href="/toko-daffsha-kids/dashboard/products"
          class="flex items-center gap-3 p-3 rounded-xl transition <?php echo isActive('/toko-daffsha-kids/dashboard/products'); ?>">
          <i data-feather="package" class="w-5"></i>
          <span>Produk</span>
        </a>
      </li>


      <!-- Users -->
      <li>
        <a href="/toko-daffsha-kids/dashboard/user-managements"
          class="flex items-center gap-3 p-3 rounded-xl transition <?php echo isActive('/toko-daffsha-kids/dashboard/user-managements'); ?>">
          <i data-feather="users" class="w-5"></i>
          <span>Pengguna</span>
        </a>
      </li>

      <!-- Laporan -->
      <li>
        <a href="/toko-daffsha-kids/dashboard/reports"
          class="flex items-center gap-3 p-3 rounded-xl transition <?php echo isActive('/toko-daffsha-kids/dashboard/reports'); ?>">
          <i data-feather="bar-chart-2" class="w-5"></i>
          <span>Laporan</span>
        </a>
      </li>

      <!-- Logout -->
      <li>
        <a href="/toko-daffsha-kids/include/logout.php"
          class="flex items-center gap-3 p-3 text-gray-600 transition rounded-xl hover:bg-red-100 hover:text-red-600">
          <i data-feather="log-out" class="w-5"></i>
          <span>Logout</span>
        </a>
      </li>
    </ul>
  </nav>
</aside>