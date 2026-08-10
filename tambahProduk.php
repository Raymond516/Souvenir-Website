<?php
session_start();
include 'Database/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_POST['simpan_produk'])) {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $deskripsi   = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga       = $_POST['harga_normal'];
    $stok        = $_POST['stok'];
    
    $nama_file   = $_FILES['gambar']['name'];
    $ukuran_file = $_FILES['gambar']['size'];
    $tmp_file    = $_FILES['gambar']['tmp_name'];
    $error_file  = $_FILES['gambar']['error'];
    
    if ($error_file === 4) {
        echo "<script>alert('Pilih gambar produk terlebih dahulu!');</script>";
    } else {
        $ekstensi_valid = ['jpg', 'jpeg', 'png'];
        $ekstensi_gambar = explode('.', $nama_file);
        $ekstensi_gambar = strtolower(end($ekstensi_gambar));
        
        if (!in_array($ekstensi_gambar, $ekstensi_valid)) {
            echo "<script>alert('Yang Anda unggah bukan format gambar yang valid (Gunakan JPG/JPEG/PNG)!');</script>";
        } else if ($ukuran_file > 2000000) { // Maksimal 2MB
            echo "<script>alert('Ukuran gambar terlalu besar! Maksimal 2MB.');</script>";
        } else {
            $nama_file_baru = uniqid() . '.' . $ekstensi_gambar;
            $tujuan_upload = 'uploads/' . $nama_file_baru;
            
            // Pindahkan file ke folder uploads
            if (move_uploaded_file($tmp_file, $tujuan_upload)) {
                $query = "INSERT INTO products (nama_produk, deskripsi, harga_normal, stok, gambar) 
                          VALUES ('$nama_produk', '$deskripsi', '$harga', '$stok', '$nama_file_baru')";
                          
                if (mysqli_query($conn, $query)) {
                    echo "<script>
                            alert('Produk berhasil ditambahkan!');
                            window.location='explore.php';
                          </script>";
                    exit;
                } else {
                    echo "<script>alert('Gagal menyimpan data ke database!');</script>";
                }
            } else {
                echo "<script>alert('Gagal mengunggah gambar!');</script>";
            }
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-5 mb-5" style="min-height: 70vh;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="fw-bold mb-0 text-white">📦 Tambah Produk B2B Baru</h5>
                </div>
                <div class="card-body p-4 bg-light">
                    
                    <form action="" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Produk</label>
                            <input type="text" class="form-control" name="nama_produk" placeholder="Contoh: Flashdisk Kartu Custom" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi Produk</label>
                            <textarea class="form-control" name="deskripsi" rows="4" placeholder="Tuliskan spesifikasi, material, dan detail produk..." required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Harga Normal (Rp)</label>
                                <input type="number" class="form-control" name="harga_normal" placeholder="Contoh: 25000" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Stok Tersedia (Pcs)</label>
                                <input type="number" class="form-control" name="stok" placeholder="Contoh: 5000" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Unggah Foto Produk</label>
                            <input class="form-control" type="file" name="gambar" accept=".jpg, .jpeg, .png" required>
                            <small class="text-muted">Format: JPG/PNG. Maksimal ukuran: 2MB.</small>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="dashboardAdmin.php" class="btn btn-outline-secondary px-4">Batal</a>
                            <button type="submit" name="simpan_produk" class="btn btn-primary px-5 fw-bold shadow-sm">Simpan Produk</button>
                        </div>
                        
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>