<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';

if (!isset($_SESSION['payment_filename'])) {
    die("Không tìm thấy hóa đơn để gửi mail.");
}

$toEmail = $_SESSION['email'] ?? null;
$username = $_SESSION['login']['username'] ?? 'Khách hàng';
$filePath = $_SESSION['payment_filename'];

if (!$toEmail || !file_exists($filePath)) {
    die("Thiếu email hoặc file hóa đơn.");
}

$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';   
try {
    // SMTP config
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'dangvietdung2903@gmail.com'   ; // email gửi
    $mail->Password   = 'fssyqhwtypzceits';  // mật khẩu ứng dụng
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Người gửi & nhận
    $mail->setFrom('dangvietdung2903@gmail.com', 'Góc Sách Nhỏ');
    $mail->addAddress($toEmail, $username);

    // Đính kèm hóa đơn
    $mail->addAttachment($filePath);

    // Nội dung
    $mail->isHTML(true);
    $mail->Subject = 'Hóa đơn mua hàng - Góc Sách Nhỏ';
    $mail->Body    = "
        <p>Xin chào <b>$username</b>,</p>
        <p>Cảm ơn bạn đã mua hàng tại <b>Góc Sách Nhỏ</b>.</p>
        <p>Hóa đơn mua hàng được đính kèm trong email này.</p>
        <p>Chúc bạn đọc sách vui vẻ 📚</p>
    ";

    $mail->send();

    unset($_SESSION['payment_success']);
    // Gửi xong thì quay lại payment
    $_SESSION['mail_success'] = true;
        exit;

} catch (Exception $e) {
    echo "Gửi mail thất bại: {$mail->ErrorInfo}";
}