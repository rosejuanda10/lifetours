<?php
include 'config.php';
$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM packages WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$p = $result->fetch_assoc();

if (!$p)
    die("Paket tidak ditemukan.");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $package_name = $_POST['package_name'];
    $destination = $_POST['destination'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE packages SET package_name = ?, destination = ?, price = ?, duration = ?, description = ?, image = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ssdssssi", $package_name, $destination, $price, $duration, $description, $image, $status, $id);

    if ($stmt->execute()) {
        header("Location: paket.php?msg=updated");
        exit();
    } else {
        $error = "Gagal memperbarui.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Paket - LIFE TOURS</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 700px;
            margin: 40px auto;
        }

        .btn-action {
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h5><i class="bi bi-pencil-square"></i> Edit Paket Wisata</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Nama Paket</label>
                            <input type="text" name="package_name" class="form-control"
                                value="<?= htmlspecialchars($p['package_name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Destinasi</label>
                            <input type="text" name="destination" class="form-control"
                                value="<?= htmlspecialchars($p['destination']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Harga (Rp)</label>
                            <input type="number" name="price" class="form-control" value="<?= $p['price'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Durasi</label>
                            <input type="text" name="duration" class="form-control"
                                value="<?= htmlspecialchars($p['duration']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Gambar</label>
                            <select name="image" class="form-control" required>
                                <option value="paket1.jpg" <?= $p['image'] == 'paket1.jpg' ? 'selected' : '' ?>>paket1.jpg
                                </option>
                                <option value="paket2.jpg" <?= $p['image'] == 'paket2.jpg' ? 'selected' : '' ?>>paket2.jpg
                                </option>
                                <option value="paket3.jpg" <?= $p['image'] == 'paket3.jpg' ? 'selected' : '' ?>>paket3.jpg
                                </option>
                                <option value="paket4.jpg" <?= $p['image'] == 'paket4.jpg' ? 'selected' : '' ?>>paket4.jpg
                                </option>
                                <option value="paket5.jpg" <?= $p['image'] == 'paket5.jpg' ? 'selected' : '' ?>>paket5.jpg
                                </option>
                                <option value="paket6.jpg" <?= $p['image'] == 'paket6.jpg' ? 'selected' : '' ?>>paket6.jpg
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="Available" <?= $p['status'] == 'Available' ? 'selected' : '' ?>>Available
                                </option>
                                <option value="Not Available" <?= $p['status'] == 'Not Available' ? 'selected' : '' ?>>Not
                                    Available</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Deskripsi</label>
                            <textarea name="description" class="form-control" rows="4"
                                required><?= htmlspecialchars($p['description']) ?></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-warning text-white btn-action">
                                <i class="bi bi-arrow-repeat"></i> Perbarui
                            </button>
                            <a href="paket.php" class="btn btn-secondary btn-action">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>