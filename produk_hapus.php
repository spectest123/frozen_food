<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$id = isset($_GET['id']) && ctype_digit((string)$_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['flash'] = 'Produk berhasil dihapus.';
    $_SESSION['flash_type'] = 'success';
} else {
    $_SESSION['flash'] = 'ID produk tidak valid.';
    $_SESSION['flash_type'] = 'danger';
}
header('Location: index.php');
exit;
