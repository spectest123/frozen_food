<?php
require_once 'config.php';
require_once 'header.php';

$search = isset($_GET['search']) && is_scalar($_GET['search']) ? trim((string)$_GET['search']) : '';

$sql = "SELECT s.*, COUNT(p.id) AS product_count
        FROM suppliers s
        LEFT JOIN products p ON p.supplier_id = s.id";
$params = [];
if ($search !== '') {
    $like = like_param($search);
    $sql .= " WHERE (s.supplier_name LIKE ? ESCAPE '\\\\' OR s.contact LIKE ? ESCAPE '\\\\')";
    $params[] = $like;
    $params[] = $like;
}
$sql .= " GROUP BY s.id ORDER BY s.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$list = $stmt->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Daftar Supplier</h3>
    <a href="supplier_form.php" class="btn btn-success">Tambah Supplier</a>
</div>
<form method="get" class="row g-2 mb-3">
    <div class="col-md-9">
        <label for="search" class="visually-hidden">Cari supplier</label>
        <input type="search" id="search" name="search" class="form-control" placeholder="Cari nama atau kontak supplier..." value="<?php echo e($search); ?>">
    </div>
    <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-fill">Cari</button>
        <a href="supplier.php" class="btn btn-secondary flex-fill">Reset</a>
    </div>
</form>
<?php if ($search !== ''): ?>
    <div class="alert alert-info py-2">Hasil pencarian untuk <strong><?php echo e($search); ?></strong> &mdash; <?php echo count($list); ?> supplier ditemukan.</div>
<?php endif; ?>
<div class="table-responsive">
<table class="table table-bordered table-striped">
    <thead class="table-light">
        <tr><th>No</th><th>Nama Supplier</th><th>Kontak</th><th>Jumlah Produk</th><th>Aksi</th></tr>
    </thead>
    <tbody>
    <?php if (count($list) === 0): ?>
        <tr><td colspan="5" class="text-center"><?php echo $search !== '' ? 'Tidak ada supplier yang cocok dengan pencarian ini.' : 'Belum ada data supplier.'; ?></td></tr>
    <?php else: $no=1; foreach ($list as $row): ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo e($row['supplier_name']); ?></td>
            <td><?php echo e($row['contact']); ?></td>
            <td><?php echo e($row['product_count']); ?></td>
            <td>
                <a href="supplier_form.php?id=<?php echo e($row['id']); ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="supplier_hapus.php?id=<?php echo e($row['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus supplier ini?');">Hapus</a>
            </td>
        </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>
</div>
<?php require_once 'footer.php'; ?>
