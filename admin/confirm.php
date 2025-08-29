<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    die("Akses ditolak.");
}

$order_id = $_GET['id'] ?? null;
if ($order_id && is_numeric($order_id)) {
    $stmt = $conn->prepare("UPDATE orders SET admin_confirmed = 1, status = 'Confirmed' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
}

header("Location: index.php?msg=confirmed");
exit();