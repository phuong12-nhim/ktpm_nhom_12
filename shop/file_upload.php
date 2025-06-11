<?PHP 
echo "tải lên tập tin trong php <br>";
    if (isset($_POST['submit'])) {
        $permitted_extensions = ['png','jpg','jpeg','gif'];
        $file_name = $_FILES['hinhanh']['name'];
        if (!empty($file_name)) {
            // print_r($_FILES);
            $file_size = $_FILES['hinhanh']['size'];
            $file_tmp_name = $_FILES['hinhanh']['tmp_name'];
            $generated_file_name = time() . '-' . $file_name;
            $destination_path = "uploads/$generated_file_name";
            $file_extensions = explode('.' , $file_name);
            $file_extensions = strtolower(end($file_extensions));
            if (in_array($file_extensions , $permitted_extensions)) {
                if ($file_size <= 1000000) {
                    move_uploaded_file($file_tmp_name , $destination_path);
                    $message = '<p style="color:green;"> tập tin đã được tải lên </p>';
                }else{
                    $message = '<p style="color:red;"> tệp quá lớn </p>';
                }
            }else{
                $message = '<p style="color:red;"> loại tệp không hợp lệ </p>';
            }
        }else{
            $message = '<p style="color:red;"> không có tệp nào được chọn, vui lòng thử lại </p>';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>upload</title>
</head>

<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" 
        method="post" 
        enctype="multipart/form-data"> 

        Chọn hình ảnh để tải lên:
        <input type="file" name="hinhanh" id="hinhanh">
        <input type="submit" value="Upload Image" name="submit">

    </form>
    
</body>

</html>