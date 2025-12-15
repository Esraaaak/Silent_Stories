<?php
require "DB.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") exit;

$name = trim($_POST["name"]);
$email = trim($_POST["email"]);
$message = trim($_POST["message"]);

if (!$name || !$email || !$message) {
    echo "All fields are required.";
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO contact_messages (name, email, message)
     VALUES (?, ?, ?)"
);
$stmt->bind_param("sss", $name, $email, $message);

if ($stmt->execute()) {
    echo "Message sent successfully. Thank you!";
} else {
    echo "Something went wrong. Please try again.";
}
