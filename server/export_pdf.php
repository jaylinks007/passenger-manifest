<?php
// I am assuming tcpdf is installed and available in the vendor folder.
// If not, this script will not work.
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');
include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM passengers WHERE deleted_at IS NULL ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$passengers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Passenger Manifest System');
$pdf->SetTitle('Passenger Manifest');
$pdf->SetSubject('Passenger Manifest');

// set default header data
$pdf->SetHeaderData('', 0, 'Passenger Manifest', '', array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 12);

// add a page
$pdf->AddPage();

$html = '<h1>Passenger Manifest</h1>
<table border="1" cellpadding="4">
<thead>
<tr>
<th scope="col"><b>ID</b></th>
<th scope="col"><b>First Name</b></th>
<th scope="col"><b>Last Name</b></th>
<th scope="col"><b>Email</b></th>
<th scope="col"><b>Phone</b></th>
<th scope="col"><b>Address</b></th>
</tr>
</thead>
<tbody>';

foreach ($passengers as $passenger) {
    $html .= '<tr>';
    $html .= '<td>' . $passenger['id'] . '</td>';
    $html .= '<td>' . $passenger['first_name'] . '</td>';
    $html .= '<td>' . $passenger['last_name'] . '</td>';
    $html .= '<td>' . $passenger['email'] . '</td>';
    $html .= '<td>' . $passenger['phone'] . '</td>';
    $html .= '<td>' . $passenger['address'] . '</td>';
    $html .= '</tr>';
}

$html .= '</tbody></table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('passenger_manifest.pdf', 'I');

?>
