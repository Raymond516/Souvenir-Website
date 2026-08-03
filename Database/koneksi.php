<?php
$host     = "localhost";
$user     = "root";
$password = "";
$dbname   = "responsive_web_promosi";

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>