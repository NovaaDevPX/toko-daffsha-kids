<?php
require "../../include/auth-kasir.php";
require_once "../../include/conn.php";

date_default_timezone_set('Asia/Jakarta');

/* ===============================
   DATA USER LOGIN
================================ */
$user_id = $_SESSION['user_id'];

/* ===============================
   PROSES CANCEL TRANSAKSI
================================ */
if (isset($_POST['cancel_id'])) {
  $transaction_id = (int) $_POST['cancel_id'];

  $trx = mysqli_query($conn, "
    SELECT status 
    FROM transactions 
    WHERE id = $transaction_id 
    AND user_id = $user_id 
    LIMIT 1
  ");

  if (mysqli_num_rows($trx) === 1) {
    $data = mysqli_fetch_assoc($trx);

    if ($data['status'] === 'completed') {
      mysqli_query($conn, "
        UPDATE transactions 
        SET status = 'cancelled' 
        WHERE id = $transaction_id
      ");
    }
  }

  header("Location: history.php");
  exit;
}

/* ===============================
   AMBIL 5 TRANSAKSI TERBARU
================================ */
$transactions = mysqli_query($conn, "
  SELECT * 
  FROM transactions 
  WHERE user_id = $user_id 
  ORDER BY created_at DESC 
  LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>History Transaksi</title>

  <?php include __DIR__ . '/../../include/module.php'; ?>

  <style>
    .gradient-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .card-shadow {
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .status-badge {
      transition: all 0.3s ease;
    }

    .status-badge:hover {
      transform: scale(1.05);
    }
  </style>
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen p-4 md:p-6">

  <div class="max-w-6xl mx-auto">

    <!-- HEADER -->
    <div class="gradient-bg text-white rounded-2xl p-6 mb-8 card-shadow">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl md:text-4xl font-bold mb-2 flex items-center">
            <i data-feather="file-text" class="mr-3"></i>
            History Transaksi
          </h1>
          <p class="text-blue-100">
            Kelola dan pantau riwayat transaksi Anda
          </p>
        </div>
        <div class="hidden md:block">
          <i data-feather="clock" class="w-16 h-16 text-blue-200"></i>
        </div>
      </div>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-2xl card-shadow overflow-hidden">

      <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
          <i data-feather="list" class="mr-2"></i>
          Transaksi Terbaru
        </h2>
        <p class="text-gray-600 mt-1">
          Menampilkan 5 transaksi terakhir
        </p>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-100">
            <tr>
              <th class="p-4 text-left font-semibold text-gray-700">ID</th>
              <th class="p-4 text-left font-semibold text-gray-700">Tanggal</th>
              <th class="p-4 text-left font-semibold text-gray-700">Total</th>
              <th class="p-4 text-left font-semibold text-gray-700">Metode</th>
              <th class="p-4 text-left font-semibold text-gray-700">Status</th>
              <th class="p-4 text-center font-semibold text-gray-700">Aksi</th>
            </tr>
          </thead>

          <tbody>
            <?php if (mysqli_num_rows($transactions) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($transactions)): ?>
                <?php $created_at = strtotime($row['created_at']); ?>
                <tr class="hover:bg-blue-50 transition">

                  <td class="p-4 border-b">
                    <span class="font-mono text-blue-600">#<?= $row['id'] ?></span>
                  </td>

                  <td class="p-4 border-b">
                    <div>
                      <div class="font-medium"><?= date('d M Y', $created_at) ?></div>
                      <div class="text-xs text-gray-500"><?= date('H:i:s', $created_at) ?> WIB</div>
                    </div>
                  </td>

                  <td class="p-4 border-b font-bold text-green-600">
                    Rp <?= number_format($row['total']) ?>
                  </td>

                  <td class="p-4 border-b">
                    <span class="px-3 py-1 bg-gray-100 rounded-full text-xs uppercase">
                      <?= $row['method'] ?>
                    </span>
                  </td>

                  <td class="p-4 border-b">
                    <?php if ($row['status'] === 'completed'): ?>
                      <span class="status-badge inline-flex items-center px-3 py-1 rounded-full text-xs bg-green-100 text-green-800">
                        <i data-feather="check-circle" class="w-3 h-3 mr-1"></i>
                        Completed
                      </span>
                    <?php else: ?>
                      <span class="status-badge inline-flex items-center px-3 py-1 rounded-full text-xs bg-red-100 text-red-800">
                        <i data-feather="x-circle" class="w-3 h-3 mr-1"></i>
                        Cancelled
                      </span>
                    <?php endif; ?>
                  </td>

                  <td class="p-4 border-b text-center">
                    <?php if ($row['status'] === 'completed'): ?>
                      <form method="POST" onsubmit="return confirm('Batalkan transaksi ini?')">
                        <input type="hidden" name="cancel_id" value="<?= $row['id'] ?>">
                        <button class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-lg text-xs">
                          <i data-feather="x" class="w-4 h-4 mr-1"></i>
                          Batalkan
                        </button>
                      </form>
                    <?php else: ?>
                      <span class="text-xs text-gray-400 italic">Tidak ada aksi</span>
                    <?php endif; ?>
                  </td>

                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="p-12 text-center">
                  <i data-feather="inbox" class="w-16 h-16 mx-auto text-gray-300 mb-4"></i>
                  <p class="text-gray-500">Belum ada transaksi</p>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <div class="p-4 bg-gray-50 text-center text-sm text-gray-500">
        Maksimal 5 transaksi terbaru
      </div>

    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      feather.replace();
    });
  </script>

</body>

</html>