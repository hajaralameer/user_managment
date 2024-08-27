<?php
include '../config/db.php'; 
include 'functions.php'; 
include '../frontend/header.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = clean_input($_POST['input']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $reset_link = "http://yourdomain.com/reset_password.php?email=" . $user['email'];
           echo "A password reset link has been sent to your email.";
    } else {
        echo "<p style='color:red;'>No account found with that email or phone number.</p>";
    }
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
<?php include('../frontend/footer.php'); ?>