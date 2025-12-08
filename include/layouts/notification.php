<?php
// Ambil notifikasi dari URL
$success = isset($_GET['success']) ? $_GET['success'] : null;
$error   = isset($_GET['error']) ? $_GET['error'] : null;

// Fungsi ubah kode menjadi pesan
function getMessage($key)
{
  $messages = [
    'updated'   => 'Data berhasil diperbarui!',
    'added'     => 'Data berhasil ditambahkan!',
    'deleted'   => 'Data berhasil dihapus!',
    'failed'    => 'Terjadi kesalahan, silakan coba lagi!',
    'notfound'  => 'Data tidak ditemukan!',
  ];

  return isset($messages[$key]) ? $messages[$key] : $key;
}
?>

<?php if ($success || $error): ?>
  <div id="notif"
    class="notif <?= $success ? 'notif-success' : 'notif-error' ?>">
    <?= $success ? getMessage($success) : getMessage($error) ?>
  </div>
<?php endif; ?>

<style>
  .notif {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 22px;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 500;
    color: #fff;
    z-index: 9999;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);

    /* Animasi muncul */
    opacity: 0;
    transform: translateX(50px);
    animation: slideIn 0.4s forwards, hide 0.4s 3s forwards;
  }

  .notif-success {
    background: linear-gradient(135deg, #28c76f, #20a55f);
  }

  .notif-error {
    background: linear-gradient(135deg, #ff4e4e, #d93636);
  }

  @keyframes slideIn {
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  @keyframes hide {
    to {
      opacity: 0;
      transform: translateX(50px);
    }
  }
</style>