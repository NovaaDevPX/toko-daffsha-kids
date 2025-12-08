<?php
include "../../../include/conn.php";
include "../../../include/base-url.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $name  = htmlspecialchars($_POST['name']);
  $price = (int) $_POST['price'];
  $stock = (int) $_POST['stock'];

  $stmt = $conn->prepare("INSERT INTO products (name, price, stock) VALUES (?, ?, ?)");
  $stmt->bind_param("sii", $name, $price, $stock);

  if ($stmt->execute()) {
    header("Location: /toko-daffsha-kids/dashboard/products?success=added");
    exit;
  } else {
    header("Location: /toko-daffsha-kids/dashboard/products/create?error=failed");
    exit;
  }
}
