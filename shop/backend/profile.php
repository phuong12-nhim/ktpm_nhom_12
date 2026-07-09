<?php
require_once 'connect.php';
include 'header.php';

if (!isset($_SESSION['admin']['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['admin']['username'];

$sql = "SELECT id, username, fullname, email, sdt 
        FROM admin 
        WHERE username = '$username'";

$result = mysqli_query($conn, $sql);
$admin = mysqli_fetch_assoc($result);

$admin_id = $admin['id'];

$error = '';
$success = '';

if (isset($_POST['update'])) {
    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $sdt      = trim($_POST['sdt']);

    // Validate
    if ($fullname === '') {
        $error = 'Họ tên không được để trống';
    } elseif ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ';
    }

    // Chỉ update khi KHÔNG có lỗi
    if ($error === '') {
        $stmt = mysqli_prepare($conn, "UPDATE admin SET fullname=?, email=?, sdt=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sssi", $fullname, $email, $sdt, $admin_id);

        if (mysqli_stmt_execute($stmt)) {
            $success = 'Cập nhật thành công';
        } else {
            $error = 'Cập nhật thất bại';
        }

        mysqli_stmt_close($stmt);
    }

    // Load lại dữ liệu từ DB sau khi submit
    $result = mysqli_query($conn, $sql);
    $admin = mysqli_fetch_assoc($result);
}
?>

<?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
<?php if (!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

<form method="POST">
    <div class="form-group">
        <label>Họ tên</label>
        <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($admin['fullname']) ?>">
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="text" name="email" class="form-control" value="<?= htmlspecialchars($admin['email']) ?>">
    </div>

    <div class="form-group">
        <label>SĐT</label>
        <input type="text" name="sdt" class="form-control" value="<?= htmlspecialchars($admin['sdt']) ?>">
    </div>

    <button type="submit" name="update" class="btn btn-primary">
        Cập nhật
    </button>
</form>