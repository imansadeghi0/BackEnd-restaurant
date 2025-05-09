<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // دریافت اطلاعات ورودی
    $data = json_decode(file_get_contents("php://input"));

    $name = $data->name;
    $email = $data->email;
    $password = password_hash($data->password, PASSWORD_DEFAULT);
    $phone = $data->phone;
    $address = $data->address;

    // اتصال به دیتابیس
    $conn = new mysqli("localhost", "root", "", "restaurant");

    if ($conn->connect_error) {
        die(json_encode(array("success" => false, "message" => "مشکل در اتصال به پایگاه داده")));
    }

    // بررسی ایمیل تکراری
    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmail);
    if ($result->num_rows > 0) {
        echo json_encode(array("success" => false, "message" => "این ایمیل قبلاً ثبت شده است"));
        exit();
    }

    // ثبت کاربر در دیتابیس
    $sql = "INSERT INTO users (name, email, password, phone, address, created_at) 
            VALUES ('$name', '$email', '$password', '$phone', '$address', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("success" => true, "message" => "ثبت‌نام موفقیت‌آمیز بود"));
    } else {
        echo json_encode(array("success" => false, "message" => "خطا در ثبت‌نام"));
    }

    $conn->close();
}
?>
