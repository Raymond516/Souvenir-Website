<?php 
session_start();
include 'Database/koneksi.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query_inbox = "
    SELECT m.product_id, m.pesan, m.pengirim, m.waktu, p.nama_produk
    FROM messages m
    LEFT JOIN products p ON m.product_id = p.id
    WHERE m.user_id = '$user_id'
    AND m.id IN (
        SELECT MAX(id) 
        FROM messages 
        WHERE user_id = '$user_id' 
        GROUP BY product_id
    )
    ORDER BY m.waktu DESC
";
$result_inbox = mysqli_query($conn, $query_inbox);
?>
<?php include 'includes/header.php'; ?>

<div class="row mt-4 mb-5 justify-content-center">
    <div class="col-md-8">
        <h3 class="fw-bold mb-4">💬 Chat Masuk</h3>
        
        <div class="card border-0 shadow-sm">
            <div class="list-group list-group-flush">
                
                <?php if (mysqli_num_rows($result_inbox) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result_inbox)): 
                        
                        $nama_tampil = "Supplier Pusat";
                        if (!empty($row['nama_produk'])) {
                            $nama_tampil = "Pemilik " . $row['nama_produk'];
                        }
                        
                        $chat_link = empty($row['product_id']) ? "Chat.php" : "Chat.php?product_id=" . $row['product_id'];
                    ?>
                        <a href="<?= $chat_link; ?>" class="list-group-item list-group-item-action p-4">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 50px; height: 50px; font-size: 24px;">
                                        👤
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold text-dark"><?= htmlspecialchars($nama_tampil); ?></h6>
                                        
                                        <?php if (!empty($row['nama_produk'])): ?>
                                            <span class="badge bg-light text-secondary border mb-2">Terkait: <?= htmlspecialchars($row['nama_produk']); ?></span><br>
                                        <?php endif; ?>
                                        
                                        <p class="mb-0 text-muted small">
                                            <?php 
                                                $prefix = ($row['pengirim'] == 'user') ? 'Anda: ' : '';
                                                echo $prefix . htmlspecialchars(substr($row['pesan'], 0, 50)) . (strlen($row['pesan']) > 50 ? '...' : ''); 
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <small class="text-muted text-nowrap ms-3">
                                    <?= date('d M, H:i', strtotime($row['waktu'])); ?>
                                </small>
                            </div>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="p-5 text-center text-muted">
                        <h5 class="fw-bold">Kotak Masuk Kosong</h5>
                        <p>Anda belum memiliki percakapan dengan supplier mana pun.</p>
                        <a href="explore.php" class="btn btn-primary mt-2">Mulai Cari Produk</a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>