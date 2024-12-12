<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader (if using Composer)
require 'vendor/autoload.php';

// If not using Composer, manually include the following:
// require 'path/to/PHPMailer/src/PHPMailer.php';
// require 'path/to/PHPMailer/src/Exception.php';
// require 'path/to/PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true); // Passing `true` enables exceptions

try {
    // Server settings
    $mail->SMTPDebug = 0;                               // Disable verbose debug output
    $mail->isSMTP();                                    // Set mailer to use SMTP
    $mail->Host       = 'smtp.gmail.com';             // Specify main and backup SMTP servers (replace with your SMTP host)
    $mail->SMTPAuth   = true;                           // Enable SMTP authentication
    $mail->Username   = 'servicehub343@gmail.com';       // SMTP username
    $mail->Password   = 'czzx vdln tpfu keoq';                // SMTP password
    $mail->SMTPSecure = 'tls';                          // Enable TLS encryption, `ssl` is also accepted
    $mail->Port       = 587;                            // TCP port to connect to (587 for TLS, 465 for SSL)

    // Recipients
    $mail->setFrom('servicehub343@gmail.com', 'Don'); // Sender's email address and name
    $mail->addAddress('donprince2003428@gmail.com', 'don'); // Add a recipient (email and name)
    $mail->addReplyTo('servicehub343@gmail.com', 'Information'); // Reply-To address

    // Attachments (optional)
    // $mail->addAttachment('/path/to/file');         // Add attachments if needed
    // $mail->addAttachment('/path/to/image.jpg', 'new_name.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                               // Set email format to HTML
    $mail->Subject = 'Here is the subject';            // Email subject
    $mail->Body    = 'This is the <b>HTML</b> message body.';  // Email body (HTML)
    $mail->AltBody = 'This is the plain text version for non-HTML clients'; // Plain text version

    // Send the email
    $mail->send();
    echo 'Message has been sent successfully';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
