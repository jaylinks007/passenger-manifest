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
    !empty($data->id) &&
    !empty($data->first_name) &&
    !empty($data->last_name) &&
    !empty($data->email)
) {
    $query = "UPDATE passengers SET first_name = :first_name, last_name = :last_name, email = :email, phone = :phone, address = :address WHERE id = :id";

    $stmt = $db->prepare($query);

    // sanitize
    $id = htmlspecialchars(strip_tags($data->id));
    $first_name = htmlspecialchars(strip_tags($data->first_name));
    $last_name = htmlspecialchars(strip_tags($data->last_name));
    $email = htmlspecialchars(strip_tags($data->email));
    $phone = htmlspecialchars(strip_tags($data->phone));
    $address = htmlspecialchars(strip_tags($data->address));

    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("message" => "Passenger was updated."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update passenger."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update passenger. Data is incomplete."));
}
?>
