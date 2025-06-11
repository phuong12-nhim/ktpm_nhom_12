<?php
include 'header.php';
require_once 'connect.php';
require_once 'uploadfiles.php';

$id = !empty($_GET['Proid']) ? (int)$_GET['Proid'] : 0;
$result = mysqli_query($conn, "SELECT s.idsanpham, c.catelogname as 'tenNhanHang', s.tensanpham, s.imgae, s.noidung, s.noidungchitiet,
                    s.giadauvao, s.giadaura, s.status FROM sanpham s JOIN catelog c ON s.catelogid = c.catelogid AND s.idsanpham = $id");
$rowSP = mysqli_fetch_assoc($result);

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
        $sql = "UPDATE sanpham SET catelogid ='$idnhanhang',`tensanpham`='$tensp',`imgae`='$image',`noidung`='$desc',`noidungchitiet`='$detail',
                `giadauvao`='$pricein',`giadaura`='$priceout' WHERE `idsanpham`= $id";
        if (mysqli_query($conn, $sql)) {

            header('location: san_pham.php');
        } else {
            $error = 'Có lỗi, vui lòng thử lại';
        }
    }
}
?>
<?php $catelog = mysqli_query($conn, "SELECT * FROM catelog"); ?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Thêm mới sản phẩm</h3>
    </div>
    <div class="panel-body">
        <form action="" method="POST" enctype="multipart/form-data" role="form">
            <div class="form-group">
                <label for="">Tên nhãn hàng</label>
                <select name="catelogid" class="form-control">
                    <?php foreach ($catelog as $row) : ?>
                        <option value="<?php echo $row['catelogid']; ?>"><?php echo $row['catelogname']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="">Tên sản phẩm</label>
                <input type="text" class="form-control" name="tensanpham" value="<?php echo isset($rowSP['tensanpham']) ? $rowSP['tensanpham'] : ''; ?>">

            </div>
            <div class="form-group">
                <label for="">Ảnh Mẫu</label>
                <input type="file" class="form-control" name="imgae" value="<?php echo $rowSP['imgae']; ?>">
                <?php
                if (!empty(isset($rowSP['imgae']))) {
                ?>
                    <img width="200" height="200" src="<?php echo "/shop/uploads/".$rowSP['imgae'] ?>" alt="">
                <?php } ?>
            </div>
            <div class="form-group">
                <label for="">Mô tả</label>
                <input type="text" class="form-control" name="noidung" value="<?php echo isset($rowSP['noidung']) ? $rowSP['noidung'] : ''; ?>">

            </div>
            <div class="form-group">
                <label for="">Chi tiết</label>
                <input type="text" class="form-control" name="noidungchitiet" value="<?php echo isset($rowSP['noidungchitiet']) ? $rowSP['noidungchitiet'] : ''; ?>">

            </div>
            <div class="form-group">
                <label for="">Giá nhập</label>
                <input type="number" class="form-control" name="giadauvao" min="0" step="500" value="<?php echo $rowSP['giadauvao']; ?>">
            </div>
            <div class="form-group">
                <label for="">Giá bán</label>
                <input type="number" class="form-control" name="giadaura" min="0" step="500" value="<?php echo $rowSP['giadaura']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Lưu lại</button>
        </form>
    </div>
</div>