<style>
.am-radio, .am-checkbox{display: inline-block;}
.am-radio input[type="radio"]{margin-left:0px;}
 
.am-ucheck-checkbox, .am-ucheck-icons, .am-ucheck-radio {
    height: 20px;
    left: 0;
    position: absolute;
    top: 0px;
    width: 20px;
}
.am-list>li{margin-bottom:0;border-style: none;}
.admin-sidebar-list li a{color:#fff;background-color: #5eb95e;}
.am-list > li > a.am-active, .am-list > li > a.am-active:hover, .am-list > li > a.am-active:focus{font-weight: bold;}
.scrollspy-nav.am-sticky.am-animation-slide-top{width: 100%;}
.am-sticky-placeholder{margin-top: 10px;}
.scrollspy-nav {top: 0;z-index: 100;background: #5eb95e;width: 100%;padding: 0 10px}
.scrollspy-nav ul {margin: 0;padding: 0;}
.scrollspy-nav li {display: inline-block;list-style: none;}
.scrollspy-nav a {color: #eee;padding: 10px 20px;display: inline-block;}
.scrollspy-nav a.am-active {color: #fff;font-weight: bold;}
.crumbs{padding-left:0;margin-bottom:22px;}
</style>
<div>
	<div class="am-panel-group admin-content am-detail-view" id="accordion" style="width: 98%;margin-right: 1%;">
		<?php echo $form->create('Refunds',array('action'=>'/'.(isset($refund_info["Refund"]["id"])?$refund_info["Refund"]["id"]:""),'name'=>'theForm',"onsubmit"=>"return product_detail_checks();"));?>
			<input type="hidden" name="data[Refund][id]" value="<?php echo $refund_info['Refund']['id'] ?>" >
			<input type="hidden" name="data[Refund][refund_id]" value="<?php echo $refund_info['Refund']['refund_id'] ?>" >
			<!-- 导航 -->
			<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
			    <ul>
				   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
				</ul>
			</div>

			<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
			    <button style="margin-right: 0;" type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
				<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
			</div>
			<!-- 导航结束 -->
			<div id="basic_information"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['basic_information']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">

						<div class="am-g">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 "  >订单号</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"> 
								 <?php echo $refund_info['Refund']['order_code'] ?>
							</div> 
						</div>	


						<div class="am-g">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3"  >退货单号</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"> 
								<?php echo $refund_info['Refund']['refund_id'] ?>
							</div> 
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="margin-top:17px;">交易总金额</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><div>
								<input type="text" name="data[Refund][total_fee]" value="<?php echo $refund_info['Refund']['total_fee'] ?>">
							</div></div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:0px;">操作类型</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6" ><div>
								<label class="am-radio am-success"style="padding-top:2px;" >
									<input type="radio" name="data[Refund][refund_type]" data-am-ucheck  value="0" <?php echo $refund_info['Refund']['refund_type']==0?'checked':'' ?>> 退款 
								</label>&nbsp;&nbsp;
								<label class="am-radio am-success" style="padding-top:2px;">
									<input type="radio" name="data[Refund][refund_type]" data-am-ucheck  value="1" <?php echo $refund_info['Refund']['refund_type']==1?'checked':'' ?>>退货
								</label>
							</div></div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:3px;">退货运费</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><div>
								<input type="text" name="data[Refund][shipping_fee]" value="<?php echo $refund_info['Refund']['shipping_fee'] ?>">
							</div></div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:4px;">退货退款类型</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><div>
								<textarea maxlength="200" name="data[Refund][return_reason_type]"><?php echo $refund_info['Refund']['return_reason_type'] ?></textarea>
							</div></div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:3px;">退货退款原因</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><div>
								<textarea maxlength="200" name="data[Refund][return_reason]"><?php echo $refund_info['Refund']['return_reason'] ?></textarea>
							</div></div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label">退货退款状态</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<select name="data[Refund][status]" data-am-selected>
									<option value="WAIT_SELLER_AGREE" <?php echo $refund_info['Refund']['status']=='WAIT_SELLER_AGREE'?"selected='selected'":'' ?>>买家已经申请退款，等待卖家同意</option>
									<option value="WAIT_BUYER_RETURN_GOODS" <?php echo $refund_info['Refund']['status']=='WAIT_BUYER_RETURN_GOODS'?"selected='selected'":'' ?>>卖家已经同意退款，等待买家退货</option>
									<option value="WAIT_SELLER_CONFIRM_GOODS" <?php echo $refund_info['Refund']['status']=='WAIT_SELLER_CONFIRM_GOODS'?"selected='selected'":'' ?>>买家已经退货，等待卖家确认收货</option>
									<option value="SELLER_REFUSE_BUYER" <?php echo $refund_info['Refund']['status']=='SELLER_REFUSE_BUYER'?"selected='selected'":'' ?>>卖家拒绝退款</option>
									<option value="CLOSED" <?php echo $refund_info['Refund']['status']=='CLOSED'?"selected='selected'":'' ?>>退款关闭</option>
									<option value="SUCCESS" <?php echo $refund_info['Refund']['status']=='SUCCESS'?"selected='selected'":'' ?>>退款成功</option>
									<option value="BUYER_NOT_ASK" <?php echo $refund_info['Refund']['status']=='BUYER_NOT_ASK'?"selected='selected'":'' ?>>没有申请退款</option>
									<option value="SELLER_REFUSE_RETURN" <?php echo $refund_info['Refund']['status']=='SELLER_REFUSE_RETURN'?"selected='selected'":'' ?>>卖家拒绝确认收货</option>
									<option value="WAIT_SELLER_REFUND" <?php echo $refund_info['Refund']['status']=='WAIT_SELLER_REFUND'?"selected='selected'":'' ?>>同意退款，待打款</option>
								</select>
							</div>
						</div>	
						<div class="am-g" style="margin-top:8px;">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 " >退货商品</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"> 
								<?php echo $refund_info['Refund']['product_name'] ?>
							</div> 
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="padding-top:0px;">退货商品货号</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<?php echo $refund_info['Refund']['product_code'] ?>
							</div>
						</div>	
						<div class="am-g">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3  " style="padding-top:8px;">退货商品数量</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><div>
								<input type="text" name="data[Refund][product_quantity]" value="<?php echo $refund_info['Refund']['product_quantity'] ?>">	
							</div></div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:2px;">退货商品价格</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><div>
								<input type="text" name="data[Refund][product_price]" value="<?php echo $refund_info['Refund']['product_price'] ?>">
							</div></div>
						</div>
					</div>
				</div>				
			</div>
		<?php echo $form->end();?>
	</div>
</div>