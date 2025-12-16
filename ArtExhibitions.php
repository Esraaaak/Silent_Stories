<?php

session_start();
include 'DB.php';

// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$user_id = $is_logged_in ? $_SESSION['user_id'] : 0;
$user_name = $is_logged_in ? $_SESSION['user_name'] : 'Gallery Guest'; // Store user information or default guest values
$user_email = $is_logged_in ? $_SESSION['user_email'] : 'N/A';

// Fetch wishlist event IDs for the logged-in user
$wishlist_events_ids = [];
if ($is_logged_in) {
 $sql_wishlist = "SELECT event_id FROM wishlist WHERE user_id = ?";
 $stmt = $conn->prepare($sql_wishlist);
$stmt->bind_param("i", $user_id);
$stmt->execute();
 $result_wishlist = $stmt->get_result();
while($row = $result_wishlist->fetch_assoc()) {
 $wishlist_events_ids[] = $row['event_id'];
 }
 $stmt->close();
}
$wishlist_count = count($wishlist_events_ids);

// Fetch all events from the database
$sql_events = "SELECT id, title, event_date, location, description, image_url FROM events ORDER BY id ASC";
$result_events = $conn->query($sql_events);
// Store events in an array
$events = []; 
if ($result_events && $result_events->num_rows > 0) {
 while($row = $result_events->fetch_assoc()) {
 $events[] = $row;
 }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Art Exhibitions</title>

<link rel="stylesheet" href="ArtExhibitionscss.css">

<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
<style>
:root {
--bg-body: #F2F0EB;
--text-primary: #2c2a29;
--text-secondary: #4a4a4a;
--accent: #b07c55;
--white-trans: rgba(255, 255, 255, 0.98);
}

body { font-family: 'Montserrat', sans-serif; margin: 0; padding-top: 60px; background-color: var(--bg-body); color: var(--text-primary); }

header {
position: fixed;
top: 0;
width: 100%;
padding: 18px 40px;
background: var(--white-trans);
border-bottom: 1px solid #e6e6e6;
z-index: 1000;

display: flex;
align-items: center;
justify-content: space-between;
}

/* Logo */
header .logo {
font-family: 'Cormorant Garamond', serif;
font-size: 26px;
font-weight: 600;
color: var(--text-primary);
letter-spacing: 0.5px;
}

/* Navigation Container */
.nav-links {
display: flex;
align-items: center;
gap: 20px;
}

/* Navigation Links Style (TEXT) */
.nav-links a {
font-family: 'Montserrat', sans-serif;
font-size: 11px;
text-transform: uppercase;
letter-spacing: 1.5px;
font-weight: 500;

color: var(--text-primary);
text-decoration: none;

transition: color 0.3s ease;
}

/* Hover line effect */
.nav-links a::after {
display: none !important;
}

.nav-links a:hover {
color: var(--accent);
}

/* Auth links (LOGIN SIGN UP)*/
.auth-links {

border-left: 1px solid #ccc;
padding-left: 20px;
margin-left: 5px;
}

.auth-links a {

font-weight: 600;
letter-spacing: 2px;
}



#wishlistBtn {
background-color: var(--text-primary);
color: white;
border: none;
padding: 8px 15px;
border-radius: 5px;
cursor: pointer;
font-weight: bold;
margin-right: 10px;
transition: background-color 0.3s;
}
#wishlistBtn:hover {
background-color: var(--text-secondary);
}
.modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.7); justify-content: center; align-items: center; }
.modal-content { background-color: var(--bg-body); padding: 30px; border-radius: 8px; box-shadow: 0 0 20px rgba(0,0,0,0.4); text-align: center; position: relative; color: var(--text-primary); max-width: 450px; }
.close-modal { color: var(--text-secondary); font-size: 28px; font-weight: bold; cursor: pointer; position: absolute; top: 10px; right: 15px; }
.modal-btn { background-color: var(--accent); color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1rem; margin: 10px 5px; transition: background-color 0.3s; }
.modal-btn:hover { background-color: #926442; }
.details-box { text-align: left; background-color: #fff; border: 1px solid #ddd; padding: 15px; margin: 20px 0; border-radius: 5px; }
.details-box p { margin: 5px 0; line-height: 1.4; font-size: 0.95rem; color: var(--text-primary); }
footer { background-color: var(--bg-body); border-top: 1px solid #dcdcdc; padding: 20px; text-align: center; margin-top: 40px; }
footer p { font-size: 12px; color: var(--text-secondary); margin-bottom: 5px; }
footer a { color: var(--text-secondary); text-decoration: none; margin: 0 5px; }
#scrollTopBtn { display: none; position: fixed; bottom: 20px; right: 30px; z-index: 99; border: none; outline: none; background-color: var(--accent); color: white; cursor: pointer; padding: 15px; border-radius: 50%; font-size: 18px; transition: opacity 0.3s; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
#scrollTopBtn:hover { background-color: #926442; }
</style>
</head>
<body>
 <!-- Fixed header navigation bar -->
<header>
<div class="nav-left">
</div>
<div class="logo">Silent Stories</div>
<nav class="nav-links">
<a href="Silent storiest home.html" style="color:var(--accent);">Home</a>
<a href="ArtExhibitions.php">Exhibitions</a>
<a href="classicPage.html">Classic</a>
<a href="modernPage.html">Modern</a>
<a href="ArtistsPage.html">Artists</a>
<a href="SubmitArtworkPage.html">Submit</a>
<a href="aboutPage.html">About</a>
<a href="Contact.html">Contact</a>
 <!-- Display welcome message if user is logged in -->
<?php if ($is_logged_in): ?>
<span class="auth-links" style="font-weight: 600; color: var(--accent);">Welcome, <?php echo htmlspecialchars($user_name); ?></span>
<?php else: ?>
<span class="auth-links">
<a href="login.html">Login</a>
<a href="signup.html">Sign Up</a>
</span>
<?php endif; ?>
</nav>
<div>
<button id="wishlistBtn" onclick="showWishlistModal()">My Wishlist (<?php echo $wishlist_count; ?>)</button>
<?php if ($is_logged_in): ?>
<button id="logoutBtn" onclick="window.location.href='logout.php'">Logout</button> <?php endif; ?>
</div>
</header>

<div class="hero-section">
<span class="sub-headline">CURATED EXHIBITION</span>
<h1>Upcoming Events & Workshops</h1>
<p>Explore our schedule of public events, special tours, and hands-on workshops designed for art lovers of all ages.</p>
<button class="view-collection-btn">VIEW SCHEDULE</button>
</div>
<!-- Loop through all events and display them as cards -->
<div id="gallery">
<?php foreach ($events as $event):
$event_id = $event['id'];

$is_liked = in_array($event_id, $wishlist_events_ids);
$like_icon = $is_liked ? "❤️" : "♡";
?>
<div class="art-card event-type" data-event-id="<?php echo $event_id; ?>">
<div class="image-container">
<img src="<?php echo htmlspecialchars($event['image_url']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">

<span class="like-btn" onclick="toggleLike(this, <?php echo $event_id; ?>)"><?php echo $like_icon; ?></span>
</div>
<div class="card-details">
<h2><?php echo htmlspecialchars($event['title']); ?></h2>

<p class="event-date"><?php echo htmlspecialchars($event['event_date']); ?> — <?php echo htmlspecialchars($event['location']); ?></p>
<p class="event-description"><?php echo htmlspecialchars($event['description']); ?></p>
<button class="signup-button-inline" onclick="registerForEvent('<?php echo htmlspecialchars($event['title']); ?>', '<?php echo htmlspecialchars($event['event_date']); ?>', <?php echo $event_id; ?>)">Sign up Now!</button>
</div>
</div>
<?php endforeach; ?>
</div>

<div id="choiceModal" class="modal">
<div class="modal-content">
<span class="close-modal" onclick="closeChoiceModal()">✕</span>
<h2 style="color: var(--accent);">Get Started!</h2>
<p>To continue, please sign in or register:</p>
<h3 id="choiceEventTitle" style="margin-bottom: 25px; color: var(--text-primary);"></h3>
<p style="font-size: 1.1rem; font-weight: bold;">Do you already have an account?</p>

 <button class="modal-btn" onclick="redirectToLoginChoice()">Yes, Log In</button>
<button class="modal-btn" style="background-color: #555;" onclick="redirectToSignupChoice()">No, Sign Up</button>
</div>
</div>

<div id="fancyModal" class="modal">
<div class="modal-content">
<span class="close-modal" onclick="closeFancyModal()">✕</span>

<div id="registrationSuccessView" style="display: block;">
    <h2 style="color: green;">🎉 Registration Confirmed!</h2>
<p style="font-size: 1.1rem; font-weight: 500;">You are registered for:</p>
<h3 id="successEventTitle" style="color: var(--accent); margin-top: 5px;"></h3>
<div class="details-box">
<p><strong>Attendee:</strong> <span id="successName"></span></p>
<p><strong>Email:</strong> <span id="successEmail"></span></p>
<p><strong>Date & Time:</strong> <span id="successDate"></span></p>
</div>
<button class="modal-btn" onclick="closeFancyModal()">Done</button>
</div>
</div>
</div>
<!-- Modal shown when user must login or signup -->
<div id="wishlistModal" class="modal">
<div class="modal-content" style="max-width: 600px;">
<span class="close-modal" onclick="closeWishlistModal()">✕</span>
<h2 style="color: var(--accent);">💖 My Wishlist</h2>
<div id="wishlistContent" style="text-align: center; margin-top: 20px;">
</div>
<button class="modal-btn" onclick="closeWishlistModal()">Close</button>
</div>
</div>


<button id="scrollTopBtn" title="Go to top">↑</button>

<script>
// Store login status and user info from PHP
 const IS_LOGGED_IN = <?php echo json_encode($is_logged_in); ?>;
 const USER_NAME = <?php echo json_encode($user_name); ?>;
 const USER_EMAIL = <?php echo json_encode($user_email); ?>; 

 function closeFancyModal() {
document.getElementById("fancyModal").style.display = "none";
}

 function closeChoiceModal() {
 document.getElementById("choiceModal").style.display = "none";
}

function closeWishlistModal() {
 document.getElementById("wishlistModal").style.display = "none";
}

 function redirectToLoginChoice() {
 closeChoiceModal();
 window.location.href = 'login.html';
 }

 function redirectToSignupChoice() {
 closeChoiceModal();
window.location.href = 'signup.html';
 }

 function showWishlistModal() {
 if (!IS_LOGGED_IN) {
 alert("Please log in to view your wishlist.");
 return;
 }
const wishlistContent = document.getElementById('wishlistContent');
 wishlistContent.innerHTML = '<p style="text-align: center;">Loading wishlist...</p>';
 document.getElementById("wishlistModal").style.display = "flex";

 fetch('process_action.php', {
 method: 'POST',
 headers: {
 'Content-Type': 'application/x-www-form-urlencoded',
 },
body: `action=fetch_wishlist_content`
})
 .then(response => response.json())
 .then(data => {
 if (data.success) {
 if (data.events.length === 0) {
 wishlistContent.innerHTML = '<p style="text-align: center; color: var(--text-secondary);">Your wishlist is empty. Start adding events!</p>';
 } else {
 let html = '<ul style="list-style-type: disc; padding-left: 20px; text-align: left; max-height: 400px; overflow-y: auto;">';
 data.events.forEach(item => {
 html += `<li><strong>${item.title}</strong><br><small style="color: var(--text-secondary);">${item.event_date} - ${item.location}</small></li>`;
 });
 html += '</ul>';
 wishlistContent.innerHTML = html;
 }
 } else {
wishlistContent.innerHTML = `<p style="text-align: center; color: red;">Error: ${data.message} </p>`;
 }
 })
 .catch(error => {
 console.error('Error fetching wishlist:', error);
 wishlistContent.innerHTML = `<p style="text-align: center; color: red;">Connection error.</p>`;
 });
 }
// Register the logged-in user for an event
 function registerForEvent(eventTitle, eventDate, event_id) {
 if (IS_LOGGED_IN) {

 fetch('process_action.php', {
 method: 'POST',
headers: {
'Content-Type': 'application/x-www-form-urlencoded',
 },
body: `action=register&event_id=${event_id}`
})
 .then(response => response.json())
 .then(data => {
alert(data.message);
if (data.success) {
document.getElementById("successEventTitle").textContent = eventTitle;
document.getElementById("successName").textContent = USER_NAME;
 document.getElementById("successEmail").textContent = USER_EMAIL;
 document.getElementById("successDate").textContent = eventDate;
 document.getElementById("fancyModal").style.display = "flex";
 }
 })
.catch(error => {
 console.error('Error:', error);
 alert("An error occurred during registration.");
});
 } else {
 localStorage.setItem('pending_action', JSON.stringify({
action: 'register',
 event_id: event_id,
                eventTitle: eventTitle, 
                eventDate: eventDate
 }));
 document.getElementById("choiceEventTitle").textContent = eventTitle;
 document.getElementById("choiceModal").style.display = "flex";
 }
 }
 // Toggle wishlist status (add/remove event)
function toggleLike(iconElement, event_id) {
 if (!IS_LOGGED_IN) {
 alert("Please log in to add items to your wishlist.");

 localStorage.setItem('pending_action', JSON.stringify({
 action: 'toggle_wishlist',
 event_id: event_id,
}));

 window.location.href = 'login.html';
return;
 }


 fetch('process_action.php', {
 method: 'POST',
headers: {
'Content-Type': 'application/x-www-form-urlencoded',
 },
 body: `action=toggle_wishlist&event_id=${event_id}`
 })
.then(response => response.json())
.then(data => {
if (data.success) {
 if (data.status === 'added') {
    iconElement.textContent = '❤️';
 } else {
 iconElement.textContent = '♡';
 }
 window.location.reload(); 
 } else {
alert(data.message);
 }
 })
  .catch(error => {
 console.error('Error:', error);
 alert("An error occurred while updating the wishlist.");
 });
 }

// Check if there is a pending action saved before login
 function checkPendingAction() {
 if (!IS_LOGGED_IN) return;

 const pendingAction = localStorage.getItem('pending_action');

if (pendingAction) {

 try {
                const data = JSON.parse(pendingAction);
                localStorage.removeItem('pending_action'); 
   
                if (data.action === 'register') {
                   
                    registerForEvent(data.eventTitle, data.eventDate, data.event_id);
 } else if (data.action === 'toggle_wishlist') {

 const eventElement = document.querySelector(`[data-event-id="${data.event_id}"]`);
 const iconElement = eventElement ? eventElement.querySelector('.like-btn') : null;
                    
 if (iconElement) {
 toggleLike(iconElement, data.event_id);
 }
 }
            } catch (e) {
              
                console.error("Error parsing pending action from localStorage:", e);
                localStorage.removeItem('pending_action');
            }
}
 }


 window.onload = function() {
 checkPendingAction(); 

      
 const scrollBtn = document.getElementById("scrollTopBtn");

window.onscroll = function() {
 if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
 scrollBtn.style.display = "block";
 } else {
 scrollBtn.style.display = "none";
 }
 };

 scrollBtn.onclick = function() {
 window.scrollTo({ top: 0, behavior: 'smooth' });
};
 };
</script>
<!-- Website footer -->
<footer>
<p>© 2025 Silent Stories. All rights reserved.</p>
<p>
<a href="aboutPage">About</a> |
<a href="ArtistPage.html">Artists</a> |
<a href="Contact.html">Contact us</a>
</p>
</footer>
<?php $conn->close();?>
</body>
</html>
