<?php


/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Test Image
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).

include 'plugins/TCPDF-6.7.5/tcpdf.php';
ob_start();


echo '<link rel="icon" type="image/x-icon" href="/dist/img/splogo.jpg" style="width:200%; height:100%">';


// Set the timezone to Manila, Asia, Philippines
date_default_timezone_set('Asia/Manila');


if (file_exists('config/')) {
    include 'config/sp_franchise_db_config.php';
    include 'config/sccdrrmo_db_config.php';
} else {
    include '../config/sp_franchise_db_config.php';
    include '../config/sccdrrmo_db_config.php';
}




$current_date = new DateTime();
if (!function_exists('convertToTitleCase')) {
    function convertToTitleCase($inputName)
    {
        // Split the input name into words
        $words = explode(' ', strtolower($inputName));

        // Convert each word to title case
        $titleCaseWords = array_map('ucwords', $words);

        // Join the words back together
        $convertedName = implode(' ', $titleCaseWords);

        return $convertedName;
    }
}

// if (isset($_POST['from']) &&  isset($_POST['to'])) {

//     $to = $_POST['to'];
//     $from = $_POST['from'];

//     if (isset($_POST['query']) && $_POST['query'] == 'NEAR_EXPIRY') {
//         $query = $spcon->prepare("SELECT franchise_no,operator,no_units,reso_no,status,date_reso ,validity FROM franchise_info 
//     WHERE (validity <= DATE_ADD(NOW(), INTERVAL 30 DAY)
//     OR (YEAR(validity) = YEAR(NOW()) AND MONTH(validity) = MONTH(NOW()) + 1 AND YEAR(validity) = YEAR(NOW())))
//     AND status NOT IN ('EXPIRED', 'CANCEL')
//     AND YEAR(validity) = YEAR(NOW()) AND validity BETWEEN :from AND :to");

//         $query->execute([
//             ':from' => $from,
//             ':to' => $to
//         ]);

//         $franchise_masterlist = $query->fetchAll();

//         $entity_details = $sccdrrmocon->prepare("SELECT firstname,middlename,lastname FROM `tbl_individual` WHERE entity_no = :entity");
//     } else if (isset($_POST['query']) && $_POST['query'] == 'EXPIRED') {
//         $query = $spcon->prepare('SELECT * FROM franchise_info WHERE (validity < NOW() AND date_reso != "0000-00-00" AND STATUS = "EXPIRED") AND VALIDITY BETWEEN :from AND :to');
//         $query->execute([
//             ':from' => $from,
//             ':to' => $to
//         ]);

//         $franchise_masterlist = $query->fetchAll();
//         $entity_details = $sccdrrmocon->prepare("SELECT firstname,middlename,lastname FROM `tbl_individual` WHERE entity_no = :entity");
//     }
// } else {
//     if (isset($_POST['query']) && $_POST['query'] == 'NEAR_EXPIRY') {
//         $query = $spcon->prepare("SELECT franchise_no,operator,no_units,reso_no,status,date_reso ,validity FROM franchise_info 
//                 WHERE (validity <= DATE_ADD(NOW(), INTERVAL 30 DAY)
//                 OR (YEAR(validity) = YEAR(NOW()) AND MONTH(validity) = MONTH(NOW()) + 1 AND YEAR(validity) = YEAR(NOW())))
//                 AND status NOT IN ('EXPIRED', 'CANCEL')
//                 AND YEAR(validity) = YEAR(NOW())");
//         $query->execute();
//         $franchise_masterlist = $query->fetchAll();

//         $entity_details = $sccdrrmocon->prepare("SELECT firstname,middlename,lastname FROM `tbl_individual` WHERE entity_no = :entity");
//     } else if (isset($_POST['query']) && $_POST['query'] == 'EXPIRED') {
//         $query = $spcon->prepare('SELECT * FROM franchise_info WHERE validity < NOW() AND date_reso != "0000-00-00" AND STATUS = "EXPIRED"');
//         $query->execute();

//         $franchise_masterlist = $query->fetchAll();

//         $entity_details = $sccdrrmocon->prepare("SELECT firstname,middlename,lastname FROM `tbl_individual` WHERE entity_no = :entity");
//     }
// }






// create new PDF document
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Print Document');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');


// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Add watermark
// $pdf->Image('/dist/img/sp.png', 0, 0, 210, 297, 'PNG', '', 'T', false, 300, '', true, 0, 0, 0, false);




// set default monospaced font
// $pdf->SethefaultMonospacedFont(PDF_FONT_NAME_DATA);
$pdf->SetFont('courier', '', 10);






// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);





// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}
// set JPEG quality
$pdf->setJPEGQuality(75);

$pdf->AddPage();



// Image example with resizing
$pdf->Image('dist/img/splogo.jpg', 138, 5, 20, 20, 'JPG', '', 'C', false, 150, '', false, false, 0, false, false,);
$pdf->Ln(15);
$pdf->SetFont('courier', 'B', 12); // 'B' for bold, 12 is the font size
// $pdf->Image('dist/img/splogo.jpg', '', '', 40, 40, '', '', 'T', false, 300, '', false, false, 1, false, false, false);






$query = $spcon->prepare("SELECT franchise_no,operator,no_units,reso_no,status,date_reso ,validity FROM franchise_info 
WHERE (validity <= DATE_ADD(NOW(), INTERVAL 30 DAY)
OR (YEAR(validity) = YEAR(NOW()) AND MONTH(validity) = MONTH(NOW()) + 1 AND YEAR(validity) = YEAR(NOW())))
AND status NOT IN ('EXPIRED', 'CANCEL') AND archive_status != 'isArchived' AND is_old_data != 'OLD'
AND YEAR(validity) = YEAR(NOW())");
$query->execute();
$franchise_masterlist = $query->fetchAll();

$entity_details = $sccdrrmocon->prepare("SELECT firstname,middlename,lastname FROM `tbl_individual` WHERE entity_no = :entity");




$pdf->cell(0, 10, 'SANGGUNIANG PANLUNSOD', 0, 1, 'C', 0);
// $pdf->Cell(0, 2, 'FRANCHISING', 0, 1, 'C', 0, '', 1);
$pdf->Ln(3);


if ((isset($_POST['from']) && isset($_POST['to']))) {

    $to = $_POST['to'];
    $from = $_POST['from'];

    $pdf->SetFont('courier', 'B', 17); // 'B' for bold, 12 is the font size
    $pdf->Cell(0, 5, $_POST['query'] . ' FRANCHISE', 0, 1, 'C', 0, '', 1);
    $pdf->SetFont('courier', 'B', 12); // 'B' for bold, 12 is the font size
    $pdf->Cell(0, 2, 'DATE : ' . date('Y', strtotime($from)) . '-' . date('Y', strtotime($to)), 0, 1, 'C', 0, '', 1);
    $pdf->Ln(5);
    $pdf->SetFont('courier', '', 12);

    if (isset($_POST['query']) && $_POST['query'] == 'EXPIRED') {
        $query = $spcon->prepare('SELECT * FROM franchise_info WHERE (validity < NOW() AND date_reso != "0000-00-00" AND STATUS = "EXPIRED") AND VALIDITY BETWEEN :from AND :to');
        $query->execute([
            ':from' => $from,
            ':to' => $to
        ]);

        $franchise_masterlist = $query->fetchAll();
        $entity_details = $sccdrrmocon->prepare("SELECT firstname,middlename,lastname FROM `tbl_individual` WHERE entity_no = :entity");
    }

    $html = ' <table border="1" cellspacing="0" cellpadding="1" align="center" style="width: 100%; max-width: 100%;">
                    <tr>
                        <th width="12%" style="text-align: center; vertical-align: middle; ">Franchise</th>
                        <th width="35%" style="text-align: center; vertical-align: middle;">Operator</th>
                        <th width="7%" style="text-align: center; vertical-align: middle;">Units</th>
                        <th width="15%" style="text-align: center; vertical-align: middle;">Date Granted</th>
                        <th width="15%" style="text-align: center; vertical-align: middle;">Validity</th>
                        <th width="16%" style="text-align: center; vertical-align: middle;">Resolution</th>
                    </tr>';
    foreach ($franchise_masterlist as $index => $value) {
        $entity_details->bindParam(':entity', $value['operator']);
        $entity_details->execute();
        $entity_data = $entity_details->fetch();
        $name = convertToTitleCase($entity_data['lastname'] . ', ' . $entity_data['firstname'] . ' ' . $entity_data['middlename']);

        $expiry_date = ($value['validity'] === '0000-00-00') ? 'Pending...' : date('Y-m-d', strtotime($value['validity']));

        $html .= '<tr style="text-align: center; vertical-align: middle; line-height: 20px;">'
            . '<td>' . $value['franchise_no'] . '</td>'
            . '<td>' . $name . '</td>'
            . '<td>' . $value['no_units'] . '</td>'
            . '<td>' . $value['date_reso'] . '</td>'
            . '<td>' . $expiry_date . '</td>'
            . '<td>' . $value['reso_no'] . '</td>'
            . '</tr>';
    }
    $html .= '
        </table> 
        <style>
            th, td {
            border : 0px solid black
            }

            td {
            font-size: 12px;
            }

        </style>';

    $pdf->writeHTML($html, false, false, false, true, 'C');
} else {
    if (isset($_POST['query'])) {
        if ($_POST['query'] == 'EXPIRED') {
            $pdf->SetFont('courier', 'B', 17); // 'B' for bold, 12 is the font size
            $pdf->Cell(0, 5, $_POST['query'] . ' FRANCHISE', 0, 1, 'C', 0, '', 1);
            $pdf->SetFont('courier', 'B', 12); // 'B' for bold, 12 is the font size
            $pdf->Cell(0, 2, 'PRINT DATE : ' . date('Y'), 0, 1, 'C', 0, '', 1);
            $pdf->Ln(5);
            $pdf->SetFont('courier', '', 12);
            $query = $spcon->prepare('SELECT * FROM franchise_info WHERE validity < NOW() AND date_reso != "0000-00-00" AND STATUS = "EXPIRED"');
            $query->execute();

            $franchise_masterlist = $query->fetchAll();

            $entity_details = $sccdrrmocon->prepare("SELECT firstname,middlename,lastname FROM `tbl_individual` WHERE entity_no = :entity");
            $html = ' <table border="1" cellspacing="0" cellpadding="1" align="center" style="width: 100%; max-width: 100%;">
            <tr>
                <th width="12%" style="text-align: center; vertical-align: middle; ">Franchise</th>
                <th width="35%" style="text-align: center; vertical-align: middle;">Operator</th>
                <th width="7%" style="text-align: center; vertical-align: middle;">No of Units</th>
                <th width="15%" style="text-align: center; vertical-align: middle;">Date Granted</th>
                <th width="15%" style="text-align: center; vertical-align: middle;">Validity</th>
                <th width="16%" style="text-align: center; vertical-align: middle;">Resolution</th>
            </tr>';
            foreach ($franchise_masterlist as $index => $value) {
                $entity_details->bindParam(':entity', $value['operator']);
                $entity_details->execute();
                $entity_data = $entity_details->fetch();
                $name = convertToTitleCase($entity_data['lastname'] . ', ' . $entity_data['firstname'] . ' ' . $entity_data['middlename']);

                $expiry_date = ($value['validity'] === '0000-00-00') ? 'Pending...' : date('Y-m-d', strtotime($value['validity']));

                $html .= '<tr style="text-align: center; vertical-align: middle; line-height: 20px;">'
                    . '<td>' . $value['franchise_no'] . '</td>'
                    . '<td>' . $name . '</td>'
                    . '<td>' . $value['no_units'] . '</td>'
                    . '<td>' . $value['date_reso'] . '</td>'
                    . '<td>' . $expiry_date . '</td>'
                    . '<td>' . $value['reso_no'] . '</td>'
                    . '</tr>';
            }
            $html .= '</table> 
                            <style>
                                th, td {
                                border : 0px solid black
                                }

                                td {
                                font-size: 12px;
                                }

                            </style>';

            $pdf->writeHTML($html, false, false, false, true, 'C');
        } else if ($_POST['query'] === 'ABOUT TO EXPIRE') {
            $pdf->SetFont('courier', 'B', 17); // 'B' for bold, 12 is the font size
            $pdf->Cell(0, 5,  'EXPIRING FRANCHISE', 0, 1, 'C', 0, '', 1);
            $pdf->SetFont('courier', 'B', 12); // 'B' for bold, 12 is the font size
            $pdf->Cell(0, 2, 'PRINT DATE : ' . date('Y-m-d'), 0, 1, 'C', 0, '', 1);
            $pdf->Ln(5);
            $pdf->SetFont('courier', '', 12);

            $query = $spcon->prepare("SELECT franchise_no,operator,no_units,reso_no,STATUS,date_reso ,validity FROM franchise_info 
                                WHERE (validity <= DATE_ADD(NOW(), INTERVAL 30 DAY)
                                OR (YEAR(validity) = YEAR(NOW()) AND MONTH(validity) = MONTH(NOW()) + 1 AND YEAR(validity) = YEAR(NOW())))
                                AND STATUS NOT IN ('EXPIRED', 'CANCEL') AND archive_status != 'isArchived' AND is_old_data != 'OLD'
                                AND YEAR(validity) = YEAR(NOW())");
            $query->execute();

            $franchise_masterlist = $query->fetchAll();

            $entity_details = $sccdrrmocon->prepare("SELECT firstname,middlename,lastname FROM `tbl_individual` WHERE entity_no = :entity");
            $html = ' <table border="1" cellspacing="0" cellpadding="1" align="center" style="width: 100%; max-width: 100%;">
            <tr>
                <th width="12%" style="text-align: center; vertical-align: middle; ">Franchise</th>
                <th width="35%" style="text-align: center; vertical-align: middle;">Operator</th>
                <th width="7%" style="text-align: center; vertical-align: middle;">No of Units</th>
                <th width="15%" style="text-align: center; vertical-align: middle;">Date Granted</th>
                <th width="15%" style="text-align: center; vertical-align: middle;">Validity</th>
                <th width="16%" style="text-align: center; vertical-align: middle;">Resolution</th>
            </tr>';
            foreach ($franchise_masterlist as $index => $value) {
                $entity_details->bindParam(':entity', $value['operator']);
                $entity_details->execute();
                $entity_data = $entity_details->fetch();
                $name = convertToTitleCase($entity_data['lastname'] . ', ' . $entity_data['firstname'] . ' ' . $entity_data['middlename']);

                // $expiry_date = ($value['validity'] === '0000-00-00') ? 'Pending...' : date('Y-m-d', strtotime($value['validity']));

                $ndate = new DateTime($value['validity']); // Create a DateTime object for the validity date
                $interval = $current_date->diff($ndate);
                $days_left = ($current_date < $ndate) ? $interval->days + 1 : $interval->days;


                $html .= '<tr style="text-align: center; vertical-align: middle; line-height: 20px;">'
                    . '<td>' . $value['franchise_no'] . '</td>'
                    . '<td>' . $name . '</td>'
                    . '<td>' . $value['no_units'] . '</td>'
                    . '<td>' . $value['date_reso'] . '</td>'
                    . '<td>' . $days_left . " day" . ($days_left > 1 ? "s" : "") . " left" . '</td>'
                    . '<td>' . $value['reso_no'] . '</td>'
                    . '</tr>';
            }
            $html .= '</table> 
                            <style>
                                th, td {
                                border : 0px solid black
                                }

                                td {
                                font-size: 12px;
                                }

                            </style>';

            $pdf->writeHTML($html, false, false, false, true, 'C');
        }
    }
}



$pdf->StopTransform();







$pdf->lastPage();

ob_end_clean();


$pdf->Output('document.pdf', 'I');
