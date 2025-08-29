<?php
$host = "localhost";
$user = "orpkwhbn_lifetours";
$pass = "FX2CUhLUmanyjYXS2fgs";
$db = "orpkwhbn_lifetours";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>