  <?php
  require "../logic/index.php";
  require "../../include/auth-kasir.php";
  ?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <title>Kasir - Toko Daffsha Kids</title>
    <?php include __DIR__ . '/../../include/module.php'; ?>

    <style>
      .fade-in {
        animation: fadeIn 0.5s ease-out;
      }

      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(-10px) scale(0.95);
        }

        to {
          opacity: 1;
          transform: translateY(0) scale(1);
        }
      }

      .product-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
      }

      .product-hover:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
      }

      /* Floating Logout Button */
      .floating-logout {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 50;
      }

      /* Pagination Styles */
      .pagination-btn {
        transition: all 0.3s ease;
      }

      .pagination-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      }

      .pagination-active {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
      }
    </style>
  </head>

  <body class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">

    <?php include "../../include/layouts/notification-kasir.php"; ?>

    <div class="px-4 pt-10 pb-16 mx-auto max-w-7xl">

      <!-- Header -->
      <div class="mb-8 text-center">
        <img
          src="/toko-daffsha-kids/assets/img/logo.png"
          alt="Toko Daffsha Kids"
          class="object-contain mx-auto w-96 sm:w-48 md:w-56">
      </div>


      <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

        <!-- PRODUK LIST -->
        <div class="p-8 border border-gray-200 shadow-xl lg:col-span-2 bg-white/90 backdrop-blur-lg rounded-3xl fade-in">

          <!-- SEARCH BAR -->
          <div class="relative max-w-md mx-auto mb-6">
            <!-- Icon Search -->
            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
              <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
              </svg>
            </div>

            <!-- Input Field -->
            <input type="text" id="searchInput" placeholder="Cari produk..."
              class="w-full py-4 pl-12 pr-4 font-medium text-gray-700 transition duration-300 bg-white border-2 border-gray-300 rounded-full shadow-lg focus:outline-none focus:ring-4 focus:ring-blue-200 focus:border-blue-400"
              oninput="filterProducts()">
          </div>

          <h2 class="mb-6 text-3xl font-semibold text-gray-800">Daftar Produk</h2>
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" id="productList"></div>
          <div id="paginationWrapper" class="mt-4"></div>
        </div>

        <!-- KERANJANG -->
        <div class="p-8 border border-gray-200 shadow-xl bg-white/90 backdrop-blur-lg rounded-3xl fade-in">
          <h2 class="mb-6 text-3xl font-semibold text-gray-800">Keranjang</h2>

          <form id="transactionForm">
            <div class="mb-4 overflow-x-auto">
              <table class="w-full text-sm" id="cartTable">
                <thead>
                  <tr class="font-semibold text-gray-700 border-b-2 border-gray-300">
                    <th class="py-3 text-left">Barang</th>
                    <th class="py-3 text-center">Qty</th>
                    <th class="py-3 text-center">Harga</th>
                    <th class="py-3 text-center">Sub</th>
                    <th class="py-3 text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>

            <div class="mt-6 space-y-4">
              <div>
                <label class="font-semibold text-gray-700">Total</label>
                <input type="text" id="total" name="total_display" readonly
                  class="w-full p-4 mt-2 font-bold text-gray-800 border-2 border-gray-300 shadow-inner rounded-xl bg-gray-50">
                <input type="hidden" id="total_hidden" name="total">
              </div>

              <div>
                <label class="font-semibold text-gray-700">Pembayaran</label>
                <input type="text" id="payment" name="payment_display"
                  placeholder="Rp. 0"
                  oninput="formatPayment()"
                  class="w-full p-4 mt-2 transition border-2 border-blue-300 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-400">
                <input type="hidden" id="payment_hidden" name="payment">
              </div>

              <div>
                <label class="font-semibold text-gray-700">Kembalian</label>
                <input type="text" id="change" name="change_display" readonly
                  class="w-full p-4 mt-2 font-bold text-gray-800 border-2 border-gray-300 shadow-inner rounded-xl bg-gray-50">
                <input type="hidden" id="change_hidden" name="change">
              </div>
            </div>

            <button type="button"
              onclick="confirmTransaction()"
              class="w-full py-4 mt-8 text-lg font-bold text-white transition transform shadow-xl bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 rounded-xl hover:scale-105">
              Lanjutkan Transaksi
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Floating Action Menu -->
    <div id="fabWrapper" style="
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 9999;
">

      <!-- MENU -->
      <div id="fabMenu" style="
    display: none;
    flex-direction: column;
    align-items: flex-end;
    margin-bottom: 12px;
    gap: 10px;
  ">

        <a href="<?= $baseUrl ?>/kasir/ui/history.php" style="
      background: white;
      color: #1f2937;
      padding: 10px 16px;
      border-radius: 9999px;
      box-shadow: 0 10px 25px rgba(0,0,0,.15);
      font-weight: 600;
      text-decoration: none;
    ">
          History Transaksi
        </a>

        <a href="/toko-daffsha-kids/include/logout.php" style="
      background: #ef4444;
      color: white;
      padding: 10px 16px;
      border-radius: 9999px;
      box-shadow: 0 10px 25px rgba(0,0,0,.15);
      font-weight: 600;
      text-decoration: none;
    ">
          Logout
        </a>
      </div>

      <!-- BUTTON -->
      <button id="fabToggle" style="
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg,#3b82f6,#2563eb);
    color: white;
    border: none;
    font-size: 28px;
    cursor: pointer;
    box-shadow: 0 15px 30px rgba(0,0,0,.3);
  ">
        +
      </button>
    </div>


    <script>
      const btn = document.getElementById('fabToggle');
      const menu = document.getElementById('fabMenu');

      let open = false;

      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        open = !open;
        menu.style.display = open ? 'flex' : 'none';
        btn.textContent = open ? '×' : '+';
      });

      document.addEventListener('click', () => {
        open = false;
        menu.style.display = 'none';
        btn.textContent = '+';
      });
    </script>

    <script>
      let cart = [];
      let productStock = {}; // stok tersisa per produk

      function addToCart(id, name, price) {
        let stockEl = document.getElementById(`stock-${id}`);
        if (!(id in productStock)) {
          productStock[id] = parseInt(stockEl.textContent);
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
        stockEl.textContent = productStock[id];

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

        stockEl.textContent = productStock[id];
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
  <tr class="py-3 border-b border-gray-200 fade-in">
    <td class="py-3 font-medium text-gray-800">${item.name}</td>

    <td class="text-center">
      <input type="number" min="1" value="${item.qty}"
        class="w-16 p-2 text-center border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200"
        onchange="updateQty(${item.id}, this.value)">
    </td>

    <td class="font-semibold text-center text-gray-700">
      Rp ${item.price.toLocaleString()}
    </td>

    <td class="font-bold text-center text-blue-600">
      Rp ${sub.toLocaleString()}
    </td>

    <td class="text-center">
      <button
        type="button"
        onclick="removeFromCart(${item.id})"
        class="px-3 py-1 transition bg-red-500 rounded-lg shadow text-red hover:bg-red-600">
        ✕
      </button>
    </td>
  </tr>

  <input type="hidden" name="product_id[]" value="${item.id}">
  <input type="hidden" name="qty[]" value="${item.qty}">
  <input type="hidden" name="price[]" value="${item.price}">
  <input type="hidden" name="subtotal[]" value="${sub}">
`;
        });

        // Update total display dan hidden
        document.getElementById("total").value = "Rp. " + total.toLocaleString();
        document.getElementById("total_hidden").value = total;

        hitungKembalian();
      }

      function removeFromCart(id) {
        Swal.fire({
          title: 'Hapus barang?',
          text: 'Barang akan dihapus dari keranjang',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, hapus',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (!result.isConfirmed) return;

          const index = cart.findIndex(item => item.id === id);
          if (index === -1) return;

          const item = cart[index];
          productStock[id] += item.qty;

          const stockEl = document.getElementById(`stock-${id}`);
          if (stockEl) stockEl.textContent = productStock[id];

          cart.splice(index, 1);
          renderCart();
        });
      }


      function formatPayment() {
        let input = document.getElementById("payment");
        let hidden = document.getElementById("payment_hidden");
        let value = input.value.replace(/[^0-9]/g, ''); // Ambil hanya angka

        if (value) {
          input.value = "Rp. " + parseInt(value).toLocaleString();
          hidden.value = parseInt(value);
        } else {
          input.value = "";
          hidden.value = "";
        }

        hitungKembalian();
      }

      function hitungKembalian() {
        let total = parseInt(document.getElementById("total_hidden").value) || 0;
        let pay = parseInt(document.getElementById("payment_hidden").value) || 0;
        let change = pay - total;

        // Update change display dan hidden
        document.getElementById("change").value = "Rp. " + (change >= 0 ? change.toLocaleString() : "0");
        document.getElementById("change_hidden").value = change >= 0 ? change : 0;
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
        let total = parseInt(document.getElementById("total_hidden").value) || 0;
        let pay = parseInt(document.getElementById("payment_hidden").value) || 0;

        if (cart.length === 0) {
          Swal.fire('Keranjang kosong', 'Tambahkan produk terlebih dahulu', 'warning');
          return;
        }

        if (pay < total) {
          Swal.fire('Pembayaran kurang', 'Uang tidak mencukupi', 'error');
          return;
        }

        Swal.fire({
          title: "Lanjutkan transaksi?",
          icon: "question",
          showCancelButton: true,
          confirmButtonText: "Ya, lanjutkan"
        }).then((result) => {
          if (!result.isConfirmed) return;

          const form = document.getElementById("transactionForm");
          const formData = new FormData(form);

          fetch("kasir/transaction", {
              method: "POST",
              body: formData
            })
            .then(res => res.json())
            .then(res => {
              if (res.status === "success") {

                // 🔔 ALERT LANGSUNG
                Swal.fire({
                  icon: 'success',
                  title: 'Transaksi berhasil',
                  text: 'Struk sedang diunduh',
                  timer: 2000,
                  showConfirmButton: false
                });

                // 🧾 DOWNLOAD PDF TANPA PINDAH HALAMAN
                document.getElementById("printFrame").src =
                  `kasir/logic/print-struk.php?id=${res.transaction_id}`;

                // reset keranjang
                cart = [];
                renderCart();
              } else {
                Swal.fire('Gagal', 'Transaksi gagal', 'error');
              }
            });
        });
      }

      function loadProducts(page = 1) {
        const search = document.getElementById("searchInput").value;

        fetch(`kasir/logic/search-product-kasir.php?search=${search}&page=${page}`)
          .then(res => res.text())
          .then(html => {
            document.getElementById("productList").innerHTML = html;

            // Ambil pagination yang dirender
            const pagination = document.querySelector("#pagination");
            document.getElementById("paginationWrapper").innerHTML = "";

            if (pagination) {
              document.getElementById("paginationWrapper").appendChild(pagination);
            }

            feather.replace(); // Render ulang icon
          });
      }


      // Load awal
      loadProducts();

      // Live search
      document.getElementById("searchInput").addEventListener("input", () => {
        loadProducts();
      });
    </script>

    <iframe id="printFrame" style="display:none;"></iframe>

  </body>

  </html>