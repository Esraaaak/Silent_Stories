<?php
require "DB.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") exit;

$email = trim($_POST["email"]);
$password = trim($_POST["password"]);

if (!$email || !$password) {
    echo "Please fill all fields.";
    exit;
}

$stmt = $conn->prepare(
    "SELECT name, password FROM users WHERE email = ?"
);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if (password_verify($password, $row["password"])) {
        echo "Login successful|".$row["name"];
    } else {
        echo "Incorrect password.";
    }
} else {
    echo "Email not found.";
}
