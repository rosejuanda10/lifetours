<?php
require 'koneksi.php';
$email = $_POST["email"];
$password = $_POST["password"];

$query_sql = "SELECT * FROM tbl_users
			WHERE email = '$email' AND password = '$password'";

$result = mysqli_query($conn, $query_sql);

if (mysqli_num_rows($result) > 0) {
	header("Location: dashboard.html");
} else {
	echo '<center><div class="alert alert-danger">Upss...!!! Login gagal. Silakan Coba Kembali</div><br><button<strong><a href="index.html">LOGIN AGAIN</a></strong></button></center>';
}
