<?php
session_start();

session_unset();

session_destroy();

header("Location:/silent_stories/ArtExhibitions.php"); 
exit;
?>
