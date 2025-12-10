<?php
require "../../include/conn.php";

$search = isset($_GET['search']) ? strtolower($_GET['search']) : "";
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$limit = 9;
$offset = ($page - 1) * $limit;

// ============================
// Hitung total produk
// ============================
$countSql = "SELECT COUNT(*) AS total FROM products 
             WHERE is_deleted = 0 AND stock > 0 
             AND LOWER(name) LIKE '%$search%'";

$totalRows = $conn->query($countSql)->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// ============================
// Query data produk
// ============================
$sql = "SELECT * FROM products 
        WHERE is_deleted = 0 AND stock > 0 
        AND LOWER(name) LIKE '%$search%'
        ORDER BY name ASC
        LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

// ============================
// Output HTML (kartu produk)
// ============================
while ($p = $result->fetch_assoc()):
  $imgPathServer = __DIR__ . '/../../uploads/products/' . basename($p['image']);
  $imgPathUrl = 'uploads/products/' . urlencode(basename($p['image']));
?>
  <div
    class="product-item h-full rounded-2xl p-6 bg-white border-2 border-gray-200 shadow-lg product-hover cursor-pointer fade-in"
    onclick="addToCart(<?= $p['id'] ?>, '<?= $p['name'] ?>', <?= $p['price'] ?>)"
    data-name="<?= strtolower($p['name']) ?>">

    <div class="w-full h-32 bg-gray-100 rounded-xl mb-4 flex items-center justify-center overflow-hidden shadow-inner">

      <?php if ($p['image'] && file_exists($imgPathServer)): ?>
        <img src="<?= $imgPathUrl ?>"
          class="w-full h-full object-cover rounded-xl">
      <?php else: ?>
        <span class="text-4xl text-gray-400">📦</span>
      <?php endif; ?>

    </div>

    <p class="font-bold text-gray-800 text-lg mb-1"><?= $p['name'] ?></p>
    <p class="text-lg text-blue-600 font-semibold">Rp <?= number_format($p['price']) ?></p>

    <div class="flex justify-between items-center mt-2">
      <span class="text-sm text-gray-500">Stok tersedia</span>
      <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium shadow-sm"
        id="stock-<?= $p['id'] ?>">
        <?= $p['stock'] ?>
      </span>
    </div>

  </div>
<?php endwhile; ?>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
  <div class="col-span-full flex justify-center items-center mt-8 space-x-2" id="pagination">

    <!-- Previous Button -->
    <?php if ($page > 1): ?>
      <button
        onclick="loadProducts(<?= $page - 1 ?>)"
        class="pagination-btn px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
        <i data-feather="chevron-left" class="w-4 h-4 inline"></i> Previous
      </button>
    <?php else: ?>
      <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
        <i data-feather="chevron-left" class="w-4 h-4 inline"></i> Previous
      </span>
    <?php endif; ?>

    <!-- Page Numbers -->
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <button
        onclick="loadProducts(<?= $i ?>)"
        class="pagination-btn px-4 py-2 rounded-lg 
            <?= $i == $page ? 'pagination-active' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50'; ?>">
        <?= $i ?>
      </button>
    <?php endfor; ?>

    <!-- Next Button -->
    <?php if ($page < $totalPages): ?>
      <button
        onclick="loadProducts(<?= $page + 1 ?>)"
        class="pagination-btn px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
        Next <i data-feather="chevron-right" class="w-4 h-4 inline"></i>
      </button>
    <?php else: ?>
      <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
        Next <i data-feather="chevron-right" class="w-4 h-4 inline"></i>
      </span>
    <?php endif; ?>

  </div>
<?php endif; ?>