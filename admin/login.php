<?php
session_start();

// Jika sudah login, redirect ke dashboard admin
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Hardcoded admin (bisa diganti dengan database nanti)
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: index.php");
        exit();
    } else {
        $error = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | LIFE TOURS</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: url('../assets/img/hero-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Open Sans', sans-serif;
        }

        .login-container {
            max-width: 400px;
            margin: 10% auto;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .logo img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }

        .logo h3 {
            margin-top: 0.5rem;
            color: #000;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="logo">
            <img src="../lifetour.jpg" alt="Logo">
            <h3>Admin Panel</h3>
        </div>

        <h4 class="text-center mb-4">Masuk sebagai Admin</h4>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-dark">Masuk</button>
            </div>
        </form>

        <hr>
        <p class="text-center text-muted" style="font-size: 0.9em;">
            <strong>Catatan:</strong> Username: <code>admin</code>, Password: <code>admin123</code>
        </p>
    </div>
</body>

</html>