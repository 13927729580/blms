<?php echo $htmlSeevia->js(array("region")); ?>
<div class="am-g am-g-fixed">
	<div class="am-radius bor-shadow" style=";padding:0;;overflow:hidden">
		<div class="am-text-center" style="padding:20px 0;"><h3 style="margin:0;color:#000;font-size:20px;">收货人信息</h3></div>
	</div>
	<div class="am-cf" style="margin:18px 18px 0;">
	    <?php echo $form->create('vouchers',array('action'=>'info_confirm','id'=>'VoucherForm','class'=>' am-form am-form-horizontal','type'=>'POST'));?>
		<div class="am-form-group">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="margin:0;color:#000;padding-left:0;padding-right:0;text-align:right">姓名</label>
			<div class="am-u-lg-6 am-u-md-6 am-u-sm-9">
				<input  type="text" class="am-radius" name="data[consignee]" placeholder="收货人姓名">
			</div>
			<div class="am-u-lg-3 am-u-md-3 am-hide-sm-only">&nbsp;</div>
		</div>
		<div class="am-form-group">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="margin:0;color:#000;padding-left:0;padding-right:0;text-align:right">手机号</label>
			<div class="am-u-lg-6 am-u-md-6 am-u-sm-9">
				<input  type="text" class="js_consignee_mobile am-radius" name="data[mobile]"  placeholder="收货人电话">
			</div>
			<div class="am-u-lg-3 am-u-md-3 am-hide-sm-only">&nbsp;</div>
		</div>
	    <div class="am-form-group">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="margin:0;color:#000;padding-left:0;padding-right:0;text-align:right"><?php echo $ld['region'] ?></label>
			<div class="am-u-lg-6 am-u-md-6 am-u-sm-9"><input type="hidden" id="local" value="<?php echo LOCALE; ?>" />
				<span id="regionsupdate">
					<select  name="region" id="region" onchange="reload_two_regions()">
						<option><?php echo $ld['state_province'] ?></option>
						<option>...</option>
					</select>
					<select gtbfieldid="2" onchange="reload_two_regions()">
						<option><?php echo $ld['city'] ?></option>
						<option>...</option>
					</select>
					<select gtbfieldid="3" onchange="reload_two_regions()">
						<option><?php echo $ld['counties'] ?></option>
						<option>...</option>
					</select>
				</span>
			</div>
			<div class="am-u-lg-3 am-u-md-3 am-hide-sm-only">&nbsp;</div>
	    </div>
		<div class="am-form-group">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="margin:0;color:#000;padding-left:0;padding-right:0;text-align:right">详细地址</label>
			<div class="am-u-lg-6 am-u-md-6 am-u-sm-9">
				<input class="am-radius" type="text" name="data[address]">
			</div>
			<div class="am-u-lg-3 am-u-md-3 am-hide-sm-only">&nbsp;</div>
		</div>
	   	
		<div class="am-text-center am-show-sm-only">
			<p style="padding:13px 18px 0;">
				<button type="submit" class="am-btn am-btn-warning" style="width:100%;margin-bottom:10px">开始兑换</button>
			</p>
			<p style="padding:0 18px;">
				<button type="button" class="am-btn" style="background:#0e90d2;color:#fff;width:100%;">返回上一页</button>
			</p>
		</div>
	    <div class="am-text-center am-show-md-up">
		<p style="padding:13px 18px 0;">
			<button type="submit" class="am-btn am-btn-warning" style="width:120px;margin-bottom:10px">开始兑换</button>
		</p>
		<p style="padding:0 18px;">
			<button type="button" class="am-btn" style="background:#0e90d2;color:#fff;width:120px;"  onclick="window.location.href='<?php echo $html->url('/vouchers/index'); ?>';">返回上一页</button>
		</p>
	  </div>
	  <?php echo $form->end(); ?>
  	</div>
  	<hr>
  </div>
<style type="text/css">
.am-btn:active:focus, .am-btn:focus{outline:none;}
#VoucherForm .am-form-group select{display: inline;float: left;margin-right: 5px;min-width: 100px;position: relative;width: 30%;}
.am-form-horizontal .am-form-label{padding-top:0.4em;}
</style>
<script type="text/javascript">
show_uncheck_regions('');

$(function(){
 	$('#VoucherForm').validator({
 		keyboardEvents:'change',
    		validateOnSubmit:true,
    		validate: function(validity) {
    			var tagName=$(validity.field).prop("tagName");
    			var input_value = $(validity.field).val();
    			if(input_value.trim()==""){
    				validity.valid=false;
    				return validity;
    			}
    			if(tagName=="SELECT"){
    				if(input_value.trim()==j_please_select){
    					validity.valid=false;
    				}else{
    					validity.valid=true;
    				}
				return validity;
    			}
    			if ($(validity.field).is('.js_consignee_mobile')) {
    				if (!input_value.match(/^1\d{10}$/)) {
    					validity.valid=false;
    				}else{
    					validity.valid=true;
    				}
				return validity;
    			}
    		}
  	});
})
</script>