<?php
require_once 'config.php';

$id = isset($_GET['id']) && ctype_digit((string)$_GET['id']) ? (int)$_GET['id'] : 0;
$edit = $id > 0;
$product_name = '';
$price = '';
$stock = '';
$category_id = '';
$supplier_id = '';
$errors = [];

$categoryList = $pdo->query("SELECT id, category_name FROM categories ORDER BY category_name")->fetchAll();
$supplierList = $pdo->query("SELECT id, supplier_name FROM suppliers ORDER BY supplier_name")->fetchAll();

if ($edit) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) {
        $_SESSION['flash'] = 'Produk tidak ditemukan.';
        $_SESSION['flash_type'] = 'danger';
        header('Location: index.php');
        exit;
    }
    $product_name = $row['product_name'];
    $price = (string)$row['price'];
    $stock = (string)$row['stock'];
    $category_id = (string)$row['category_id'];
    $supplier_id = (string)$row['supplier_id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = trim($_POST['product_name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $stock = trim($_POST['stock'] ?? '');
    $category_id = trim($_POST['category_id'] ?? '');
    $supplier_id = trim($_POST['supplier_id'] ?? '');

    if ($product_name === '') $errors[] = 'Nama produk tidak boleh kosong.';
    if ($price === '' || !ctype_digit($price)) $errors[] = 'Harga harus berupa angka.';
    if ($stock === '' || !ctype_digit($stock)) $errors[] = 'Stok harus berupa angka.';
    if ($category_id === '' || !ctype_digit($category_id)) $errors[] = 'Kategori harus dipilih.';
    if ($supplier_id === '' || !ctype_digit($supplier_id)) $errors[] = 'Supplier harus dipilih.';

    if (empty($errors)) {
        $cekK = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE id = ?");
        $cekK->execute([$category_id]);
        $cekS = $pdo->prepare("SELECT COUNT(*) FROM suppliers WHERE id = ?");
        $cekS->execute([$supplier_id]);
        if ($cekK->fetchColumn() == 0) $errors[] = 'Kategori tidak valid.';
        if ($cekS->fetchColumn() == 0) $errors[] = 'Supplier tidak valid.';
    }

    if (empty($errors)) {
        if ($edit) {
            $stmt = $pdo->prepare("UPDATE products SET product_name=?, price=?, stock=?, category_id=?, supplier_id=? WHERE id=?");
            $stmt->execute([$product_name, $price, $stock, $category_id, $supplier_id, $id]);
            $_SESSION['flash'] = 'Produk berhasil diperbarui.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO products (product_name, price, stock, category_id, supplier_id) VALUES (?,?,?,?,?)");
            $stmt->execute([$product_name, $price, $stock, $category_id, $supplier_id]);
            $_SESSION['flash'] = 'Produk berhasil ditambahkan.';
        }
        $_SESSION['flash_type'] = 'success';
        header('Location: index.php');
        exit;
    }
}

require_once 'header.php';
?>
<h3><?php echo $edit ? 'Edit Produk' : 'Tambah Produk'; ?></h3>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $err) echo '<li>'.e($err).'</li>'; ?></ul></div>
<?php endif; ?>
<form method="post" class="card card-body">
    <div class="mb-3">
        <label class="form-label">Nama Produk</label>
        <input type="text" name="product_name" class="form-control" value="<?php echo e($product_name); ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Harga</label>
        <input type="number" name="price" class="form-control" value="<?php echo e($price); ?>" min="0">
    </div>
    <div class="mb-3">
        <label class="form-label">Stok</label>
        <input type="number" name="stock" class="form-control" value="<?php echo e($stock); ?>" min="0">
    </div>
    <div class="mb-3">
        <label class="form-label">Kategori</label>
        <select name="category_id" class="form-select">
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($categoryList as $c): ?>
                <option value="<?php echo e($c['id']); ?>" <?php echo ((string)$category_id === (string)$c['id']) ? 'selected' : ''; ?>><?php echo e($c['category_name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Supplier</label>
        <select name="supplier_id" class="form-select">
            <option value="">-- Pilih Supplier --</option>
            <?php foreach ($supplierList as $s): ?>
                <option value="<?php echo e($s['id']); ?>" <?php echo ((string)$supplier_id === (string)$s['id']) ? 'selected' : ''; ?>><?php echo e($s['supplier_name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </div>
</form>
<?php require_once 'footer.php'; ?>
