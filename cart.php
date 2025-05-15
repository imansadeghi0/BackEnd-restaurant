<?php
header('Access-Control-Allow-Origin: *'); // اجازه دسترسی از تمام دامنه‌ها
header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); // روش‌های مجاز (POST، GET، OPTIONS)
header('Access-Control-Allow-Headers: Content-Type'); // هدرهای مجاز
header('Content-Type: application/json'); // نوع محتوا برای پاسخ

include 'db.php'; // فایل برای اتصال به دیتابیس

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // دریافت داده‌های سفارش
    $data = json_decode(file_get_contents('php://input'), true);

    $userId = $data['userId'];
    $totalPrice = $data['total_price'];
    $items = $data['items'];

    // 1. ثبت سفارش کلی در جدول orders
    $query = "INSERT INTO orders (user_id, total_price) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $userId, $totalPrice);
    $stmt->execute();
    $orderId = $stmt->insert_id;  // شناسه سفارش ثبت شده

    // 2. ثبت آیتم‌های سفارش در جدول order_items
    foreach ($items as $item) {
        $foodId = $item['food_id'];
        $quantity = $item['quantity'];
        $price = $item['price'];

        $query = "INSERT INTO order_items (order_id, food_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiid", $orderId, $foodId, $quantity, $price);
        $stmt->execute();
    }

    // ارسال پاسخ موفقیت‌آمیز
    echo json_encode(['success' => true]);
} else {
    // پاسخ برای روش‌های درخواست دیگر
    echo json_encode(['error' => 'Invalid request method']);
}
?>
