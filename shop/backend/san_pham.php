<?php
include 'header.php';
require_once 'connect.php';

$sql = "SELECT s.idsanpham, c.catelogname as 'tenNhanHang', s.tensanpham, s.imgae, s.noidung, s.noidungchitiet,
        s.giadauvao, s.giadaura, s.status FROM sanpham s JOIN catelog c ON s.catelogid = c.catelogid";
$sanpham = mysqli_query($conn, $sql);
?>
<style>
    .phan-trang {
        width: 100%;
        text-align: center;
        list-style: none;
        list-style: none;
        font-weight: bold;
        font-size: 1.5em;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .phan-trang li {
        display: inline;
    }

    .phan-trang a {
        padding: 10px;
        border: 1px solid #ebebeb;
        text-decoration: none;
    }
</style>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Danh sách sản phẩm</h3>
    </div>
    <?php
    $page = 0;
    if (isset($_GET["page"])) {
        $page = $_GET["page"] - 1;
    }

    $sql = "SELECT CEIL((SELECT COUNT(*) FROM `sanpham`) / 6) AS 'totalpage'";
    $result = mysqli_query($conn, $sql);
    $totalpage = 0;
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $totalpage = $row["totalpage"];
        }
    }

    $sql = "SELECT " . $page . " * (SELECT (SELECT COUNT(*) FROM `sanpham`) / (SELECT CEIL((SELECT COUNT(*) FROM `sanpham`) / 6))) AS 'offset'";
    $result = mysqli_query($conn, $sql);
    $offset = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $offset = (int) $row["offset"];
    }

    $sql = "SELECT * FROM `sanpham` LIMIT " . $offset . ", 6";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
    ?>
        <ul class="phan-trang">
            <?php
            for ($i = 1; $i <= $totalpage; $i++) {
                echo "<li><a href='?page=" . $i . "'>" . $i . "</a></li>";
            }
            ?>
        </ul>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID sản phẩm</th>
                    <th>Nhãn hàng</th>
                    <th>Tên sản phẩm</th>
                    <th>Ảnh mẫu</th>
                    <th>Mô tả</th>
                    <th>Giá nhập hàng</th>
                    <th>Giá bán</th>
                    <th>Trạng thái</th>
                    <th><a href="/shop/backend/them_san_pham.php" class="btn btn-primary btn-lg">Thêm sản phẩm</a></th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $sql_cat = "SELECT catelogname FROM catelog WHERE catelogid = " . $row['catelogid'];
                    $res = mysqli_query($conn, $sql_cat);
                    if ($r = mysqli_fetch_assoc($res)) {
                        $brand_name = $r['catelogname'];
                    }
                ?>
                    <tr>
                        <td><?php echo $row['idsanpham']; ?></td>
                        <td><?php echo $brand_name; ?></td>
                        <td><?php echo $row['tensanpham']; ?></td>
                        <td><?php echo "<img width=\"100px\" height = \"auto\" src=\"/shop/uploads/" . $row["imgae"] . "\" alt=\"" . $row["tensanpham"] . "\">" ?></td>
                        <td><?php echo $row['noidung']; ?></td>
                        <td><?php echo $row['giadauvao']; ?></td>
                        <td><?php echo $row['giadaura']; ?></td>
                        <td><?php echo $row['status'] == 1 ? 'Còn hàng' : 'Đã hết hàng'; ?></td>
                        <td>
                            <a href="/shop/backend/sua_san_pham.php?Proid=<?php echo $row['idsanpham']; ?>" class="btn btn-xs btn-primary">Sửa</a>
                            <a href="/shop/backend/xoa_san_pham.php?Proid=<?php echo $row['idsanpham']; ?>" class="btn btn-xs btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');">Xóa</a>
                        </td>
                    </tr>
                <?php }; ?>
            </tbody>
        </table>
    <?php } ?>
</div>