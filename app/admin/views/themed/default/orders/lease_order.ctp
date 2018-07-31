<style type="text/css">
.linkshow{text-decoration:underline;color:green;}
#SearchForm{padding-top:8px;}
.am-form-label{font-weight:bold;margin-top:-6px; margin-left:15px;}
.am-form-label-text{margin-left:15px;}
.am-datepicker-dropdown{z-index:121000;}
</style>
<div id="order_list" class="am-u-md-12 am-u-sm-12 am-u-lg-12">
    <div class="listsearch">
        <?php echo $form->create('Order',array('action'=>'/','type'=>'get','id'=>"SearchForm",'class'=>'am-form am-form-inline am-form-horizontal','onsubmit'=>'return formsubmit();'));?>
        <input type="hidden" name="export_act_flag" id="export_act_flag" value=""/>
        <input id="select_code" name="select_code" type="hidden" value="" />
        <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1" style="margin:1px 0 0 0">
            
            <li style="margin:0 0 10px 0" >
                <label class="am-u-lg-2  am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['order_status'];?></label>
                <div class="am-u-lg-9 am-u-md-7 am-u-sm-7  am-u-end">
                    <select name="order_status"  id="order_status" data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?> '}">
                        <option value="-1" selected><?php echo $ld['all_data']?> </option>
                        <option value="0" <?php if (isset($order_status)&&$order_status == 0){?>selected<?php }?>><?php echo $ld['unrecognized']?></option>
                        <option value="5" <?php if (isset($payment_status)&&$payment_status == 0){?>selected<?php }?>><?php echo $ld['unpaid']?></option>
                        <option value="10" <?php if (isset($shipping_status)&&$shipping_status == 0){?>selected<?php }?>><?php echo $ld['unreceived']?></option>
                        <option value="16" <?php if (isset($shipping_status)&&$shipping_status == 6){?>selected<?php }?>><?php echo $ld['pre_shipment']?></option>
                        <option value="11" <?php if (isset($shipping_status)&&$shipping_status == 1){?>selected<?php }?>><?php echo $ld['shipped']?></option>
                        <option value="12" <?php if (isset($shipping_status)&&$shipping_status == 2){?>selected<?php }?>><?php echo $ld['received']?></option>
                        <option value="4" <?php if (isset($order_status)&&$order_status == 4){?>selected<?php }?>><?php echo $ld['return']?></option>
                        <option value="6" <?php if (isset($payment_status)&&$payment_status == 1){?>selected<?php }?>><?php echo $ld['pending']?></option>
                        <option value="13" <?php if (isset($shipping_status)&&$shipping_status == 3){?>selected<?php }?>><?php echo $ld['picking']?></option>
                        <option value="3" <?php if (isset($order_status)&&$order_status == 3){?>selected<?php }?>><?php echo $ld['invalid']?></option>
                        <option value="2" <?php if (isset($order_status)&&$order_status == 2){?>selected<?php }?>><?php echo $ld['canceled']?></option>
                        <option value="8" <?php if (isset($payment_status)&&$payment_status == 3){?>selected<?php }?>><?php echo $ld['order_apply_for_a_Refund']?><?php //echo $ld['pending']?></option>
                        <option value="9" <?php if (isset($payment_status)&&$payment_status == 4){?>selected<?php }?>><?php echo $ld['order_refunded']?><?php //echo $ld['pending']?></option>
                        <option value="14" <?php if (isset($shipping_status)&&$shipping_status == 4){?>selected<?php }?>><?php echo $ld['order_submitting_a_Return']?><?php //echo $ld['pending']?></option>
                        <option value="15" <?php if (isset($shipping_status)&&$shipping_status == 5){?>selected<?php }?>><?php echo $ld['order_has_returned']?><?php //echo $ld['pending']?></option>
                        <option value="20" <?php if (isset($order_status)&&$order_status == 5){?>selected<?php }?>><?php echo $ld['been_combined']?></option>
                        <option value="25" <?php if (isset($order_status)&&$order_status == 25){?>selected<?php }?>><?php echo $ld['unpaid']."".$ld['shipped'];?></option>
                    </select>
                </div>
            </li>
            <li style="margin:0 0 10px 0">
                <label class="am-u-lg-2 am-u-md-3  am-u-sm-4 am-form-label-text"><?php echo $ld['order_code']?></label>
                <div class="am-u-lg-9 am-u-md-7 am-u-sm-7 am-u-end">
                    <input class="am-form-field am-input-sm" type="text" size="12" onkeypress="if(event.keyCode==13)formsubmit()" name="order_code" id="order_code" value="<?php echo $order_code?>">
                </div>
            </li> 
            <li style="margin:0 0 10px 0" >
                <label class="am-u-lg-3  am-u-md-3  am-u-sm-4 am-form-label-text  "><?php echo $ld['orders_time']?></label>
                <div class="am-u-lg-3  am-u-md-3 am-u-sm-3" >
                    <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="start_date" value="<?php echo $start_date;?>" />
                </div>
                <em class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center" style="padding: 0.35em 0px;">-</em>
                <div class=" am-u-lg-3  am-u-md-3  am-u-sm-3 am-u-end" >
                    <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="end_date" value="<?php echo $end_date;?>" />
                </div>
            </li> 
            <li style="margin:0 0 10px 0">
                <label class="am-u-lg-2  am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['consignee']?></label>
                <div class=" am-u-lg-9 am-u-md-7 am-u-sm-7 am-u-end">  <input class="am-form-field " type="text" size="12" onkeypress="if(event.keyCode==13)formsubmit()" name="consignee" id="consignee" value="<?php echo $consignee?>">
                </div>
            </li>
            <li style="margin:0 0 10px 0">
                <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['amount']?></label>
                <div class=" am-u-lg-4 am-u-md-3  am-u-sm-3" >
                    <input type="text" size="12" class="am-form-field am-input-sm" id="min_amount" name="min_amount" onkeydown="return writeamount(this,event.keyCode);" value="<?php echo $min_amount ?>" />
                </div>
                <em class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center  " style="padding: 0.35em 0px;">-</em>
                <div class="am-u-lg-4 am-u-md-3  am-u-sm-3 am-u-end" style="padding:0 0.5rem;">
                    <input class="am-form-field am-input-sm" type="text" size="12" id="max_amount" name="max_amount" onkeydown="return writeamount(this,event.keyCode);" value="<?php echo $max_amount ?>" />
                </div>
            </li>
           
            <li  style="margin:0 0 10px 0">
                <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['sku'].'/'.$ld['z_name']?></label>
                <div class=" am-u-lg-8 am-u-md-7 am-u-sm-7 am-u-end">
                    <input class="am-form-field am-input-sm"  placeholder="<?php echo $ld['product_sku_or_name'];?>" type="text" onkeypress="if(event.keyCode==13)formsubmit()" name="product_keywords" id="product_keywords" value="<?php echo @$product_keywords?>">
                </div>
            </li>
			<?php if(isset($configs['order_product_expires'])&&$configs['order_product_expires']=='1'){ ?>
			<li  style="margin:0 0 10px 0">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label-text" style="top:5px;"><?php echo $ld['time_of_commodity_lease'] ?></label>
                <div class=" am-u-lg-4 am-u-md-3  am-u-sm-3" >
                    <input type="text" class="am-form-field am-input-sm" name="lease_date_start" id="lease_date_start" value="<?php echo $lease_date_start; ?>" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly />
                </div>
                <em class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center  " style="padding: 0.35em 0px;">-</em>
                <div class="am-u-lg-4 am-u-md-3  am-u-sm-3 am-u-end" style="padding:0 0.5rem;">
                    <input class="am-form-field am-input-sm" type="text" name="expire_date_end" id="lease_date_end" value="<?php echo $lease_date_end; ?>" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly />
                </div>
			</li>
			<?php } ?>
          
            <li style="margin:0 0 10px 0" class="am-text-left">
                <label class="am-u-lg-2  am-u-md-3 am-u-sm-4 am-form-label"> </label>
                <div class=" am-u-lg-8 am-u-md-8 am-u-sm-7 am-u-end"><input class="am-btn am-btn-success am-radius am-btn-sm" type="button" onclick="formsubmit()" value="<?php echo $ld['search'];?>" style="margin-right:5px;"/>
                </div>
            </li>
        </ul>
        <div class="am-u-md-12">
            <div class="am-fr am-btn-group-xs">
        		<?php if($svshow->operator_privilege("lease_orders_add")){?>
                <a class="am-btn am-btn-warning am-radius am-btn-sm" href="<?php echo $html->url('lease_add/'); ?>"><span class="am-icon-plus"></span><?php echo $ld['add_order'] ?></a>
                <?php } ?><div style="clear:both"></div>
            </div>
        </div>
        <?php echo $form->end();?>
    </div></div>
<div>
    <?php echo $form->create('',array('action'=>'/batch_order_shipping_print/',"name"=>"OrdForm",'onsubmit'=>"return false"));?>
    <div class="am-u-md-12 am-u-sm-12">
        <table class="am-table  table-main">
            <thead>
            <tr>
                <th><label class="am-checkbox am-success">
                        <span class="am-hide-sm-only"><input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck  type="checkbox" /></span>&nbsp;<b><?php echo $ld['order_code']?></b></label>
                </th>
                <th><?php echo $ld['product_name']?></th>
                <th><?php echo $ld['order_quantity'] ?></th>
                <th><?php echo $ld['price']?></th>
                <th><?php echo $ld['lease_time'] ?></th>
                <th><?php echo $ld['time_to_start'] ?></th>
                <th><?php echo $ld['time_to_maturity'] ?></th>
                <th><?php echo $ld['consignee']?></th>
                <th><span><?php echo $ld['order_total_amount']?><br ><?php echo $ld['payment_time'] ?></span></th>
                <th style="white-space:nowrap;"><?php echo $ld['commodity_status'] ?></th>
               
                <th style="width:250px;" class="am-text-left"><?php echo $ld['operate']?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(isset($po_info) && sizeof($po_info)>0){foreach($po_info as $k=>$v){?>
            <tr>
                <td><label class="am-checkbox am-success">
                        <span class="am-hide-sm-only"><input type="checkbox" name="checkboxes[]" value="<?php echo $v['order_id']?>"  data-am-ucheck /></span>&nbsp;<?php echo $html->link("{$v['order_code']}","/orders/{$v['order_id']}",array("target"=>"_blank"),false,false);?></label>
                </td>
                <td><?php echo $v['product_name']?></td>
                <td><?php echo $v['product_quntity']?></td>
                <td><?php echo $svshow->price_format(sprintf("%01.2f",$v['product_price']),$configs['price_format']);?></td>
                <td><input type="hidden" id="lease_unit_<?php echo $v['order_product_id']?>" value="<?php echo $v['base_unit']?>"/><?php echo $v['lease_unit']?>天</td>
                <td><?php if($v['begin_date']!="2008-01-01 00:00:00")echo date('Y-m-d',strtotime($v['begin_date']));?>&nbsp;</td>
               	<td><?php if($v['expire_date']!="2008-01-01 00:00:00")echo date('Y-m-d',strtotime($v['expire_date']));?>&nbsp;</td>
                <td><?php echo $v['consignee']?></td>
                <td><?php echo $svshow->price_format(sprintf("%01.2f",$v['product_price']*$v['product_quntity']),$configs['price_format']);?>
    				<?php if(isset($v['payment_status'])&&$v['payment_status']==2){?>
    				<?php echo "<br>".$v['payment_time'] ?>
    				<?php }?>
                </td>
                <td style="white-space:nowrap; " >
                        <?php if( $v['product_status']==1 ){?>
    					<?php echo $ld['unreceived'] ?>
    					<?php }elseif( $v['product_status']==2){?>
    					<?php echo $ld['shipped'] ?>
    					<?php }elseif( $v['product_status']==3){?>
    					<?php echo $ld['returned'] ?>
    					<?php }elseif( $v['product_status']==4){?>
    					<?php echo $ld['replacement'] ?>
    					<?php }elseif( $v['product_status']==5){?>
    					<?php echo $ld['renew'] ?>
    					<?php }?>
                </td>
                <td  class="am-text-left am-btn-group-xs am-action" style="min-width:180px;"><?php
                    if($svshow->operator_privilege("lease_order_view")){ ?>
                    <a class="am-btn am-btn-default  am-btn-success am-seevia-btn  am-btn-xs" href="<?php echo $html->url('/orders/view/'.$v['order_id']); ?>">
                        <span class="am-icon-eye"></span> <?php echo $ld['preview'] ?>
                    </a>
                    <?php }
                    if($v['shipping_status']==1&&$v['status']==1){ ?>
                    <?php }
                    if($svshow->operator_privilege("lease_orders_edit")){ ?>
                    <a class="am-seevia-btn-edit am-btn am-btn-default am-btn-xs am-text-secondary" href="<?php echo $html->url('/orders/lease_edit/'.$v['order_id']); ?>">
                        <span class="am-icon-pencil-square-o"> </span> <?php echo $ld['edit']; ?>
                    </a>
                    <?php }
                    if($v['payment_status']==2&&$v['status']!=2&&$v['shipping_status']!=0&&$v['product_status']==2){ ?>
                    <?php if($svshow->operator_privilege("lease_return")){  ?>
                    <a class="am-seevia-btn-edit am-btn am-btn-default am-btn-xs am-text-secondary" href="<?php echo $html->url('/orders/lease_order_status?order_id='.$v['order_id']).'&pro_id='.$v['order_product_id'].'&status=cancel'; ?>">
                        <span class="am-icon-eye"></span><?php echo $ld['lease_return'] ?>
                    </a>
                    <?php } ?>
                    <?php if($svshow->operator_privilege("lease_return")&&$svshow->operator_privilege("lease_orders_add")){  ?>
                    <a class="am-seevia-btn-edit am-btn am-btn-default am-btn-xs am-text-secondary" href="<?php echo $html->url('/orders/lease_order_status?order_id='.$v['order_id']).'&pro_id='.$v['order_product_id'].'&status=change'; ?>">
                        <span class="am-icon-eye"></span><?php echo $ld['exchange_goods'] ?>
                    </a>
                    <?php } ?>
                    <?php if($svshow->operator_privilege("lease_renew")){  ?>
                    <a class="am-seevia-btn-edit am-btn am-btn-default am-btn-xs am-text-secondary" onclick="show_time(<?php echo $v['order_id']?>,<?php echo $v['order_product_id']?>)">
                        <span class="am-icon-eye"></span><?php echo $ld['renew'] ?>
                    </a>
                    <?php } ?>
                    <?php }?>
                    </td>
            </tr>
    			<?php }}else{?>
            <tr>
                <td colspan="7" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
            <?php }?>
            </tbody>
        </table>
        <?php if(isset($po_info) && sizeof($po_info)>0){?>
        <div id="btnouterlist" class="btnouterlist btnouterlist am-form-group am-hide-sm-only">
            <div class="am-u-lg-6 am-u-md-12 am-u-sm-12">
                <div class="am-fl">
                    <label class="am-checkbox am-success" style="margin:5px 5px 5px 0px;"><input onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)"  class="am-btn am-radius am-btn-success am-btn-sm" type="checkbox" data-am-ucheck /><?php echo $ld['select_all']?></label>
                </div>
                <div class="am-fl" style="margin-right:5px;">
                    <select id="order_operations_select" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}" onchange="changetype()">
                        <option value=""><?php echo $ld['all_data']?></option>
                        <?php if($svshow->operator_privilege("lease_orders_remove")){?>
                        <option value="delete"><?php echo $ld['batch_delete']?></option>
                        <?php }?>
        				<?php if($svshow->operator_privilege('lease_combineds_view')){?>
                        <option value="batch_combined"><?php echo $ld['order_batch_merge']?></option>
                        <?php }?>
                        <option value="export_act"><?php echo $ld['batch_export']?></option>
                        <option value="search_result"><?php echo $ld['search_export']?></option>
                        <option value="import_to_vendor"><?php echo "导入供应商平台"; ?></option>
                    </select>
                </div>
                <div class="am-fl" style="display:none;margin-right:5px;">
                    <select id="export_csv" data-am-selected name="barch_opration_select_onchange">
                        <option value="0"><?php echo $ld['please_select']?></option>
                        <option value="export_check_delivery"><?php echo  $ld['choice_export']?></option>
                        <option value="export_delivery"><?php echo $ld['all_export']?></option>
                    </select>&nbsp;
                </div>
                <div class="am-fl">
                    <input type="button" value="<?php echo $ld['submit']?>" class="am-btn am-btn-sm am-btn-danger  am-btn-radius"  onclick="submit_operations()" />
                </div>
            </div>
            <div class="am-u-lg-6 am-u-md-12 am-u-sm-12"><?php echo $this->element('pagers'); ?></div>
            <div class="am-cf"></div>
        </div>
        <?php }?>
    </div>
    <?php echo $form->end();?>
</div>
<div class="am-modal am-modal-no-btn tablemain" tabindex="-1" id="placement" >
    <div class="am-modal-dialog">
        <div class="am-modal-hd">
            <?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['file_allocation'].' '.$ld['templates']:$ld['file_allocation'].$ld['templates'];?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            <form id='placementform3' method="POST" class="am-form am-form-horizontal">
                <div class="am-form-group">
                    <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">
                        <?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['choice_export'].' '.$ld['templates']:$ld['choice_export'].$ld['templates'];?>:
                    </label>
                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                        <select name="profilegroup" id="profilegroup" data-am-selected>
                            <option value="0"><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['please_select'].' '.$ld['templates']:$ld['please_select'].$ld['templates'];?></option>
                        </select>&nbsp;&nbsp;&nbsp;&nbsp;<em style="color:red;">*</em>
                    </div>
                </div>
                <div><input type="button" id="mod" class="am-btn am-btn-success am-btn-sm am-radius"  name="changeprofileButton" value="<?php echo $ld['confirm']?>" onclick="javascript:changeprofile();"></div>
            </form>
        </div>
    </div>
</div>
<div class="am-modal am-modal-no-btn tablemain" tabindex="-1" id="lease" >
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style="border-bottom:1px solid #ddd;">订单续租
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd"  style="padding-top:15px;">
            <form id='lease3' method="POST" class="am-form am-form-horizontal">
                <div class="am-form-group">
                    <div class="am-g" style="line-height:37px;">
                        <div class="am-u-sm-4 am-margin-top-0 am-text-right" style="line-height:37px;font-size:16px;"><?php echo $ld['lease_days'] ?>：</div>
                        <div class="am-u-sm-6 am-margin-top-0 am-text-left">
                        <div class="am-u-sm-8 am-margin-top-0 am-text-left" style="line-height:37px">
                         <div class="am-u-sm-9">
                        <label id="unit"/></label>
                        </div>
                        </div>
                    </div>
                    </div>
                    <div class="am-g am-margin-top-xs">
                        <div class="am-u-sm-4 am-margin-top-0 am-text-right" style="line-height:37px;font-size:16px;"><?php echo $ld['number_ lease_days'] ?>：</div>
                        <div class="am-u-sm-8 am-margin-top-0 am-text-left">
                            <div class="am-u-sm-9">
                            <input type="text" value="" onchange="change_lease_day(this)" id="lease_day"/>
                            </div>
                            <div class="am-u-sm-2">
                            <em class="color_red">*</em>    
                            </div>
                        </div>
                    </div>
        			<input type="hidden" id="lease_order" value="">
        			<input type="hidden" id="lease_pro" value="">
                </div>
                <div class="am-margin-top-sm"><input type="button" id="lease_button" class="am-btn am-btn-success am-btn-sm am-radius"  name="changeprofileButton" value="<?php echo $ld['confirm']?>" onclick="javascript:changelease();"></div>
            </form>
        </div>
    </div>
</div>

<!-- import_to_vendor -->
<div class="am-modal am-modal-no-btn" tabindex="-1" id="import_to_vendor">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">导入供应商
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
      	
    </div>
  </div>
</div>
<!-- import_to_vendor -->
<script type="text/javascript">
var po_logistics_company_selhtml=$("#po_logistics_company").parent().html();
function createPoLogisticsCompanySel(value){
    var slehtml="<span id='po_logistics_company_txt'></span><select id='po_logistics_company'>";
    var selobj=$(po_logistics_company_selhtml).find("select");
    var seloption=$(po_logistics_company_selhtml).find("option");
    $(seloption).each(function(i,n){
        if(value!="undefined"&&value==n.value){
            slehtml+="<option value='"+n.value+"' selected='selected'>"+n.text+"</option>";
        }else{
            slehtml+="<option value='"+n.value+"'>"+n.text+"</option>";
        }
    });
    slehtml+="</select>";
    return slehtml;
}

    var obj_lease_unit = {};
    function change_lease_day (obj) {
        var lease_number = Number($(obj).val());
        if (!!lease_number == false) {
            alert("请输入正确的天数");
            $(obj).val('');
            return false;
        };
        var lease_days = obj_lease_unit.day*lease_number;
        $("#unit").html(obj_lease_unit.day+"x"+lease_number+"="+lease_days);
    }

function show_time(order_id,pro_id){
	$("#lease_order").val(order_id);
	$("#lease_pro").val(pro_id);
	$("#unit").html($("#lease_unit_"+pro_id).val());
    obj_lease_unit.day = $("#lease_unit_"+pro_id).val();
    $("#lease_day").val('');
	$('#lease').modal({width:400,height:230});
}

function changelease(){
	var order_id=$("#lease_order").val();
	var day=parseInt($("#lease_day").val())*parseInt($("#unit").html());
	var pro_id=$("#lease_pro").val();
	if(day!=""){
		window.location.href=admin_webroot+"orders/lease_order_status?order_id="+order_id+"&day="+day+"&pro_id="+pro_id+"&status=continue";
	}
}

function change_purchase_order(order_code){
    $('#purchase_order').modal('open');
    $("#purchase_order #po_order_code").val(order_code);
    $("#purchase_order #ESD").val("");
    $("#purchase_order #ASD").val("");
    $("#purchase_order #po_invoice_no").val("");
    $("#purchase_order .po_save_btn").css("display","none");
    var po_logistics_companyselect=po_logistics_company_selhtml;
    var sUrl = admin_webroot+"orders/change_vendor_information/";//访问的URL地址
    $.ajax({ url:sUrl,
        type:"POST",
        data:{"order_code":order_code},
        dataType:"json",
        success: function(data){
            if(data['code']==1){
                var data=data['data'];
                var editFlag=true;
                if(data.ESD!="0000-00-00"){
                    $("#purchase_order #ESD").val(data.ESD);
                }else{
                    editFlag=false;
                }
                if(data.ASD!="0000-00-00"){
                    $("#purchase_order #ASD").val(data.ASD);
                }else{
                    editFlag=false;
                }
                if(data.logistics_company_id=="0"){
                    editFlag=false;
                }
                po_logistics_companyselect=createPoLogisticsCompanySel(data.logistics_company_id);
                if(data.invoice_no!=""){
                    $("#purchase_order #po_invoice_no").val(data.invoice_no);
                }else{
                    editFlag=false;
                }
                <?php if($svshow->operator_privilege("edit_factory_time")){ ?>editFlag=false;<?php } ?>
                if(editFlag){
                    $("#purchase_order .po_save_btn").css("display","none");
                }else{
                    $("#purchase_order .po_save_btn").css("display","inline");
                }
            }else{
                $("#purchase_order .po_save_btn").css("display","inline");
            }
            $("#purchase_order #po_logistics_company").parent().html(po_logistics_companyselect);
            $("#purchase_order #po_logistics_company").selected();
        }
    });
}

function save_purchase_order(){
    var order_code=$("#po_order_code").val();
    var ESD=$("#ESD").val();
    var ASD=$("#ASD").val();
    var logistics_company_id=$("#po_logistics_company").val();
    var invoice_no=$("#po_invoice_no").val();
    var sUrl = admin_webroot+"orders/change_vendor_information/data_save";//访问的URL地址
    $.ajax({ url:sUrl,
        type:"POST",
        data:{"order_code":order_code,"ESD":ESD,"ASD":ASD,"logistics_company_id":logistics_company_id,"invoice_no":invoice_no},
        dataType:"json",
        success: function(data){
            if(data['code']==1){
                $(".purchase_order_detail_"+order_code+" .ESD").html(ESD);
                $(".purchase_order_detail_"+order_code+" .ASD").html(ASD);
                var company_detail="";
                if(logistics_company_id!="0"){
                    company_detail=$("#po_logistics_company option[value='"+logistics_company_id+"']").text();
                }
                if(invoice_no!=""&&company_detail!=""){
                    company_detail+="-"+invoice_no;
                }
                $(".purchase_order_detail_"+order_code+" .company_detail").html(company_detail);
                $(".am-close").click();
            }else{
                alert(data['msg']);
            }
        }
    });
}

function writeamount(obj,keyCode){
    if((keyCode>=48&&keyCode<=57)||(keyCode==8)||(keyCode==190)||(keyCode==110)||(keyCode>=96&&keyCode<=105)){
        if(keyCode==190||keyCode==110){
            var value=obj.value;
            if(value.indexOf('.')>0){
                return false;
            }
        }
        return true;
    }else{
        if(keyCode==13){formsubmit();}
        return false;
    }
}

function changetype(){
	var b=document.getElementById("order_operations_select");
	if(b.value=="export_del"){
		$("#export_csv").parent().show();
	}else{
		$("#export_csv").parent().hide();
	}
}

function submit_operations(){
    var order_status=document.getElementById('order_status').value;
    var a=document.getElementById("export_csv");
    var bratch_operat_check = document.getElementsByName("checkboxes[]");
    var order_operations_select = document.getElementById("order_operations_select");
    if(order_operations_select.value==''){
        alert(j_select_operation_type+" !");
        return;
    }
    var strsel = order_operations_select.options[order_operations_select.selectedIndex].text;
    var postData = "";
    for(var i=0;i<bratch_operat_check.length;i++){
        if(bratch_operat_check[i].checked){
            postData+="&checkboxes[]="+bratch_operat_check[i].value;
        }
    }
    if(a.value == 'export_delivery'){
		window.location.href=admin_webroot+"orders/delivery_exprot_out";
		return false;
	}
    if(order_operations_select.value!="search_result"&&postData==''){
        alert(j_please_select+orders+" !");
        return false;
    }
    if(order_operations_select.value=="import_to_vendor"&&postData!=''){
    		import_to_vendor(postData);
    		return false;
    }
    var code=document.getElementById("profilegroup").value;
    document.getElementById('export_act_flag').value='';
    postData+="&order_status="+order_status;
    if(order_operations_select.value=='export_act' || order_operations_select.value=='search_result' ){
        var func="/profiles/getdropdownlist/";
        var group="OrderExport";
        $.ajax({url: admin_webroot+func,
            type:"POST",
            data:{group:group},
            dataType:"json",
            success: function(result){
                try{
                    if(result.flag == 1){
                        var result_content = (result.flag == 1) ? result.content : "";
                        if(result_content!=""){
                            strbind(result_content);
                        }
                        $("#placement").modal("open");
                    }
                    if(result.flag == 2){
                        alert(result.content);
                    }
                }catch(e){
                    alert(j_object_transform_failed);
                    alert(o.responseText);
                }
            }
        });
    }else{
        if(confirm(confirm_exports+" "+strsel+"？")){
        	if(a.value == 'export_check_delivery'){
				window.location.href=admin_webroot+"orders/delivery_exprot_out?ids="+postData;
			}
            if(order_operations_select.value=='batch_combined'){
                batch_combined();
            }else if(order_operations_select.value=='order_batch_print'){
                batch_shipping_print();
            }else if(order_operations_select.value=='batch_shipping_print_pdf'){
                batch_shipping_print_pdf();
            }else{
                $.ajax({url: admin_webroot+"orders/batch_operations/"+order_operations_select.value,
                    type:"POST",
                    data:postData,
                    dataType:"html",
                    success: function(result){
                        window.location.href = window.location.href;
                    }
                });
            }
        }
    }
}

//绑定下拉
function strbind(arr){
    //先清空下拉中的值
    var profilegroup=document.getElementById("profilegroup");
    $("#profilegroup option").remove();
    var optiondefault=document.createElement("option");
    profilegroup.appendChild(optiondefault);
    optiondefault.value="0";
    optiondefault.text=j_templates;
    for(var i=0;i<arr.length;i++){
        var option=document.createElement("option");
        profilegroup.appendChild(option);
        option.value=arr[i]['Profile']['code'];
        option.text=arr[i]['ProfileI18n']['name'];
    }
    $("profilegroup").trigger('changed.selected.amui');
}

//修改档案分类导出
function changeprofile(){
    var order_operations_select = document.getElementById("order_operations_select");
    var code=document.getElementById("profilegroup").value;
    if(code==0){
        alert("请选择导出方式");
        return false;
    }
    var strsel = order_operations_select.options[order_operations_select.selectedIndex].text;
    if(confirm(confirm_exports+" "+strsel+"？")){
        if(order_operations_select.value=='search_result'){
            search_result(code);
        }else if(order_operations_select.value=='export_act'){
            export_act(code);
        }
    }
}

function search_result(code){
    document.getElementById('export_act_flag').value='1';
    document.getElementById('select_code').value=code;
    var form=document.getElementById('SearchForm');
    form.action='/admin/orders/index/';
    form.method="post";
    form.submit();
}

function export_act(code){
    document.OrdForm.action=admin_webroot+"orders/export_act/"+code;
    document.OrdForm.onsubmit= "";
    document.OrdForm.submit();
}

//高级搜索
function sv_advanced_search2(obj,advanced_id){
    var show=document.getElementById(advanced_id).style.display;
    if(show=="block"){
        document.getElementById(advanced_id).style.display="none";
    }else{
        document.getElementById(advanced_id).style.display="block";
    }
}

function close_advanced(advanced_id){
    document.getElementById(advanced_id).style.display = "none";
}

function diachange(){
    var id=document.getElementsByName('checkboxes[]');
    var i;
    var j=0;
    var image="";
    for( i=0;i<=parseInt(id.length)-1;i++ ){
        if(id[i].checked){
            j++;
        }
    }
    if( j>=1 ){
        // layer_dialog_show('确定删除?','batch_action()',5);
        if(confirm("<?php echo $ld['confirm_delete']?>"))
        {
            batch_action();
        }
    }else{
        // layer_dialog_show('请选择！！','batch_action()',3);
        if(confirm(j_please_select))
        {
            return false;
        }
    }
}

function batch_action(){
    var order_status=document.getElementById('order_status').value;
    document.OrdForm.action=admin_webroot+"orders/batch_delete?order_status="+order_status;
    document.OrdForm.onsubmit= "";
    document.OrdForm.submit();
}

function batch_shipping_print(){
    document.OrdForm.action=admin_webroot+"orders/batch_order_shipping_print";
    document.OrdForm.onsubmit= "";
    document.OrdForm.submit();
}

function batch_shipping_print_pdf(){
    document.OrdForm.action=admin_webroot+"orders/batch_order_shipping_print_pdf";
    document.OrdForm.onsubmit= "";
    document.OrdForm.submit();
}

function batch_combined(){
    var id=document.getElementsByName('checkboxes[]');
    var i;
    var j=0;
    for( i=0;i<=parseInt(id.length)-1;i++ ){
        if(id[i].checked){
            j++;
        }
    }
    if( j<1 ){
        if(confirm(j_please_select))
        {
            return false;
        }
    }else{
        document.OrdForm.action=admin_webroot+"combineds/index/combined_status";
        document.OrdForm.onsubmit= "";
        document.OrdForm.submit();
    }
}

function export_flag(){
    var id=document.getElementsByName('checkboxes[]');
    var i;
    var j=0;
    for( i=0;i<=parseInt(id.length)-1;i++ ){
        if(id[i].checked){
            j++;
        }
    }
    if( j<1 ){
        if(confirm(j_please_select))
        {
            return false;
        }
    }else{
        document.OrdForm.action=admin_webroot+"orders/export_flag";
        document.OrdForm.onsubmit= "";
        document.OrdForm.submit();
    }
}

function formsubmit(){
    var order_status=document.getElementById('order_status').value;
    var order_code=document.getElementById('order_code').value;
    var product_keywords=document.getElementById('product_keywords').value;
    var consignee=document.getElementById('consignee').value;
    var start_date = document.getElementsByName('start_date')[0].value;
    var end_date = document.getElementsByName('end_date')[0].value;
    var type=document.getElementsByName('type');
    var min_amount=document.getElementById('min_amount').value;
    var max_amount=document.getElementById('max_amount').value;
    var reg=/^([1-9][\d]{0,7}|0)(\.[\d]{1,2})?$/;
    if(min_amount!=''&&!reg.test(min_amount)){min_amount='';}
    if(max_amount!=''&&!reg.test(max_amount)){max_amount='';}
    if(document.getElementById('select_to_type')!=null){
        var select_to_type=document.getElementById('select_to_type').value;
    }else{
        var select_to_type='';
    }
    if(document.getElementById('dealer_id')!=null){
        var dealer_id=document.getElementById('dealer_id').value;
    }else{
        var dealer_id='';
    }
	if(document.getElementById('expire_date_start')!=null){
        var expire_date_start=document.getElementById('expire_date_start').value;
    }else{
        var expire_date_start='';
    }
	if(document.getElementById('expire_date_end')!=null){
        var expire_date_end=document.getElementById('expire_date_end').value;
    }else{
        var expire_date_end='';
    }
    if(document.getElementById('lease_date_start')!=null){
        var lease_date_start=document.getElementById('lease_date_start').value;
    }else{
        var lease_date_start='';
    }
	if(document.getElementById('lease_date_end')!=null){
        var lease_date_end=document.getElementById('lease_date_end').value;
    }else{
        var lease_date_end='';
    }
//		var order_type='';
//		for (var i=0; i < type.length; i++) {
//			if(type[i].checked){
//				order_type += type[i].value+',';
//			}
//		};
//		order_type = order_type.substring(0,order_type.length-1);
    //var ta = checkbox();
    var str = '';
	str +="&expire_date_start="+expire_date_start;
	str +="&expire_date_end="+expire_date_end;
    //document.getElementById("ta").value=ta.substring(ta,ta.length-1);
    var url = "order_status="+order_status+"&lease_date_start="+lease_date_start+"&lease_date_end="+lease_date_end+"&order_code="+order_code+"&product_keywords="+product_keywords+"&consignee="+consignee+"&start_date="+start_date+"&end_date="+end_date+"&select_to_type="+select_to_type+"&dealer_id="+dealer_id+"&min_amount="+min_amount+"&max_amount="+max_amount+str;
    window.location.href = encodeURI(admin_webroot+"orders/lease_order?"+url);
}

function on_hide(){
    document.getElementById("dealer_id").style.display = (document.getElementById("select_to_type").options[1].selected ==true) ? "inline-block" : "none";
    document.getElementById("labden").style.display = (document.getElementById("select_to_type").options[1].selected ==true) ? "inline-block" : "none";
}

function changeOperators(){
    var Obj=document.getElementById("check1");
    var Obj1=document.getElementById("checkbox");
    if(Obj.checked==true){
        var  str=document.getElementById("check1").value;
        window.location.href = encodeURI(admin_webroot+"operators/index/"+"1"+"/"+str);
    }
    if(Obj1.checked==true){
        var  str=document.getElementById("checkbox").value;
        window.location.href = encodeURI(admin_webroot+"operators/index/"+"2"+"/"+str);
    }
}

function search_dealer(){
    var obj = document.getElementById("add_dealer_button");
    obj.className="disablebtn";
    var keywords=Trim(document.getElementById("deal").value);
    var postData = "keywords="+keywords;
    YUI().use("io",function(Y) {
        var cfg = {
            method: "POST",
            data: postData
        };
        var sUrl = admin_webroot+"orders/search_dealer/";//访问的URL地址
        var request = Y.io(sUrl, cfg);//开始请求
        var handleSuccess = function(ioId,o){
            if(o.responseText !== undefined){
                try{
                    eval('var result='+o.responseText);
                }catch(e){
                    alert(j_object_transform_failed);
                    alert(o.responseText);
                }
                var sel = document.getElementById('dealer_id');
                sel.innerHTML = "";
                if (result.message){
                    var opt = document.createElement("OPTION");
                    opt.value = "";
                    opt.text = "请选择";
                    sel.options.add(opt);
                    if(result.message.length==0){
                        alert('没有搜到相关经销商！');
                        document.getElementById('add_dealer_button').className="add_button";
                        return;
                    }
                    for (i = 0; i < result.message.length; i++ ){
                        var opt = document.createElement("OPTION");
                        opt.value = result.message[i]['Dealer'].id;
                        opt.text  = result.message[i]['Dealer'].name;
                        sel.options.add(opt);
                    }
                }
                obj.className="add_button";
                document.getElementById('dealer_id').className=""; }
        }
        var handleFailure = function(ioId,o){}
        Y.on('io:success', handleSuccess);
        Y.on('io:failure', handleFailure);
    });
}

function import_to_vendor(postData){
	$("#import_to_vendor").modal({closeViaDimmer:false});
	$("#import_to_vendor div.am-modal-bd").html('<i class="am-icon-spinner am-icon-spin am-icon-lg"></i>')
	
	$.ajax({url: admin_webroot+"orders/import_to_vendor",
            type:"POST",
            data:postData,
            dataType:"json",
            success: function(result){
                try{
                    if(result.flag=='1'){
                    	$("#import_to_vendor div.am-modal-bd").html(result.message);
                    }else{
                    	$("#import_to_vendor").modal('close');
                    	alert(result.message);
                    }
                }catch(e){
                	 $("#import_to_vendor").modal('close');
                    alert(j_object_transform_failed);
                }
            }
        });
}
</script>