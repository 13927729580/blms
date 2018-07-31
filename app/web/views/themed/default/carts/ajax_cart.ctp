<?php ob_start();?>
<?php 
$out1 = ob_get_contents();ob_end_clean();  
	$price=array("les_subtotal_ajax"=>$les_subtotal_ajax,"sum_discount_ajax"=>$sum_discount_ajax,"sum_subtotal_ajax"=>$sum_subtotal_ajax,"sum_market_subtotal"=>$sum_market_subtotal);
	echo json_encode($price);ob_end_flush();?>	