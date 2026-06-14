<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    die("Akses Ditolak. Anda bukan Admin. <a href='../logout.php'>Keluar</a>");
}
?>