<?php
// Ambil path sekarang dan hapus query string
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$currentPath = rtrim($currentPath, '/'); // hapus trailing slash

function isActive($path)
{
  global $currentPath;
  $path = '/' . trim($path, '/'); // pastikan diawali slash
  return $currentPath === $path ? 'bg-blue-100 text-blue-600' : 'text-gray-700';
}
