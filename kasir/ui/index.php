<?php
require "../logic/index.php";
require "../../include/base-url.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Kasir - Toko Daffsha Kids</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    .fade-in {
      animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-6px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>

<body class="bg-gradient-to-br from-slate-100 to-slate-200 min-h-screen">

  <?php include "../../include/layouts/notification-kasir.php"; ?>

  <div class="max-w-7xl mx-auto pt-10 pb-16">

    <h1 class="text-4xl font-bold text-left text-slate-700 mb-6 tracking-tight">
      Toko Daffsha Kids
    </h1>

    <div class="grid grid-cols-3 gap-10">

      <!-- PRODUK LIST -->
      <div class="col-span-2 bg-white/70 backdrop-blur-lg p-8 rounded-3xl shadow-xl border border-slate-200">

        <!-- SEARCH BAR -->
        <div class="mb-6 relative max-w-md mx-auto">
          <!-- Icon Search -->
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
            </svg>
          </div>

          <!-- Input Field -->
          <input type="text" id="searchInput" placeholder="Cari produk..."
            class="w-full pl-10 pr-4 py-3 rounded-full border border-slate-300 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition duration-200 text-slate-700"
            oninput="filterProducts()">
        </div>


        <h2 class="text-2xl font-semibold text-slate-700 mb-6">Daftar Produk</h2>
        <div class="grid grid-cols-3 gap-6" id="productList">
          <?php while ($p = $products->fetch_assoc()): ?>
            <?php if ($p['stock'] <= 0) continue; ?>

            <div
              class="product-item h-full rounded-2xl p-5 bg-white border border-slate-200 shadow hover:shadow-xl transition cursor-pointer hover:scale-[1.02]"
              data-name="<?= strtolower($p['name']) ?>"
              onclick="addToCart(<?= $p['id'] ?>, '<?= $p['name'] ?>', <?= $p['price'] ?>)">

              <div class="w-full h-28 bg-slate-100 rounded-xl mb-4 flex items-center justify-center overflow-hidden">
                <?php
                // Path fisik di server
                $imgPathServer = __DIR__ . '/../../uploads/products/' . basename($p['image']);
                // Path URL untuk <img>
                $imgPathUrl = 'uploads/products/' . urlencode(basename($p['image']));
                ?>

                <?php if ($p['image'] && file_exists($imgPathServer)): ?>
                  <img src="<?= $imgPathUrl; ?>"
                    alt="<?= htmlspecialchars($p['name']); ?>"
                    class="w-full h-full object-cover rounded-xl">
                <?php else: ?>
                  <span class="text-3xl">👕</span>
                <?php endif; ?>
              </div>

              <p class="font-semibold text-slate-700 text-lg"><?= $p['name'] ?></p>
              <p class="text-sm text-slate-500">Rp <?= number_format($p['price']); ?></p>
              <p class="text-xs text-slate-400" id="stock-<?= $p['id'] ?>">Stok: <?= $p['stock'] ?></p>

            </div>
          <?php endwhile; ?>


        </div>
      </div>

      <!-- KERANJANG -->
      <div class="bg-white/80 backdrop-blur-lg p-8 rounded-3xl shadow-xl border border-slate-200">
        <h2 class="text-2xl font-semibold text-slate-700 mb-6">Keranjang</h2>

        <form action="kasir/transaction" method="POST">

          <table class="w-full text-sm mb-4" id="cartTable">
            <thead>
              <tr class="border-b text-slate-600 font-medium">
                <th class="text-left py-2">Barang</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Sub</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>

          <div class="mt-5">
            <label class="font-medium text-slate-600">Total</label>
            <input type="text" id="total" name="total" readonly
              class="w-full mt-1 p-3 border border-slate-200 rounded-xl bg-slate-100 font-semibold text-slate-700">
          </div>

          <div class="mt-4">
            <label class="font-medium text-slate-600">Pembayaran</label>
            <input type="number" id="payment" name="payment"
              oninput="hitungKembalian()"
              class="w-full mt-1 p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-300">
          </div>

          <div class="mt-4">
            <label class="font-medium text-slate-600">Kembalian</label>
            <input type="text" id="change" name="change" readonly
              class="w-full mt-1 p-3 border border-slate-200 rounded-xl bg-slate-100 font-semibold text-slate-700">
          </div>

          <button type="button"
            onclick="confirmTransaction()"
            class="mt-6 w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl shadow-lg transition font-semibold text-lg">
            Lanjutkan Transaksi
          </button>
        </form>
      </div>
    </div>
  </div>

  <script>
    let cart = [];
    let productStock = {}; // stok tersisa per produk

    function addToCart(id, name, price) {
      let stockEl = document.getElementById(`stock-${id}`);
      if (!(id in productStock)) {
        productStock[id] = parseInt(stockEl.textContent.replace('Stok: ', ''));
      }

      if (productStock[id] <= 0) {
        showCashierNotification(`⚠ Stok barang sudah habis!`, 'error');
        return;
      }

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

      productStock[id]--;
      stockEl.textContent = `Stok: ${productStock[id]}`;

      renderCart();
    }

    function updateQty(id, qty) {
      let item = cart.find(p => p.id === id);
      qty = parseInt(qty);
      let stockEl = document.getElementById(`stock-${id}`);

      if (qty < 1) {
        item.qty = 1;
      } else if (qty > item.qty + productStock[id]) {
        qty = item.qty + productStock[id];
        showCashierNotification(`⚠ Stok hanya ${qty}`, 'error');
      }

      let diff = qty - item.qty;
      productStock[id] -= diff;
      item.qty = qty;

      stockEl.textContent = `Stok: ${productStock[id]}`;
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
        <tr class="border-b py-2">
          <td class="py-2">${item.name}</td>
          <td class="text-center">
            <input type="number" min="1" value="${item.qty}"
              class="w-14 p-1 border border-slate-300 rounded-lg"
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

    function hitungKembalian() {
      let total = parseInt(document.getElementById("total").value) || 0;
      let pay = parseInt(document.getElementById("payment").value) || 0;
      let change = pay - total;
      document.getElementById("change").value = change >= 0 ? change : 0;
    }

    // ======================
    // SEARCHING FUNCTION
    // ======================
    function filterProducts() {
      const query = document.getElementById('searchInput').value.toLowerCase();
      const products = document.querySelectorAll('.product-item');

      products.forEach(p => {
        const name = p.getAttribute('data-name');
        if (name.includes(query)) {
          p.style.display = 'block';
        } else {
          p.style.display = 'none';
        }
      });
    }
  </script>

  <script>
    function confirmTransaction() {
      let total = parseInt(document.getElementById("total").value) || 0;
      let pay = parseInt(document.getElementById("payment").value) || 0;

      if (cart.length === 0) {
        Swal.fire({
          icon: 'warning',
          title: 'Keranjang kosong!',
          text: 'Tambahkan minimal satu produk.',
        });
        return;
      }

      if (pay < total) {
        Swal.fire({
          icon: 'error',
          title: 'Pembayaran tidak cukup!',
          text: 'Jumlah pembayaran harus lebih besar atau sama dengan total.',
        });
        return;
      }

      Swal.fire({
        title: "Lanjutkan transaksi?",
        text: "Pastikan data sudah benar sebelum melanjutkan.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#16a34a",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, lanjutkan!",
        cancelButtonText: "Batal"
      }).then((result) => {
        if (result.isConfirmed) {
          document.querySelector("form").submit();
        }
      });
    }
  </script>

</body>

</html>