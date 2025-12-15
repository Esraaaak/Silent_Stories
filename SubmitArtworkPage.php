<?php
// Database configuration
$host = "localhost";
$dbname = "silent_stories";
$user = "root";
$pass = "";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("DB Connection Failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $artistName = $_POST['artistName'];
    $email = $_POST['email'];
    $artworkTitle = $_POST['artworkTitle'];
    $description = $_POST['description'];

    // Upload folder
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) { mkdir($uploadDir); }

    $fileName = time() . "_" . $_FILES['artworkFile']['name'];
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['artworkFile']['tmp_name'], $filePath)) {
        // Insert into DB
        $sql = "INSERT INTO artworks (artist_name, email, artwork_title, description, artwork_file)
                VALUES ('$artistName', '$email', '$artworkTitle', '$description', '$fileName')";
        if ($conn->query($sql)) {
            $message = "Artwork submitted successfully!";
        } else {
            $message = "DB Insert Error: " . $conn->error;
        }
    } else {
        $message = "Failed to upload file.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Submit Your Artwork</title>
<link rel="stylesheet" href="style.css">
<style>
main { padding-top:120px; text-align:center; }
form { display:flex; flex-direction:column; align-items:center; gap:15px; max-width:500px; margin:0 auto; background:#fff; padding:25px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.08); }
form input, form textarea { width:100%; padding:8px 10px; border:1px solid #ccc; border-radius:5px; }
form button { padding:10px 25px; background:#a06645; color:#fff; border:none; border-radius:5px; cursor:pointer; }
#preview { display:block; max-width:300px; margin:20px auto; }
.back-home { display:inline-block; margin-bottom:50px; text-decoration:none; color:#a06645; }
</style>
</head>
<body>
<header>
<div class="logo">Silent Stories</div>
<nav class="nav-links">
<a href="SilentStoriesHome.html" style="color:#a06645;">Home</a>
<a href="ArtistsPage.html">Artists</a>
<a href="SubmitArtworkPage.php">Submit</a>
</nav>
</header>
<main>
<h1>Submit Your Artwork</h1>

<?php if($message) echo "<p style='color:green;'>$message</p>"; ?>

<form id="artworkForm" action="" method="POST" enctype="multipart/form-data">
<label for="artistName">Artist Name:</label>
<input type="text" id="artistName" name="artistName" required>

<label for="email">Email:</label>
<input type="email" id="email" name="email" required>

<label for="artworkTitle">Artwork Title:</label>
<input type="text" id="artworkTitle" name="artworkTitle" required>

<label for="description">Description:</label>
<textarea id="description" name="description" rows="4" required></textarea>

<label for="artworkFile">Upload Artwork:</label>
<input type="file" id="artworkFile" name="artworkFile" accept="image/*" required>

<button type="submit">Submit</button>
</form>

<img id="preview" src="" alt="Artwork Preview">

<a href="SilentStoriesHome.html" class="back-home">Back to Home</a>
</main>

<footer>
<p>© 2025 Silent Stories. All rights reserved.</p>
</footer>

<script>
// Preview selected image
document.getElementById('artworkFile').onchange = function() {
    const file = this.files[0];
    if (file) {
        document.getElementById('preview').src = URL.createObjectURL(file);
    } else {
        document.getElementById('preview').src = '';
    }
};
</script>
</body>
</html>
