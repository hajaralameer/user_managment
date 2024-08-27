<?php
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function email_exists($email) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

function phone_exists($phone) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}
?>
