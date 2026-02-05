    <?php
    require_once 'dieuhuong.php';
    require_once 'ketnoi.php';
    require_once 'cart_function.php';
    date_default_timezone_set('Asia/Ho_Chi_Minh');

    if(!isset($_SESSION['login']['username'])){
        header('Location: login.php');
        exit;
    }
    $cart = $_SESSION['cart'] ?? [];
    $checkout = $_SESSION['checkout'] ?? null;

    if(isset($_POST['payment'])){
        $username = $_SESSION['login']['username'];

        // Lấy thông tin khách hàng
        $stmt = $conn->prepare("SELECT * FROM khachang WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        if (!$res) {
            die("Không tìm thấy thông tin khách hàng.");
        }

        $idkhachhang = $res['idkhachhang'];
        $Ngay_tao  = date("Y-m-d H:i:s");
        $address   = $res['address'];
        $phone     = $res['phone'];
        $email     = $res['email'];
        $tong_tien = 0;
        $soluong = 0;

        foreach ($cart as $item) {
            $tong_tien += $item['price'] * $item['quantity'];
            $soluong   += $item['quantity'];
        }
        // Insert hóa đơn (1 lần duy nhất)
        $stmt = $conn->prepare("INSERT INTO hoadon (idkhachhang, name, address, phone, email, Tong_tien, Ngay_tao, soluong) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssdsi", $idkhachhang, $username, $address, $phone, $email, $tong_tien, $Ngay_tao, $soluong);
        $stmt->execute();
        $mahd = $conn->insert_id;

        // Insert chi tiết đơn hàng
        foreach ($cart as $idsanpham => $item) {
            $dongia = isset($item['price']) && is_numeric($item['price']) ? (float)$item['price'] : 0;
            $soluong_sp = (int)$item['quantity'];
            $stmt = $conn->prepare("INSERT INTO chitietdonhang (idhoadon, idsanpham, dongia, soluong) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iidi", $mahd, $idsanpham, $dongia, $soluong_sp);
            $stmt->execute();
        }
        // File content generation
        $ordersDir = 'orders/';
        if (!is_dir($ordersDir)) {
            mkdir($ordersDir, 0755, true);
        }
        $filename = $ordersDir . 'order_' . $mahd . '_' . time() . '.txt';        
        $fileContent = " \n\t\t\t\t\tGÓC SÁCH NHỎ\n";
        $fileContent .= "Địa chỉ: Phố Phan Đình Giót - Phương Liệt - Thanh Xuân - TP.Hà Nội\n";
        $fileContent .= "\nThông Tin Đơn Hàng:\n";
        $fileContent .= "-----------------------------------------------------------------------------\n";

        // Add customer information
        $fileContent .= "Họ & Tên: " . $res['username'] . "\n";
        $fileContent .= "Email: " . $res['email'] . "\n";
        $fileContent .= "Số Điện Thoại: " . $res['phone'] . "\n";
        $fileContent .= "Địa Chỉ: " . $res['address'] . "\n";
        $fileContent .= "Ngày Mua Hàng: " . date("d-m-Y H:i:s") . "\n";
        $fileContent .= "Ghi chú đơn hàng: " . $checkout['note'] . "\n";
        $fileContent .= "-----------------------------------------------------------------------------\n";

        $fileContent .= sprintf(
            "%-40s %10s %15s %15s\n",
            "Tên Sản Phẩm",
            "Số Lượng",
            "Đơn Giá",
            "Thành Tiền"
        );
        foreach ($cart as $item) {
            $fileContent .= sprintf(
                "%-40s %10d %15s %15s\n",
                $item['name'],
                $item['quantity'],
                number_format($item['price']) . " VNĐ",
                number_format($item['price'] * $item['quantity']) . " VNĐ"
            );
        }


        $fileContent .= "-----------------------------------------------------------------------------\n";
        $fileContent .= "Tổng Tiền:" . number_format(total_price($cart)) . " VNĐ\n";
        $fileContent .= "Cảm ơn quý khách đã tin tưởng mua sản phẩm của Góc Sách Nhỏ!\n";

        // Lưu file vào thư mục an toàn để tải lại
        file_put_contents($filename, $fileContent);

        $_SESSION['email'] = $res['email'];
        $_SESSION['payment_success'] = true;
        $_SESSION['payment_filename'] = $filename; // file.txt vừa tạo
        unset($_SESSION['cart']);
        header("Location: payment.php?success=1");
        require_once 'mail.php';
        exit;
}
    if (isset($_SESSION['mail_success'])) {
        echo "<script>alert('Đã gửi hóa đơn về email của bạn!');</script>";
        unset($_SESSION['mail_success']);
    }
    ?>
    <style>
        /* RESET */
    * {
        box-sizing: border-box;
        font-family: Arial, Helvetica, sans-serif;
    }

    body {
        margin: 0;
        background: #f2f4f8;
    }
    .payment-container {
        max-width: 420px;
        margin: 70px auto;
        background: #fff;
        padding: 25px 30px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .payment-container h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #2c3e50;
    }

    .payment-container p {
        margin: 6px 0;
        font-size: 15px;
        color: #333;
    }

    .payment-form {
        margin-top: 20px;
    }

    .payment-form label {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 14px;
        margin-bottom: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        cursor: pointer;
        background: #fafafa;
        transition: 0.2s;
    }

    .payment-form label:hover {
        border-color: #3498db;
        background: #eef6ff;
    }

    .payment-form input[type="radio"] {
        transform: scale(1.2);
    }

    .payment-form button {
        width: 100%;
        margin-top: 10px;
        padding: 12px;
        font-size: 16px;
        border: none;
        border-radius: 6px;
        background: #3498db;
        color: #fff;
        cursor: pointer;
        transition: 0.3s;
    }

    .payment-form button:hover {
        background: #217dbb;
    }

    .btn {
        display: inline-block;
        margin-top: 15px;
        background: #28a745;
        color: #fff;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
    }

    .btn:hover {
        background: #1e7e34;
    }
    </style>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <div class="payment-container">
        <h2>Thanh toán</h2> 

        <p><b>Khách hàng:</b> <?= htmlspecialchars($_SESSION['login']['username']) ?></p>
        <p><b>Tổng tiền:</b> <?= htmlspecialchars(number_format(total_price($cart))) ?> VNĐ</p>

        <form method="post" class="payment-form">
            <label>
                <input type="radio" name="payment_method" value="COD" required>
                Thanh toán khi nhận hàng (COD)
            </label>

            <label>
                <input type="radio" name="payment_method" value="BANK">
                Chuyển khoản online
            </label>
            <button type="submit" name="payment">Xác nhận thanh toán</button>
        </form>
    <?php if (isset($_GET['success']) && isset($_SESSION['payment_filename'])): ?>
        <?php $file = $_SESSION['payment_filename'];?>
        <div style="text-align:center; margin:20px">
            <a href="<?= $_SESSION['payment_filename'] ?>" download class="btn">
                Xem chi tiết đơn hàng
            </a>
        </div>
    <?php endif; ?>
    </div>
    </body>
    </html>