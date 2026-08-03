<?php
session_start();
include 'Database/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: dashboardAdmin.php");
    exit;
}

$order_id = $_GET['id'];

if (isset($_POST['update_status'])) {
    $status_baru = $_POST['status'];
    
    $query_update = "UPDATE orders SET status = '$status_baru' WHERE id = '$order_id'";
    if (mysqli_query($conn, $query_update)) {
        $pesan_sukses = "Status pesanan berhasil diperbarui!";
    } else {
        $pesan_error = "Gagal memperbarui status pesanan.";
    }
}

$query_order = "SELECT orders.*, users.nama_perusahaan, users.email 
                FROM orders 
                JOIN users ON orders.user_id = users.id 
                WHERE orders.id = '$order_id'";
$result_order = mysqli_query($conn, $query_order);
$order = mysqli_fetch_assoc($result_order);

if (!$order) {
    echo "<script>alert('Pesanan tidak ditemukan!'); window.location='dashboardAdmin.php';</script>";
    exit;
}
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-4 mb-5" style="min-height: 60vh;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Kelola Pesanan #ORD-<?= $order['id']; ?></h3>
        <a href="dashboardAdmin.php" class="btn btn-outline-secondary">⬅ Kembali ke Dashboard</a>
    </div>

    <?php if (isset($pesan_sukses)) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> <?= $pesan_sukses; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Informasi Pemesan & Pengiriman</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Perusahaan Pemesan</div>
                        <div class="col-sm-8 fw-bold text-primary"><?= htmlspecialchars($order['nama_perusahaan']); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Kontak (Email)</div>
                        <div class="col-sm-8"><?= htmlspecialchars($order['email']); ?></div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Tanggal Order</div>
                        <div class="col-sm-8"><?= date('d F Y, H:i', strtotime($order['tanggal_order'])); ?> WIB</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Alamat Pengiriman</div>
                        <div class="col-sm-8"><?= nl2br(htmlspecialchars($order['alamat_pengiriman'])); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Jenis Pengiriman</div>
                        <div class="col-sm-8"><?= htmlspecialchars($order['jenis_pengiriman'] ?: 'Belum ditentukan'); ?></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4 text-muted fs-5">Total Tagihan</div>
                        <div class="col-sm-8 fw-bold fs-5 text-success">Rp <?= number_format($order['total_harga'], 0, ',', '.'); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-top border-4 border-warning">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Update Status</h5>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label text-muted">Status Pesanan Saat Ini</label>
                            <select name="status" class="form-select fw-bold" required>
                                <option value="menunggu_pembayaran" <?= ($order['status'] == 'menunggu_pembayaran') ? 'selected' : ''; ?>>⏳ Menunggu Pembayaran</option>
                                <option value="diproses" <?= ($order['status'] == 'diproses') ? 'selected' : ''; ?>>⚙️ Sedang Diproses</option>
                                <option value="dikirim" <?= ($order['status'] == 'dikirim') ? 'selected' : ''; ?>>🚚 Sedang Dikirim</option>
                                <option value="selesai" <?= ($order['status'] == 'selesai') ? 'selected' : ''; ?>>✅ Selesai</option>
                            </select>
                        </div>
                        <button type="submit" name="update_status" class="btn btn-warning w-100 fw-bold">Simpan Perubahan Status</button>
                    </form>
                    
                    <div class="mt-4 p-3 bg-light rounded text-center small text-muted">
                        <p class="mb-0">Pastikan pembayaran sudah divalidasi sebelum mengubah status menjadi <b>Sedang Diproses</b>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>