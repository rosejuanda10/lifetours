<?php
include 'config.php';
$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM packages WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: paket.php?msg=deleted");
} else {
    echo "Gagal menghapus paket.";
}
?>