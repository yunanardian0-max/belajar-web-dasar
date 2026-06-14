<?php
require_once "cek_sesi.php";
require_once "../koneksi.php";

// FITUR 1: CREATE & MULTIPLE UPLOAD
if (isset($_POST['tambah_video'])) {
    $judul = $_POST['judul']; 
    $youtube_id = $_POST['youtube_id']; 
    $deskripsi = $_POST['deskripsi'];
    $uploaded_files = [];

    if (!empty($_FILES['lampiran']['name'][0])) {
        if (!is_dir('uploads')) mkdir('uploads', 0777, true);
        $total = count($_FILES['lampiran']['name']);
        for ($i = 0; $i < $total; $i++) {
            $tmp = $_FILES['lampiran']['tmp_name'][$i];
            if ($tmp != "") {
                $filename = time() . "_" . basename($_FILES['lampiran']['name'][$i]);
                $path = "uploads/" . $filename;
                if (move_uploaded_file($tmp, $path)) $uploaded_files[] = $path;
            }
        }
    }
    
    $json_files = json_encode($uploaded_files);
    $stmt = mysqli_prepare($conn, "INSERT INTO videos (judul, youtube_id, deskripsi, attachment_files) VALUES (?, ?, ?, ?)");
    
    // PERBAIKAN FATAL 1: Perbaikan susunan bind parameter agar tidak error
    mysqli_stmt_bind_param($stmt, "ssss", $judul, $youtube_id, $deskripsi, $json_files);
    mysqli_stmt_execute($stmt);
    header("Location: index.php"); 
    exit;
}

// FITUR 1: DELETE (Proses Hapus Video)
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM videos WHERE id = $id");
    header("Location: index.php"); 
    exit;
}

$pesan_query = mysqli_query($conn, "SELECT * FROM messages ORDER BY id DESC");
$video_query = mysqli_query($conn, "SELECT * FROM videos ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <style>
        body { padding:20px; font-family:sans-serif; background: #f9f9f9; color: #333; }
        .box { background:#fff; padding:20px; margin-bottom:20px; border: 1px solid #ddd; border-radius: 5px; }
        input[type="text"], textarea { width: 100%; padding: 10px; margin-bottom: 10px; box-sizing: border-box; }
        button { background: #0a0a0a; color: #e8ff47; padding: 10px 20px; border: none; cursor: pointer; font-weight: bold; }
        .btn-delete { background: red; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 13px; }
    </style>
</head>
<body>

    <a href="../logout.php" style="float:right; color:red; font-weight: bold; text-decoration: none;">Logout</a>
    <h1>Dashboard Administrator</h1>
    <hr><br>

    <div class="box">
        <h2>Tambah Video & Upload Multiple File</h2><br>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="judul" placeholder="Judul Video" required>
            <input type="text" name="youtube_id" placeholder="ID Youtube (Contoh: dQw4w9WgXcQ)" required>
            <textarea name="deskripsi" placeholder="Deskripsi Video" rows="4" required></textarea>
            
            <label style="font-weight: bold;">Upload File Pendukung (Bisa pilih banyak file sekaligus):</label><br><br>
            <input type="file" name="lampiran[]" multiple><br><br>
            
            <button type="submit" name="tambah_video">Simpan Data</button>
        </form>
    </div>

    <div class="box">
        <h2>Daftar Video Portfolio (CRUD - Read & Delete)</h2><br>
        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse:collapse; background:#fff;">
            <thead>
                <tr style="background:#eee;">
                    <th>Judul</th>
                    <th>ID Youtube</th>
                    <th>Deskripsi</th>
                    <th>File Lampiran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($video_query) == 0): ?>
                    <tr><td colspan="5" style="text-align:center;">Belum ada data video.</td></tr>
                <?php endif; ?>
                <?php while($v = mysqli_fetch_assoc($video_query)): ?>
                <tr>
                    <td><?= htmlspecialchars($v['judul']) ?></td>
                    <td><code><?= htmlspecialchars($v['youtube_id']) ?></code></td>
                    <td><?= htmlspecialchars($v['deskripsi']) ?></td>
                    <td>
                        <?php 
// PERBAIKAN: Cek dulu apakah kolom data ada isinya dan tidak NULL sebelum di-decode
$files = [];
if (!empty($v['attachment_files'])) {
    $files = json_decode($v['attachment_files'], true);
}

if(!empty($files) && is_array($files)): 
    foreach($files as $key => $filePath): ?>
        <a href="<?= htmlspecialchars($filePath) ?>" target="_blank">File <?= $key+1 ?></a><br>
    <?php endforeach;
else: ?>
    Tidak ada lampiran
<?php endif; ?>
                    </td>
                    <td>
                        <a href="?hapus=<?= $v['id'] ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus video ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="box">
        <h2>Data Pesan Masuk (Datatable + Konversi Data)</h2><br>
        <table id="tabelPesan" class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Pesan</th>
                    <th>Tanda Tangan</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($pesan_query)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['pesan']) ?></td>
                    <td>
                        <?php if(!empty($row['ttd'])): ?>
                            <img src="<?= $row['ttd'] ?>" height="50" style="background:#fff; border:1px solid #ccc; padding:2px;">
                        <?php else: ?>
                            Belum Tanda Tangan
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            // Mengaktifkan fitur pencarian, lembar halaman otomatis, dan tombol download data (Excel/PDF)
            $('#tabelPesan').DataTable({
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            });
        });
    </script>
</body>
</html>