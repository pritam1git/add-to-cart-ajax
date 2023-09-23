<?php
// Include PHPMailer
require 'db.php';

// Create a PHPMailer instance


// Enable debugging (optional)
$mail->SMTPDebug = 0; // Set to 2 for debugging

// Set mailer to use SMTP
$mail->isSMTP();

// SMTP server settings
$mail->Host = 'localhost';  // Specify your SMTP server
$mail->SMTPAuth = true;            // Enable SMTP authentication
$mail->Username = 'root'; // SMTP username
$mail->Password = '';     // SMTP password
$mail->SMTPSecure = '';        // Enable TLS encryption, 'ssl' also accepted
$mail->Port = 8000;                // TCP port to connect to

// Sender information
$mail->setFrom('pritamsood123@gmail.com', 'pritam');

// Recipient email address
$to = 'pritam.up2mark@gmail.com';

// Email subject and body
$mail->Subject = 'Confirmation Email';
$mail->Body = 'Thank you for registering on our website.';

// Add recipient
$mail->addAddress($to);

// Send the email
if ($mail->send()) {
    echo 'Confirmation email sent successfully.';
} else {
    echo 'Confirmation email could not be sent. Error: ' . $mail->ErrorInfo;
}
?>
