<?php
session_start();
include 'config.php';
//cek apakah belum login
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
// Ambil ID paket dari URL
$package_id = $_GET['id'] ?? null;

if (!$package_id || !is_numeric($package_id)) {
  die("ID paket tidak valid.");
}

// Ambil data paket dari database
$stmt = $conn->prepare("SELECT * FROM packages WHERE id = ?");
$stmt->bind_param("i", $package_id);
$stmt->execute();
$result = $stmt->get_result();
$package = $result->fetch_assoc();

// Terima qty dari chatbot (opsional)
$quantity = $_GET['qty'] ?? 1;
$total = $package['price'] * $quantity;

if (!$package) {
  die("Paket tidak ditemukan.");
}

$error = '';
$showModal = false;
$order_id = null;

// Proses form saat submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $quantity = (int) $_POST['quantity'];

  if ($quantity < 1) {
    $error = "Jumlah pesanan minimal 1.";
  } else {
    $total_price = $package['price'] * $quantity;

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO orders (user_id, package_id, quantity, total_price, status) VALUES (?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("iiid", $_SESSION['user_id'], $package_id, $quantity, $total_price);

    if ($stmt->execute()) {
      $order_id = $stmt->insert_id;
      $showModal = true; // Tampilkan modal setelah sukses
    } else {
      $error = "Gagal menyimpan pesanan.";
    }
  }
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
        <div class="row">
          <div class="col-12">

            <!-- Tombol Kembali -->
            <a href="index.php" class="btn btn-outline-dark btn-sm mb-4" data-aos="fade-right">
              <i class="bi bi-arrow-left"></i> Kembali
            </a>

            <h2 class="mb-4" data-aos="fade-up"><i class="bi bi-cart"></i> Form Pemesanan</h2>

            <?php if ($error): ?>
              <div class="alert alert-danger alert-dismissible fade show" data-aos="fade-up">
                <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            <?php endif; ?>

            <div class="row g-4" data-aos="fade-up" data-aos-delay="100">
              <!-- Detail Paket -->
              <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                  <img src="assets/img/<?= htmlspecialchars($package['image']) ?>" class="img-fluid rounded-top"
                    alt="<?= htmlspecialchars($package['package_name']) ?>" style="height: 300px; object-fit: cover;">
                  <div class="card-body">
                    <h4><?= htmlspecialchars($package['package_name']) ?></h4>
                    <p><strong>Tujuan:</strong> <?= htmlspecialchars($package['destination']) ?></p>
                    <p><strong>Durasi:</strong> <?= htmlspecialchars($package['duration']) ?></p>
                    <h5 class="text-dark fw-bold">Rp <?= number_format($package['price'], 0, ',', '.') ?></h5>
                    <p><?= htmlspecialchars($package['description']) ?></p>
                  </div>
                </div>
              </div>
              <!-- Form Pemesanan -->
              <div class="col-lg-6">
                <div class="card border-0 shadow-sm p-4">
                  <form method="POST" id="orderForm">
                    <div class="mb-3">
                      <label class="form-label">Harga per Paket</label>
                      <input type="text" class="form-control"
                        value="Rp <?= number_format($package['price'], 0, ',', '.') ?>" readonly>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Jumlah Pesanan</label>
                      <input type="number" name="quantity" class="form-control" value="<?= $quantity ?>" min="1"
                        required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Total Harga</label>
                      <input type="text" id="total_price" class="form-control"
                        value="Rp <?= number_format($total, 0, ',', '.') ?>" readonly>
                    </div>
                    <div class="d-grid">
                      <button type="submit" class="btn btn-outline-dark">
                        <i class="bi bi-check-circle"></i> Pesan Sekarang
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>

  <!-- Modal Upload Bukti Pembayaran -->
  <?php if ($showModal): ?>
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true"
      data-bs-backdrop="static" data-bs-keyboard="false">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <form action="upload-bukti.php" method="POST" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title" id="paymentModalLabel">Upload Bukti Pembayaran</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Silakan upload bukti pembayaran Anda:</p>
              <input type="hidden" name="order_id" value="<?= $order_id ?>">
              <input type="file" name="payment_proof" class="form-control" accept="image/*" required>
              <span>Harap transfer ke rekening BCA berikut : 6470524608</span>
              <small class="text-muted mt-2 d-block">Format: JPG, PNG, maksimal 2MB.</small>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle"></i> Cancel
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-upload"></i> Upload & Lihat Pesanan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- Vendor JS -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/js/main.js" defer=""></script>

  <!-- Auto-update Total Harga -->
  <script>
    const price = <?= $package['price'] ?>;
    document.querySelector('input[name="quantity"]').addEventListener('input', function () {
      const qty = this.value || 1;
      const total = price * qty;
      document.getElementById('total_price').value = 'Rp ' + total.toLocaleString('id-ID');
    });
  </script>
  <!-- Initialize AOS & Show Modal -->
  <script>
    AOS.init();
    // Tampilkan modal jika $showModal true
    <?php if ($showModal): ?>
      document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
        modal.show();
      });
    <?php endif; ?>
  </script>
</body>

</html>