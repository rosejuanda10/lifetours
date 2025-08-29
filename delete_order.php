<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id']))
    die("Akses ditolak");

$id = intval($_GET['id']);
$conn->query("DELETE FROM orders WHERE id = $id AND user_id = {$_SESSION['user_id']}");
header("Location: my-orders.php");