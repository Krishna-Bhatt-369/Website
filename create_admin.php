<?php
include 'connect.php';

$user = 'admin';       
$pass = 'admin123';     


$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo "Admin user already exists. Delete or modify it in phpMyAdmin if needed.";
    exit;
}
$stmt->close();

$hash = password_hash($pass, PASSWORD_DEFAULT);
$ins = $conn->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, 1)");
$ins->bind_param("ss", $user, $hash);
if ($ins->execute()) {
    echo "Admin created: username='$user' password='$pass' — delete this file now for security.";
} else {
    echo "Error: " . $conn->error;
}
$ins->close();
?>
