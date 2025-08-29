<?php
include 'config.php';
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];


    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows() > 0) {
        $stmt->bind_result($id, $hashed);
        $stmt->fetch();


        if (password_verify($password, $hashed)) {
            $_SESSION['user_id'] = $id;
            header("Location: index.php");
            exit();
        } else {
            $message = "Password salah.";
        }
    } else {
        $message = "Email tidak ditemukan.";
    }


    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="stlyez.css" media="screen" title="none">
    <link rel="stylesheet" href="fontawesome-free-6.2.1-web/css/all.css">

    <title>Life Tours And Travel</title>
    <style>
        .error {
            background: #ff6666;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        .btn-back {
            display: inline-block;
            width: 100%;
            padding: 12px;
            background-color: #6c757d;
            /* Warna abu-abu netral */
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
            border: none;
        }

        .btn-back:hover {
            background-color: #5a6268;
            text-decoration: none;
        }

        .btn-back i {
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <div class="taroh">
        <div class="input">
            <h1>Login Here</h1>


            <?php if (!empty($message)): ?>
                <div class="error">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="box-input">
                    <i class="fas fa-envelope-open-text"></i>
                    <input type="text" name="email" placeholder="Email"
                        value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>
                <div class="box-input">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password">
                </div>
                <button type="submit" name="login" class="btn-input">LOGIN</button>
                <div class="bottom">
                    <p>Not having an account?
                        <a href="register.html">Create an account</a>
                    </p>
                </div>
                <div>
                    <a href="index.php" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>