<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // دریافت اطلاعات ورودی
    $data = json_decode(file_get_contents("php://input"));
    $name = $data->name;
    $email = $data->email;
    $checkpassowrdempty = $data->password;
    if($checkpassowrdempty == null){
        echo json_encode(array("success" => false, "message" => "فیلد مرتبط با پسورد خالی است"));
        exit();
    }
    $password = password_hash($data->password, PASSWORD_DEFAULT);
    $phone = $data->phone;
    $address = $data->address;

    // اتصال به دیتابیس
    $conn = new mysqli("localhost", "root", "", "restaurant");

    if ($conn->connect_error) {
        die(json_encode(array("success" => false, "message" => "مشکل در اتصال به پایگاه داده")));
    }

    // بررسی ایمیل تکراری با استفاده از prepared statement
    $checkEmailStmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $result = $checkEmailStmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(array("success" => false, "message" => "این ایمیل قبلاً ثبت شده است"));
        exit();
    }

    // بررسی شماره تماس تکراری با استفاده از prepared statement
    $checkPhoneStmt = $conn->prepare("SELECT * FROM users WHERE phone = ?");
    $checkPhoneStmt->bind_param("s", $phone);
    $checkPhoneStmt->execute();
    $result = $checkPhoneStmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(array("success" => false, "message" => "این شماره تماس قبلاً استفاده شده است"));
        exit();
    }

    // ثبت کاربر در دیتابیس با استفاده از prepared statement
    $sql = "INSERT INTO users (name, email, password, phone, address, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $password, $phone, $address);

    if ($stmt->execute()) {
        echo json_encode(array("success" => true, "message" => "ثبت‌نام موفقیت‌آمیز بود"));
    } else {
        echo json_encode(array("success" => false, "message" => "خطا در ثبت‌نام"));
    }

    // بستن ارتباط با دیتابیس
    $stmt->close();
    $conn->close();
}
?>
