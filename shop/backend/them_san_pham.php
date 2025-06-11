<?php
include 'header.php';
require_once 'connect.php';
require_once 'uploadfiles.php';
// Truy vấn dữ liệu bảng lớp học
$catelog = mysqli_query($conn, "SELECT * FROM catelog");

$error = '';
if (isset($_POST['tensanpham'])) {
    $idnhanhang = $_POST['catelogid'];
    $tensp = $_POST['tensanpham'];
    $desc = $_POST['noidung'];
    $detail = $_POST['noidungchitiet'];
    $pricein = $_POST['giadauvao'];
    $priceout = $_POST['giadaura'];
    if (empty($tensp)) {
        $error = 'Tên nhãn hàng không được để trống';
    }
    $isUploadOk = uploadFile($_FILES["imgae"], $tensp);
    if ($isUploadOk) {
        echo "Upload file thành công";
        $image = str_replace(" ", "_", $tensp) . "." . "jpg";
    } else {
        echo "Upload file thất bại";
    }
    // nếu không có lỗi thì tiến hành thêm mới vào bảng
    if (!$error) {
        $sql = "INSERT INTO `sanpham`(`catelogid`, `tensanpham`, `imgae`, `noidung`, `noidungchitiet`, `giadauvao`, `giadaura`) 
        VALUES ('$idnhanhang','$tensp','$image','$desc','$detail','$pricein','$priceout')";
        if (mysqli_query($conn, $sql)) {

            header('location: san_pham.php');
        } else {
            $error = 'Có lỗi, vui lòng thử lại';
        }
    }
}
?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Thêm mới sản phẩm</h3>
    </div>
    <div class="panel-body">
        <form action="" method="POST" enctype="multipart/form-data" role="form">
            <div class="form-group">
                <label for="">Tên nhãn hàng</label>
                <select name="catelogid" class="form-control">
                    <option value="">Chọn Nhãn Hàng</option>
                    <?php foreach ($catelog as $row) : ?>
                        <option value="<?php echo $row['catelogid']; ?>"><?php echo $row['catelogname']; ?></option>
                        <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="">Tên sản phẩm</label>
                <input type="text" class="form-control" name="tensanpham" placeholder="Nhập tên nhãn hàng">
            </div>
            <div class="form-group">
                <label for="">Ảnh Mẫu</label>
                <input type="file" class="form-control" name="imgae">
            </div>
            <div class="form-group">
                <label for="">Mô tả</label>
                <input type="text" class="form-control" name="noidung" placeholder="Nhập mô tả">
            </div>
            <div class="form-group">
                <label for="">Chi tiết</label>
                <input type="text" class="form-control" name="noidungchitiet" placeholder="Nhập chi tiết sản phẩm">
            </div>
            <div class="form-group">
                <label for="">Giá nhập</label>
                <input type="number" class="form-control" name="giadauvao" min="0" step="500" placeholder="Nhập giá gốc">
            </div>
            <div class="form-group">
                <label for="">Giá bán</label>
                <input type="number" class="form-control" name="giadaura" min="0" step="500" placeholder="Nhập giá bán">
            </div>
            <button type="submit" class="btn btn-primary">Lưu lại</button>
        </form>
    </div>
</div>