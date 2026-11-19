<?php
require_once 'config.php';
require_once 'header.php';

$search = isset($_GET['search']) && is_scalar($_GET['search']) ? trim((string)$_GET['search']) : '';

$sql = "SELECT c.*, COUNT(p.id) AS product_count
        FROM categories c
        LEFT JOIN products p ON p.category_id = c.id";
$params = [];
if ($search !== '') {
    $sql .= " WHERE c.category_name LIKE ? ESCAPE '\\\\'";
    $params[] = like_param($search);
}
$sql .= " GROUP BY c.id ORDER BY c.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$list = $stmt->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Daftar Kategori</h3>
    <a href="kategori_form.php" class="btn btn-success">Tambah Kategori</a>
</div>
<form method="get" class="row g-2 mb-3">
    <div class="col-md-9">
        <label for="search" class="visually-hidden">Cari kategori</label>
        <input type="search" id="search" name="search" class="form-control" placeholder="Cari nama kategori..." value="<?php echo e($search); ?>">
    </div>
    <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-fill">Cari</button>
        <a href="kategori.php" class="btn btn-secondary flex-fill">Reset</a>
    </div>
</form>
<?php if ($search !== ''): ?>
    <div class="alert alert-info py-2">Hasil pencarian untuk <strong><?php echo e($search); ?></strong> &mdash; <?php echo count($list); ?> kategori ditemukan.</div>
<?php endif; ?>
<div class="table-responsive">
<table class="table table-bordered table-striped">
    <thead class="table-light">
        <tr><th>No</th><th>Nama Kategori</th><th>Jumlah Produk</th><th>Aksi</th></tr>
    </thead>
    <tbody>
    <?php if (count($list) === 0): ?>
        <tr><td colspan="4" class="text-center"><?php echo $search !== '' ? 'Tidak ada kategori yang cocok dengan pencarian ini.' : 'Belum ada data kategori.'; ?></td></tr>
    <?php else: $no=1; foreach ($list as $row): ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo e($row['category_name']); ?></td>
            <td><?php echo e($row['product_count']); ?></td>
            <td>
                <a href="kategori_form.php?id=<?php echo e($row['id']); ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="kategori_hapus.php?id=<?php echo e($row['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus kategori ini?');">Hapus</a>
            </td>
        </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>
</div>
<?php require_once 'footer.php'; ?>
