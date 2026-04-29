<?php
session_start();
include __DIR__ . "/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama = $_POST['nama'] ?? '';
    $email = $_POST['email'] ?? '';
    $pesan = $_POST['pesan'] ?? '';

    if ($nama === '' || $email === '' || $pesan === '') {
        die("Semua field wajib diisi");
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO messages (nama, email, pesan) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $pesan);
    $hasil = mysqli_stmt_execute($stmt);

    if ($hasil) {
        $_SESSION['status'] = 'sukses';
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
