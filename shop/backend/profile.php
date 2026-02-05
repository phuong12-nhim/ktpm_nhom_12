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

if (isset($_POST['update'])) {
    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $sdt    = trim($_POST['sdt']);
    if ($fullname == '') {
        $error = 'Họ tên không được để trống';
    } elseif ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ';
    }

    if (empty($error)) {
        $update = "
            UPDATE admin 
            SET fullname='$fullname',
                email='$email',
                sdt='$sdt'
            WHERE id=$admin_id
        ";

        if (mysqli_query($conn, $update)) {
            $success = 'Cập nhật thành công';

            // Load lại dữ liệu mới
            $result = mysqli_query($conn, $sql);
            $admin = mysqli_fetch_assoc($result);
        } else {
            $error = 'Cập nhật thất bại';
        }
    }
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