<?php
session_start();
include 'Database/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['action']) && isset($_GET['lock_id'])) {
    $lock_id = $_GET['lock_id'];
    $action = $_GET['action'];
    
    $status_baru = ($action == 'approve') ? 'approved' : 'rejected';
    
    $update_query = "UPDATE price_locks SET status = '$status_baru' WHERE id = '$lock_id'";
    if (mysqli_query($conn, $update_query)) {
        header("Location: dashboardAdmin.php");
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui status!');</script>";
    }
}

$query_admin = "SELECT price_locks.*, products.nama_produk, products.harga_normal, users.nama_perusahaan 
                FROM price_locks 
                JOIN products ON price_locks.product_id = products.id 
                JOIN users ON price_locks.user_id = users.id 
                ORDER BY price_locks.id DESC";

$result_admin = mysqli_query($conn, $query_admin);


$query_orders = "SELECT orders.*, users.nama_perusahaan 
                 FROM orders 
                 JOIN users ON orders.user_id = users.id 
                 ORDER BY orders.tanggal_order DESC";

$result_orders = mysqli_query($conn, $query_orders);
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-4 mb-5" style="min-height: 60vh;">
    
    <div class="p-4 mb-4 bg-dark text-white rounded shadow-sm border-start border-4 border-warning">
        <h3 class="fw-bold mb-1">⚙️ Dashboard Admin (Pusat Kendali)</h3>
        <p class="text-light mb-0">Kelola semua negosiasi B2B dan persetujuan harga khusus di sini.</p>
    </div>

    <div class="card border-0 shadow-sm mb-5">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">🔒 Daftar Pengajuan Kunci Harga (Masuk)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Perusahaan Pembeli</th>
                            <th>Produk</th>
                            <th>Kuantitas</th>
                            <th>Harga Pengajuan / Pcs</th>
                            <th>Status Saat Ini</th>
                            <th class="text-center">Aksi (Keputusan)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result_admin) > 0) : ?>
                            <?php while ($row = mysqli_fetch_assoc($result_admin)) : ?>
                                <tr>
                                    <td>#<?= $row['id']; ?></td>
                                    <td class="fw-bold text-primary"><?= htmlspecialchars($row['nama_perusahaan']); ?></td>
                                    <td>
                                        <?= htmlspecialchars($row['nama_produk']); ?><br>
                                        <small class="text-muted">Normal: Rp <?= number_format($row['harga_normal'], 0, ',', '.'); ?></small>
                                    </td>
                                    <td><?= number_format($row['kuantitas'], 0, ',', '.'); ?> pcs</td>
                                    <td class="fw-bold text-danger">Rp <?= number_format($row['harga_pengajuan'], 0, ',', '.'); ?></td>
                                    
                                    <td>
                                        <?php if ($row['status'] == 'pending') : ?>
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        <?php elseif ($row['status'] == 'approved') : ?>
                                            <span class="badge bg-success">Disetujui</span>
                                        <?php elseif ($row['status'] == 'selesai') : ?>
                                            <span class="badge bg-primary">🛍️ Selesai / Dibeli</span>
                                        <?php else : ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="text-center">
                                        <?php if ($row['status'] == 'pending') : ?>
                                            <div class="btn-group" role="group">
                                                <a href="dashboardAdmin.php?action=approve&lock_id=<?= $row['id']; ?>" class="btn btn-sm btn-success fw-bold shadow-sm" onclick="return confirm('Yakin ingin menyetujui harga ini?');">✓ Setujui</a>
                                                <a href="dashboardAdmin.php?action=reject&lock_id=<?= $row['id']; ?>" class="btn btn-sm btn-danger fw-bold shadow-sm" onclick="return confirm('Yakin ingin menolak tawaran ini?');">✕ Tolak</a>
                                            </div>
                                        <?php else : ?>
                                            <span class="text-muted small"><i>Sudah diproses</i></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada pengajuan masuk dari pembeli.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom border-warning">
            <h5 class="fw-bold mb-0">📦 Daftar Pesanan (Order Masuk)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID Order</th>
                            <th>Waktu Pesanan</th>
                            <th>Perusahaan Pembeli</th>
                            <th>Total Tagihan</th>
                            <th>Jenis Pengiriman</th>
                            <th>Status Pembayaran</th>
                            <th class="text-center">Aksi Kelola</th>
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
                                    <td class="fw-bold text-primary"><?= htmlspecialchars($order['nama_perusahaan']); ?></td>
                                    <td class="fw-bold text-success">Rp <?= number_format($order['total_harga'], 0, ',', '.'); ?></td>
                                    <td>
                                        <small><?= empty($order['jenis_pengiriman']) ? '<i>Tidak ada data</i>' : htmlspecialchars($order['jenis_pengiriman']); ?></small>
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
                                    <td class="text-center">
                                        <?php if ($order['status'] == 'selesai') : ?>
                                            <span class="text-muted small"><i>Sudah selesai</i></span>
                                        <?php else : ?>
                                            <a href="kelolaPesanan.php?id=<?= $order['id']; ?>" class="btn btn-sm btn-outline-dark fw-bold">Kelola Pesanan</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada pesanan masuk dari pembeli.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>