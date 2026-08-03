<?php 
include 'Database/koneksi.php'; 
include 'includes/header.php'; 

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$query = "SELECT * FROM products WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$produk = mysqli_fetch_assoc($result);


if(!$produk) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location='explore.php';</script>";
    exit;
}
?>

<div class="mb-3">
    <a href="explore.php" class="btn btn-outline-secondary">⬅ Kembali</a>
</div>

<div class="row mt-5 mb-5">
    <div class="col-md-6 mb-4">
        <div class="bg-light d-flex justify-content-center align-items-center rounded shadow-sm" style="height: 400px; font-size: 5rem; color: #ccc;">
            <img src="uploads/<?= $produk['gambar']; ?>" alt="Gambar Produk" style="object-fit: cover; height: 100%; width: 100%;">
        </div>
    </div>

    <div class="col-md-6">
        <h2 class="fw-bold mb-2"><?= $produk['nama_produk']; ?></h2>
        <h3 class="text-primary fw-bold mb-4">Rp <?= number_format($produk['harga_normal'], 0, ',', '.'); ?></h3>
        
        <p class="text-muted" style="line-height: 1.8;">
            <?= nl2br($produk['deskripsi']); ?>
        </p>

        <div class="mb-4">
            <span class="badge bg-secondary p-2 fs-6">Stok Tersedia: <?= $produk['stok']; ?> pcs</span>
        </div>

        <hr class="mb-4">

        <div class="d-grid gap-2 d-md-flex">
            <a href="kunciHarga.php?id=<?= $produk['id']; ?>" class="btn btn-warning btn-lg px-4 me-md-2 fw-bold text-dark shadow-sm">
                🔒 Minta Kunci Harga
            </a>
            <a href="chat.php?product_id=<?= $produk['id']; ?>&from=detail" class="btn btn-outline-dark btn-lg px-4 fw-bold shadow-sm">
                💬 Chat Supplier
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>