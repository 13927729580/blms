<div class="am-cf am-container am-cart-checkout" style="padding-left:5px;">
	<!-- 收货人 -->
		<div class="am-panel am-panel-default ">
		<div class="am-panel-hd" ><strong class="am-text-primary am-text-md"><?php echo $ld['contact_information']?></strong>
			<?php echo $html->link($ld['modify'],"/carts/check_shipping/",array('class'=>'am-btn am-btn-secondary am-btn-sm am-fr','style'=>'padding:5px 10px;position:relative;top:-1px;'));?>
		</div>
		<div class="am-panel-bd" style="font-size:1.4rem">
			<div class="am-g am-g-collapse">
			<div class="am-u-sm-6 am-u-md-6 am-u-lg-2" ><?php echo $ld['consignee']?>:<?php if(isset($_SESSION['checkout']['address']['consignee']))echo $_SESSION['checkout']['address']['consignee'];?></div>
			<!-- 手机 -->
			<?php if(isset($_SESSION['checkout']['address']['mobile']) && $_SESSION['checkout']['address']['mobile']!=""){ ?>
			<div class="am-u-sm-6 am-u-md-6 am-u-lg-2" ><?php echo $_SESSION['checkout']['address']['mobile'];?></div>
			<?php } ?>
				<!-- 电话 -->
			<?php if(isset($_SESSION['checkout']['address']['telephone']) && $_SESSION['checkout']['address']['telephone']!=""){ ?>
			<div class="am-u-sm-12 am-u-md-12 am-u-lg-2" >
				<?php echo $ld['telephone']?>:<?php echo $_SESSION['checkout']['address']['telephone'];?>
			</div>
			<?php }?>
			<!-- 地址等 -->
			<?php if(!isset($_SESSION['checkout']['shipping'])||(isset($_SESSION['checkout']['shipping']['shipping_code'])&&$_SESSION['checkout']['shipping']['shipping_code']!='cac')){ ?>
			<div class="am-u-sm-12 am-u-md-12 am-u-lg-12" >
				<span><?php echo $ld['delivery_address']?>:&nbsp;</span>
				<span>
				<?php if(isset($_SESSION['checkout']['address']['regionI18n']))echo $_SESSION['checkout']['address']['regionI18n'];?><?php if(isset($_SESSION['checkout']['address']['address']))echo $_SESSION['checkout']['address']['address'];?></span>
			</div>
			<!-- 标志建筑 -->
			<?php  if(isset($_SESSION['checkout']['address']['sign_building'])&&!empty($_SESSION['checkout']['address']['sign_building'])){?>
			<div class="am-u-sm-12 am-u-md-6 am-u-lg-4" >
				<?php echo $ld['address_to']?>:<?php if(isset($_SESSION['checkout']['address']['sign_building']))echo $_SESSION['checkout']['address']['sign_building'];?>
			</div>
			<?php }?>
			<!-- 邮编 -->
			 <?php  if(isset($_SESSION['checkout']['address']['zipcode'])&&!empty($_SESSION['checkout']['address']['zipcode'])){?>
			<div class="am-u-sm-12 am-u-md-6 am-u-lg-8" >
				<?php echo $ld['zip']?>:<?php echo $_SESSION['checkout']['address']['zipcode'];?>
			</div>
			<?php }?>
			<?php }?>
		
			</div>
			<ul class="am-list am-list-static" style="margin:10px 0 0 0;">
				<li style="border-bottom:0;margin-bottom:0;padding:0;height:30px;line-height:30px;"><?php echo $_SESSION['checkout']['shipping']['shipping_name'];?></li>
					<li style="padding-top:0;padding-left:0;padding-bottom:0;line-height:20px;border:none;height:auto">
					<?php echo !empty($_SESSION['checkout']['shipping']

['shipping_description'])?trim($_SESSION['checkout']['shipping']['shipping_description']):'';?>
				</li>
				<?php if(isset($_SESSION['checkout']['shipping']['shipping_code'])&&$_SESSION['checkout']['shipping']['shipping_code']=='cac'){?>
				<li style="padding:0;border-bottom:0;padding:0;height:30px;line-height:30px;"><?php echo $_SESSION['checkout']['shipping']['shipping_description'];?></li>
				<li style="padding-top:0;padding-left:0;padding-bottom:0;line-height:20px;border:none;height:auto">
					<?php echo !empty($_SESSION['checkout']['shipping']

['shipping_description'])?trim($_SESSION['checkout']['shipping']['shipping_description']):'';?>
				</li>
				<?php }?>

				<?php if(isset($_SESSION['checkout']['remark'])&&$_SESSION['checkout']['remark']!=""){?>
				<li style="border-bottom:0;padding:0;height:30px;line-height:30px;"><?php echo $ld['remarks']?>:&nbsp;<?php echo $_SESSION['checkout']['remark'];?>
				</li>
				<li style="padding-top:0;padding-left:0;padding-bottom:0;line-height:20px;border:none;height:auto">
					<?php echo !empty($_SESSION['checkout']['shipping']

['shipping_description'])?trim($_SESSION['checkout']['shipping']['shipping_description']):'';?>
				</li>
				<?php }?>
			</ul>
		</div>
	</div>
	<?php echo $form->create('carts',array('action'=>'/done','name'=>'cart_info','type'=>'POST','class'=>'am-form  am-form-horizontal'));?>
			<!-- 商品清单sm -->
	<div class="am-panel am-panel-default am-hide">
		<div class="am-panel-hd"><strong class="am-text-primary am-text-md"><?php echo $ld['product_list']?></strong>
			<?php echo $svshow->link($ld["back_to_the_shopping_cart"],'/carts',array('class'=>'am-fr am-btn am-btn-secondary am-btn-sm','style'=>'padding:5px 10px;position:relative;top:-2px;'));?>
		</div>
		<div class="am-panel-bd">
			<table class="am-table admin-content-table pro_list">
				<thead>
					<tr>
						<th colspan="2"><?php echo $ld['product_name']?></th>
						<th style="width:17%;"><?php echo $ld['quantity']?></th>
					</tr>
				</thead>
				<tbody>
				<?php
					$sum_point = 0;$sum_discount=0;$key_munber = 0;
					foreach($checkout['products'] as $k=>$v){
						$total_attr_price=0;$key_munber+=1;
						$sum_point+=$v['Product']['point']*$v['quantity'];
						if(!empty($v['Product']['market_price'])&&$v['Product']['market_price']>$v['Product']['shop_price']){
							$sum_discount+=(($v['Product']['market_price']-$v['Product']['shop_price'])*$v['quantity']);
						}
				?>
					<tr class="<?php if ($key_munber == 1) {
						echo "am-hide" ;
					} ?>" >
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
					<tr>
						<td style="width:13%;padding-left:0;text-align:left" rowspan="2" align="center" >
					<?php echo $html->image(!empty($v['Product']['img_thumb'])?$v['Product']['img_thumb']:'theme/default/images/default.png',array('style'=>'width:70px;padding-right:.7em'));  ?></td>
						<td style="padding:0;width:70%"><?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'name'=>$v['ProductI18n']['name']));?></td>
						<td align="center" style="width:17%;text-align:center"><?php echo $v['quantity']?></td>
					</tr>

					<tr>		
						<td class="checkoutprice am-checkprice" colspan="2" align="left" style="padding-left:0"><?php
						if ($v['Product']['is_lease'] !== 1) {
							if(isset($v['is_promotion'])&&$v['is_promotion']==1){
								echo $svshow->price_format($v['Product']['promotion_price'],$configs['price_format']);
								if($v['Product']['market_price']!=$v['Product']['promotion_price']){
									echo "<span>&nbsp;(<del>".$svshow->price_format($v['Product']['market_price'],$configs['price_format'])."</del>)</span>";
								}
							}else{
								echo $svshow->price_format($v['Product']['shop_price'],$configs['price_format']);
								if($v['Product']['market_price']!=$v['Product']['shop_price']){
									echo "<span>&nbsp;(<del>".$svshow->price_format($v['Product']['market_price'],$configs['price_format'])."</del>)</span>";
								}
							}}?>
					<?php if($v['Product']['is_lease'] == 1){
						echo $ld['lease_days'].":".$v['Product']['lease_day']."<br>  ".$ld['lease_price'].":".$svshow->price_format($v['Product']['lease_price'],$configs['price_format']);
					}?>
						</td>
					</tr>



					<?php if(isset($v['attributes'])&&$v['attributes']!=""){?>
					<tr>
					  <td style="border:none"></td>
					  <td style="border:none;padding-left:0" colspan="2" ><?php echo $v['attributes'];?>
						
						<!-- 定制属性信息 -->
					  
					</tr>
					<?php if($total_attr_price!=0){?>
					<tr>
					  <td style="border:none;"></td>
					  <td style="font-size:14px;border:none;" colspan="2">
						<span class="am-fl">定制金额:</span>
						<span class="am-fr">
						  <?php echo $svshow->price_format($total_attr_price,$configs['price_format']); ?>
						</span>
					  </td>
					</tr>
					<?php }?>
					<?php }?>
					<tr>
						<td style="border:none"></td>
						<td colspan="3" class="checkoutPrice" align="left" style="border-top:0px;padding-left:0"><?php
							if(isset($v['is_promotion'])&&$v['is_promotion']==1){
							if(($v['Product']['market_price']-$v['Product']['promotion_price'])>0){
							?>
								<span><?php echo $ld['offer']?>:</span>
							<?php
								echo $svshow->price_format($v['Product']['market_price']-$v['Product']['promotion_price'],$configs['price_format']);
							}
							}else{
							if(($v['Product']['market_price']-$v['Product']['shop_price'])>0){ ?>
								<span><?php echo $ld['offer']?>:</span>
							<?php
								echo $svshow->price_format($v['Product']['market_price']-$v['Product']['shop_price'],$configs['price_format']);
							}} ?>
						<?php if($v['Product']['point']!=0){ ?>
							<span><?php echo $ld['single-product_integration']?></span>:<span><?php echo $v['Product']['point']; ?></span><br />
						<?php }else if(isset($v['subtotal'])){ ?>
							<span><?php echo $ld['subtotal']?>:</span><span><?php echo $svshow->price_format($v['subtotal'],$configs['price_format']);?></span>
						<?php }?>
						</td>
					</tr>
					
					<!-- 套装商品 -->
						<?php if (isset($v['PackageProduct'])&&sizeof($v['PackageProduct'])>0) {
							$PackageProduct_total=isset($v['PackageProduct_total'])?$v['PackageProduct_total']:0;
							$PackageProduct_proportion=$v['Product']['shop_price']/$PackageProduct_total;
							$PackageProduct_sutotal=0;
	                    			foreach ($v['PackageProduct'] as $kk => $vv) {
                    					if($kk<sizeof($v['PackageProduct'])-1){
	                    					$PackageProduct_price=$PackageProduct_proportion*$vv['Product']['shop_price'];
	                    					$PackageProduct_price = number_format($PackageProduct_price, 2, '.', ' '); //套装单品价格
	                    					$PackageProduct_sutotal+=$PackageProduct_price;
                    					}else{
                    						$PackageProduct_price=$v['Product']['shop_price']-$PackageProduct_sutotal;
                    					} ?>
					<tr>
					<td style="width:13%;padding-left:0;text-align:right" rowspan="2" align="center">	
							<img src="<?php echo !empty($vv['Product']['img_thumb'])?$vv['Product']['img_thumb']:'/theme/default/images/default.png' ?>" style="max-width:90px;height:auto;width:70%" alt="">
					</td>	
					<td style="padding:0;width:70%;padding-left:10px;">
						<a class="am-text-truncate" href="<?php echo $html->url('/products/'.$vv['Product']['id']) ?>" style="display:block;font-size:14px;color:#666"><?php echo $vv['PackageProduct']['package_product_name'] ?></a>
					</td>
					<th align="center" style="width:17%;text-align:center">
							<?php echo $vv['PackageProduct']['package_product_qty']?>
					</th>
					</tr>
					<tr>
						<td class="am-checkprice" colspan="2" align="left" style="padding-left:10px;">
							<?php echo $svshow->price_format($PackageProduct_price,$configs['price_format']); ?>
						</td>	
					</tr>
						
				
				
				
				

				
						
						<?php }} ?>
					

					<!-- 套装商品end -->

					<?php if(isset($checkout['Product_by_Promotion']) && sizeof($checkout['Product_by_Promotion'])>0) { foreach($checkout['Product_by_Promotion'] as $promotion_id=>$pro){foreach($pro as $i=>$p) {?>
						<tr>
							<td></td>
							<td><?php if(isset($p['ProductI18n']['name'])) {?>
										<?php 	$p_name = $p['ProductI18n']['name'];
										if(isset($p['attributes']) && $p['attributes'] != "") {
											$p_name .= " (".$p['attributes']." )";
										}?>
										<?php echo $html->link($p_name,$svshow->sku_product_link($i,$p['ProductI18n']['name'],$p['Product']['code'],$configs['use_sku']),array("target"=>"_blank"),false,false);?><font style="color:red;">【<?php echo $checkout['promotion'][$promotion_id]['title']?>】</font>
										<?php }?></td>
							<td align="center">1</td>
						</tr>
						<tr>
							<td colspan="3" align="left">
								<label><?php echo $ld['price']?></label>:<span><?php echo $svshow->price_format($p['Product']['market_price'],$configs['price_format']);?></span>
								<label><?php echo $ld['subtotal']?></label><span><?php $svshow->price_format($p['Product']['now_fee'],$configs['price_format']); ?></span>
							</td>
						</tr>
				<?php 
								}
							}
						} 
					}
				?>

				</tbody>

			</table>
		</div>
	</div>	

		<!-- 商品清单lg -->
	<div class="am-panel am-panel-default am-hide-sm-only">
		<div class="am-panel-hd"><strong class="am-text-primary am-text-md"><?php echo $ld['product_list']?></strong>
			<?php echo $svshow->link($ld["back_to_the_shopping_cart"],'/carts',array('class'=>'am-fr am-btn am-btn-secondary am-btn-sm','style'=>'position:relative;top:-3px;'));?>
		</div>
	</div>
	<div class="am-panel-bd">
			<div class="pro_list">
				<div class="am-g">
				<div class="am-u-sm-12" style="border-bottom:2px solid #ddd">
					
						<div class="am-u-sm-8 am-u-lg-7 am-u-md-7" style="line-height:28px;"><?php echo $ld['product_name']?></div>

						<div class="am-u-sm-4 am-u-lg-2 am-u-md-2" style="line-height:28px;"><?php echo $ld['quantity']?></div>
						<div class="am-u-lg-3 am-u-md-3 am-hide-sm-only" style="line-height:28px;">小计</div>
				</div>
				</div>
				<?php
					$sum_point = 0;$sum_discount=0;
					foreach($checkout['products'] as $k=>$v){
						$total_attr_price=0;
						$sum_point+=$v['Product']['point']*$v['quantity'];
						if(!empty($v['Product']['market_price'])&&$v['Product']['market_price']>$v['Product']['shop_price']){
							$sum_discount+=(($v['Product']['market_price']-$v['Product']['shop_price'])*$v['quantity']);
						}
				?>
				<div class="am-g" style="border:1px solid #eee;margin-top:10px;padding-bottom:10px;">
				<div class="am-u-sm-12 am-margin-top-xs">
					<div class="am-u-sm-8 am-u-md-7 am-u-lg-7">
						<div class="am-u-sm-4 am-u-lg-4 am-u-md-4 am-padding-0">
							<img src="<?php echo !empty($v['Product']['img_thumb'])?$v['Product']['img_thumb']:'/theme/default/images/default.png' ?>" style="max-width:100px;height:auto;width:100%" alt="">
						</div>
						<div class="am-u-sm-8">
						<div class="am-u-sm-12 am-padding-0">
							<a class="am-text-truncate" href="<?php echo $html->url('/products/'.$v['Product']['id']) ?>" style="display:block;color:#333;"><?php echo $v['ProductI18n']['name'] ?></a>
						</div>

						<div class="am-u-sm-12 am-padding-0">
						<?php if(isset($v['attributes'])&&$v['attributes']!=""){?>	
						<?php echo $v['attributes'];?>
						<?php } ?>
						</div>
						</div>

					</div>
					<div class="am-u-sm-4 am-u-lg-2 am-u-md-2 check_product_number">
						<?php echo $v['quantity']?>
					</div>

					<div class="am-u-sm-12 am-u-lg-3 am-u-md-3 check-price" style="color:#dd514c;font-weight:700;">
						<?php
						if($v['Product']['is_lease']!=1){

							if(isset($v['is_promotion'])&&$v['is_promotion']==1){
								echo $svshow->price_format($v['Product']['promotion_price'],$configs['price_format']);
								if($v['Product']['market_price']!=$v['Product']['promotion_price']){
									echo "<span style='color:#aaa'>&nbsp;(<del>".$svshow->price_format($v['Product']['market_price'],$configs['price_format'])."</del>)</span>";
								}
								echo "<br class='br_remove'>";
							}else{
								echo $svshow->price_format($v['Product']['shop_price'],$configs['price_format']);
								if($v['Product']['market_price']!=$v['Product']['shop_price']){
									echo "<span style='color:#aaa'>&nbsp;(<del>".$svshow->price_format($v['Product']['market_price'],$configs['price_format'])."</del>)</span>";
								}
								echo "<br class='br_remove'>";
							}

						}?>
						
						<?php
							if(isset($v['is_promotion'])&&$v['is_promotion']==1){
							if(($v['Product']['market_price']-$v['Product']['promotion_price'])>0){
							?>
								<span><?php echo $ld['offer']?>:</span>
							<?php
								echo $svshow->price_format($v['Product']['market_price']-$v['Product']['promotion_price'],$configs['price_format']);

							}
							}else{
							if($v['Product']['is_lease']==1){
								echo $ld['lease_days'].":".$v['Product']['lease_day']."<br>  ".$ld['lease_price'].":".$svshow->price_format($v['Product']['lease_price'],$configs['price_format']);
							}else if(($v['Product']['market_price']-$v['Product']['shop_price'])>0){ ?>
								<span><?php echo $ld['offer']?>:</span>
							<?php
								echo $svshow->price_format($v['Product']['market_price']-$v['Product']['shop_price'],$configs['price_format']);
							}} ?>
						<br class='br_remove'>
						<?php if($v['Product']['is_lease']==1){ ?>
							<span><?php echo $ld['subtotal']?>:</span><span><?php echo $svshow->price_format($v['quantity']*$v['Product']['lease_price'],$configs['price_format']);?></span>
						<?php } ?>
						
					</div>
				</div>
				<!-- 定制属性 -->
				<div class="am-u-sm-12" style="padding:5px;">
					<div class="am-u-sm-9">
					<?php if(isset($v['CartProductValue'])&&!empty($v['CartProductValue'])){ ?>
						<div class='cart_product_value' style="font-size:14px;">
						  <p style="border-bottom:1px solid #ccc;margin-bottom:0.5rem;font-size:16px;line-height:25px;">定制属性:</p>
						  <ul class="am-avg-sm-1 am-avg-md-1 am-avg-lg-2" id="checkout_customized">
						  <?php foreach($v['CartProductValue'] as $cpk=>$cpv){ ?>
						  <li style="padding:0 10px;height:25px;line-height:25px;">
							<div style="clear:both;font-size:15px;">
							  <span class="am-fl">
								<span class="name"><?php echo $all_attr_list[$cpv['attribute_id']]; ?>:&nbsp;&nbsp;</span><?php echo $cpv['attribute_value'] ?>
							  </span>
							  <span class="am-fr">
							    <?php if(isset($cpv['attr_price'])&&!empty($cpv['attr_price'])&&intval($cpv['attr_price'])>0 ){echo $svshow->price_format($cpv['attr_price'],$configs['price_format']);}$total_attr_price+=$cpv['attr_price']; ?>
							  </span>
							</div>
						  </li>
						  <?php } ?>
						  </ul>
						</div>
						<div class='cart_product_note' style="font-size:16px;clear:both;">
						  <div><span><?php echo $ld['remark'] ?>:&nbsp;&nbsp;</span><?php echo $v['note'] ?></div>
						</div>
						<?php } ?>
						<!-- 定制属性信息 -->
						<?php if($total_attr_price!=0){?>
						<div class="am-g">
						<span class="am-fl">定制金额:</span>
						<span class="am-fr" style="padding-right:10px;color:#dd514c;font-weight:600">
						  <?php echo $svshow->price_format($total_attr_price,$configs['price_format']); ?>
						</span>
						</div>
						<?php } ?>
						</div>
				</div>
				<?php if (isset($v['PackageProduct'])&&sizeof($v['PackageProduct'])>0) {
						$PackageProduct_total=isset($v['PackageProduct_total'])?$v['PackageProduct_total']:0;
						$PackageProduct_proportion=$v['Product']['shop_price']/$PackageProduct_total;
						$PackageProduct_sutotal=0;
                    			foreach ($v['PackageProduct'] as $kk => $vv) {
                    					if($kk<sizeof($v['PackageProduct'])-1){
	                    					$PackageProduct_price=$PackageProduct_proportion*$vv['Product']['shop_price'];
	                    					$PackageProduct_price = number_format($PackageProduct_price, 2, '.', ' '); //套装单品价格
	                    					$PackageProduct_sutotal+=$PackageProduct_price;
                    					}else{
                    						$PackageProduct_price=$v['Product']['shop_price']-$PackageProduct_sutotal;
                    					} ?>
				<div class="am-u-sm-12 am-margin-top-xs">
				<div class="am-u-sm-8 am-u-md-7 am-u-lg-7">
						<div class="am-u-sm-4 am-u-lg-4 am-u-md-4 am-text-right am-padding-0">
							<img src="<?php echo !empty($vv['Product']['img_thumb'])?$vv['Product']['img_thumb']:'/theme/default/images/default.png' ?>" style="max-width:90px;height:auto;width:80%" alt="">
						</div>
						<div class="am-u-sm-8">
						<a class="am-text-truncate" href="<?php echo $html->url('/products/'.$vv['Product']['id']) ?>" style="display:block;font-size:14px;color:#666"><?php echo $vv['PackageProduct']['package_product_name'] ?></a>
						</div>
				</div>
				<div class="am-u-sm-4 am-u-lg-2 am-u-md-2 check_number">
						<?php echo $vv['PackageProduct']['package_product_qty']?>
				</div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-12 check-price" style="">
				<?php echo $svshow->price_format($PackageProduct_price,$configs['price_format']); ?>
				</div>
				</div>
				<?php }} ?>
				</div>
				<?php } ?>
			</div>
	</div>
		<!-- 付款方式 -->
<input type="hidden" name="shipping_id" value="<?php echo $_SESSION['checkout']['shipping']['shipping_id'];?>">
	<input type="hidden" name="free_subtotal" value="<?php echo $_SESSION['checkout']['shipping']['free_subtotal'];?>">
	<input type="hidden" name="shipping_fee" value="<?php echo $_SESSION['checkout']['shipping']['shipping_fee'];?>">
	<div class="am-panel am-panel-default payment_method">
		<div class="am-panel-hd"><strong class="am-text-primary am-text-md"><?php echo $ld['payment_method']?></strong>
			<?php if(isset($_SESSION['payment_tp']) && !empty($_SESSION['payment_tp'])||isset($payment_length)&&sizeof($payment_length)<=1) echo "";else echo $html->link($ld['modify'],"/carts/check_payment",array('class'=>'am-btn am-btn-secondary am-btn-sm','style'=>"float:right;padding:5px 10px;"));?>
		</div>
		<div class="am-panel-bd" style="padding-top:0">
			<ul class="am-list am-list-static">
				<?php if(!empty($_SESSION['checkout']['payment']['payment_id'])){ ?>
				<li style="margin-bottom:0;padding-left:0"><?php echo $_SESSION['checkout']['payment']['payment_name'];?><?php echo isset($_SESSION['checkout']['payment']['sub_pay'])&&!empty($_SESSION['checkout']['payment']['sub_pay'])?"&nbsp;-&nbsp;".$_SESSION['checkout']['payment']['sub_pay']['PaymentI18n']['name']:'';?>
				<?php if(!empty($_SESSION['checkout']['payment']['payment_fee'])){?>
				<span style="margin-left:20px;"><?php echo $ld['flat_rate']." ".$svshow->price_format($_SESSION['checkout']['payment']['payment_fee'],$configs['price_format']);?></span>
				<?php }?>
				</li>
				<?php if($_SESSION['checkout']['payment']['code']=='AuthorizeNet_AIM'){?>
				<li><?php if(isset($aim_card)){ ?><span style="padding-right:5px;"><?php echo "Card Number" ?></span><span>:&emsp;<?php echo $aim_card;?></span><?php } ?></li>
				<li><span style="padding-right:5px;"><?php echo "Card Name";?></span><span>:&emsp;<?php echo $_SESSION['checkout']['payment']['card_name'];?></span></li>
				<?php } ?>
				<?php }else if($checkout['cart_info']['total']<=0){ ?>
				<li>无需支付</li>
				<?php }else{ ?>
				<li>请选择支付方式</li>
				<?php } ?>
			</ul>
		</div>
	</div>
			<!-- 付款方式 -->
			<!-- 积分 -->
<?php
		$point_use_status=false;//积分是否可用
		if(isset($configs['use_point'])&&$configs['use_point']=='1'){
			$points_occasions=isset($configs['points_occasions'])?$configs['points_occasions']:'';//积分使用场合
			if(in_array($points_occasions,array('0','2'))&&isset($_SESSION['checkout']['cart_info']['sum_quantity'])&&$_SESSION['checkout']['cart_info']['sum_quantity']!='0'){//购物可使用积分
				$point_use_status=true;
			}else if(in_array($points_occasions,array('0','3'))&&isset($_SESSION['checkout']['cart_info']['lease_quantity'])&&$_SESSION['checkout']['cart_info']['lease_quantity']!='0'){//租赁可使用积分
				$point_use_status=true;
			}
		}
	if($point_use_status){ ?>
	<input type='hidden' name='point_u' id='point_u' value="<?php echo $configs['point-equal'];?>">
	<input type='hidden' name='point_del' id='point_del' value="<?php echo isset($checkout['point']['fee']) ?$checkout['point']['fee']:'0'; ?>">
	<div class="am-panel am-panel-default">
		<div class="am-panel-hd">
			<input type="checkbox" class="use_offer" id="cart_point" <?php echo (isset($checkout['point']['fee']) && $checkout['point']['fee']>0)||empty($_SESSION['checkout']['payment']['payment_id'])?'checked':''; ?> /><strong class="am-text-primary am-text-md"><?php echo $ld['use_points']?></strong>&nbsp;&nbsp;<span><span style="color:#999"><?php echo  $ld['your_points'];?></span><?php echo $user_info['User']['point'];?>&nbsp;<span style="color:#999">,&nbsp;<?php echo $ld['available_points']; ?>:</span><span id="available_points"><?php echo isset($checkout['point']['can_use_point'])&&$checkout['point']['can_use_point']<$user_info['User']['point']?$checkout['point']['can_use_point']:$user_info['User']['point']; ?></span>&nbsp;<span style="color:#999">,</span>&nbsp;<?php echo sprintf($ld['points_gifts_money'],$configs['point-equal'],$svshow->price_format("1",$configs['price_format']));?></span></div>
		<div class="am-panel-bd <?php echo (isset($checkout['point']['fee']) && $checkout['point']['fee']>0)||empty($_SESSION['checkout']['payment']['payment_id'])?'':'am-hide'; ?>">
            <a href="javascript:void(0);" class="am-hide discount_clear" onclick="clear_point()"></a>
			<div class="am-form-detail" >
				 <div class="am-form-group" style="margin-bottom:0px;">
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-12 am-padding-top-xs am-padding-left-0" >
						<input type="text" name="use_point" id="use_point"  value="<?php echo isset($checkout['point'])?$checkout['point']['point']:0; ?>" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" onblur="javascript:usepoint(<?php echo $user_info['User']['point']?>,<?php echo $checkout['cart_info']['total']?>,j_points);" />
					</div>
					<div class="am-u-lg-10 am-u-md-9 am-u-sm-12 am-form-label" style="text-align:left;padding-left:2px;padding-top:0;line-height:39px;">
						<div id='point_li' <?php if(!isset($checkout['point']['fee']) || $checkout['point']['fee']==0){ echo 'style="display:none;"';}?>>
					<div class="mname"><p><span style="color:#999"><?php echo $ld['points_to_amount']?>:</span><i id='del'><?php $checkout['point']['fee'] = isset($checkout['point']['fee'])?$checkout['point']['fee']:0; echo $svshow->price_format($checkout['point']['fee'],$configs['price_format']);?></i></p></div>
						</div>
					</div>
				 </div>
				 <div class="am-form-group" style="margin-bottom:0px;">
				 </div>
			</div>
		</div>
	</div>
	<?php if(empty($_SESSION['checkout']['payment']['payment_id'])){ ?>
	<script type='text/javascript'>
		$(function(){
			document.getElementById('use_point').value=$("#available_points").html();
			usepoint(<?php echo $user_info['User']['point']?>,<?php echo $checkout['cart_info']['total']?>,j_points);
		});
	</script>
	<?php } ?>
	<?php } ?>
	<!-- 积分 -->
<?php 
		if(isset($configs['enable_bonus'])&&$configs['enable_bonus']=='1'){
			$use_num = isset($configs['coupons-usenum'])?$configs['coupons-usenum']:0;
?>
<!-- 优惠券 -->
	<div class="am-panel am-panel-default">
		<div class="am-panel-hd"><input id="jifen1" type="checkbox" class="use_offer" <?php echo isset($checkout['coupon']) && sizeof($checkout['coupon'])>0?'checked':''; ?>/><strong class="am-text-primary am-text-md"><?php echo $ld['rebate_084']?></strong></div>
		<div id="jifen2" class="am-panel-bd am-form am-form-horizontal <?php echo isset($checkout['coupon']) && sizeof($checkout['coupon'])>0?'':'am-hide'; ?>">
            <a href="javascript:void(0);" class="am-hide discount_clear" onclick="clear_coupon()"></a>
			<input type="hidden" name="coupon_use_num" id="coupon_use_num" value="<?php echo $use_num;?>">
			<div class="am-form-detail" id="coupon_list">
					<?php 
		if(isset($checkout['coupon']) && sizeof($checkout['coupon']) >0){ $i=0;foreach($checkout['coupon'] as $sk=>$sc){?>
					<div class="am-form-group coupon_list">
						<div class="am-fl"><?php echo $ld['rebate_061']; ?>:&nbsp;</div>
						<div class="am-fl"><?php echo $sk; ?><input type="hidden" name='coupon' value="<?php echo $sk; ?>" id="coupon_value<?php echo $sk; ?>" ><span onclick="remove_coupon_value('coupon_value<?php echo $sk; ?>')" class="coupon_remove" style="color:red;cursor: pointer;margin-left:0.7rem;">X</span></div>
						<div class="am-text-left am-fl">&nbsp;&nbsp;&nbsp;<span style="color:#999"><?php echo $ld['deductible_money'].":"; ?></span><i><?php echo $svshow->price_format($sc['fee'],$configs['price_format']); ?></i></div>
					</div>
					<?php $i++;}} ?>
					<div class="am-form-group add_coupon" style="margin:0px;">
						<input style="max-width:150px;float:left;margin-top:10px;margin-bottom:10px;" type="text" name="coupon" id="coupon_value" value="" />
						<input style="float:left;height:39px;margin-top:10px;" type="button" class="am-btn am-btn-secondary am-btn-sm" value="<?php echo $ld['ok'] ?>" onclick="check_coupon_value('coupon_value')" />
					</div>
			</div>
			<?php if((isset($checkout['coupon']) && sizeof($checkout['coupon'])<$use_num&&$use_num>1) ||(!isset($checkout['coupon']) && $use_num!=1)){?>
			<div class="am-form-detail">
				<div class="am-form-group"><div class="am-u-lg-5 am-u-md-5 am-u-sm-5"><?php echo $html->link($ld['add'],"javascript:add_coupon();",array('class'=>'am-btn am-btn-secondary am-btn-sm','id'=>'add_coupon'));?></div></div>
			</div>
			<?php }?>
		</div>
	</div>
<!-- 优惠券 -->
<?php } ?>
<!-- 余额支付 -->
 <?php
        if(isset($configs['enable_balance'])&&$configs['enable_balance']==1){
    ?>
    <div class="am-panel am-panel-default">
        <div class="am-panel-hd am-cf"><input type="checkbox" class="use_offer" id="checknone" /><strong class="am-text-primary am-text-md"><?php echo $ld['use_balance_of_payments']?></strong>&nbsp;<span class="available_balance" style="margin-right:10px;"><?php echo  $ld['available_balance'];?><b style="color:#dd514c;"></b></span><a style="color:#c60000;font-size:16px;line-height:27px;float:none" target="_blank" href="<?php echo $html->url('/users/deposit') ?>"><?php echo $ld['i_want_to_recharge']; ?></a></div>
        <div class="am-panel-bd am-form am-form-horizontal user_balance am-hide">
            <a href="javascript:void(0);" class="am-hide discount_clear" onclick="balance_clear()"></a>
            <div class="am-form-detail">
               
                <div class="am-form-group" style="margin-bottom:0px;">
                    <div class="am-u-lg-3 am-u-md-4 am-u-sm-7 am-padding-top-xs am-padding-left-0">
                        <div class="am-input-group">
                          <span class="am-input-group-label">
                            <input type="checkbox" id="use_balance_flag" name="use_balance_flag" onclick="set_user_balance(this)" value="1" />
                          </span>
                          <input type="text" disabled id="user_balance" name="user_balance" value="" onkeyup="clearNoNum(this)" onafterpaste="clearNoNum(this)" onblur="checkbalance(this)" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <script type="text/javascript">
		var cart_total;
		var js_config_price_format="<?php echo $configs['price_format']; ?>";
            
            $(function(){
                cart_total=$("#total").html().replace(/[^\d.]/g,"");
                cart_total=(parseFloat(cart_total).toFixed(2))*1;
                getbalance();
            })
            
            //使用支付
            function set_user_balance(obj){
                if($(obj).prop('checked')){
                    $("#user_balance").attr("disabled",false);
                }else{
                    $("#user_balance").attr("disabled",true);
                    $("#user_balance").val("");
                    
                    $("#total").html(sprintf(js_config_price_format,cart_total));
                    
                    var balance_money=sprintf(js_config_price_format,'0.00');
                    $("#balance_money").html(balance_money);
                }
            }
            
            //验证金额
            function checkbalance(obj){
		var balance="0.00";
		var balancedata=obj.value==""?"0.00":obj.value;
                balancedata=(parseFloat(balancedata).toFixed(2))*1;
                var userbalance=$(".available_balance b").html().replace(/[^\d.]/g,"");
                userbalance=(parseFloat(userbalance).toFixed(2))*1;
                if(balancedata>userbalance){
                    balance=userbalance;
                }else if(balancedata>cart_total){
                    balance=cart_total;
                }else{
                    balance=balancedata;
                }
                obj.value=balance;
                var balance_money=sprintf(js_config_price_format,parseFloat(balance).toFixed(2));
                $("#balance_money").html(balance_money);
                
                var total_html=parseFloat(cart_total-balance).toFixed(2);
                total_html=sprintf(js_config_price_format,total_html);
                $("#total").html(total_html);
            }

            //只能输入数字和小数点
            function clearNoNum(obj){
                obj.value = obj.value.replace(/[^\d.]/g,"");  //清除“数字”和“.”以外的字符  
                obj.value = obj.value.replace(/^\./g,"");  //验证第一个字符是数字而不是. 
                obj.value = obj.value.replace(/\.{2,}/g,"."); //只保留第一个. 清除多余的.   
                obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
            }
            
            //获取余额
            function getbalance(){
                $.ajax({ 
                	// url: "<?php echo $html->url('/users/ajax_getUserInfo/')?>",
                	 url: web_base+"/users/ajax_getUserInfo/",
    	    		dataType:"json",
    	    		type:"POST",
    	    		data: {},
    	    		success: function(data){
                        var userbalance="0.00";
    	    			if(typeof(data.code)=='undefined'){
                            var userdata=data['User'];
                            userbalance=userdata.balance;
                        }else{
                            alert(data.msg);
                        }
                        $(".available_balance b").html(sprintf(js_config_price_format,userbalance));
                        if (userbalance=="0.00") {
                        	$("#checknone").prop("disabled",true);
                        };
    	  			}
    	  			
    	  		});
            }
	     
            function balance_clear(){
                $(".user_balance #use_balance_flag").click();
            }
        </script>
    <?php } ?>
<!-- 余额支付 -->
	<!-- 余额结算 -->
			<div class="moneyinfo">
				<div class="am-form am-form-horizontal">
				<?php $coupon_del = isset($checkout['cart_info']['coupon_del'])?$checkout['cart_info']['coupon_del']:0?>
					<div class="am-form-group" style="margin:0">
						<div class="am-u-lg-10 am-u-md-10 am-u-sm-8 am-form-label"><?php echo $ld['shipping']?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><?php if(isset($checkout['shipping']['shipping_fee']) && $checkout['shipping']['shipping_fee']!=0){ echo $svshow->price_format($checkout['shipping']['shipping_fee'],$configs['price_format']); }else{echo $ld['free_shipping'];} ?></div>
					</div>
					<?php if(isset($_SESSION['checkout']['payment']['payment_fee'])&&$_SESSION['checkout']['payment']['payment_fee']!=0){ ?>
					<div class="am-form-group" style="margin:0">
						<div class="am-u-lg-10 am-u-md-10 am-u-sm-8 am-form-label"><?php echo $ld['flat_rate']?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><?php echo $svshow->price_format($_SESSION['checkout']['payment']['payment_fee'],$configs['price_format']);?></div>
					</div>
					<?php } ?>
					<?php if(isset($_SESSION['checkout']['cart_info']['lease_subtotal'])&&$_SESSION['checkout']['cart_info']['lease_subtotal']!=0){ ?>
					<div class="am-form-group" style="margin:0">
						<div class="am-u-lg-10 am-u-md-10 am-u-sm-8 am-form-label"><?php echo $ld['subtotal'] ?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><?php echo $svshow->price_format($_SESSION['checkout']['cart_info']['lease_subtotal'],$configs['price_format']);?></div>
					</div>
					<?php }else{ ?>
					<div class="am-form-group" style="margin:0">
						<div class="am-u-lg-10 am-u-md-10 am-u-sm-8 am-form-label"><?php echo $ld['order_products_total']?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><?php echo $svshow->price_format($checkout['cart_info']['sum_subtotal'],$configs['price_format']);?></div>
					</div>
					<?php }?>
				      <?php if(isset($checkout['cart_info']['insure_fee'])&&$checkout['cart_info']['insure_fee']>0){ ?>
				     <div>
				     	 	<div class="am-u-lg-10 am-u-md-10 am-u-sm-8 am-form-label"><?php echo $ld['total_value_of_products']; ?></div>
				     	 	<div class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><?php echo $svshow->price_format($checkout['cart_info']['lease_total'],$configs['price_format']);?>
				     	 	</div>
				     </div>
		                    <div class="am-form-group" style="margin:0">
		                    	<div class="am-u-lg-10 am-u-md-10 am-u-sm-8 am-form-label"><?php echo $ld['lease_deposit']; ?></div>
		                    	<div class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><?php echo $svshow->price_format($checkout['cart_info']['insure_fee'],$configs['price_format']);?>
		                    	</div>
		                    </div>
		                    <?php } ?>
					<div  class="am-form-group coupon_del_li am-margin-0" <?php if($coupon_del == 0){ echo 'style="display:none"';}?>>
						<div class="am-u-lg-10 am-u-md-10 am-u-sm-8 am-form-label"><?php echo $ld['rebate_085']?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><span id="coupon_del"><?php echo $svshow->price_format($coupon_del,$configs['price_format']);?></span></div>
					</div>
		                    <?php if(isset($configs['enable_balance'])&&$configs['enable_balance']==1){ ?>
		                    <div class="am-form-group" style="margin:0">
						<div class="am-u-lg-10 am-u-md-10 am-u-sm-8 am-form-label"><?php echo $ld['balance_of_payments']?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><span id="balance_money"><?php echo $svshow->price_format('0.00',$configs['price_format']);?></span></div>
					</div>
		                    <?php } ?>
					<div class="am-form-group" style="margin:0">
						<div class="am-u-lg-10 am-u-md-10 am-u-sm-8 am-form-label"><b style="color:#dd514c;"><?php echo $ld['pay_for_the_order']?></b></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><span id="total" style="font-weight:600;color: #dd514c;"><?php echo $svshow->price_format($checkout['cart_info']['total'],$configs['price_format']);?></span></div>
					</div>
					<?php if(isset($configs['settlement_verification_code'])&&$configs['settlement_verification_code']=='1'){ ?>
					<!-- 验证码 -->
					<div class="am-form-group" style="margin-bottom:0px;">
						<div class="am-u-lg-10 am-u-md-9 am-u-sm-7 am-form-label"><?php echo $ld['please_enter_the_code']?></div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-5 am-form-icon am-form-feedback" ><input type="text" name="check_captcha" class="am-form-field" chkRules="authnum:验证码错误" id="check_captcha" /><span></span></div>
					</div>
					<div class="am-form-group" style="margin-bottom:0px;">
						<div class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-form-label" style="text-align:right;padding-right:0px;"><input type="hidden" id="ck_authnum" value="" /><img id="register_captcha_page" alt="<?php echo $ld['not_clear']?>" onclick="javascript:change_captcha('register_captcha_page');" src="<?php echo $webroot; ?>securimages/index/?1234" /><a href="javascript:void(0);" class="change" onclick="javascript:change_captcha('register_captcha_page');"><?php echo $ld['not_clear']?></a></div>
					</div>
					<?php } ?>
					<div class="am-form-group">
						<div class="am-u-lg-2 am-u-md-12 am-u-sm-12 am-form-label am-fr">
							<a  id="click-one" class="am-u-sm-12 am-btn am-btn-warning am-btn-block-two buysubmit" href="javascript:void(0);"><?php echo $ld['submit_order']?></a>
						</div>
					</div>
				</div>
			</div>
	<?php echo $form->end();?>
</div>
<style type="text/css">
/*结算商品清单样式*/
.am-jiesuan{border-bottom: 1px solid #b4d0ff;margin-top: 5px;background: #f2f6ff;padding: 5px;}
.am-xiahua{border-bottom: 3px solid #b2d1ff;padding-bottom: 9px;}
/*结算商品清单样式*/
.am-panel-hd .use_offer{margin-right:0.6rem;}
.am-cart-checkout{padding-top:20px;}
.am-cart-checkout .am-panel-hd a{text-decoration: none;margin: 0 30px;}
.shipping_method .am-panel-bd .am-list,.payment_method .am-panel-bd .am-list{margin-bottom:0px;}
.shipping_method .am-panel-bd .am-list li,.payment_method .am-panel-bd .am-list li{border:0px;}

.pro_list th{text-align:center;}
.pro_list td label{}

.moneyinfo .am-form-group{margin-bottom:0;}
.moneyinfo .am-form-label{text-align:right;}
.moneyinfo .am-form-label:last-child{text-align:left;}
.moneyinfo table{width:98%;margin:0 auto;}
.moneyinfo td{position:relative;height:30px;}
.am-panel-hd .use_offer{margin-right:0.6rem;}
.am-cart-checkout{padding-top:20px;}
.am-cart-checkout .am-panel-hd a{text-decoration: none;margin: 0 30px;}
.shipping_method .am-panel-bd .am-list,.payment_method .am-panel-bd .am-list{margin-bottom:0px;}
.shipping_method .am-panel-bd .am-list li,.payment_method .am-panel-bd .am-list li{border:0px;}

.pro_list th{text-align:center;}
.pro_list td label{}
/*seeworlds移植样式*/
.moneyinfo .am-form-group{margin:0;}
.moneyinfo .am-form-label{text-align:right;}
.moneyinfo .am-form-label:last-child{text-align:left;}
.moneyinfo table{width:98%;margin:0 auto;}
.moneyinfo td{position:relative;height:30px;}
.am-cart-checkout .am-panel-hd a{margin:0;float:right;}
.pro_list tbody td:nth-child(2){font-size:1.5rem;font-weight:600;}
.checkoutprice{font-weight:bold;color:#dd514c;font-size:1.7rem;}
.checkoutprice span{color:#ccc;font-size:1.5rem;font-weight:normal}
.checkoutPrice{font-weight:500;color:#000;font-size:1.5rem;}
.am-panel-hd{padding-left:0;}
.am-list-static li{font-size:1.4rem;}
.am-table tbody tr td{padding-bottom:0;padding-top:0;}
.am-panel-hd{padding-left:.7em;}
.am-cart-checkout .am-panel .am-panel-bd{padding-left:.7em;padding-bottom:0;}
td a{font-size:1em;}
tr td del{font-weight:400;}
.am-panel-bd{padding-top:0px;}
.am-checkprice span{color:gray;}
#check_captcha{padding:2px;margin-top:5px;}
#check_captcha+span{right:25px!important;top:60%;}
</style>
<script type="text/javascript">
change_captcha('register_captcha_page',true);
js_config_price_format="<?php echo $configs['price_format']; ?>";
$("#check_captcha").blur(function(){
	var authnum_msg="Error";
	var authnum_msg_div=$(".authnum_msg");
	var authnum_val=$("#check_captcha").val().trim();
	var ck_auth_num=$(".moneyinfo").find("input[id=ck_authnum]").length;
	
	if(authnum_val.length==0){
		authnum_msg_div.parent().css("display","block");
		authnum_msg_div.css("color","red").html("验证码必填");
		$("#check_captcha").parent().removeClass("am-form-success");
		$("#check_captcha").parent().removeClass("am-form-error");
		$("#check_captcha").parent().addClass("am-form-warning");
		$("#check_captcha").parent().find("span").removeClass("am-icon-times").removeClass("am-icon-check");
		$("#check_captcha").parent().find("span").addClass("am-icon-warning").css("display","block");
	}else if(ck_auth_num>0){
		var ck_auth=$(".moneyinfo").find("input[id=ck_authnum]").val();
		if(ck_auth.trim().length>0){
			if(authnum_val.toLowerCase()!=ck_auth){
				authnum_msg_div.parent().css("display","block");
				authnum_msg_div.css("color","red").html("验证码错误");
    			$("#check_captcha").parent().removeClass("am-form-success");
    			$("#check_captcha").parent().removeClass("am-form-warning");
    			$("#check_captcha").parent().addClass("am-form-error");
    			$("#check_captcha").parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-check");
    			$("#check_captcha").parent().find("span").addClass("am-icon-times").css("display","block");
			}else{
				authnum_msg_div.parent().css("display","none");
				authnum_msg_div.css("color","green").html("");
    			$("#check_captcha").parent().removeClass("am-form-error");
    			$("#check_captcha").parent().removeClass("am-form-warning");
    			$("#check_captcha").parent().addClass("am-form-success");
    			$("#check_captcha").parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-times");
    			$("#check_captcha").parent().find("span").addClass("am-icon-check").css("display","block");
				authnum_msg="";
			}
		}
	}
});


$(".buysubmit").click(function(){
	 //提交订单按钮
	 var authnum_check="<?php echo isset($configs['settlement_verification_code'])?$configs['settlement_verification_code']:'0'; ?>";
	 var authnum_msg="";
	 if(authnum_check=='1'){
		 authnum_msg="Error";
		 var authnum_msg_div=$(".authnum_msg");
		 var authnum_val=$("#check_captcha").val().trim();
		 var ck_auth_num=$(".moneyinfo").find("input[id=ck_authnum]").length;

		 if(authnum_val.length==0){
		 	authnum_msg_div.parent().css("display","block");
		 	authnum_msg_div.css("color","red").html("验证码必填");
		 	$("#check_captcha").parent().removeClass("am-form-success");
		 	$("#check_captcha").parent().removeClass("am-form-error");
		 	$("#check_captcha").parent().addClass("am-form-warning");
		 	$("#check_captcha").parent().find("span").removeClass("am-icon-times").removeClass("am-icon-check");
		 	$("#check_captcha").parent().find("span").addClass("am-icon-warning").css("display","block");
		 }else if(ck_auth_num>0){
		 	var ck_auth=$(".moneyinfo").find("input[id=ck_authnum]").val();
		 	if(ck_auth.trim().length>0){
		 		if(authnum_val.toLowerCase()!=ck_auth){
		 			authnum_msg_div.parent().css("display","block");
		 			authnum_msg_div.css("color","red").html("验证码错误");
		 			$("#check_captcha").parent().removeClass("am-form-success");
		 			$("#check_captcha").parent().removeClass("am-form-warning");
		 			$("#check_captcha").parent().addClass("am-form-error");
		 			$("#check_captcha").parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-check");
		 			$("#check_captcha").parent().find("span").addClass("am-icon-times").css("display","block");
		 		}else{
		 			authnum_msg_div.parent().css("display","none");
		 			authnum_msg_div.css("color","green").html("");
		 			$("#check_captcha").parent().removeClass("am-form-error");
		 			$("#check_captcha").parent().removeClass("am-form-warning");
		 			$("#check_captcha").parent().addClass("am-form-success");
		 			$("#check_captcha").parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-times");
		 			$("#check_captcha").parent().find("span").addClass("am-icon-check").css("display","block");
		 			authnum_msg="";
		 		}
		 	}
		 }
	 }
	 if(authnum_msg==""){
	 	 $(this).button('loading');
	 	document.forms.cart_info.submit();
	 }
});

function invoice_sel(obj){
	if(obj.value==1){
		document.getElementById("invoice_title").style.display="none";
		document.getElementById("invoice_title").value="<?php echo (isset($ld['type_person']))?$ld['type_person']:''; ?>";
	}
	if(obj.value==2){
		document.getElementById("invoice_title").value="";
		document.getElementById("invoice_title").style.display="inline-block";

	}

}
$(function(){
	$(".but").click(function(){
		$("#faqbg").css({
			display:"block",
			height:$(document).height()
			});
		var yscroll =document.documentElement.scrollTop;
		$("#faqdiv").css("top","300px");
		$("#faqdiv").css("display","block");
		document.documentElement.scrollTop=0;
	});
	$(".close").click(function(){
		$("#faqbg").css("display","none");
		$("#faqdiv").css("display","none");
	});
	$("#close").click(function(){
		$("#faqbg").css("display","none");
		$("#faqdiv").css("display","none");
	});
    
    
    $(".am-panel-hd .use_offer").click(function(){
        if($(this).prop("checked")){
            $(this).parent().parent().find(".am-panel-bd").removeClass("am-hide");
        }else{
            $(this).parent().parent().find(".am-panel-bd").addClass("am-hide");
            $(this).parent().parent().find(".discount_clear").click();
        }
    });
    
    $("#cart_point").click(function(){
    		usepoint("<?php echo $user_info['User']['point']?>","<?php echo $checkout['cart_info']['total']?>",j_points)
    });
})
function but_onclick(){
	$("#faqbg").css({
		display:"block",
		height:$(document).height()
		});
	var yscroll =document.documentElement.scrollTop;
	$("#faqdiv").css("top","300px");
	$("#faqdiv").css("display","block");
	document.documentElement.scrollTop=0;
}
function usepoint(user_point,total,j_points){
	var point=document.getElementById("use_point").value;
	var p=document.getElementById("point_u").value;
	//判断输入的积分是不是整形
	var regNum =/[\d]+$/;
	document.getElementById("point_del").value = "";
	//document.getElementById("total").innerHTML=total;
	if(!regNum.test(point)){
		alert(j_enter_integer);
		document.getElementById("use_point").value='0';
		point = 0;
	}
	if(point=='0'){
		 document.getElementById("point_li").style.display="none";
	}else{
		 document.getElementById("point_li").style.display="block";
	}
	if(point>user_point){
		document.getElementById("use_point").value='0';
		alert(j_points_remaining);
		return;
	}
    var sUrl = web_base+"/carts/usepoint/";
    var postData ={point:point};
	var check_point_Success = function(result){
		if(point !=0 &&result.type!=1){
			alert(result.msg);
		}
	    	document.getElementById("point_del").value = result.point_del;
		document.getElementById("del").innerHTML=result.format_point_del;
		document.getElementById("total").innerHTML=result.format_total;
		if(typeof(cart_total)!="undefined"&&document.getElementById("user_balance")){
			cart_total=result.format_total;
			cart_total=cart_total.replace(/[^\d.]/g,"");
			cart_total=(parseFloat(cart_total).toFixed(2))*1;
			var balance_input=document.getElementById("user_balance");
			checkbalance(balance_input);
		}
		var a='<?php echo $svshow->price_format("0",$configs['price_format']);?>';
		var dell=a.replace(/[\d\.]+/g,result.point_del);
		//可用积分
		document.getElementById("available_points").innerHTML=result.can_use_point;
		if(document.getElementById("cart_point").checked&&($("#use_point").val()==''||$("#use_point").val()=='0')){
			$("#use_point").val(result.can_use_point);
			$("#use_point").blur();
		}
	}
	$.post(
		sUrl, //url
		postData,//data
		check_point_Success,
 		"json"//type
 	);
}
function remove_coupon_value(id){
	if(confirm(confirm_delete)){
		$("#"+id).val('');
		$("#"+id).parent().parent().fadeOut().remove();
		check_coupon_value(id,true);
	}
}

//使用优惠券
function check_coupon_value(id,del_coupon){
	var coupon_value="";
	if(typeof(del_coupon)=='undefined'&&document.getElementById(id)){
		coupon_value=document.getElementById(id).value;
		if(coupon_value.trim()==""){return false;}
	}else if(document.getElementById(id)&&del_coupon==true){
		document.getElementById(id).value="";
	}
	var allcoupons = "";
	$("input[name='coupon']").each(function(){
		if($(this).val()!=""){
			allcoupons = allcoupons + $(this).val()+',';
		}
	});
	var sUrl = web_base+"/carts/usecoupon/";
	var postData ={coupon:coupon_value,allcoupons:allcoupons};
	var check_coupon_Success = function(result){
		if(result.coupon_del>0){
			$(".coupon_del_li").css("display","block");
		}else{
			$(".coupon_del_li").css("display","none");
		}
		document.getElementById("coupon_del").innerHTML = result.format_coupon_del;
		document.getElementById("total").innerHTML = result.format_total;
		$("#coupon_list .coupon_list").remove();
		if(result.type=="1"){
			$("#coupon_value").val("");
		}else{
			if(typeof(del_coupon)=='undefined'){
				alert(result.msg);
			}
		}
		if(typeof(result.coupon_list)!='undefined'){
			var coupon_html="";
			$.each( result.coupon_list, function(key,value){ 
				var coupon_fee_txt=sprintf(js_config_price_format,value.fee);
				coupon_html+="<div class='am-form-group coupon_list'><div class='am-fl'><?php echo $ld['rebate_061']; ?>:&nbsp;</div><div class='am-fl'>"+value.sn_code+"<input type='hidden' id='coupon_value"+value.sn_code+"' value='"+value.sn_code+"' name='coupon'><span style='color:red;cursor: pointer;margin-left:0.7rem;' class='coupon_remove' onclick=\"remove_coupon_value('coupon_value"+value.sn_code+"')\">X</span></div><div class='am-text-left am-fl'>&nbsp;&nbsp;&nbsp;<span style='color:#999'><?php echo $ld['deductible_money']; ?>:</span><i>"+coupon_fee_txt+"</i></div></div>";
			});
			$("#coupon_list").prepend(coupon_html);
		}
	}
	$.post(
		sUrl, //url
		postData,//data
		check_coupon_Success,
 		"json"//type
 	);
}

//增加输入框的数量
function add_coupon(){
	var length=$("#coupon_list>div.coupon_list").length;
	var showhtml="<div class='am-form-group coupon_value_"+length+"'><div class='am-u-lg-3 am-u-md-4 am-u-sm-7'><input type='text' value='' id='coupon_value"+length+"' name='coupon'></div><div class='am-u-lg-4 am-u-md-4 am-u-sm-4'><input type='button' onclick=\"check_coupon_value('coupon_value"+length+"\')\" value='<?php echo $ld['ok'] ?>' class='am-btn am-btn-secondary'><span style='margin-left:0.7rem;color:red;cursor: pointer;' onclick=\"delete_coupon('coupon_value_"+length+"\')\">X</span></div></div></div>";
	$("#coupon_list").append(showhtml);
	length=$("#coupon_list>div.coupon_list").length;
	if(length >= $('#coupon_use_num').val()){
		$('#add_coupon').hide();
	}
}

function delete_coupon(id){
	$("."+id).remove();
	var length=$("#coupon_list>div.coupon_list").length;
	if(length<$('#coupon_use_num').val()){
		$('#add_coupon').show();
	}
}

function clear_point(){
    $("#use_point").val('0');
}

function clear_coupon(){
    $("#coupon_list .coupon_remove").each(function(){
        var coupon_remove=$(this).parent().find("input[type='hidden']");
        $(coupon_remove).val("");
        $(this).parent().parent().fadeOut();
        check_coupon_value($(coupon_remove).prop("id"),true);
    });
}

$(function () {
var checkout_customized_border_length = $("#checkout_customized li").length;
for(var i = 0;i < checkout_customized_border_length;i++){
    if (i%2 == 0) {
    $("#checkout_customized li").eq(i).css("border-right","1px solid #ccc");
    };
}
})


function auto_number () {
    var num_product = $(".check_product_number").length;
    for(var i =0; i<num_product; i++){
    $(".check_product_number").eq(i).parent().parent().find(".check_number").html($(".check_product_number").eq(i).text());
    }
}
auto_number();

$(function () {
	if ($(window).width()<641) {
		$(".br_remove").addClass('am-hide');
		$(".br_remove").next().attr("style","padding-left:10px");
		$(".check-price").css("text-align","right");
	};
})

$(window).resize(function () {
	if ($(window).width()<641) {
		$(".br_remove").addClass('am-hide');	
		$(".br_remove").next().css("padding-left","10px");
		$(".check-price").css("text-align","right");
	}else{
		$(".br_remove").removeClass('am-hide');
		$(".br_remove").next().css("padding-left","0");
		$(".check-price").css("text-align","left");
	}
})
</script>
<style type="text/css">
/* 发票层样式 */
#faqbg{background-color:#666666; position:absolute; z-index:99; left:0; top:0;display:none; width:100%; height:1000px;opacity:0.5;filter:alpha(opacity=50);-moz-opacity: 0.5;}
#faqdiv{position:absolute;width:400px; left:50%; top:50%; margin-left:-200px; height:auto; z-index:100;background-color:#fff; border:1px solid #5F7A1F; padding:1px;}
#faqdiv h2{ height:25px; font-size:14px; background-color:#C1DFA6; position:relative; padding-left:10px; line-height:25px;}
#faqdiv h2 a{position:absolute; right:5px; font-size:12px; color:#434E30}
#faqdiv .form{padding:10px;}
/* 发票层样式 end */
</style>