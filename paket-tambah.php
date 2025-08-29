<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $package_name = $_POST['package_name'];
    $destination = $_POST['destination'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO packages (package_name, destination, price, duration, description, image, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdssss", $package_name, $destination, $price, $duration, $description, $image, $status);

    if ($stmt->execute()) {
        header("Location: paket.php?msg=created");
        exit();
    } else {
        $error = "Gagal menyimpan data.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tambah Paket - LIFE TOURS</title>
  <!-- Bootstrap 5 -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="fontawesome-free-6.2.1-web/css/all.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #fc5c24, #fc5c24);
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
      margin: 0;
      padding: 0;
      color: #333;
    }

    .form-container {
      max-width: 750px;
      margin: 40px auto;
      animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .card {
      border: none;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      background: white;
      backdrop-filter: blur(10px);
    }

    .card-header {
      background: #fc5c24;
      color: white;
      padding: 1.5rem;
      text-align: center;
      font-weight: 600;
      font-size: 1.3rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .card-header i {
      font-size: 1.5rem;
    }

    .card-body {
      padding: 2rem;
    }

    .form-label {
      font-weight: 500;
      color: #444;
    }

    .form-control, .form-select {
      border: 1px solid #ced4da;
      border-radius: 10px;
      padding: 10px 14px;
      transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
      border-color: #fc5c24;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .btn {
      padding: 10px 20px;
      border-radius: 10px;
      font-weight: 500;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn-success {
      background-color: #28a745;
      border: none;
    }

    .btn-success:hover {
      background-color: #218838;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    .btn-secondary {
      background-color: #6c757d;
      border: none;
    }

    .btn-secondary:hover {
      background-color: #5a6268;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
    }

    .action-buttons {
      display: flex;
      justify-content: space-between;
      gap: 15px;
      margin-top: 20px;
    }

    @media (max-width: 576px) {
      .action-buttons {
        flex-direction: column;
      }
      .btn {
        width: 100%;
        justify-content: center;
      }
    }

    .form-section {
      margin-bottom: 25px;
    }

    .form-section h5 {
      color: #fc5c24;
      margin-bottom: 15px;
      font-weight: 600;
      border-bottom: 2px solid #e9ecef;
      padding-bottom: 5px;
    }

    .alert {
      border-radius: 10px;
      font-weight: 500;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="form-container">
      <div class="card shadow-lg">
        <div class="card-header">
          <i class="fas fa-suitcase-rolling"></i>
          Tambah Paket Wisata Baru
        </div>
        <div class="card-body">
          
          <!-- Notifikasi Error -->
          <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
              <i class="bi bi-exclamation-triangle"></i> <?= $error ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <!-- Form -->
          <form method="POST">
            <div class="form-section">
              <h5><i class="bi bi-info-circle"></i> Informasi Dasar</h5>
              <div class="mb-3">
                <label class="form-label">Nama Paket</label>
                <input type="text" name="package_name" class="form-control" placeholder="Contoh: Paket Wisata Bali" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Destinasi</label>
                <input type="text" name="destination" class="form-control" placeholder="Contoh: Bali, Lombok, Jogja" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Durasi</label>
                <input type="text" name="duration" class="form-control" placeholder="Contoh: 4 Hari 3 Malam" required>
              </div>
            </div>

            <div class="form-section">
              <h5><i class="bi bi-currency-dollar"></i> Harga & Status</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="price" class="form-control" placeholder="Contoh: 5000000" required min="0">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                      <option value="Available">Available</option>
                      <option value="Not Available">Not Available</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-section">
              <h5><i class="bi bi-image"></i> Gambar & Deskripsi</h5>
              <div class="mb-3">
                <label class="form-label">Pilih Gambar</label>
                <select name="image" class="form-select">
                  <option value="paket1.jpg">paket1.jpg - Bali</option>
                  <option value="paket2.jpg">paket2.jpg - Lombok</option>
                  <option value="paket3.jpg">paket3.jpg - Yogyakarta</option>
                  <option value="paket4.jpg">paket4.jpg - Bromo</option>
                  <option value="paket5.jpg">paket5.jpg - Bandung</option>
                  <option value="paket6.jpg">paket6.jpg - Belitung</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Tulis deskripsi menarik tentang paket ini..." required></textarea>
              </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="action-buttons">
              <a href="paket.php" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Batal
              </a>
              <button type="submit" class="btn btn-success">
                <i class="bi bi-save"></i> Simpan Paket
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>