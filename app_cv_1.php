<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Cek halaman yang sedang diakses
$page = isset($_GET['page']) ? $_GET['page'] : 'login';
$error = "";

// Proses Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && $page == "login") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Ambil domain email
    $emailParts = explode("@", $email);
    if (count($emailParts) === 2) {
        $emailDomain = $emailParts[1]; // Ambil domain setelah '@'
    } else {
        $emailDomain = "";
    }

    if ($password === $emailDomain) { // Password harus sesuai domain email
        $_SESSION['email'] = $email;
        header("Location: app_cv_1.php?page=form");
        exit();
    } else {
        $error = "Password yang Anda masukkan salah";
    }
}

// Proses Input Form CV
if ($_SERVER["REQUEST_METHOD"] == "POST" && $page == "form") {
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['dob'] = $_POST['dob'];
    $_SESSION['education'] = $_POST['education'];
    $_SESSION['skills'] = $_POST['skills'];
    $_SESSION['phone'] = $_POST['phone'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['bio'] = $_POST['bio'];
    $_SESSION['description'] = $_POST['description'];
    header("Location: app_cv_1.php?page=cv");
    exit();
}

// Proses Logout
if ($page == "logout") {
    session_destroy();
    header("Location: app_cv_1.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi CV</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f9;
        }
        .container {
            width: 210mm;
            min-height: 297mm;
            background-color: #ffffff;
            border: 10px solid #40e0d0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            padding: 20px;
        }
        .header {
            background-color: #40e0d0;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5em;
        }
        .header p {
            font-size: 1.2em;
        }
        .section {
            padding: 20px;
            margin-bottom: 20px;
        }
        .section h2 {
            font-size: 1.8em;
            margin-bottom: 10px;
            color: #40e0d0;
            border-bottom: 1px solid #40e0d0;
            padding-bottom: 5px;
        }
        .section p {
            color: #333333;
            font-size: 1em;
            text-align: justify;
        }
        .section ul {
            list-style-type: none;
            padding-left: 0;
        }
        .section ul li::before {
            content: "•";
            color: #40e0d0;
            margin-right: 10px;
        }
        input, textarea, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($page == "login"): ?>
            <h2>Login</h2>
            <form method="post">
                <input type="email" name="email" placeholder="Masukkan Email" required>
                <input type="password" name="password" placeholder="Masukkan Password (Domain Email)" required>
                <button type="submit">Login</button>
            </form>
            <?php if ($error): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>

        <?php elseif ($page == "form" && isset($_SESSION['email'])): ?>
            <h2>Form CV</h2>
            <form method="post">
                <input type="text" name="name" placeholder="Nama Lengkap" required>
                <input type="date" name="dob" required>
                <input type="text" name="education" placeholder="Riwayat Pendidikan" required>
                <input type="text" name="skills" placeholder="Keterampilan (Pisahkan dengan koma)" required>
                <input type="text" name="phone" placeholder="Nomor Telepon" required>
                <input type="email" name="email" placeholder="Email" required>
                <textarea name="bio" placeholder="Biografi Tambahan" required></textarea>
                <textarea name="description" placeholder="Deskripsi Singkat" required></textarea>
                <button type="submit">Simpan CV</button>
            </form>

        <?php elseif ($page == "cv" && isset($_SESSION['email']) && isset($_SESSION['name'])): ?>
            <div class="header">
                <h1><?= $_SESSION['name'] ?></h1>
                <p><?= $_SESSION['description'] ?></p>
            </div>

            <div class="section">
                <h2>Biografi</h2>
                <p>Saya lahir pada <?= $_SESSION['dob'] ?> dan memiliki latar belakang pendidikan <?= $_SESSION['education'] ?>.</p>
                <p><?= $_SESSION['bio'] ?></p>
            </div>

            <div class="section">
                <h2>Keterampilan</h2>
                <ul>
                    <?php
                    $skills = explode(',', $_SESSION['skills']);
                    foreach ($skills as $skill) {
                        echo "<li>" . trim($skill) . "</li>";
                    }
                    ?>
                </ul>
            </div>

            <div class="section">
                <h2>Kontak</h2>
                <p>Email: <a href="mailto:<?= $_SESSION['email'] ?>"><?= $_SESSION['email'] ?></a></p>
                <p>Telepon: <?= $_SESSION['phone'] ?></p>
            </div>

            <a href="app_cv_1.php?page=logout"><button>Logout</button></a>

        <?php else: ?>
            <p>Akses tidak diizinkan.</p>
            <a href="app_cv_1.php"><button>Kembali ke Login</button></a>
        <?php endif; ?>
    </div>
</body>
</html>
