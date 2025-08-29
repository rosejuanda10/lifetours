<?php
session_start();
include '../config.php';

// Cek login admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Ambil semua pesanan
$result = $conn->query("
    SELECT o.id, o.user_id, o.package_id, o.status, o.quantity, o.total_price, o.order_date, o.admin_confirmed, o.payment_proof,
           p.package_name, p.destination, u.username 
    FROM orders o 
    JOIN packages p ON o.package_id = p.id 
    JOIN users u ON o.user_id = u.id 
    ORDER BY o.order_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | LIFE TOURS</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #000;
            color: white;
            padding: 1.5rem 1rem;
        }

        .sidebar .logo {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
        }

        .sidebar a {
            color: #ddd;
            padding: 0.75rem 1rem;
            display: block;
            border-radius: 8px;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #333;
            color: white;
        }

        .main-content {
            padding: 2rem;
        }

        .proof-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            border: 1px solid #ddd;
            transition: transform 0.2s;
        }

        .proof-img:hover {
            transform: scale(2.5);
            z-index: 10;
        }

        .no-proof {
            font-size: 0.9em;
            color: #6c757d;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="logo">LIFE TOURS</div>
                <a href="index.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="../paket.php"><i class="bi bi-speedometer2"></i> Paket</a>
                <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 col-lg-10 main-content">
                <h2><i class="bi bi-clipboard-check"></i> Konfirmasi Pesanan</h2>
                <p class="text-muted">Kelola pesanan pengguna — konfirmasi untuk memvalidasi pembayaran.</p>

                <?php if (isset($_GET['msg']) && $_GET['msg'] == 'confirmed'): ?>
                    <div class="alert alert-success">Pesanan berhasil dikonfirmasi.</div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Paket</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                                <th>Bukti Pembayaran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $order['id'] ?></td>
                                    <td><?= htmlspecialchars($order['username']) ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($order['package_name']) ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($order['destination']) ?></small>
                                    </td>
                                    <td><?= $order['quantity'] ?></td>
                                    <td>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></td>
                                    <td><?= date('d M Y, H:i', strtotime($order['order_date'])) ?></td>
                                    <td>
                                        <?php if ($order['payment_proof']): ?>
                                            <a href="../assets/bukti/<?= htmlspecialchars($order['payment_proof']) ?>"
                                                target="_blank">
                                                <img src="../assets/bukti/<?= htmlspecialchars($order['payment_proof']) ?>"
                                                    class="proof-img" alt="Bukti Pembayaran">
                                            </a>
                                        <?php else: ?>
                                            <small class="no-proof">Belum ada</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($order['admin_confirmed']): ?>
                                            <span class="badge bg-success">Dikonfirmasi</span>
                                        <?php elseif ($order['status'] == 'Cancelled'): ?>
                                            <span class="badge bg-danger text-dark">Dibatalkan</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!$order['admin_confirmed'] && $order['status'] == 'Pending'): ?>
                                            <a href="confirm.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary"
                                                onclick="return confirm('Konfirmasi pesanan ini?')">
                                                <i class="bi bi-check-circle"></i> Konfirmasi
                                            </a>
                                        <?php else: ?>
                                            <small class="text-success">Sudah dikonfirmasi</small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>