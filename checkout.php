<?php
session_start();
include 'Database/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$total_tagihan = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_tagihan += ($item['harga_final'] * $item['kuantitas']);
}

if (isset($_POST['buat_pesanan'])) {
    $user_id = $_SESSION['user_id'];
    $alamat = $_POST['alamat_pengiriman'];
    $jenis_pengiriman = $_POST['jenis_pengiriman'];
    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date('Y-m-d H:i:s');
    
    $query_order = "INSERT INTO orders (user_id, tanggal_order, total_harga, alamat_pengiriman, jenis_pengiriman, status) 
                    VALUES ('$user_id', '$tanggal', '$total_tagihan', '$alamat', '$jenis_pengiriman', 'menunggu_pembayaran')";
    
    if (mysqli_query($conn, $query_order)) {
        $order_id = mysqli_insert_id($conn);
        
        foreach ($_SESSION['cart'] as $item) {
            $product_id = $item['product_id'];
            $qty = $item['kuantitas'];
            $harga = $item['harga_final'];
            $subtotal = $qty * $harga;
            $lock_id = $item['lock_id'];
            
            $query_item = "INSERT INTO order_items (order_id, product_id, kuantitas, harga_satuan, subtotal) 
                           VALUES ('$order_id', '$product_id', '$qty', '$harga', '$subtotal')";
            mysqli_query($conn, $query_item);

            mysqli_query($conn, "UPDATE price_locks SET status = 'selesai' WHERE id = '$lock_id'");
        }
        
        unset($_SESSION['cart']);
        
        echo "<script>
                alert('Pesanan berhasil dibuat! Silakan lakukan pembayaran sesuai instruksi.'); 
                window.location='dashboardUser.php';
              </script>";
        exit;
    } else {
        echo "<script>alert('Gagal membuat pesanan!');</script>";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-4 mb-5">
    <div class="mb-4">
        <a href="cart.php" class="btn btn-outline-secondary">⬅ Kembali ke Keranjang</a>
    </div>

    <h3 class="fw-bold mb-4">Penyelesaian Pesanan (Checkout)</h3>

    <div class="row">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">📍 Informasi Pengiriman</h5>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Lengkap Perusahaan / Gudang</label>
                            <textarea class="form-control" name="alamat_pengiriman" rows="3" placeholder="Tuliskan alamat lengkap beserta kode pos..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Jenis Pengiriman / Logistik</label>
                            <select class="form-select" name="jenis_pengiriman" required>
                                <option value="" selected disabled>-- Pilih Metode Pengiriman --</option>
                                <option value="Ambil Sendiri di Gudang Supplier">Ambil Sendiri di Gudang Supplier</option>
                                <option value="Kurir Internal / Armada Toko">Kurir Internal / Armada Toko</option>
                                <option value="Ekspedisi Kargo Darat (Indah/JNE Trucking)">Ekspedisi Kargo Darat (Rekanan)</option>
                                <option value="Kontainer / Kargo Laut (Skala Besar)">Kontainer / Kargo Laut (Skala Besar)</option>
                            </select>
                        </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">💳 Instruksi Pembayaran (Manual)</h5>
                </div>
                <div class="card-body bg-light">
                    <p class="mb-2">Silakan transfer sesuai total tagihan ke rekening perusahaan kami:</p>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-1"><strong>Bank BCA:</strong> 1234567890</li>
                        <li class="mb-1"><strong>Atas Nama:</strong> PT Aneka Sukses Promosi</li>
                        <li><strong>Catatan:</strong> Pesanan Anda akan diproses setelah kami memvalidasi mutasi pembayaran.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">📋 Ringkasan Pesanan</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($_SESSION['cart'] as $item) : ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <h6 class="my-0 fw-bold"><?= htmlspecialchars($item['nama_produk']); ?></h6>
                                    <small class="text-muted"><?= number_format($item['kuantitas'], 0, ',', '.'); ?> pcs x Rp <?= number_format($item['harga_final'], 0, ',', '.'); ?></small>
                                </div>
                                <span class="text-muted">Rp <?= number_format($item['kuantitas'] * $item['harga_final'], 0, ',', '.'); ?></span>
                            </li>
                        <?php endforeach; ?>
                        
                        <li class="list-group-item d-flex justify-content-between bg-light py-3">
                            <span class="fw-bold">Total Tagihan</span>
                            <strong class="text-danger fs-5">Rp <?= number_format($total_tagihan, 0, ',', '.'); ?></strong>
                        </li>
                    </ul>
                </div>
                <div class="card-footer bg-white border-0 py-3">
                        <button type="submit" name="buat_pesanan" class="btn btn-warning w-100 fw-bold py-2 shadow-sm">
                            Konfirmasi & Buat Pesanan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>