<?php
session_start();
include 'config.php';

$user_id = $_SESSION['user_id'] ?? 1;
$result = $conn->query("
    SELECT o.id,o.payment_proof, o.quantity, o.status, o.total_price, o.order_date, o.admin_confirmed, o.created_at, p.package_name, p.destination, p.image 
    FROM orders o 
    JOIN packages p ON o.package_id = p.id 
    WHERE o.user_id = $user_id 
    ORDER BY o.order_date DESC
");
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

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

  <!-- Topbar (Opsional, bisa disamakan jika ingin selalu muncul) -->
  <section id="topbar" class="d-flex align-items-center">
		<div class="container d-flex justify-content-center justify-content-md-between">
			<div class="contact-info d-flex align-items-center">
				<i class="bi bi-envelope d-flex align-items-center"><a
						href="mailto:contact@example.com">lifetoursandtravel@gmail.com</a></i>
				<i class="bi bi-phone d-flex align-items-center ms-4"><span>0878-5565-8800</span></i>
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
              <a href="index.php" class="btn btn-outline-primary btn-sm mb-4">
                <i class="bi bi-arrow-left"></i> Kembali
              </a>
              <h2><i class="bi bi-journal-text"></i> Riwayat Pemesanan</h2>

              <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  Pesanan berhasil dibuat!
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              <?php endif; ?>
              <?php if (isset($_GET['msg']) && $_GET['msg'] == 'canceled'): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  Pesanan berhasil dibatalkan.
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              <?php endif; ?>

              <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive mt-4">
                  <table class="table table-bordered table-hover">
                    <thead class="table-light">
                      <tr>
                        <th>Gambar</th>
                        <th>Paket</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                        <th>Bukti TF</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php while ($order = $result->fetch_assoc()):
                        if ($order['admin_confirmed'] == 1) {
                          $can_cancel = false;
                        } else {
                          $can_cancel = true;
                        }

                        ?>
                        <tr>
                          <td>
                            <img src="assets/img/<?= htmlspecialchars($order['image']) ?>" class="img-order" alt="Image">
                          </td>
                          <td>
                            <strong><?= htmlspecialchars($order['package_name']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($order['destination']) ?></small>
                          </td>
                          <td><?= $order['quantity'] ?></td>
                          <td><strong>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></strong></td>
                          <td><?= date('d M Y, H:i', strtotime($order['order_date'])) ?></td>
                          <td>
                            <?php if ($order['payment_proof']): ?>
                              <img src="assets/bukti/<?= htmlspecialchars($order['payment_proof']) ?>" class="img-order"
                                alt="Bukti Pembayaran">
                            <?php else: ?>
                              <small class="text-muted">Belum ada bukti</small>
                            <?php endif; ?>
                          <td>
                            <?php if (!$order['admin_confirmed'] && $order['status'] == 'Pending'): ?>
                              <a href="cancel-order.php?id=<?= $order['id'] ?>" class="btn btn-cancel btn-sm"
                                onclick="return confirm('Yakin batalkan pesanan?')">Batalkan</a>
                              <a href="edit-payment.php?id=<?= $order['id'] ?>" class="btn btn-warning btn-sm text-dark">
                                <i class="bi bi-pencil"></i> Edit Bukti
                              </a>
                            <?php elseif ($order['admin_confirmed']): ?>
                              <small class="text-success">✓ Dikonfirmasi</small>
                            <?php elseif ($order['status'] == 'Cancelled'): ?>
                              <small class="text-danger">X Dibatalkan</small>
                            <?php else: ?>
                              <small class="text-muted">Menunggu konfirmasi</small>
                            <?php endif; ?>
                          </td>
                        </tr>
                      <?php endwhile; ?>
                    </tbody>
                  </table>
                </div>
              <?php else: ?>
                <div class="alert alert-info mt-4">Belum ada pesanan.</div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
<script src="assets/vendor/aos/aos.js" defer></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js" defer></script>
<script src="assets/vendor/isotope-layout/isotope.pkgd.min.js" defer></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js" defer></script>
<script src="assets/vendor/php-email-form/validate.js" defer></script>
<script src="assets/js/main.js" defer></script>

<!-- Initialize AOS -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    AOS.init();
  });
</script>
</body>

</html>