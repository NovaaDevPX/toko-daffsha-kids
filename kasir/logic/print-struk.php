<?php
require "../../include/conn.php";
require "../../vendor/autoload.php";

use Dompdf\Dompdf;

$transaction_id = $_GET['id'] ?? 0;
if (!$transaction_id) exit;

$trx = $conn->query("
  SELECT t.*, u.name AS kasir
  FROM transactions t
  LEFT JOIN users u ON u.id = t.user_id
  WHERE t.id = $transaction_id
")->fetch_assoc();

$items = $conn->query("
  SELECT p.name, ti.qty, ti.price, ti.subtotal
  FROM transaction_items ti
  JOIN products p ON p.id = ti.product_id
  WHERE ti.transaction_id = $transaction_id
");

ob_start();
?>
<!DOCTYPE html>
<html>

<head>
  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 11px;
      margin: 0;
      padding: 0;
    }

    .container {
      width: 100%;
    }

    .center {
      text-align: center;
    }

    .right {
      text-align: right;
    }

    .bold {
      font-weight: bold;
    }

    .line {
      border-top: 1px dashed #000;
      margin: 6px 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      padding: 2px 0;
      vertical-align: top;
    }

    th {
      border-bottom: 1px dashed #000;
      font-weight: bold;
    }

    .footer {
      margin-top: 6px;
    }
  </style>
</head>

<body>
  <div class="container">

    <!-- HEADER -->
    <div class="center bold">
      TOKO DAFFSHA KIDS
    </div>
    <div class="center">
      Jl. Contoh Alamat No. 12<br>
      Telp: 08xxxxxxxx
    </div>

    <div class="line"></div>

    <!-- INFO -->
    <table>
      <tr>
        <td>Tanggal</td>
        <td class="right"><?= date('d-m-Y H:i', strtotime($trx['created_at'])) ?></td>
      </tr>
      <tr>
        <td>Kasir</td>
        <td class="right"><?= $trx['kasir'] ?? 'Kasir' ?></td>
      </tr>
    </table>

    <div class="line"></div>

    <!-- ITEM -->
    <table>
      <tr>
        <th>Barang</th>
        <th class="right">Qty</th>
        <th class="right">Harga</th>
        <th class="right">Sub</th>
      </tr>

      <?php while ($i = $items->fetch_assoc()): ?>
        <tr>
          <td><?= $i['name'] ?></td>
          <td class="right"><?= $i['qty'] ?></td>
          <td class="right"><?= number_format($i['price']) ?></td>
          <td class="right"><?= number_format($i['subtotal']) ?></td>
        </tr>
      <?php endwhile; ?>
    </table>

    <div class="line"></div>

    <!-- TOTAL -->
    <table>
      <tr>
        <td>Total</td>
        <td class="right bold">Rp <?= number_format($trx['total']) ?></td>
      </tr>
      <tr>
        <td>Bayar</td>
        <td class="right">Rp <?= number_format($trx['payment']) ?></td>
      </tr>
      <tr>
        <td>Kembali</td>
        <td class="right">Rp <?= number_format($trx['change_money']) ?></td>
      </tr>
    </table>

    <div class="line"></div>

    <!-- FOOTER -->
    <div class="center footer">
      Terima kasih <br>
    </div>

  </div>
</body>

</html>
<?php
$html = ob_get_clean();

$pdf = new Dompdf();
$pdf->loadHtml($html);
$pdf->setPaper('A7', 'portrait');
$pdf->render();
$pdf->stream("struk-$transaction_id.pdf", ["Attachment" => true]);
exit;
