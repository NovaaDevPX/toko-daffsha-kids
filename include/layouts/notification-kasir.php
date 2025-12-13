<?php
// Ambil notifikasi dari URL
$success = isset($_GET['success']) ? $_GET['success'] : null;
$error   = isset($_GET['error']) ? $_GET['error'] : null;

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Fungsi ubah kode menjadi pesan khusus kasir
function getCashierMessage($key)
{
  $messages = [
    'transaction_success' => 'Transaksi berhasil disimpan!',
    'transaction_failed'  => 'Gagal menyimpan transaksi!',
    'stock_low'           => 'Stok tidak cukup!',
    'payment_error'       => 'Pembayaran kurang atau tidak valid!',
  ];

  return isset($messages[$key]) ? $messages[$key] : $key;
}
?>

<div id="notif-cashier-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<script>
  function showCashierNotification(message, type = 'success', duration = 4000) {
    const container = document.getElementById('notif-cashier-container');
    const notif = document.createElement('div');

    // Icon berdasarkan type
    const icon = type === 'success' ?
      `<svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
         </svg>` :
      `<svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
         </svg>`;

    notif.className = `notif ${type === 'success' ? 'notif-success' : 'notif-error'} flex items-center p-4 rounded-2xl shadow-2xl text-white font-medium text-sm max-w-sm w-full`;
    notif.innerHTML = `${icon}<span class="flex-1">${message}</span><button onclick="closeNotif(this)" class="ml-3 text-white hover:text-gray-200 transition">✕</button>`;

    // Style awal (hidden)
    notif.style.opacity = '0';
    notif.style.transform = 'translateX(100%) scale(0.8) rotate(-5deg)';
    notif.style.transition = 'all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55)';

    container.appendChild(notif);

    // Bounce in
    setTimeout(() => {
      notif.style.opacity = '1';
      notif.style.transform = 'translateX(0) scale(1) rotate(0deg)';
    }, 10);

    // Auto close after duration
    const autoClose = setTimeout(() => {
      closeNotif(notif);
    }, duration);

    // Store timeout for manual close
    notif.autoClose = autoClose;
  }

  function closeNotif(element) {
    const notif = element.closest('.notif');
    clearTimeout(notif.autoClose);

    notif.style.opacity = '0';
    notif.style.transform = 'translateX(100%) scale(0.8) rotate(5deg)';
    setTimeout(() => {
      if (notif.parentNode) notif.parentNode.removeChild(notif);
    }, 500);
  }

  // Trigger otomatis jika ada success/error
  <?php if ($success): ?>
    showCashierNotification("<?= getCashierMessage($success); ?>", 'success');
  <?php elseif ($error): ?>
    showCashierNotification("<?= getCashierMessage($error); ?>", 'error');
  <?php endif; ?>
</script>

<?php if (isset($_SESSION['success']) && $_SESSION['success'] === 'struk'): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Transaksi Berhasil!',
      text: 'Struk berhasil diunduh.',
      timer: 2500,
      showConfirmButton: false
    });
  </script>
<?php unset($_SESSION['success']);
endif; ?>

<style>
  .notif-success {
    background: linear-gradient(135deg, #10b981, #059669);
    border-left: 4px solid #047857;
  }

  .notif-error {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    border-left: 4px solid #b91c1c;
  }

  /* Hover effect */
  .notif:hover {
    transform: translateX(-5px) !important;
  }

  /* Mobile responsive */
  @media (max-width: 640px) {
    #notif-cashier-container {
      right: 1rem;
      left: 1rem;
      top: 1rem;
    }

    .notif {
      max-width: none;
    }
  }
</style>