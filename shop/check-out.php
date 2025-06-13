    <?php
    require_once 'dieuhuong.php';
    require_once 'ketnoi.php';
    require_once 'cart_function.php';
    date_default_timezone_set('Asia/Ho_Chi_Minh');

    $cart = $_SESSION['cart'] ?? [];

    if (isset($_SESSION['login']['username']) && isset($_POST['checkout'])) {
    $username = $_SESSION['login']['username'];
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $note = $_POST['note'] ?? '';

    // Lấy thông tin khách hàng
    $stmt = $conn->prepare("SELECT * FROM khachang WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if (!$res) {
        die("Không tìm thấy thông tin khách hàng.");
    }

    $idkhachhang = $res['idkhachhang'];
    $Ngay_tao = date("Y-m-d H:i:s");

    // Tính tổng tiền giỏ hàng
    $tong_tien = 0;
    foreach ($cart as $item) {
        $soluong = isset($item['quantity']) && is_numeric($item['quantity']) ? (int)$item['quantity'] : 0;
        $dongia = isset($item['price']) && is_numeric($item['price']) ? (float)$item['price'] : 0;
        $tong_tien += $soluong * $dongia;
    }

    // Insert hóa đơn (1 lần duy nhất)
    $stmt = $conn->prepare("INSERT INTO hoadon (idkhachhang, name, address, phone, email, Tong_tien, Ngay_tao) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssds", $idkhachhang, $username, $address, $phone, $email, $tong_tien, $Ngay_tao);
    $stmt->execute();
    $mahd = $conn->insert_id;

    // Insert chi tiết đơn hàng
    foreach ($cart as $idsanpham => $item) {
        $soluong = isset($item['quantity']) && is_numeric($item['quantity']) ? (int)$item['quantity'] : 0;
        $dongia = isset($item['price']) && is_numeric($item['price']) ? (float)$item['price'] : 0;

        $stmt = $conn->prepare("INSERT INTO chitietdonhang (idhoadon, idsanpham, dongia, soluong) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iidi", $mahd, $idsanpham, $dongia, $soluong);
        $stmt->execute();
    }
        // File content generation
        $filename = 'order_details_' . time() . '.txt';
        
        $fileContent = " \n\t\t\tGÓC SÁCH NHỎ\n";
        $fileContent .= "Địa chỉ: Phố Phan Đình Giót - Phương Liệt - Thanh Xuân - TP.Hà Nội\n";
        $fileContent .= "\nThông Tin Đơn Hàng:\n";
        $fileContent .= "-----------------------------------------------------------------------------\n";

        // Add customer information
        $fileContent .= "Họ & Tên: " . $res['username'] . "\n";
        $fileContent .= "Email: " . $res['email'] . "\n";
        $fileContent .= "Số Điện Thoại: " . $res['phone'] . "\n";
        $fileContent .= "Địa Chỉ: " . $res['address'] . "\n";
        $fileContent .= "Ngày Mua Hàng: " . date("d-m-Y H:i:s") . "\n";
        $fileContent .= "Ghi chú đơn hàng: " . $note . "\n";
        $fileContent .= "-----------------------------------------------------------------------------\n";

        $fileContent .= sprintf("%-30s %-20s %-15s %-15s\n", "Tên Sản Phẩm\t\t", "Số Lượng", "Đơn Giá", "Thành Tiền");
        foreach ($cart as $item) {
            $fileContent .= sprintf(
                "%-35s %-10d %-15s %-15s\n",
                $item['name'],
                $item['quantity'],
                number_format($item['price']) . " VNĐ",
                number_format($item['price'] * $item['quantity']) . " VNĐ"
            );
        }

        $maxLength = max(array_map('mb_strlen', array_column($cart, 'name')));

        function amountToWords($amount) {
            // ... (no changes here)
        }

        foreach ($cart as $value) {
            // ... (no changes here)
        }

        $totalAmountInWords = amountToWords(total_price($cart));

        $fileContent .= "-----------------------------------------------------------------------------\n";
        $fileContent .= "Tổng Tiền:" . number_format(total_price($cart)) . " VNĐ\n";
        $fileContent .= "Cảm ơn quý khách đã tin tưởng mua sản phẩm của Góc Sách Nhỏ!\n";

        // Tạo thư mục lưu đơn hàng nếu chưa có
$ordersDir = 'orders/';
if (!is_dir($ordersDir)) {
    mkdir($ordersDir, 0755, true);
}

// Lưu file vào thư mục an toàn để tải lại
$filename = $ordersDir . 'order_details_' . time() . '.txt';
file_put_contents($filename, $fileContent);

// Sau khi insert đơn hàng thành công
$_SESSION['checkout_success'] = true;
$_SESSION['checkout_filename'] = $filename; // file.txt vừa tạo
unset($_SESSION['cart']);

// Redirect để tránh gửi file trực tiếp
header("Location: check-out.php");
exit;
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <style>
        .customer-box {
            background: #f9f9f9;
            border: 2px solid #ccc;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            font-family: Arial, sans-serif;
        }

        .customer-box h3 {
            margin-bottom: 15px;
            font-size: 20px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .customer-box .form-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 15px;
        }

        .customer-box label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .customer-box input {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .customer-box .total {
            font-weight: bold;
            font-size: 16px;
            margin-top: 10px;
        }

        .customer-box .btn {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .customer-box .btn:hover {
            background-color: #218838;
        }
    </style>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hiển thị giỏ hàng</title>
        <style>
            .container {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
            }

            .flex-1,.flex-2 {
                flex: 1;
                padding: 10px;
                
                
            }
            .flex-2>table{
                background: #f9f9f9;
                border-radius: 8px  ;
                border: 2px solid #ccc;
                margin-top: 10px;
            }
            .k {
                text-align: center;
            }
        </style>
    </head>

    <body>
        <?php
        if (isset($_SESSION['login']['username'])){
            $username = $_SESSION['login']['username'];
            $stmt = $conn->prepare("SELECT * FROM khachang WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $res = $result->num_rows > 0 ? $result->fetch_assoc() : null;
            // <!-- nút thanh toán -->
        ?>
            <div class="container">
                <div class="flex-1">
                    <?php if ($res) { ?>
                        <form action="" method="post">

                            <!-- ... (your existing form fields) -->

                            <div class="form-group">
                                    <label for="note"> <h3>GHI CHÚ</h3></label>
                                    <textarea name="note" id="note" cols="30" rows="10" placeholder="CHi TIẾT ĐƠN HÀNG" class="form-control"></textarea>
                            </div>
                            
                            <button type="submit" name="checkout" class="btn btn-info" >Thanh Toán </button>
                             <?php
                    if (isset($_SESSION['checkout_success']) && $_SESSION['checkout_success']) {
                        echo "<script>alert('Đặt hàng thành công!');</script>";

                        if (isset($_SESSION['checkout_filename'])) {
                            $file = $_SESSION['checkout_filename'];
                            echo "<div style='text-align:center; margin: 20px;'>
                                    <a href='$file' download class='btn' style='background-color: #28a745; color: white; padding: 10px 20px; border-radius: 5px;'>Xem chi tiết đơn hàng</a>
                                </div>";
                        }

                        // Xóa session sau khi hiển thị
                        unset($_SESSION['checkout_success']);
                        unset($_SESSION['checkout_filename']);
                    }
?>

                    <?php } ?>
                    
                    </div>
                    <div class="flex-2">
                        <h2>Thông Tin Đơn Hàng</h2>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Tên Sản Phẩm</th>
                                    <th>Số Lượng</th>
                                    <th>Đơn Giá</th>
                                    <th>Thành Tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart as $value) {  ?>
                                    <tr>
                                        <td><?php echo $value['name'] ?></td>
                                        <td style="width: 30px;"><?php echo $value['quantity'] ?></td>
                                        <td><?php echo number_format($value['price']) ?></td>
                                        <td><?php echo number_format($value['price'] * $value['quantity']) ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (isset($_SESSION['login']['username']) && isset($_POST['update'])) {
                                    $username_old = $_SESSION['login']['username'];
                                    $username_new = $_POST['username'] ?? '';
                                    $email = $_POST['email'] ?? '';
                                    $phone = $_POST['phone'] ?? '';
                                    $address = $_POST['address'] ?? '';

                                    // Lấy id khách hàng theo username cũ
                                    $stmt = $conn->prepare("SELECT idkhachhang FROM khachang WHERE username = ?");
                                    $stmt->bind_param("s", $username_old);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $res_id = $result->fetch_assoc();

                                    if ($res_id) {
                                        $idkhachhang = $res_id['idkhachhang'];

                                        // Cập nhật dữ liệu
                                        $stmt = $conn->prepare("UPDATE khachang SET username = ?, email = ?, phone = ?, address = ? WHERE idkhachhang = ?");
                                        $stmt->bind_param("ssssi", $username_new, $email, $phone, $address, $idkhachhang);
                                        $stmt->execute();

                                        if ($stmt->affected_rows > 0) {
                                            // Cập nhật session username nếu đổi tên
                                            $_SESSION['login']['username'] = $username_new;
                                            echo "<script>alert('Cập nhật thông tin thành công!'); window.location.href=window.location.href;</script>";
                                            exit;
                                        } else {
                                            echo "<script>alert('Không có thay đổi.Vui lòng kiểm tra lại thông tin.');</script>";
                                        }
                                    } else {
                                        echo "<script>alert('Không tìm thấy khách hàng để cập nhật.');</script>";
                                    }
                                } ?>
                                <?php if ($res) ?>
                                    <div class="customer-box">
                                        <h3>Thông Tin Khách Hàng</h3>
                                            <div class="form-row">
                                                <div class="form-group" style="flex:1">
                                                    <label for="username">Họ & Tên:</label>
                                                    <input type="text" name="username" value="<?php echo isset($res['username']) ? htmlspecialchars($res['username']) : ''; ?>" required>
                                                </div>
                                                <div class="form-group" style="flex:1">
                                                    <label for="email">Email:</label>
                                                    <input type="text" name="email" value="<?php echo isset($res['email']) ? htmlspecialchars($res['email']) : ''; ?>" required>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group" style="flex:1">
                                                    <label for="phone">Số Điện Thoại:</label>
                                                    <input type="text" name="phone" value="<?php echo isset($res['phone']) ? htmlspecialchars($res['phone']) : ''; ?>" required>
                                                </div>
                                                <div class="form-group" style="flex:1">
                                                    <label for="address">Địa Chỉ:</label>
                                                    <input type="text" name="address" value="<?php echo isset($res['address']) ? htmlspecialchars($res['address']) : ''; ?>" required>
                                                </div>
                                            </div>
                                            <div class="total">Tổng Tiền: <?php echo number_format(total_price($cart)) ?> VNĐ</div>
                                            <button type="submit" name="update" class="btn">Cập Nhật</button>
                                        
                                    </div> 
                            </form>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } else { ?>
            <div class="k">
                <h1>Vui lòng ĐĂNG NHẬP để mua hàng </h1> <a href="login.php?action=check-out">Tại Đây</a>
            </div>
        <?php } ?>
    </body>

    </html>
