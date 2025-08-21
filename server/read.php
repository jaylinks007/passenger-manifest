<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT id, first_name, last_name, email, phone, address, created_at, updated_at FROM passengers WHERE deleted_at IS NULL ORDER BY created_at DESC";

$stmt = $db->prepare($query);
$stmt->execute();

$num = $stmt->rowCount();

if ($num > 0) {
    $passengers_arr = array();
    $passengers_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $passenger_item = array(
            "id" => $id,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "phone" => $phone,
            "address" => $address,
            "created_at" => $created_at,
            "updated_at" => $updated_at
        );
        array_push($passengers_arr["records"], $passenger_item);
    }

    http_response_code(200);
    echo json_encode($passengers_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No passengers found.")
    );
}
?>
