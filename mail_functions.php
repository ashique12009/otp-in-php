<?php
require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOTP($otp) {
    $phpmailer = new PHPMailer(true);
    try {
        // Server settings
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = '8c47d5a7abace5';
        $phpmailer->Password = '7b69ef42c5e37e';
        // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    
        // Recipients
        $phpmailer->setFrom('ashique-otp@local.com', 'Ashique OTP');
        $phpmailer->addAddress('ashique12009@yahoo.com', 'Ashique User');     // Add a recipient
        $phpmailer->addReplyTo('info@example.com', 'Information');
        $phpmailer->addCC('cc@example.com');
        $phpmailer->addBCC('bcc@example.com');
    
        // Content
        $phpmailer->isHTML(true);                                  // Set email format to HTML
        $phpmailer->Subject = 'Take your OTP';
        $phpmailer->Body    = 'Your OTP is: ' . $otp;
        $phpmailer->AltBody = 'Your OTP is: ' . $otp;
    
        return $phpmailer->send();
    } 
    catch (Exception $e) {
        echo 'Email failed';
    }
}