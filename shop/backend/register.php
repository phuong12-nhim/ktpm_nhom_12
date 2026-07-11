<?php
session_start();
ob_start();
include_once 'connect.php';

$errors = [
    'username' => '',
    'password' => '',
    'general' => ''
];

if (isset($_POST['dangky']) && !empty($_POST['username']) && !empty($_POST['password']) && isset($_POST['level'])) {
    
    $name = trim($_POST['username']);
    $pas = $_POST['password']; 
    $lev = htmlspecialchars($_POST['level']);

    // 1. Kiểm tra định dạng Email (@gmail.com)
    if (!filter_var($name, FILTER_VALIDATE_EMAIL) || strpos($name, '@gmail.com') === false) {
        $errors['username'] = "Email không hợp lệ! Vui lòng sử dụng định dạng ...@gmail.com";
    } else {
        // Kiểm tra trùng username
        $checkUser = "SELECT id FROM admin WHERE username = '$name' LIMIT 1";
        $result = mysqli_query($conn, $checkUser);
        if (mysqli_num_rows($result) > 0) {
            $errors['username'] = "Tên đăng nhập đã tồn tại.";
        }
    }

    // 2. Kiểm tra độ dài mật khẩu (4 - 16 ký tự)
    if (strlen($pas) < 4 || strlen($pas) > 16) {
        $errors['password'] = "Mật khẩu phải từ 4 đến 16 ký tự.";
    }

    // 3. Nếu KHÔNG có lỗi → Thực hiện insert DB
    if (empty($errors['username']) && empty($errors['password'])) {
        $pas_hashed = md5($pas);

        $sql = "INSERT INTO admin(username, password, level) VALUES ('$name', '$pas_hashed', '$lev')";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['register_success'] = true;
            header("Location: login.php");
            exit;
        } else {
            $errors['general'] = "Đăng ký tài khoản thất bại.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body { background-image: linear-gradient(#f4d6cf, #8eccf5); font-family: Arial, sans-serif; }
        .container { display: flex; justify-content: center; align-items: center; height: 100vh; }
        .form-container { max-width: 400px; width: 100%; padding: 20px; background-color: white; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); }
        .btn-primary { background-color: #007bff; border-color: #007bff; width: 100%; padding: 10px; }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="form-container">
            <form action="register.php" method="post">
                <legend>Đăng Ký</legend>
                
                <?php if (!empty($errors['general'])) { echo '<div class="alert alert-danger">'.$errors['general'].'</div>'; } ?>

                <div class="form-group">
                    <label>Tên đăng nhập</label>
                    <input type="text" name="username" 
                    class="form-control <?php echo !empty($errors['username']) ? 'is-invalid' : ''; ?>" 
                    value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>

                    <div class="invalid-feedback">
                        <?php echo $errors['username']; ?>
                    </div>
                    <?php if (!empty($errors['username'])) { ?>
                        <small class="text-danger"><?php echo $errors['username']; ?></small>
                    <?php } ?>
                </div>

                <div class="form-group">
                    <label>Mật khẩu</label>
                    <input type="password" name="password" 
                           class="form-control <?php echo !empty($errors['password']) ? 'is-invalid' : ''; ?>" required>
                    <?php if (!empty($errors['password'])) { ?>
                        <small class="text-danger"><?php echo $errors['password']; ?></small>
                    <?php } ?>
                </div>
            
                <div class="form-group">
                    <label>Level</label>
                    <input type="number" name="level" class="form-control" value="1" required>
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