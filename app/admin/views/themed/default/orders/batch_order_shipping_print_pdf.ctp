<?php App::import('Vendor','tcpdf');
class MYPDF extends TCPDF {
	var $order_code='';
	var $order_logo='';
    //Page header
    public function Header() {

        $this->SetFont('stsongstdlight', 'B', 20);
        // Title
        $this->Cell(0, 15, '订单信息', 0, true, 'C', 0, '', 0, true, 'C', 'M');

        // Logo
		if($this->order_logo!=''){
		    $image_file = $this->order_logo;//"/saas/src/dev/htdocs/vhosts/c599712.ioco.dev/www/logo.jpg";
       	    $this->Image($image_file, 15, 10, '', 10, '', '', 'T', false, 300, '', false, false, 0, false, false, false);        // Set font
		}

		if($this->order_code!=''){
				$this->write1DBarcode($this->order_code, 'C128', '', '10', 35, 10, 0.4, array('position'=>'R', 'border'=>false, 'padding'=>'0', 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'stsongstdlight', 'fontsize'=>8, 'stretchtext'=>4), 'N');


		}

    }
	public function Footer(){}
}


$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);


// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Order');

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
//logo
if(isset($order_logo)){
	$pdf->order_logo=$order_logo;
}
foreach($all_order_info as $vall){
	if(isset($configs['order-print-barcode'])&&$configs['order-print-barcode']){
	//	$pdf->order_code = $vall['Order']['order_code'];
	}
	// add a page
	$pdf->AddPage();
	ob_start();
?>

	<table>
		<tr>
			<th align="right" >订单编号：</th><td align="left"><?php echo $vall['Order']['order_code']?></td>
			<?php if($vall['Order']['created']){ ?>
			<th align="right">下单时间：</th><td align="left"><?php echo date("Y-m-d", strtotime($vall['Order']['created']));?></td>
			<?php }else{ ?>

			<?php } ?>
			<?php if($vall['Order']['payment_name']){ ?>
			<th align="right">支付方式：</th><td align="left"><?php echo $vall['Order']['payment_name']?></td>
			<?php }else{ ?>

			<?php } ?>
			<?php if($vall['Order']['payment_time']!='0000-00-00 00:00:00'&&$vall['Order']['payment_time']!='2008-01-01 00:00:00'){ ?>
			<th align="right">付款时间：</th><td align="left"><?php echo date("Y-m-d", strtotime($vall['Order']['payment_time']));?></td>
			<?php }else{ ?>
			<th></th><td></td>
			<?php } ?>
		</tr>
		<tr>
			<th align="right">收货人：</th><td align="left"><?php echo $vall['Order']['consignee']?></td>
			<?php if($vall['Order']['mobile']){ ?>
			<th align="right">联系电话：</th><td align="left"><?php echo $vall['Order']['mobile']?><?php if(!$vall['Order']['mobile']){ ?><?php echo @$vall['Order']['telephone']?><?php } ?></td>
			<?php }else{ ?>

			<?php } ?>
			<?php if($vall['Order']['shipping_name']){ ?>
			<th align="right">配送方式：</th><td align="left"><?php echo $vall['Order']['shipping_name']?></td>
			<?php }else{ ?>

			<?php } ?>
			<?php if($vall['Order']['shipping_status']!=1&&$vall['Order']['shipping_status']!=2){ ?>
			<th align="right">发货时间：</th><td align="left"><?php echo date("Y-m-d");?></td>
			<?php }else{ ?>
			<th align="right">发货时间：</th><td align="left"><?php echo date("Y-m-d", strtotime($vall['Order']['shipping_time']));?></td>
			<?php } ?>
		</tr>
		<?php if($vall['Order']['address']){ ?>
		<tr>
			<th align="right">收货地址：</th><td colspan="3">[<?php echo isset($vall['Order']['province'])?$vall['Order']['province']:'';?>&emsp;<?php echo isset($vall['Order']['city'])?$vall['Order']['city']:'';?>]&emsp;<?php echo $vall['Order']['address']?></td>
			<th align="right">邮编：</th><td align="left"><?php echo $vall['Order']['zipcode']?></td>
		</tr>
		<?php } ?>
	</table>
		<div height="1"></div>
<style>

#tablemain td{border:1px solid black;}
#tablemain th{border:1px solid black;}

</style>
	<table id='tablemain' cellpadding="4" style="font-size:10;width:100%;border:1px solid black;">
		<thead>
			<tr align="center" style="height:7px;line-height:7px;">
				<th style="border:1px solid black;width:15%">货号</th>
				<th style="border:1px solid black;width:40%">商品名称</th>
				<th style="border:1px solid black;width:13%">单价</th>
				<th style="border:1px solid black;width:6%;">数量</th>
				<th style="border:1px solid black;width:13%;">优惠</th>
				<th style="border:1px solid black;width:13%">小计</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($vall['OrderProduct'] as $k=>$v){?>
			<?php if($v['extension_code']!='virtual_card'){?>
			<tr style="line-height:5px;">
				<td style="border:1px solid black;width:15%"><?php if(isset($configs['order-print-barcode'])&&$configs['order-print-barcode']){$params = $pdf->serializeTCPDFtagParameters(array($v['product_code'], 'C128A', '', '', 25, 15, 0.4, array('position'=>'N', 'border'=>false, 'padding'=>'0', 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>4), 'N'));?><tcpdf method="write1DBarcode" params="<?php echo $params ?>" /><?php }else{echo $v['product_code'];} ?>
				</td>
				<td style="border:1px solid black;width:40%"><?php echo $v['product_name'];if(!empty($v['product_attrbute'])){echo "<br />".$v['product_attrbute'];}?></td>
				<td align="right" style="border:1px solid black;width:13%"><?php echo $v['product_price']?></td>
				<td align="right" style="border:1px solid black;width:6%;"><?php echo $v['product_quntity']?></td>
				<td align="right" style="border:1px solid black;width:13%;"><?php echo $v['adjust_fee']?></td>
				<td align="right" style="border:1px solid black;width:13%"><?php echo $v['product_total']?></td>
			</tr>
			<?php }} ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" style="text-align:left;border:none;" width="30%">
					<?php if(!empty($vall['Order']['message'])||!empty($vall['Order']['note'])){?>
					<span style="float:left;">发货备注:<?php if(!empty($vall['Order']['message'])){echo $vall['Order']['message'];} ?>
					<?php if(!empty($vall['Order']['note'])){ echo $vall['Order']['note'];} ?>
					</span>
					<?php }?></td><td  colspan="4" style="text-align:right;border:none;" width="70%">
					<span style="display:block"><?php
						echo "商品总金额:";
						echo $vall['Order']['format_novir_subtotal'];
					?></span><br />
					<span style="display:block">
					<?php
						if($vall['Order']['pack_fee']!='0.00'){
							echo "+ ";
							echo $vall['Order']['format_pack_fee'];
							echo "(包装费用)";
						}
						if($vall['Order']['card_fee']!='0.00'){
							echo "+ ";
							echo $vall['Order']['format_card_fee'];
							echo "(贺卡费用)";
						}
						if($vall['Order']['payment_fee']!='0.00'){
							echo "+ ";
							echo $vall['Order']['format_payment_fee'];
							echo "(支付费用)";
						}
						if($vall['Order']['shipping_fee']!='0.00'){
							echo "+ ";
							echo $vall['Order']['format_shipping_fee'];
							echo "(配送费用)";
						}
						if($vall['Order']['insure_fee']!='0.00'){
							echo "+ ";
							echo $vall['Order']['format_insure_fee'];
							echo "(保价费用)";
						}
						if($vall['Order']['tax']!='0.00'){
							echo "+ ";
							echo $vall['Order']['format_tax'];
							echo "(税)";
						}
						echo "= 订单总金额:";
						echo $vall['Order']['format_total'];
					?></span><br />
						<span style='display:block'>
					<?php
						if ($vall['Order']['format_discount']!='0.00' || $vall['Order']['point_fee']!='0.00' || $vall['Order']['format_coupon_fee']!='0.00') {
							echo "";
						}

						if($vall['Order']['format_discount']!='0.00'){
							echo "- ";
							echo $vall['Order']['format_discount'];
							echo "(折扣)";
						}
						if($vall['Order']['point_fee']!='0.00'){
							echo "- ";
							echo $vall['Order']['format_point_fee'];
							echo "(使用积分)";
						}
						if($vall['Order']['format_coupon_fee']!='0.00'){
							echo "- ";
							echo $vall['Order']['format_coupon_fee'];
							echo "(红包)";
						}
						if ($vall['Order']['discount']>0 || $vall['Order']['point_fee']!='0.00' || intval($vall['Order']['coupon_fee'])!='0.00') {
							echo "";
						}
					?></span><br />
					<span style="display:block"><?php
						echo "- 已付款金额:";
						echo $vall['Order']['format_money_paid'];
					?></span><br />
					<?php if($vall['Order']['should_pay']!='0.00'){?>
						= 应付款金额:<?php echo $vall['Order']['format_should_pay'];?>
					<?php } ?>
				</td>
			</tr>
		</tfoot>
	</table>
<?php  $content=ob_get_contents();ob_clean();

	$pdf->writeHTML($content, true, false, false, false, '');
	$pdf->lastPage();
}


// -----------------------------------------------------------------------------

//Close and output PDF document
$pdf->Output('order.pdf', 'I');

?>