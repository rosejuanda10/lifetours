<?php
include 'config.php';

$message = '';
$message_type = ''; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

  
    if (empty($username) || empty($email) || empty($password)) {
        $message = "Semua field wajib diisi.";
        $message_type = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Format email tidak valid.";
        $message_type = "error";
    } elseif (strlen($password) < 6) {
        $message = "Password minimal 6 karakter.";
        $message_type = "error";
    } else {
        
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows() > 0) {
            $message = "Email sudah terdaftar. Gunakan email lain atau <a href='login.php'>login di sini</a>.";
            $message_type = "error";
        } else {
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

           
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                $message = "Pendaftaran berhasil! <a href='login.php'>Klik di sini untuk login</a>.";
                $message_type = "success";
                // Kosongkan input setelah sukses
                $username = $email = '';
            } else {
                $message = "Terjadi kesalahan. Coba lagi nanti.";
                $message_type = "error";
            }
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="stlyez.css" media="screen">
    <link rel="stylesheet" href="fontawesome-free-6.2.1-web/css/all.css">
    <title>Life Tours And Travel - Register</title>
    <style>
        .success {
            background: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
        .error {
            background: #f44336;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="taroh">
        <div class="input">
            <h1>Register Here</h1>

            <!-- Tampilkan pesan -->
            <?php if (!empty($message)): ?>
                <div class="<?= $message_type ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="box-input">
                    <i class="fas fa-user"></i>
                    <input type="text" name="fullname" placeholder="Full Name" 
                           value="<?= isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : '' ?>">
                </div>
                <div class="box-input">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Username" 
                           value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                </div>
                <div class="box-input">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" 
                           value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>
                <div class="box-input">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password">
                </div>

                <!-- Perbaikan: button tidak boleh di dalam <a> -->
                <button type="submit" class="btn-input">Create account</button>

                <div class="bottom">
                    <p>Already have an account? 
                        <a href="login.php">Login here</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>