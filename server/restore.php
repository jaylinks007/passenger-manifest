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

if (!empty($data->id)) {
    $query = "UPDATE passengers SET deleted_at = NULL WHERE id = :id";

    $stmt = $db->prepare($query);

    $id = htmlspecialchars(strip_tags($data->id));
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("message" => "Passenger was restored."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to restore passenger."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to restore passenger. ID is missing."));
}
?>
