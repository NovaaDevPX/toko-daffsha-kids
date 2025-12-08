<?php
require "../logic/index.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Kasir</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

  <?php if (isset($_GET['success'])): ?>
    <div class="bg-green-200 text-green-800 p-3 mb-4 rounded-lg">
      Transaksi berhasil disimpan!
    </div>
  <?php endif; ?>


  <div class="max-w-6xl mx-auto mt-10">
    <h1 class="text-3xl font-bold mb-6">💰 Sistem Kasir Sederhana</h1>

    <div class="grid grid-cols-3 gap-6">

      <!-- =======================================
         LEFT: LIST PRODUK
    ======================================== -->
      <div class="col-span-2 bg-white p-5 rounded-xl shadow">
        <h2 class="text-xl font-semibold mb-4">Daftar Produk</h2>

        <div class="grid grid-cols-3 gap-4">
          <?php while ($p = $products->fetch_assoc()): ?>
            <div class="border p-3 rounded-xl shadow cursor-pointer bg-gray-50 hover:bg-gray-100"
              onclick="addToCart(<?= $p['id'] ?>, '<?= $p['name'] ?>', <?= $p['price'] ?>)">
              <p class="font-semibold"><?= $p['name'] ?></p>
              <p class="text-sm text-gray-600">Harga: Rp <?= number_format($p['price']) ?></p>
              <p class="text-sm text-gray-600">Stok: <?= $p['stock'] ?></p>
            </div>
          <?php endwhile; ?>
        </div>
      </div>

      <!-- =======================================
         RIGHT: KERANJANG
    ======================================== -->
      <div class="bg-white p-5 rounded-xl shadow">
        <h2 class="text-xl font-semibold mb-4">Keranjang</h2>

        <form action="../logic/transaction.php" method="POST">
          <table class="w-full text-sm mb-3" id="cartTable">
            <thead>
              <tr class="border-b">
                <th class="text-left">Barang</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Sub</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>

          <div class="mt-4">
            <label class="font-semibold">Total</label>
            <input type="text" id="total" name="total" readonly
              class="w-full mt-1 p-2 border rounded bg-gray-100">
          </div>

          <div class="mt-2">
            <label class="font-semibold">Pembayaran</label>
            <input type="number" id="payment" name="payment"
              oninput="hitungKembalian()"
              class="w-full mt-1 p-2 border rounded">
          </div>

          <div class="mt-2">
            <label class="font-semibold">Kembalian</label>
            <input type="text" id="change" name="change" readonly
              class="w-full mt-1 p-2 border rounded bg-gray-100">
          </div>

          <button type="submit"
            class="mt-4 w-full bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700">
            Simpan Transaksi
          </button>

        </form>

      </div>
    </div>
  </div>


  <script>
    let cart = [];

    function addToCart(id, name, price) {
      let item = cart.find(p => p.id === id);

      if (item) {
        item.qty++;
      } else {
        cart.push({
          id,
          name,
          price,
          qty: 1
        });
      }

      renderCart();
    }

    function renderCart() {
      const tbody = document.querySelector("#cartTable tbody");
      tbody.innerHTML = "";
      let total = 0;

      cart.forEach(item => {
        let sub = item.price * item.qty;
        total += sub;

        tbody.innerHTML += `
      <tr>
        <td>${item.name}</td>
        <td>
          <input type="number" min="1" value="${item.qty}"
            class="w-12 p-1 border rounded"
            onchange="updateQty(${item.id}, this.value)">
        </td>
        <td>Rp ${item.price.toLocaleString()}</td>
        <td>Rp ${sub.toLocaleString()}</td>
      </tr>
      <input type="hidden" name="product_id[]" value="${item.id}">
      <input type="hidden" name="qty[]" value="${item.qty}">
      <input type="hidden" name="price[]" value="${item.price}">
      <input type="hidden" name="subtotal[]" value="${sub}">
    `;
      });

      document.getElementById("total").value = total;
      hitungKembalian();
    }

    function updateQty(id, qty) {
      let item = cart.find(p => p.id === id);
      item.qty = parseInt(qty);
      renderCart();
    }

    function hitungKembalian() {
      let total = parseInt(document.getElementById("total").value) || 0;
      let pay = parseInt(document.getElementById("payment").value) || 0;
      let change = pay - total;

      document.getElementById("change").value = change >= 0 ? change : 0;
    }
  </script>

</body>

</html>