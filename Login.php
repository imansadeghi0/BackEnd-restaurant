<?php
include 'db.php'; 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->email) || !isset($data->password)) {
    echo json_encode(["success" => false, "message" => "ایمیل و رمز عبور را وارد کنید"]);
    exit;
}

$email = $data->email;
$password = $data->password;

// بررسی اینکه کاربر وجود دارد
$sql = "SELECT id, name, email, password, phone, address FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // بررسی رمز عبور
    if (password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        echo json_encode([
            "success" => true,
            "message" => "ورود موفقیت‌آمیز بود",
            "user" => [
                "id" => $user['id'],
                "name" => $user['name'],
                "email" => $user['email'],
                "phone" => $user['phone'],
                "address" => $user['address']
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "رمز عبور اشتباه است"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "کاربری با این ایمیل یافت نشد"]);
}

$conn->close();
?>
