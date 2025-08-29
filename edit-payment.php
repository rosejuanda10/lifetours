<?php
session_start();
include 'config.php';

$order_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

if (!$order_id || !$user_id) {
    die("Akses ditolak.");
}

// Cek kepemilikan dan konfirmasi admin
$stmt = $conn->prepare("SELECT admin_confirmed, payment_proof FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("Pesanan tidak ditemukan.");
}

if ($order['admin_confirmed']) {
    die("Tidak bisa mengedit bukti pembayaran setelah dikonfirmasi admin.");
}

// Proses upload jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['payment_proof']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_name = "bukti_" . $order_id . "." . $ext;
            $upload_dir = "assets/bukti/";
            $target = $upload_dir . $new_name;

            if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $target)) {
                // Hapus file lama jika ada
                if ($order['payment_proof'] && file_exists($upload_dir . $order['payment_proof'])) {
                    unlink($upload_dir . $order['payment_proof']);
                }

                // Simpan nama file baru
                $stmt = $conn->prepare("UPDATE orders SET payment_proof = ? WHERE id = ?");
                $stmt->bind_param("si", $new_name, $order_id);
                $stmt->execute();

                header("Location: my-orders.php?msg=updated");
                exit();
            } else {
                $error = "Gagal mengunggah file.";
            }
        } else {
            $error = "File tidak valid atau ukuran terlalu besar (maks 2MB).";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Bukti Pembayaran</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5" style="max-width: 500px;">
        <h4>Edit Bukti Pembayaran</h4>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Upload Bukti Pembayaran Baru</label>
                <input type="file" name="payment_proof" class="form-control" accept="image/*" required>
                <small class="text-muted">Maksimal 2MB, format: JPG, PNG, GIF</small>
            </div>
            <div class="d-flex gap-2">
                <a href="my-orders.php" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Update Bukti</button>
            </div>
        </form>
    </div>
</body>

</html>