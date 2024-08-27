<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../config/db.php'; 
include 'functions.php'; 
include '../frontend/header.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = clean_input($_POST['password']);
    $retype_password = clean_input($_POST['retype_password']);
    $errors = [];

    if (strlen($password) < 8 || !preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password)) {
        $errors[] = "Password must be at least 8 characters, contain at least one letter and one number.";
    }
    if ($password !== $retype_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);
        if ($stmt->execute()) {
            $stmt2 = $conn->prepare("INSERT INTO user_activities (user_id, activity) VALUES (?, ?)");
            if ($stmt2 === false) {
                die('Error preparing statement: ' . $conn->error);
            }
            $activity='Password update';
        $stmt2->bind_param("ss", $user_id,$activity );
        $stmt2->execute();
        $stmt2->close(); 
            echo "Password updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="change-password-form">
        <h2>Change Password</h2>
        <form action="change_password.php" method="POST">
            <label for="password">New Password:</label>
            <input type="password" name="password" required>
            
            <label for="retype_password">Retype New Password:</label>
            <input type="password" name="retype_password" required>
            
            <button type="submit" class="btn">Change Password</button>
        </form>
    </div>
</body>
</html>
<?php include('../frontend/footer.php'); ?>
