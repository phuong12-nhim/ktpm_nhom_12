<?php

$conn = mysqli_connect(
    "mysql",
    "root",
    "root",
    "webbansach"
);

if (!$conn) {
    die("Không thể kết nối MySQL: " . mysqli_connect_error());
}

mysqli_set_charset($conn,"utf8");