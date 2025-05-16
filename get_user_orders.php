<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
include "db.php";

if (!isset($_GET['userId'])) {
    echo json_encode(['success' => false, 'message' => 'شناسه کاربر ارسال نشده است']);
    exit;
}

$userId = intval($_GET['userId']);

// گرفتن سفارش‌ها
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];

while ($order = $result->fetch_assoc()) {
    $orderId = $order['id'];

    // گرفتن آیتم‌های هر سفارش با نام غذا از جدول foods
    $stmtItems = $conn->prepare(
        "SELECT oi.food_id, oi.quantity, f.name AS food_name, oi.price 
         FROM order_items oi 
         JOIN foods f ON oi.food_id = f.id 
         WHERE oi.order_id = ?"
    );
    $stmtItems->bind_param("i", $orderId);
    $stmtItems->execute();
    $itemsResult = $stmtItems->get_result();

    $items = [];
    while ($item = $itemsResult->fetch_assoc()) {
        $items[] = $item;
    }

    $order['items'] = $items;

    $orders[] = $order;
}

echo json_encode(['success' => true, 'orders' => $orders]);
