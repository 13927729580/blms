<?php
/*****************************************************************************
 * SV-Cart 编辑优惠卷
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
 *****************************************************************************/
?>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<style>
.status{ display:none;}
.btnouter{}
.am-no{color: #dd514c;cursor: pointer;}
.related_dt{width:100%;height:300px;overflow-y: auto;padding-left:10px;}
.related_dt dl{float:left;text-align:left;padding:3px 5px;;border:1px solid #ccc;margin:2px 5px;width:auto;display:block;white-space:nowrap}
.related_dt dl:hover{cursor: pointer;border: 1px solid #5eb95e;color:#5eb95e;}
.related_dt dl:hover span{color:#5eb95e;}
.related_dt dl span{float:none;color: #ccc;padding:3px 2px 0px 2px;margin-right:5px;}
.am-radio input[type="radio"]{margin-left:0px;}
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline-block;;position:relative;top:5px;}
.am-form-label {
    font-weight: bold;
    margin-left: 10px;
    top: 0px;
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
		<?php echo $form->create('Coupons',array('action'=>'/view/'.(isset($this->data['CouponType'])?$this->data['CouponType']['id']:''),'onsubmit'=>'return coupons_check();'));?>
			<!-- 导航 -->
			<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
			    <ul>
				   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
					<li><a href="#rebate_092"><?php echo $ld['rebate_092']?></a></li>
					<li><a href="#rebate_028"><?php echo $ld['rebate_028']?></a></li>
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
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="margin-top:17px;">电子优惠券</label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
			    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" >
			    					<input type="text" id="coupons_name_<?php echo $v['Language']['locale']?>" name="data[CouponTypeI18n][<?php echo $v['Language']['locale'];?>][name]" value="<?php echo isset($this->data['CouponTypeI18n'][$v['Language']['locale']]['name'])?$this->data['CouponTypeI18n'][$v['Language']['locale']]['name']:'';?>" />
			    				</div>
				    			<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-group-label am-text-left" style="font-weight:normal;padding-left: 0;">
			    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;<em style="color:red;">*</em>
			    					</label>
				    			<?php }?>
			    			<?php }} ?>
			    			</div>
						</div>
						<input type="hidden" name="data[CouponType][id]" value="<?php echo isset($this->data['CouponType']['id'])?$this->data['CouponType']['id']:'';?>" id="CouponType_id" />		
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label" style="text-align: left;margin-left: 0;"><?php echo $ld['status']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
			    				<label class="am-radio am-success" style="margin-left: 20px;">
			    					<input type="radio" class="radio" data-am-ucheck value="1" name="data[CouponType][status]" <?php if(isset($this->data['CouponType']['status'])&&$this->data['CouponType']['status'] == 1){echo "checked";} ?>checked/><?php echo $ld['yes']?>
			    				</label>&nbsp;&nbsp;
			    				<label class="am-radio am-success">
									<input type="radio" class="radio" data-am-ucheck name="data[CouponType][status]" value="0" <?php if(isset($this->data['CouponType']['status'])&&$this->data['CouponType']['status'] == 0){echo "checked";} ?>/><?php echo $ld['no']?>
			    			</div>
			    		</div>	
					</div>
				</div>
			</div>
			<div id="rebate_092"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['rebate_092']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
						<div class="am-form-group" style="margin-bottom: 0;">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:7px;"><?php echo $ld['rebate_004']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
			    					<?php foreach( $Resource_info["coupontype"] as $k=>$v ){?>
			    						<label class="am-radio am-success" style="white-space:nowrap">
										<input type="radio" data-am-ucheck onclick ="check_send_type(this.value)" name="data[CouponType][send_type]"  value="<?php echo $k;?>" <?php if((isset($this->data['CouponType']['send_type']) && $this->data['CouponType']['send_type'] == $k) ||(!isset($this->data['CouponType']['send_type']) && $k ==0)){echo "checked";}?>  /> <?php echo $v;?>
										</label>&nbsp;&nbsp;
									<?php }?>
			    				</div>
			    			</div>
			    		</div>		
						<div class="am-form-group">
							<div <?php if(empty($this->data) || $this->data['CouponType']['send_type']!=2){?>class = "status"<?php }?> id="order_tr">		
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" ><?php echo $ld['rebate_016']?></label>
				    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
				    					<input type="text" name="data[CouponType][min_products_amount]" value="<?php echo isset($this->data['CouponType']['min_products_amount'])?$this->data['CouponType']['min_products_amount']:'0';?>" />（<?php echo $ld['rebate_018']?>）
				    				</div>
				    			</div>
				    		</div>
			    		</div>		
			    		<?php if(!empty($this->data)){?>			
			    			<div <?php if($this->data['CouponType']['send_type']!=1){?>class = "status"<?php }?> id="product_tr">		
			    				<label style="padding-left:18px" ><?php echo $ld['rebate_017']?></label>
		    					<ul class="am-avg-lg-3 am-avg-md-1 am-avg-sm-1">
		    						    <li style="margin-bottom:10px;">
			    						<div class="am-u-lg-8 am-u-md-3 am-u-sm-3">
											<?php echo $this->element('category_tree');?>
										<div>
									</li>
									<li style="margin-bottom:10px;">
										<div class="am-u-lg-8 am-u-md-8 am-u-sm-3">
											<?php echo $this->element('brand_tree');?>
										</div>
									</li>
									<li style="margin-bottom:10px;">
										<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
											<input type="text" name="product_keyword" id="product_keyword" />
										</div>
										<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
											<input type="button" class="am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['search']?>" onclick="searchProducts();" />
										</div>
									</li>
		    					</ul>
	
			    				<div class="relatedtable am-form-group am-margin-top-xs"  >
			    					<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-text-center">
			    						
			    						<label ><?php echo $ld['option_products']?></label> 

			    						<div><dt id="product_select" class="related_dt" style="font-weight:normal;"></dt></div>
			    					</div>
			    					<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-text-center">
			    						<label ><?php echo $ld['rebate_019'];?></label>
										<div id="relative_product">
											<?php if(isset($product_relations) && sizeof($product_relations)>0){ foreach($product_relations as $k=>$v){?>
											<div class="am-u-lg-10 am-u-md-10 am-u-sm-10 am-text-left">
												<?php echo $v['Product']['code'].'--'.$v['ProductI18n']['name']?>
											</div>
											<div id="r<?php echo $v['Product']['id'];?>" class="am-u-lg-2 am-u-md-2 am-u-sm-2">
												<span class="am-icon-close am-no" onMouseout="onMouseout_deleteimg(this)" onmouseover="onmouseover_deleteimg(this)" onclick="dropCoupon('<?php echo $this->data['CouponType']['id']?>','drop_link_products','<?php echo $v['Product']['id']?>');">
												</span>
											</div>
											<?php }}?>
										</div>
									</div>
			    				</div>
			    								
			    			</div>		
			    		<?php }?>				
						<div class="am-form-group" >
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:3px;"><?php echo $ld['rebate_003']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-6">
			    					<input type="text" id="prefix" name="data[CouponType][prefix]" value="<?php echo isset($this->data['CouponType']['prefix'])?$this->data['CouponType']['prefix']:'';?>" />
			    				</div>
			    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-6 am-form-group-label am-text-left" style="font-weight:normal;padding-left: 0;">（VIP）<em style="color:red;">*</em></label>
			    			</div>
			    		</div>				
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:3px;"><?php echo $ld['rebate_021']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
			    				<div style="width:79%">
				    				<div class="am-u-lg-9 am-u-md-7 am-u-sm-5">
				    					<select data-am-selected id="money" name="data[CouponType][type]" onchange="changetype()">
											<option value="1" <?php echo isset($this->data['CouponType']['type'])&&$this->data['CouponType']['type']=="1"?'selected':'';?>><?php echo $ld['discount']?></option>
											<option value="2" <?php echo isset($this->data['CouponType']['type'])&&$this->data['CouponType']['type']=="2"?'selected':'';?>><?php echo $ld['relief']?></option>
										</select>
				    				</div>

				    				<div class="am-u-lg-2 am-u-md-3 am-u-sm-4" style="width: 20%">
				    					<input type="text" name="data[CouponType][money]" id="money" class="discount_money" value="<?php echo isset($this->data['CouponType']['money'])?$this->data['CouponType']['money']:'';?>" />	
				    				</div>
			    				</div>
			    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-3 am-form-group-label am-text-left" style="font-weight:normal;padding-left: 0;">（<span id="money_i"></span>）<em style="color:red;">*</em></label>
			    			</div>
			    		</div>				
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:3px;" ><?php echo $ld['rebate_022']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[CouponType][min_amount]" id="min_amount" value="<?php echo isset($this->data['CouponType']['min_amount'])?$this->data['CouponType']['min_amount']:'';?>" />（<?php echo $ld['rebate_023'];?>）
			    				</div>
			    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-group-label am-text-left"><em style="color:red;">*</em></label>
			    			</div>
			    		</div>				
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:3px;"><?php echo $ld['rebate_024']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="send_start_date"  name="data[CouponType][send_start_date]" class="am-form-field" placeholder="" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  value="<?php echo isset($this->data['CouponType']['send_start_date'])?$this->data['CouponType']['send_start_date']:'';?>"  readonly/>
			    				</div>
			    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-group-label am-text-left"><em style="color:red;">*</em></label>
			    			</div>
			    		</div>		
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:3px;"><?php echo $ld['rebate_025']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="send_end_date" name="data[CouponType][send_end_date]"  class="am-form-field" placeholder="" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  readonly value="<?php echo isset($this->data['CouponType']['send_end_date'])?$this->data['CouponType']['send_end_date']:'';?>" />
			    				</div>
			    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-group-label am-text-left"><em style="color:red;">*</em></label>
			    			</div>
			    		</div>		
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:3px;" ><?php echo $ld['rebate_026']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="use_start_date" name="data[CouponType][use_start_date]"  class="am-form-field" placeholder="" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  readonly  value="<?php echo isset($this->data['CouponType']['use_start_date'])?$this->data['CouponType']['use_start_date']:'';?>" />
			    				</div>
			    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-group-label am-text-left"><em style="color:red;">*</em></label>
			    			</div>
			    		</div>		
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:3px;"><?php echo $ld['rebate_027']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="use_end_date" name="data[CouponType][use_end_date]"  class="am-form-field" placeholder="" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  readonly  value="<?php echo isset($this->data['CouponType']['use_end_date'])?$this->data['CouponType']['use_end_date']:'';?>" />
			    				</div>
			    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-group-label am-text-left"><em style="color:red;">*</em></label>
			    			</div>
			    		</div>	
					</div>
				</div>
			</div>
			<div id="rebate_028" class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['rebate_028']?>
					</h4>
			    </div>
				<div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
		      			 <label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label"> </label>
			    			<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">		
						<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
						<?php if($configs["show_edit_type"]){?>
						<div class="am-form-group">		
						<div ><span class="ckeditorlanguage  " style="right: 10%;"><?php echo $v['Language']['name'];?></span></div>
							<textarea cols="80" id="elm<?php echo $v['Language']['locale'];?>" name="data[CouponTypeI18n][<?php echo $v['Language']['locale'];?>][description]" rows="10" style="width:auto;height:300px;"><?php echo isset($this->data['CouponTypeI18n'][$v['Language']['locale']]['description'])?$this->data['CouponTypeI18n'][$v['Language']['locale']]['description']:"";?></textarea>
							<script>
							var editor;
							KindEditor.ready(function(K) {
							editor = K.create('#elm<?php echo $v['Language']['locale'];?>', {width:'80%',
	                        langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false});
							});
							</script>
						</div>
						<?php }else{?>
							<div class="am-form-group">
								<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label"><?php echo $v['Language']['name'];?></label>
								<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
									<textarea cols="80" id="elm<?php echo $v['Language']['locale'];?>" name="data[CouponTypeI18n][<?php echo $k;?>][description]" rows="10"><?php echo isset($this->data['CouponTypeI18n'][$v['Language']['locale']]['description'])?$this->data['CouponTypeI18n'][$v['Language']['locale']]['description']:"";?></textarea>
									<?php echo $ckeditor->load("elm".$v['Language']['locale']); ?>
								</div>
							</div>
						<?php }?>
						<?php }}?></div><div class="am-cf"></div>	
					</div>
				</div>
			</div>
		<?php echo $form->end();?>
	</div>
</div>

<script type="text/javascript">
//电子优惠券管理
function coupons_check(){
	var coupontype_name =document.getElementById("coupons_name_"+backend_locale);
	var send_start_date = document.getElementById("send_start_date");
	var send_end_date = document.getElementById("send_end_date");
	var use_start_date = document.getElementById("use_start_date");
	var use_end_date = document.getElementById("use_end_date");
	var use_end_date = document.getElementById("use_end_date");
	var prefix = document.getElementById("prefix");
	var discount_money = document.querySelector(".discount_money");
	var min_amount = document.getElementById("min_amount");
	if( Trim( coupontype_name.value ) == "" ){
		alert('<?php echo $ld['rebate_048'];?>');
		return false;
	}
	if( Trim( prefix.value ) == "" ){
		alert('<?php echo $ld['rebate_049'];?>');
		return false;
	}
	if( Trim( discount_money.value ) == "" ){
		alert('<?php echo $ld['rebate_050'];?>');
		return false;
	}
	if( Trim( min_amount.value ) == "" ){
		alert('最小订单金额不能为空！');
		return false;
	}
	if( Trim( send_start_date.value ) == "" ){
		alert('<?php echo $ld['rebate_051'];?>');
		return false;
	}
	if( Trim( send_end_date.value ) == "" ){
		alert('<?php echo $ld['rebate_052'];?>');
		return false;
	}
	if( Trim( use_start_date.value ) == "" ){
		alert('<?php echo $ld['rebate_053'];?>');
		return false;
	}
	if( Trim( use_end_date.value ) == "" ){
		alert('<?php echo $ld['rebate_054'];?>');
		return false;
	}
	send_start_date = send_start_date.value;
	send_end_date = send_end_date.value;
	use_start_date = use_start_date.value;
	use_end_date = use_end_date.value;

	send_start_date = new Date(send_start_date.replace(/\-/g, "\/"));  
	send_end_date = new Date(send_end_date.replace(/\-/g, "\/")); 
	use_start_date = new Date(use_start_date.replace(/\-/g, "\/"));  
	use_end_date = new Date(use_end_date.replace(/\-/g, "\/")); 

    if(send_start_date > send_end_date){
        alert('发放起始日期 不能大于 发放结束日期！');
        return false;
    }
    if(use_start_date > use_end_date){
        alert('使用起始日期 不能大于 使用结束日期！');
        return false;
    }
    if(send_start_date > use_start_date){
        alert('发放起始日期 不能大于 使用起始日期！');
        return false;
    }
    if(send_end_date > use_end_date){
        alert('发放结束日期 不能大于 使用结束日期！');
        return false;
    }
}

function searchProducts(){
	var category_id = document.getElementById("category_id");//商品分类
	if(document.getElementById("productid")){
		var productid = document.getElementById("productid").value;//该商品id
	}else{
		var productid =0;
	}
	if(document.getElementById("brand_id")){
		var brand_id = document.getElementById("brand_id").value;//商品品牌
	}else{
		var brand_id ='0';
	}
	var product_keyword = document.getElementById("product_keyword");//搜索关键字
	$.ajax({
		url:admin_webroot+"products/searchProducts/",
		type:"POST",
		data:{category_id:category_id.value,brand_id:brand_id,product_keyword:product_keyword.value,productid:productid},
		dataType:"json",
		success:function(data){
			if(data.flag=="1"){
					var product_select_sel = document.getElementById('product_select');
					product_select_sel.innerHTML = "";
					if(data.content){
						var selhtml="";
						for(i=0;i<data.content.length;i++){
							selhtml+="<dl onclick=\"addCoupon('"+data.content[i]['Product'].id+"')\">"+data.content[i]['Product'].code+"--"+data.content[i]['ProductI18n'].name+"<span>+</span></dl>";
						}
						product_select_sel.innerHTML = selhtml;
			         }
					return;
				}
				if(data.flag=="2"){
					alert(data.content);
				}
		}
	});
/*	YUI().use("io",function(Y) {
		var sUrl = admin_webroot+"products/searchProducts/";//访问的URL地址
		var postData = "category_id="+category_id.value+"&brand_id="+brand_id+"&product_keyword="+product_keyword.value+"&productid="+productid;
		var cfg = {
			method: "POST",
			data: postData
		};
		var request = Y.io(sUrl, cfg);//开始请求
		var handleSuccess = function(ioId, o){
			if(o.responseText !== undefined){
				try{
					eval('result='+o.responseText);
				}catch(e){
					alert(j_object_transform_failed);
					alert(o.responseText);
				}

				if(result.flag=="1"){
					var product_select_sel = document.getElementById('product_select');
					product_select_sel.innerHTML = "";
					if(result.content){
						var selhtml="";
						for(i=0;i<result.content.length;i++){
							selhtml+="<dl onclick=\"addCoupon('"+result.content[i]['Product'].id+"')\">"+result.content[i]['Product'].code+"--"+result.content[i]['ProductI18n'].name+"<span>+</span></dl>";
						}
						product_select_sel.innerHTML = selhtml;
			         }
					return;
				}
				if(result.flag=="2"){
					alert(result.content);
				}
			}
		}
		var handleFailure = function(ioId, o){
			//alert("异步请求失败!");
		}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
	});*/
}


function addCoupon (linkedId){
	var act="insert_link_products";
	if(document.getElementById("CouponType_id")){
		var Id=document.getElementById("CouponType_id").value;
	}else{
		var Id=0;
	}
	$.ajax({
		url:admin_webroot+"coupons/"+act+"/"+linkedId+"/"+Id+"/"+Math.random(),
		type:"GET",
		dataType:"json",
		success:function(data){
			if(data.flag=="1"){
				var newhtml = "";
				for(i=0;i<data.content.length;i++){
					var code = "";
					if(data.content[i].code){
						code = data.content[i].code+'--';
					}
            		newhtml+="<div><div class='am-u-lg-10 am-u-md-10 am-u-sm-10  am-text-left' id='r"+data.content[i].id+"'>"+code+data.content[i].name+"</div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'><span class='am-icon-close am-no' onMouseout='onMouseout_deleteimg(this);' onmouseover='onmouseover_deleteimg(this);' onclick=\"dropCoupon("+Id+",'"+data.action+"', "+data.content[i].id+");\"></span></div></div>";
				}
				document.getElementById("relative_product").innerHTML = newhtml;
			//	alert("<?php echo $ld['add_successful'];?>");
				return;
			}
			if(data.flag=="2"){
				alert(data.msg);
			}
		}
	});
}
function dropCoupon(coupon_type_id,act,id){
	//alert(coupon_type_id);
	//alert(act);
	//alert(id);
	$.ajax({
		url:admin_webroot+"coupons/"+act+"/"+coupon_type_id+"/"+id+"/"+Math.random(),
		type:"GET",
		dataType:"json",
		success:function(data){
			if(data.flag=="1"){
				alert(j_deleted_success);
				var obj=document.getElementById('r'+id);
   	     	//	obj.parentNode.removeChild(obj);
   	     		obj.parentNode.remove();
				return;
			}
			if(data.flag=="2"){
				alert(j_failed_delete);
			}
		}
	});
	
	
/*	YUI().use("io",function(Y) {
		var sUrl = admin_webroot+"coupons/"+act+"/"+coupon_type_id+"/"+id+"/"+Math.random();
		var cfg = {
			method: "GET"
		};
		var request = Y.io(sUrl, cfg);//开始请求
		var newhtml = "";
		var handleSuccess = function(ioId, o){
			if(o.responseText !== undefined){
				try{
					eval('result='+o.responseText);
				}catch(e){
					alert(j_object_transform_failed);
					alert(o.responseText);
				}
				if(result.flag=="1"){
					alert(j_deleted_success);
					var obj=document.getElementById('r'+id);
	   	     		obj.parentNode.removeChild(obj);
					return;
				}
				if(result.flag=="2"){
					alert(j_failed_delete);
				}
			}
		}
		var handleFailure = function(ioId, o){
			//alert("异步请求失败!");
		}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
	});*/
}

function check_send_type(val){
	if(val == 1){
		document.getElementById('order_tr').className="status";
        document.getElementById('product_tr').className="";
	}else if(val == 2){
		document.getElementById('order_tr').className="";
		document.getElementById('product_tr').className="status";
	}else{
	    document.getElementById('order_tr').className="status";
		document.getElementById('product_tr').className="status";
	}

}
window.onload =changetype();
function changetype(){
	if(document.getElementById('money').value=="1"){
		document.getElementById('money_i').innerHTML ="%";
	}else{
		document.getElementById('money_i').innerHTML ="<?php echo $ld['app_yuan'];?>";
	}
}
</script>
