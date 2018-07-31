<style type="text/css">
.linkshow{text-decoration:underline;color:green;}
#SearchForm{padding-top:8px;}
.am-form-label{font-weight:bold;margin-top:-6px; margin-left:15px;}
.am-form-label-text{margin-left:15px;}
.am-datepicker-dropdown{z-index:121000;}
#add_order .am-form-group,#batch_order_manager .am-form-group{margin-bottom:10px;}
</style>
<div id="order_list" class="am-u-md-12 am-u-sm-12 am-u-lg-12">
    <div class="listsearch">
        <?php echo $form->create('Order',array('action'=>'/','type'=>'get','id'=>"SearchForm",'class'=>'am-form am-form-inline am-form-horizontal','onsubmit'=>'return formsubmit();'));?>
        <input type="hidden" name="export_act_flag" id="export_act_flag" value=""/>
        <input id="select_code" name="select_code" type="hidden" value="" />
        <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1" id="order_do" style="margin:1px 0 0 0">
    		<?php if($svshow->operator_privilege("order_advanced")){ ?>
		<li style="margin:0 0 10px 0">
			<label class="am-u-lg-2  am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['administrator'];?></label>
			<div class="am-u-lg-9 am-u-md-7 am-u-sm-7  am-u-end">
				<select name="order_manager"  id="order_manager" data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?>',maxHeight:200}">
					<option value="-1"><?php echo $ld['all_data']?></option>
                    <option value="0" <?php echo isset($order_manager)&&$order_manager==0?'selected':''; ?>>未分配</option>
					<?php if(isset($operator_list)&&sizeof($operator_list)>0){foreach($operator_list as $k=>$v){ ?>
					<option value="<?php echo $k; ?>" <?php echo isset($order_manager)&&$order_manager==$k?'selected':''; ?>><?php echo $v; ?></option>
					<?php }} ?>
				</select>
			</div>
		</li>
		<?php } ?>
        
            <li style="margin:0 0 10px 0" >
                <label class="am-u-lg-2  am-u-md-3 am-u-sm-4 am-form-label-text" ><?php echo $ld['order_status'];?></label>
                <div class="am-u-lg-9 am-u-md-7 am-u-sm-7  am-u-end">
                    <select name="order_status"  id="order_status" data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?>',maxHeight:200}">
                        <option value="-1" selected><?php echo $ld['all_data']?> </option>
                        <option value="0" <?php if (isset($order_status)&&$order_status == 0){?>selected<?php }?>><?php echo $ld['unrecognized']?></option>
                        <option value="5" <?php if (isset($payment_status)&&$payment_status == 0){?>selected<?php }?>><?php echo $ld['unpaid']?></option>
                        <option value="10" <?php if (isset($shipping_status)&&$shipping_status == 0){?>selected<?php }?>><?php echo $ld['unreceived']?></option>
                        <option value="26" <?php if (isset($shipping_status)&&$shipping_status == 6){?>selected<?php }?>><?php echo $ld['for_pickup']?></option>
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
            <li style="margin:0 0 10px 0" >
                <label class="am-u-lg-2  am-u-md-3 am-u-sm-4 am-form-label-text" ><?php echo '审核状态';?></label>
                <div class="am-u-lg-9 am-u-md-7 am-u-sm-7  am-u-end">
                    <select name="check_status"  id="check_status" data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?>',maxHeight:200}">
                        <option value="-1" selected><?php echo $ld['all_data']?> </option>
                       
                        <option value="1" <?php if(isset($check_status)&&$check_status == 1){?>selected<?php }?>>
                            已审核
                        </option>
                        <option value="0" <?php if(isset($check_status)&&$check_status == 0){?>selected<?php }?>>
                            未审核
                        </option>
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
                <label class="am-u-lg-2  am-u-md-3  am-u-sm-4 am-form-label-text  "><?php echo $ld['orders_time']?></label>
                <div class="am-u-lg-4  am-u-md-3 am-u-sm-3" style="padding-right:0;width:37%;">
                    <div class="am-input-group">
                    <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="start_date" value="<?php echo $start_date;?>" />
                    <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
          </div>
                </div>
                <em class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center" style="padding: 0.35em 0px;width:4%;">-</em>
                <div class=" am-u-lg-4  am-u-md-3  am-u-sm-3 am-u-end" style="padding-left:0;padding-right:0;width:33%;">
                    <div class="am-input-group">
                    <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="end_date" value="<?php echo $end_date;?>" />
                    <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
          </div>
                </div>
            </li> 
            <li style="margin:0 0 10px 0">
                <label class="am-u-lg-2  am-u-md-3 am-u-sm-4 am-form-label-text" ><?php echo $ld['consignee']?></label>
                <div class=" am-u-lg-9 am-u-md-7 am-u-sm-7 am-u-end">  <input class="am-form-field " type="text" size="12" onkeypress="if(event.keyCode==13)formsubmit()" name="consignee" id="consignee" value="<?php echo $consignee?>">
                </div>
            </li> 

            <li style="margin:0 0 10px 0">
                <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['amount']?></label>
                <div class=" am-u-lg-4 am-u-md-3  am-u-sm-3" >
                    <input type="text" size="12" class="am-form-field am-input-sm" id="min_amount" name="min_amount" onkeydown="return writeamount(this,event.keyCode);" value="<?php echo $min_amount ?>" />
                </div>
                <em class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center  " style="padding: 0.35em 0px;">-</em>
                <div class="am-u-lg-4 am-u-md-3  am-u-sm-3 am-u-end" style="">
                    <input class="am-form-field am-input-sm" type="text" size="12" id="max_amount" name="max_amount" onkeydown="return writeamount(this,event.keyCode);" value="<?php echo $max_amount ?>" />
                </div>
            </li>
           
            <li  style="margin:0 0 10px 0">
                <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label-text" style="padding-right:0;"><?php echo $ld['sku'].'/'.$ld['z_name']?></label>
                <div class=" am-u-lg-9 am-u-md-7 am-u-sm-7 am-u-end">
                    <input class="am-form-field am-input-sm"  placeholder="<?php echo $ld['product_sku_or_name'];?>" type="text" onkeypress="if(event.keyCode==13)formsubmit()" name="product_keywords" id="product_keywords" value="<?php echo @$product_keywords?>">
                </div>
            </li>
			<?php if(isset($configs['order_product_expires'])&&$configs['order_product_expires']=='1'){ ?>
			<li  style="margin:0 0 10px 0">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label-text" style="top:-5px;"><?php echo $ld['order_goods'].$ld['time_to_maturity']?></label>
                <div class=" am-u-lg-4 am-u-md-3  am-u-sm-3" style="padding-right:0;width:37%;">
                    <div class="am-input-group">
                    <input type="text" class="am-form-field am-input-sm" name="expire_date_start" id="expire_date_start" value="<?php echo $expire_date_start; ?>" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly />
                    <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
          </div>
                </div>
                <em class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center  " style="padding: 0.35em 0px;width:4%;">-</em>
                <div class="am-u-lg-4 am-u-md-3  am-u-sm-3 am-u-end" style="padding-left:0;padding-right:0;">
                    <div class="am-input-group">
                    <input class="am-form-field am-input-sm" type="text" name="expire_date_end" id="expire_date_end" value="<?php echo $expire_date_end; ?>" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly />
                    <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
          </div>
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
    	    <span class="am-fl">
    	    <label class="am-checkbox am-success">
                <input type="checkbox" name="pro_show" id="pro_show" onclick="pro_show_change()" <?php if(isset($_GET['showitem'])&&$_GET['showitem']==1){echo "checked=checked";}else if(isset($_GET['showitem'])&&$_GET['showitem']==0){echo "";} else{if($configs['product_show']==1){echo "checked=checked";}} ?>>
                <?php echo $ld['show'].$ld['details'];?>
            </label>
    	    </span>
            <div class="am-fr am-btn-group-xs">
                <?php if($svshow->operator_privilege("warn_lists_view")){echo $html->link($ld['warn_lists'],"/warn_lists/",array('target'=>'_blank','style'=>'margin-top:0.5rem',"class"=>"am-btn am-btn-default am-btn-sm ")).'&nbsp;';}?>
        		<!-- <?php if($svshow->operator_privilege("configvalues_view")){echo $html->link($ld['order'].$ld['set_up'],"/orders/config",array('target'=>'_blank','class'=>' am-btn am-btn-default am-btn-sm')).'&nbsp;';}?> -->
        		<?php if($svshow->operator_privilege("combineds_view")){echo $html->link($ld['order_combination'],'/combineds/',array('class'=>'am-btn am-btn-default am-btn-sm','style'=>'margin-top:0.5rem')).'&nbsp;';}?>
        		<?php if($svshow->operator_privilege("orders_upload")){echo $html->link($ld["import_order"],"/orders/uploadorders/",array("class"=>"am-btn am-btn-sm am-btn-default",'style'=>'margin-top:0.5rem')).'&nbsp;';}?>
        		<?php echo $html->link($ld["import"].$ld["delivery"]."单","/orders/uploaddelivery/",array("class"=>"am-btn am-btn-sm am-btn-default",'style'=>'margin-top:0.5rem')).'&nbsp;';?>
        		<?php if($svshow->operator_privilege("orders_add")){?>
                <a class="am-btn am-btn-warning am-radius am-btn-sm" style="margin-top:0.5rem;" href="javascript:void(0);" data-am-modal="{target: '#add_order', closeViaDimmer:0}"><span class="am-icon-plus"></span><?php echo $ld['add_order'] ?></a>
                <?php } ?><div style="clear:both"></div>
            </div>
        </div>
        <?php echo $form->end();?>
    </div></div>
<div>
    <?php echo $form->create('',array('action'=>'/batch_order_shipping_print/',"name"=>"OrdForm",'onsubmit'=>"return false"));?>

    <!--电脑端-->
    <div class="am-u-md-12 am-u-sm-12">
        <table class="am-table">
            <thead class="am-hide-sm-only">
            <tr>
                <th style="padding-bottom:0;width:28%;"><label class="am-checkbox am-success" style="font-weight:700;margin-bottom:0.8rem;">
                        <span class="am-hide-sm-only" id="order_num"><input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck  type="checkbox" /></span>&nbsp;<b style="margin-left:-0.3rem;display:none;"><?php echo $ld['reffer']?></b><b style="margin-left:-0.3rem;"><?php echo $ld['order_code']?> (<?php echo $ld['products_number']?>)</b></label></th>
 
                <th style="width:15%;"><?php echo $ld['consignee']?><br><?php echo $ld['remarks_notes']?></th>
                <th style="width:15%;"><span><?php echo $ld['order_total_amount']?><br ><?php echo $ld['payment_time'] ?></span></th>
                <th style="white-space:nowrap;width:15%;"><?php echo $ld['order_status']?></th>
                <th style="white-space:nowrap;width:15%;" class="am-hide-md-down"><?php echo $ld['shipping']?><br><?php echo $ld['shipping_status']?></th>
                <?php if(isset($apps['Applications']['APP-API-WEBSERVICE'])){?>
                    <th class="thicon">同步状态</th>
                <?php }?>
                <?php if(isset($configs['vendor_shipment'])&&$configs['vendor_shipment']=='1'&&$svshow->operator_privilege("view_factory_time")){ ?>
                <th class="am-hide-md-down"><?php echo $ld['vendor_information'] ?></th>
                <?php } ?>
                <th class="am-text-left"><?php echo $ld['operate']?></th>
            </tr>
            </thead>
            <tbody class="am-hide-sm-only">
                <?php //pr($orders_list); ?>
            <?php if(isset($orders_list) && sizeof($orders_list)>0){foreach($orders_list as $k=>$v){
                    $order_product_number=0;
                    $order_id=$v['Order']['id'];
                    $order_product_data=isset($pro_infos[$order_id])?$pro_infos[$order_id]:array();
                    $package_product=array();
                    foreach($order_product_data as $vv){
                        if(intval($vv['OrderProduct']['parent_product_id'])>0){
                            $package_product[$vv['OrderProduct']['parent_product_id']]=$vv['OrderProduct']['parent_product_id'];
                        }
                    }
                    foreach($order_product_data as $vv){
                        if(isset($package_product[$vv['OrderProduct']['product_id']]))continue;
                        $order_product_number+=$vv['OrderProduct']['product_quntity'];
                    }
            ?>
            <tr>
                <td style="padding-top:0;"><label class="am-checkbox am-success">
                    <?php echo isset($ld[$v['Order']['type']])?$ld[$v['Order']['type']]:$v['Order']['type'];
                    if(isset($v['Order']['type_id'])&&$v['Order']['type_id']=="front"){echo "-".$ld['frontend'];}else if(isset($ld[$v['Order']['type_id']])){echo "-".$ld[$v['Order']['type_id']];}else{echo $v['Order']['type_id'];}?><br />
                        <span class="am-hide-sm-only"><input type="checkbox" name="checkboxes[]" value="<?php echo $v['Order']['id']?>"  data-am-ucheck /></span><?php echo $html->link("{$v['Order']['order_code']}","/orders/{$v['Order']['id']}",array("target"=>"_blank"),false,false);?> (<?php echo $order_product_number;?>)</label>
                </td>
 
                <td><?php echo $v['Order']['consignee'];if(!empty($v['Order']['note']) || !empty($v['Order']['postscript'])){?>
                    <div>
                        <?php }?>
            <?php echo !empty($v['Order']['note'])?"卖家留言：".$v['Order']['note']:"";if(!empty($v['Order']['note'])&&!empty($v['Order']['postscript'])){echo "<br/>";}echo !empty($v['Order']['postscript'])?" 买家留言：".$v['Order']['postscript']:"";?>
    <?php if(!empty($v['Order']['note'])||!empty($v['Order']['postscript'])){?></div><?php }?><div></div><span class='order_manager_info' style="" data-value="<?php echo $v['Order']['id']; ?>"><?php echo isset($operator_list[$v['Order']['order_manager']])?$operator_list[$v['Order']['order_manager']]:'-'; ?></span></td>
                <td><?php echo $svshow->price_format(sprintf("%01.2f",$v['Order']['total']),$configs['price_format']);?>
                    <?php if(isset($v['Order']['payment_status'])&&$v['Order']['payment_status']==2){?>
                    <?php echo "<br>".$v['Order']['payment_time'] ?>
                    <?php }?>
                </td>
                <td style="white-space:nowrap; " ><?php if( $v['Order']['status']!=1 ){?>
                        <?php echo $Resource_info["order_status"][$v['Order']['status']];?>
                        <?php }elseif( $v['Order']['payment_status']==0 &&$v['Order']['paymenttype']==1){?>
                        <?php echo $Resource_info["shipping_status"][$v['Order']['shipping_status']];?>
                        <?php }elseif( $v['Order']['payment_status']!=2){?>
                        <?php echo $Resource_info["payment_status"][$v['Order']['payment_status']];?>
                        <?php }else{?>
                        <?php echo $Resource_info["shipping_status"][$v['Order']['shipping_status']];?>
                        <?php }?><br>
                        <?php if($v['Order']['check_status'] == '1'){
                            echo '已审核';
                        }else{echo '未审核';} ?>
                </td>
                <td class="am-hide-md-down" ><?php echo $v['Order']['shipping_name']?></td>
                <?php if(isset($apps['Applications']['APP-API-WEBSERVICE'])){?>
                <td><?php if($v['Order']['export_flag'] != 2){
                    if(isset($v['Order']['ec_export_flag'])){
                    echo $v['Order']['ec_export_flag'];
                    }
                    if($v['Order']['export_flag'] == 1){
                    echo "<br> 暂停同步中";
                    }
                    }else{
                    echo $html->image('unfound.png',array("alt"=>$v['Order']['message'],"title"=>$v['Order']['message']));
                    }?></td>

                <?php }?>
                <?php if(isset($configs['vendor_shipment'])&&$configs['vendor_shipment']=='1'&&$svshow->operator_privilege("view_factory_time")){ ?>
                <td  class="purchase_order_detail_<?php echo $v['Order']['order_code'] ?> am-hide-md-down">
                    <span><?php echo $ld['pre_shipment'] ?>:</span>
                    <span class="ESD"><?php echo isset($purchase_order_list[$v['Order']['order_code']]['ESD']) && $purchase_order_list[$v['Order']['order_code']]['ESD']!="0000-00-00"?$purchase_order_list[$v['Order']['order_code']]['ESD']:''; ?></span><br />
                    <span><?php echo $ld['real_shipment'] ?>:</span>
                    <span class="ASD"><?php echo isset($purchase_order_list[$v['Order']['order_code']]['ASD']) && $purchase_order_list[$v['Order']['order_code']]['ASD']!="0000-00-00"?$purchase_order_list[$v['Order']['order_code']]['ASD']:''; ?></span>
                    <div class="company_detail">
                        <?php echo isset($purchase_order_list[$v['Order']['order_code']]['logistics_company_id']) && isset($purchase_order_list[$v['Order']['order_code']]['logistics_company_id'])?(isset($logistics_companys[$purchase_order_list[$v['Order']['order_code']]['logistics_company_id']])?$logistics_companys[$purchase_order_list[$v['Order']['order_code']]['logistics_company_id']]:''):'';
                        echo isset($purchase_order_list[$v['Order']['order_code']]['invoice_no']) && $purchase_order_list[$v['Order']['order_code']]['invoice_no']!=""?'<br />'.$purchase_order_list[$v['Order']['order_code']]['invoice_no']:''; ?>
                    </div>
                    <?php if($svshow->operator_privilege("add_factory_time")||$svshow->operator_privilege("edit_factory_time")){    ?>
                    <button class="am-btn am-btn-default am-radius am-btn-xs am-text-secondary" type="button" onclick='change_purchase_order("<?php echo $v["Order"]["order_code"];?>")'><span class="am-icon-pencil"></span><?php echo $ld["edit"];?></button>
                    <?php } ?>
                </td>
                <?php } ?>
                <td  class="am-text-left am-btn-group-xs am-action" style="min-width:180px;"><?php
                    if($svshow->operator_privilege("orders_view")){ ?>
                    <a class="am-btn am-btn-default  am-btn-success am-seevia-btn  am-btn-xs " href="<?php echo $html->url('/orders/view/'.$v['Order']['id']); ?>">
                        <span class="am-icon-eye"></span> <?php echo $ld['preview'] ?>
                    </a>
                      <?php }
                    if($svshow->operator_privilege("orders_edit")){ ?>
                    <a class="am-seevia-btn-edit am-btn am-btn-default am-btn-xs am-text-secondary" href="<?php echo $html->url('/orders/edit/'.$v['Order']['id']); ?>">
                        <span class="am-icon-pencil-square-o"> </span> <?php echo $ld['edit']; ?>
                    </a>
                    <?php }
                    if($svshow->operator_privilege("orders_redelivery")&&$v['Order']['shipping_status']==1&&$v['Order']['status']==1){ ?>
                    <a class="am-btn  am-btn-default am-btn-xs am-text-secondary" target='_blank' href="<?php echo $html->url('/orders/new_order/'.$v['Order']['order_code']); ?>">
                        <span class="am-icon-copy"></span><?php echo $ld['order_redelivery'] ?>
                    </a>
                  
                    <?php }
                    if($svshow->operator_privilege("orders_print")){
                    //echo $html->link($ld['order_print'],"/orders/batch_order_shipping_print/{$v['Order']['id']}",array("class"=>"am-btn am-btn-default am-btn-xs am-text-secondary","target"=>"_blank",'escape' => false));
                    }
                    if($svshow->operator_privilege("orders_remove")&&$v['Order']['payment_status']!=2&&$v['Order']['shipping_status']!=1&&$v['Order']['shipping_status']!=2){
                    //echo $html->link($ld['delete'],"javascript:;",array("onclick"=>"if(confirm('{$ld['confirm_delete_order']}')){window.location.href='{$admin_webroot}orders/delete_order/{$v['Order']['id']}';}","class"=>"am-btn am-btn-default am-btn-xs am-text-secondary",'escape' => false));
                    }
                    ?></td>
            </tr>
            <?php  if(isset($_GET['showitem']) && $_GET['showitem']==1 && !empty($order_product_data) || !isset($_GET['showitem']) && $configs['product_show']==1 && !empty($order_product_data)){ ?>
            <tr class="info_tr" name="product_info" >
                <td style="padding-left:0" colspan="7">
                    <table>
                        <?php foreach($order_product_data as $vv){ ?>
                        <tr>
                            <td  width='50%' style="padding-left:2.7rem;"><?php echo @$html->image(isset($order_product_info[$vv['OrderProduct']['product_id']]['img_thumb'])?$order_product_info[$vv['OrderProduct']['product_id']]['img_thumb']:'',array("class"=>"am-img")); ?><p class="p_name"><?php echo $vv["OrderProduct"]["product_name"]; ?><br /><?php echo $vv["OrderProduct"]["product_code"]; ?></p></td>
                            <td width='15%'><s>￥<?php echo isset($order_product_info[$vv['OrderProduct']['product_id']]['market_price'])?$order_product_info[$vv['OrderProduct']['product_id']]['market_price']:'0.00'; ?></s>/<?php echo $vv['OrderProduct']['product_price']; ?></td>
                            <td width='10%'><?php echo $vv['OrderProduct']['product_quntity']; ?></td>
                            <td width='10%'>￥<?php echo $vv['OrderProduct']['product_quntity']*$vv['OrderProduct']['product_price']; ?></td>
                            <td style='width:145px'>&nbsp;</td>
                        </tr>
                        <?php }
                            $sale=!empty($v['Order']['note'])?"卖家留言：".$v['Order']['note']:"";
                                $buy= !empty($v['Order']['postscript'])?" 买家留言：".$v['Order']['postscript']:"";
                                $str=$sale.$buy;
                                // if(!empty($str))echo "<tr><td style='text-align:left' colspan='9'>".$str."</td></tr>";
                        ?>
                    </table>
                </td>
            </tr>
            <?php }?>
                <?php }}else{?>
            <tr>
                <td colspan="7" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
            <?php }?>
            </tbody>
              </table>
            <!-- 手机端 -->
            <div class="am-show-sm-only am-g" style="border-top:1px solid #ddd;">
            <?php if(isset($orders_list) && sizeof($orders_list)>0){foreach($orders_list as $k=>$v){
                    $order_product_number=0;
                    $order_id=$v['Order']['id'];
                    $order_product_data=isset($pro_infos[$order_id])?$pro_infos[$order_id]:array();
                    $package_product=array();
                    foreach($order_product_data as $vv){
                        if(intval($vv['OrderProduct']['parent_product_id'])>0){
                            $package_product[$vv['OrderProduct']['parent_product_id']]=$vv['OrderProduct']['parent_product_id'];
                        }
                    }
                    foreach($order_product_data as $vv){
                        if(isset($package_product[$vv['OrderProduct']['product_id']]))continue;
                        $order_product_number+=$vv['OrderProduct']['product_quntity'];
                    }
            ?>
                <div class="am-u-sm-1" style="padding-top:0;margin-bottom:1rem;margin-top:0.5rem;padding-right:0;width:6%;">
                    <label class="am-checkbox am-success" style="margin-top:0;margin-bottom:0;padding-left:0;line-height:1.1;padding-left:1.8rem;">
                         <span class="" style="display:inline-block;"><input type="checkbox" name="checkboxes[]" value="<?php echo $v['Order']['id']?>"  data-am-ucheck /></span>
                    </label>
                </div>
                <div style="padding-top:0;padding-left:0;margin-bottom:1rem;margin-top:0.5rem;" class="am-u-lg-12 am-u-md-12 am-u-sm-11">
                <div class="am-cf">
                    <div class="am-u-sm-9" style="margin-bottom:1rem;">
                    <label class="am-checkbox am-success" style="margin-top:0;margin-bottom:0;padding-left:0;line-height:1.1;">
                    <?php echo $v['Order']['type']."  ".$v['Order']['type_id']?><br />
                       <?php echo $html->link("{$v['Order']['order_code']}","/orders/{$v['Order']['id']}",array("target"=>"_blank"),false,false);?> (<?php echo $order_product_number;?>)</label></div>
                       <!-- 订单状态 -->
                       <div style="white-space:nowrap; margin-bottom:1rem;" class="am-u-sm-3"><?php if( $v['Order']['status']!=1 ){?>
                        <?php echo $Resource_info["order_status"][$v['Order']['status']];?>
                        <?php }elseif( $v['Order']['payment_status']==0 &&$v['Order']['paymenttype']==1){?>
                        <?php echo $Resource_info["shipping_status"][$v['Order']['shipping_status']];?>
                        <?php }elseif( $v['Order']['payment_status']!=2 &&$v['Order']['shipping_status']==0){?>
                        <?php echo $Resource_info["payment_status"][$v['Order']['payment_status']];?>
                        <?php }else{?>
                        <?php echo $Resource_info["shipping_status"][$v['Order']['shipping_status']];?>
                        <?php }?>
                </div>
            </div>
            <!-- 收货人备注 -->
                       <div class="am-u-sm-4" style="margin-bottom:1rem;"><?php echo $v['Order']['consignee'];if(!empty($v['Order']['note']) || !empty($v['Order']['postscript'])){?>
                    <div>
                        <?php }?>
             <?php //echo !empty($v['Order']['note'])?"卖家留言：".$v['Order']['note']:"";if(!empty($v['Order']['note'])&&!empty($v['Order']['postscript'])){echo "<br/>";}echo !empty($v['Order']['postscript'])?" 买家留言：".$v['Order']['postscript']:"";?>
    <?php if(!empty($v['Order']['note'])||!empty($v['Order']['postscript'])){?></div><?php }?><div></div><span class='order_manager_info' style="" data-value="<?php echo $v['Order']['id']; ?>"><?php echo isset($operator_list[$v['Order']['order_manager']])?$operator_list[$v['Order']['order_manager']]:'-'; ?></span></div>
                <div class="am-u-sm-5" style="margin-bottom:1rem;"><?php echo $svshow->price_format(sprintf("%01.2f",$v['Order']['should_pay']),$configs['price_format']);?>
                    <?php if(isset($v['Order']['payment_status'])&&$v['Order']['payment_status']==2){?>
                    <?php echo "<br>".$v['Order']['payment_time'] ?>
                    <?php }?>
                </div>

                
                <div class="am-u-sm-3 margin-bottom:1rem;" style="padding-right:0;"><?php echo $v['Order']['shipping_name']?></div>
                </div>
 
                
                <?php if(isset($apps['Applications']['APP-API-WEBSERVICE'])){?>
                <td><?php if($v['Order']['export_flag'] != 2){
                    if(isset($v['Order']['ec_export_flag'])){
                    echo $v['Order']['ec_export_flag'];
                    }
                    if($v['Order']['export_flag'] == 1){
                    echo "<br> 暂停同步中";
                    }
                    }else{
                    echo $html->image('unfound.png',array("alt"=>$v['Order']['message'],"title"=>$v['Order']['message']));
                    }?></td>

                <?php }?>
                <?php if(isset($configs['vendor_shipment'])&&$configs['vendor_shipment']=='1'&&$svshow->operator_privilege("view_factory_time")){ ?>
                <td  class="purchase_order_detail_<?php echo $v['Order']['order_code'] ?> am-hide-md-down">
                    <span><?php echo $ld['pre_shipment'] ?>:</span>
                    <span class="ESD"><?php echo isset($purchase_order_list[$v['Order']['order_code']]['ESD']) && $purchase_order_list[$v['Order']['order_code']]['ESD']!="0000-00-00"?$purchase_order_list[$v['Order']['order_code']]['ESD']:''; ?></span><br />
                    <span><?php echo $ld['real_shipment'] ?>:</span>
                    <span class="ASD"><?php echo isset($purchase_order_list[$v['Order']['order_code']]['ASD']) && $purchase_order_list[$v['Order']['order_code']]['ASD']!="0000-00-00"?$purchase_order_list[$v['Order']['order_code']]['ASD']:''; ?></span>
                    <div class="company_detail">
                        <?php echo isset($purchase_order_list[$v['Order']['order_code']]['logistics_company_id']) && isset($purchase_order_list[$v['Order']['order_code']]['logistics_company_id'])?(isset($logistics_companys[$purchase_order_list[$v['Order']['order_code']]['logistics_company_id']])?$logistics_companys[$purchase_order_list[$v['Order']['order_code']]['logistics_company_id']]:''):'';
                        echo isset($purchase_order_list[$v['Order']['order_code']]['invoice_no']) && $purchase_order_list[$v['Order']['order_code']]['invoice_no']!=""?'<br />'.$purchase_order_list[$v['Order']['order_code']]['invoice_no']:''; ?>
                    </div>
                    <?php if($svshow->operator_privilege("add_factory_time")||$svshow->operator_privilege("edit_factory_time")){    ?>
                    <button class="am-btn am-btn-default am-radius am-btn-xs am-text-secondary" type="button" onclick='change_purchase_order("<?php echo $v["Order"]["order_code"];?>")'><span class="am-icon-pencil"></span><?php echo $ld["edit"];?></button>
                    <?php } ?>
                </td>
                <?php } ?>
                <div  class="am-btn-group-xs am-action am-u-sm-12" style="border-bottom:1px solid #ddd;padding-bottom:0.2rem;"><?php
                    if($svshow->operator_privilege("orders_redelivery")&&$v['Order']['shipping_status']==1&&$v['Order']['status']==1){ ?>
                    <a class="am-btn  am-btn-default am-btn-xs am-text-secondary" style="float:right;margin-left:0.3rem;" target='_blank' href="<?php echo $html->url('/orders/new_order/'.$v['Order']['order_code']); ?>">
                        <span class="am-icon-copy"></span><?php echo $ld['order_redelivery'] ?>
                    </a>                 
                    <?php }
                    if($svshow->operator_privilege("orders_edit")){ ?>
                    <a class="am-seevia-btn-edit am-btn am-btn-default am-btn-xs am-text-secondary" style="float:right;margin-left:0.3rem" href="<?php echo $html->url('/orders/edit/'.$v['Order']['id']); ?>">
                        <span class="am-icon-pencil-square-o"> </span> <?php echo $ld['edit']; ?>
                    </a>
                    <?php }
                    if($svshow->operator_privilege("orders_view")){ ?>
                    <a class="am-btn am-btn-default  am-btn-success am-seevia-btn  am-btn-xs " style="float:right;" href="<?php echo $html->url('/orders/view/'.$v['Order']['id']); ?>">
                        <span class="am-icon-eye"></span> <?php echo $ld['preview'] ?>
                    </a>
                      <?php }
                    
                    
                    if($svshow->operator_privilege("orders_print")){
                    //echo $html->link($ld['order_print'],"/orders/batch_order_shipping_print/{$v['Order']['id']}",array("class"=>"am-btn am-btn-default am-btn-xs am-text-secondary","target"=>"_blank",'escape' => false));
                    }
                    if($svshow->operator_privilege("orders_remove")&&$v['Order']['payment_status']!=2&&$v['Order']['shipping_status']!=1&&$v['Order']['shipping_status']!=2){
                    //echo $html->link($ld['delete'],"javascript:;",array("onclick"=>"if(confirm('{$ld['confirm_delete_order']}')){window.location.href='{$admin_webroot}orders/delete_order/{$v['Order']['id']}';}","class"=>"am-btn am-btn-default am-btn-xs am-text-secondary",'escape' => false));
                    }
                    ?></div>
            <?php  if(isset($_GET['showitem']) && $_GET['showitem']==1 && !empty($order_product_data) || !isset($_GET['showitem']) && $configs['product_show']==1 && !empty($order_product_data)){ ?>
            <tr class="info_tr" name="product_info" >
                <td style="padding-left:0" colspan="7">
                    <table style="border-bottom:1px solid #ddd;width:100%;" class="order_1">
                        <?php foreach($order_product_data as $vv){ ?>
                        <tr class="am-u-sm-1" style="padding-right:0;margin-bottom:0.4rem;margin-top:0.4rem;">
                            <td>&nbsp;</td>
                        </tr>
                        <tr class="am-u-sm-11" style="padding-left:0;margin-bottom:0.4rem;margin-top:0.4rem;">
                            <td  class="am-u-sm-8 am-cf" style="padding-left:0;"><?php echo @$html->image(isset($order_product_info[$vv['OrderProduct']['product_id']]['img_thumb'])?$order_product_info[$vv['OrderProduct']['product_id']]['img_thumb']:'',array("class"=>"am-img am-u-sm-4","style"=>"width:60px;height:60px;padding-left:0;padding-right:0;")); ?><p class="p_name am-u-sm-7" style="word-break:break-all;line-height:1.2;padding-left:0;"><?php echo $vv["OrderProduct"]["product_name"]; ?><br /><?php echo $vv["OrderProduct"]["product_code"]; ?></p></td>
                           <!--  <td width='15%'><s>￥<?php echo isset($order_product_info[$vv['OrderProduct']['product_id']]['market_price'])?$order_product_info[$vv['OrderProduct']['product_id']]['market_price']:'0.00'; ?></s>/<?php echo $vv['OrderProduct']['product_price']; ?></td> -->
                            <td class="am-u-sm-4" style="line-height:1.2;">数量：<?php echo $vv['OrderProduct']['product_quntity']; ?></td>
                           <!--  <td width='10%'>￥<?php echo $vv['OrderProduct']['product_quntity']*$vv['OrderProduct']['product_price']; ?></td> -->
                            <!-- <td style='width:145px'>&nbsp;</td> -->
                        </tr>
                        <?php }
                            $sale=!empty($v['Order']['note'])?"卖家留言：".$v['Order']['note']:"";
                                $buy= !empty($v['Order']['postscript'])?" 买家留言：".$v['Order']['postscript']:"";
                                $str=$sale.$buy;
                                // if(!empty($str))echo "<tr><td style='text-align:left' colspan='9'>".$str."</td></tr>";
                        ?>
                    </table>
                </td>
            </tr>
            <?php }?>
                <?php }}else{?>
            <tr>
                <td colspan="7" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
            <?php }?>
            </div>
      
        <?php if(isset($orders_list) && sizeof($orders_list)>0){ ?>
        <div id="btnouterlist" class="btnouterlist btnouterlist am-form-group">
            <div class="am-u-lg-6 am-u-md-12 am-u-sm-12">
                <div class="am-fl">
                    <label class="am-checkbox am-success" style="margin:5px 5px 5px 0px;"><input onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)"  class="am-btn am-radius am-btn-success am-btn-sm" type="checkbox" data-am-ucheck /><?php echo $ld['select_all']?></label>
                </div>
                <div class="am-fl" style="margin-right:5px;">
                    <select id="order_operations_select" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}" onchange="changetype()">
                        <option value=""><?php echo $ld['all_data']?></option>
                        <?php if($svshow->operator_privilege("order_advanced")){?>
                        <option value="batch_order_manager"><?php echo $ld['edit'].$ld['administrator']; ?></option>
                        <?php } ?>
                        <?php if($svshow->operator_privilege("orders_remove")){?>
                        <option value="delete"><?php echo $ld['batch_delete']?></option>
                        <?php }?>
                        <option value="order_batch_check">批量审核</option>
                        <option value="order_batch_check_remove">批量取消审核</option>
        		     <?php if( $svshow->operator_privilege("orders_print")){?>
                        <option value="order_batch_print"><?php echo $ld["order_batch_print"];?></option>
                        <option value="batch_shipping_print_pdf"><?php echo $ld["mass_production"];?> PDF</option>
                        <?php }?>
        				<?php if($svshow->operator_privilege('combineds_view')){?>
                        <option value="batch_combined"><?php echo $ld['order_batch_merge']?></option>
                        <?php }?>
        				<?php if(isset($apps['Applications']['APP-API-WEBSERVICE'])){?>
                            <option value="export_flag">设置为已同步</option>
                        <?php }?>
                        <option value="export_act"><?php echo $ld['batch_export']?></option>
                        <option value="search_result"><?php echo $ld['search_export']?></option>
                        <option value="export_del">导出抓货单</option>
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


<div class="am-modal am-modal-no-btn tablemain" tabindex="-1" id="add_order" >
    <div class="am-modal-dialog">
        <div class="am-modal-hd">
            <?php echo $ld['add_order']; ?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
    		<?php echo $form->create('Order',array('action'=>'/add','type'=>'get','class'=>'am-form am-form-horizontal'));?>
                <div class="am-form-group">
	                    <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label am-text-right" style="padding-left:0;"><?php echo '服务类型';?>:</label>
	                    <div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
	                        <select name="order_service_type" data-am-selected="{noSelectedText:'<?php echo $ld['please_select'] ?>'}">
	                            	<option value=''><?php echo $ld['please_select'] ?></option>
	                            	<?php if(isset($Resource_info['order_service_type'])&&sizeof($Resource_info['order_service_type'])>0){foreach($Resource_info['order_service_type'] as $k=>$v){ ?>
	                            	<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
	                            	<?php }} ?>
	                        </select>
	                    </div>
                </div>
                <?php if($svshow->operator_privilege("order_advanced")){ ?>
                <div class="am-form-group">
                		<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label am-text-right"><?php echo $ld['administrator'];?>:</label>
                		<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
                			<select name="order_manager" data-am-selected="{noSelectedText:'<?php echo $ld['please_select'] ?>',maxHeight:150}">
	                            	<option value='0'><?php echo $ld['please_select'] ?></option>
	                            	<?php if(isset($operator_list)&&sizeof($operator_list)>0){foreach($operator_list as $k=>$v){ ?>
	                            	<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
	                            	<?php }} ?>
	                        </select>
                		</div>
                </div>
                <?php } ?>
                <div class="am-form-group">
            		<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">&nbsp;</label>
            		<div class="am-u-lg-7 am-u-md-7 am-u-sm-7 am-text-left">
            			<input type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['confirm']?>">
            		</div>
                </div>
            </form>
        </div>
    </div>
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

<!-- 供应商信息弹窗start 
<div class="am-popup" id="purchase_order">
    <form class="am-form">
        <div class="am-popup-inner">
            <div class="am-popup-hd" style=" z-index: 11;">
                <h4 class="am-popup-title"><?php echo $ld['vendor_information'];?></h4>
                <span data-am-modal-close class="am-close">&times;</span>
            </div>
            <div class="am-popup-bd" >
                <table class="am-table">
                    <tr>
                        <th width="20%"><?php echo $ld['pre_shipment'];?>:</th>
                        <td><span id="ESD_txt"></span><label class='label_calendar'>
                                <input type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly id="ESD" value=""  /></label></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['real_shipment'];?>:</th>
                        <td><span id="ASD_txt"></span><label class='label_calendar'>
                                <input type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly id="ASD" value="" /></label></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['order_logistics_company'] ?></th>
                        <td>
                            <span id="po_logistics_company_txt"></span>
                            <select id="po_logistics_company">
                                <option value="0"><?php echo $ld['please_select'] ?></option>
                                <?php if(isset($logistics_companys)&&sizeof($logistics_companys)>0){foreach($logistics_companys as $k=>$v){ ?>
                                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                <?php }} ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['invoice_number'] ?></th>
                        <td><span id="po_invoice_no_txt"></span><input type="text" id="po_invoice_no" value="" /></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input type="button" class="am-btn am-btn-success am-radius am-btn-sm po_save_btn" onclick="save_purchase_order()" value="<?php echo $ld['confirm']?>">
                            <input type="hidden" id="po_order_code" value="">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </form>
</div>
供应商信息弹窗end -->


<!-- import_to_vendor 
<div class="am-modal am-modal-no-btn" tabindex="-1" id="import_to_vendor">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">导入供应商
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
      	
    </div>
  </div>
</div>-->
<!-- import_to_vendor -->

<!-- 更新订单管理员 -->
<div class="am-modal am-modal-no-btn tablemain" tabindex="-1" id="batch_order_manager" >
    <div class="am-modal-dialog">
        <div class="am-modal-hd">
            <?php echo $ld['edit'].$ld['administrator']; ?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
    		    <?php echo $form->create('Order',array('action'=>'/','type'=>'POST','class'=>'am-form am-form-horizontal'));?>
                <div class="am-form-group">
                		<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label am-text-right"><?php echo $ld['administrator'];?>:</label>
                		<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
                			<select name="order_manager" data-am-selected="{noSelectedText:'<?php echo $ld['please_select'] ?>',maxHeight:150}">
	                            	<option value='0'><?php echo $ld['please_select'] ?></option>
	                            	<?php if(isset($operator_list)&&sizeof($operator_list)>0){foreach($operator_list as $k=>$v){ ?>
	                            	<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
	                            	<?php }} ?>
	                        </select>
                		</div>
                </div>
                <div class="am-form-group">
            		<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">&nbsp;</label>
            		<div class="am-u-lg-7 am-u-md-7 am-u-sm-7 am-text-left">
            			<input type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick="batch_order_manager(this)" value="<?php echo $ld['confirm']?>">
            		</div>
                </div>
            </form>
        </div>
    </div>
</div>
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
    if(order_operations_select.value=="batch_order_manager"){
    		if(postData==''){
    			alert(j_please_select+orders+" !");
        		return false;
    		}
    		$("#batch_order_manager").modal("open")
    		return false;
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

function pro_show_change(){
    var check=document.getElementById("pro_show");
    var url=window.location.href;
    var show="showitem";
    var search="?";
    var patt=new RegExp(show);
    var flag=0;
    for(var i=0;i<url.length;i++){
        if(url.substring(i,i+1)=="?"){
            flag++;
        }
    }
    if(check.checked==true){
        if(patt.test(url)){
            url=url.replace("showitem=0","showitem=1");
            window.location.href=url;
        }else{
            if(flag>0){
                window.location.href=url+"&showitem=1";
            }
            else{
                window.location.href=url+"?showitem=1";
            }
        }
    }else{
        if(patt.test(url)){
            url=url.replace("showitem=1","showitem=0");
            window.location.href=url;
        }
        else{
            if(flag>0){
                window.location.href=url+"&showitem=0";
            }
            else{
                window.location.href=url+"?showitem=0";
            }
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
	if(document.getElementById('order_manager')!=null){
		var order_manager=document.getElementById('order_manager').value;
	}else{
		var order_manager=0;
	}
    var order_status=document.getElementById('order_status').value;
    var order_code=document.getElementById('order_code').value;
    var product_keywords=document.getElementById('product_keywords').value;
    var consignee=document.getElementById('consignee').value;
    var check_status=document.getElementById('check_status').value;
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
    if(document.getElementById('exp_flag')!=null){
        var exp_flag=document.getElementById('exp_flag').value;
    }else{
        var exp_flag='-1';
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
//		var order_type='';
//		for (var i=0; i < type.length; i++) {
//			if(type[i].checked){
//				order_type += type[i].value+',';
//			}
//		};
//		order_type = order_type.substring(0,order_type.length-1);
    //var ta = checkbox();
    var str = '';
    var showitem=document.getElementById("pro_show").checked==true?"1":"0";
    str +="&showitem="+showitem;
	str +="&expire_date_start="+expire_date_start;
	str +="&expire_date_end="+expire_date_end;
    //document.getElementById("ta").value=ta.substring(ta,ta.length-1);
    var url = "order_manager="+order_manager+"&exp_flag="+exp_flag+"&order_status="+order_status+"&order_code="+order_code+"&check_status="+check_status+"&product_keywords="+product_keywords+"&consignee="+consignee+"&start_date="+start_date+"&end_date="+end_date+"&select_to_type="+select_to_type+"&dealer_id="+dealer_id+"&min_amount="+min_amount+"&max_amount="+max_amount+str;
    window.location.href = encodeURI(admin_webroot+"orders?"+url);
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

function batch_order_manager(btn){
	var order_id_list=document.getElementsByName('checkboxes[]');
	var order_ids=[];
	for(var i=0;i<order_id_list.length;i++){
		if(order_id_list[i].checked){
			order_ids.push(order_id_list[i].value);
		}
	}
	if(order_ids.length>0){
		var order_manager=$(btn).parents('form').find("select[name='order_manager']").val();
		$.ajax({
			url: admin_webroot+"orders/ajax_batch_order_manager",
			type:"POST",
			data:{'order_manager':order_manager,'order_ids':order_ids},
			dataType:"json",
			success: function(data){
				alert(data.message);
				if(data.code=='1'){
					window.location.reload();
				}
			}
		});
	}
}

$("span.order_manager_info").dblclick(function(){
	var child_tag=$(this).children(":eq(0)").length;
	if(child_tag>0)return false;
	var order_id=$(this).attr("data-value");
	if(typeof(order_id)=='undefined')order_id=parseInt(order_id);
	var span_obj=$(this);
	var order_manager_text=$(this).text().trim();
	order_manager_text=order_manager_text=='-'?'':order_manager_text;
	var order_manager_select=document.createElement('select');
	order_manager_select.options.add(new Option(j_please_select,'0'));
	$("select[name='order_manager']:eq(0) option").each(function(index,item){
		if(item.value!='0'&&item.value!=''){
			order_manager_select.options.add(new Option(item.text,item.value,true,item.text.trim()==order_manager_text));
		}
	});
	$(this).html('');
	$(this).html(order_manager_select);
	order_manager_select.onchange=function(){
		var sel_index=order_manager_select.selectedIndex;
		var val = order_manager_select.options[sel_index].value;
		$.ajax({
			type: "POST",
			url:admin_webroot+'orders/ajax_batch_order_manager',
			data:{'order_manager':val,'order_ids':order_id},
			dataType:"json",
			success: function(data) {
				try{
					if(data.code=='1'){
						var select_text=val==0?"-":order_manager_select.options[sel_index].text;
						span_obj.html(select_text);
					}else{
						alert(data.message);
						span_obj.html(order_manager_text);
					}
				}catch(e){
					alert(j_object_transform_failed);
					obj.innerHTML = order_manager_text;
				}
			}
		});
	};
});

</script>