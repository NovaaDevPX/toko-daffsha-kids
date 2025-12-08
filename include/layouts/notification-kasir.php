<?php
// Ambil notifikasi dari URL
$success = isset($_GET['success']) ? $_GET['success'] : null;
$error   = isset($_GET['error']) ? $_GET['error'] : null;

// Fungsi ubah kode menjadi pesan khusus kasir
function getCashierMessage($key)
{
  $messages = [
    'transaction_success' => '✅ Transaksi berhasil disimpan!',
    'transaction_failed'  => '❌ Gagal menyimpan transaksi!',
    'stock_low'           => '⚠ Stok tidak cukup!',
    'payment_error'       => '❌ Pembayaran kurang atau tidak valid!',
  ];

  return isset($messages[$key]) ? $messages[$key] : $key;
}
?>

<div id="notif-cashier-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<script>
  function showCashierNotification(message, type = 'success', duration = 3500) {
    const container = document.getElementById('notif-cashier-container');
    const notif = document.createElement('div');

    notif.className = `notif ${type === 'success' ? 'notif-success' : 'notif-error'}`;
    notif.innerHTML = message;

    // Style
    notif.style.cssText = `
        padding: 15px 22px;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 500;
        color: #fff;
        margin-top: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        opacity: 0;
        transform: translateX(50px);
        transition: all 0.4s ease;
    `;

    container.appendChild(notif);

    // Slide in
    setTimeout(() => {
      notif.style.opacity = '1';
      notif.style.transform = 'translateX(0)';
    }, 10);

    // Slide out after duration
    setTimeout(() => {
      notif.style.opacity = '0';
      notif.style.transform = 'translateX(50px)';
      setTimeout(() => container.removeChild(notif), 400);
    }, duration);
  }

  // Trigger otomatis jika ada success/error
  <?php if ($success): ?>
    showCashierNotification("<?= getCashierMessage($success); ?>", 'success');
  <?php elseif ($error): ?>
    showCashierNotification("<?= getCashierMessage($error); ?>", 'error');
  <?php endif; ?>
</script>

<style>
  .notif-success {
    background: linear-gradient(135deg, #28c76f, #20a55f);
  }

  .notif-error {
    background: linear-gradient(135deg, #ff4e4e, #d93636);
  }
</style>