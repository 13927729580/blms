<?php
	if(isset($_COOKIE['Voucher_Info'])&&!empty($_COOKIE['Voucher_Info'])){
		$Voucher_Info=unserialize(stripslashes($_COOKIE['Voucher_Info']));
	}
?>
<div class="am-g am-g-fixed">
  <div class="am-radius bor-shadow" style="overflow:hidden">
    	<div class="am-text-center" style="padding:20px 0;"><h3 style="margin:0;color:#000;font-size:20px;">礼卡礼券兑换</h3></div>
  	<div class="am-cf" style="margin:18px 18px 0;">
		<?php echo $form->create('/vouchers',array('action'=>'index','name'=>'VoucherForm','id'=>'VoucherForm','class'=>' am-form am-form-horizontal','type'=>'POST'));?>
			<div class="am-form-group am-cf">
				<label class="am-u-sm-2 am-u-lg-4 am-text-right" style="margin:0;color:#000;padding-left:0;padding-right:0;padding-top: .4em" for="kahao">卡号</label>
				<div class="am-u-sm-10 am-u-lg-4">
					<input  type="text" class="am-radius" name="data[card_sn]" value="<?php echo isset($Voucher_Info['VoucherEntityCard'])?$Voucher_Info['VoucherEntityCard']['card_sn']:''; ?>" minlength='6' placeholder="礼品卡卡号">
				</div>
				<!-- <div class="am-u-sm-4"><button type="button" class="am-btn am-btn-primary am-radius" onclick="search_amount(this)">查询面值</button></div> -->
			</div>
			<div class="am-form-group am-cf">
				<label class="am-u-sm-2 am-u-lg-4 am-text-right" style="margin:0;color:#000;padding-top: .4em;padding-left:0;padding-right:0;">密码</label>
				<div class="am-u-sm-10 am-u-lg-4">
					<input type="password" class="am-radius voucher_check" value="<?php //echo isset($Voucher_Info['VoucherEntityCard'])?$Voucher_Info['VoucherEntityCard']['card_password']:''; ?>" name="data[card_password]" minlength='6' placeholder="礼品卡密码">
				</div>
				<div class="am-u-sm-4">&nbsp;</div>
			</div>
			<div class="am-form-group am-cf authnum_voucher" style="display:none;">
				<label class="am-u-sm-4 am-text-right" style="margin:0;color:#000;padding-top: .4em;padding-left:0;padding-right:0;">验证码</label>
				<div class="am-u-sm-4">
					<input type="hidden" class="ck_authnum" id="ck_authnum" name="data[ck_authnum]" value="" />
					<input type="text" class="am-radius authnum_check" name="data[authnum]">
				</div>
				<div class="am-u-sm-4">
					<img id='authnum_voucher_img' align='absmiddle' src="<?php echo $webroot;?>securimages/index/?1234" alt="<?php echo $ld['not_clear']?>" onclick="change_captcha('authnum_voucher_img');" /><a href="javascript:change_captcha('authnum_voucher_img');"><?php echo $ld['not_clear']?></a>
				</div>
				<div class="am-u-sm-3">&nbsp;</div>
			</div>
			
			<div class="am-form-group am-cf">
				<div class="am-text-center ">
					<button type="button" class="am-btn am-btn-primary am-radius" onclick="search_amount(this)">查询面值</button>
					<button type="submit" class="am-btn am-btn-warning am-radius">开始兑换</button>
				</div>
				
			</div>
		<?php echo $form->end();?>
 	</div>
 	<hr >
</div>

<!-- 查询面值 -->
<div class="am-modal am-modal-no-btn" tabindex="-1" id="EntityCard_amount">
  <div class="am-modal-dialog" style="overflow-y:auto;">
    <div class="am-modal-hd"><?php echo '实体卡信息';?>
      <a href="javascript: void(0)" style="top:-5px;right:0px;" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd am-form-detail am-form am-form-horizontal">
        <table class="am-table">
    		<tr>
    			<th>面额</th>
    			<td></td>
    		</tr>
    		<tr>
    			<th align='right'>商品</th>
    			<td></td>
    		</tr>
    	</table>
    </div>
  </div>
</div>
<!-- 查询面值 -->

<style type="text/css">
#EntityCard_amount table.am-table th{width:20%;padding-right:10px;text-align:right;}
</style>
<script type="text/javascript">
$(function(){
	var Voucher_error_count=0;
 	$('#VoucherForm').validator({
    		validate: function(validity) {
    			var input_value = $(validity.field).val();
    			if(input_value.trim()==""){
    				validity.valid=false;
			}else{
				validity.valid=true;
			}
    			if ($(validity.field).is('.authnum_check')) {
    				var ck_authnum=$(validity.field).parent().find("input[type='hidden']").val();
    				if(ck_authnum!=input_value.toLowerCase()){
    					validity.valid=false;
    				}else{
    					validity.valid=true;
    				}
    			}
    			/*if ($(validity.field).is('.voucher_check')){
	    			var PostData=$('#VoucherForm').serialize();
			        // 异步操作必须返回 Deferred 对象
			        return $.ajax({
					url: "<?php echo $html->url('/vouchers/voucher_check'); ?>",
					cache: false, //实际使用中请禁用缓存
					data:PostData,
					type:'POST',
					dataType: 'json'
			        }).then(function(data) {
			        	if(data.flag=='1'){
			        		validity.valid=true;
			        	}else{
			        		Voucher_error_count++;
			        		if(Voucher_error_count>=4){
			        			$(".authnum_voucher").show();
			        			change_captcha('authnum_voucher_img');
			        		}
			        		validity.valid=false;
			        	}
			          	return validity;
			        }, function() {
			        	validity.valid=false;
			          	return validity;
			       });
 		       }*/
    		},
    		submit:function(){
    			if(this.isFormValid()){
    				voucher_check();
    			}
    			return false;
    		}
  	});
 	
})


function voucher_check(){
	var PostData=$('#VoucherForm').serialize();
	$.ajax({
			url:web_base+"/vouchers/voucher_check",
			data:PostData,
			type:'POST',
			dataType: 'json',
			success:function(data){
				if(data.flag=='1'){
		        		document.VoucherForm.submit();
		        	}else{
		        		alert(data.message);
		        	}
			}
		});
}

function search_amount(obj){
	var card_sn=$(obj).parent().parent().parent().find("input[type='text']").val().trim();
	if(card_sn!=""){
		$.ajax({
			// url:"<?php echo $html->url('/vouchers/search_amount'); ?>",
			url:web_base+"/vouchers/search_amount",
			data:{'card_sn':card_sn},
			type:'POST',
			dataType: 'json',
			success:function(data){
				if(data.flag=='1'){
					var EntityCard=data.data;
					var product_info=data.product_info;
					if(EntityCard.status=='1'){
						$("#EntityCard_amount").modal();
						$("#EntityCard_amount table.am-table td:eq(0)").html(EntityCard.amount);
						if(typeof(product_info['ProductI18n'])!='undefined'){
							$("#EntityCard_amount table.am-table td:eq(1)").html(product_info['ProductI18n']['name']);
						}else{
							$("#EntityCard_amount table.am-table td:eq(1)").html('');
						}
					}else{
						alert('无效兑换券');
					}
				}else{
					alert('无效兑换券');
				}
			}
		});
	}
}
</script>