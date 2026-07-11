<?php
// Replace 'your_password' with your actual password
$conn = mysqli_connect(
    "mysql",
    "root",
    "root",
    "webbansach"
);

if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

// Hàm định dạng tiền tệ
function formatCurrency($curr)
{
    return number_format($curr, 0, ',', '.') . ' VND';
}

// Hàm định dạng số
function formatNumber($num, $decimal)
{
    return number_format($num, $decimal, ',', '.');
}
?>
