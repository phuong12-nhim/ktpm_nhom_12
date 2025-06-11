<?php
session_start();
if (isset($_SESSION['register_success']) && $_SESSION['register_success']) {
    echo "<script>alert('Đăng ký thành công!');</script>";
    unset($_SESSION['register_success']); // Xóa thông báo sau khi hiển thị
}
ob_start();
include_once 'ketnoi.php';

if (isset($_POST['dangnhap'])) {
    $use = $_POST['username'];
    $pass = $_POST['password'];
    
    // Check if the password contains at least 4 numeric characters and no special characters
    if (preg_match('/^[0-9]{4,}$/', $pass) && !preg_match('/[^A-Za-z0-9]/', $pass)) {
        $pass = md5($pass);
// echo $use, $pass;
    $sql = "SELECT * FROM `khachang` WHERE username = '$use' AND matKhau = '$pass'";
    $use_sql = mysqli_query($conn, $sql);
        if (mysqli_num_rows($use_sql) > 0) {
            echo "đăng nhập thành công";
            if (isset($_GET['action'])) {
                $action = $_GET['action'];
                header('location: ' . $action . '.php');
            } else {
                header('location: sanpham.php');
            }
        } else {
            echo "thông tin tài khoản hoặc mật khẩu không chính xác";
        }
        $_SESSION['login']['username'] = $use;
        $_SESSION['login'];
    } else {
        echo "Mật khẩu phải chứa ít nhất 4 ký tự số và không chứa ký tự đặc biệt.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            background-image: linear-gradient(#f4d6cf, #8eccf5);
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
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        /* Additional styles for login and logout links */
        .login-link,
        .logout-link {
            color: #007bff;
            text-decoration: none;
        }

        .login-link:hover,
        .logout-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }
    </style>

</head>

<body>
    <div class="container">
        <div class="form-container">
            <form action="login.php" method="post">
                <legend class="text-center">Đăng Nhập</legend>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Nhập tài khoản" class="form-control">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" placeholder="Nhập mật khẩu" class="form-control">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="show-password-toggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="submit" name="dangnhap" class="btn btn-primary btn-block">Đăng Nhập</button>

                <div class="form-group text-center">
                    Bạn chưa có tài khoản? <a href="register.php" style="margin: 10px 10px 10px 20px" class="btn btn-primary">Đăng Ký</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

    <!-- JavaScript for password visibility toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var passwordInput = document.getElementById('password');
            var passwordToggleBtn = document.getElementById('show-password-toggle');

            passwordToggleBtn.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    passwordToggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>'; // Change the icon to an eye slash when password is visible
                } else {
                    passwordInput.type = 'password';
                    passwordToggleBtn.innerHTML = '<i class="fas fa-eye"></i>'; // Change the icon back to an eye when password is hidden
                }
            });
        });
    </script>
</body>

</html>
