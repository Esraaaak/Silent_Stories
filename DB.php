<?php
$conn = new mysqli("localhost", "root", "", "silent_stories");

if ($conn->connect_error) {
    die("Database connection failed");
}
?>
