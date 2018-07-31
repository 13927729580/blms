
<?php
	echo $htmlSeevia->js(array('region'));
?>
<?php //加载css
		
echo $htmlSeevia->css(array('common'));?>	
<div class="checktitle" style="display:none;">
	<h1><?php echo $ld['settlement_procedure']?>: 1.<?php echo $ld['login_or_register']?><i></i><em>2.<?php echo $ld['select_order']?></em><i></i>3.<?php echo $ld['fill_order']?><i></i>4.<?php echo $ld['order_submit']?></h1>
</div>
<input type='hidden' id='local' value="<?php echo LOCALE;?>">
<div class="am-container" style="padding-top:15px;">

<div class="am-panel am-panel-default shipping_method">
	<div class="am-panel-hd am-padding-right-0"><strong class="am-text-primary am-text-lg"><?php echo $ld['shipping_method']?></strong>
		<?php echo $html->link($ld['modify'],"/carts/check_shipping/",array('class'=>'am-btn am-btn-secondary am-btn-sm am-fr','style'=>'position:relative;top:-2px'));?>
	</div>
	<div class="am-panel-bd am-padding-top-0 am-padding-bottom-0">
		<ul class="am-list am-list-static">
			<li  style="border-top-style:none"><?php echo $_SESSION['checkout']['shipping']['shipping_name'];?></li>
			<?php if(isset($_SESSION['checkout']['shipping']['shipping_code'])&&$_SESSION['checkout']['shipping']['shipping_code']=='cac'){?>
			<li><?php echo $_SESSION['checkout']['shipping']['shipping_description'];?></li>
			<?php }?>
			<?php if(isset($_SESSION['checkout']['remark'])&&$_SESSION['checkout']['remark']!=""){?>
			<li><?php echo $ld['remarks']?>:&nbsp;<?php echo $_SESSION['checkout']['remark'];?>
			</li>
			<?php }?>
		</ul>
	</div>
</div>
<?php if(!empty($addresses)) {?>
<div class="am-panel am-panel-default am-address">
  <div class="am-panel-hd am-padding-right-0"><strong class="am-text-primary am-text-lg"><?php echo $ld['account_address_book']?></strong>
	<?php if(!(isset($configs['vip-address-num'])&& $configs['vip-address-num']!="" && $add_num>=$configs['vip-address-num'])){?>
	  <?php echo $html->link($ld['add_address'],"/carts/check_address#addaddress",array("style"=>'text-decoration: none;width:140px;position:relative;top:-2px',"class"=>"am-btn am-btn-secondary am-btn-sm am-fr"))?>
	<?php }?>
  </div>
  <div class="am-panel-bd">
	<table>
	<?php foreach($addresses as $k=>$v) {?>
	  <tr>
		<td><?php echo $ld['consignee']?>:&nbsp;</td>
		<td><?php if(isset($v['UserAddress']['consignee'])&&!empty($v['UserAddress']['consignee']))echo $v['UserAddress']['consignee'];?></td>
		<td>&nbsp;&nbsp;
		  <?php echo $html->link($ld['modify'],"/carts/check_address/".$v['UserAddress']['id']."/#addaddress",array("class"=>"am-btn am-btn-default am-btn-sm"),null,null,false);?>
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
		<td colspan="2" style="padding:10px 0;"><?php echo $html->link($ld['delivery_to_this_address'],"/carts/confirm_address/".$v['UserAddress']['id'].'/1',array("class"=>"buatsub am-btn am-btn-primary am-btn-sm"),false,false)?></td>
	  </tr>
	<?php }?>
	</table>
  </div>
</div>
<?php }?>
<?php echo $form->create('carts',array('action'=>'edit_address_act','id'=>'addaddress','class'=>'am-form  am-form-horizontal','name'=>'edit_address_act_update','type'=>'POST','onsubmit'=>'return check_address();'));?>
<div class="am-panel am-panel-default">
  <div class="am-panel-hd"><strong class="am-text-primary am-text-lg"><?php echo $ld['contact_information']?></strong></div>
  <div class="am-panel-bd">

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
      <?php if(!isset($_SESSION['checkout']['shipping'])||(isset($_SESSION['checkout']['shipping']['shipping_code'])&&$_SESSION['checkout']['shipping']['shipping_code']!='cac')){ ?>
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld["region"]?></label>
		<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
		  <input type='hidden' id='local' value="<?php echo LOCALE;?>">
			<span id="regionsupdate" >
				<select>
					<option></option>
				</select>
				<i><?php echo $ld['state_province']?>: </i>
				<select>
					<option></option>
				</select>
				<i><?php echo $ld['city']?>: </i>
				<select>
					<option></option>
				</select>
				<i><?php echo $ld['county_district']?>: </i>
				<select>
					<option></option>
				</select>
			</span><!--<em><font color="red">*</font><font></font></em>-->
		</div>
	  </div>
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
		<div class="am-u-lg-10 am-u-md-9 am-u-sm-9"><?php echo $ld['mark_of_delivery_services']?></div>
	  </div>
	  <div class="am-form-group" >
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
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['address_to']?></label>
		<div class="am-u-lg-10 am-u-md-9 am-u-sm-9"><input type="text" name="data[address][sign_building]" id="address_sign_building" class="w_111" onpropertychange='limitTxtByte(this,100)' oninput='limitTxtByte(this,100)' value="<?php echo isset($address['UserAddress']['sign_building'])?$address['UserAddress']['sign_building']:'';?>" ></div>
	  </div>
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['zip']?></label>
		<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
		  <input size="6" name="data[address][zipcode]" id="address_zipcode" maxlength="6" value="<?php echo isset($address['UserAddress']['zipcode'])?$address['UserAddress']['zipcode']:'';?>" type="text">
		</div>
	  </div>
      <?php } ?>
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['telephone']?>: </label>
		<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
		  <input type="text" name="user_tel0" id="user_tel0" size="20"  value="<?php if(isset($address['UserAddress']['telephone'])) {echo $address['UserAddress']['telephone'];}?>"/>
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
<div class="checkout am-contact am-container" style="padding:30px;" >
</div>	

<style type="text/css">
/*.module_content,.checkout{max-height:800px;}*/
.am-form select, .am-form textarea, .am-form input[type="text"], .am-form input[type="password"],
 .am-form input[type="datetime"], .am-form input[type="datetime-local"], 
 .am-form input[type="date"], .am-form input[type="month"], .am-form input[type="time"],
  .am-form input[type="week"], .am-form input[type="number"],
   .am-form input[type="email"], .am-form input[type="url"],
    .am-form input[type="search"], .am-form input[type="tel"], 
    .am-form input[type="color"], .am-form-field{width:80%;float:left;}

.am-form-detail select {
    display: inline;
    margin-right: 5px;
    position: relative;
    width: auto;
}
.am-address tr td:first-child{padding-left:1.3rem;}
.am-address tr td{font-size:1.5rem;}

</style>			
<script type="text/javascript">
	<?php if(isset($address['UserAddress']['regions'])) {?>
		var regions_add = <?php echo "'".$address['UserAddress']['regions']."'"?>;
	<?php }else {?>
		var regions_add =  '';
	<?php }?>
    <?php if(!isset($_SESSION['checkout']['shipping'])||(isset($_SESSION['checkout']['shipping']['shipping_code'])&&$_SESSION['checkout']['shipping']['shipping_code']!='cac')){ ?>
	show_two_regions(regions_add);
    <?php }?>
        
	function check_address(){
		var address_consignee = document.getElementById('address_consignee');
		var address_mobile = document.getElementById('address_mobile');
		var user_tel0 = document.getElementById('user_tel0');
		if(address_consignee.value==''){
			alert('<?php echo $ld['consignee_not_empty']?>');
			return false;
		}
        if(document.getElementById('regionsupdate')){
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
        if(document.getElementById('address_address')){
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


</script>
<script type="text/javascript">
<?php if(isset($address['UserAddress']['regions'])) {?>

		var regions_add = <?php echo "'".$address['UserAddress']['regions']."'"?>;
		//alert(regions_add);
	<?php }else {?>
		var regions_add =  '';
	<?php }?>
		//show_two_regions(regions_add);
	<?php
		//echo "alert(".$swap['y']['RegionI18n']['region_id'].")";

		if(!empty($_SESSION['swap'])) {
			if(isset($_SESSION['swap']['y']['RegionI18n']['region_id']))
				$y=$_SESSION['swap']['y']['RegionI18n']['region_id'];
			else
				$y='';
			if(isset($_SESSION['swap']['z']['RegionI18n']['region_id']))
				$z=$_SESSION['swap']['z']['RegionI18n']['region_id'];
			else
				$z='';
			//$xyz=
			echo "show_two_regions('".$_SESSION['swap']["x"]." ".$y." ".$z."')";
		}
	?>
</script>
