<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencatatan Produk Frozen Food - Pak Budi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">Frozen Food Pak Budi</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="kategori.php">Kategori</a></li>
                <li class="nav-item"><a class="nav-link" href="supplier.php">Supplier</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mb-4">
<?php if (!empty($_SESSION['flash'])): ?>
    <div class="alert alert-<?php echo e($_SESSION['flash_type'] ?? 'info'); ?> alert-dismissible fade show">
        <?php echo e($_SESSION['flash']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash'], $_SESSION['flash_type']); ?>
<?php endif; ?>
