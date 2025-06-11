<?php require_once 'header.php';
require_once 'connect.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Hóa đơn</title>
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
</head>

<body>
	<!-- Header -->
	<header></header>

	<!-- phân trang -->
	<?php
	$page = 0;
	if (isset($_GET["page"])) {
		// echo $_GET["page"];
		$page = $_GET["page"] - 1;
	}

	// Lấy tổng số trang
	$sql = "SELECT CEIL((SELECT COUNT(*) FROM `hoadon`) / 12) AS 'totalpage'"; // Mỗi page 6 items >>> có thể thay đổi theo tham số
	$result = mysqli_query($conn, $sql);
	$totalpage = 0;
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$totalpage = $row["totalpage"];
		}
	}

	// Tải sản phẩm (all)
	// $sql = "SELECT * FROM `hoadon`";
	// Lấy OFFSET hiện tại
	$sql = "SELECT " . $page . " * (SELECT (SELECT COUNT(*) FROM `hoadon`) / (SELECT CEIL((SELECT COUNT(*) FROM `hoadon`) / 12))) AS 'offset'";
	$result = mysqli_query($conn, $sql);
	$offset = 0;
	while ($row = mysqli_fetch_assoc($result)) {
		$offset = (int) $row["offset"];
	}

	// Lấy items trong trang
	$sql = "SELECT * FROM `hoadon` LIMIT " . $offset . ", 12";
	// echo $sql;

	$result = mysqli_query($conn, $sql); // Truy vấn
	// $result = mysqli_multi_query($conn, $sql); // Truy vấn

	// Duyệt hiển thị dữ liệu
	if (mysqli_num_rows($result) > 0) {
		// Code bảng dữ liệu hiển thị
	?>
		<!-- phân trang -->
		<!-- Phân trang -->
		<ul class="phan-trang">
			<?php
			for ($i = 1; $i <= $totalpage; $i++) {
				echo "<li><a href='?page=" . $i . "'>" . $i . "</a></li>";
			}
			?>
		</ul>

		<!-- Body -->
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>ID hóa đơn</th>
					<th>ID khách hàng</th>
					<th>Tên khách hàng</th>
					<th>Địa chỉ</th>
					<th>SĐT</th>
					<th>Email</th>
					<th>Tổng tiền</th>
					<th>Trạng thái</th>
				</tr>
			</thead>
			<tbody>
				<!-- duyệt dữ liệu sử dụng vòng lặp foreach -->
				<?php
				while ($row = mysqli_fetch_assoc($result)) {
				?>
					<tr>
						<td><?php echo $row['idhoadon']; ?></td>
						<td><?php echo $row['idkhachhang']; ?> </td>
						<td><?php echo $row['name']; ?></td>
						<td><?php echo $row['address']; ?></td>
						<td><?php echo $row['phone']; ?></td>
						<td><?php echo $row['email']; ?></td>
						<td><?php echo number_format($row['Tong_tien'])." VNĐ\n"; ?></td>
						<td>
							<?php
							if ($row["status"] == 1) {
								echo "<a href='invoice_delete.php?invoiceId=" . $row["idhoadon"] . "' onclick='return confirm(\"Bạn có chắc muốn xóa hóa đơn này không?\")'> chờ duyệt</a>";
							} else {
								echo "Hoàn thành";
							}
							?>
						</td>
					</tr>
				<?php }; ?>
			</tbody>
		</table>
	<?php } ?>
	<!-- Footer -->
	<footer style="height: 50px; min-height: 50px; line-height: 50px; text-align: center">
		<div class="content-center">
			Hệ thống được bảo trợ bởi GÓC SÁCH NHỎ - Việt Nam | Mọi chi tiết liên hệ Admin Việt Dũng, Hotline: 0355452903
		</div>
	</footer>
</body>

</html>
<!-- 
< ? php echo $row['status']; ?>
			 $hd_query = mysqli_query($conn, "SELECT * FROM `hoadon`");
					 while ($row = mysqli_fetch_assoc($hd_query)) {
			  < ?php } ?>
-->