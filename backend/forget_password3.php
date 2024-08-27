<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Load Composer's autoloader

include 'db.php'; // Include database connection
include 'functions.php'; // Include helper functions

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = clean_input($_POST['input']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $reset_link = "http://yourdomain.com/reset_password.php?email=" . $user['email'];
        
        // Send reset email
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();                                    
            $mail->Host       = 'smtp.example.com'; // Set the SMTP server to send through
            $mail->SMTPAuth   = true;               
            $mail->Username   = 'your-email@example.com'; // SMTP username
            $mail->Password   = 'your-password'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            //Recipients
            $mail->setFrom('your-email@example.com', 'Your Name');
            $mail->addAddress($user['email'], $user['name']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Reset your password';
            $mail->Body    = "Click this link to reset your password: <a href='$reset_link'>Reset Password</a>";

            $mail->send();
            $message = "A password reset link has been sent to your email.";
        } catch (Exception $e) {
            $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $message = "No account found with that email or phone number.";
    }

    echo "<script>alert('$message');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="forget-password-form">
        <h2>Forget Password</h2>
        <form action="forget_password.php" method="POST">
            <label for="input">Enter your Email or Phone number:</label>
            <input type="text" name="input" required>
            
            <button type="submit" class="btn">Send</button>
        </form>
    </div>
</body>
</html>
