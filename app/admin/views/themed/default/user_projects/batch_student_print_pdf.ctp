<?php App::import('Vendor','tcpdf');
class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        $this->SetFont('stsongstdlight', 'B', 20);
        // Title
        $this->Cell(0, 15, '证件信息', 0, true, 'C', 0, '', 0, true, 'C', 'M');
    }
    public function Footer(){}
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Project');

// set default header data
//$pdf->SetHeaderData($image_file, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 048', PDF_HEADER_STRING);

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(15);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray('chi');

// ---------------------------------------------------------

// set font
//$pdf->SetFont('helvetica', 'B', 20);

$pdf->SetFont('stsongstdlight','', '10');
// define barcode style
$style = array(
    'position' => '',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => true,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 8,
    'stretchtext' => 4
);

foreach($data as $vall){
	$media_file=WWW_ROOT.$vall;
	if(!(file_exists($media_file)&&is_file($media_file)))continue;
	
	// add a page
	$pdf->AddPage();
	ob_start();
?>
	<img src="<?php echo $vall?>" >
	<div height="1"></div>
	<?php  $content=ob_get_contents();ob_clean();
	$pdf->writeHTML($content, true, false, false, false, '');
	$pdf->lastPage();
}


// -----------------------------------------------------------------------------

//Close and output PDF document
$pdf->Output('project.pdf', 'I');

?>