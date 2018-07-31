<div class="am-container coupons">
    <div class="am-cf am-user" style="padding-top:0;">
	    <!-- <h3 style="margin-left:2.2rem;"><?php echo $ld['rebate_084'] ?></h3> -->
		<div style="text-align:left;font-size:20px;border-bottom:1px solid #ccc;padding-bottom:1rem;margin-top:1%;padding-left:5px;margin-bottom:1rem;" >
			<span style="float:left;"><?php echo $ld['rebate_084'] ?></span>
			<div class="am-cf"></div>
		</div>
		<span style="margin-left:2.2rem;"><select name="status" onchange="check_stauts(this.value)"><option value="-1" 
			<?php if($status == "-1"){ echo 'selected';} ?>><?php echo $ld['rebate_066'];?></option>
			<option value="0" <?php if($status == "0"){ echo 'selected';} ?>><?php echo $ld['rebate_067'];?></option><option value="1" <?php if($status == "1"){ echo 'selected';} ?>><?php echo $ld['rebate_068'];?></option><option value="2"  <?php if($status == "2"){ echo 'selected';} ?>><?php echo $ld['rebate_069'];?></option>	<option value="3"  <?php if($status == "3"){ echo 'selected';} ?>><?php echo $ld['used'];?></option> </select>
		</span>
    </div>
    <div class="am-panel am-panel-default coupon-list">
		<div class="am-panel-bd">
			<table class="am-table am-table-bd am-table-striped admin-content-table" style="">
				<tr >
					<th><?php echo $ld['rebate_060'] ?><br class="am-hide-sm-only" /><span class="am-hide-sm-only"><?php echo $ld['rebate_061'] ?></span></th>
					<th><?php echo $ld['rebate_062'] ?></th>
					<th><?php echo $ld['rebate_063'] ?></th>
					<th><?php echo $ld['rebate_064'] ?></th>
					<th class="am-hide-sm-only"><?php echo $ld['status'] ?></th>
				</tr>
				<?php if(isset($coupons) && sizeof($coupons)>0){?>
				<?php foreach($coupons as $k=>$v){?>
					<tr>
						<td style="padding-left:1.2rem;padding-right:1.2rem"><?php echo $v['CouponTypeI18n']['name']?><br class="am-hide-sm-only" /><span class="am-hide-sm-only"><?php echo $v['Coupon']['sn_code']?></span></td>
						<td style="padding-left:1.2rem;padding-right:1.2rem"><?php echo ($v['CouponType']['type']==2)?($v['CouponType']['money'].'元'):(($v['CouponType']['money']/10).'折');?></td>
						<td style="padding-left:1.2rem;padding-right:1.2rem"><?php echo $v['CouponType']['min_amount']?></td>
						<td style="padding-left:1.2rem;padding-right:1.2rem"><?php echo date('Y-m-d',strtotime($v['CouponType']['use_start_date']));?>&nbsp;<br /><?php echo date('Y-m-d',strtotime($v['CouponType']['use_end_date']));?></td>
						<td class="am-hide-sm-only" style="padding-left:1.2rem;padding-right:1.2rem">
							<?php if($v['Coupon']['order_id'] == 1){?>
							<?php echo $ld['rebate_070'];?>
							<?php }?>
							<?php if($v['Coupon']['order_id'] == 0){?>
							<?php echo $ld['rebate_071'];?>
							<?php }?>
						</td>
					</tr>
				<?php }?>
				<?php }else{?>
				<tr>
					<td colspan='5' class="am-text-center" style="padding-top:20px;"><?php echo $ld['rebate_072'];?></td>
				</tr>
				<?php }?>
			</table>
		</div>
	</div>
	<div class="am-panel am-panel-default add-coupon">
		<div class="am-panel-hd" style="height:33px;padding-top:0;padding-bottom:0;line-height:32px;"><strong><?php echo $ld['rebate_073'];?></strong></div>
		<div class="am-cf am-panel-bd" style="padding-top:1.25rem;">
			<p style="padding:0.7rem 1.25rem;"><?php echo $ld['rebate_074'];?></p>
			<?php echo $form->create('/users',array('action'=>'coupons/add_coupon','id'=>'edit_form','name'=>'user_edit','type'=>'POST','class'=>'am-form am-form-horizontal'));?>
			<div class="am-form-detail am-coupons">
				<div class="am-form-group">
					<div class="am-u-lg-2 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:left;padding-left:1em;"><?php echo $ld['rebate_075'];?></div>
					<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input type="text" name="sn_code" id="sn_code" style="margin-left:0;"/></div>
				</div>
				<div class="am-form-group">
					<div class="am-u-lg-2 am-u-md-4 am-u-sm-4 am-form-label" style="text-align:left;padding-left:1em;"><?php echo $ld['rebate_076'];?></div>
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-8" style="width:24%;padding-right:0;"><input type="text" id="captcha" name="captcha" style="margin-left:0;width:95%;" /></div>
					<div class="am-u-lg-4 am-u-md-4 am-u-sm-12 captcha"><img id="coupon_captcha" src="<?php echo $webroot; ?>securimages/index/?1234" /><a href="javascript:change_captcha('coupon_captcha');" class="am-icon-refresh"></a></div>
				</div>
				<div class="am-form-group">
					<div class="am-u-lg-2 am-u-md-4 am-u-sm-5 am-form-label">&nbsp;</div>
					<div class="am-u-lg-4 am-u-md-4 am-u-sm-3"><input type="button" class="am-btn am-btn-primary" onclick="add_coupon();" value="<?php echo $ld['activation'];?>" /></div>
					<div class="am-u-lg-4 am-u-md-4 am-u-sm-4" id="sn_code_msg" style="color:red;">&nbsp;</div>
				</div>
			</div>
			<?php echo $form->end();?>
		</div>
	</div>
	
	<div class="am-panel am-panel-default coupon-desc">
		<div class="am-panel-hd" style="height:33px;padding-top:0;padding-bottom:0;line-height:32px;background-color:#f5f5f5;"><strong><?php echo $ld['rebate_078'];?></strong></div>
		<div class="am-cf am-panel-bd" style="padding:1.25rem;line-height:29px;">
			<p style=""><span style="">(1)</span>&nbsp;<b><?php echo $ld['rebate_079'];?></b></p>
			<p>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $ld['rebate_080'];?></p>
			<p>(2)&nbsp;<b><?php echo $ld['rebate_081'];?></b></p>
			<p>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $ld['rebate_082'];?></p>
			<p>(3)&nbsp;<b><?php echo $ld['supply_019'];?></b></p>
			<p>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $ld['supply_020'];?></p>
		</div>
	</div>
</div>
<style type='text/css'>
label.am-form-label{font-weight:normal;}
table.am-table th{font-weight:normal;}
</style>
<script type="text/javascript">
function add_coupon(){
	var sn_code_msg=document.getElementById('sn_code_msg');
	sn_code_msg.innerHTML = '&nbsp;';
	var sn_code = document.getElementById('sn_code').value;
	var coupon_captcha = document.getElementById('captcha').value;
	if(sn_code == ''){
		alert('<?php echo $ld['rebate_059'];?>');
		return;
	}
	if(coupon_captcha == ''){
	        alert("<?php echo $ld['please_enter_the_code'].'!'?>");
	        return;
	}
	var sUrl=web_base+"/coupons/user_add_coupon/";
	var postData ={
		sn_code:sn_code,
		is_ajax:1,
		captcha:coupon_captcha
	};
	var add_coupon_Success = function (result){
		var result = eval('('+result+')');
		if(result.type){
			window.location.reload();
		}else{
			alert(result.msg);
		}
	}
	$.post(sUrl,postData,add_coupon_Success);
}
function check_stauts(status){
	window.location=web_base+"/coupons/user_index/?status="+status;
}
</script>