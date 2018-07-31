<?php
    if($action_code!='product_style_change'){
    	//pr($order_product_value_data);
?>
<style type="text/css">
.print_attr_btn_div{float:right;position: relative;right: 25px;top: -28px;}
select[id*='attr_edit_value']{padding:0.125em}
</style>
<?php   
	$media_list_condition_co = array();
    $media_list_condition_add = array();
    foreach ($media_condition_list as $kkk2 => $vvv2) {               
            $media_list_condition_co[]=$vvv2;
    }
    if(count($media_condition_list)>=1){
    	$len2 = 4- (count($media_condition_list))%4;
    }else{
    	$len2 = 4;
    }
    
    if(($len2 == 4 && count($media_condition_list) == 0)||($len2 == 4 && count($media_condition_list) == 1)||$len2 != 4){
    	for($x = 0;$x < $len2;$x++){
        	$media_list_condition_add[] = '';
    	}
    }
?>
<div style="margin-top:1rem;">
<form action="" id="clothes_condition">
	<div class="am-form-group">
	<?php //pr($media_list_condition_co); ?>
		<div class="am-u-lg-3">衣物状况</div>
		
		<ul class="am-u-lg-8 am-avg-sm-4 am-thumbnails" style="list-style:none;background-color:#F8F8F8;" id="clo_con">
			<?php foreach ($media_list_condition_co as $kkk3 => $vvv3) { ?>
				<li class="am-thumbnail" style="border:none;background-color:#F8F8F8;font-size:12px;">
					<input type="hidden" name="data[OrderProductMedia][id][]" value="<?php echo isset($vvv3['OrderProductMedia']['id'])?$vvv3['OrderProductMedia']['id']:'0'; ?>">
					<input type="hidden" name="data[OrderProductMedia][media][]" value="<?php echo isset($vvv3['OrderProductMedia']['media'])?$vvv3['OrderProductMedia']['media']:''; ?>">
					<input type="hidden" name="data[OrderProductMedia][media_group][]" value="<?php echo isset($vvv3['OrderProductMedia']['media_group'])?$vvv3['OrderProductMedia']['media_group']:1; ?>">
					<input type="file" onchange="ajax_upload_media_attr_group(this)" style="display:none;" />
					<img src="<?php echo isset($vvv3['OrderProductMedia']['media'])?$vvv3['OrderProductMedia']['media']:'/media/order_product_media/order-pro-add.png'; ?>" alt="" style="width:60px;height:60px;margin-left:0;" id="<?php echo isset($vvv3['OrderProductMedia']['id'])&&$vvv3['OrderProductMedia']['id']!=''?$vvv3['OrderProductMedia']['id']:''; ?>" onclick="up_img(this)">
					<select name="data[OrderProductMedia][location][]" style="width:60px;margin-top:5px;">
						<option value="">请选择</option>
						<option value="1" <?php if($vvv3['OrderProductMedia']['location'] == 1){ ?>selected<?php } ?> >胸前第3颗纽扣</option>
						<option value="2" <?php if($vvv3['OrderProductMedia']['location'] == 2){ ?>selected<?php } ?> >位置2</option>
						<option value="3" <?php if($vvv3['OrderProductMedia']['location'] == 3){ ?>selected<?php } ?> >位置3</option>
					</select>
					<textarea cols="2" name="data[OrderProductMedia][description][]"  style="width:60px;margin-top:5px;resize: none;"><?php echo isset($vvv3['OrderProductMedia']['description'])?$vvv3['OrderProductMedia']['description']:''; ?></textarea>
				</li>
			<?php } ?>
			<?php foreach ($media_list_condition_add as $kkk4 => $vvv4) { ?>
				<li class="am-thumbnail" style="border:none;background-color:#F8F8F8;font-size:12px;">
					<input type="hidden" name="data[OrderProductMedia][id][]" value="">
					<input type="hidden" name="data[OrderProductMedia][media][]" value="<?php echo isset($vvv4['OrderProductMedia']['media'])?$vvv4['OrderProductMedia']['media']:''; ?>">
					<input type="hidden" name="data[OrderProductMedia][media_group][]" value="<?php echo isset($vvv4['OrderProductMedia']['media_group'])?$vvv4['OrderProductMedia']['media_group']:1; ?>">
					<input type="file" onchange="ajax_upload_media_attr_group(this)" style="display:none;" />
					<img src="<?php echo isset($vvv4['OrderProductMedia']['media'])?$vvv4['OrderProductMedia']['media']:'/media/order_product_media/order-pro-add.png'; ?>" alt="" style="width:60px;height:60px;margin-left:0;" id="<?php echo isset($vvv4['OrderProductMedia']['id'])&&$vvv4['OrderProductMedia']['id']!=''?$vvv4['OrderProductMedia']['id']:''; ?>" onclick="up_img(this)">
					<select name="data[OrderProductMedia][location][]" style="width:60px;margin-top:5px;">
						<option value="">请选择</option>
						<option value="1">胸前第3颗纽扣</option>
						<option value="2">位置2</option>
						<option value="3">位置3</option>
					</select>
					<textarea cols="2" name="data[OrderProductMedia][description][]" value="<?php echo isset($vvv3['OrderProductMedia']['description'])?$vvv3['OrderProductMedia']['description']:''; ?>" style="width:60px;margin-top:5px;resize: none;"></textarea>
				</li>
			<?php } ?>
		</ul>
		<div class="am-u-lg-1">
			<img src="/media/order_product_media/order-pro-more.png" alt="" style="width:60px;height:60px;" onclick="add_condition()">
		</div>
	</div>
</form>
<div class="am-cf"></div>
</div>
<?php echo $form->create('orders',array('action'=>'print_attr_value','target'=>'_blank',"id"=>"order_product_attr_from"));?>
<input type="hidden" name="data[order_id]" value="<?php echo isset($order_id)?$order_id:0 ?>" >
<input type="hidden" name="data[pro_Id]" value="<?php echo isset($pro_Id)?$pro_Id:0 ?>" >
<input type="hidden" name="data[order_product_id]" id="order_product_id" value="<?php echo isset($order_product_id)?$order_product_id:0 ?>" >
<input type="hidden" name="data[order_product_code]" id="order_product_code" value="<?php echo isset($order_pro_info['product_code'])?$order_pro_info['product_code']:'' ?>" >
<input type="hidden" name="data[user_id]" value="<?php echo $user_id; ?>" >
<input type="hidden" name="data[product_type_id]" value="<?php echo $pro_Info['Product']['product_type_id'] ?>" >
<input type="hidden" name="data[saveflag]" id="saveflag" value="default_save" >
<input type="hidden" name="data[user_style_id]" id="user_style_id" value="<?php echo isset($order_pro_info['user_style_id'])?$order_pro_info['user_style_id']:0 ?>" >
<div id="basic_information" class="am-panel-collapse am-collapse am-in" style="z-index: 0;">
  
  <div class="am-form-detail am-form am-form-horizontal" >
	<?php if(isset($user_style_list)&&sizeof($user_style_list)>0){ ?>
	<div class="am-form-group">
	  <label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label"><?php echo $ld['user_template'] ?></label>
	  <div class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label"></div>
	  <div class="am-u-lg-6 am-u-md-5 am-u-sm-4">
	    <select id="user_style_list" name="data[user_style_id]" <?php echo $action_code=='attr_view'?"disabled":'' ?> onchange="overload_userstyleattrvalue(this.value)">
	    <option value="0"><?php echo $ld['please_select'] ?></option>
		<?php foreach($user_style_list as $k=>$v){ ?>
		  <option value="<?php echo $v['UserStyle']['id']; ?>" <?php echo isset($order_pro_info['user_style_id'])&&$order_pro_info['user_style_id']==$v['UserStyle']['id']?" selected='selected'":'' ?>><?php echo $v['UserStyle']['attribute_code'].' - '.$v['UserStyle']['user_style_name']; ?></option>
		<?php } ?>
		</select>
	  </div>
	</div>
	<?php } ?>
    
    <div class="am-form-group buy_attr_list">
		<?php if(isset($buy_attr)&&sizeof($buy_attr)>0){foreach($buy_attr as $k=>$v){ ?>
		<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label"><?php echo $v; ?>:</label>
        <label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label">&nbsp;</label>
		<div class="am-u-lg-5 am-u-md-5 am-u-sm-3">
		  <?php if(isset($att_sel_list[$k])&&sizeof($att_sel_list[$k])>0){ ?>
		    <?php if($action_code=='attr_view'){
		    	foreach($att_sel_list[$k] as $kk=>$vv){
					if(isset($order_product_attr_info[$k])&&$order_product_attr_info[$k]==$vv){
						echo "<label class='am-form-label'>".$vv."</label>";
					}
				}
		    } ?>
		  <select <?php echo $action_code=='attr_view'?"style='display:none;'":''; ?> onchange="get_order_pro_code('<?php echo isset($order_pro_info['id'])&&isset($order_pro_info['product_style_id'])?$order_pro_info['product_style_id']:0; ?>')" alt="<?php echo $k; ?>">
		    <?php foreach($att_sel_list[$k] as $kk=>$vv){ ?>
			<option value="<?php echo $vv; ?>"><?php echo $vv; ?></option>
		    <?php } ?>
		  </select>
		  <?php } ?>
		</div>
		<?php }} ?>
      <div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="text-align:left;"><?php echo $ld['units'] ?>:cm</div>
    </div>

    
    <div class="am-form-group">
	  <label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label">版型</label>
	  <div class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label"></div>
	  <div class="am-u-lg-6 am-u-md-5 am-u-sm-4 product_style_select">
        <select id="product_style_select" name="data[product_style_id]" <?php echo $action_code=='attr_view'?"disabled='disabled'":''; ?> onchange="product_style_change(this.value)">
            <option value="0"><?php echo $ld['please_select'] ?></option>
        </select>
	  </div>
	</div>

    <div class="am-form-group">
		<div class="am-g" id="product_attrInfo_editinfo"></div>
		<div class='am-g' id='order_customize_attribute'>
			<table class='am-table'>
			<?php if(isset($multiple_customize)&&sizeof($multiple_customize)>0){foreach($multiple_customize as $v){ ?>
				<?php //pr($v); ?>
				<tr>
					<th><?php echo $v['AttributeI18n']['name']; ?></th>
					<td><?php
						if(isset($v['AttributeOption'])&&sizeof($v['AttributeOption'])>0){foreach($v['AttributeOption'] as $vv){
					?>
					<?php //pr($vv); ?>
					<div class='am-g' id="<?php echo $vv['option_value']; ?>" >
						<input type="hidden" id="<?php echo $vv['attribute_id']; ?>">
						<label class='am-u-lg-4'>
							<?php echo $vv['option_name']; ?>
							<input type="hidden" value="<?php echo $vv['price'] ?>" class="<?php if(isset($order_product_value_data)&&count($order_product_value_data)>0){
								foreach ($order_product_value_data as $key2 => $value2) {
									$cod1 = explode(':',$value2['OrderProductValue']['attribute_value']);
									if($cod1[0]==$vv['option_value']){
										echo explode(" ",$cod1[1])[1];
									}else{
										echo '';
									}
								}
							} ?>">
						</label>
						<div class='am-u-lg-8'>
							<div style="width:22px;height:22px;border-radius:50%;background-color:#DE6A10;position:relative;cursor:pointer;display:none;" class="am-fl reduce-attr" onclick="reduce_attr(this)">
								<input type="hidden" value="<?php echo $vv['option_value'] ?>">
								<div style="width:18px;height:2px;background-color:#fff;position:absolute;top:10px;left:2px;"></div>
							</div>
							<input type="text" class="am-fl attr_num" style="width:34px;height:16px;margin-top:3px;padding-top:0;padding-bottom:0;margin-left:2px;margin-right:2px;text-align:center;padding-bottom:1px;display:none;" value="<?php if(isset($order_product_value_data)&&count($order_product_value_data)>0){
								foreach ($order_product_value_data as $key2 => $value2) {
									$cod1 = explode(':',$value2['OrderProductValue']['attribute_value']);
									if($cod1[0]==$vv['option_value']){
										echo explode(" ",$cod1[1])[0];
										
									}else{
										echo '';

									}
								}
							} ?>">
							<div style="width:22px;height:22px;border-radius:50%;background-color:#DE6A10;position:relative;cursor:pointer;" class="am-fl add-attr" onclick="add_attr(this)">
								<input type="hidden" value="<?php echo $vv['option_value'] ?>">
								<div style="width:18px;height:2px;background-color:#fff;position:absolute;top:10px;left:2px;"></div>
								<div style="width:2px;height:18px;background-color:#fff;position:absolute;top:2px;left:10px;"></div>
							</div>
						</div>
					</div>
					
					<?php
						}}
					?></td>
				</tr>
			<?php }} ?>
			</table>
		</div>
		<table class="am-table">
			<thead>
				<tr>
					<th style="width:15%;">编号</th>
					<th style="width:10%;">个数</th>
					<th style="width:15%;">小件/元</th>
					<th>修改幅度</th>
					<th>改后尺寸</th>
				</tr>
			</thead>
			<tbody id="p_info">
				<?php foreach ($order_product_value_data as $key1 => $value1) { ?>
					<?php  $cod = explode(":", $value1['OrderProductValue']['attribute_value']); $num = explode(" ",$cod[1]) ?>
					<tr class="<?php echo $cod[0] ?>">
						<td><?php if(isset($multiple_customize)&&sizeof($multiple_customize)>0){foreach ($multiple_customize as $v) {
							if(isset($v['AttributeOption'])&&sizeof($v['AttributeOption'])>0){foreach($v['AttributeOption'] as $vv){
								if($cod[0] == $vv['option_value']){
									echo $vv['option_name'];

								}
							}
						}
						}} ?>
						
					</td>
						<td>
							<?php echo $num[0] ?>
						</td>
						<td>
							<?php echo $value1['OrderProductValue']['attr_price'] ?>
						</td>
						<td>
							<input type="text" name="data[pro_type_attr_value][<?php echo $value1['OrderProductValue']['attribute_id'] ?>][<?php echo $cod[0] ?>][value][]" value="<?php echo isset($num[1])&&$num[1]!=''?explode(',',$num[1])[0]:''; ?>">
							<input type="hidden" name="data[pro_type_attr_value][<?php echo $value1['OrderProductValue']['attribute_id'] ?>][<?php echo isset($cod[0])&&$cod[0]!=''?$cod[0]:''; ?>][qty]" value="<?php echo isset($num[0])&&$num[0]!=''?$num[0]:''; ?>">
						</td>
						<td>
							<input type="text" name="data[pro_type_attr_value][<?php echo $value1['OrderProductValue']['attribute_id'] ?>][<?php echo isset($cod[0])&&$cod[0]!=''?$cod[0]:''; ?>][value][]" value="<?php echo isset($num[1])&&$num[1]!=''?explode(',',$num[1])[1]:''; ?>">
						</td>
					</tr>
                <?php } ?>
			</tbody>
		</table>
		<div class="am-cf"></div>
	<?php if($action_code!='attr_view'){ ?>
	  <div id="user_group" class="user_group">
		<table class="am-table">
		  <tr>
			<td width="20%"><?php echo $ld['remarks_notes'] ?></td>
			<td colspan="3"><textarea name="data[notes]" rows="5" cols="50" style="resize:none;"><?php echo isset($order_pro_info['note'])?$order_pro_info['note']:''; ?></textarea></td>
		  </tr>
		  <tr class="edit_attr">
			<td width="20%"><div class="am-form-label" style="padding-top:0.3em;">
				<input class="am-btn am-btn-success am-radius am-btn-sm" type="button" value="<?php echo '另存为';?>" onclick="save_user_style()" />
			  </div></td>
			<td colspan="3"><div class="save_user_style" style="display:none;">
				<select data-am-selected name="data[user_style_action]" id="user_style_action" onchange="set_user_style_action(this)">
				<option value="0"><?php echo $ld['add'] ?></option>
				<?php 
					foreach($user_style_list as $k=>$v){
				?>
					<option value="<?php echo $v['UserStyle']['id']; ?>" <?php echo isset($order_pro_info['user_style_id'])&&$order_pro_info['user_style_id']==$v['UserStyle']['id']?" selected='selected'":'' ?> ><?php echo $v['UserStyle']['attribute_code'].' - '.$v['UserStyle']['user_style_name']; ?></option>
				<?php } ?>
				</select></div>
			</td>
		  </tr>
		  <tr class="add_user_style" style="display:none;">
			<td width="20%" style="padding-top:0.2em;"><div class="am-form-label"><?php echo $ld['user_template'].$ld['name']; ?></div></td>
			<td width="30%"><input type="text" name="data[user_style_name]" id="user_style_name" value="<?php echo isset($user_style_data)?$user_style_data['UserStyle']['user_style_name']:''; ?>"  /></td>
			<td width="20%" style="padding-top:0.2em;"><div class="am-form-label"><?php echo $ld['log_set_default_template'] ?></div></td>
			<td width="20%">
			  <div class="am-form-label" style="text-align:left;padding-top:0.35em;">
				<input type="radio" class="user_style_default_status" value="1" name="data[default_status]" <?php echo isset($user_style_data['UserStyle']['default_status']) && $user_style_data['UserStyle']['default_status']=='1'?" checked='checked'":""; ?> /> <?php echo $ld['yes']?>&nbsp;&nbsp;
				<input type="radio" class="user_style_default_status" name="data[default_status]" value="0" <?php echo (!isset($user_style_data['UserStyle']['default_status'])) || (isset($user_style_data['UserStyle']['default_status']) && $user_style_data['UserStyle']['default_status']=='0')?" checked='checked'":""; ?> /> <?php echo $ld['no']?>
			  </div>
			</td>
		  </tr>
		  <tr class="save_user_style" style="display:none;">
			<td colspan="4" style="text-align:center;"><input class="am-btn am-btn-success am-radius am-btn-sm" type="button" value="<?php echo $ld['submit'];?>" onclick="order_product_attr_data_save('save_as')" /></td>
		  </tr>
		  <tr>
			<td colspan="5" align="center">
			  <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" value="<?php echo $ld['save'] ?>" onclick="order_product_attr_data_save('default_save')" />
			  <?php if(isset($order_product_id)&&$order_product_id!=0){ ?>
			  <input class="am-btn am-btn-success am-radius am-btn-sm" type="submit" id="print_attr_btn" value="<?php echo $ld['generate_amendments'] ?>" />
			  <?php } ?>
			</td>
		  </tr>
		</table>
	  </div>
	<?php } ?>
    </div>
  </div>
</div>



<script type="text/javascript">
$(function(){
    
<?php if(!isset($order_pro_info['id'])){ ?>//新增商品
    get_order_pro_code();
<?php }else{ ?>//编辑商品
    //get_order_pro_code("<?php echo isset($order_pro_info['product_style_id'])?$order_pro_info['product_style_id']:0; ?>");
<?php } ?>
    
})

function change_pro_type_attr_value(obj){
	var change_value=Number($(obj).val());
	var TR=$(obj).parent().parent();
	var hidinput=Number(TR.find("input[type='hidden']").val());
	TR.find("td:eq(3) span").html(hidinput+change_value);
	TR.find("td:eq(3) input").val(hidinput+change_value);
}

function get_order_pro_code(product_style_id){
	var select_ids=new Array();
	var select_values=new Array();
	$(".buy_attr_list div select").each(function(){
		var attr_id=$(this).attr("alt");
		var attr_val=$(this).val();
		select_ids.push(attr_id);
		select_values[attr_id]=attr_val;
	});
    if(typeof(product_style_id)=="undefined"){
        product_style_id=0;
    }
	$.ajax({url: admin_webroot+"orders/update_order_product_attr/get_order_pro_code",
			type:"POST",
			data:{
				order_product_code:"<?php echo $pro_Info['Product']['code'] ?>",
				attr_ids:select_ids,
				attr_values:select_values,
				product_type_id:"<?php echo $pro_Info['Product']['product_type_id'] ?>"
			},
			dataType:"json",
			success: function(data){
                $(".product_style_select").html("<select id='product_style_select' name='data[product_style_id]' onchange='product_style_change(this.value)'></select>");
                $("<option></option>").val('0').text(j_please_select).appendTo($("#product_style_select"));
                
				if(data.code=='1'){
					var pro_code=data.pro_code_info['Product'].code;
					$("#order_product_code").val(pro_code);
                    
                    var product_style_flag=false;
                    if(typeof(data.product_style_infos)!="undefined"){
                        $(data.product_style_infos).each(function(index,item){
                            if(product_style_id==item['ProductStyle']["id"]){
                            $("<option></option>").val(item['ProductStyle']["id"]).text(item['ProductStyleI18n']["style_name"]).attr('selected',true).appendTo($("#product_style_select"));
                                product_style_flag=true;
                            }else{
                                $("<option></option>").val(item['ProductStyle']["id"]).text(item['ProductStyleI18n']["style_name"]).appendTo($("#product_style_select"));
                            }
                        });
                    }
                    <?php if($action_code!='attr_view'){ ?>
                    $("#product_style_select").selected();
                    <?php }else{ ?>
                    $("#product_style_select").prop("disabled",true);
                    <?php } ?>
                    
                    if(product_style_flag){
                        product_style_change(product_style_id);
                    }else{
                        $("#product_attrInfo_editinfo").html('');
                    }
				}else{
					$("#order_product_code").val("");
                    $("#product_attrInfo_editinfo").html("");
				}
			}
	  	});
}


function product_style_change(product_style_id){
    var select_ids=new Array();
	var select_values=new Array();
	$(".buy_attr_list div select").each(function(){
		var attr_id=$(this).attr("alt");
		var attr_val=$(this).val();
		select_ids.push(attr_id);
		select_values[attr_id]=attr_val;
	});
    if(document.getElementById("user_style_list")){
        var user_style_id=document.getElementById("user_style_list").value;
    }else{
        var user_style_id=0;
    }
    if(product_style_id>0){
        $.ajax({url: admin_webroot+"orders/update_order_product_attr/product_style_change?page_action=<?php echo $action_code; ?>",
    			type:"POST",
    			data:{
                        order_id:"<?php echo isset($order_id)?$order_id:0 ?>",
                        order_product_id:"<?php echo isset($order_product_id)?$order_product_id:0 ?>",
    					product_style_id:product_style_id,
    					attr_values:select_values,
    					product_type_id:"<?php echo $pro_Info['Product']['product_type_id'] ?>",
                        user_style_id:user_style_id
    			},
    			dataType:"html",
    			success: function(data){
                    $("#product_attrInfo_editinfo").html(data);
                    <?php if($action_code!='attr_view'){ ?>
                    $("#product_attrInfo_editinfo select").selected();
                    <?php } ?>
    			}
    	  	});
    }else{
        $("#product_attrInfo_editinfo").html('');
    }
}

function change_attr_default_value(obj){
	var change_value=$(obj).val();
	var TR=$(obj).parent().parent();
	var hidinput=TR.find("input[type='hidden']").val();
	if(change_value!=""){
		TR.find("td:eq(3) span").html(change_value);
		TR.find("td:eq(3) input").val(change_value);
	}else{
		TR.find("td:eq(3) span").html(hidinput);
		TR.find("td:eq(3) input").val(hidinput);
	}
}

function overload_userstyleattrvalue(user_style_id){
	var user_id=$("#opener_select_user_id").val();
	if(user_id==""){
		alert('未找到用户!');return false;
	}
	if(user_style_id>0){
        $.ajax({url:admin_webroot+"orders/update_order_product_attr/get_pro_style",
                type:"POST",
                data:{
                    user_id:user_id,
                    user_style_id:user_style_id
                },
                dataType:"json",
                success:function(data){
                    if(data.code==1){
                        var attr_size=data.data['UserStyle']['attribute_code'];
                        $(".buy_attr_list select option[value='"+attr_size+"']").prop("selected",true);
                        get_order_pro_code(data.data['UserStyle']['style_id']);
                    }
                }
            })
    }
}
function add_sku_pro(saveflag){
	var order_product_code=$("#order_product_attr_from #order_product_code").val();
	if(order_product_code==""){return false;}
	var PostData={order_id:"<?php echo isset($order_id)?$order_id:0 ?>",order_product_code:order_product_code,order_product_id:"<?php echo isset($pro_Id)?$pro_Id:0 ?>"};
	$.ajax({url: admin_webroot+"orders/update_order_product_attr/add_order_product",
			type:"POST",
			data:PostData,
			dataType:"json",
			success: function(data){
				try{
					if(data.code==1){
						$("#order_product_id").val(data.last_order_product_id);
						save_order_product_attr_data(saveflag);
					}else{
						alert(j_object_transform_failed);
					}
				}catch (e){
					alert(j_object_transform_failed);
				}
	  		}
	  	});
}

function save_order_product_attr_data(saveflag){
	$("#saveflag").val(saveflag);
	if(saveflag=="save_as"){
		var user_style_action=$("#user_style_action").val();
		if(user_style_action=='0'){
			var UserStyleName=$("#user_style_name").val();
			if(UserStyleName==""){
				alert("用户模板名称不能为空");return false;
			}
		}
	}
	var PostData=$("#order_product_attr_from").serialize();
	$.ajax({url: admin_webroot+"orders/update_order_product_attr/data_save",
			type:"POST",
			data:PostData,
			dataType:"json",
			success: function(data){
				try{
					if(data.code==1){
						$(".am-close").click();
						order_reflash(data.hasproduct,data.total,data.need_pay);
					}else if(data.code==2){
						alert('保存成功');
						$(".save_user_style").css("display","none");
						$(".save_user_style input[type=text]").val("");
						$("#user_style_id").val(data.user_style_id);3
                            
                        if(typeof(data.user_style_list)!="undefined"){
                            $("#user_style_list option").remove();
                            $("<option></option>").val('0').text(j_please_select).appendTo($("#user_style_list"));
                            $(data.user_style_list).each(function(index,item){
                                $("<option></option>").val(item['UserStyle']["id"]).text(item['UserStyle']["attribute_code"]+"-"+item['UserStyle']["user_style_name"]).appendTo($("#user_style_list"));
                            });
                        }
                        $("#user_style_list option[value='"+data.user_style_id+"']").prop("selected",true);
                        $("#user_style_list").trigger('changed.selected.amui');
					}else{
						alert('保存失败');
					}
				}catch (e){
					alert(j_object_transform_failed);
				}
	  		}
	  	});
}

function order_product_attr_data_save(saveflag){
	var order_product_id=$("#order_product_id").val();
	if(order_product_id=='0'&&saveflag!="save_as"){
		add_sku_pro(saveflag);
	}else{
		se(saveflag);
		
	}
}

function save_user_style(){
	$(".save_user_style").css("display","");
	$("#user_style_action").change();
}

function set_user_style_action(obj){
	var action_id=$(obj).val();
	if(action_id=='0'){
		$(".add_user_style").css('display',"");
	}else{
		$(".add_user_style").css('display',"none");
	}
}
$(".attr_num").each(function(){
	if($(this).val()>0){
		$(this).show().siblings().show();
	}
});

function add_attr(obj){
   $(obj).siblings().show();
   var count = $(obj).siblings('input').val();
   count++;
   $(obj).siblings('input').val(count);
   var len = $("#p_info").find("."+$(obj).parent().parent().attr('id')).length;
   if(len<= '0'){
   	 $("#p_info").append('<tr class="'+$(obj).parent().parent().attr('id')+'"><td>'+$(obj).parent().siblings('label').html()+'</td><td>'+$(obj).siblings('input').val()+'</td><td>'+$(obj).siblings('input').val()*$(obj).parent().siblings('label').find('input').val()+'</td><td><input type="text" name="data[pro_type_attr_value]['+$(obj).parent().siblings('input').attr('id')+']['+$(obj).find('input').val()+'][value]" value="'+$(obj).parent().siblings('label').find('input').attr('class')+'" >'+'<input type="hidden" name="data[pro_type_attr_value]['+$(obj).parent().siblings('input').attr('id')+']['+$(obj).find('input').val()+'][qty]" value="'+count+'"></td></tr>');
   }else{
   		$("#p_info").find("."+$(obj).parent().parent().attr('id')).html('<td>'+$(obj).parent().siblings('label').html()+'</td><td>'+$(obj).siblings('input').val()+'</td><td>'+$(obj).siblings('input').val()*$(obj).parent().siblings('label').find('input').val()+'</td><td><input type="text" name="data[pro_type_attr_value]['+$(obj).parent().siblings('input').attr('id')+']['+$(obj).find('input').val()+'][value]" value="'+$(obj).parent().siblings('label').find('input').attr('class')+'" >'+'<input type="hidden" name="data[pro_type_attr_value]['+$(obj).parent().siblings('input').attr('id')+']['+$(obj).find('input').val()+'][qty]" value="'+count+'"></td>');
   }
}

function reduce_attr(obj){
	var count = $(obj).siblings('input').val();
   count--;
   $(obj).siblings('input').val(count);
   if($(obj).siblings('input').val() <= "0"){
   	$(obj).hide();
   	$(obj).siblings('input').hide();
   }
  
   if(count >= 1){
   	$("#p_info").find("."+$(obj).parent().parent().attr('id')).html('<td>'+$(obj).parent().siblings('label').html()+'</td><td>'+$(obj).siblings('input').val()+'</td><td>'+$(obj).siblings('input').val()*$(obj).parent().siblings('label').find('input').val()+'</td><td><input type="text" name="data[pro_type_attr_value]['+$(obj).parent().siblings('input').attr('id')+']['+$(obj).find('input').val()+'][value]" value="'+$(obj).parent().siblings('label').find('input').attr('class')+'" >'+'<input type="hidden" name="data[pro_type_attr_value]['+$(obj).parent().siblings('input').attr('id')+']['+$(obj).find('input').val()+'][qty]" value="'+count+'"></td>');
   }else{
   	$("#p_info").find("."+$(obj).parent().parent().attr('id')).remove();
   }
}

function up_img(obj){
	$(obj).siblings('input[type="file"]').click();	
}

function ajax_upload_media_attr_group(input_file){
	var files = input_file.files;
	var post_data = new FormData();
	var order_product_id = $("#order_product_id").val();
	var desc = $(input_file).siblings('input[type="text"]').val();
	//alert(order_product_id);
	if (files){
		  var file = files[0];
                var file_name=file.name;
                var reader = new FileReader();//新建一个FileReader
                reader.readAsText(file, "UTF-8");//读取文件
                reader.onload = function(e){ //读取完文件之后会回来这里
                    var file_size=Math.round(e.total/1024/1024);
                    if(file_size>5){
                        alert('最大文件限制为5M,'+file_name+'当前为'+file_size+'M');
                        return false;
                    }
                }
                post_data.append("product_media",file);
	}else{
		return false;
	}
	post_data.append("order_product_id",order_product_id);
	//alert('good');
	var xhr = null;
        if (window.XMLHttpRequest){// code for all new browsers
            xhr=new XMLHttpRequest();
        }else if (window.ActiveXObject){// code for IE5 and IE6
            xhr=new ActiveXObject("Microsoft.XMLHTTP");
        }else{
            alert("Your browser does not support XMLHTTP.");return false;
        }
        xhr.onreadystatechange = function(){
            if (xhr.readyState == 4 && xhr.status == 200){
                eval("var result="+xhr.responseText);
                if(result.code=='1'){
                	//alert(result.message);
                	$(input_file).parent().find("img").attr('src',result.message);
                	if($(input_file).siblings('input[name="data[OrderProductMedia][id][]"]').val() == ''){
                		$(input_file).siblings('input[name="data[OrderProductMedia][id][]"]').val(0);
                	}
                	$(input_file).siblings('input[name="data[OrderProductMedia][media][]"]').val(result.message);
                	$(input_file).val('');
                }else{
                	$("#my-modal-loading-update").modal('close');
                    alert(result.message);
                }
            }
        };
        xhr.onerror=function(evt){
            	console.log(j_object_transform_failed);
        };
        xhr.open("POST", admin_webroot+'order_product_medias/ajax_upload_media');
        xhr.send(post_data);
}

function update_media_co_group(order_product_id,surl,desc,img_id){
	//alert(order_product_id);
	var pro_code = document.getElementById("order_product_code").value;
	var pro_id = document.getElementById("order_product_id").value;
	$.ajax({
		url: admin_webroot+'order_product_medias/add/'+order_product_id,
		type:"POST",
		dataType:"json", 
		data: {'data[OrderProductMedia][id]':img_id,'data[OrderProductMedia][order_product_id]':order_product_id,'data[OrderProductMedia][type]':'image','data[OrderProductMedia][media]':surl,'data[OrderProductMedia][description]':desc,'data[OrderProductMedia][media_group]':1},
		success: function(data){
			if(data.code==1){
				//alert(pro_code);
				//update_pro_attr(pro_code,pro_id,order_product_id);
			}
		}
	});
}

function se(saveflag){
	var order_product_id = $("#order_product_id").val();
	var postData = $("#clothes_condition").serialize();
	$.ajax({
		url: admin_webroot+'order_product_medias/batch_add/'+order_product_id,
		type:"POST",
		dataType:"json", 
		data: postData,
		success: function(data){
			if(data.code==1){
				save_order_product_attr_data(saveflag);
				//alert(pro_code);
				//update_pro_attr(pro_code,pro_id,order_product_id);
			}
		}
	});
}

function get_information_resource(){
	$.ajax({
		url: admin_webroot+'infomation_resources/searchInforationresources',
		type:"POST",
		dataType:"json", 
		data: {'code':'clothes_location'},
		success: function(data){
			if(data.code==1){
				
				//alert(pro_code);
				//update_pro_attr(pro_code,pro_id,order_product_id);
			}
		}
	});
}

function add_condition(){
	var b1 = '<li class="am-thumbnail" style="border:none;background-color:#F8F8F8;font-size:12px;">';
	var b2 = '<input type="hidden" name="data[OrderProductMedia][id][]" value="">';
	var b3 = '<input type="hidden" name="data[OrderProductMedia][media][]" value="">';
	var b4 = '<input type="hidden" name="data[OrderProductMedia][media_group][]" value="1">';
	var b5 = '<input type="file" onchange="ajax_upload_media_attr_group(this)" style="display:none;" />';
	var b6 = '<img src="/media/order_product_media/order-pro-add.png" alt="" style="width:60px;height:60px;margin-left:0;" id="" onclick="up_img(this)">';
	var b7 = '<select name="data[OrderProductMedia][location][]" style="width:60px;margin-top:5px;">'+
						'<option value="">请选择</option>'+
						'<option value="1">胸前第3颗纽扣</option>'+
						'<option value="2">位置2</option>'+
						'<option value="3">位置3</option>'+
					'</select>';
	var b8 = '<textarea cols="2" name="data[OrderProductMedia][description][]" value="" style="width:60px;margin-top:5px;resize: none;"></textarea></li>';
	var b = b1+b2+b3+b4+b5+b6+b7+b8;
	for (var i = 0; i <4; i++) {
		$("#clo_con").append(b);
	};

}

</script>
<?php
    }else{
?>

<table class="am-table">

<?php 
    foreach($attrvalueInfo as $k=>$v){
        $attribute_id=$v['StyleTypeGroupAttributeValue']['attribute_id'];
        $attribute_name=isset($attr_infos[$attribute_id])?$attr_infos[$attribute_id]:'-';
        $order_product_value="";
        if(isset($user_style_value_data_list[$attribute_id])){
            $order_product_value=$user_style_value_data_list[$attribute_id];
        }else if(isset($order_product_value_data[$attribute_id])){
            $order_product_value=$order_product_value_data[$attribute_id];
        }else if(isset($attrvaluelist[$attribute_id])){
            $order_product_value=$attrvaluelist[$attribute_id];
        }else if(isset($attr_values[$attribute_id])){
            $order_product_value=$attr_values[$attribute_id];
        }
        $pro_type_attr_type_data=isset($pro_type_attr_type_list[$attribute_id])?$pro_type_attr_type_list[$attribute_id]:array();
        $attr_select_data=isset($attr_select_list[$attribute_id])?$attr_select_list[$attribute_id]:array();
        $user_style_value=isset($user_style_value_data_list[$attribute_id])?$user_style_value_data_list[$attribute_id]:0;
?>
    <tr>
    		<td width="25%"><?php echo $attribute_name; ?></td>
    		<td width="20%" id="pro_type_attr_<?php echo $attribute_id; ?>">
                <?php echo isset($attrvaluelist[$attribute_id])&&!empty($attrvaluelist[$attribute_id])?$attrvaluelist[$attribute_id]:$attr_values[$attribute_id]; ?></span>
    				<input type="hidden" name="data[pro_type_attr][<?php echo $k; ?>]" value="<?php echo isset($attrvaluelist[$attribute_id])&&!empty($attrvaluelist[$attribute_id])?$attrvaluelist[$attribute_id]:$attr_values[$attribute_id]; ?>">
            </td>
            <td width="23%">
                <?php if(!empty($pro_type_attr_type_data)){ ?>
                <select name="data[attr_edit_value][<?php echo $attribute_id; ?>]" id="attr_edit_value_<?php echo $attribute_id; ?>" onchange="change_pro_type_attr_value(this)" <?php echo $page_action=='attr_view'?"disabled='disabled'":''; ?>>
                    <?php foreach($pro_type_attr_type_data as $kk=>$vv){
						$attr_type_txt=$vv;
						if($vv>0){$attr_type_txt=$ld['plus'].$vv;}else if($vv<0){$attr_type_txt=$ld['minus'].($vv*-1);}else{$attr_type_txt=$ld['no_change'];}
						$sel_txt="";
						if($user_style_value){
							$difference=$user_style_value-$attrvaluelist[$attribute_id];
							if($difference==$vv){$sel_txt=" selected='selected'";}
						}else if($order_product_value!=""){
							$difference=$order_product_value-$attrvaluelist[$attribute_id];
							if($difference==$vv){$sel_txt=" selected='selected'";}
						}else if($vv==0){
							$sel_txt=" selected='selected'";
						}
					?>
						<option value="<?php echo trim($vv); ?>" <?php echo $sel_txt; ?>><?php echo $attr_type_txt; ?></option>
					<?php }}else{ ?>
                    <select name="data[attr_edit_value][<?php echo $attribute_id; ?>]" id="attr_edit_value_<?php echo $attribute_id; ?>" onchange="change_attr_default_value(this)" <?php echo $page_action=='attr_view'?"disabled='disabled'":''; ?>>
                    <?php foreach($attr_select_data as $kk=>$vv){ ?>
                        <option value="<?php echo trim($kk); ?>" <?php echo $kk==$order_product_value?"selected":''; ?>><?php echo $vv; ?></option>
                    <?php }} ?>
				</select>
            </td>
            <td width="23%" class="select_difference_value_<?php echo $attribute_id; ?>">
    			<?php if(isset($pro_type_attr_type_list[$attribute_id])&&!empty($pro_type_attr_type_list[$attribute_id])){ ?>
    				<span><?php echo $order_product_value; ?></span><input type="hidden" name="data[pro_type_attr_value][<?php echo $attribute_id; ?>]" value="<?php echo $order_product_value; ?>" >
    			<?php }else{ ?>
    			<input type="text" name="data[pro_type_attr_value][<?php echo $attribute_id; ?>]" value="<?php echo $order_product_value; ?>" <?php echo $page_action=='attr_view'?"disabled='disabled'":''; ?>>
    			<?php } ?>
    		</td>
    </tr>
<?php } ?>
    
    
</table>
    
<?php
    }
?>