<?php
session_start();
include 'Database/koneksi.php';

if(isset($_SESSION['user_id'])) {
    if($_SESSION['role'] == 'admin') {
        header("Location: dashboardAdmin.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['nama_perusahaan'] = $row['nama_perusahaan'];

        if($row['role'] == 'admin') {
            header("Location: dashboardAdmin.php");
        } else {
            header("Location: index.php");
        }
    } else {
        echo "<script>alert('Email atau password salah!');</script>";
    }
}

if(isset($_POST['register'])) {
    $nama_perusahaan = $_POST['nama_perusahaan'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $cek_email = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    
    if(mysqli_num_rows($cek_email) > 0) {
        echo "<script>alert('Email sudah terdaftar! Silakan gunakan email lain atau Sign In.');</script>";
    } else {
        $query = "INSERT INTO users (nama_perusahaan, email, password, role) VALUES ('$nama_perusahaan', '$email', '$password', 'user')";
        
        if(mysqli_query($conn, $query)) {
            header("Location: login.php");
            exit;

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Login - TokoPromosi</title>
</head>
<body>
    <div class="background"></div>
    <div class="container">
        <div class="content">
            <h2 class="logo"><i class='bx bxl-graphql'></i>Aneka Sukses Promosi</h2>

            <div class="text-sci">
                <h2>Welcome!<br><span>To Platform Grosir B2B.</span></h2>
                <p>Silakan masuk untuk menemukan harga terbaik dan bernegosiasi dengan supplier kami.</p>
            </div>

            <div class="social-icons">
                <a href="#"><i class='bx bxl-linkedin-square'></i></a>
                <a href="#"><i class='bx bxl-facebook'></i></a>
                <a href="#"><i class='bx bxl-instagram' ></i></a>
                <a href="#"><i class='bx bxl-twitter'></i></a>
            </div>
        </div>

        <div class="logreg-box">
            <div class="form-box login">
                <form action="" method="POST">
                    <h2>Sign In</h2>

                    <div class="input-box">
                        <span class="icon"><i class='bx bx-envelope'></i></span>
                        <input type="email" name="email" required>
                        <label>Email</label>
                    </div>

                    <div class="input-box">
                        <span class="icon"><i class='bx bx-lock-alt'></i></span>
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>

                    <div class="remember-forgot">
                        <label><input type="checkbox">Remember Me</label>
                        <a href="#">Forgot Password</a>
                    </div>

                    <button type="submit" name="login" class="btn">Sign In</button>

                    <div class="login-register">
                        <p>Don't have an account? <a href="#" class="register-link">Sign Up</a></p>
                    </div>
                </form>
            </div>

            <div class="form-box register">
                <form action="" method="POST">
                    <h2>Sign Up</h2>

                    <div class="input-box">
                        <span class="icon"><i class='bx bx-user'></i></span>
                        <input type="text" name="nama_perusahaan" required>
                        <label>Name</label>
                    </div>

                    <div class="input-box">
                        <span class="icon"><i class='bx bx-envelope'></i></span>
                        <input type="email" name="email" required>
                        <label>Email</label>
                    </div>

                    <div class="input-box">
                        <span class="icon"><i class='bx bx-lock-alt'></i></span>
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>

                    <div class="remember-forgot">
                        <label><input type="checkbox" required>I agree to the terms & conditions</label>
                    </div>

                    <button type="submit" name="register" class="btn">Sign Up</button>

                    <div class="login-register">
                        <p>Already have an account? <a href="#" class="login-link">Sign In</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="interactive.js" defer></script>
    <script src="script.js"></script>
</body>
</html>