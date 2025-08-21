<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : die();

$query = "SELECT * FROM passengers WHERE id = ? LIMIT 0,1";

$stmt = $db->prepare($query);
$stmt->bindParam(1, $id);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $passenger_item = array(
        "id" => $row['id'],
        "first_name" => $row['first_name'],
        "last_name" => $row['last_name'],
        "email" => $row['email'],
        "phone" => $row['phone'],
        "address" => $row['address']
    );

    http_response_code(200);
    echo json_encode($passenger_item);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Passenger not found."));
}
?>
