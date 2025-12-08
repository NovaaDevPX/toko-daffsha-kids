<?php
include "../../../include/conn.php";
include "../../../include/base-url.php";

if (!isset($_GET['id'])) {
  die("ID produk tidak ditemukan.");
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM products WHERE id = $id");

if ($result->num_rows == 0) {
  die("Produk tidak ditemukan.");
}

$product = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Edit Produk</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/feather-icons"></script>
</head>

<body class="bg-gray-100">

  <?php include "../../../include/layouts/sidebar.php"; ?>
  <?php include "../../../include/layouts/header.php"; ?>

  <!-- Main Content -->
  <div class="ml-64 pt-20 p-8">

    <!-- Title -->
    <div class="flex items-center justify-between mb-10">
      <h1 class="text-3xl font-bold text-gray-800">Edit Produk</h1>

      <a href="dashboard/products"
        class="flex items-center gap-2 bg-gray-200 text-gray-800 px-4 py-2 rounded-lg border hover:bg-gray-300 transition">
        <i data-feather="arrow-left" class="w-4"></i>
        Kembali
      </a>
    </div>

    <!-- FORM -->
    <div class="bg-white border rounded-xl shadow p-10">

      <form id="editForm" action="dashboard/products/update/<?= $product['id']; ?>" method="POST"
        class="space-y-6">

        <!-- Nama -->
        <div>
          <label class="block mb-2 text-gray-700 font-medium">Nama Produk</label>
          <input type="text" name="name" required
            value="<?= $product['name']; ?>"
            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Harga -->
        <div>
          <label class="block mb-2 text-gray-700 font-medium">Harga</label>

          <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-600 font-medium">Rp</span>

            <input type="text" id="priceFormatted" required
              value="<?= number_format($product['price'], 0, ',', '.'); ?>"
              class="w-full pl-12 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
          </div>

          <input type="hidden" name="price" id="priceRaw">
        </div>

        <!-- Stok -->
        <div>
          <label class="block mb-2 text-gray-700 font-medium">Stok</label>
          <input type="number" name="stock" required min="0"
            value="<?= $product['stock']; ?>"
            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Tombol -->
        <button type="submit"
          class="w-full py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition">
          Simpan Perubahan
        </button>

      </form>

    </div>

  </div>

  <script>
    feather.replace();

    const priceInput = document.getElementById("priceFormatted");
    const priceRaw = document.getElementById("priceRaw");
    const form = document.getElementById("editForm");

    function formatRupiah(value) {
      return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    priceInput.addEventListener("input", () => {
      let number = priceInput.value.replace(/\D/g, "");
      if (number === "") {
        priceInput.value = "";
        return;
      }
      priceInput.value = formatRupiah(number);
    });

    form.addEventListener("submit", (e) => {
      let raw = priceInput.value.replace(/\./g, "");

      if (raw === "" || raw === "0") {
        e.preventDefault();
        alert("Harga tidak boleh kosong atau 0.");
        return;
      }

      priceRaw.value = raw;
    });
  </script>

</body>

</html>