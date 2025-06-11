<?php 
	ob_start();
	require_once 'connect.php';
	if (isset($_GET['invoiceId'])) {
    $invoiceId = intval($_GET['invoiceId']);

    // Bắt đầu transaction để đảm bảo đồng bộ
    mysqli_begin_transaction($conn);

    try {
        // Xóa bảng con: chitietdonhang (nếu có)
        $sql1 = "DELETE FROM chitietdonhang WHERE idhoadon = ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("i", $invoiceId);
        $stmt1->execute();

        // Xóa bảng cha: hoadon
        $sql2 = "DELETE FROM hoadon WHERE idhoadon = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $invoiceId);
        $stmt2->execute();

        // Commit nếu không lỗi
        mysqli_commit($conn);

        // Quay lại trang hóa đơn
        header("Location: order.php?page=1");
        exit();
    } catch (Exception $e) {
        // Hủy bỏ nếu có lỗi
        mysqli_rollback($conn);
        echo "Lỗi khi xóa hóa đơn: " . $e->getMessage();
    }

    // Đóng kết nối
    $stmt1->close();
    $stmt2->close();
    $conn->close();
} else {
    echo "Thiếu tham số invoiceId.";
}
?>