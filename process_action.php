<?php
session_start();
include 'DB.php';

header('Content-Type: application/json'); // إرجاع البيانات بصيغة JSON

// التأكد من أن المستخدم مسجل دخوله قبل أي عملية
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in. Please log in to proceed.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

$action = $_POST['action'] ?? '';
$event_id = $_POST['event_id'] ?? 0;

// ---------------------------------------------
// 1. معالجة التسجيل في الفعالية (action=register)
// ---------------------------------------------
if ($action === 'register') {
    $check_sql = "SELECT id FROM registrations WHERE user_id = ? AND event_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $event_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'You are already registered for this event.']);
    } else {
        $insert_sql = "INSERT INTO registrations (user_id, event_id, user_name, user_email) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iiss", $user_id, $event_id, $user_name, $user_email);

        if ($insert_stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'You have successfully signed up for the event!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error during registration.']);
        }
        $insert_stmt->close();
    }
    $check_stmt->close();
}

// ------------------------------------------------
// 2. معالجة قائمة الأمنيات (action=toggle_wishlist)
// ------------------------------------------------
elseif ($action === 'toggle_wishlist') {
    $check_sql = "SELECT id FROM wishlist WHERE user_id = ? AND event_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $event_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // إزالة من Wishlist
        $delete_sql = "DELETE FROM wishlist WHERE user_id = ? AND event_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("ii", $user_id, $event_id);
        $delete_stmt->execute();
        
        echo json_encode(['success' => true, 'status' => 'removed', 'message' => 'Removed from wishlist.']);
        $delete_stmt->close();
    } else {
        // إضافة إلى Wishlist مع الاسم والإيميل
        $insert_sql = "INSERT INTO wishlist (user_id, event_id, user_name, user_email) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iiss", $user_id, $event_id, $user_name, $user_email);
        $insert_stmt->execute();
        
        echo json_encode(['success' => true, 'status' => 'added', 'message' => 'Added to wishlist.']);
        $insert_stmt->close();
    }
    $check_stmt->close();
}

// ------------------------------------------------
//
// -------------------------------------------
elseif ($action === 'fetch_wishlist_content') {
    $sql = "SELECT e.title, e.event_date, e.location, w.user_name, w.user_email
            FROM events e
            JOIN wishlist w ON e.id = w.event_id
            WHERE w.user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $wishlist_events = [];
    while ($row = $result->fetch_assoc()) {
        $wishlist_events[] = $row;
    }
    $stmt->close();

    echo json_encode(['success' => true, 'events' => $wishlist_events]);
}

$conn->close();
?>
