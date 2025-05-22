<?php
include 'db.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

$foodId = isset($_GET['food_id']) ? intval($_GET['food_id']) : 0;

if (!$foodId) {
    echo json_encode(['success' => false, 'message' => 'شناسه غذا ارسال نشده']);
    exit;
}

$query = "SELECT comments.comment, comments.created_at, comments.user_id, users.name
          FROM comments
          JOIN users ON comments.user_id = users.id
          WHERE comments.food_id = ?
          ORDER BY comments.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $foodId);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}

echo json_encode(['success' => true, 'comments' => $comments]);
