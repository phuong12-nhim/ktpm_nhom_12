<?php
include 'header.php';
require_once 'connect.php';

$id = !empty($_GET['Catid']) ? (int)$_GET['Catid'] : 0;

$sql = "DELETE FROM catelog WHERE catelogid = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: nhan_hang.php");
    exit();
} else {
    die("Lỗi MySQL: " . mysqli_error($conn));
}
?>