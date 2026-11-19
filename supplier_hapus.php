<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$id = isset($_GET['id']) && ctype_digit((string)$_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    $cek = $pdo->prepare("SELECT COUNT(*) FROM products WHERE supplier_id = ?");
    $cek->execute([$id]);
    if ($cek->fetchColumn() > 0) {
        $_SESSION['flash'] = 'Supplier tidak dapat dihapus karena masih digunakan oleh produk.';
        $_SESSION['flash_type'] = 'danger';
    } else {
        $stmt = $pdo->prepare("DELETE FROM suppliers WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['flash'] = 'Supplier berhasil dihapus.';
        $_SESSION['flash_type'] = 'success';
    }
} else {
    $_SESSION['flash'] = 'ID supplier tidak valid.';
    $_SESSION['flash_type'] = 'danger';
}
header('Location: supplier.php');
exit;
