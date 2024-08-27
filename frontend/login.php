<?php
include '../config/db.php'; 
include '../backend/functions.php'; 
include('header.php'); 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = clean_input($_POST['email']);
    $password = clean_input($_POST['password']);
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            if ($user['status'] == 'active') {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['privilege'] = $user['privilege'];
                $stmt2 = $conn->prepare("INSERT INTO user_activities (user_id, activity) VALUES (?, ?)");
                if ($stmt2 === false) {
                    die('Error preparing statement: ' . $conn->error);
                }
                $activity='Login';
            $stmt2->bind_param("ss", $user_id,$activity );
            $stmt2->execute();
            $stmt2->close(); 
                header("Location: my_account.php");
                exit();
            } else {
                echo "<p style='color:red;'>Your account is inactive. Please activate your account via the email sent to you.</p>";
            }
        } else {
            echo "<p style='color:red;'>Invalid password.</p>";
        }
    } else {
        echo "<p style='color:red;'>No account found with that email.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-form">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            
            <button type="submit" class="btn">Login</button>
        </form>
        <p><a href="../backend/forget_password.php">Forgot Password?</a></p>
    </div>
</body>
</html>
<?php include('footer.php'); ?>
