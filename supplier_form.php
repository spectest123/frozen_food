<?php
require_once 'config.php';

$id = isset($_GET['id']) && ctype_digit((string)$_GET['id']) ? (int)$_GET['id'] : 0;
$edit = $id > 0;
$supplier_name = '';
$contact = '';
$errors = [];

if ($edit) {
    $stmt = $pdo->prepare("SELECT * FROM suppliers WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) {
        $_SESSION['flash'] = 'Supplier tidak ditemukan.';
        $_SESSION['flash_type'] = 'danger';
        header('Location: supplier.php');
        exit;
    }
    $supplier_name = $row['supplier_name'];
    $contact = $row['contact'] ?? '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_name = trim($_POST['supplier_name'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    if ($supplier_name === '') $errors[] = 'Nama supplier tidak boleh kosong.';
    if ($contact === '') $errors[] = 'Kontak tidak boleh kosong.';

    if (empty($errors)) {
        if ($edit) {
            $stmt = $pdo->prepare("UPDATE suppliers SET supplier_name=?, contact=? WHERE id=?");
            $stmt->execute([$supplier_name, $contact, $id]);
            $_SESSION['flash'] = 'Supplier berhasil diperbarui.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO suppliers (supplier_name, contact) VALUES (?, ?)");
            $stmt->execute([$supplier_name, $contact]);
            $_SESSION['flash'] = 'Supplier berhasil ditambahkan.';
        }
        $_SESSION['flash_type'] = 'success';
        header('Location: supplier.php');
        exit;
    }
}

require_once 'header.php';
?>
<h3><?php echo $edit ? 'Edit Supplier' : 'Tambah Supplier'; ?></h3>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $err) echo '<li>'.e($err).'</li>'; ?></ul></div>
<?php endif; ?>
<form method="post" class="card card-body">
    <div class="mb-3">
        <label class="form-label">Nama Supplier</label>
        <input type="text" name="supplier_name" class="form-control" value="<?php echo e($supplier_name); ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Kontak</label>
        <input type="text" name="contact" class="form-control" value="<?php echo e($contact); ?>">
    </div>
    <div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="supplier.php" class="btn btn-secondary">Batal</a>
    </div>
</form>
<?php require_once 'footer.php'; ?>
