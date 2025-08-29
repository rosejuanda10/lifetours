<!--  --><?php
include 'config.php';

// Filter status (opsional)
$status_filter = $_GET['status'] ?? '';
$sql = "SELECT * FROM packages";
if ($status_filter == 'Available' || $status_filter == 'Not Available') {
    $sql .= " WHERE status = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $status_filter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Travel Packages | LIFE TOURS</title>
    <!-- Bootstrap 5 -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-create {
            background-color: #fc5c24;
            color: white;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #000;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .img-package {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }

        .header-section {
            background: linear-gradient(135deg, #fc5c24, #fb7240ff);
            color: white;
            padding: 2rem 0;
            text-align: center;
        }

        .badge {
            font-size: 0.85em;
            padding: 0.5em 0.8em;
        }

        .btn-action {
            border-radius: 8px;
        }

        .filter-btn {
            margin: 0 5px;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <section class="header-section">
        <h1><i class="bi bi-airplane"></i> LIFE TOURS</h1>
        <p>Admin Panel - Kelola Paket Wisata</p>
    </section>

    <div class="container my-5">

        <!-- Navigasi -->
        <div class="d-flex justify-content-between mb-4">
            <a href="paket-tambah.php" class="btn btn-create btn-action">
                <i class="bi bi-plus-circle"></i> Tambah Paket
            </a>
            <a href="admin/index.php" class="btn btn-outline-secondary btn-action">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <!-- Filter Status -->
        <div class="mb-4 text-center">
            <a href="paket.php" class="btn btn-outline-primary filter-btn">All</a>
            <a href="paket.php?status=Available" class="btn btn-success text-white filter-btn">Available</a>
            <a href="paket.php?status=Not%20Available" class="btn btn-danger filter-btn">Not Available</a>
        </div>

        <!-- Notifikasi -->
        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] == 'created'/*  */): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle"></i> Paket berhasil ditambahkan!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif ($_GET['msg'] == 'updated'): ?>
                <div class="alert alert-warning alert-dismissible fade show">
                    <i class="bi bi-pencil"></i> Paket berhasil diperbarui!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif ($_GET['msg'] == 'deleted'): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-trash"></i> Paket berhasil dihapus!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Daftar Paket -->
        <?php if ($result->num_rows > 0): ?>
            <div class="row g-4">
                <?php while ($p = $result->fetch_assoc()): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100">
                            <img src="assets/img/<?= htmlspecialchars($p['image']) ?>" class="img-package"
                                alt="<?= $p['package_name'] ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($p['package_name']) ?></h5>
                                <p class="card-text text-muted mb-2">
                                    <strong>Destinasi:</strong> <?= htmlspecialchars($p['destination']) ?><br>
                                    <strong>Harga:</strong> Rp <?= number_format($p['price'], 0, ',', '.') ?><br>
                                    <strong>Durasi:</strong> <?= htmlspecialchars($p['duration']) ?>
                                </p>
                                <p class="text-truncate" title="<?= htmlspecialchars($p['description']) ?>">
                                    <?= htmlspecialchars($p['description']) ?>
                                </p>
                                <!-- Status Badge -->
                                <span class="badge <?= $p['status'] == 'Available' ? 'bg-success' : 'bg-danger' ?> mb-3">
                                    <?= $p['status'] ?>
                                </span>
                                <div class="mt-auto d-flex justify-content-between">
                                    <a href="paket-edit.php?id=<?= $p['id'] ?>" class="btn btn-edit btn-sm btn-action">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="paket-hapus.php?id=<?= $p['id'] ?>" class="btn btn-delete btn-sm btn-action"
                                        onclick="return confirm('Yakin ingin hapus paket ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </div>
                            </div>
                            <div class="card-footer small text-muted">
                                Dibuat: <?= date('d M Y', strtotime($p['created_at'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">Belum ada paket wisata.</div>
        <?php endif; ?>
    </div>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>