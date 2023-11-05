<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $email = $_POST["email"];
    $password = md5($_POST["password"]);

    // Check if email is already in use
    $emailCheck = $conn->prepare("SELECT COUNT(*) FROM `signup` WHERE `email` = ?");
    $emailCheck->bind_param("s", $email);
    $emailCheck->execute();
    $emailCheck->bind_result($emailCount);
    $emailCheck->fetch();
    $emailCheck->close();

    if ($emailCount > 0) {
        echo "<script>alert('Email already in use.');</script>";
    } else {
        // Check if ID is already in use
        $idCheck = $conn->prepare("SELECT COUNT(*) FROM `signup` WHERE `u_id` = ?");
        $idCheck->bind_param("i", $id);
        $idCheck->execute();
        $idCheck->bind_result($idCount);
        $idCheck->fetch();
        $idCheck->close();

        if ($idCount > 0) {
            echo "<script>alert('ID already in use.');</script>";
        } else {
            // Both email and ID are unique, insert the new record
            $sql = "INSERT INTO `signup`(`u_id`, `email`, `password`) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $id, $email, $password);

            if ($stmt->execute()) {
                $ok = "\n";
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'little.library261@gmail.com';
                $mail->Password = 'wuyomlqgwywhlzpy';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;
                $mail->setFrom('little.library261@gmail.com');
                $mail->addAddress($email);
                $mail->Subject = "UIU FYDP HORIZON'S Login Info ";
                $mail->Body = "Dear, $id" . ". Welcome to UIU FYDP HORIZONS. Your Login ID : $id  & Password is : 1234";
                $mail->send();

                echo "<script>alert('Registration successful.');</script>";
                echo "<script>window.location.href = 'index.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }

            $stmt->close();
        }
    }
}
?>
