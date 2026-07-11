<?php
session_start();
ob_start();
include_once 'ketnoi.php';

// BIẾN LỖI ĐỂ HIỂN THỊ
$error_msg = "";

if (isset($_POST['dangky'])) {
    // Lấy dữ liệu từ form
    $name = trim($_POST['name'] ?? '');
    $sdt = trim($_POST['phone'] ?? '');
    $diachi = trim($_POST['address'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $use = trim($_POST['username'] ?? '');
    $pas = $_POST['password'] ?? '';

    // LOGIC RÀNG BUỘC 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strpos($email, '@gmail.com') === false) {
        $error_msg = "Lỗi: Email phải có định dạng @gmail.com";
    } elseif (strlen($pas) < 4 || strlen($pas) > 16) {
        $error_msg = "Lỗi: Mật khẩu phải từ 4 đến 16 ký tự.";
    } else {
        // Xử lý lưu vào DB
        $pas_hashed = md5($pas);
        $sql = "INSERT INTO `khachang`(`tenkhachhang`, `phone`, `address`, `email`, `username`, `matKhau`) 
                VALUES ('$name','$sdt','$diachi','$email','$use','$pas_hashed')";

        $dk_sql = mysqli_query($conn, $sql);

        if ($dk_sql) {
            $_SESSION['register_success'] = true;
            header("Location: login.php");
            exit;
        } else {
            $error_msg = "Đăng ký tài khoản thất bại.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-image: linear-gradient(#f4d6cf, #8eccf5);
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        legend {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            width: 100%;
            padding: 10px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .login-link {
            color: #007bff;
            text-decoration: none;
        }

        .login-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .input-group-text {
            background-color: transparent;
            border: none;
        }

        .eye-icon {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <form action="register.php" method="post">
                <legend class="text-center">Đăng Ký</legend>
                
                <?php if (!empty($error_msg)) { echo '<div class="alert alert-danger">'.$error_msg.'</div>'; } ?>

                <div class="form-group">
                    <input type="text" name="name" id="name" class="form-control" placeholder="Họ và tên" required>
                </div>
                <div class="form-group">
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Số điện thoại" required>
                </div>
                <div class="form-group">
                    <input type="text" name="address" id="address" class="form-control" placeholder="Địa chỉ" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="text" name="username" id="username" class="form-control" placeholder="Tên đăng nhập" required>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Mật khẩu" required>
                        <span class="input-group-text eye-icon" id="show-password-toggle">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>
                <button type="submit" name="dangky" class="btn btn-primary">Đăng ký</button>

                <div class="form-group text-center">
                    Bạn đã có tài khoản? <a href="login.php" class="login-link">Đăng nhập</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var passwordInput = document.getElementById('password');
            var passwordToggleBtn = document.getElementById('show-password-toggle');

            passwordToggleBtn.addEventListener('click', function () {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    passwordToggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    passwordInput.type = 'password';
                    passwordToggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
                }
            });
        });
    </script>
</body>
</html>