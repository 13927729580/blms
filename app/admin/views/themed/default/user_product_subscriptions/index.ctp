<?php echo $form->create('user_product_subscriptions',array('class'=>'am-form','action'=>'/','id'=>'','name'=>'','type'=>'get'));?>
	<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
		<li  class="am-margin-top-xs">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['brand'] ?></label>
 			<div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
  			 <select data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['please_select'] ?>'}" multiple name="brand_id[]" >
   			<?php if (isset($brand_data)&&!empty($brand_data)) {foreach ($brand_data as $k => $v) {?>
        	<option <?php echo isset($brand_id) && in_array($v['Brand']['id'],$brand_id)?'selected':''; ?> value="<?php echo $v['Brand']['id'] ?>"><?php echo $v['BrandI18n']['name'] ?></option>
			<?php }}else{ ?>
				 <option value=""><?php echo $ld['please_select'] ?></option>
			<?php } ?>
    		 </select>
  			</div>
		</li>
		<li  class="am-margin-top-xs">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['classification'] ?></label>
			<div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
				<select name="category_id[]" multiple data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['please_select'] ?>'}">
				<?php if(isset($category_data)&&!empty($brand_data)){foreach ($category_data as $k => $v) { ?>
  				<option <?php echo isset($category_id) && in_array($v['CategoryProduct']['id'],$category_id)?'selected':''; ?> value="<?php echo $v['CategoryProduct']['id'] ?>"><?php echo $v['CategoryProductI18n']['name'] ?></option>
  				<?php if (isset($v['SubCategory'])&&sizeof($v['SubCategory'])>0) {foreach ($v['SubCategory'] as $kk => $vv) { ?>
				<option <?php echo isset($category_id) && in_array($vv['CategoryProduct']['id'],$category_id)?'selected':''; ?> value="<?php echo $vv['CategoryProduct']['id'] ?>">--<?php echo $vv['CategoryProductI18n']['name'] ?></option>
  				<?php }} ?>
				<?php }}else{ ?>
				 <option value=""><?php echo $ld['please_select'] ?></option>
				<?php } ?>
		  		</select>
			</div>
		</li>
		<li  class="am-margin-top-xs">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['product_type'] ?></label>
			<div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
				<select multiple  name="product_type_id[]" data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['please_select'] ?>'}" id="attribute_group">
				<?php if(isset($product_type_data)&&!empty($brand_data)){foreach ($product_type_data as $k => $v) { ?>
  				<option <?php echo isset($product_type_id) && in_array($v['ProductType']['id'],$product_type_id)?'selected':''; ?> value="<?php echo $v['ProductType']['id'] ?>" ><?php echo $v['ProductTypeI18n']['name'] ?></option>
				<?php }}else{ ?>
				 <option value=""><?php echo $ld['please_select'] ?></option>
				<?php } ?>
		  		</select>
			</div>
		</li>
		<li class="am-margin-top-xs">
		 <label class="am-u-lg-3  am-u-md-3  am-u-sm-4 am-form-label-text  "><?php echo $ld['time_slot'] ?></label>
		 <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
			<select name="send_time" data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['please_select'] ?>'}">
				<?php if (isset($informationresource_infos['product_subscription'])&&sizeof($informationresource_infos['product_subscription'])>0) {foreach ($informationresource_infos['product_subscription'] as $k => $v) { ?>
				<option <?php if (isset($send_time) && $k== $send_time) { echo "selected" ;} ?> value="<?php echo $k ?>"><?php echo $v ?></option>
				<?php }}else{ ?>
				 <option value=""><?php echo $ld['please_select'] ?></option>
				<?php } ?>
			</select>
		 </div>
		</li>
		<li class="am-margin-top-xs">
    <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['status'] ?></label>
    	<div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
      		<select data-am-selected="{noSelectedText:'<?php echo $ld['please_select'] ?>'}" name="status">
        	<option value=""><?php echo $ld['please_select'] ?></option>
        	<option <?php if (isset($status)&& $status == 1 ) { echo "selected" ;} ?> value="1"><?php echo $ld['yes'] ?></option>
        	<option <?php if (isset($status)&& $status == 0 ) { echo "selected" ;} ?> value="0"><?php echo $ld['no'] ?></option>
      		</select>
    	</div>
  		</li>
		<li  class="am-margin-top-xs">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['keyword'] ?></label>
 			<div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
  			<input type="text" name="keyword" value="<?php echo @$keyword ?>" class="am-form-field am-input-sm">
  			</div>
		</li>
		<li class="am-margin-top-xs">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">&nbsp;</label>
			<div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
			<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius"><?php echo $ld['search'] ?></button>
			</div>
		</li>
	</ul>
<?php echo $form->end();?>

<form id="remove_product_subscriptions" action="/">
<?php if($svshow->operator_privilege("product_subscription_add")) { ?>
<div class="am-cf"><a href="<?php echo $html->url('/user_product_subscriptions/view/0') ; ?>" class="am-btn am-fr am-btn-xs am-btn-warning"><span class="am-icon-plus"></span><?php echo $ld['add'] ?></a></div>
<?php } ?>
<div class="subscriptions-list am-panel-group">
	<div class="am-cf subscriptions_list_title am-panel-hd" style="border-bottom:2px solid #ddd">
		<div class="am-u-sm-2">
			<label class="am-checkbox am-success am-margin-0">
				<input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/>
				<?php echo $ld['user_name'] ?>
			</label>
		</div>
		<div class="am-u-sm-2"><?php echo $ld['real_name'] ?></div>
		<div class="am-u-sm-2"><?php echo $ld['subscription_name'] ?></div>
		<div class="am-u-sm-2"><?php echo $ld['send_time'] ?></div>
		<div class="am-u-sm-2"><?php echo $ld['status'] ?></div>
		<div class="am-u-sm-2"><?php echo $ld['edit'] ?></div>
	</div>
<?php if (isset($subscription_data)&&sizeof($subscription_data)>0) {foreach ($subscription_data as $k => $v) { ?>
	<div class="am-cf am-panel-bd" style="border-bottom:1px solid #ddd">
		<div class="am-u-sm-2">
			<label class="am-checkbox am-success am-margin-0">
				<input type="checkbox" name="checkboxes[]" value="<?php echo $v['UserProductSubscription']['id']?>" data-am-ucheck />
				<?php echo $v['User']['name'] ?>
			</label></div>
		<div class="am-u-sm-2"><?php echo $v['User']['first_name'] ?>&nbsp;</div>
		<div class="am-u-sm-2"><?php echo $v['UserProductSubscription']['name'] ?>&nbsp;</div>
		<div class="am-u-sm-2"><?php echo isset($informationresource_infos['product_subscription'][$v['UserProductSubscription']['send_time']])?$informationresource_infos['product_subscription'][$v['UserProductSubscription']['send_time']]:'-' ?>&nbsp;</div>
		<div class="am-u-sm-2">
			 <?php if ($v['UserProductSubscription']['status'] == 0) { ?>
      			<span style="cursor:pointer;" onclick="change_state(this,'user_product_subscriptions/toggle_on_status',<?php echo $v['UserProductSubscription']['id'];?>)" class="am-icon-close am-no"></span>
      		<?php } ?>
     		<?php if($v['UserProductSubscription']['status'] == 1) { ?>
      			<span style="cursor:pointer;" onclick="change_state(this,'user_product_subscriptions/toggle_on_status',<?php echo $v['UserProductSubscription']['id'];?>)" class="am-icon-check am-yes"></span>
      		<?php } ?>&nbsp;
		</div>
		<div class="am-u-sm-2">
			<?php if($svshow->operator_privilege("product_subscription_edit")) { ?>
			<a class="am-btn am-btn-default am-btn-xs am-text-secondary" href="<?php echo $html->url('/user_product_subscriptions/view/'.$v['UserProductSubscription']['id']); ?>" ><span class="am-icon-pencil-square-o"></span>&nbsp;<?php echo $ld['edit'] ?></a>
			<?php } ?>
			<?php if($svshow->operator_privilege("product_subscription_remove")) { ?>
			<a href="javascript:void(0);" onclick="subscriptions_remove('<?php echo $v['UserProductSubscription']['id'] ?>')" class="am-btn am-btn-default am-btn-xs am-text-danger">&nbsp;<span class="am-icon-trash-o"></span><?php echo $ld['delete']; ?></a>
			<?php } ?>
		</div>
	</div>
<?php }} ?>
</div>

<?php echo $this->element('pagers') ?>
</form>
<script>

	function subscriptions_remove (id) {
		if (confirm(js_confirm_deletion)) {
		window.location.href = admin_webroot+"/user_product_subscriptions/remove/"+id;
	}
}

function change_state(obj,func,id){
    var ClassName=$(obj).attr('class');
    var val = (ClassName.match(/yes/i)) ? 0 : 1;
    var postData = "status="+val+"&id="+id;
    $.ajax({
        url:admin_webroot+func,
        type:"POST",
        dataType:"json",
        data:postData,
        success:function(data){
        	console.log(data);
            if(data.flag == 1){
                if(val==0){
                    $(obj).removeClass("am-icon-check am-yes");
                    $(obj).addClass("am-icon-close am-no");
                }
                if(val==1){
                    $(obj).removeClass("am-icon-close am-no");
                    $(obj).addClass("am-icon-check am-yes");
                }
            }

        }
    });
}

	// function attribute () {

	//   $("#attribute_optgroup").html('');
	//   var attribute_option = $("#attribute_group option:gt(0)");
	//   var array_id = [];
	//   for(var i = 0;i<attribute_option.length;i++){
	//   	var attribute_option_attr = attribute_option[i].selected
	//   	if (attribute_option_attr) {
	//   		array_id.push(attribute_option[i].value);
	//   	};
	//   }
	//   var post_data = array_id.toString();
	//   $.ajax({
	//   	url:admin_webroot+"/user_product_subscriptions/ajax_product_attribute",
	//   	dataType:"json",
	//   	type:"POST",
	//   	data:{"product_type_id":post_data},
	//   	success:function (data) {
	//   		if (data.code == 1) {
	//   			$.each(data.data,function (index,content) {
	//   				var optgroup_option = ""
	//   				$.each(content.AttributeOption,function (ind,con) {
	//   						optgroup_option+='<option value="'+con.attribute_id+":"+con.option_value+'">'+con.option_name+'</option>';
	//   				})
	//   				if (optgroup_option != '') {
	//   					var optgroup_title = '<optgroup label="'+content.AttributeI18n.name+'">'+optgroup_option+'</optgroup>';
	//   					$("#attribute_optgroup").append(optgroup_title);
	//   				}
	//   			})
	//   		}
	//   	}
	//   })
	// }


// 	  function diachange(){
//   var a=document.getElementById("superuser_type");
//   if(a.value!='0'){
//     for(var j=0;j<a.options.length;j++){
//       if(a.options[j].selected){
//         var vals = a.options[j].text ;
//       }
//     }
//     var id=document.getElementsByName('checkboxes[]');
//     var i;
//     var j=0;
//     var image="";
//     for( i=0;i<=parseInt(id.length)-1;i++ ){
//       if(id[i].checked){
//         j++;
//       }
//     }
//     if( j>=1 ){
//     //  layer_dialog_show('确定'+vals+'?','batch_action()',5);
//       if(confirm("<?php echo $ld['submit']?>"+vals+'?'))
//       {
//         batch_action();
//       }
//     }else{
//     //  layer_dialog_show('请选择！！','batch_action()',3);
//       if(confirm(j_please_select))
//       {
//         batch_action();
//       }
//     }
//   }
// }

// function batch_action()
// {
// var post_data = $("#product_subscriptions").serializeArray();
// $.ajax({
//   url:admin_webroot+"user_product_subscriptions/remove",
//   dataType:"json",
//   type:"POST",
//   data:post_data,
//   success:function (data){
//     if (data.flag == 1) {
//       alert(data.message);
//       window.location.href = window.location.href;
//     }else{

//     }
//   }
// })
// }
</script>