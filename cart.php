<?php
session_start();
include 'Database/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location='login.php';</script>";
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (isset($_GET['add_lock'])) {
    $lock_id = $_GET['add_lock'];
    $user_id = $_SESSION['user_id'];

    $query = "SELECT price_locks.*, products.nama_produk 
              FROM price_locks 
              JOIN products ON price_locks.product_id = products.id 
              WHERE price_locks.id = '$lock_id' AND price_locks.user_id = '$user_id' AND price_locks.status = 'approved'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);

        $item = array(
            'lock_id' => $data['id'],
            'product_id' => $data['product_id'],
            'nama_produk' => $data['nama_produk'],
            'harga_final' => $data['harga_pengajuan'],
            'kuantitas' => $data['kuantitas']
        );

        $sudah_ada = false;
        foreach ($_SESSION['cart'] as $key => $cart_item) {
            if ($cart_item['lock_id'] == $lock_id) {
                $sudah_ada = true;
                break;
            }
        }

        if (!$sudah_ada) {
            $_SESSION['cart'][] = $item;
        }

        header("Location: cart.php");
        exit;
    }
}


if (isset($_GET['remove'])) {
    $index = $_GET['remove'];
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);

        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    header("Location: cart.php");
    exit;
}
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-4 mb-5" style="min-height: 60vh;">
    <h3 class="fw-bold mb-4">🛒 Keranjang Belanja B2B</h3>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Produk</th>
                                    <th>Kuantitas</th>
                                    <th>Harga / Pcs</th>
                                    <th>Subtotal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total_belanja = 0;
                                if (!empty($_SESSION['cart'])) : 
                                ?>
                                    <?php foreach ($_SESSION['cart'] as $index => $item) : 
                                        $subtotal = $item['harga_final'] * $item['kuantitas'];
                                        $total_belanja += $subtotal;
                                    ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-primary">
                                                <?= htmlspecialchars($item['nama_produk']); ?><br>
                                                <small class="text-muted fw-normal">Dari Pengajuan #<?= $item['lock_id']; ?></small>
                                            </td>
                                            <td><?= number_format($item['kuantitas'], 0, ',', '.'); ?> pcs</td>
                                            <td>Rp <?= number_format($item['harga_final'], 0, ',', '.'); ?></td>
                                            <td class="fw-bold">Rp <?= number_format($subtotal, 0, ',', '.'); ?></td>
                                            <td class="text-center">
                                                <a href="cart.php?remove=<?= $index; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus item ini dari keranjang?');">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            Keranjang Anda masih kosong.<br>
                                            <a href="dashboardUser.php" class="btn btn-outline-primary mt-3">Cek Pengajuan Disetujui</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm mt-3 mt-md-0">
                <div class="card-body">
                    <h5 class="fw-bold border-bottom pb-3">Ringkasan Pesanan</h5>
                    <div class="d-flex justify-content-between mb-3 mt-3">
                        <span class="text-muted">Total Tagihan</span>
                        <span class="fw-bold fs-5 text-danger">Rp <?= number_format($total_belanja, 0, ',', '.'); ?></span>
                    </div>
                    <hr>
                    
                    <?php if (!empty($_SESSION['cart'])) : ?>
                        <form action="checkout.php" method="POST">
                            <button type="submit" name="checkout" class="btn btn-warning w-100 fw-bold shadow-sm py-2">
                                Lanjutkan Checkout
                            </button>
                        </form>
                    <?php else : ?>
                        <button class="btn btn-secondary w-100 fw-bold py-2" disabled>Keranjang Kosong</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>