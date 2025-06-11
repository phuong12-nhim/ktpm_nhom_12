<?php 
require_once 'header.php';
require_once 'connect.php';

// Phân trang
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

// Lấy tổng số trang (từ bảng khachang, không phải hoadon)
$sql_total = "SELECT COUNT(*) AS total FROM khachang";
$result_total = mysqli_query($conn, $sql_total);
$row_total = mysqli_fetch_assoc($result_total);
$total_customers = $row_total['total'];
$totalpage = ceil($total_customers / $limit);

// Lấy dữ liệu khách hàng với LIMIT
$sql = "SELECT idkhachhang, tenkhachhang, phone, address FROM khachang LIMIT $offset, $limit";
$result = mysqli_query($conn, $sql);
?>

<h2>Quản lý khách hàng</h2>
<table border="1" cellpadding="8">
  <thead>
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Phone</th>
      <th>Address</th>
      <th>Hành động</th>
    </tr>
  </thead>
  <ul class="phan-trang">
  <?php for ($i = 1; $i <= $totalpage; $i++) { ?>
    <li style="display:inline-block; margin:5px;">
      <a href="?page=<?= $i ?>"><?= $i ?></a>
    </li>
  <?php } ?>
</ul>
  <tbody>
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?= $row['idkhachhang'] ?></td>
        <td><?= $row['tenkhachhang'] ?></td>
        <td><?= $row['phone'] ?></td>
        <td><?= $row['address'] ?></td>
        <td>
          <a href="xoa_user.php?id=<?= $row['idkhachhang'] ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>

<!-- Phân trang -->

