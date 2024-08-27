<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../config/db.php';
include('header.php'); 

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="account-page">
        <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
        <a href="logout.php" class="btn">Log Out</a>
        <a href="../backend/change_password.php" class="btn">Change Password</a>
    </div>
</body>
</html>
