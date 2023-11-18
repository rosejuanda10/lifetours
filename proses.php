<?php
//koneksi
$host = "localhost";
$user = "root";
$pass = "";
$database = "db_anggota";

$koneksi = mysqli_connect($host, $user, $pass, $database);

if ($koneksi) {
	echo "koneksi berhasil";
}

$number = "";
$nama = "";
$tujuan = "";
$pekerjaan = "";
$sukses = "";
$error = "";

if (isset($_GET['op'])) {
	$op = $_GET['op'];
}else {
	$op = "";
}

if ($op == 'ubah') {
	$number = $_GET['number'];
	$query = "SELECT * from tbl_anggota where number = '$number'";
	$ubah = mysqli_query($koneksi, $query);
	$tampil = mysqli_fetch_array($ubah);
	$number =$tampil['number'];
	$nama =$tampil['nama'];
	$pekerjaan =$tampil['pekerjaan'];
	$tujuan =$tampil['tujuan'];

	if ($number == '') {
		$error = "data ga ketemu";
		$query = "delete from tbl_anggota where number = '$number'";
		$hapus = mysqli_query($koneksi, $query);
		if ($hapus) {
			$sukses = "data berhasil dihapus";
			$number = "";
		}else {
			$error = "Data gagal di hapus ";
		}
	}
}

if ($op == 'hapus') {
	$number = $_GET['number'];
	$query = "delete from tbl_anggota where number = '$number'";
	$hapus = mysqli_query($koneksi, $query);
	if ($hapus) {
		$sukses = "Data berhasil dihapus";
		$number = "";
	}else {
		$error = "data gagal dihapus";
	}
}

if (isset($_POST['simpan'])){
	$number =$_POST['number'];
	$nama =$_POST['nama'];
	$pekerjaan =$_POST['pekerjaan'];
	$tujuan =$_POST['tujuan'];
	if ($number && $nama && $tujuan && $pekerjaan){
		if ($op == 'ubah') {
			$query = "update tbl_anggota set nama = '$nama', tujuan = '$tujuan', pekerjaan = '$pekerjaan', where Phone number = '$number'";
			$ubah = mysqli_query($koneksi, $query);
			if ($ubah){
				$sukses = "data berhasil di uodate";
				$number ="";
				$nama ="";
				$tujuan ="";
				$pekerjaan ="";
			}else{
				$error = "data gagal disimpan";
			}
		}else {

			$query = "insert into tbl_anggota values ('$number', '$nama', '$pekerjaan', '$tujuan')";
			$simpan = mysqli_query($koneksi, $query);
			if ($simpan){
				$sukses = "data berhasil di simpan";
				$number ="";
				$nama ="";
				$tujuan ="";
				$pekerjaan ="";
			}else{
				$error = "data gagal disimpan";
			}
		}
	}else {
		$error  = "masukan semua data";
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SUPERSONIC</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <style> 
  .mx-auto { width: 800px; }
  .card { margin-top: 10px }
  </style>
</head>
<body>
	<div class= "mx-auto">
		<ul class="navbar-nav">
			<li class="nav-item">
				<a align="right"class="nav-link" href="dashboard.html" >HOME</a>
			</li>
		</ul>
		<div class="card">
			<div class="card-header text-white bg-warning">
				INPUT
			</div>
			<div class="card-body">
				<?php 
				if ($sukses) {
					?>
					<div class="alert alert-success" role="alert">
						<?php echo $sukses; ?> 
					</div>
					<?php 
				}
				if ($error) {
					?>
					<div class="alert alert-danger" role="alert">
						<?php echo $error; ?> 
					</div>
					<?php 
				}
				?>
				<form action="" method="POST">
					<div class="mb-3 row">
						<label for="number" class="col-sm-2 col-form-label">The Contribution</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="number" name="number" value="<?php echo $number ?>">
						</div>
					</div>
					<div class="mb-3 row">
						<label for="nama" class="col-sm-2 col-form-label">Name</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
						</div>
					</div>
					<div class="mb-3 row">
						<label for="tujuan" class="col-sm-2 col-form-label">Purpose</label>
						<div class="col-sm-10">
							<select class="form-control" id="tujuan" name="tujuan">
								<option value="">-choose your purpose-</option>
								<option value="charity" <?php if ($tujuan == 'charity') echo "selected";?>>charity</option>
								<option value="invesment" <?php if ($tujuan == 'invesment') echo "selected";?>>investment</option>
								<option value="amazing" <?php if ($tujuan == 'amazing') echo "selected";?>>amazing</option>
							</select>
						</div>
					</div>
					<div class="mb-3 row">
						<label for="pekerjaan" class="col-sm-2 col-form-label">Job</label>
						<div class="col-sm-10">
							<textarea class="form-control" id="pekerjaan" name="pekerjaan" rows="3" value="<?php echo $pekerjaan ?>"></textarea>
						</div>
					</div>
					<div class="col-12" align="right">
						<input type="submit" name="simpan" value="simpan data" class="btn btn-dark">
					</div>
				</form>
			</div>
		</div>
		<div class="card">
			<div class="card-header text-white bg-warning">
				YOUR CONTRIBUTION
			</div>
			<div class="card-body">
				<table class="table">
					<thead>
						<tr>
							<th scope="col">LIST</th>
							<th scope="col">The Contribution</th>
							<th scope="col">Name</th>
							<th scope="col">Purpose</th>
							<th scope="col">Job</th>
							<th scope="col">action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$query = "SELECT * from tbl_anggota order by number asc";
						$tampil = mysqli_query ($koneksi, $query);
						$urut = 1;
						while ($result = mysqli_fetch_array($tampil)) {
							$number = $result ['number'];
							$nama = $result ['nama'];
							$pekerjaan = $result ['pekerjaan'];
							$tujuan = $result ['tujuan'];
						
						?>
						<tr>
							<th scope="row"><?php echo $urut++; ?></th>
							<td scope="row"><?php echo $number; ?></td>
							<td scope="row"><?php echo $nama; ?></td>
							<td scope="row"><?php echo $tujuan; ?></td>
							<td scope="row"><?php echo $pekerjaan; ?></td>
							<td scope="row">
								<a href="proses.php?op=ubah&number=<?php echo $number; ?>"><button type="button" class="btn btn-warning">edit</button></a>
								<a href="proses.php?op=hapus&number=<?php echo $number; ?>" onclick="return confirm('apakah anda ingin menghapus?')"><button type="button" class="btn btn-danger">delete</button></a>
						</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</body>
</html>