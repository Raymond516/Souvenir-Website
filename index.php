<?php
session_start();
include 'Database/koneksi.php';

$query_products = "SELECT * FROM products ORDER BY id DESC";
$result_products = mysqli_query($conn, $query_products);
?>

<?php include 'includes/header.php'; ?>

<link rel="stylesheet" href="design.css">

<section class="hero-banner py-5">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-white text-primary px-3 py-2 fw-bold mb-3 shadow-sm">🚀 Platform B2B Merchant & Promosi</span>
                <h1 class="display-4 fw-bold mb-3 text-white">Temukan Produk Promosi & Merchandise Terbaik</h1>
                <p class="lead text-white-50 mb-4">Solusi pengadaan barang promosi bisnis Anda dengan fitur <strong>Kunci Harga (Price Lock)</strong> langsung dari supplier resmi.</p>
                <a href="#katalog" class="btn btn-warning btn-lg fw-bold px-4 shadow-sm">🔍 Jelajahi Produk</a>
            </div>
        </div>
    </div>
</section>

<section id="katalog" class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">📦 Katalog Produk Promosi</h3>
                <p class="text-muted mb-0">Pilih produk dan ajukan penawaran harga grosir terbaik Anda.</p>
            </div>
        </div>

        <div class="row g-4">
            <?php if (mysqli_num_rows($result_products) > 0) : ?>
                <?php while ($produk = mysqli_fetch_assoc($result_products)) : ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm card-interactive rounded-4">
                            <img src="Uploads/<?= !empty($produk['gambar']) ? $produk['gambar'] : 'default.jpg'; ?>" 
                                 class="card-img-top rounded-top-4" 
                                 alt="<?= htmlspecialchars($produk['nama_produk']); ?>" 
                                 style="height: 200px; object-fit: cover;"
                                 onerror="this.src='https://via.placeholder.com/300x200?text=Produk+Promosi'">
                            
                            <div class="card-body d-flex flex-column p-4">
                                <h6 class="card-title fw-bold mb-2 text-dark"><?= htmlspecialchars($produk['nama_produk']); ?></h6>
                                <p class="text-primary fw-bold fs-5 mb-3">Rp <?= number_format($produk['harga_normal'], 0, ',', '.'); ?></p>
                                
                                <div class="mt-auto">
                                    <a href="detailProduk.php?id=<?= $produk['id']; ?>" class="btn btn-outline-primary w-100 rounded-3 fw-semibold mb-2">
                                        👁️ Lihat Detail
                                    </a>
                                    <a href="Chat.php?product_id=<?= $produk['id']; ?>&from=detail" class="btn btn-light text-primary border w-100 rounded-3 fw-semibold">
                                        💬 Chat Supplier
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm border">
                    <p class="text-muted mb-0">Belum ada produk yang tersedia saat ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="bg-white py-5 border-top border-bottom">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h3 class="fw-bold">💡 Mengapa Bertransaksi di Platform Kami?</h3>
            <p class="text-muted">Kemudahan pengadaan barang promosi bisnis dalam satu genggaman</p>
        </div>

        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-4 bg-light rounded-4 shadow-sm h-100 border-0">
                    <div class="icon-box bg-primary text-white mb-3 shadow-sm">🔒</div>
                    <h5 class="fw-bold mb-2">Fitur Kunci Harga</h5>
                    <p class="text-muted small mb-0">Ajukan negosiasi harga khusus (Price Lock) langsung ke supplier untuk pembelian partai besar tanpa khawatir naik harga.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-light rounded-4 shadow-sm h-100 border-0">
                    <div class="icon-box bg-success text-white mb-3 shadow-sm">🎨</div>
                    <h5 class="fw-bold mb-2">Custom Branding & Logo</h5>
                    <p class="text-muted small mb-0">Hampir semua produk dapat disesuaikan dengan logo instansi, event, atau identitas perusahaan Anda secara presisi.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-light rounded-4 shadow-sm h-100 border-0">
                    <div class="icon-box bg-warning text-white mb-3 shadow-sm">🚚</div>
                    <h5 class="fw-bold mb-2">Pengiriman Terintegrasi</h5>
                    <p class="text-muted small mb-0">Pantau status pesanan dan pengiriman barang B2B Anda secara real-time langsung dari Dashboard akun Anda.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">📸 Galeri & Template Merchandise Popular</h3>
        </div>

        <div id="templateCarousel" class="carousel slide shadow rounded-4 overflow-hidden border" data-bs-ride="carousel" data-bs-interval="3500">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#templateCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#templateCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#templateCarousel" data-bs-slide-to="2"></button>
            </div>
            
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1200&q=80" class="d-block w-100" alt="Seminar Kit">
                    <div class="carousel-caption text-start mb-3">
                        <span class="badge bg-warning text-dark mb-2">Populer</span>
                        <h4 class="fw-bold">Paket Seminar Kit Perusahaan</h4>
                        <p class="mb-0">Kombinasi tumbler, notebook premium, dan pulpen custom logo siap mendukung kelancaran event Anda.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=1200&q=80" class="d-block w-100" alt="Kaos Custom">
                    <div class="carousel-caption text-start mb-3">
                        <span class="badge bg-primary mb-2">Best Seller</span>
                        <h4 class="fw-bold">Tumbler dan termos premium</h4>
                        <p class="mb-0">Bahan premium dengan sablon berkualitas tinggi, cocok untuk gathering dan merchandise.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1513151233558-d860c5398176?auto=format&fit=crop&w=1200&q=80" class="d-block w-100" alt="Souvenir Eksklusif">
                    <div class="carousel-caption text-start mb-3">
                        <span class="badge bg-success mb-2">Eksklusif</span>
                        <h4 class="fw-bold">Souvenir & Gift Box Premium</h4>
                        <p class="mb-0">Berikan kesan mendalam untuk klien VIP Anda dengan packaging gift box mewah.</p>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#templateCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#templateCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
</section>

<section class="bg-white py-5 border-top border-bottom">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h3 class="fw-bold">💬 Apa Kata Pelanggan Kami?</h3>
            <p class="text-muted">Kepercayaan dari berbagai instansi & perusahaan B2B</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4 bg-light rounded-4 h-100 border-0 shadow-sm">
                    <div class="text-warning mb-2">⭐⭐⭐⭐⭐</div>
                    <p class="fst-italic text-secondary small">"Fitur Kunci Harga sangat membantu efisiensi anggaran event tahunan kami. Respon supplier cepat dan pengiriman tepat waktu!"</p>
                    <div class="d-flex align-items-center mt-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 40px; height: 40px;">B</div>
                        <div>
                            <h6 class="mb-0 fw-bold">Budi Santoso</h6>
                            <small class="text-muted">Event Organizer Jakarta</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-light rounded-4 h-100 border-0 shadow-sm">
                    <div class="text-warning mb-2">⭐⭐⭐⭐⭐</div>
                    <p class="fst-italic text-secondary small">"Sangat praktis! Bisa diskusi custom logo produk langsung di halaman chat tanpa ribet pindah aplikasi lain."</p>
                    <div class="d-flex align-items-center mt-3">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 40px; height: 40px;">S</div>
                        <div>
                            <h6 class="mb-0 fw-bold">Siti Rahmawati</h6>
                            <small class="text-muted">Procurement PT Jaya Mandiri</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-light rounded-4 h-100 border-0 shadow-sm">
                    <div class="text-warning mb-2">⭐⭐⭐⭐⭐</div>
                    <p class="fst-italic text-secondary small">"Kualitas merchandise tumblernya bagus sekali. Hasil cetak logo presisi dan pengerjaannya tergolong sangat cepat."</p>
                    <div class="d-flex align-items-center mt-3">
                        <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 40px; height: 40px;">A</div>
                        <div>
                            <h6 class="mb-0 fw-bold">Andi Wijaya</h6>
                            <small class="text-muted">HR Corporate Manager</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-primary text-white py-5">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <h3 class="fw-bold mb-3 text-white">📬 Ada Pertanyaan atau Butuh Bantuan?</h3>
                <p class="text-white-50 mb-4">Tim layanan pelanggan kami siap membantu Anda memilih merchandise terbaik untuk kebutuhan promosi bisnis Anda.</p>
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex align-items-center"><span class="fs-5 me-3">📍</span> Jakarta, Indonesia</div>
                    <div class="d-flex align-items-center"><span class="fs-5 me-3">✉️</span> support@AnekaSuksesPromosi.com</div>
                    <div class="d-flex align-items-center"><span class="fs-5 me-3">📞</span> +62 812-3456-7890</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-white text-dark p-4 rounded-4 shadow">
                    <h5 class="fw-bold mb-3">Kirim Pesan Singkat</h5>
                    <form onsubmit="event.preventDefault(); alert('Terima kasih! Pesan Anda telah terkirim.');">
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Nama Lengkap / Perusahaan" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Alamat Email" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" placeholder="Tuliskan kebutuhan atau pertanyaan Anda..." rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>