<?php
require_once 'config.php';
require_once 'header.php';

$category_id = isset($_GET['category_id']) && is_scalar($_GET['category_id']) ? (string)$_GET['category_id'] : '';
$supplier_id = isset($_GET['supplier_id']) && is_scalar($_GET['supplier_id']) ? (string)$_GET['supplier_id'] : '';
$search      = isset($_GET['search']) && is_scalar($_GET['search']) ? trim((string)$_GET['search']) : '';

$categoryList = $pdo->query("SELECT id, category_name FROM categories ORDER BY category_name")->fetchAll();
$supplierList = $pdo->query("SELECT id, supplier_name FROM suppliers ORDER BY supplier_name")->fetchAll();

$sql = "SELECT p.*, c.category_name, s.supplier_name
        FROM products p
        JOIN categories c ON p.category_id = c.id
        JOIN suppliers s ON p.supplier_id = s.id
        WHERE 1=1";
$params = [];
if ($search !== '') {
    $like = like_param($search);
    $sql .= " AND (p.product_name LIKE ? ESCAPE '\\\\' OR c.category_name LIKE ? ESCAPE '\\\\' OR s.supplier_name LIKE ? ESCAPE '\\\\')";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}
if ($category_id !== '' && ctype_digit($category_id)) {
    $sql .= " AND p.category_id = ?";
    $params[] = $category_id;
}
if ($supplier_id !== '' && ctype_digit($supplier_id)) {
    $sql .= " AND p.supplier_id = ?";
    $params[] = $supplier_id;
}
$sql .= " ORDER BY p.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$productList = $stmt->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Daftar Produk</h3>
    <a href="produk_form.php" class="btn btn-success">Tambah Produk</a>
</div>
<form method="get" class="row g-2 mb-3">
    <div class="col-md-6">
        <label for="search" class="visually-hidden">Cari produk</label>
        <input type="search" id="search" name="search" class="form-control" placeholder="Cari nama produk, kategori, atau supplier..." value="<?php echo e($search); ?>">
    </div>
    <div class="col-md-3">
        <label for="category_id" class="visually-hidden">Filter kategori</label>
        <select id="category_id" name="category_id" class="form-select">
            <option value="">-- Semua Kategori --</option>
            <?php foreach ($categoryList as $c): ?>
                <option value="<?php echo e($c['id']); ?>" <?php echo $category_id === (string)$c['id'] ? 'selected' : ''; ?>><?php echo e($c['category_name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <label for="supplier_id" class="visually-hidden">Filter supplier</label>
        <select id="supplier_id" name="supplier_id" class="form-select">
            <option value="">-- Semua Supplier --</option>
            <?php foreach ($supplierList as $s): ?>
                <option value="<?php echo e($s['id']); ?>" <?php echo $supplier_id === (string)$s['id'] ? 'selected' : ''; ?>><?php echo e($s['supplier_name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Cari / Filter</button>
        <a href="index.php" class="btn btn-secondary">Reset</a>
    </div>
</form>
<?php if ($search !== ''): ?>
    <div class="alert alert-info py-2">Hasil pencarian untuk <strong><?php echo e($search); ?></strong> &mdash; <?php echo count($productList); ?> produk ditemukan.</div>
<?php endif; ?>
<div class="table-responsive">
<table class="table table-bordered table-striped">
    <thead class="table-light">
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Kategori</th>
            <th>Supplier</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php if (count($productList) === 0): ?>
        <tr><td colspan="7" class="text-center">
            <?php echo ($search !== '' || $category_id !== '' || $supplier_id !== '') ? 'Tidak ada produk yang cocok dengan pencarian/filter ini.' : 'Belum ada data produk.'; ?>
        </td></tr>
    <?php else: ?>
        <?php $no = 1; foreach ($productList as $p): ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo e($p['product_name']); ?></td>
            <td>Rp <?php echo number_format((int)$p['price'], 0, ',', '.'); ?></td>
            <td><?php echo e($p['stock']); ?></td>
            <td><?php echo e($p['category_name']); ?></td>
            <td><?php echo e($p['supplier_name']); ?></td>
            <td>
                <a href="produk_form.php?id=<?php echo e($p['id']); ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="produk_hapus.php?id=<?php echo e($p['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus produk ini?');">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
</div>
<?php require_once 'footer.php'; ?>
