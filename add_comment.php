<?php
include 'db.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$foodId = intval($data['food_id']);
$userId = intval($data['user_id']);
$comment = trim($data['comment']);

if (!$foodId || !$userId || !$comment) {
    echo json_encode(['success' => false, 'message' => 'اطلاعات ناقص است']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO comments (food_id, user_id, comment) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $foodId, $userId, $comment);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'خطا در ثبت نظر']);
}
