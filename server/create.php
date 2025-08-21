<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->first_name) &&
    !empty($data->last_name) &&
    !empty($data->email)
) {
    $query = "INSERT INTO passengers (first_name, last_name, email, phone, address) VALUES (:first_name, :last_name, :email, :phone, :address)";

    $stmt = $db->prepare($query);

    // sanitize
    $first_name = htmlspecialchars(strip_tags($data->first_name));
    $last_name = htmlspecialchars(strip_tags($data->last_name));
    $email = htmlspecialchars(strip_tags($data->email));
    $phone = htmlspecialchars(strip_tags($data->phone));
    $address = htmlspecialchars(strip_tags($data->address));

    $stmt->bindParam(":first_name", $first_name);
    $stmt->bindParam(":last_name", $last_name);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":phone", $phone);
    $stmt->bindParam(":address", $address);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "Passenger was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create passenger."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create passenger. Data is incomplete."));
}
?>
