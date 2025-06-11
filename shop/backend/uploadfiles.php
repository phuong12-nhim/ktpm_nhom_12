<?php

function uploadFile($file, $title){
    // Folder để upload
    $folderUpload = "../uploads";
    $filename = basename($file["name"]);
    $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    $fileNameTarget = str_replace(" ", "_", $title);
    // Đường dẫn lưu file
    $fileDestination = $folderUpload . "/" . $fileNameTarget . "." . $filetype; // uploads/anh_san_pham_1.png
    
    // Chỉ nhận tập tin .jpg
    if ($filetype != "jpg") {
        return false;
    }
    // Giới hạn kích thước file là 2Mb: 1000 * 1024 * 2
    if ($file["size"] > (1000 * 1024 * 2)) {
        return false;
    }
    
    // Upload file
    if (move_uploaded_file($file["tmp_name"], $fileDestination)) {
        return true;
    } else {
        return false;
    }

}