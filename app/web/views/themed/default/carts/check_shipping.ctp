<?php 
	echo $htmlSeevia->css(array('common'));
	echo $htmlSeevia->js(array('region'));
	$cart_shipping_data=isset($_SESSION['checkout']['shipping'])?$_SESSION['checkout']['shipping']:array();
?>
<!-- Shipping Information -->
<div class="cart_shipping">
	<div class="am-container" style="padding-top:15px;">
		  <?php echo $form->create('carts',array('action'=>'check_address/','id'=>'cart_shipping_form','name'=>'check_shipping','type'=>'POST','onsubmit'=>'return shipping_check();'));?>
		  <div class="am-panel am-panel-default">
		    <div class="am-panel-hd"><strong class="am-text-primary am-text-lg"><?php echo $ld['shipping_method']?></strong>&nbsp;<input type="hidden" name="shipping" value="1"></div>
		    <div class="am-panel-bd" style="padding-left:15px;">
			  <table cellpadding="0" cellspacing="0" width="100%" class="delivery">
				<tbody>
				<?php $num=0;if(!empty($shippings)&&!empty($isHave)) {?>
				  <tr>
					<th colspan="2"><?php echo $ld['shipping_method']?></th>
				  </tr>
				<?php foreach($shippings as $k=>$v) {?>
				  <tr>
					<td width="3%"><input type="radio" id="shipping_<?php echo $num;?>" name="shipping_id" value="<?php echo $v['Shipping']['id'];?>" <?php if(isset($cart_shipping_data['shipping_id'])&&$cart_shipping_data['shipping_id']==$v['Shipping']['id']){echo "checked='checked'";}else if($num==0) echo "checked='checked'";?> /></td>
					<td width="95%"><?php echo $v['ShippingI18n']['name'];?></td>
					<input name="free_subtotal" id="free_subtotal_<?php echo $num;?>" type="hidden" value="<?php echo $v['ShippingArea']['free_subtotal'];?>" />
					<input name="shipping_name" id="shippingname_<?php echo $num;?>" type="hidden" value="<?php echo $v['ShippingI18n']['name'];?>" />
					<input name="shipping_fee" id="shippingfee_<?php echo $num;?>" type="hidden" value="<?php echo $v['ShippingArea']['fee'];?>" />
				  </tr>
				  <?php $num++;?>
				  <?php if(!empty($v['ShippingI18n']['description'])){?>
				  <tr><td></td><td colspan="3"><p><?php echo trim($v['ShippingI18n']['description']);?></p></td></tr>
				  <?php }?>
				  <?php }?>
				  <tr>
					<td colspan="3" style="padding:10px 0px;"><span class="remarks"><span><?php echo $ld['remarks']?>:&nbsp;</span>
						<textarea id="remark" name="remark"><?php echo isset($_SESSION['checkout']['remark'])?$_SESSION['checkout']['remark']:'';?></textarea>
						</span></td>
				  </tr>
				  <tr>
					<td></td>
					<td colspan="2" style="padding:10px 25px;"><a class="shipsub btncon am-btn-secondary am-btn am-btn-sm" href="javascript:shipping_check();void(0);"><?php echo $ld['confirm_delivery']?></a></td>
				  </tr>
				  <?php }else{echo "<tr><td colspan='2'>".$ld['no_distribution']."</td></tr>";}?>
				</tbody>
			  </table>
			</div>
		  </div>
		  <?php echo $form->end();?>
	</div>
</div>
<!-- Shipping Information End -->
<style>
.am-u-lg-10.am-u-md-9.am-u-sm-9 input{margin-left: 0;}
</style>
<!-- Cart Address -->
<div class="cart_address">
	<div class="am-container" style="padding-top:15px;">
		<?php if(!empty($addresses)) {?>
		<div class="am-panel am-panel-default am-address">
		  <div class="am-panel-hd"><strong class="am-text-primary am-text-lg"><?php echo $ld['account_address_book']?></strong>
			<?php if(!(isset($configs['vip-address-num'])&& $configs['vip-address-num']!="" && isset($add_num) && $add_num>=$configs['vip-address-num'])){?>
			  <?php echo $html->link($ld['add_address'],"javascript:void(0);",array("style"=>'text-decoration: none;width:140px;position:relative;top:-2px',"class"=>"am-btn am-btn-secondary am-btn-sm am-fr cart_address_add"))?>
			<?php }?>
		  </div>
		  <div class="am-panel-bd" style="padding-left:15px;">
			<table>
			<?php foreach($addresses as $k=>$v) {?>
			  <tr>
				<td><?php echo $ld['consignee']?>:&nbsp;</td>
				<td><?php if(isset($v['UserAddress']['consignee'])&&!empty($v['UserAddress']['consignee']))echo $v['UserAddress']['consignee'];?></td>
				<td>&nbsp;&nbsp;
				  <?php echo $html->link($ld['modify'],"/carts/check_shipping/".$v['UserAddress']['id']."/#cart_address_form",array("class"=>"am-btn am-btn-default am-btn-sm"),null,null,false);?>
				  <?php echo $html->link($ld['delete'],"/carts/del_address/".$v['UserAddress']['id'],array("class"=>"am-btn am-btn-default am-btn-sm"),null,null,false);?>
				</td>
			  </tr>
			  <tr>
				<td><?php echo $ld['region']?>:&nbsp;</td>
				<td colspan="2"><?php if(isset($v['UserAddress']['regions'])&&!empty($v['UserAddress']['regions']))echo $v['UserAddress']['regions'];?></td>
			  </tr>
			  <tr>
				<td><?php echo $ld['delivery_address']?>:&nbsp;</td>
				<td colspan="2"><?php if(isset($v['UserAddress']['address'])&&!empty($v['UserAddress']['address']))echo $v['UserAddress']['address'];?></td>
			  </tr>
			  <?php  if(isset($v['UserAddress']['sign_building'])&&!empty($v['UserAddress']['sign_building'])){?>
			  <tr>
				<td><?php echo $ld['address_to']?>:&nbsp;</td>
				<td colspan="2"><?php echo $v['UserAddress']['sign_building'];?></td>
			  </tr>
			  <?php }?>
			  <tr>
				<td><?php echo $ld['zip']?>:&nbsp;</td>
				<td colspan="2"><?php if(isset($v['UserAddress']['zipcode'])&&!empty($v['UserAddress']['zipcode']))echo $v['UserAddress']['zipcode'];?></td>
			  </tr>
			  <?php if($v['UserAddress']['telephone']){?>
			  <tr>
				<td><?php echo $ld['telephone']?>:&nbsp;</td>
				<td colspan="2"><?php if(isset($v['UserAddress']['telephone'])&&!empty($v['UserAddress']['telephone']))echo $v['UserAddress']['telephone'];?></td>
			  </tr>
			  <?php } if(!empty($v['UserAddress']['mobile'])){?>
			  <tr>
				<td><?php echo $ld['mobile']?>:&nbsp;</td>
				<td colspan="2"><?php if(isset($v['UserAddress']['mobile'])&&!empty($v['UserAddress']['mobile']))echo $v['UserAddress']['mobile'];?></td>
			  </tr>
			  <?php }?>
			  <tr>
				<td >&nbsp;</td>
				<td colspan="2" style="padding:10px 0;"><?php echo $html->link($ld['delivery_to_this_address'],"/carts/confirm_address/".$v['UserAddress']['id'].'/1',array("class"=>"buatsub am-btn am-btn-primary am-btn-sm",'onclick'=>"return confirm_address(this,'".(isset($v['UserAddress']['regions'])&&!empty($v['UserAddress']['regions'])?$v['UserAddress']['regions']:'')."','".(isset($v['UserAddress']['address'])&&!empty($v['UserAddress']['address'])?$v['UserAddress']['address']:'')."')"),false,false)?></td>
			  </tr>
			<?php }?>
			</table>
		  </div>
		</div>
		<?php }?>
		
		<?php echo $form->create('carts',array('action'=>'edit_address_act','id'=>'cart_address_form','class'=>'am-form  am-form-horizontal','name'=>'edit_address_act_update','type'=>'POST','onsubmit'=>'return check_address();','style'=>(isset($add_num)&&$add_num>0&&!isset($address)?"display:none;":'')));?>
		<div class="am-panel am-panel-default">
		  <div class="am-panel-hd"><strong class="am-text-primary am-text-lg"><?php echo $ld['contact_information']?></strong></div>
		  <div class="am-panel-bd" style="padding-top: 15px;">
			<input type='hidden' name='is_vancl' value='1' />
			<input type="hidden" size="30" name="data[address][name]" id="EditAddressName" value="<?php echo isset($_SESSION['User']['User']['name'])?$_SESSION['User']['User']['name']:'';?>">
			<input type="hidden" size="30" name="data[address][email]" id="EditAddressEmail" value="<?php echo isset($address['UserAddress']['email'])?$address['UserAddress']['email']:'';?>">
			<input type="hidden" size="30" name="data[address][best_time]" id="EditAddressBestTime" value="<?php echo isset($address['UserAddress']['best_time'])?$address['UserAddress']['best_time']:'';?>">
			<input type='hidden' name="data[address][id]" id="EditAddressId" value="<?php echo isset($address['UserAddress']['id'])?$address['UserAddress']['id']:'';?>"/>
			<input type="hidden" name="data[address][user_id]" value="<?php echo $_SESSION['User']['User']['id'];?>"/>
			<input type="hidden" id="isNo" value="0"/>
			<div class="am-form-detail">
			   <div class="am-form-group">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['consignee']?></label>
				<div class="am-u-lg-10 am-u-md-9 am-u-sm-9" >
				  <input type="text"  class="input" id="address_consignee" name="data[address][consignee]" onpropertychange='limitTxtByte(this,120)' oninput='limitTxtByte(this,120)' value="<?php echo isset($address['UserAddress']['consignee'])?$address['UserAddress']['consignee']:'';?>">
				  <em style="top:auto;"><font color="red">*</font><font></font></em>
				</div>
			  </div>
			  <div class="am-form-group cart_shipping_address">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld["region"]?></label>
				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				  <input type='hidden' id='local' value="<?php echo LOCALE;?>">
					<span id="regionsupdate" >
						<select name="data[Address][RegionUpdate][0]" onchange="reload_region(this)">
							<option></option>
						</select>
						<select name="data[Address][RegionUpdate][1]" onchange="reload_region(this)">
							<option></option>
						</select>
						<select name="data[Address][RegionUpdate][2]" onchange="reload_region(this)">
							<option></option>
						</select>
					</span>
				</div>
			  </div>
			  <div class="am-form-group cart_shipping_address">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
				<div class="am-u-lg-10 am-u-md-9 am-u-sm-9"><?php echo $ld['mark_of_delivery_services']?></div>
			  </div>
			  <div class="am-form-group cart_shipping_address" >
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['address']?></label>
				<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
					<?php if(LOCALE=="eng"){?>
					<input type="text"   name="data[address][address]" id="address_address" class="w_111" onpropertychange='limitTxtByte(this,100)' oninput='limitTxtByte(this,100)' value="<?php echo isset($address['UserAddress']['address'])?$address['UserAddress']['address']:'';?>">
					<span id='regionsupdate_str' style="padding-left: 140px;"></span>
					<?php }else{?>
					<span id='regionsupdate_str'></span>
					<input type="text" name="data[address][address]" id="address_address" class="w_111" onpropertychange='limitTxtByte(this,100)' oninput='limitTxtByte(this,100)' value="<?php echo isset($address['UserAddress']['address'])?$address['UserAddress']['address']:'';?>" >
					<?php }?><em style="top:auto;"><font color="red">*</font><font></font></em>
				</div>
			  </div>
			  <div class="am-form-group cart_shipping_address">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['address_to']?></label>
				<div class="am-u-lg-10 am-u-md-9 am-u-sm-9"><input type="text" name="data[address][sign_building]" id="address_sign_building" class="w_111" onpropertychange='limitTxtByte(this,100)' oninput='limitTxtByte(this,100)' value="<?php echo isset($address['UserAddress']['sign_building'])?$address['UserAddress']['sign_building']:'';?>" ></div>
			  </div>
			  <div class="am-form-group cart_shipping_address">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['zip']?></label>
				<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
				  <input size="6" name="data[address][zipcode]" id="address_zipcode" maxlength="6" value="<?php echo isset($address['UserAddress']['zipcode'])?$address['UserAddress']['zipcode']:'';?>" type="text">
				</div>
			  </div>
			  <div class="am-form-group">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['telephone']?>: </label>
				<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
				  <input type="text" name="user_tel0" id="user_tel0" size="20"  value="<?php if(isset($address['UserAddress']['telephone'])) {echo $address['UserAddress']['telephone'];}?>" placeholder="xxx-xxxxxxx"/>
				  <em style="top:auto;"><font color="red">*</font><font><?php echo $ld['please_fill_in_the_code']?></font></em>
				</div>
			  </div>
				  <div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['mobile']?></label>
					<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
					  <input size="20" name="data[address][mobile]" id="address_mobile"   onKeyUp="is_int(this);"  value="<?php echo isset($address['UserAddress']['mobile'])?$address['UserAddress']['mobile']:'';?>" type="text">
					  <em style="top:auto;"><font color="red">*</font><?php echo $ld['mobile_and_fixed_telephone_at_least_one_required']?></em>
				   </div>
			  </div>
			  <div class="am-form-group">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"></label>
				<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
				  <a class="buyaddsub am-btn am-btn-primary am-btn-sm" href="javascript:check_address();void(0); "><span style="margin:-1px 0 0 0;"><?php echo $ld['submit_shipping_address']?></span></a>
				</div>
			  </div>
			</div>
		  </div>
		</div>
		<?php echo $form->end();?>
		
	</div>
</div>
<style type="text/css">
.cart_address,.cart_shipping{font-size:15px;}
</style>
<!-- Cart Address End -->
<script type="text/javascript">
var user_address_data=<?php $user_address_data=isset($address['UserAddress'])?$address['UserAddress']:array();echo json_encode($user_address_data); ?>;
load_region(user_address_data);

var cart_shipping_code="<?php echo isset($cart_shipping_data['shipping_code'])?$cart_shipping_data['shipping_code']:''; ?>";
cart_address_init("<?php echo isset($cart_shipping_data['shipping_id'])?$cart_shipping_data['shipping_id']:0; ?>",cart_shipping_code);

$(function(){
	$(".cart_address_add").click(function(){
		$("#cart_address_form").show();
		reload_region($("#regionsupdate select:eq(0)")[0]);
	});
});

function shipping_check(){
	var shipping=i=0;
	while(true){
		if(document.getElementById('shipping_'+i)==null){
			break;
		}
		if(document.getElementById('shipping_'+i).checked){
			shipping = 1;
		}
		i++;
	}
	if(shipping == 0){
		alert("<?php echo $ld['select_shipping_method']?>");
		return false;
	}
	$.ajax({
            type: "POST",
            url: web_base+"/carts/ajax_check_shipping",
            dataType: 'json',
            data: $("#cart_shipping_form").serialize(),
            success: function (result) {
            		if(result.code=='1'){
            			var shipping_id=result.cart_shipping['shipping_id'];
            			cart_shipping_code=result.cart_shipping['shipping_code'];
            			cart_address_init(shipping_id,cart_shipping_code);
            			var default_address=$(".cart_address table").length;
            			if(default_address>0)window.location.href=web_base+"/carts/checkout";
            		}
            		alert(result.msg);
            }
        });
}

function cart_address_init(shipping_id,shipping_code){
	if(shipping_code=='cac'){
		$(".cart_shipping_address input[type='text']").val('');
		$(".cart_shipping_address").hide();
	}else{
		$(".cart_shipping_address").show();
	}
}

function check_address(){
	var address_consignee = document.getElementById('address_consignee');
	var address_mobile = document.getElementById('address_mobile');
	var user_tel0 = document.getElementById('user_tel0');
	
	if(address_consignee.value==''){
		alert('<?php echo $ld['consignee_not_empty']; ?>');
		return false;
	}
    if(document.getElementById('regionsupdate')&&cart_shipping_code!='cac'){
        var regionsupdate = document.getElementById('regionsupdate');
		if(regionsupdate){
			var select = regionsupdate.getElementsByTagName('select');
			//var sPost = Array();
			for(var i=0;i<select.length;i++){
				if(select[i].value=='<?php echo $ld['please_select']?>'){
					alert('<?php echo $ld['select_a_region']?>');
					return false;
				}
			}
		}
    }
    if(document.getElementById('address_address')&&cart_shipping_code!='cac'){
        var address_address = document.getElementById('address_address');
		if(address_address.value==''){
			alert('<?php echo $ld['address_empty']?>');
			return false;
		}
    }
	if(address_mobile.value=='' && user_tel0.value==''){
		alert('<?php echo $ld['mobile_and_fixed_telephone_at_least_one_required']?>');
		return false;
	}else{
		if(user_tel0.value!=''){
			var reg_telephone = /^((0\d{2,4})-)(\d{7,8})/;
			if(!reg_telephone.test(user_tel0.value)){
				alert("电话号码不合法！");
				return false;
			}
		}
		if(address_mobile.value!=''){
			var reg_mobile = /^1[3-9]\d{9}$/;
			if(!reg_mobile.test(address_mobile.value)){
				alert("手机号码不合法！");
				return false;
			}
		}
	}
	document.forms['edit_address_act_update'].submit();
}

function confirm_address(linkobj,regions,address){
	if(cart_shipping_code!="cac"){
		if(regions==''){
			alert('<?php echo $ld['select_a_region']?>');
			return false;
		}
		if(address==''){
			alert('<?php echo $ld['address_empty']?>');
			return false;
		}
	}
	return true;
}
</script>