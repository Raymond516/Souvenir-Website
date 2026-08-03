<?php 
session_start();
include 'Database/koneksi.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : '';
$from = isset($_GET['from']) ? $_GET['from'] : 'inbox';

$nama_produk = "Umum"; 
$nama_supplier = "Supplier Pusat";

if ($product_id != '') {
    $query = "SELECT * FROM products WHERE id = '$product_id'";
    $result = mysqli_query($conn, $query);
    if ($produk = mysqli_fetch_assoc($result)) {
        $nama_produk = $produk['nama_produk'];
        
        if (isset($produk['nama_supplier'])) {
            $nama_supplier = $produk['nama_supplier'];
        } else {
            $nama_supplier = "Pemilik " . $nama_produk;
        }
    }
} else {
    $product_id = 'NULL'; 
}

if (isset($_POST['kirim_pesan']) && !empty(trim($_POST['pesan']))) {
    $pesan = mysqli_real_escape_string($conn, $_POST['pesan']);
    
    $query_insert = "INSERT INTO messages (user_id, product_id, pesan, pengirim) 
                     VALUES ('$user_id', $product_id, '$pesan', 'user')";
    mysqli_query($conn, $query_insert);
    
    $from_param = ($from == 'detail') ? "&from=detail" : "";
    $redirect_url = ($product_id != 'NULL') ? "Chat.php?product_id=$product_id$from_param" : "Chat.php";
    header("Location: $redirect_url");
    exit;
}

if ($product_id == 'NULL') {
    $query_chat = "SELECT * FROM messages WHERE user_id = '$user_id' AND product_id IS NULL ORDER BY waktu ASC";
} else {
    $query_chat = "SELECT * FROM messages WHERE user_id = '$user_id' AND product_id = '$product_id' ORDER BY waktu ASC";
}
$result_chat = mysqli_query($conn, $query_chat);
?>

<?php include 'includes/header.php'; ?>

<div class="row justify-content-center mt-4 mb-5">
    <div class="col-md-8">
        
        <div class="mb-3">
            <?php if($from == 'detail' && $product_id != 'NULL'): ?>
                <a href="detailProduk.php?id=<?= $product_id; ?>" class="btn btn-outline-secondary">⬅ Kembali ke Detail Produk</a>
            <?php else: ?>
                <a href="chatInbox.php" class="btn btn-outline-secondary">⬅ Kembali</a>
            <?php endif; ?>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-white text-primary rounded-circle d-flex justify-content-center align-items-center me-3 fw-bold" style="width: 40px; height: 40px;">
                        👤
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold"><?= htmlspecialchars($nama_supplier); ?></h6>
                        <small class="text-light">Online</small>
                    </div>
                </div>
            </div>
            
            <div class="card-body bg-light" style="height: 400px; overflow-y: auto; display: flex; flex-direction: column;">
                
                <?php if(isset($_GET['product_id']) && $_GET['product_id'] != ''): ?>
                <div class="text-center mb-4">
                    <span class="badge bg-secondary px-3 py-2">Diskusi terkait: <?= htmlspecialchars($nama_produk); ?></span>
                </div>
                <?php endif; ?>

                <?php if (mysqli_num_rows($result_chat) > 0) : ?>
                    <?php while ($chat = mysqli_fetch_assoc($result_chat)) : ?>
                        <?php if ($chat['pengirim'] == 'user') : ?>
                            <div class="d-flex justify-content-end mb-3 align-self-end w-100">
                                <div class="bg-primary text-white p-3 rounded-3 shadow-sm" style="max-width: 75%; border-bottom-right-radius: 0 !important;">
                                    <p class="mb-1"><?= nl2br(htmlspecialchars($chat['pesan'])); ?></p>
                                    <small class="text-white-50 d-block text-end"><?= date('H:i', strtotime($chat['waktu'])); ?></small>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="d-flex justify-content-start mb-3 align-self-start w-100">
                                <div class="bg-white border p-3 rounded-3 shadow-sm" style="max-width: 75%; border-bottom-left-radius: 0 !important;">
                                    <p class="mb-1"><?= nl2br(htmlspecialchars($chat['pesan'])); ?></p>
                                    <small class="text-muted d-block text-end"><?= date('H:i', strtotime($chat['waktu'])); ?></small>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <div class="text-center text-muted my-auto">
                        <p>Belum ada pesan. Silakan mulai percakapan!</p>
                    </div>
                <?php endif; ?>
                
            </div>

            <div class="card-footer bg-white py-3 border-top-0">
                <form action="" method="POST">
                    <div class="input-group">
                        <input type="text" name="pesan" class="form-control form-control-lg bg-light" placeholder="Ketik pesan Anda di sini..." required autocomplete="off">
                        <button type="submit" name="kirim_pesan" class="btn btn-primary px-4 fw-bold">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const chatBody = document.querySelector('.card-body');
    chatBody.scrollTop = chatBody.scrollHeight;
</script>

<?php include 'includes/footer.php'; ?>