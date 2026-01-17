<?php
session_start();

// Cek jika sudah login, redirect sesuai role
if (isset($_SESSION['user_id'])) {
  if ($_SESSION['role'] === 'admin') {
    header("Location: /toko-daffsha-kids/dashboard/");
    exit();
  } elseif ($_SESSION['role'] === 'kasir') {
    header("Location: /toko-daffsha-kids/kasir/");
    exit();
  }
}

$login_error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : null;
unset($_SESSION['login_error']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Login Kasir</title>
  <?php include __DIR__ . '/../../include/module.php'; ?>
  <style>
    .fade-in {
      animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-30px) scale(0.95);
      }

      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    .card-hover {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }
  </style>
</head>

<body class="bg-gradient-to-br from-blue-100 via-purple-100 to-pink-100 flex items-center justify-center min-h-screen">

  <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-2xl card-hover fade-in">

    <!-- Ilustrasi Kasir -->
    <div class="text-center mb-6">
      <div class="flex justify-center mb-4">
        <!-- Enhanced cashier illustration -->
        <div class="relative">
          <svg width="100" height="100" viewBox="0 0 24 24" fill="none" class="animate-pulse">
            <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5z" fill="#facc15" />
            <path d="M4 22c0-4.4 3.6-8 8-8s8 3.6 8 8" stroke="#4b5563" stroke-width="2" />
            <rect x="3" y="14" width="18" height="5" rx="1" fill="#9ca3af" />
            <rect x="6" y="16" width="12" height="2" rx="1" fill="#e5e7eb" />
          </svg>
          <div class="absolute -top-2 -right-2 bg-green-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold animate-bounce">
            ✓
          </div>
        </div>
      </div>
      <h2 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 mb-2">Login Kasir</h2>
      <p class="text-gray-500 text-sm">Silahkan masuk untuk melanjutkan ke sistem kasir</p>
    </div>

    <!-- Error Message -->
    <?php if ($login_error): ?>
      <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-xl border border-red-200 flex items-center gap-2">
        <i data-feather="alert-triangle" class="w-5 h-5"></i> <?php echo $login_error; ?>
      </div>
    <?php endif; ?>

    <!-- Form Login -->
    <form action="/toko-daffsha-kids/login/store" method="POST" class="space-y-5">

      <div>
        <label class="block text-gray-700 mb-2 font-semibold flex items-center gap-2">
          <i data-feather="mail" class="w-4 h-4 text-blue-500"></i> Email
        </label>
        <input
          type="email"
          name="email"
          required
          class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 focus:outline-none transition-all shadow-sm"
          placeholder="Masukkan email">
      </div>

      <div>
        <label class="block text-gray-700 mb-2 font-semibold flex items-center gap-2">
          <i data-feather="lock" class="w-4 h-4 text-blue-500"></i> Password
        </label>
        <input
          type="password"
          name="password"
          required
          class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-300 focus:border-blue-500 focus:outline-none transition-all shadow-sm"
          placeholder="Masukkan password">
      </div>

      <button
        type="submit"
        class="w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-3 rounded-xl transition-all transform hover:scale-105 shadow-xl flex items-center justify-center gap-2">
        <i data-feather="log-in" class="w-5 h-5"></i> Masuk
      </button>
    </form>

    <!-- Footer -->
    <p class="text-center text-gray-400 text-sm mt-6 flex items-center justify-center gap-1">
      <i data-feather="code" class="w-4 h-4"></i> Sistem Kasir • PHP Native
    </p>

  </div>

  <script>
    feather.replace();
  </script>

</body>

</html>