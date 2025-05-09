<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
// دریافت داده‌های JSON از ورودی

// دریافت داده‌های JSON از ورودی
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// بررسی موفقیت‌آمیز بودن تجزیه JSON
if ($data === null) {
    echo json_encode(['success' => false, 'message' => 'داده‌های ارسالی نامعتبر هستند.']);
    exit;
}

// استخراج فیلدها از داده‌های دریافتی
$name = $data['name'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';
$phone = $data['phone'] ?? '';
$address = $data['address'] ?? '';

// بررسی فیلدهای الزامی
$required_fields = ['name', 'email', 'password', 'phone', 'address'];
$errors = [];

foreach ($required_fields as $field) {
    if (empty(trim($data[$field] ?? ''))) {
        $errors[] = "فیلد $field الزامی است.";
    }
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// ادامه‌ی فرآیند ثبت‌نام...
?>

if (!empty($errors)) {
    // ارسال پاسخ با خطاها
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

        $name = $data->name;
        $email = $data->email;
        $password = password_hash($data->password, PASSWORD_DEFAULT);
        $phone = $data->phone;
        $address = $data->address;
        $password = $data->repassword;
    
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
        //شماره تماس اگر تکراری بود بگو تکراریه
        $checkphone = "SELECT * FROM users WHERE phone = '$phone'";
        $result = $conn->query($checkphone);
        if ( $result->num_rows > 0){
            echo json_encode(array("success" => false, "massage" => "این شماره تماس قبلا استفاده شده است"));
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
