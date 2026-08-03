<?php 
include 'Database/koneksi.php'; 
include 'includes/header.php'; 
?>

<div class="row mt-4 mb-5">
    <div class="col-12 text-center mb-5">
        <h2 class="fw-bold">Katalog Produk</h2>
        <p class="text-muted">Temukan berbagai produk promosi terbaik untuk perusahaan Anda.</p>
    </div>

    <?php
    $query = "SELECT * FROM products ORDER BY id DESC";
    $result = mysqli_query($conn, $query);

    while($row = mysqli_fetch_assoc($result)) {
    ?>
    
    <div class="col-md-3 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <img src="uploads/<?= $row['gambar']; ?>" class="card-img-top" alt="Gambar Produk" style="object-fit: cover; height: 200px; width: 100%;">
            
            <div class="card-body">
                <h5 class="card-title fw-bold"><?= $row['nama_produk']; ?></h5>
                <h6 class="text-primary fw-bold mb-3">Rp <?= number_format($row['harga_normal'], 0, ',', '.'); ?></h6>
                
                <p class="card-text text-muted small">
                    <?= substr($row['deskripsi'], 0, 50); ?>...
                </p>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="badge bg-secondary">Stok: <?= $row['stok']; ?></span>
                    <a href="detailProduk.php?id=<?= $row['id']; ?>" class="btn btn-outline-dark btn-sm">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>
    
    <?php 
    } // Akhir dari perulangan while 
    ?>
</div>

<?php include 'includes/footer.php'; ?>