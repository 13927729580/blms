<?php
	//echo $html->css('/css/layout_min');
	echo $html->css('/skins/default/css/print');
?>
<style type="text/css">
/*body,td { font-size:13px; }*/
	h1 {text-align:center;}
	table.oneorder {margin-top:20px;border:none; }
	table.oneorder td {border:none;}
	table.oneorder td.onelabel { width: 55px;text-align:right; }


	.tablemain table { border:none;width:auto;}
	.tablemain table tfoot td { border: none; }

	.listsearch {width:100%;}
	.listsearch label { min-width: 200px; display: inline-block; }
	.listsearch label strong { margin-right:10px; }
	.listsearch table th { text-align:right; }
	.preprint input[type=button]{  height: 32px;line-height:30px;padding: 0 8px; font-size: 14px;}
</style>
<?php if(isset($configs['shop-order-logo'])&&!empty($configs['shop-order-logo'])){?>
<div style="width: 50pc;margin: 0 auto 5px;"><?php echo $html->image($configs['shop-order-logo'],array('class'=>'printlogo')); ?></div>
<?php }?>
<?php foreach($all_order_info as $vall){
//if(($vall['Order']['allvirtual']==0)&&((($vall['Order']['is_cod']==1)&&($vall['Order']['status']==1)&&($vall['Order']['shipping_status']==0))||(($vall['Order']['is_cod']==0)&&($vall['Order']['payment_status']==2)&&($vall['Order']['shipping_status']==0)))){?>
<div class="listsearch needprint">
	<table align="center" style="width:980px">
		<tr>
			<th>订单编号:</th><td><?php if(isset($configs['order-print-barcode'])&&$configs['order-print-barcode']){ echo $html->image('/admin/barcodes/view/'.$vall['Order']['order_code'],array('style'=>'vertical-align: middle; height:40px;')); }else{echo $vall['Order']['order_code'];}?></td>
			<?php if($vall['Order']['created']){ ?>
			<th>下单时间:</th><td><?php echo date("Y-m-d", strtotime($vall['Order']['created']));?></td>
			<?php }else{ ?>
			<th></th><td></td>
			<?php } ?>
			<?php if($vall['Order']['payment_name']){ ?>
			<th>支付方式:</th><td><?php echo $vall['Order']['payment_name']?></td>
			<?php }else{ ?>
			<th></th><td></td>
			<?php } ?>
			<?php if($vall['Order']['payment_time']!='0000-00-00 00:00:00'&&$vall['Order']['payment_time']!='2008-01-01 00:00:00'){ ?>
			<th>付款时间:</th><td><?php echo date("Y-m-d", strtotime($vall['Order']['payment_time']));?></td>
			<?php }else{ ?>
			<th></th><td></td>
			<?php } ?>
		</tr>
		<tr>
			<th>收货人:</th><td><?php echo $vall['Order']['consignee']?></td>
			<?php if($vall['Order']['mobile']){ ?>
			<th>联系电话:</th><td><?php echo $vall['Order']['mobile']?><?php if($vall['Order']['telephone']){ ?>&emsp;/&emsp;<?php echo $vall['Order']['telephone']?><?php } ?></td>
			<?php }else{ ?>
			<th></th><td></td>
			<?php } ?>
			<?php if($vall['Order']['shipping_name']){ ?>
			<th>配送方式:</th><td><?php echo $vall['Order']['shipping_name']?></td>
			<?php }else{ ?>
			<th></th><td></td>
			<?php } ?>
			<?php if($vall['Order']['shipping_status']!=1&&$vall['Order']['shipping_status']!=2){ ?>
			<th>发货时间:</th><td><?php echo date("Y-m-d");?></td>
			<?php }else{ ?>
			<th>发货时间:</th><td><?php echo date("Y-m-d", strtotime($vall['Order']['shipping_time']));?></td>
			<?php } ?>
		</tr>
		<?php if($vall['Order']['address']){ ?>
		<tr>
			<th>收货地址:</th><td colspan="5">[<?php echo isset($vall['Order']['province'])?$vall['Order']['province']:'';?>&emsp;<?php echo isset($vall['Order']['city'])?$vall['Order']['city']:'';?>]&emsp;<?php echo $vall['Order']['address']?></td>
			<th>邮编:</th><td><?php echo $vall['Order']['zipcode']?></td>
		</tr>
		<?php } ?>
	</table>
</div>
<div class="tablemain">
	<table align="center" style="width:900px">
		<thead>
			<tr align="center">
				<th width="5%">序号</th>
				<th width="10%">货号</th>
				<th width="20%">商品名称</th>
				<th width="15%">属性</th>
				<th width="5%">单价</th>
				<th width="5%">数量</th>
				<th width="5%">小计</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($vall['OrderProduct'] as $k=>$v){?>
			<?php if($v['extension_code']!='virtual_card'){?>
			<tr align="center">
				<td><?php echo $k+1;?></td>
				<td><?php echo $v['product_code']?><?php if(isset($configs['order-print-barcode'])&&$configs['order-print-barcode']){ echo $html->image('/admin/barcodes/view/'.$v['product_code'],array('height'=>'40px;')); }?></td>
				<td><?php echo $v['product_name']?></td>
				<td><?php echo $v['product_attrbute']?></td>
				<td><?php echo $v['product_price']?></td>
				<td><?php echo $v['product_quntity']?></td>
				<td><?php echo $v['product_total']?></td>
			</tr>
			<?php }} ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="7" style="padding-top:10px;text-align:right;border:none;">
					<?php if(!empty($vall['Order']['message'])||!empty($vall['Order']['note'])){?>
					<span style="float:left;">买家留言:
					<?php if(!empty($vall['Order']['message'])){echo $vall['Order']['message'];} ?>
					<?php if(!empty($vall['Order']['note'])){ echo $vall['Order']['note'];} ?>
					</span>
					<?php }?>
					<span style="display:block">商品总金额:<?php echo $vall['Order']['format_novir_subtotal']?></span>
					<span style="display:block"><?php if($vall['Order']['pack_fee']!='0.00'){?>
						 包装费用:+<?php echo $vall['Order']['format_pack_fee']?>
						<?php } ?>
						<?php if($vall['Order']['card_fee']!='0.00'){?>
						 +<?php echo $vall['Order']['format_card_fee']?>(贺卡费用)
						<?php } ?>
						<?php if($vall['Order']['payment_fee']!='0.00'){?>
						 +<?php echo $vall['Order']['format_payment_fee']?>(支付费用)
						<?php } ?>
						<?php if($vall['Order']['shipping_fee']!='0.00'){?>
						 +<?php echo $vall['Order']['format_shipping_fee']?>(配送费用)
						<?php } ?>
						<?php if($vall['Order']['insure_fee']!='0.00'){?>
						 +<?php echo $vall['Order']['format_insure_fee']?>(保价费用)
						<?php } ?>
						= 订单总金额:<?php echo $vall['Order']['format_total'];?></span>
					<span style="display:block">
						<?php if($vall['Order']['format_discount']!='￥0.00元'){?>
						 -<?php echo $vall['Order']['format_discount']?>(折扣)
						<?php } ?>
						<?php if($vall['Order']['point_fee']!='0.00'){?>
						 -<?php echo $vall['Order']['format_point_fee']?>(使用积分)
						<?php } ?>
						<?php if($vall['Order']['format_coupon_fee']!='￥0.00元'){?>
						 -<?php echo $vall['Order']['format_coupon_fee']?>(使用红包)
						<?php } ?>
						= 应付款金额:<?php echo $vall['Order']['format_should_pay'];?></span>
					<?php if($vall['Order']['money_paid']!='0.00'){?>
					<span style="display:block">已付款金额:<?php echo $vall['Order']['format_money_paid']?></span>
					<?php } ?>
				</td>
			</tr>
		</tfoot>
	</table>
</div>
<?php // }?>
<?php  }?>
