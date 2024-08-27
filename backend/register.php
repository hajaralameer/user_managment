<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../autoload.php';
include '../config/db.php'; 
include 'functions.php'; 
include '../frontend/header.php'; 

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean_input($_POST['name']);
    $email = clean_input($_POST['email']);
    $phone = clean_input($_POST['phone']);
    $password = clean_input($_POST['password']);
    $retype_password = clean_input($_POST['retype_password']);
    $errors = [];

    
    if (strlen($name) < 3 || !preg_match("/^[a-zA-Z ]*$/", $name)) {
        $errors[] = "Name must be at least 3 letters and contain only letters.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        if (email_exists($email)) {
            $errors[] = "Email already exists.";
        }
    }
    if (!preg_match("/^((\+9                                                                                                                                                                                    67)|(00967)|(7))?[7][0137]\d{7}$/", $phone)) {
        $errors[] = "Invalid Yemeni phone number.";
    } else {
        if (phone_exists($phone)) {
            $errors[] = "Phone number already exists.";
        }
    }
    if (strlen($password) < 8 || !preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password)) {
        $errors[] = "Password must be at least 8 characters, contain at least one letter and one number.";
    }
    if ($password !== $retype_password) {
        $errors[] = "Passwords do not match.";
    }

   
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $status = "inactive";
        $privilege = "client";
        
        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, privilege, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $phone, $hashed_password, $privilege, $status);
        if ($stmt->execute()) {
            
           
            $mail = new PHPMailer(true);
            try {
                
                $mail->isSMTP();
                $mail->Host       = 'smtp.example.com'; 
                $mail->SMTPAuth   = true;               
                $mail->Username   = 'your-email@example.com'; 
                $mail->Password   = 'your-password'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                $mail->setFrom('your-email@example.com', 'Your Name');
                $mail->addAddress($email, $name);

                $mail->isHTML(true);
                $mail->Subject = 'Activate your account';
                $mail->Body    = "Please click the following link to activate your account: <a href='http://yourdomain.com/activate.php?email=$email'>Activate Account</a>";

                $mail->send();
                echo "Registration successful! Please check your email to activate your account.";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo implode("<br>", $errors); 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="register-form">
        <h2>Register</h2>
        <form id="registerForm" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="retype_password">Retype Password:</label>
            <input type="password" id="retype_password" name="retype_password" required>
            
            <button type="submit" class="btn">Register</button>
        </form>
        <div id="responseMessage"></div> 
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#registerForm').on('submit', function(event) {
                event.preventDefault(); 

                if ($('#password').val() !== $('#retype_password').val()) {
                    alert('Passwords do not match.');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: 'register.php',
                    data: $(this).serialize(), 
                    success: function(response) {
                        $('#responseMessage').html(response); 
                    }
                });
            });
        });
    </script>
</body>
</html>
<?php include('../frontend/footer.php'); ?>