<?php
// I am assuming PhpWord is installed and available in the vendor folder.
// If not, this script will not work.
require_once '../vendor/autoload.php';
include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM passengers WHERE deleted_at IS NULL ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$passengers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Creating the new document...
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Adding an empty Section to the document...
$section = $phpWord->addSection();
// Adding Text element to the Section...
$section->addText('Passenger Manifest', array('bold' => true, 'size' => 16));

$table = $section->addTable();
$table->addRow();
$table->addCell(500)->addText('ID');
$table->addCell(2000)->addText('First Name');
$table->addCell(2000)->addText('Last Name');
$table->addCell(2000)->addText('Email');
$table->addCell(1500)->addText('Phone');
$table->addCell(3000)->addText('Address');

foreach ($passengers as $passenger) {
    $table->addRow();
    $table->addCell(500)->addText($passenger['id']);
    $table->addCell(2000)->addText($passenger['first_name']);
    $table->addCell(2000)->addText($passenger['last_name']);
    $table->addCell(2000)->addText($passenger['email']);
    $table->addCell(1500)->addText($passenger['phone']);
    $table->addCell(3000)->addText($passenger['address']);
}

// Saving the document as OOXML file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$filename = 'passenger_manifest.docx';
$objWriter->save($filename);

// Send headers
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.$filename);
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($filename));
flush(); // Flush system output buffer
readfile($filename);
unlink($filename); // Delete the temp file
exit;
?>
