<?php
$conn = mysqli_connect("localhost", "root", "", "db_video_portfolio");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>