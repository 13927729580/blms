<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-detail-menu">
  <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
    <li><a href="#basic_info"><?php echo $ld['order_details']?></a></li>
    <li><a href="#consignee_information"><?php echo $ld['receiving_information']?></a></li>
	<li><a href="#pro_info"><?php echo $ld['product_list']?></a></li>
    <li><a href="#other_title"><?php echo $ld['other_information']?></a></li>
    <li><a href="#subtotal_title"><?php echo $ld['subtotal']?></a></li>
    <li><a href="#operation_title"><?php echo $ld['operation_records']?></a></li>
  </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-detail-view" id="accordion" >
  <div id="basic_info" class="am-panel am-panel-default">
    <div class="am-panel-hd">
      <h4 class="am-panel-title">
      	<?php echo $ld['order_details'] ?>
		<?php if($svshow->operator_privilege("orders_mgt")){echo $html->link("[".$ld['print']."]","/orders/batch_order_shipping_print/{$order_info['Order']['id']}",array("target"=>"_blank","class"=>"noprint",'escape' => false));}?>
      </h4>
    </div>
    <div id="basic_information" class="am-panel-collapse am-collapse am-in">
      <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
	    <table class="am-table">
		  <tr>
		  	<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['order_code']?></td>
		  	<td class="am-u-lg-4 am-u-md-4 am-u-sm-4">
			  <?php echo $order_info['Order']['order_code']?>&emsp;&emsp;<span class="noprint"><?php echo $order_info['Order']['consignee']?></span><?php echo $html->link("[".$ld['send_or_view_message']."]","/messages/index/{$order_info['Order']['user_id']}",array(),false,false);?>
			  <?php if($svshow->operator_privilege("orders_edit")){echo $html->link($ld['edit'],"/orders/edit/{$order_info['Order']['id']}",false,false);}?>
		  	</td>
		  	<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['invoice_number']?></td>
		  	<td class="am-u-lg-4 am-u-md-4 am-u-sm-4">
			  <?php if($order_info['Order']['shipping_status']==1 || $order_info['Order']['shipping_status']==2){?>
				<?php echo $order_info['Order']['invoice_no']?>
			  <?php }?>
			</td>
		  </tr>
		  <tr>
		  	<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['order_status']?></td>
		  	<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $Resource_info["order_status"][$order_info['Order']['status']];?></td>
		  	<td   class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['domain_from']?></td>
		  	<td style="max-width:150px;word-wrap:break-word;  word-break:break-all;" class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $order_info['Order']['order_domain']?></td>
		  </tr>
		  <tr>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['order_payment_status']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $Resource_info["payment_status"][$order_info['Order']['payment_status']];?>  <?php echo $order_info['Order']['payment_name'];?></td>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['order_currency']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo empty($order_info['Order']['order_currency'])?"RMB":$order_info['Order']['order_currency'];?>&nbsp;</td>
		  </tr>
		  <tr>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['delivery_status']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4" ><?php echo $Resource_info["shipping_status"][$order_info['Order']['shipping_status']];?>
				<?php if($order_info['Order']['shipping_id']>0){echo $order_info['Order']['shipping_name'];}else{echo $ld['no_delivery'];}?></td>
			<td colspan="2" class="am-u-lg-6 am-u-md-6 am-u-sm-6" ></td>
		  </tr>
		  <?php if(isset($express_info)){?>
		  <tr>
			<td><?php echo $ld['logistics_tracking']?></td>
			<td colspan="3"><?php echo $express_info;?></td>
		  </tr>
		  <?php }?>
		 <?php if(isset($configs['vendor_shipment'])&&$configs['vendor_shipment']=='1'&&$svshow->operator_privilege("view_factory_time")){ ?>
		  <tr>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['vendor_information'] ?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $ld['pre_shipment'] ?> <?php echo isset($purchase_order_data['PurchaseOrder']['ESD']) && $purchase_order_data['PurchaseOrder']['ESD']!="0000-00-00" ? $purchase_order_data['PurchaseOrder']['ESD']:'' ?>
			</td>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['real_shipment'] ?> <?php echo isset($purchase_order_data['PurchaseOrder']['ASD']) && $purchase_order_data['PurchaseOrder']['ASD']!="0000-00-00" ? $purchase_order_data['PurchaseOrder']['ASD']:'' ?>
			</td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4">
			  <?php if(isset($purchase_order_data['PurchaseOrder'])&&$purchase_order_data['PurchaseOrder']['logistics_company_id']!=0){
				echo $logistics_companys[$purchase_order_data['PurchaseOrder']['logistics_company_id']];
				echo $purchase_order_data['PurchaseOrder']['invoice_no']!=""?"-".$purchase_order_data['PurchaseOrder']['invoice_no']:'';
			  }?>
			</td>
		  </tr>
		  <?php } ?>
	    </table>
	  </div>
	</div>
  </div>
  				
  <div id="consignee_information" class="am-panel am-panel-default">
    <div class="am-panel-hd">
      <h4 class="am-panel-title">
      	<?php echo $ld['receiving_information'] ?>
      </h4>
    </div>
    <div id="consignee_info" class="am-panel-collapse am-collapse am-in">
	  <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
	    <table class="am-table">
		  <tr>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['consignee']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $order_info['Order']['consignee']?>&nbsp;</td>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['region']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $order_info['Order']['country'].' '.$order_info['Order']['province'].' '.$order_info['Order']['city'];?>&nbsp;</td>
		  </tr>
		  <tr>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['email']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $order_info['Order']['email']?>&nbsp;</td>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['address']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $order_info['Order']['address']?>&nbsp;</td>
		  </tr>
		  <tr>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['zip_code']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $order_info['Order']['zipcode']?>&nbsp;</td>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['phone']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $order_info['Order']['telephone']?>&nbsp;</td>
		  </tr>
		  <tr>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['address_to']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $order_info['Order']['sign_building']?>&nbsp;</td>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['mobile']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $order_info['Order']['mobile']?>&nbsp;</td>
		  </tr>
		  <tr>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['best_delivery_time']?></td>
			<td colspan="3" class="am-u-lg-10 am-u-md-10 am-u-sm-10"><?php echo $order_info['Order']['best_time']?>&nbsp;</td>
		  </tr>
	    </table>
	  </div>
	</div>
  </div>
  <div id="pro_info" class="am-panel am-panel-default">
    <div class="am-panel-hd">
      <h4 class="am-panel-title">
      	<?php echo $ld['product_list'] ?>
      </h4>
    </div>
    <div id="product_list" class="am-panel-collapse am-collapse am-in">
	  <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
		<table class="am-table">
		  <tr>
			<th style="max-width:110px;"><?php echo $ld['product_image']?></th>
			<th><?php echo $ld['name']?></th>
		   	<th style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><?php echo $ld["shop_price"];?>(<?php echo $ld["order_list_price"];?>)</th>
			<th style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><?php echo $ld['price'];?></th>
			<th><?php echo $ld['order_quantity']?></th>
			<th style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><?php echo $ld['subtotal']?></th>
		  </tr>
		  <?php $sum = 0;if(isset($order_info['OrderProduct']) && sizeof($order_info['OrderProduct'])>0){$i=0;
			foreach($order_info['OrderProduct'] as $k=>$v){$i++;?>
		  <tr>
			<td style="width:110px;">
			<?php if(!empty($v["file_url"])){
			  echo $html->image($v["file_url"],array('width'=>'130','id'=>'image'.$i,'onclick'=>'img_click_big("image'.$i.'")'));
			}elseif($product_img_new[$v['product_id']]["Product"]["img_thumb"]!=""){
			  $product_img_new1=substr($product_img_new[$v['product_id']]["Product"]["img_thumb"],0,4);
			  if($product_img_new1=="http"){
				echo $html->image($product_img_new[$v['product_id']]["Product"]["img_thumb"],array('width'=>'100','height'=>'100'));
			  }else{
				if(isset($product_img_new[$v['product_id']]["Product"]["img_thumb"]) && !empty($product_img_new[$v['product_id']]["Product"]["img_thumb"])){
				  echo $html->image($server_host.$product_img_new[$v['product_id']]["Product"]["img_thumb"],array('width'=>'100'));}}}?>
			</td>
			<td>
			  <p><?php echo $html->link($v['product_name'],$webroot."products/{$v['product_id']}",array('target'=>'_blank'));?></p>
			  <p><?php echo $ld['product_code']?>&emsp;<?php echo $html->link($v['product_code'],$webroot."products/{$v['product_id']}",array('target'=>'_blank'));?></p>
			  <?php if(!empty($v['delivery_note'])){?>
			  <?php echo $ld["note2"];?><?php echo $v['delivery_note'];}?>&nbsp;<br/>
		          <?php echo $ld['product_attribute']?><br/><?php echo $v['product_attrbute']?>
				<?php if(isset($product_img_new[$v['product_id']])){
 				  if($v['product_style_id']!=0 && $v['product_style_id']!="" && $product_img_new[$v['product_id']]['Product']['product_type_id']!="" && in_array($product_img_new[$v['product_id']]['Product']['product_type_id'],$customize_product_type_list) && isset($all_product_code_infos[$v['product_id']]) && $all_product_code_infos[$v['product_id']]!=$v['product_code']){echo $html->link($ld["view"].$ld['product_attribute'],"javascript:void(0)",array('onclick'=>"view_product_attr_value('".$v['product_id']."','".$v['product_code']."','".$v['id']."')","data-am-modal"=>"{target: '#update_pro_attr'}"));?><br />
				<?php echo $html->link($ld['view_amendments'],'/orders/print_attr_value/'.$order_info['Order']['id'].'/'.$v['id'].'/'.$product_img_new[$v['product_id']]['Product']['product_type_id'].'/'.$v['product_style_id'],array('target'=>'_blank'));
					}
				}?> 
			</td>
		    <td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>">
			  <?php echo $v['product_price'];?>(<?php echo $all_product_infos[$v['product_id'].$v['product_code']];?>)
			</td>
			<td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>">
			  <?php 
			  $zhekou = @sprintf('%01.2f',($v['product_price']+$v['adjust_fee']/$v['product_quntity'])/$v['product_price']*10);
				if($zhekou!=10){
					echo $zhekou;
					echo ($backend_locale='eng'?"%":'折')."  =";
					echo sprintf("%01.2f",$v['adjust_fee']/$v['product_quntity']);
					echo "<br />";
				}?>
				<?php echo sprintf($price_format,($v['product_price']+$v['adjust_fee']/$v['product_quntity']));?>
			</td>
			<td><?php echo $v['product_quntity']?></td>
			<td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>">
			  <?php echo sprintf($price_format,$v['product_price']*$v['product_quntity']+$v['adjust_fee'])?>
			</td>
		  </tr>
		  <?php $sum=$sum+$product_img_new[$v['product_id']]["Product"]["market_price"]*$v['product_quntity'];
								}?>
			<!--
			<tr class="noprint">
				<td colspan="8" style="padding-top:10px;text-align:right;">
				<?php
					if(!empty($order_info['Order']['note'])){
						echo $ld['note2']."： ".$order_info['Order']['note'];
					}
				?>
				<span style="color:#FF6300;"><?php echo $ld['market_price']?><?php echo sprintf($price_format,sprintf("%01.2f",$sum));?></span>&emsp;<?php echo $ld['subtotal'];?>： <?php echo sprintf($price_format,sprintf("%01.2f",$order_info['Order']['subtotal']));?></td>
			</tr>
			-->
		  <tr style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>">
			<td colspan="8" style="padding-top:10px;text-align:right;">
			  <?php
				if(!empty($order_info['Order']['note'])){
					echo '<span style="float:left;">'.$ld['note2'].": ".$order_info['Order']['note'].'</span>';
				}
			  ?>
			  <span style="display:block">
				<?php if($order_info['Order']['subtotal']>0){ echo "&emsp;".$ld['product_total_amount'].": ";
				  printf($price_format,sprintf("%01.2f",$order_info['Order']['subtotal']));}
				?>
			  </span>
			  <span style="display:block">
				<?php
					if($order_info['Order']['tax']>0){ echo "&emsp;".$ld['order_abbr_tax'].": ";
						printf($price_format,sprintf("%01.2f",$order_info['Order']['tax']));}
					if($order_info['Order']['insure_fee']>0){ echo "&emsp;".$ld['order_abbr_insuredFee'].": ";
						printf($price_format,sprintf("%01.2f",$order_info['Order']['insure_fee']));}
					if($order_info['Order']['pack_fee']>0){ echo "&emsp;".$ld['packaging_costs'].": ";
						printf($price_format,sprintf("%01.2f",$order_info['Order']['pack_fee']));}
					if($order_info['Order']['card_fee']>0){ echo "&emsp;".$ld['card_fees'].": ";
						printf($price_format,sprintf("%01.2f",$order_info['Order']['card_fee']));}
					if($order_info['Order']['payment_fee']>0){ echo "&emsp;".$ld['order_abbr_handling'].": ";
						printf($price_format,sprintf("%01.2f",$order_info['Order']['payment_fee']));}
					if(!empty($SVConfigs['rank']) && $SVConfigs['rank']>0){if($order_info['Order']['point_fee']>0){
						echo "&emsp;".$ld['points_exchange'].": ";
						printf($price_format,sprintf("%01.2f",$order_info["Order"]["point_fee"]));
					}}
					if($order_info['Order']['money_paid']>0){ echo "&emsp;".$ld['amount_paid'].": ";
						printf($price_format,sprintf("%01.2f",$order_info["Order"]["money_paid"]));}
					if($order_info['Order']['coupon_fee']>0){ echo "&emsp;".$ld['use_coupons'].": ";
						printf($price_format,sprintf("%01.2f",$order_info["Order"]["coupon_fee"]));}
					?>
			  </span>
			  <span style="display:block">
				<?php
				  if($order_info['Order']['shipping_fee']>0){ echo "&emsp;".$ld['order_abbr_shippingFee'].": ";
					printf($price_format,sprintf("%01.2f",$order_info['Order']['shipping_fee']));}
				  if($order_info['Order']['discount']>0){ echo "&emsp;".$ld['discount'].": ";
					printf($price_format,sprintf("%01.2f",$order_info['Order']['discount']));}
				  if($order_info['Order']['total']>0){ echo "&emsp;".$ld['order_abbr_total'].": ";
					printf($price_format,sprintf("%01.2f",$order_info['Order']['total']));}
				?>
			  </span>
			  <span style="display:block">
				<?php echo "&emsp;".$ld['order_abbr_totalToPay'].": ";
		printf($price_format,sprintf("%01.2f",$order_info['Order']['total']-$order_info["Order"]["point_fee"]-$order_info['Order']['discount']-$order_info['Order']['coupon_fee']-$order_info['Order']['money_paid']));
					?>
			  </span>
			</td>
		  </tr>
		  <?php }?>
		</table>
		<table class="am-table">
		  <?php if(isset($order_packaging_list) && sizeof($order_packaging_list)>0){?>
		  <tr>
			<th><?php echo $ld['package_name']?></th>
			<th style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><?php echo $ld['package_price']?></th>
			<th><?php echo $ld['package_number']?></th>
			<th><?php echo $ld['note2']?></th>
		  </tr>
		  <?php foreach($order_packaging_list as $k=>$v){?>
		  <tr>
			<input name="OrderPackaging_id[]" type="hidden" value="<?php echo $v['OrderPackaging']['id']?>" />
			<td><?php echo $v['OrderPackaging']['packaging_name'];?></td>
			<td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><?php echo $v['OrderPackaging']['packaging_fee']?></td>
			<td><?php echo $v['OrderPackaging']['packaging_quntity']?></td>
			<td><?php echo $v['OrderPackaging']['note']?></td>
		  </tr>
		  <?php }}?>
		  <?php if(isset($order_card_list) && sizeof($order_card_list)>0){?>
		  <tr>
			<th><?php echo $ld['card_name']?></th>
			<th style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><?php echo $ld['card_price']?></th>
			<th><?php echo $ld['card_number']?></th>
			<th><?php echo $ld['note2']?></th>
		  </tr>
		  <?php	foreach($order_card_list as $k=>$v){?>
		  <tr>
			<input name="OrderCsrd_id[]" type="hidden" value="<?php echo $v['OrderCard']['id']?>" />
			<td><?php echo $v['OrderCard']['card_name'];?></td>
			<td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><?php echo $v['OrderCard']['card_fee']?></td>
			<td><?php echo $v['OrderCard']['card_quntity']?></td>
			<td><?php echo $v['OrderCard']['note']?></td>
		  </tr>
		  <?php }}?>
		</table>
	  </div>
	</div>
  </div>
  <div id="other_title" class="am-panel am-panel-default">
    <div class="am-panel-hd">
      <h4 class="am-panel-title">
      	<?php echo $ld['other_information'] ?>
      </h4>
    </div>
    <div id="other_information" class="am-panel-collapse am-collapse am-in">
	  <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
		<table class="am-table">
		  <tr>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['message_to_shop']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $order_info['Order']['postscript']?>&nbsp;</td>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['message_to_customer'];?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $order_info['Order']['to_buyer']?>&nbsp;</td>
		  </tr>
		  <tr>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['invoice_type']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php if(isset($InvoiceType) && sizeof($InvoiceType)>0){foreach( $InvoiceType as $k=>$v ){?>
				<?php if($order_info['Order']['invoice_type']==$v["InvoiceType"]["id"]){echo $v["InvoiceTypeI18n"]["name"];}?>
				<?php }}?>
				&nbsp;</td>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['invoice_title']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $order_info['Order']['invoice_payee']?>&nbsp;</td>
		  </tr>
		  <tr>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['invoice_content']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $order_info['Order']['invoice_content']?>&nbsp;</td>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['stock_handling']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $order_info['Order']['how_oos']?>&nbsp;</td>
		  </tr>
		</table>
	  </div>
	</div>
  </div>
  <div id="subtotal_title" class="am-panel am-panel-default">
    <div class="am-panel-hd">
      <h4 class="am-panel-title">
      	<?php echo $ld['subtotal'] ?>
      </h4>
    </div>
    <div id="subtotal_information" class="am-panel-collapse am-collapse am-in">
	  <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
		<table class="am-table">
		  <tr>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['product_total_amount']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php printf($price_format,sprintf("%01.2f",$order_info['Order']['subtotal']))?>
				<input type="hidden" id="subtotal" name="subtotal" value="<?php echo $order_info['Order']['subtotal']?>"></td>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['discount']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php printf($price_format,sprintf("%01.2f",$order_info['Order']['discount']));?></td>
		  </tr>
		  <tr>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['invoice_tax']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php printf($price_format,sprintf("%01.2f",$order_info['Order']['tax']));?>
				<input type="hidden" id="tax" name="tax" value="<?php echo $order_info['Order']['tax']?>"></td>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['order_total_amount']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php printf($price_format,sprintf("%01.2f",$order_info["Order"]["total"]));?></td>
	 	  </tr>
		  <tr>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['shipping_fee']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php printf($price_format,sprintf("%01.2f",$order_info['Order']['shipping_fee']));?></td>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['amount_paid']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php printf($price_format,sprintf("%01.2f",$order_info["Order"]["money_paid"]));?></td>
		  </tr>
		  <tr>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['insured_costs']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php printf($price_format,sprintf("%01.2f",$order_info['Order']['insure_fee']));?></td>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['use_balance']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php printf($price_format,sprintf("%01.2f",$balance_log["UserBalanceLog"]["amount"]));?></td>
		  </tr>
		  <tr>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['order_payment_fee']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php printf($price_format,sprintf("%01.2f",$order_info['Order']['payment_fee']));?></td>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['use_points']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $order_info["Order"]["point_use"];?></td>
		  </tr>
		  <tr>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['packaging_costs']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php printf($price_format,sprintf("%01.2f",$order_info['Order']['pack_fee']));?></td>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['points_exchange']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php printf($price_format,sprintf("%01.2f",$order_info["Order"]["point_fee"]));?></td>
		  </tr>
		  <tr>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['card_fees']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php printf($price_format,sprintf("%01.2f",$order_info['Order']['card_fee']));?></td>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['use_coupons']?></td>
			<td class="am-u-lg-4 am-u-md-4 am-u-sm-4">
				<?php printf($price_format,sprintf("%01.2f",$order_info["Order"]["coupon_fee"]));?>
				<?php if(isset($order_info['Order']['coupon_id']) && $order_info['Order']['coupon_id']!=""){
						foreach($coupon_name_arr as $ca){
							if($ca == ""){
								continue;
							}
							echo  '['.$ca.']';
						}
				}?>
			</td>
		  </tr>
		  <tr>
			<td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['amount_payable']?></td>
			<td colspan="3" class="am-u-lg-10 am-u-md-10 am-u-sm-10"><?php printf($price_format,sprintf("%01.2f",$order_info['Order']['total']-$order_info["Order"]["point_fee"]-$order_info['Order']['discount']-$order_info['Order']['coupon_fee']-$order_info['Order']['money_paid']));?></td>
		  </tr>
		</table>
	  </div>
	</div>
  </div>
  <div id="operation_title" class="am-panel am-panel-default">
    <div class="am-panel-hd">
      <h4 class="am-panel-title">
      	<?php echo $ld['operation_records'] ?>
      </h4>
    </div>
    <div id="operation_information" class="am-panel-collapse am-collapse am-in">
	  <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
		<table class="am-table">
		  <tr>
			<th><?php echo $ld['operation_time']?></th>
			<th><?php echo $ld['operator']?></th>
			<th><?php echo $ld['order_status']?></th>
			<th><?php echo $ld['order_payment_status']?></th>
			<th><?php echo $ld['shipping_status']?></th>
			<th><?php echo $ld['note2']?></th>
		  </tr>
		  <?php if(isset($action_list)&&sizeof($action_list)>0){foreach($action_list as $k=>$v){?>
		  <tr>
			<td><?php echo $v['OrderAction']['created']?></td>
			<td><?php echo isset($v['Operator']['name'])?$v['Operator']['name']:$v['OrderAction']['operator_name'];?></td>
			<td><?php echo $Resource_info["order_status"][$v['OrderAction']['order_status']];?></td>
			<td><?php echo $Resource_info["payment_status"][$v['OrderAction']['payment_status']];?></td>
			<td><?php echo $Resource_info["shipping_status"][$v['OrderAction']['shipping_status']];?></td>
			<td><?php echo $v['OrderAction']['action_note']?></td>
		  </tr>
		  <?php }}?>
		</table>
	  </div>
	</div>
  </div>
</div>
            
<!-- 属性修改弹窗start -->
<div class="am-popup" id="update_pro_attr">
  <div class="am-popup-inner">
    <div class="am-popup-hd" style=" z-index: 11;">
      <span data-am-modal-close class="am-close">&times;</span>
    </div>
    <div class="am-popup-bd"></div>
  </div>
</div>
<!-- 属性修改弹窗end -->
<style type="text/css">
.am-table{margin-bottom:0;}
</style>
<script type="text/javascript">

function view_product_attr_value(pro_id,pro_code,order_pro_id){
	var user_id="<?php echo $order_info['Order']['user_id']; ?>";
	var order_id="<?php echo $order_info['Order']['id']; ?>";
	$.ajax({ url: admin_webroot+"orders/update_order_product_attr/attr_view",
			type:"POST",
			data:{pro_code:pro_code,pro_id:pro_id,user_id:user_id,order_id:order_id,order_product_id:order_pro_id},
			dataType:"html",
			success: function(data){
                $(".am-dimmer").css("display","block");
				$("#update_pro_attr .am-popup-bd").html(data);
	  		}
	  	});
}
</script>