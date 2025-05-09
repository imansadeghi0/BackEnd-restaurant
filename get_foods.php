<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include "db.php"; // بررسی کن که این فایل وجود داشته باشه و درست لود بشه

$sql = "SELECT * FROM foods";
$result = $conn->query($sql);

$foods = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $foods[] = $row;
    }
}

echo json_encode($foods, JSON_UNESCAPED_UNICODE);
?>
