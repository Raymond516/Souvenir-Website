<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platform Grosir B2B</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="design.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
    <div class="container">
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <span class="navbar-brand fw-bold" style="cursor: default;">Aneka Sukses Promosi</span>
        <?php else: ?>
            <a class="navbar-brand fw-bold" href="index.php">Aneka Sukses Promosi</a>
        <?php endif; ?>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
        <!-- ================= MENU KHUSUS ADMIN ================= -->
                    <li class="nav-item"><a class="nav-link fw-bold" href="dashboardAdmin.php">Dashboard Admin</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="tambahProduk.php">Tambah Produk</a></li>
                    <li class="nav-item"><a class="nav-link text-danger fw-bold" href="logout.php">Logout</a></li>
                <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] == 'user'): ?>
        <!-- ================= MENU KHUSUS PEMBELI ================= -->
                    <li class="nav-item"><a class="nav-link" href="explore.php">Explore</a></li>
                    <li class="nav-item"><a class="nav-link" href="chatInbox.php">Chat</a></li>
                    <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold text-primary" href="dashboardUser.php">Dashboard Saya</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
                <?php else: ?>
        <!-- ================= MENU GUEST (BELUM LOGIN) ================= -->
                    <li class="nav-item"><a class="nav-link" href="explore.php">Explore</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="login.php">Login / Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container">