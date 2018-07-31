<?php
	$is_wechat=true;
	if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
		$is_wechat=false;
	}
?>
<div class="am-container orderInfo">
	<?php if($order_info['Order']['service_type']!='virtual'){ ?>
	<div class="am-panel am-panel-default">
		<div class="am-panel-hd">
			<strong class="am-text-primary am-text-md"><?php echo $ld['order_information']?></strong>
			<span><b><?php echo $ld['order_no.']?></b>:<?php echo $order_info['Order']['order_code'];?></span>
			<span><b><?php echo $ld['status']?></b>:<?php if($order_info['Order']['status']==0){echo $ld['unrecognized'];}else if($order_info['Order']['status']==2){ ?>
				<?php echo $ld['order_canceled'] ?>
				<?php  }elseif($order_info['Order']['payment_status']==0){?>
				<?php if(isset($order_info['Order']['payment_is_cod'])&&$order_info['Order']['payment_is_cod']==1 && $order_info['Order']['shipping_status']==1){echo $ld['order_shipped'];}else{ echo $ld['order_unpaid'] ;}?>
				<?php }elseif($order_info['Order']['status']==1 && $order_info['Order']['shipping_status']==0 && $order_info['Order']['payment_status']==2){ ?>
				<?php echo $ld['order_processing'] ?>
				<?php }elseif($order_info['Order']['status']==1 && $order_info['Order']['shipping_status']==1 && $order_info['Order']['payment_status']==2){ ?>
				<?php echo $ld['order_shipped'] ?>
				<?php }elseif($order_info['Order']['status']==1 && $order_info['Order']['shipping_status']==2 && $order_info['Order']['payment_status']==2){ ?>
				<?php echo $ld['order_complete'] ?>
				<?php }elseif($order_info['Order']['status']==4){echo $ld['product_returns'];}else if($order_info['Order']['shipping_status']==3){echo $ld['order_processing'];}else if($order_info['Order']['shipping_status']==5){echo $ld['product_returns'];}?>
						<?php
							if($order_info['Order']['order_currency']=='Euro'){
								$currency_code='EUR';
							}else if($order_info['Order']['order_currency']=='Dollar'){
								$currency_code='USD';
							}else if($order_info['Order']['order_currency']=='Pound'){
								$currency_code='GBP';
							}else if($order_info['Order']['order_currency']=='CA_Dollar'){
								$currency_code='CAD';
							}else if($order_info['Order']['order_currency']=='AU_Dollar'){
								$currency_code='AUD';
							}else if($order_info['Order']['order_currency']=='Francs'){
								$currency_code='CHF';
							}else if($order_info['Order']['order_currency']=='hk'){
								$currency_code='HKD';
							}else if($order_info['Order']['order_currency']=='CNY'){
								$currency_code='CNY';
							}
						?></span>
			<?php if($order_info['Order']['status']==1 && $order_info['Order']['shipping_status']==1 && $order_info['Order']['payment_status']==2){ ?>
					<?php echo $svshow->link($ld['confirm_receipt'],'/orders/receiving_order/'.$order_info['Order']['id']."/1",array('class'=>'am-btn am-btn-secondary'));?>
					<?php  } ?>
		</div>
		<div class="am-panel-bd" style="margin-left:12px;">
			<table>
				<tr>
					<th><?php echo $ld['contact_information']?></th>
				</tr>
				<tr>
					<td><?php echo $ld['consignee']?>: <?php echo $order_info['Order']['consignee'];?></td>
				</tr>
    		    <?php if(isset($shipping_info['Shipping']['code'])&&$shipping_info['Shipping']['code']=='cac'){ }else{ ?>
                <tr>
                    <td>
        			<?php echo $ld['delivery_address']?>:<?php echo $order_info['Order']['country']." ";echo $order_info['Order']['province']." "; echo $order_info['Order']['city']." "; echo $order_info['Order']['address']." ";?>
                	</td>
        		</tr>
				<?php if(isset($order_info['Order']['sign_building'])&&!empty($order_info['Order']['sign_building'])){?>
				<tr>
					<td><?php echo $ld['address_to']?>: <?php echo $order_info['Order']['sign_building'];?></td>
				</tr>
				<?php }?>
                <?php if(isset($order_info['Order']['zipcode'])&&!empty($order_info['Order']['zipcode'])){?>
				<tr>
					<td><?php echo $ld['zip']?>: <?php echo $order_info['Order']['zipcode'];?></td>
				</tr>
                <?php }?>
        		<?php }?>
				<?php if(!empty($order_info['Order']['telephone'])){?>
				<tr>
					<td><?php echo $ld['telephone']?>: <?php echo $order_info['Order']['telephone'];?></td>
				</tr>
				<?php } if(!empty($order_info['Order']['mobile'])){?>
				<tr>
					<td><?php echo $ld['mobile']?>: <?php echo $order_info['Order']['mobile'];?></td>
				</tr>
				<?php }?>
<!--				<tr>
					<td><?php echo $ld['e-mail']?>: <?php echo $order_info['Order']['email'];?></td>
				</tr>-->
				
			</table>
		</div>
	</div>
	<?php } ?>
	<div class="am-panel am-panel-default shipping_method">
		<div class="am-panel-hd"><strong class="am-text-primary am-text-md"><?php echo $ld['payment_and_shipping_methods']?></strong></div>
		<div class="am-panel-bd"   style="margin-left:11px;margin-top: 1rem;">
			<ul class="am-list am-list-static">
				<li><?php echo $order_info['Order']['payment_name'];?><?php echo !empty($sub_pay_name[$order_info['Order']['sub_pay']])?"-".$sub_pay_name[$order_info['Order']['sub_pay']]:'' ?></li>
				<?php if(isset($order_info['Order']['shipping_name']) && $order_info['Order']['shipping_name']!=""){ ?>
				<li><span><?php echo $order_info['Order']['shipping_name'];?></span>
				<?php if(isset($shipping_info['Shipping']['code'])&&$shipping_info['Shipping']['code']=='cac'){?>
				<?php echo $shipping_info['ShippingI18n']['description'];?>
				<?php }?>
				</li>
				<?php } ?>
				<?php if($order_info['Order']['payment_status']==0 || ($order_info['Order']['status']==1 && $order_info['Order']['shipping_status']==1 && $order_info['Order']['payment_status']==2)){?>
				<?php if(!empty($order_info['Order']['best_time'])){?>
				<li><?php echo $ld['delivery_date']?>:&nbsp;&nbsp;<?php echo $order_info['Order']['best_time'];?></li>
				<?php }?>
				<?php }?>
				<?php if(!empty($order_info['Order']['logistics_company_id'])){?>

				<li><?php echo $ld['logistics_company']?>:&nbsp;&nbsp;<?php echo isset($company_info['LogisticsCompany']['name'])?$company_info['LogisticsCompany']['name']:"";?>
				  <input type="hidden" id="logistics_company_id"  value="<?php echo !empty($company_info['LogisticsCompany']['id'])?$company_info['LogisticsCompany']['id']:'';?>"/>
				  
				  <input type="hidden" id="Company_express_code" value="<?php echo $company_info['LogisticsCompany']['express_code']; ?>" />
				  
				</li>
				<li><?php echo $ld['invoice_number']?>:&nbsp;&nbsp;<?php echo $order_info['Order']['invoice_no'];?>
					<!-- <input type="hidden" id="order_invoice_no"  value="<?php echo $order_info['Order']['invoice_no'];?>"/> -->
				</li>
				<li id="express_info">
					<span style="float:left;"><?php echo $ld['logistics_tracking'];?>:</span>
					<div style="float:left;margin-left: 8px;">
						<span id="ex_info">
						<?php
							//参数设置
							$post_data = array();
							$post_data["customer"] = '3944EE0AE174459C10C6EE72331BEC8C';
							$key= 'uJMFBoPH7780' ;
							$com=$company_info['LogisticsCompany']['express_code'];
							$num=$order_info['Order']['invoice_no'];
							$post_data["param"] = "{'com':'$com','num':'$num'}";
							$url='https://poll.kuaidi100.com/poll/query.do';
							$post_data["sign"] = md5($post_data["param"].$key.$post_data["customer"]);
							$post_data["sign"] = strtoupper($post_data["sign"]);
							$o="?";
							foreach ($post_data as $k=>$v)$o.= "$k=".urlencode($v)."&";  //默认UTF-8编码格式
							$data=file_get_contents($url.$o);
							if(!empty($data))$data=json_decode($data,true);
							//echo "<pre>";
							//print_r($data);
						?>
						<?php pr($company_info); ?>
							<?php foreach($data['data'] as $k=>$v){ ?>
	                            <span style="margin-bottom: 20px;display: block;"><?php echo $v['time'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $v['context']; ?></span>
	                        <?php } ?>
						</span>
					</div>
					<div class="am-cf"></div>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>
	
	
	<?php if(!empty($order_info['Order']['invoice_type'])){?>
		
	<div class="am-panel am-panel-default invoice_type">
		<div class="am-panel-hd"><strong class="am-text-primary am-text-md"><?php echo $ld['invoice_information']?></strong></div>
		<div class="am-panel-bd">
			<table>
				<tr>
					<td><?php echo $ld['invoice_type']?>:<?php echo $order_info['Order']['invoice_type'];?></td>
				</tr>
				<tr>
					<td><?php echo $ld['invoices_payable_to']?>:<?php echo $order_info['Order']['invoice_payee'];?></td>
				</tr>
				<tr>
					<td><?php echo $ld['invoice_details']?>:<?php echo $order_info['Order']['invoice_content'];?></td>
				</tr>
			</table>
		</div>
	</div>
	<?php } ?>
		
	<?php 
		if(isset($payment_info['Payment'])&&($order_info['Order']['payment_status']==0||(isset($need_pay)&&floatval($need_pay)>0))&& $order_info['Order']['status']!=2){
			//积分抵扣
			if(isset($configs['use_point'])&&$configs['use_point']=='1'){
	?>			
				<h2 style="padding-left:1.25rem;margin:1em 0 0 10px;font-size:14px;" class='am-text-primary'><?php echo $ld['use_points'] ?></h2>
				<hr style="border-width:2px;" />
				<div class='am-form'>
					<div class='am-form-group'>
						<div class='am-u-lg-12'><span style="color:#999"><?php echo  $ld['your_points'];?></span><?php echo $user_list['User']['point'];?>&nbsp;<span style="color:#999">,&nbsp;<?php echo $ld['available_points']; ?>:</span><span id="available_points"><?php
										$can_use_point = round($order_info['Order']['need_paid'] * $configs['point-equal']);
	$can_use_point=isset($user_data['User'])&&$user_data['User']['point']>$can_use_point?$can_use_point:(isset($user_list['User'])?$user_list['User']['point']:0);
										echo $can_use_point;
				?></span>&nbsp;<span style="color:#999">,</span>&nbsp;<?php echo sprintf($ld['points_gifts_money'],$configs['point-equal'],$svshow->price_format("1",$configs['price_format']));?></span></div>
						<div class='am-cf'></div>
					</div>
					<div class='am-form-group'>
						<div class='am-u-lg-6'>
							<div class="am-input-group">
								<span class="am-input-group-label"><input type="checkbox" onclick="order_use_point(this)"></span>
								<input type="text" class="am-form-field" value='0' max="<?php echo $can_use_point; ?>" disabled>
								<span class="am-input-group-btn"><button type='button' class='am-btn am-btn-primary' disabled onclick="order_point_pay(this,<?php echo $order_info['Order']['id']; ?>)"><?php echo $ld['confirm'] ?></button></span>
							</div>
						</div>
						<div class='am-cf'></div>
					</div>
				</div>
	<?php
			}
	?>
				<h2 style="padding-left:1.25rem;margin:1em 0 0 10px;font-size:14px;" class='am-text-primary'><?php echo $ld['payment_method'] ?></h2>
				<hr style="border-width:2px;" />
	<?php
			if($payment_info['Payment']['is_online']=='1'){//在线支付
				echo $form->create('balances',array('action'=>'/balance_deposit2','name'=>'payform','id'=>'payform','type'=>'POST'));
	?>
				<input name='amount_num' type='hidden' value="<?php echo $order_info['Order']['need_paid'];?>">
				<input type='hidden' value="<?php echo $order_info['Order']['payment_id'];?>">
				<input type='hidden' name='cmd' value='_xclick'/>
				<input type='hidden' name='business' value='order@idealhere.com'/>
				<input type='hidden' name='item_name' value='<?php echo $order_info['Order']['order_code'];?>'/>
				<input type='hidden' name='amount' value='<?php echo $order_info['Order']['total'];?>'/>
				<input type='hidden' name='currency_code' value='<?php echo $order_info['Order']['order_currency'];?>'/>
				<input type='hidden' name='return' value='<?php echo $server_host;?>/'/>
				<input type='hidden' name='invoice' value='<?php echo $order_info['Order']['id'];?>'/>
				<input type='hidden' name='charset' value='utf-8'/>
				<input type='hidden' name='no_shipping' value='1'/>
				<input type='hidden' name='no_note' value='1' />
				<input type='hidden' name='notify_url' value='<?php echo $server_host;?>/'/>
				<input type='hidden' name='rm' value='2'/>
				<input type='hidden' name='cancel_return' value='<?php echo $server_host;?>/'/>
				<div class="am-form am-form-horizontal" style="margin-top:1.25rem;">
					<?php if(isset($sub_paylist)&&sizeof($sub_paylist)>0){ ?>
					<div class="am-form-detail">
                        <div class="am-form-group">
                            <div class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-btn-group radio-btn" data-am-button>
                            <?php foreach($sub_paylist as $k=>$v){ ?>
                                <label class="am-btn am-btn-default <?php echo $order_info['Order']['sub_pay']==$v['Payment']['id']?" am-active":''; ?>">
                                    <input type="radio" class="payments  pay_<?php echo $v['Payment']['code'] ?>" name="payment_id" <?php echo $order_info['Order']['sub_pay']==$v['Payment']['id']?" checked='checked'":''; ?> value="<?php echo $v['Payment']['id']; ?>"/><?php echo $html->image($v['Payment']['logo'],array('alt'=>$v['PaymentI18n']['name']));  ?>
                                </label>
                            <?php } ?>
                            </div>
    	    			</div>
    				</div>
					<?php } ?>
					<div class="am-form-detail payinfo">
						<div class="am-form-group">
							<div class="am-u-lg-12 am-u-md-12 am-u-sm-12"><a class="am-btn am-btn-block am-btn-warning" href="javascript:void(0);" onclick="topay()"><?php echo $ld['pay_now'];?></a></div>
						</div>
					</div>
				</div>
	<?php
				echo $form->end();
			}else{
	?>
			<div class="am-form am-form-horizontal" style="margin-top:1.25rem;">
				<?php if(isset($sub_paylist)&&sizeof($sub_paylist)>0){ ?>
				<div class="am-form-detail">
					<div class="am-form-group">
						<div class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-btn-group radio-btn" data-am-button>
							<?php foreach($sub_paylist as $k=>$v){ ?>
								<p><?php echo $v['PaymentI18n']['name']; ?></p>
								<p><?php echo $v['PaymentI18n']['description']; ?></p>
								<?php if(($k+1)<sizeof($sub_paylist)){ ?><hr style="border-width:2px;" /><?php } ?>
							<?php } ?>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
	<?php
			}
		}
	?>
		<div class="am-panel am-panel-default">
		 <div class="am-panel-bd" style="padding:0 0.5rem;">
			<table class="am-table am-table-bd am-table-striped admin-content-table" style="margin-bottom: 0;">
				<tr  class="am-text-primary am-text-lg">
					<th  colspan='3' width="55%" style="padding-left: 10px;"><?php echo $ld['product_name']?></th>
                                 <th width="15%" style="white-space:nowrap"><?php echo $ld['price']?></th>
					<?php if(isset($configs['point-use-status']) && $configs['point-use-status']==1){?>
					<th width="15%" style="white-space:nowrap"><?php echo $ld['single-product_integration']?></th>
					<?php }?>
					<th width="15%" style="white-space:nowrap"><?php echo $ld['quantity']?></th>
					<?php if(isset($order_info['Order']['status'])&&$order_info['Order']['status']==1&&isset($order_info['Order']['shipping_status'])&&$order_info['Order']['shipping_status']==2){?>
					<th><?php echo $ld['operation']?></th>
					<?php }?>
				</tr>
				<?php if(sizeof($order_products)>0) foreach($order_products as $k=>$v) { ?>
			<tr>
				<td class="productname" style="padding-bottom:0">
					<?php if(!empty($v['OrderProduct']['file_url'])){
						echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'img'=>$v['OrderProduct']['file_url'],'name'=>$v['ProductI18n']['name']));
						echo $v['OrderProduct']['product_name'];
					}else if(isset($v['Product'])&&!empty($v['Product']['img_thumb'])){
							echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'img'=>$v['Product']['img_thumb'],'name'=>$v['ProductI18n']['name']));
					}else if(isset($v['Product'])){
                        			echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'img'=>'/theme/default/images/default.png','name'=>$v['ProductI18n']['name']));
                    } ?>
				</td>
				<td class="productname" style="max-width:300px;vertical-align:top;padding-left:10px;" align="left">
					<a class="am-text-truncate" href="<?php echo $html->url('/orders/product_view/'.$v['OrderProduct']['id']); ?>" style="display:block;cursor:pointer; "><?php echo $v['OrderProduct']['product_name'];?></a>
					<span style="display:block;white-space:nowrap"><?php echo $ld['sku'];?>:&nbsp;<span><?php echo $v['OrderProduct']['product_code'] ?></span></span>
					<?php if(isset($v['OrderProduct']['product_attrbute'])&&$v['OrderProduct']['product_attrbute']!=""){?>
					<span style="display:block;"><?php echo $v['OrderProduct']['product_attrbute'];?></span>
					<?php }?>
					<!-- 定制属性信息 -->
				</td>
				<td>
					<!-- 空白 -->
				</td>
                <td style="vertical-align:top;"><?php if($v['OrderProduct']['lease_type']=="L"){echo "租赁天数:".$v['OrderProduct']['lease_unit']."  租赁价:";} echo $svshow->price_format(isset($v['Product'])?($v['Product']['shop_price']-$v['OrderProduct']['adjust_fee']):$v['OrderProduct']['product_price'],$configs['price_format']);?></td>
				<?php if(isset($configs['point-use-status']) && $configs['point-use-status']==1){?>
				<td style="vertical-align:top;"><?php echo $v['Product']['point']?></td>
				<?php }?>
				<td style="vertical-align:top;"><?php echo $v['OrderProduct']['product_quntity']?></td>
				<?php if(isset($order_info['Order']['status'])&&$order_info['Order']['status']==1&&isset($order_info['Order']['shipping_status'])&&$order_info['Order']['shipping_status']==2){?>
				<td><a class="am-btn am-btn-warning am-seevia-btn-add am-btn-xs am-radius am-margin-left-sm" href="<?php echo $html->url('/products/'.$v['OrderProduct']['product_id']);?>#Commodity_review">评论</a></td>
				<?php }?>
			</tr>
			<tr>
				<td colspan="4" style="border:none;padding-top:0;background:#fff;">
					<!-- 定制属性信息 -->
					<?php if(isset($order_product_value[$v['OrderProduct']['id']])&&!empty($order_product_value[$v['OrderProduct']['id']])){$total_attr_price=0; ?>
					<div class='cart_product_value' style="font-size:14px;">
						<p style="border-bottom:1px solid #ccc;margin-bottom:0.5rem;font-size:16px;padding-bottom:3px;">定制属性:</p>
						<ul class="am-avg-sm-1 am-avg-md-1 am-avg-lg-2" id="customized_border">
						<?php  foreach($order_product_value[$v['OrderProduct']['id']] as $cpk=>$cpv){ ?>
						<li style="height:25px;line-height:25px;padding:0 10px;">
						<div class="am-cf" style="font-size:15px;">
						  <span class="am-fl">
							<span class="name"><?php echo $all_attr_list[$cpv['OrderProductValue']['attribute_id']]; ?>:&nbsp;&nbsp;</span><?php echo $cpv['OrderProductValue']['attribute_value'] ?>
						  </span>
						  <span class="am-fr">
						    <?php if(isset($cpv['OrderProductValue']['attr_price'])&&!empty($cpv['OrderProductValue']['attr_price'])&&intval($cpv['OrderProductValue']['attr_price'])>0 ){echo $svshow->price_format($cpv['OrderProductValue']['attr_price'],$configs['price_format']);}$total_attr_price+=$cpv['OrderProductValue']['attr_price']; ?>
						  </span>
						</div>
						</li>
						<?php  } ?>
						</ul>
					</div>
					<div class='cart_product_note' style="font-size:16px;">
						<div><span><?php echo $ld['remark'] ?>:&nbsp;&nbsp;</span><?php echo $v['OrderProduct']['note'] ?></div>
					</div>
					<div>
						<span class="am-fl">定制金额:</span>
						<span class="am-fr" style="padding-right:10px;color:#f60;font-weight:600">
						  <?php echo $svshow->price_format($total_attr_price,$configs['price_format']); ?>
						</span>
					</div>
					<?php } ?>
				<!-- 定制属性end -->
				</td>
			</tr>
			<!--循环套装商品-->
			<?php if(isset($order_package_products[$v['OrderProduct']['product_id']])&&sizeof($order_package_products[$v['OrderProduct']['product_id']])>0){foreach($order_package_products[$v['OrderProduct']['product_id']] as $pk=>$pv){?>
			<tr>
				<td class="productname" align="right" style="padding:0.5rem 0 0.5rem 30px;"><?php if(!empty($pv['OrderProduct']['file_url'])){
						echo $svshow->seo_link(array('type'=>'P','id'=>$pv['Product']['id'],'img'=>$pv['OrderProduct']['file_url'],'name'=>$pv['ProductI18n']['name']));
					}else if(isset($pv['Product']['img_thumb'])){
						echo $svshow->seo_link(array('type'=>'P','id'=>$pv['Product']['id'],'img'=>$pv['Product']['img_thumb'],'name'=>$pv['ProductI18n']['name']));
					}?>
				</td>
				<td style="vertical-align:top;max-width:300px;padding-left:10px;">
					<span style="display:block;" class="am-text-truncate"><?php echo $pv['OrderProduct']['product_name']?></span>
					<span style="display:block;white-space:nowrap"><?php echo $ld['sku'];?>:&nbsp;<span><?php echo $pv['OrderProduct']['product_code'] ?></span></span>
					<?php if(isset($pv['OrderProduct']['product_attrbute'])&&$pv['OrderProduct']['product_attrbute']!=""){?>
						<span style="display:block;"><?php echo $pv['OrderProduct']['product_attrbute'];?> </span>
					<?php }?>
				</td>
				<td>
					<!-- 空白 -->
				</td>
				<td ><span><?php echo $svshow->price_format(($pv['Product']['shop_price']-$pv['OrderProduct']['adjust_fee']),$configs['price_format']);?></span></td>
				<td ><?php echo $pv['OrderProduct']['product_quntity']?></td>
				<?php if(isset($order_info['Order']['status'])&&$order_info['Order']['status']==1&&isset($order_info['Order']['shipping_status'])&&$order_info['Order']['shipping_status']==2){?>
				<td ><a class="am-btn am-btn-warning am-seevia-btn-add am-btn-xs am-radius am-margin-left-sm" href="<?php echo $html->url('/products/'.$v['OrderProduct']['product_id']);?>#Commodity_review">评论</a></td>
				<?php }?>
			</tr>	
			<?php }}?>
			<?php }?>
			</table>
			<div class="moneyinfo">
				<hr style="border-width:2px;" />
				<div>
					<div class='am-form-group'>
						<label class='am-u-lg-8 am-u-md-8 am-u-sm-6 am-text-right'><?php echo $ld['order_products_total']?></label>
						<div class='am-u-lg-4 am-u-md-4 am-u-sm-6 am-text-right'><?php echo $svshow->price_format($order_info['Order']['subtotal'],$configs['price_format']);?></div>
						<div class='am-cf'></div>
					</div>
					<?php if($order_info['Order']['lease_type']=='L'&&$order_info['Order']['insure_fee']>0){ ?>
					<div class='am-form-group'>
						<label class='am-u-lg-8 am-u-md-8 am-u-sm-6 am-text-right'><?php echo $ld['lease_deposit']?></label>
						<div class='am-u-lg-4 am-u-md-4 am-u-sm-6 am-text-right'><?php echo $svshow->price_format($order_info['Order']['insure_fee'],$configs['price_format']);?></div>
						<div class='am-cf'></div>
					</div>
					<?php } ?>
					<?php if($order_info['Order']['service_type']!='virtual'){ ?>
					<div class='am-form-group'>
						<label class='am-u-lg-8 am-u-md-8 am-u-sm-6 am-text-right'><?php echo $ld['shipping']?></label>
						<div class='am-u-lg-4 am-u-md-4 am-u-sm-6 am-text-right'><?php if(isset($order_info['Order']['shipping_fee']) && $order_info['Order']['shipping_fee']!=0){echo $svshow->price_format($order_info['Order']['shipping_fee'],$configs['price_format']);}else{echo $ld['free_shipping']; }?></div>
						<div class='am-cf'></div>
					</div>
					<?php } ?>
					<?php if($order_info['Order']['payment_fee']!=0){?>
					<div class='am-form-group'>
						<label class='am-u-lg-8 am-u-md-8 am-u-sm-6 am-text-right'><?php echo $ld['flat_rate']?></label>
						<div class='am-u-lg-4 am-u-md-4 am-u-sm-6 am-text-right'><?php if(!empty($order_info['Order']['payment_fee']) && $order_info['Order']['payment_fee']!=0.00){echo $svshow->price_format($order_info['Order']['payment_fee'],$configs['price_format']);}else{echo $svshow->price_format('0.00',$configs['price_format']);}?></div>
						<div class='am-cf'></div>
					</div>
					<?php } ?>
                        		<?php if($order_info['Order']['total']!=0){?>
                        		<!-- 订单总金额 -->
                        		<div class='am-form-group'>
						<label class='am-u-lg-8 am-u-md-8 am-u-sm-6 am-text-right'><b><?php echo $ld['order_total']?></b></label>
						<div class='am-u-lg-4 am-u-md-4 am-u-sm-6 am-text-right'><b><?php echo $svshow->price_format($order_info['Order']['total'],$configs['price_format']);?></b></div>
						<div class='am-cf'></div>
					</div>
				 	<?php }?>
					<?php if($order_info['Order']['point_fee']!=0){?>
					<div class='am-form-group'>
						<label class='am-u-lg-8 am-u-md-8 am-u-sm-6 am-text-right'><?php echo $ld['points_to_amount']?></label>
						<div class='am-u-lg-4 am-u-md-4 am-u-sm-6 am-text-right'><?php echo $svshow->price_format($order_info['Order']['point_fee'],$configs['price_format']);?></div>
						<div class='am-cf'></div>
					</div>
				 	<?php }?>
				 	<?php if($order_info['Order']['coupon_fee']!=0){?>
					<div class='am-form-group'>
						<label class='am-u-lg-8 am-u-md-8 am-u-sm-6 am-text-right'><?php echo $ld['use_red_envelopes']?></label>
					  	<div class='am-u-lg-4 am-u-md-4 am-u-sm-6 am-text-right'><?php if(!empty($order_info['Order']['coupon_fee']) && $order_info['Order']['coupon_fee'] !=0.00){if(isset($order_info['Order']['coupon_id']) && $order_info['Order']['coupon_id']!=""){foreach($coupon_name_arr as $ca){if($ca == ""){continue;}echo  '['.$ca.']';}} ?>:</b><em style="color:red;">-</em><?php echo $svshow->price_format($order_info['Order']['coupon_fee'],$configs['price_format']);}else{echo $svshow->price_format('0.00',$configs['price_format']);} ?></div>
						<div class='am-cf'></div>
					</div>
					<?php } ?>
					 <?php if($order_info['Order']['user_balance']!=0){?>	
					<div class='am-form-group'>
						<label class='am-u-lg-8 am-u-md-8 am-u-sm-6 am-text-right'><?php echo $ld['balance_of_payments']?></label>
						<div class='am-u-lg-4 am-u-md-4 am-u-sm-6 am-text-right'><?php echo $svshow->price_format($order_info['Order']['user_balance'],$configs['price_format']);?></div>
						<div class='am-cf'></div>
					</div>
					<?php }?>
						<!-- 应付金额 -->
					<div class='am-form-group'>
							<?php if($order_info['Order']['payment_status']!=2||(isset($need_pay)&&floatval($need_pay)>0)){ ?>
							<label class='am-u-lg-8 am-u-md-8 am-u-sm-6 am-text-right am-text-danger'><b><?php echo $ld['amount_to_be_paid']?></b></label>
							<div class='am-u-lg-4 am-u-md-4 am-u-sm-6 am-text-right am-text-danger'><?php echo $svshow->price_format($need_pay,$configs['price_format']);?></div>
							<?php }else{ ?>
							<label class='am-u-lg-8 am-u-md-8 am-u-sm-6 am-text-right'><b><?php echo $ld['amount_paid']?></b></label>
							<div class='am-u-lg-4 am-u-md-4 am-u-sm-6 am-text-right'><b><?php echo $svshow->price_format($order_info['Order']['money_paid'],$configs['price_format']);?></b></div>
							<?php } ?>
						<div class='am-cf'></div>
					</div>
				</div>
			
			</div>
		</div>
	</div>
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="wechat_ajax_payaction">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
      <div class="am-text-center"><?php echo $html->image('/theme/default/images/loading.gif');  ?></div>
    </div>
  </div>
</div>

<style type="text/css">
.am-form-detail .am-form-group .radio-btn .am-btn{margin-right:4rem;padding:1rem;background: #FFF;border:2px solid #FFF;box-shadow:none;}
.am-form-detail .am-form-group .radio-btn .am-btn.am-active {background: #FFF;border:2px solid #0e90d2;}
.am-form-detail .am-form-group .radio-btn .am-btn img{width:120px;height:auto;}
.shipping_method .am-panel-bd .am-list li{min-height:auto;padding:3px;}
.orderInfo table{margin-top:1.5rem;}
.orderInfo table th,.orderInfo table td{padding-top:3px;padding-bottom:3px;}
.orderInfo hr{margin:0.5rem 10px;}
</style>
<script type="text/javascript">
var order_product_infos=<?php echo isset($order_products)?json_encode($order_products):'{}';?>;
if(order_product_infos.length>0){
	var server_host="<?php echo $server_host; ?>";
	var order_product_detail=order_product_infos[0];
	var wechat_shareTitle=order_product_detail['OrderProduct']['product_name'];
	var wechat_imgUrl=order_product_detail['Product']['img_thumb'];
	var Expression=/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;
	var objExp=new RegExp(Expression);
	if(!objExp.test(wechat_imgUrl)){
		wechat_imgUrl=server_host+web_base+wechat_imgUrl;
	}
	var wechat_lineLink=server_host+web_base+"/products/view/"+order_product_detail['OrderProduct']['product_id'];
}
var is_wechat="<?php echo $is_wechat; ?>";

function topay(){
    var $radios = $('[name="payment_id"]');
	var pay=$radios.filter(':checked').val();
	if(typeof(pay) == 'undefined'){
		alert("<?php echo $ld['fill_payment_method']?>");
		return false;
	}else{
		var payment_code=$radios.filter(':checked').is(".pay_weixinpay");
		if(payment_code&&is_wechat){
			try{
				wechat_ajax_payaction();
			}catch(Error){
				alert(Error);
				return false;
			}
		}else{
			document.getElementById('payform').submit();
		}
	}
}

$(function () {
var customized_border_length = $("#customized_border li").length;
for(var i = 0;i < customized_border_length;i++){
	if (i%2 == 0) {
	$("#customized_border li").eq(i).css("border-right","1px solid #ccc");
	};
}
})

function order_use_point(checkbox){
	$(checkbox).parents('div.am-input-group').find("input[type='text']").val(0).attr('disabled',!($(checkbox).is(':checked')));
	$(checkbox).parents('div.am-input-group').find("button").attr('disabled',!($(checkbox).is(':checked')));
}

function order_point_pay(btn,order_id){
	var use_point=$(btn).parents('div.am-input-group').find("input[type='text']").val().trim();
	use_point=use_point==''?0:parseInt(use_point);
	var max_use_point=$("#available_points").html().trim();
	max_use_point=max_use_point==''?0:parseInt(max_use_point);
	if(use_point>0&&use_point>max_use_point){
		seevia_alert('最大使用积分:'+max_use_point);
	}else if(use_point>0&&use_point<=max_use_point){
		$.ajax({
			url:web_base+'/orders/order_point_pay',
			type:"POST",
			data:{'order_id':order_id,'use_point':use_point},
			dataType:"json",
			success:function(result){
				if(result.code=='1'){
					seevia_alert_func(result.message,function(){
						window.location.reload();
					});
				}else{
					seevia_alert(result.message);
				}
			}
		});
	}
}
</script>