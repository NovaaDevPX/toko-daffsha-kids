<?php
include "../../../include/conn.php";
include "../../../include/base-url.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  $role = trim($_POST['role']);

  if ($name === "" || $email === "" || $password === "") {
    header("Location: /toko-daffsha-kids/dashboard/user-managements/add?error=empty");
    exit;
  }

  // Cek email duplikat
  $check = mysqli_query($conn, "SELECT email FROM users WHERE email='$email' LIMIT 1");
  if (mysqli_num_rows($check) > 0) {
    header("Location: /toko-daffsha-kids/dashboard/user-managements/add?error=email_exist");
    exit;
  }

  // Hash password menggunakan MD5
  $hashedPassword = md5($password);

  $query = "INSERT INTO users (name, email, password, role)
            VALUES ('$name', '$email', '$hashedPassword', '$role')";

  if (mysqli_query($conn, $query)) {
    header("Location: /toko-daffsha-kids/dashboard/user-managements?success=added");
    exit;
  } else {
    echo "Query Error: " . mysqli_error($conn);
    exit;
  }
}
