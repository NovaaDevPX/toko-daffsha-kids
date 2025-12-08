<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Login Kasir</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">

    <!-- Ilustrasi Kasir -->
    <div class="text-center mb-5">
      <div class="flex justify-center mb-3">
        <!-- Simple cashier illustration -->
        <svg width="90" height="90" viewBox="0 0 24 24" fill="none">
          <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5z" fill="#facc15" />
          <path d="M4 22c0-4.4 3.6-8 8-8s8 3.6 8 8" stroke="#4b5563" stroke-width="2" />
          <rect x="3" y="14" width="18" height="5" rx="1" fill="#9ca3af" />
          <rect x="6" y="16" width="12" height="2" rx="1" fill="#e5e7eb" />
        </svg>
      </div>
      <h2 class="text-2xl font-bold text-gray-700">Login Kasir</h2>
      <p class="text-gray-500 text-sm mt-1">Silahkan masuk untuk melanjutkan ke sistem kasir</p>
    </div>

    <!-- Form Login -->
    <form action="proses-login.php" method="POST" class="space-y-4">

      <div>
        <label class="block text-gray-600 mb-1">Username</label>
        <input
          type="text"
          name="username"
          required
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
          placeholder="Masukkan username">
      </div>

      <div>
        <label class="block text-gray-600 mb-1">Password</label>
        <input
          type="password"
          name="password"
          required
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
          placeholder="Masukkan password">
      </div>

      <button
        type="submit"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
        Masuk
      </button>

    </form>

    <!-- Footer -->
    <p class="text-center text-gray-400 text-sm mt-5">
      Sistem Kasir • PHP Native
    </p>

  </div>

</body>

</html>