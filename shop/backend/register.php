<?php
session_start();
ob_start();
include_once 'connect.php';

if (
    isset($_POST['dangky']) &&
    !empty($_POST['username']) &&
    !empty($_POST['password']) &&
    !empty($_POST['repassword']) &&
    isset($_POST['level'])
) {
    $name = htmlspecialchars($_POST['username']);
    $pas = htmlspecialchars($_POST['password']);
    $repass = htmlspecialchars($_POST['repassword']);
    $lev = htmlspecialchars($_POST['level']);

    if (strlen($pas) < 4) {
        echo "Mật khẩu phải có ít nhất 4 ký tự.";
    } elseif ($pas !== $repass) {
        echo "Mật khẩu không khớp.";
    } else {
        $pas = md5($pas);
        $repass = md5($repass);

        $sql = "INSERT INTO `admin`(`username`, `password`, `repassword`, `level`) VALUES 
            ('$name','$pas','$repass','$lev')";

        $dk_sql = mysqli_query($conn, $sql);

        if ($dk_sql) {
    $_SESSION['register_success'] = true; // đánh dấu đã đăng ký thành công
    header("Location: login.php");        // chuyển hướng về trang đăng nhập
    exit;
} else {
    echo "đăng ký tài khoản thất bại";
}
    }
}
// Generate a secret token and store it in the session
$_SESSION['secret'] = md5(uniqid(rand(), true));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>

    <!--  -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!--  -->
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
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="col-md-6">
            <form action="register.php" method="post">
                <legend>Đăng Ký</legend>
                <div class="form-group">
                    <label for="">Tên đăng nhập</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="">Nhập lại mật khẩu</label>
                    <input type="password" name="repassword" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="">Level</label>
                    <input type="number" name="level" class="form-control" required>
                </div>
                <!-- Secret input field -->
                <div class="form-group">
                    <label for="">Secret</label>
                    <input type="text" name="secret" class="form-control" required>
                </div>
                <button type="submit" name="dangky" class="btn btn-primary">Đăng ký</button>

                <div class="form-group mt-2">
                    Bạn đã có tài khoản? <a href="login.php" class="btn btn-link">Đăng Nhập</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>