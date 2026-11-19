<?php
require_once 'config.php';

$id = isset($_GET['id']) && ctype_digit((string)$_GET['id']) ? (int)$_GET['id'] : 0;
$edit = $id > 0;
$category_name = '';
$errors = [];

if ($edit) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) {
        $_SESSION['flash'] = 'Kategori tidak ditemukan.';
        $_SESSION['flash_type'] = 'danger';
        header('Location: kategori.php');
        exit;
    }
    $category_name = $row['category_name'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = trim($_POST['category_name'] ?? '');
    if ($category_name === '') $errors[] = 'Nama kategori tidak boleh kosong.';

    if (empty($errors)) {
        if ($edit) {
            $stmt = $pdo->prepare("UPDATE categories SET category_name=? WHERE id=?");
            $stmt->execute([$category_name, $id]);
            $_SESSION['flash'] = 'Kategori berhasil diperbarui.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO categories (category_name) VALUES (?)");
            $stmt->execute([$category_name]);
            $_SESSION['flash'] = 'Kategori berhasil ditambahkan.';
        }
        $_SESSION['flash_type'] = 'success';
        header('Location: kategori.php');
        exit;
    }
}

require_once 'header.php';
?>
<h3><?php echo $edit ? 'Edit Kategori' : 'Tambah Kategori'; ?></h3>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $err) echo '<li>'.e($err).'</li>'; ?></ul></div>
<?php endif; ?>
<form method="post" class="card card-body">
    <div class="mb-3">
        <label class="form-label">Nama Kategori</label>
        <input type="text" name="category_name" class="form-control" value="<?php echo e($category_name); ?>">
    </div>
    <div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="kategori.php" class="btn btn-secondary">Batal</a>
    </div>
</form>
<?php require_once 'footer.php'; ?>
