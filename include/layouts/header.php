<header class="fixed top-0 left-64 right-0 bg-white shadow-sm border-b border-gray-200 px-6 py-3 flex items-center justify-end z-30">
  <div class="flex items-center gap-4">

    <span class="text-gray-700 font-medium" style="padding: 10px 15px;">
      <?= htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?>
    </span>

  </div>
</header>