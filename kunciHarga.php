<?php 
include 'Database/koneksi.php';
include 'includes/header.php'; 

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$query = "SELECT * FROM products WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$produk = mysqli_fetch_assoc($result);

if(!$produk) {
    echo "<script>alert('Silakan pilih produk terlebih dahulu!'); window.location='explore.php';</script>";
    exit;
}

if(isset($_POST['submit_pengajuan'])) {
    $kuantitas = $_POST['kuantitas'];
    $harga_pengajuan = $_POST['harga_pengajuan'];
    $user_id = $_SESSION['user_id']; 

    $insert_query = "INSERT INTO price_locks (user_id, product_id, kuantitas, harga_pengajuan, status) 
                     VALUES ('$user_id', '$id', '$kuantitas', '$harga_pengajuan', 'pending')";
    
    if(mysqli_query($conn, $insert_query)) {
        header("Location: dashboardUser.php");
        exit;
    } else {
        echo "<script>alert('Gagal mengirim pengajuan!');</script>";
    }
}
?>

<div class="row justify-content-center mt-5 mb-5">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-4">
            <h3 class="fw-bold border-bottom pb-3 mb-4">Minta Kunci Harga (Price Lock)</h3>
            
            <div class="alert alert-info mb-4">
                Anda sedang mengajukan negosiasi harga untuk produk: <br>
                <strong class="fs-5"><?= $produk['nama_produk']; ?></strong>
            </div>

            <form action="" method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Kuantitas Pesanan (Pcs)</label>
                        <input type="number" class="form-control" name="kuantitas" placeholder="Contoh: 1000" required>
                        <div class="form-text">Minimal pesanan B2B adalah 100 pcs.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Harga Pengajuan / Pcs (Rp)</label>
                        <input type="number" class="form-control" name="harga_pengajuan" placeholder="Contoh: <?= $produk['harga_normal'] - 1000; ?>" required>
                        <div class="form-text text-danger">Harga normal saat ini: Rp <?= number_format($produk['harga_normal'], 0, ',', '.'); ?></div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Catatan Tambahan untuk Supplier (Opsional)</label>
                    <textarea class="form-control" name="catatan" rows="3" placeholder="Contoh: Kami butuh produk ini rutin setiap bulan, apakah bisa dapat harga grosir?"></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="detailProduk.php?id=<?= $produk['id']; ?>" class="btn btn-outline-secondary">Batal & Kembali</a>
                    
                    <button type="submit" name="submit_pengajuan" class="btn btn-warning fw-bold text-dark px-4 shadow-sm">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>