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

.action-buttons {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    margin-top: 25px;
}

.soft-btn {
    display: inline-block;
    padding: 9px 24px;
    background-color: #e6dfd6;        /* slightly darker beige */
    color: #4a3f36;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.95rem;
    border: 1px solid #c9bfb4;        /* soft border */
    transition: background-color 0.3s, color 0.3s, border-color 0.3s;
}

.soft-btn:hover {
    background-color: #d8cec3;
    color: #2f2620;
    border-color: #b8ab9d;
}




</style>

<header>
    <div class="logo">Silent Stories</div>
    <nav class="nav-links">
        <a href="Silent storiest home.html" style="color:var(--accent);">Home</a>
        <a href="ArtExhibitions.html">Exhibitions</a>
        <a href="classicPage.html">Classic</a>
        <a href="modernPage.html">Modern</a>
        <a href="ArtistsPage.html">Artists</a>
        <a href="SubmitArtworkPage.php">Submit</a>
        <a href="aboutPage.html">About</a>
        <a href="Contact.html">Contact</a>

        <span class="auth-links">
            <a href="login.html">Login</a>
            <a href="signup.html">Sign Up</a>
        </span>
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

<div class="action-buttons">
    <a href="Silent stories home.html" class="soft-btn">Back to Home</a>

    <a href="UserArtworksPage.php" class="soft-btn">View User Artworks</a>

    </a>
</div>

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

