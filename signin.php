<?php
include 'db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $password = md5($_POST['password']);
    $sql = "SELECT * FROM signup WHERE u_id='$id' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    $data = $result->fetch_assoc();
    $num = mysqli_num_rows($result);

    if ($num == 1) {
        session_start();
        $_SESSION['id'] = $data['u_id'];
        $_SESSION['email'] = $data['email'];
 
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'little.library261@gmail.com'; 
        $mail->Password = 'wuyomlqgwywhlzpy'; 
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom('little.library261@gmail.com'); 
        $mail->addAddress($data['email']); 
        $mail->Subject = "Login Successful";
        $mail->Body = "Dear {$data['u_id']}, You are logged in successfully at " . date('Y-m-d H:i:s');
        
        try {
            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        header("Location: index.php");
    } else {
        $error_msg = "Incorrect id or password";
        echo "<script>alert('$error_msg');</script>";
    }
}
?>
