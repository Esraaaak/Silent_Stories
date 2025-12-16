<?php
session_start();
require "DB.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") exit;

$email = trim($_POST["email"]);
$password = trim($_POST["password"]);

if (!$email || !$password) {
    echo "Please fill all fields.";
    exit;
}

$stmt = $conn->prepare(
"SELECT id, name, email, password FROM users WHERE email = ?"
);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if (password_verify($password, $row["password"])) {
        
        // 🚨 التعديل: التحقق من وجود المفاتيح قبل استخدامها
        if (isset($row["id"]) && isset($row["name"]) && isset($row["email"])) {
            
            $_SESSION['user_id'] = $row["id"];
            $_SESSION['user_name'] = $row["name"];
            $_SESSION['user_email'] = $row["email"];
            
            echo "Login successful|" . $row["name"];
        } else {
            echo "Login failed: Missing required user data.";
        }
        
    } else {
        echo "Incorrect password.";
    }
} else {
    echo "Email not found.";
}

if (isset($stmt)) {
    $stmt->close();
}
?>