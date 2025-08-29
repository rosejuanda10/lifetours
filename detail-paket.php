<?php
session_start();
include 'config.php';

//cek apakah belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// Ambil ID dari URL
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    die("ID paket tidak valid.");
}

// Ambil data dari database
$stmt = $conn->prepare("SELECT * FROM packages WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$paket = $result->fetch_assoc();

if (!$paket) {
    die("Paket tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>LIFE TOURS</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Bootstrap CSS (Hanya satu sumber) -->
  <link href="  https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css  " rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css  " rel="stylesheet">

  <!-- Vendor CSS -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="style.css" rel="stylesheet">

  <!-- FontAwesome (jika diperlukan untuk ikon tambahan) -->
  <link rel="stylesheet" href="fontawesome-free-6.2.1-web/css/all.css">

  <style>
    .img-order {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
    }

    .btn-cancel {
      background: #dc3545;
      color: white;
      border: none;
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
    }

    .btn-cancel:hover {
      background: #c82333;
    }

    .btn-cancel:disabled {
      background: #6c757d;
      opacity: 0.6;
    }

    .table th {
      background-color: #f8f9fa;
      font-weight: 600;
      color: #333;
    }

    .table td {
      vertical-align: middle;
    }
  </style>
</head>

<body>

    <!-- Topbar -->
    <section id="topbar" class="d-flex align-items-center">
        <div class="container d-flex justify-content-center justify-content-md-between">
            <div class="contact-info d-flex align-items-center">
                <i class="bi bi-envelope d-flex align-items-center">
                    <a href="mailto:lifetoursandtravel@gmail.com">lifetoursandtravel@gmail.com</a>
                </i>
                <i class="bi bi-phone d-flex align-items-center ms-4">
                    <span>0878-5565-8800</span>
                </i>
            </div>
        </div>
    </section>

    <!-- Header -->
    <header id="header" class="d-flex align-items-center">
		<div class="container d-flex align-items-center justify-content-between">
			<div class="logo d-flex align-items-center">
				<img src="lifetour.jpg" alt="Logo" width="40" height="40" class="me-2">
				<h1><a href="login.php" class="text-decoration-none">LIFE TOURS</a></h1>
			</div>

			<nav id="navbar" class="navbar">
				<ul>
					<li><a class="nav-link scrollto active" href="index.php">Home</a></li>
					<li><a class="nav-link scrollto" href="index.php#about">About Life Tours</a></li>
					<li><a class="nav-link scrollto" href="index.php#Destiny">Destinations</a></li>
					<li><a class="nav-link scrollto" href="index.php#testimonials">Testimonials</a></li>
					<li><a class="nav-link scrollto" href="index.php#contact">Contact</a></li>

					<?php if (isset($_SESSION['user_id'])): ?>
						<!-- Jika sudah login -->
						<li><a class="nav-link scrollto" href="my-orders.php">My Orders</a></li>
						<li>
							<a class="logout" href="logout.php" onclick="return confirm('Apakah anda yakin akan logout?')">
								Keluar
							</a>
						</li>
					<?php else: ?>
						<!-- Jika belum login -->
						<li><a class="nav-link scrollto" href="login.php">Login</a></li>
					<?php endif; ?>
				</ul>
				<i class="bi bi-list mobile-nav-toggle"></i>
			</nav>
		</div>
	</header>

    <!-- Main Content -->
    <main id="main" class="pt-5">
        <div class="container">
            <section class="about section-bg">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <!-- Tombol Kembali -->
                        <a href="index.php" class="btn btn-outline-dark btn-sm mb-4" data-aos="fade-right">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>

                        <!-- Detail Paket -->
                        <div class="card border-0 shadow-sm" data-aos="fade-up">
                            <div class="row g-0">
                                <div class="col-md-6">
                                    <img src="assets/img/<?= htmlspecialchars($paket['image']) ?>"
                                        class="img-fluid rounded-start"
                                        alt="<?= htmlspecialchars($paket['package_name']) ?>"
                                        style="width: 100%; height: 400px; object-fit: cover;">
                                </div>
                                <div class="col-md-6">
                                    <div class="card-body p-4">
                                        <h2 class="card-title"><?= htmlspecialchars($paket['package_name']) ?></h2>
                                        <p class="text-muted"><strong>Tujuan:</strong>
                                            <?= htmlspecialchars($paket['destination']) ?></p>

                                        <h4 class="text-orange-500 fw-bold">
                                            Rp <?= number_format($paket['price'], 0, ',', '.') ?>
                                        </h4>
                                        <p><strong>Durasi:</strong> <?= htmlspecialchars($paket['duration']) ?></p>

                                        <!-- Status Badge -->
                                        <span class="badge rounded-pill 
                                            <?= $paket['status'] == 'Available' ? 'bg-success' : 'bg-danger' ?>">
                                            <?= htmlspecialchars($paket['status']) ?>
                                        </span>

                                        <hr class="my-3">

                                        <h5><i class="bi bi-info-circle"></i> Deskripsi</h5>
                                        <p><?= htmlspecialchars($paket['description']) ?></p>

                                        <hr class="my-3">

                                        <!-- Tombol Pesan -->
                                        <div class="d-grid gap-2 mt-4">
                                            <?php if ($paket['status'] == 'Available'): ?>
                                                <a href="order.php?id=<?= $paket['id'] ?>" class="btn btn-outline-dark">
                                                    <i class="bi bi-calendar-check"></i> Pesan Sekarang
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-secondary" disabled>
                                                    <i class="bi bi-clock"></i> Tidak Tersedia
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Vendor JS -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js" defer=""></script>
    <!-- Initialize AOS -->
    <script>
        AOS.init();
    </script>
</body>

</html>
