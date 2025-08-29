<?php
session_start();
include 'config.php';

// Ambil order_id dari URL
$order_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

if (!$order_id || !$user_id) {
  die("Akses ditolak.");
}

// Validasi order_id sebagai angka
if (!is_numeric($order_id)) {
  die("ID pesanan tidak valid.");
}

// Cek apakah pesanan milik user dan belum dikonfirmasi admin
$stmt = $conn->prepare("SELECT admin_confirmed FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
  die("Pesanan tidak ditemukan atau bukan milik Anda.");
}

if ($order['admin_confirmed']) {
  // Jika sudah dikonfirmasi admin, tidak bisa dibatalkan
  header("Location: my-orders.php?msg=confirmed");
  exit();
}

// Update status pesanan menjadi Cancelled (jangan dihapus)
$stmt = $conn->prepare("UPDATE orders SET status = 'Cancelled' WHERE id = ?");
$stmt->bind_param("i", $order_id);

if ($stmt->execute()) {
  header("Location: my-orders.php?msg=canceled");
} else {
  header("Location: my-orders.php?msg=error");
}

exit();
?>