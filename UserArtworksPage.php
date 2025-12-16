<?php
// DB connection
$host = "localhost";
$dbname = "silent_stories";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("DB Connection Failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM artworks ORDER BY submission_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Artworks</title>
    <link rel="stylesheet" href="style.css">


    <style>
        main {
            padding-top: 120px; /* space for fixed header */
            padding-bottom: 60px;
        }

        .artworks-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .art-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: transform 0.3s;
        }

        .art-card:hover {
            transform: translateY(-5px);
        }

        .image-container img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            display: block;
        }

        .card-details {
            padding: 15px;
            text-align: center;
        }

        .art-title {
            font-size: 1.1rem;
            margin: 8px 0;
            font-weight: 600;
        }

        .artist-name {
            font-size: 0.9rem;
            color: #666;
        }

        .page-title {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>

<!-- ===== HEADER  ===== -->
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

<!-- ===== MAIN ===== -->
<main>
  
  <h1 style="
    font-family: 'Cormorant Garamond', serif;   /* Elegant font for a classic look */
    font-size: 4rem;                             /* Very large size for page title */
    color: #2c2a29;                              /* Dark color for strong visibility */
    text-align: center;                           /* Center the title on the page */
    margin: 60px 0 30px 0;                        /* Top and bottom spacing */
    text-shadow: 2px 2px 6px rgba(0,0,0,0.15);  /* Light shadow for depth */
">
    User Artworks
</h1>

    <div class="artworks-container">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $img = "uploads/" . $row['artwork_file'];
                $title = htmlspecialchars($row['artwork_title']);
                $artist = htmlspecialchars($row['artist_name']);
                echo '
                <div class="art-card">
                    <div class="image-container">
                        <img src="'.$img.'" alt="'.$title.'">
                    </div>
                    <div class="card-details">
                        <h3 class="art-title">'.$title.'</h3>
                        <div class="artist-name">'.$artist.'</div>
                    </div>
                </div>';
            }
        } else {
            echo "<p style='text-align:center;'>No artworks submitted yet.</p>";
        }
        ?>
    </div>
</main>

<!-- ===== FOOTER  ===== -->
<footer>
    <p>© 2025 Silent Stories. All rights reserved.</p>
    <p>
        <a href="aboutPage.html">About</a> | 
        <a href="ArtistsPage.html">Artists</a> | 
        <a href="Contact.html">Contact us</a>
    </p>
</footer>

</body>
</html>

<?php $conn->close(); ?>





