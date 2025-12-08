<?php
include "../../../include/conn.php";
include "../../../include/base-url.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $name  = htmlspecialchars($_POST['name']);
  $price = (int) $_POST['price'];
  $stock = (int) $_POST['stock'];

  // ===== HANDLE UPLOAD GAMBAR =====
  $image = null;
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileExt, $allowedExts)) {
      $newFileName = uniqid() . '.' . $fileExt;
      $uploadDir = "../../../uploads/products/";
      if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
      }
      $destPath = $uploadDir . $newFileName;

      if (move_uploaded_file($fileTmpPath, $destPath)) {
        $image = "uploads/products/" . $newFileName; // path relatif untuk database
      }
    } else {
      header("Location: /toko-daffsha-kids/dashboard/products/create?error=invalid_image");
      exit;
    }
  }

  // ===== SIMPAN KE DATABASE =====
  $stmt = $conn->prepare("INSERT INTO products (name, price, stock, image) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("siis", $name, $price, $stock, $image);

  if ($stmt->execute()) {
    header("Location: /toko-daffsha-kids/dashboard/products?success=added");
    exit;
  } else {
    header("Location: /toko-daffsha-kids/dashboard/products/create?error=failed");
    exit;
  }
}
