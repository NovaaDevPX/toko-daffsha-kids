<?php
include "../../../include/conn.php";

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$query = "
  SELECT * FROM products
  WHERE name LIKE '%$search%'
  ORDER BY name ASC
";

$result = $conn->query($query);

if ($result->num_rows > 0) {
  $no = 1;
  while ($row = $result->fetch_assoc()) {

    $stock = (int)$row['stock'];
    if ($stock == 0) $color = "bg-red-100 text-red-800";
    elseif ($stock < 10) $color = "bg-yellow-100 text-yellow-800";
    else $color = "bg-green-100 text-green-800";

    echo "
    <tr class='border-b hover:bg-gray-50 transition'>
      <td class='p-4'>$no</td>

      <td class='p-4'>
        <img src='{$row['image']}' class='w-16 h-16 object-cover rounded-lg border shadow-sm'>
      </td>

      <td class='p-4'>{$row['name']}</td>

      <td class='p-4'>Rp " . number_format($row['price'], 0, ',', '.') . "</td>

      <td class='p-4'>
        <span class='px-3 py-1 rounded-full text-sm font-medium $color'>
          $stock
        </span>
      </td>

      <td class='p-4'>
    ";

    echo $row['is_deleted'] == 0
      ? "<span class='px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800'>Aktif</span>"
      : "<span class='px-3 py-1 rounded-full text-sm font-medium bg-gray-200 text-gray-600'>Tidak Aktif</span>";

    echo "</td>";

    // AKSI DISAMAKAN DENGAN YANG ASLI
    echo "
      <td class='p-4 relative'>
        <button onclick='toggleMenu({$row['id']})' class='p-2 rounded hover:bg-gray-200'>
          <i data-feather=\"more-vertical\"></i>
        </button>
      </td>
    </tr>
    ";

    $no++;
  }
} else {
  echo "
  <tr>
    <td colspan='7' class='text-center py-6 text-gray-500'>
      Tidak ada produk ditemukan...
    </td>
  </tr>";
}
