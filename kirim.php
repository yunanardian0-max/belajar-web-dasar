<?php
session_start();
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = $_POST['nama'] ?? ''; 
    $email = $_POST['email'] ?? ''; 
    $pesan = $_POST['pesan'] ?? ''; 
    $ttd = $_POST['ttd'] ?? '';
    
    if (empty($nama) || empty($email) || empty($pesan) || empty($ttd)) {
        die("Semua field termasuk Tanda Tangan wajib diisi!");
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO messages (nama, email, pesan, ttd) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $nama, $email, $pesan, $ttd);
    
    if(mysqli_stmt_execute($stmt)) {
        $_SESSION['status'] = 'sukses';
        header("Location: index.php");
        exit;
    } else {
        die("Gagal menyimpan data: " . mysqli_error($conn));
    }
}
?>