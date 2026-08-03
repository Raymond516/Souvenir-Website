<?php
session_start();
include 'Database/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$nama_perusahaan = isset($_SESSION['nama_perusahaan']) ? $_SESSION['nama_perusahaan'] : 'Pembeli';

$query_locks = "SELECT price_locks.*, products.nama_produk, products.harga_normal 
                FROM price_locks 
                JOIN products ON price_locks.product_id = products.id 
                WHERE price_locks.user_id = '$user_id' 
                ORDER BY price_locks.id DESC";
$result_locks = mysqli_query($conn, $query_locks);

$query_orders = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY tanggal_order DESC";
$result_orders = mysqli_query($conn, $query_orders);
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-4 mb-5">
    
    <div class="p-4 mb-4 bg-white rounded shadow-sm border-start border-4 border-warning">
        <h3 class="fw-bold mb-1">Selamat Datang, <?= htmlspecialchars($nama_perusahaan); ?>! 👋</h3>
        <p class="text-muted mb-0">Pantau status negosiasi harga (Price Lock) dan riwayat pesanan B2B Anda di sini.</p>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">🔒 Status Pengajuan Kunci Harga (Price Lock)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Harga Normal</th>
                            <th>Kuantitas</th>
                            <th>Harga Pengajuan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result_locks) > 0) : ?>
                            <?php while ($row = mysqli_fetch_assoc($result_locks)) : ?>
                                <tr>
                                    <td class="fw-bold"><?= htmlspecialchars($row['nama_produk']); ?></td>
                                    <td class="text-muted">Rp <?= number_format($row['harga_normal'], 0, ',', '.'); ?></td>
                                    <td><?= number_format($row['kuantitas'], 0, ',', '.'); ?> pcs</td>
                                    <td class="text-primary fw-bold">Rp <?= number_format($row['harga_pengajuan'], 0, ',', '.'); ?></td>
                                    <td>
                                        <?php if ($row['status'] == 'pending') : ?>
                                            <span class="badge bg-warning text-dark">⏳ Menunggu Supplier</span>
                                        <?php elseif ($row['status'] == 'approved') : ?>
                                            <span class="badge bg-success">✅ Disetujui</span>
                                        <?php elseif ($row['status'] == 'selesai') : ?>
                                            <span class="badge bg-primary">🛍️ Sudah Dibeli</span>
                                        <?php else : ?>
                                            <span class="badge bg-danger">❌ Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] == 'approved') : ?>
                                            <a href="cart.php?add_lock=<?= $row['id']; ?>" class="btn btn-sm btn-success fw-bold">
                                                🛒 Masukkan Keranjang
                                            </a>
                                        <?php elseif ($row['status'] == 'selesai') : ?>
                                            <button class="btn btn-sm btn-outline-primary" disabled>Transaksi Selesai</button>
                                        <?php else : ?>
                                            <button class="btn btn-sm btn-secondary" disabled>Belum Tersedia</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Belum ada pengajuan kunci harga. Silakan eksplor produk di <a href="explore.php" class="fw-bold text-decoration-none">Katalog Explore</a>.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom border-primary">
            <h5 class="fw-bold mb-0">📦 Riwayat Pesanan Anda</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID Order</th>
                            <th>Tanggal Pemesanan</th>
                            <th>Total Tagihan</th>
                            <th>Alamat Pengiriman</th>
                            <th>Status Terkini</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result_orders) > 0) : ?>
                            <?php while ($order = mysqli_fetch_assoc($result_orders)) : ?>
                                <tr>
                                    <td class="fw-bold">#ORD-<?= $order['id']; ?></td>
                                    <td>
                                        <?= date('d M Y', strtotime($order['tanggal_order'])); ?><br>
                                        <small class="text-muted"><?= date('H:i', strtotime($order['tanggal_order'])); ?> WIB</small>
                                    </td>
                                    <td class="fw-bold text-success">Rp <?= number_format($order['total_harga'], 0, ',', '.'); ?></td>
                                    <td>
                                        <small><?= htmlspecialchars(substr($order['alamat_pengiriman'], 0, 40)); ?>...</small>
                                    </td>
                                    <td>
                                        <?php if ($order['status'] == 'menunggu_pembayaran') : ?>
                                            <span class="badge bg-warning text-dark">⏳ Menunggu Pembayaran</span>
                                        <?php elseif ($order['status'] == 'diproses') : ?>
                                            <span class="badge bg-info text-dark">⚙️ Sedang Diproses</span>
                                        <?php elseif ($order['status'] == 'dikirim') : ?>
                                            <span class="badge bg-primary">🚚 Sedang Dikirim</span>
                                        <?php elseif ($order['status'] == 'selesai') : ?>
                                            <span class="badge bg-success">✅ Selesai</span>
                                        <?php else : ?>
                                            <span class="badge bg-secondary"><?= htmlspecialchars($order['status']); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Anda belum melakukan checkout pesanan.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>