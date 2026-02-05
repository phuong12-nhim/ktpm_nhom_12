<?php
include 'header.php';
require_once 'connect.php';

/* Lấy danh sách khách hàng */
$users = mysqli_query($conn, "SELECT * FROM khachang");
?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Danh sách khách hàng</h3>
    </div>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>id</th>
                <th>Tên</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Vai trò</th>
                <th>Hành động</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($row = mysqli_fetch_assoc($users)) : ?>
            <tr>
                <td><?= $row['idkhachhang']; ?></td>
                <td><?= $row['username']; ?></td>
                <td><?= $row['email']; ?></td>
                <td><?= $row['phone']; ?></td>
                <td>Khách hàng</td>
                <td>
                    <a href="xoa_khach_hang.php?idkhachhang=<?= $row['idkhachhang'] ?>" class="btn btn-xs btn-danger"
                        onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này không?');">
                        Xóa
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>