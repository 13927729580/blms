<style type="text/css">
label{font-weight:normal;}
body{font-size: 1.25rem;}
@media only screen and (max-width: 640px){body {word-wrap: normal;}}
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
.btnouter{}
.img_select{max-width:150px;max-height:120px;}

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
.am-u-lg-9.am-u-md-11.am-u-sm-11{margin-top: 19px;}
.img_organization{cursor: pointer;  transition: all 2s;}
.payment_time .am-selected.am-dropdown{width:33%!important;float: left;}
.payment_time .am-selected-btn.am-btn.am-dropdown-toggle.am-btn-default{height: 35px;}
</style>
<div class="am-g">
	<!-- 导航 -->
	<?php echo $form->create('/account_informations',array('action'=>'view/','id'=>'account_informations_form','name'=>'account_informations_form','type'=>'POST'));?>
	<div style="width: 95%;margin-left: 2.5%;">
		<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
		    <ul>
			   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
			</ul>
		</div>
		<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
		    	<input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit'];?>" onclick="account_informations_add()"/>
	        	<input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
		</div>
	</div>
	<div class="am-panel-group admin-content am-detail-view" id="accordion" style="width: 95%;margin-right: 2.5%;">
			<div id="basic_information"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['basic_information']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
		      			<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['classification']; ?></label>
				    			<div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
				    				<select data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}" name="data[AccountInformation][account_category]" id="account_category">
				    					<option value=''><?php echo $ld['please_select']; ?></option>
									<option value='online_trading'><?php echo $ld['online_trading']; ?></option>
									<?php if(isset($info_resource['user_project'])&&sizeof($info_resource['user_project'])>0){foreach($info_resource['user_project'] as $k=>$v){ ?>
									<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
									<?php }} ?>
				    				</select>
				    			</div>
				    		</div>
				    		<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['category']; ?></label>
				    			<div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
				    				<select data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}" name="data[AccountInformation][transaction_category]" id="transaction_category">
				    					<option value=''><?php echo $ld['all_data']; ?></option>
									<?php if(isset($Resource_info['transaction_category'])&&sizeof($Resource_info['transaction_category'])>0){foreach($Resource_info['transaction_category'] as $k=>$v){ ?>
									<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
									<?php }} ?>
				    				</select>
				    			</div>
				    		</div>
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['type']; ?></label>
				    			<div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
				    				<select data-am-selected name="data[AccountInformation][account_type]" id="account_type">
				    					<option value="-1">请选择</option>
										<option value="0">收入</option>
										<option value="1">支出</option>
				    				</select>
				    			</div>
				    		</div>
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo '付款人';?></label>
				    			<div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
					    			<input type="text" value="" name="data[AccountInformation][payer]" id="payer">
					    		</div>
				    		</div>
				    		<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo '付款人账号';?></label>
				    			<div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
					    			<input type="text" value="" name="data[AccountInformation][payer_account]" id="payer_account">
					    		</div>
				    		</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo '收款人';?></label>
							<div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
								<input type="text" value="" name="data[AccountInformation][payee]" id="payee">
							</div>	
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo '收款账号';?></label>
							<div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
								<input type="text" value="" name="data[AccountInformation][receipt_account]" id="receipt_account">
							</div>	
						</div>
	                    		<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo '支付方式'; ?></label>
				    			<div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
				    				<select data-am-selected="{maxHeight: 100}" name="data[AccountInformation][payment_id]" id="payment_id">
				    					<option value="-1">请选择</option>
				    					<?php if(isset($payment_info)&&sizeof($payment_info)>0){foreach ($payment_info as $v) { ?>
				    					<option value="<?php echo $v['Payment']['id'] ?>"><?php echo $v['PaymentI18n']['name'] ?></option>
				    					<?php }} ?>
				    				</select>
				    			</div>
				    		</div>
				    		<div class="am-form-group">
								<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo '交易号';?></label>
								<div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
									<input type="text" value="" name="data[AccountInformation][transaction]" id="transaction">
								</div>	
						</div>
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['amount']; ?></label>
				    			<div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
									<input type="text" value="" name="data[AccountInformation][payment_amount]" id="payment_amount">
				    			</div>
				    		</div>
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['time_of_payment']; ?></label>
				    			<div class="am-u-lg-4 am-u-md-3 am-u-sm-3 payment_time">
									<input id="payment_time" type="hidden" name="data[AccountInformation][payment_time]" >
									<input style="width: 33%;float: left;" data-am-datepicker="{theme: 'success'}" type="text" value="" id="payment_time_date" placeholder="请选择日期" readonly>
									<select data-am-selected="{maxHeight: 100}" id="payment_time_time">
										<option value="-1">时</option>
										<?php 
											for ($time=1; $time<=24; $time++) {
												if($time<10){
													$time = '0'.$time;
												}
											    echo '<option value="'.$time.'">'.$time.'</option>';
											} 
										?>
									</select>
									<select data-am-selected="{maxHeight: 100}" id="payment_time_minute">
										<option value="-1">分</option>
										<?php 
											for ($minute=0; $minute<=60; $minute++) {
												if($minute<10){
													$minute = '0'.$minute;
												}
											    echo '<option value="'.$minute.'">'.$minute.'</option>';
											} 
										?>
									</select>
				    			</div>
				    		</div>
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['note']; ?></label>
				    			<div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
									<input type="text" value="" name="data[AccountInformation][note]" id="note">
				    			</div>
				    		</div>
			    			<div class="am-form-group" id="status_display">
			    				<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label">状态</label>
							<label class="am-radio am-success" style="margin-left: 20px;">
								<input type="radio" name="data[AccountInformation][status]" value="0" data-am-ucheck checked>
								申请中
							</label>
							<label class="am-radio am-success">
								<input type="radio" name="data[AccountInformation][status]" value="1" data-am-ucheck>
								已完成
							</label>
							<label class="am-radio am-success">
								<input type="radio" name="data[AccountInformation][status]" value="2" data-am-ucheck>
								已取消
							</label>
			    			</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo $form->end(); ?>
</div>
<style type='text/css'>
.am-view-label{margin-top:0px;}
</style>
<script>
function account_informations_add(){
	if($('#account_category').val()){
		alert('请选择分类！');
    		return false;
	}
	if($('#transaction_category').val()){
		alert('请选择类目！');
    		return false;
	}
	$('#payment_time').val($('#payment_time_date').val()+' '+$('#payment_time_time').val()+':'+$('#payment_time_minute').val()+':00');
    if($('#account_type').val() == '-1'){
    	alert('请选择类型！');
    	return false;
    }
    if($('#payer').val() == ''){
    	alert('付款人不能为空！');
    	return false;
    }
    if($('#payer_account').val() == ''){
    	alert('付款账号不能为空！');
    	return false;
    }
    if($('#payee').val() == ''){
    	alert('收款人不能为空！');
    	return false;
    }
    if($('#receipt_account').val() == ''){
    	alert('收款账号不能为空！');
    	return false;
    }
    if($('#payment_id').val() == '-1'){
    	alert('请选择支付方式！');
    	return false;
    }
    if($('#transaction').val() == ''){
    	alert('交易号不能为空！');
    	return false;
    }
    if($('#payment_amount').val() == ''){
    	alert('金额不能为空！');
    	return false;
    }
    if($('#payment_time_date').val()==''){
    	alert('请选择付款时间 日期');
    	return false;
    }
    if($('#payment_time_time').val()=='-1'){
    	alert('请选择付款时间 时');
    	return false;
    }
    if($('#payment_time_minute').val()=='-1'){
    	alert('请选择付款时间 分');
    	return false;
    }
    if($('#payer').val() == ''){
    	alert('付款人不能为空！');
    	return false;
    }
    $.ajax({ 
		url: admin_webroot+"/account_informations/account_informations_add",
		data:$('#account_informations_form').serialize(),
		dataType:"json",
		type:"POST",
		success: function(data){
			if(data.code == '1'){
				window.location.href=admin_webroot+'account_informations/index';
			}
	    }
	});
}
</script>