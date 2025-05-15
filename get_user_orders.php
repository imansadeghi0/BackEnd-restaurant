<?php
header('Content-Type: application/json');
require 'db_connection.php'; // اتصال به دیتابیس

if (!isset($_GET['userId'])) {
    echo json_encode(['success' => false, 'message' => 'شناسه کاربر ارسال نشده است']);
    exit;
}

$userId = intval($_GET['userId']);

$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

if (count($orders) > 0) {
    echo json_encode(['success' => true, 'orders' => $orders]);
} else {
    echo json_encode(['success' => true, 'orders' => []]);
}
?>
