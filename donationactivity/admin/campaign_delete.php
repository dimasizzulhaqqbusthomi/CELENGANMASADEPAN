<?php
require '../backend/function.php';

$id = $_GET["id"];
$contributor_id = $_SESSION["id"];

// Verifikasi kepemilikan sebelum menghapus
$check = query("SELECT * FROM campaigns WHERE id = $id AND contributor_id = $contributor_id");

if (count($check) > 0) {
    mysqli_query($conn, "DELETE FROM campaigns WHERE id = $id");
    header("Location: dashboard.php");
} else {
    die("Akses ilegal terdeteksi.");
}
?>