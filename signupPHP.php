<?php
session_start();
require "DB.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") exit;

$name= trim($_POST["name"]);
$email= trim($_POST["email"]);
$phone = trim($_POST["phone"]);
$password = trim($_POST["password"]);

if (!$name || !$email || !$phone || !$password) {
 echo "All fields are required.";
 exit;
}
if (!preg_match('/^[A-Z][a-zA-Z\d\/\*\-\?\!@#\.]{7,}$/', $password)) {
 echo "Password does not meet the required criteria.";
 exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
 $stmt = $conn->prepare(
 "INSERT INTO users (name, email, phone, password)
VALUES (?, ?, ?, ?)"
 );
$stmt->bind_param("ssss", $name, $email, $phone, $hashedPassword);


    $stmt->execute();

$user_id = $conn->insert_id;
    
 
    if ($user_id > 0) {
        $_SESSION['user_id'] = $user_id;
     $_SESSION['user_name'] = $name;
     $_SESSION['user_email'] = $email;

 echo "Signup successful|$name";
    } else {

        echo "Signup successful, but login failed. Please log in.";
    }

} catch (mysqli_sql_exception $e) {
 if ($e->getCode() == 1062) {
 echo "This email is already registered. Please log in.";
 } else {
echo "Something went wrong. Please try again.";
 }
} finally {

    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
     
        $conn->close();
    }
}
?>
