<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT
            (SELECT COUNT(*) FROM passengers WHERE deleted_at IS NULL) as active_passengers,
            (SELECT COUNT(*) FROM passengers WHERE deleted_at IS NOT NULL) as deleted_passengers";

$stmt = $db->prepare($query);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $chart_data = array(
        "active_passengers" => $row['active_passengers'],
        "deleted_passengers" => $row['deleted_passengers']
    );

    http_response_code(200);
    echo json_encode($chart_data);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No chart data found."));
}
?>
