<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $target_dir = "assets/bukti/";
    $file_name = basename($_FILES["payment_proof"]["name"]);
    $target_file = $target_dir . $file_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Buat nama file unik
    $file_name = uniqid() . "." . $imageFileType;
    $target_file = $target_dir . $file_name;

    // Upload file
    if (move_uploaded_file($_FILES["payment_proof"]["tmp_name"], $target_file)) {
        // Simpan path ke database
        $stmt = $conn->prepare("UPDATE orders SET payment_proof = ? WHERE id = ?");
        $stmt->bind_param("si", $file_name, $order_id);
        $stmt->execute();

        // Redirect ke my-orders.php
        header("Location: my-orders.php?msg=success&id=$order_id");
        exit();
    } else {
        echo "Terjadi kesalahan saat mengunggah file.";
    }
}
?>