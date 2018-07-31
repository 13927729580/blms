<?php if($is_ajax==0){?>
    <script type="text/javascript">
        var user_address_obj = <?php echo $user_addresses_json;?>;
        var regions_info=<?php echo $regions_info;?>
    </script>
    <?php
        echo $javascript->link('/skins/default/js/order.amazeui');
    ?>
    <script src="<?php echo $webroot; ?>plugins/ajaxfileupload.js" type="text/javascript"></script>
    <div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-detail-menu" >
        <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
            <li><a href="#basic_info"><?php echo $ld['basic_information']?></a></li>
            <li><a href="#consignee_information"><?php echo $ld['receiving_information']?></a></li>
            <li><a href="#pro_info"><?php echo $ld['product_information']?></a></li>
            <li><a href="#cost"><?php echo $ld['expenses']?></a></li>
            <li><a href="#invoice"><?php echo $ld['invoice_information']?></a></li>
            <?php if(isset($configs['vendor_shipment'])&&$configs['vendor_shipment']=='1'&&$svshow->operator_privilege("view_factory_time")){ ?>
                <li><a href="#supplier"><?php echo $ld['vendor_information']?></a></li>
            <?php } ?>
            <li><a onclick="openother()" href="#other_title"><?php echo $ld['other_information']?></a></li>
            <li><a onclick="opencollapse()" href="#operation_title"><?php echo $ld['operation_records']?></a></li>
        </ul>
    </div>
    <div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-detail-view" id="accordion" >
        <!-- 编辑按钮区域 -->
        <div class="btnouter am-text-right" data-am-sticky="{top:'10%'}">
            <input type="hidden" id="order_id" value="<?php echo $order_info['Order']['id']?>" />
            <input type="hidden" name="lease_type" id="lease_type" value="L" />

            <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" value="<?php echo $ld['d_submit'];?>" onclick="order_data_save();" />

            <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" onclick="order_reflash();" />
        </div>
        <!-- 编辑按钮区域 -->
        <div id="basic_info" class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title">
                    <?php echo $ld['basic_information'] ?>
                </h4>
            </div>
            <div id="basic_information" class="am-panel-collapse am-collapse am-in">
                <div  class="am-panel-bd am-form-detail am-form am-form-horizontal">
                    <div id="order_address_info_div">
                        <table id="order_user_info" class="am-table" style="margin-bottom:0;">
                            <tr>
                                <td width="50%">
                                    <div class="am-form-group">
                                        <div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:10px;"><?php echo $ld['order_code']?>:</div>
                                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-top: 0.6em;">
                                            <span>#<?php echo $order_info['Order']['order_code'];?></span>
                                            <span>[<?php echo $order_info['Order']['created'];?>]</span>
                                        </div>
                                    </div>
                                </td>
                                <td width="50%">
                                    <div class="am-form-group">
                                        <div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:10px;"><?php echo $ld['order_status']?> :</div>
                                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-top: 0.6em;">
                                            <?php echo $Resource_info["order_status"][$order_info['Order']['status']];?>,
                                            <?php echo $Resource_info["payment_status"][$order_info['Order']['payment_status']];?>,
                                            <?php echo $Resource_info["shipping_status"][$order_info['Order']['shipping_status']];?>
                                            <?php if($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2){
                                                echo '<br/>'.$ld['order_logistics_company'].':';
                                                $LogisticsCompany_name=" - ";
                                                $LogisticsCompany_code="";
                                                ?>
                                                <?php
                                                foreach($logistics_company_list as $k=>$v){if($v['LogisticsCompany']['id']==$order_info['Order']['logistics_company_id']){?>
                                                    <?php $LogisticsCompany_code=$v['LogisticsCompany']['code'];$LogisticsCompany_name=$v['LogisticsCompany']['name'];}}?>
                                                <?php
                                                echo $LogisticsCompany_name.',';
                                                echo $ld['invoice_number'].':'.($LogisticsCompany_code!='not_need'&&!empty($order_info['Order']['invoice_no'])?"[<span style='color:red;'>".$order_info['Order']['invoice_no']."</span>]<br />":' - &nbsp;'); ?>
                                                <?php echo $order_info['Order']['shipping_time'];?>
                                            <?php }?>
                                            <?php if(!empty($order_info['Order']['invoice_no'])&&$order_info['Order']['logistics_company_id']!=0){ ?>
                                                <div id="express_info" style="<?php if($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2){?><?php }else{?>display:none<?php }?>"><button class="am-btn am-btn-success am-radius am-btn-sm" data-am-modal="{target: '#express_info_popup',width:600}"><?php echo $ld['logistics_tracking']?></button></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr id="OrderStatusChange" <?php if(!isset($order_info['OrderProduct'])||empty($order_info['OrderProduct'])){?>style="display:none"<?php }?>>
                                <td colspan="2">
                                    <div class="am-form-group">
                                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label">
                                            <?php echo $ld['order_status_change']?>
                                            <input id="order_status_change" type="hidden" value="" />
                                        </div>
                                        <div id="status_change_td" class="am-u-lg-9 am-u-md-9 am-u-sm-8">
                                            
                                            <?php if(isset($order_action['confirm']) && $order_action['confirm']){?>
                                                <input id="order_confirm" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_change" onclick="order_status_select(this.id)" value="<?php echo $ld['confirm']?>" />
                                            <?php }?>

                                            <?php if(isset($order_action['pay']) && $order_action['pay']){?>
                                                <?php if($order_info['Order']['to_type_id']==$admin['type_id']){?>
                                                    <input id="order_payment" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_change" onclick="order_status_select(this.id)" value="<?php echo $ld['payment_btn']?>" />
                                                <?php }?>
                                            <?php }?>
                                            <?php if( isset($order_action['pay']) && $order_action['pay'] && !isset($order_action['unship'])){?>
                                                <?php if($order_info['Order']['to_type_id']==$admin['type_id']){?>
                                                    <input id="order_payment_delivery" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_change" onclick="order_status_select(this.id)" value="<?php echo $ld['payment_and_shipping']?>" />
                                                <?php }?>
                                            <?php }?>
                                            <?php if(isset($order_action['unpay']) && $order_action['unpay']){?>
                                                <?php if($order_info['Order']['to_type_id']==$admin['type_id']){?>
                                                    <input id="order_make_no_payments" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_change" onclick="order_status_select(this.id)" value="<?php echo $ld['make_unpay']?>" />
                                                <?php }?>
                                            <?php }?>
                                            <?php if(isset($order_action['prepare']) && $order_action['prepare']){?>
                                                <input id="order_picking" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_change" onclick="order_status_select(this.id)" value="<?php echo $ld['picking_btn']?>" />
                                            <?php }?>
                                            <?php if(((isset($order_action['pay']) && $order_action['pay'])||(isset($order_action['ship']) && $order_action['ship']))&&!isset($order_action['unship'])){?>
                                                <?php if($order_info['Order']['to_type_id']==$admin['type_id']){?>
                                                    <input id="order_delivery" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_change" onclick="order_status_select(this.id)" value="<?php echo $ld['delivery']?>" />
                                                <?php }?>
                                            <?php }?>
                                            <?php if(isset($order_action['unship']) && $order_action['unship']){?>
                                                <input id="order_unfilled" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_change" onclick="order_status_select(this.id)" value="<?php echo $ld['unreceived']?>" />
                                            <?php }?>
                                            <?php if(isset($order_action['receive']) && $order_action['receive']){?>
                                                <input id="order_has_been_receiving" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_change" onclick="order_status_select(this.id)" value="<?php echo $ld['received']?>" />
                                            <?php }?>
                                            <?php if(isset($order_action['cancel']) && $order_action['cancel']){?>
                                                <input id="order_cancel" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_change" onclick="order_status_select(this.id)" value="<?php echo $ld['cancel']?>" />
                                            <?php }?>
                                            <?php if(isset($order_action['invalid']) && $order_action['invalid']){?>
                                                <input id="order_invalid" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_change" onclick="order_status_select(this.id)" value="<?php echo $ld['invalid']?>" />
                                            <?php }?>
                                           
                                            <?php if($order_info['Order']['shipping_status'] == 2 || $order_info['Order']['shipping_status'] == 1){?>
                                                <input id="after_service" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_change" onclick="order_status_select(this.id)" value="<?php echo $ld['service']?>" />
                                            <?php }?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr id="order_picking_type_tr" style="display:none">
                                <td colspan="2">
                                    <div class="am-form-group">
                                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label"><?php echo $ld['picking_style']?></div>
                                        <div class="am-u-lg-3 am-u-md-5 am-u-sm-8">
                                            <select name="picking_type" id="picking_type">
                                                <?php if(isset($Resource_info['picking_type'])&&sizeof($Resource_info['picking_type'])>0){foreach($Resource_info['picking_type'] as $k=>$v){ ?>
                                                    <option <?php echo isset($order_info['Order']['picking_type'])&&$order_info['Order']['picking_type']==$k?"selected":''; ?> value='<?php echo $k; ?>'><?php echo $v; ?></option>
                                                <?php }} ?>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr id="order_logistics_company_id_tr" style="display:none">
                                <td colspan="2">
                                    <div class="am-form-group">
                                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label"><?php echo $ld['order_logistics_company']?></div>
                                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
                                            <input type="hidden" id="logistics_company_id"  value="<?php echo !empty($order_info['Order']['logistics_company_id'])?$order_info['Order']['logistics_company_id']:'';?>"/>
                                            <?php foreach($logistics_company_list as $k=>$v){if($v['LogisticsCompany']['id']==$order_info['Order']['logistics_company_id']){?>
                                                <input type="hidden" id="Company_express_code" value="<?php echo $v['LogisticsCompany']['express_code']; ?>" />
                                            <?php	}}?>
                                            <select id="order_logistics_company_id" onchange="select_logistics_company(this.value)">
                                                <option value=''><?php echo $ld['order_logistics']?></option>
                                            </select>
                                            <?php if($order_info['Order']['shipping_status'] == 2 || $order_info['Order']['shipping_status'] == 1){?>
                                                <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" id='logistic_save_button' onclick="order_logistics_data_save()" style="width:auto;" value="修改物流信息" />
                                            <?php }?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr id="order_invoice_no_tr" style="display:none">
                                <td colspan="2">
                                    <div class="am-form-group">
                                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label"><?php echo $ld['invoice_number']?></div>
                                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
                                            <input type="text" id="order_invoice_no"  value="<?php echo $order_info['Order']['invoice_no'];?>"/>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="operation_notes_action operation_notes_action_hid">
                                <td colspan="2">
                                    <div class="am-form-group">
                                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label"><?php echo $ld['operation_remarks']?></div>
                                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-8"><textarea id="operation_notes" style="width:600px;"></textarea>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="operation_notes_action operation_notes_action_hid">
                                <td colspan="2">
                                    <div class="am-form-group">
                                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label">&nbsp;</div>
                                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
                                            <input type="hidden" id="order_id" value="<?php echo $order_info['Order']['id']?>" />
                                            <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" id="order_status_change_btn" value="<?php echo $ld['d_submit'];?>" onclick="order_status_change();" />
                                            <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" value="<?php echo $ld['d_reset']?>" onclick="order_reflash();" />
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="am-form-group">
                                        <div id="order_user_label" class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label"><?php echo $ld['order_user']?></div>
                                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
                                            <?php echo $form->create('',array('action'=>'/',"name"=>"OrdForm",'onsubmit'=>"search_user();return false;"));?>
                                            <span id="user_info">
                                              <?php if(isset($order_info["User"]['first_name'])){
                                                  echo $order_info["User"]['first_name'];
                                              }
                                              if(isset($discount)){
                                                  echo ' ('.$discount.'折)';
                                              }
                                              ?>
                                            </span>
                                            <a onclick="edit_order_user()" id="edit_order_user" href="javascript:void(0);">
                                                <?php echo $ld['edit'];?>
                                            </a>
                                            <input type="text" style="width:20%;display: inline;" name="data[Order][user_name]" id="opener_select_user_name" value="">
                                            <input type="hidden" name="data[Order][user_id]" id="opener_select_user_id" value="<?php if(isset($order_info["User"]['name'])){	echo $order_info['User']['id'];};?>">
                                            <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" id="search_user_button" onclick="search_user();" value="<?php echo $ld['find_user']; ?>" />
                                            <select id="search_user_infos" style="width:30%;display: none;" class="selecthide" onchange="select_user(this.value)"></select>
                                            <?php echo $form->end();?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr id="create_user_info" class="create_user_info">
                                <td width="50%">
                                    <div class="am-form-group">
                                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-form-label"><?php echo $ld['real_name']; ?></div>
                                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><input type="text" id="create_user_name" value="" /></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="am-form-group">
                                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-form-label"><?php echo $ld['mobile']; ?></div>
                                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><input type="text" id="create_user_mobile" value="" /></div>
                                    </div>
                                </td>
                            </tr>
                            <?php if(isset($user_info['User'])){ ?>
                                <tr>
                                    <td  colspan="4" style="padding:0.7rem 0;">
                                        <form id="order_user_avatar_from">
                                            <table class="user_avatar" width="100%">
                                                <tr>
                                                    <td>
                                                        <div class="am-form-group">
                                                            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label"><?php echo $ld['avatar']; ?></div>
                                                            <div class="am-u-lg-10 am-u-md-10 am-u-sm-10">
                                                                <div class="am-fl am-u-lg-4 am-u-md-4 am-u-sm-4">
                                                                    <?php echo $html->image(isset($user_info['User']['img01']) && $user_info['User']['img01']!=''?$user_info['User']['img01']:'/theme/default/img/no_head.png',array('id'=>'avatar_img01_priview')); ?>
                                                                    <input style="margin:8px 0;" class="order_user" type="file" id="avatar_img01" name="avatar_img01" onchange="ajaxFileUpload(<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0; ?>,'avatar_img01')" />
                                                                    <input type="hidden" id="avatar_img01_hid" name="data[User][img01]" value="<?php echo isset($user_info['User']['img01'])?$user_info['User']['img01']:''; ?>" />
                                                                </div>
                                                                <div class="am-fl am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $html->image(isset($user_info['User']['img02']) && $user_info['User']['img02']!=''?$user_info['User']['img02']:'/theme/default/img/no_head.png',array('id'=>'avatar_img02_priview')); ?>
                                                                    <input style="margin:8px 0;" class="order_user"  type="file" id="avatar_img02" name="avatar_img02" onchange="ajaxFileUpload(<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0; ?>,'avatar_img02')" />
                                                                    <input type="hidden" id="avatar_img02_hid" name="data[User][img02]" value="<?php echo isset($user_info['User']['img02'])?$user_info['User']['img02']:''; ?>" />
                                                                </div>
                                                                <div class="am-fl am-u-lg-4 am-u-md-4 am-u-sm-4">
                                                                    <?php echo $html->image(isset($user_info['User']['img03']) && $user_info['User']['img03']!=''?$user_info['User']['img03']:'/theme/default/img/no_head.png',array('id'=>'avatar_img03_priview')); ?>
                                                                    <input style="margin:8px 0;" class="order_user"  type="file" id="avatar_img03" name="avatar_img03" onchange="ajaxFileUpload(<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0; ?>,'avatar_img03')" />
                                                                    <input type="hidden" id="avatar_img03_hid" name="data[User][img03]" value="<?php echo isset($user_info['User']['img03'])?$user_info['User']['img03']:''; ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="am-form-group">
                                                            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label">&nbsp;</div>
                                                            <div class="am-u-lg-10 am-u-md-10 am-u-sm-10">
                                                                <div class="am-fl am-u-lg-4 am-u-md-4 am-u-sm-4">
                                                                    <?php echo $html->image(isset($user_info['User']['img04']) && $user_info['User']['img04']!=''?$user_info['User']['img04']:'/theme/default/img/no_head.png',array('id'=>'avatar_img04_priview')); ?>
                                                                    <input style="margin:8px 0;" class="order_user" type="file" id="avatar_img04" name="avatar_img04" onchange="ajaxFileUpload(<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0; ?>,'avatar_img04')" />
                                                                    <input type="hidden" id="avatar_img04_hid" name="data[User][img04]" value="<?php echo isset($user_info['User']['img04'])?$user_info['User']['img04']:''; ?>" />
                                                                </div>
                                                                <div class="am-fl am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $html->image(isset($user_info['User']['img05']) && $user_info['User']['img05']!=''?$user_info['User']['img05']:'/theme/default/img/no_head.png',array('id'=>'avatar_img05_priview')); ?>
                                                                    <input style="margin:8px 0;" class="order_user"  type="file" id="avatar_img05" name="avatar_img05" onchange="ajaxFileUpload(<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0; ?>,'avatar_img05')" />
                                                                    <input type="hidden" id="avatar_img05_hid" name="data[User][img05]" value="<?php echo isset($user_info['User']['img05'])?$user_info['User']['img05']:''; ?>" />
                                                                </div>
                                                                <div class="am-fl am-u-lg-4 am-u-md-4 am-u-sm-4">
                                                                    <?php echo $html->image(isset($user_info['User']['img06']) && $user_info['User']['img06']!=''?$user_info['User']['img06']:'/theme/default/img/no_head.png',array('id'=>'avatar_img06_priview')); ?>
                                                                    <input style="margin:8px 0;" class="order_user"  type="file" id="avatar_img06" name="avatar_img06" onchange="ajaxFileUpload(<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0; ?>,'avatar_img06')" />
                                                                    <input type="hidden" id="avatar_img06_hid" name="data[User][img06]" value="<?php echo isset($user_info['User']['img06'])?$user_info['User']['img06']:''; ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </form>
                                        <!-- 用户量体信息 -->
                                        <?php if(!empty($default_user_config_list)){ $user_config_count=0; ?>
                                            <form id="order_user_config_from">
                                                <table class="am-table" style="margin-bottom:0;">
                                                    <?php foreach($default_user_config_list as $ck=>$cv){$user_config_count++; ?>
                                                        <?php if($user_config_count%2!=0){ ?><tr><?php } ?>
                                                        <td width="50%">
                                                            <div class="am-form-group">
                                                                <div class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-form-label"><?php echo $cv['name']; ?></div>
                                                                <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                                                                    <?php   $user_config_values_arr=split("\r\n",$cv['user_config_values']);
                                                                    $user_config_values=array();
                                                                    $user_config_value=isset($user_config_list[$ck])?$user_config_list[$ck]:$cv['value'];
                                                                    if(!empty($user_config_values_arr[0])){
                                                                        foreach($user_config_values_arr as $selk=>$selv){
                                                                            if(empty($selv)){continue;}
                                                                            $selv_txt_arr=split(':',$selv);
                                                                            if(empty($selv_txt_arr[1])){continue;}
                                                                            $user_config_values[$selv_txt_arr[0]]=$selv_txt_arr[1];
                                                                        }
                                                                    }
                                                                    if($cv['value_type']=='textarea'){ ?>
                                                                        <textarea class="order_user" type="text" name="data[UserConfig][body_type][<?php echo $ck; ?>]">
                                                                          <?php echo $user_config_value; ?>
                                                                        </textarea>
                                                                        <label class="order_user_span am-form-label"><?php echo $user_config_value; ?></label>
                                                                    <?php }else{ ?>
                                                                        <input class="order_user" type="text" name="data[UserConfig][body_type][<?php echo $ck; ?>]" value="<?php echo $user_config_value; ?>" onKeyUp="clearNoNum(event,this)" onBlur="checkNum(this)">
                                                                        <label class="order_user_span am-form-label"><?php echo $user_config_value; ?></label>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <?php if($user_config_count%2==0){ ?></tr><?php } ?>
                                                    <?php } ?>
                                                </table>
                                            </form>
                                        <?php } ?>
                                        <!-- 用户量体信息 -->
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr class="order_user">
                                <td colspan="2">
                                    <div class="am-form-group">
                                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label"></div>
                                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
                                            <input id="order_address_data_save" type="button" class="am-btn am-btn-success am-radius am-btn-sm order_user"  onclick="order_user_save()" value="<?php echo $ld['save'];?>" />
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="consignee_information" class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title">
                    <?php echo $ld['receiving_information'] ?>
                </h4>
            </div>
            <div id="consignee_info" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                    <table id="order_address_info_table" class="am-table am-form" style="margin-bottom:0;">
                        <tr>
                            <td>
                                <div class="am-form-group">
                                    <div class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-form-label" style="margin-top:3px;"><?php echo $ld['shipping']?></div>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="margin-left:10px;margin-top:10px;">
                                        <select id="order_shipping_id" onchange="sendinfo('1');">
                                            <?php if(isset($shipping_effective_list) && sizeof($shipping_effective_list)>0){foreach($shipping_effective_list as $k=>$v){?>
                                                <option value="<?php echo $v['Shipping']['id']?>" <?php if($order_info['Order']['shipping_id']==$v['Shipping']['id']){echo "selected";}?> ><?php echo $v['ShippingI18n']['name']?></option>
                                            <?php }}?>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr class="order_user_address_edit">
                            <td>
                                <div class="am-form-group" id="order_address_info" >
                                    <div class="am-u-lg-3 am-u-md-3 am-u-sm-2 am-form-label"><?php echo $ld['select_from_delivery_address']?></div>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                                        <select id="sel_address" onchange="select_user_address_change(this.value);" class="address" >
                                            <option value=""><?php echo $ld['please_select'];?>...</option>
                                            <?php if(!empty($user_addresses_array)){foreach( $user_addresses_array as $k=>$v){?>
                                                <option value='<?php echo $k;?>' >
                                                    <?php echo $v["UserAddress"]["consignee"];?>,<?php echo isset($regions_info3[$v["UserAddress"]["country"]])?$regions_info3[$v["UserAddress"]["country"]]:'';?>,<?php echo isset($regions_info3[$v["UserAddress"]["province"]])?$regions_info3[$v["UserAddress"]["province"]]:'';?>,<?php echo isset($regions_info3[$v["UserAddress"]["city"]])?$regions_info3[$v["UserAddress"]["city"]]:'';?>,<?php echo $v["UserAddress"]["address"];?>
                                                </option>
                                            <?php }}?>
                                        </select><label class="address_span  am-form-label"></label>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="am-form-group" >
                                    <div class="am-u-lg-3 am-u-md-3 am-u-sm-2 am-form-label"><?php echo $ld['phone']?></div>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                                        <input type="text" id="order_telephone" value="<?php echo $order_info['Order']['telephone'];?>" class="address" />
                                        <label class="address_span am-form-label"  ><?php echo $order_info['Order']['telephone'];?></label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="am-form-group">
                                    <div class="am-u-lg-3 am-u-md-3 am-u-sm-2 am-form-label"><?php echo $ld['consignee']?></div>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                                        <input type="text" style="width:70%;padding-right:0;" id="order_consignee" value="<?php echo $order_info['Order']['consignee'];?>"  class="address"/>
                                        <label class="address_span am-form-label" id="order_consignee_span" style="padding-top:5px;"><?php if(!empty($order_info['Order']['consignee'])){echo $order_info['Order']['consignee'];}else{echo "&nbsp;";}?></label>
                                        <a onclick="edit_order_address()" href="javascript:void(0);"><?php echo $ld['edit'];?></a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="am-form-group">
                                    <div class="am-u-lg-3 am-u-md-3 am-u-sm-2 am-form-label"><?php echo $ld['mobile']?></div>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                                        <input type="text" id="order_mobile" value="<?php echo $order_info['Order']['mobile'];?>" class="address" />
                                        <label class="address_span am-form-label" id="order_mobile_span" style="padding-top:5px;"><?php echo $order_info['Order']['mobile'];?></label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="order_user_address_edit">
                            <td>
                                <div class="am-form-group" >
                                    <div class="am-u-lg-3 am-u-md-3 am-u-sm-2 am-form-label"><?php echo $ld['region']?>
                                        <input type="hidden" id="order_country2" value="<?php echo isset($order_info['Order']['country'])?$order_info['Order']['country']:"";?>">
                                        <input type="hidden" id="order_province2" value="<?php echo isset($order_info['Order']['province'])?$order_info['Order']['province']:"";?>">
                                        <input type="hidden" id="order_city2" value="<?php echo isset($order_info['Order']['city'])?$order_info['Order']['city']:"";?>">
                                    </div>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                                        <div id="address_select_span" style="margin-top:0px;"  <?php if(!((!isset($order_info['Order']['country'])||$order_info['Order']['country']=="")&&(!isset($order_info['Order']['province'])||$order_info['Order']['province']=="")&&(!isset($order_info['Order']['city'])||$order_info['Order']['city']==""))){?>class="order_status"<?php }?>>
                                            <select style="width:30%;" gtbfieldid="1" name="country_select" id="country_select" onchange="getRegions(this.value,'country')">
                                            </select>
                                            <select style="width:30%;" class="order_status" gtbfieldid="1" name="province_select" id="province_select" onchange="getRegions(this.value,'province')">
                                            </select>
                                            <select style="width:30%;" class="order_status" gtbfieldid="1"  name="city_select" id="city_select" onchange="getRegions(this.value,'city')">
                                            </select>
                                        </div>
                                        <label class="address_span am-form-label" style="padding-top:5px;"><?php echo isset($order_info['Order']['country'])?$order_info['Order']['country']:"";?> -
                                            <?php echo isset($order_info['Order']['province'])?$order_info['Order']['province']:"";?> -
                                            <?php echo isset($order_info['Order']['city'])?$order_info['Order']['city']:"";?>
                                        </label>
                                        <!--				  <div id="address_input_span" <?php if((!isset($order_info['Order']['country'])||$order_info['Order']['country']=="")&&(!isset($order_info['Order']['province'])||$order_info['Order']['province']=="")&&(!isset($order_info['Order']['city'])||$order_info['Order']['city']=="")){?>class="order_status"<?php }?>>
				<input type="text" style="width:65px" id="order_country" value="<?php echo isset($order_info['Order']['country'])?$order_info['Order']['country']:"";?>" class="address" />
				<span class="address_span"><?php echo isset($order_info['Order']['country'])?$order_info['Order']['country']:"";?></span> - <input type="text" style="width:65px" id="order_province" value="<?php echo isset($order_info['Order']['province'])?$order_info['Order']['province']:"";?>" class="address" />
				<span class="address_span"><?php echo isset($order_info['Order']['province'])?$order_info['Order']['province']:"";?></span>
				<span class="address"><?php echo $ld['province_state'];?></span> - <input type="text" style="width:65px" id="order_city" value="<?php echo isset($order_info['Order']['city'])?$order_info['Order']['city']:"";?>" class="address"  />
				<span class="address_span"><?php echo isset($order_info['Order']['city'])?$order_info['Order']['city']:"";?></span>
				<span class="address"><?php echo $ld['city']?></span>
			  </div>-->
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="am-form-group">
                                    <div class="am-u-lg-3 am-u-md-3 am-u-sm-2 am-form-label"><?php echo $ld['email']?></div>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                                        <input type="text" id="order_email" value="<?php echo $order_info['Order']['email'];?>" class="address" />
                                        <label class="address_span am-form-label" style="padding-top:5px;"><?php echo $order_info['Order']['email'];?></label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="order_user_address_edit">
                            <td >
                                <div class="am-form-group">
                                    <div class="am-u-lg-3 am-u-md-3 am-u-sm-2 am-form-label"><?php echo $ld['address']?></div>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-9">
                                        <input type="text" id="order_address" value="<?php echo $order_info['Order']['address'];?>" class="address" />
                                        <label class="address_span am-form-label" ><?php echo $order_info['Order']['address'];?></label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="order_user_address_edit">
                            <td>
                                <div class="am-form-group">
                                    <div class="am-u-lg-3 am-u-md-3 am-u-sm-2 am-form-label"><?php echo $ld['zip_code']?></div>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                                        <input type="text" id="order_zipcode" value="<?php echo $order_info['Order']['zipcode'];?>" class="address" />
                                        <label class="address_span am-form-label" style="padding-top:5px;"><?php echo $order_info['Order']['zipcode'];?></label>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="am-form-group">
                                    <div class="am-u-lg-3 am-u-md-3 am-u-sm-2 am-form-label"><?php echo $ld['delivery_remark']?></div>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                                        <textarea id="order_note" class="address"><?php echo $order_info['Order']['note'];?></textarea>
                                        <label class="address_span am-form-label" style="word-break:break-all;"><?php echo $order_info['Order']['note'];?></label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="order_user_address_edit">
                            <td>
                                <div class="am-form-group">
                                    <div class="am-u-lg-3 am-u-md-3 am-u-sm-2 am-form-label"><?php echo $ld['address_to']?></div>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                                        <input type="text" id="order_sign_building" value="<?php echo $order_info['Order']['sign_building'];?>" class="address" />
                                        <label class="address_span am-form-label" style="padding-top:5px;"><?php echo $order_info['Order']['sign_building'];?></label>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="am-form-group">
                                    <div class="am-u-lg-3 am-u-md-3 am-u-sm-2 am-form-label"><?php echo $ld['customer_feedback']?></div>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                                        <textarea id="order_postscript" class="address"><?php echo $order_info['Order']['postscript'];?></textarea>
                                        <label class="address_span am-form-label" style="word-break:break-all;"><?php echo $order_info['Order']['postscript'];?></label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="order_user_address_edit">
                            <td>
                                <div class="am-form-group">
                                    <div class="am-u-lg-3 am-u-md-3 am-u-sm-2 am-form-label"><?php echo $ld['best_delivery_time']?></div>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                                        <input type="text" id="order_best_time" value="<?php echo $order_info['Order']['best_time'];?>" class="address" style="width:55%;" />
                                        <label class="address_span am-form-label"><?php echo $order_info['Order']['best_time'];?></label>
                                        <select id="select_best_time" onchange="document.getElementById('order_best_time').value=this.value" class="address" style="width:40%;">
                                            <option value=""><?php echo $ld['please_select']?>...</option>
                                            <?php foreach( $information_resources_info["best_time"] as $k=>$v){?>
                                                <option value="<?php echo $v?>"><?php echo $v?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr class="order_user_address_edit">
                            <td>
                                <div class="am-form-group">
                                    <div class="am-u-lg-3 am-u-md-3 am-u-sm-2 am-form-label"><?php echo $ld['stock_handling']?></div>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                                        <input type="text" id="order_how_oos" value="<?php echo $order_info['Order']['how_oos'];?>" class="address" style="width:55%;"/>
                                        <label class="address_span am-form-label"><?php echo $order_info['Order']['how_oos'];?></label>
                                        <select id="select_how_oos" onchange="document.getElementById('order_how_oos').value=this.value" class="address" style="width:40%;">
                                            <option value=""><?php echo $ld['please_select']?>...</option>
                                            <?php foreach( $information_resources_info["how_oos"] as $k=>$v){?>
                                                <option value="<?php echo $v;?>"><?php echo $v;?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr class="order_user_address_edit address_save">
                            <td>
                                <div class="am-form-group">
                                    <div class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-form-label">&nbsp;</div>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                                        <input id="order_address_data_save" type="button" class="am-btn am-btn-success am-radius am-btn-sm address"  onclick="order_address_data_save()" value="<?php echo $ld['save'];?>" />
                                    </div>
                                </div>
                            </td>
                            <td>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div id="pro_info" class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" ><?php echo $ld['product_information']; ?>
                </h4>
            </div>
            <div class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                    <div id="order_product_div" style="<?php if(empty($order_info['Order']['type']) && sizeof($order_type)>1){echo 'display:none';}?>">
                        <table class="am-table">
                            <thead>
                            <?php if((!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2)))){?>
                            <tr >
                                <td colspan="7">
                                    <?php echo $form->create('',array('action'=>'/',"name"=>"OrdForm",'onsubmit'=>"search_order_product();return false;"));?>
                                    <div style="margin-top:10px;">
                                        <div class="am-u-lg-5 am-u-md-5 am-u-sm-5" >
                                            <?php if(isset($product_style_tree)&&sizeof($product_style_tree)>0){ ?>
                                                <label  class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="margin-top:8px;"><?php echo $ld['product_style'];?>:</label>
                                                <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                                                    <select id="product_style" name="product_style">
                                                        <option value=""><?php echo $ld['please_select']?></option>
                                                        <?php foreach($product_style_tree as $v){?>
                                                            <option value="<?php echo $v['ProductStyle']['id']?>"><?php echo $v['ProductStyleI18n']['style_name'];?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="am-u-lg-6 am-u-md-7 am-u-sm-7">
                                            <?php if(isset($product_type_tree)&&sizeof($product_type_tree)>0){ ?>
                                                <label  class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-fl" style="margin-top:8px;margin-bottom:20px;"><?php echo $ld['all_product_type']?>:</label>
                                                <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" >
                                                    <select id="product_type" name="product_type">
                                                        <option value=""><?php echo $ld['please_select']?></option>
                                                        <?php foreach($product_type_tree as $v){?>
                                                            <option value="<?php echo $v['ProductType']['id']?>"><?php echo $v['ProductTypeI18n']['name'];?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            <?php }?>
                                        </div>
                                    </div>
                                    <div style="margin-top:10px;">
                                        <div class="am-u-lg-6 am-u-md-7 am-u-sm-7" >
                                            <?php if(isset($brands)&&!empty($brands)){?>
                                                <label class="am-u-lg-4 am-u-md-4 am-u-sm-4  am-fl" style="margin-top:8px;"><?php echo $ld['brand']?>:</label>
                                                <div  class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="margin-bottom:10px;">
                                                    <select id="product_brand" name="product_brand">
                                                        <option value=""><?php echo $ld['please_select']?></option>
                                                        <?php foreach($brands as $v){?>
                                                            <option value="<?php echo $v['Brand']['id']?>"><?php echo $v['Brand']['code'].'-'.$v['BrandI18n']['name'];?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            <?php }?>
                                        </div>
                                        <div class="am-u-lg-6 am-u-md-7 am-u-sm-7" >
                                            <label  class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-fl" style="margin-top:8px;"><?php echo $ld['product_sku_or_name']?>:</label>
                                            <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                                                <input type="text" id="order_product" onkeydown="if(event.keyCode==13){return false;}"/>
                                            </div>
                                            <input style="margin-left:5px; margin-top:10px;" type="button" id="add_product_button" onclick="search_order_product();"  class="am-btn am-btn-success am-radius am-btn-sm am-fl" value="<?php echo $ld['search'];?>" />
                                            <select id="result" onchange="add_order_product(this.value)" class="selecthide am-fl" style="width:20%;margin:0px 5px 0 5px;display:none;"> 									<option value=""><?php echo $ld['please_select']?></option>
                                            </select>
                                            <span id="load_div"  class="order_status"></span>
                                        </div>
                                    </div>
                                    <?php echo $form->end(); ?>
                                </td>
                                <?php }?>
                            </tr>
                            <tr>
                                <th style="text-align:left;width:80px;"><?php echo $ld['product_image']?></th>
                                <th ><?php echo $ld['product_name']?></th>
                                <!--				  <th style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><?php echo $ld["shop_price"];?><br>(<?php echo $ld["order_list_price"];?>)</th>-->
                                <th class="am-table-order-qty"><?php echo $ld['order_quantity']?></th>
                                <th class="am-table-price" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><?php echo $ld['price']?></th>
                                <th style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><?php echo $ld['subtotal']?></th>
                                <?php //if(!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2))){?>
                                <?php //if($order_info['Order']['type']!='taobao'){}?>
                                <th><?php echo $ld['operate'];?></th>
                                <?php //}?>
                            </tr>
                            </thead>
                            <tbody id="order_products_detail_innerhtml">
                            <?php $the_subtotal=0;  $sum_quantity=0;$purchase_price_total=0;//产品总价值
                            if(isset($order_info['OrderProduct']) && sizeof($order_info['OrderProduct'])>0){
                                foreach($order_info['OrderProduct'] as $k=>$v){$total_attr_price=0;
                                	$purchase_price_total+=$v['purchase_price']*($v['product_quntity']-$v['refund_quantity']);
                                    $the_subtotal +=($v['product_price']*($v['product_quntity']-$v['refund_quantity'])+$v['adjust_fee']);
                                    $sum_quantity +=$v['product_quntity'];
                             ?>
                                    <tr>
                                        <td class="smail_img" style="text-align: left;padding: 5px 0 3px;line-height: normal;">
                                            <figure data-am-widget="figure" class="am am-figure am-figure-default "
                                                    data-am-figure="{  pureview: 'true' }">
                                                <?php echo $html->image($v['img_thumb']!=""?$v['img_thumb']:"/theme/default/images/default.png",array('date-rel'=>$v['img_thumb']!=""?$v['img_thumb']:"/theme/default/images/default.png")); ?>
                                            </figure>
                                        </td>
                                        <!-- 名称 -->
                                        <td>
                                            <p style="line-height:20px;">
                                                <?php if(isset($v['sku_product'])&&$v['sku_product']==1){
                                                    echo $svshow->seo_link(array('type'=>'P','id'=>$v['parent_product_id'],'name'=>'','sub_name'=>$v['product_name'],'style'=>'font-weight:bold'));
                                                }else{
                                                    echo $svshow->seo_link(array('type'=>'P','id'=>$v['product_id'],'name'=>'','sub_name'=>$v['product_name'],'style'=>'font-weight:bold'));}?>
                                            </p>
                                            <!--循环套装商品-->
                                            <?php if(isset($order_package_products[$v['product_id']])&&sizeof($order_package_products[$v['product_id']])>0){?>
                                                <div>
                                                    <div style="font-size:12px;clear:both;"><?php echo $ld['package_product']?>:</div>
                                                    <?php foreach($order_package_products[$v['product_id']] as $pk=>$pv){?>
                                                        <div class="pkg">
                                                            <?php echo $pv['product_name']?>
                                                        </div>
                                                        <div style="width:20px;float:left;">*<?php echo $pv['product_quntity']?></div>
                                                    <?php }?>
                                                </div>
                                            <?php }?>
                                            <div style="clear:both;">
                                                <input type="hidden" name="order_product_id[]" value="<?php echo $v['id'];?>">
                                                <input type="hidden" name="order_product_code[]" value="<?php echo $v['product_code'];?>">
                                                <span><?php echo $ld['product_code']?>:&ensp;</span><?php echo $v['product_code'];?>
                                            </div>
                                            <?php if(isset($order_info['OrderProductValue'])&& count($order_info['OrderProductValue'])>0){?>
                                                <div><?php //echo $ld['product_code'];?>属性:&ensp;</div>
                                                <?php foreach($order_info['OrderProductValue'] as $opk=>$opv){ ?>
                                                    <?php if($opv['order_product_id']==$v['id'] && (int)$opv['attr_price']!=0){?>
                                                        <?php $total_attr_price+=$opv['attr_price'];?>
                                                        <div><?php echo $opv['attribute_value'];?>:&ensp;<?php echo $opv['attr_price'];?></div>
                                                    <?php }?>
                                                <?php }?>
                                            <?php }?>
                                            <div class="am-g am-margin-bottom-xs am-margin-top-xs">
                                                <div class="am-u-lg-3 am-u-md-4 am-u-sm-12 am-padding-0" style="line-height:37px;">
                                                <span><?php echo $ld['product_attribute']?>:&ensp;</span>
                                                </div>
                                                <div class="am-u-lg-6 am-u-md-5 am-u-sm-12">
                                                <?php if(!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2))){?>

                                                    <input type="text" name="order_product_attr[]" onblur="order_products_data_save()" value="<?php if(isset($v['sku_product']['ProductAttribute'])){
                                                        foreach($v['sku_product']['ProductAttribute'] as $attr_v){
                                                            echo $attr_v['ProductAttribute']['product_type_attribute_value'].'\n';
                                                        }
                                                    }else{ echo isset($v['product_attrbute'])&&!empty($v['product_attrbute'])?str_replace('<br />', '\n', $v['product_attrbute']):'';}?>" />
                                                <?php }else{ if(isset($v['sku_product']['ProductAttribute'])){
                                                    foreach($v['sku_product']['ProductAttribute'] as $attr_v){
                                                        echo $attr_v['ProductAttribute']['product_type_attribute_value']."\n";
                                                    }
                                                }else{
                                                    echo isset($v['product_attrbute'])&&!empty($v['product_attrbute'])?str_replace("<br />", "\n", $v['product_attrbute']):'';}
                                                }?>
                                                </div>
                                            </div>
                                            <?php if(isset($configs['order_product_expires'])&&$configs['order_product_expires']=='1'&&$v['lease_type']=="L"){ ?>
                                                <div class="orderproduct_lease_unit am-margin-top-xs am-g">
                                                    <div class="am-u-lg-3 am-u-md-4 am-u-sm-12 am-padding-0" style="line-height:37px;">
                                                    <span><?php echo $ld['lease_days'] ?>:&ensp;</span>
                                                    </div>
                                                    <div class="am-u-lg-6 am-u-md-5 am-u-sm-12">
                                                    <input type="text" name="order_product_lease_unit[]" value="<?php echo $v['lease_unit'];?>"/>
                                                    <input type="hidden" id="lease_unit" value="<?php echo $v['base_unit'];?>"/>
                                                    </div>
                                                </div>
                                                 <div class="orderproduct_begin_date am-margin-top-xs am-margin-bottom-xs am-g">
                                                    <div class="am-u-lg-3 am-u-md-4 am-u-sm-12 am-padding-0" style="line-height:37px;">
                                                    <span><?php echo $ld['time_to_start']?>:&ensp;</span>
                                                    </div>
                                                    <div class="am-u-lg-6 am-u-md-5 am-u-sm-12">
                                                    <input type="text" name="order_product_begin_date[]" value="<?php echo $v['begin_date']!='2008-01-01 00:00:00'?date('Y-m-d',strtotime($v['begin_date'])):''; ?>"/>
                                                    </div>
                                                </div>
                                                <div class="orderproduct_expire_date am-margin-top-xs am-margin-bottom-xs am-g">
                                                    <div class="am-u-lg-3 am-u-md-4 am-u-sm-12 am-padding-0" style="line-height:37px;">
                                                    <span><?php echo $ld['time_to_maturity']?>:&ensp;</span>
                                                    </div>
                                                    <div class="am-u-lg-6 am-u-md-5 am-u-sm-12">
                                                    <input type="text" name="order_product_expire_date[]" value="<?php echo $v['expire_date']!='2008-01-01 00:00:00'?date('Y-m-d',strtotime($v['expire_date'])):''; ?>"/>
                                                    </div>
                                                </div>

                                                <?php if($order_info['Order']['payment_status']==2&&$order_info['Order']['status']!=2&&$order_info['Order']['shipping_status']!=0&&$v['status']==2){ ?>

                                                 <?php if($svshow->operator_privilege("lease_return")){  ?>
                                                    <a class="am-seevia-btn-edit am-btn am-btn-default am-btn-xs am-text-secondary" href="<?php echo $html->url('/orders/lease_order_status?order_id='.$order_info['Order']['id']).'&pro_id='.$v['id'].'&status=cancel'; ?>">
                                                        <span class="am-icon-eye"></span><?php echo $ld['lease_return'] ?>
                                                    </a>
                                                <?php } ?>

                                                <?php if($svshow->operator_privilege("lease_return")&&$svshow->operator_privilege("lease_orders_add")){  ?>
                                                    <a class="am-seevia-btn-edit am-btn am-btn-default am-btn-xs am-text-secondary" href="<?php echo $html->url('/orders/lease_order_status?order_id='.$order_info['Order']['id']).'&pro_id='.$v['id'].'&status=change'; ?>">
                                                        <span class="am-icon-eye"></span><?php echo $ld['exchange_goods'] ?>
                                                    </a>
                                                <?php } ?>

                                                <?php if($svshow->operator_privilege("lease_renew")){  ?>
                                                    <a class="am-seevia-btn-edit am-btn am-btn-default am-btn-xs am-text-secondary" onclick="show_time(<?php echo $order_info['Order']['id']?>,<?php echo $v['id']?>)">
                                                        <span class="am-icon-eye"></span><?php echo $ld['renew'] ?>
                                                    </a>
                                                <?php } ?>
                                                <?php }} ?>
                                        </td>
                                        <!-- 数量列 -->
                                        <td class="am-table-order-qty" style="padding-top:30px;">
                                            <?php if(!(($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2))){?>
                                                <i class="am-icon-minus"></i><input type="text" size="2" name="order_product_quntity[]" id="order_product_quntity_<?php echo $v['id'];?>" value="<?php echo $v['product_quntity'];?>" onblur="changeNum(this.value,'<?php echo $v['id']?>','<?php echo $v['product_price']*$v['product_quntity'];?>','<?php echo $order_info['Order']['subtotal']?>')" /><i class="am-icon-plus"></i>
                                            <?php }else{
                                                echo '<span id="pq_'.$v["id"].'">'.$v['product_quntity'].'</span>&nbsp;';						          if(isset($refund_warehouse)&&!empty($refund_warehouse)&&$v['product_quntity']>$v['refund_quantity']){
                                                    echo $html->link($ld['return'],"javascript:;",array("onclick"=>"document.getElementById('refund_span_".$v['id']."').style.display=''",'escape' => false,'class'=>"cancelbtn"));?>
                                                    <span id='refund_span_<?php echo $v["id"]?>' style='display:none'>
                                                      <input type='text' value=0 id="refund_input_<?php echo $v['id']?>" style="width:30px">
                                                      <select id='refund_warehouse_<?php echo $v["id"]?>'>
                                                          <option value=''><?php echo $ld['please_select']?></option>
                                                          <?php foreach ($refund_warehouse as $rk => $rv) {?>
                                                              <option value="<?php echo $rv['Warehouse']['code']?>"><?php echo $rv['Warehouse']['warehouse_name']?></option>
                                                          <?php }?>
                                                      </select>
                                                      <input type='button' value="<?php echo $ld['save'];?>" onclick="changeRefund(<?php echo $v['id']?>)">
                                                    </span>
                                                <?php } if($v['refund_quantity']>0) { echo '退货数量：'.$v['refund_quantity'];}}?>
                                            <input type='hidden' id='order_product_refund_<?php echo $v["id"]?>' value="<?php echo $v['refund_quantity']?>">
                                            <p>
                                              <span id="haveQuantity<?php echo $v['id'];?>" <?php if(isset($all_product_quantity_infos[$v['product_code']])&&$v['product_quntity']>$all_product_quantity_infos[$v['product_code']]){?>style="color:red"<?php }?>>
                                              <input type="hidden" id="order_product_have_quntity_<?php echo $v['id'];?>" value="<?php echo isset($all_product_quantity_infos[$v['product_code']])?$all_product_quantity_infos[$v['product_code']]:0;?>">
                                                <span style="font-size:13px;color:green;">
                                                  <?php //echo isset($all_product_quantity_infos[$v['product_code']])?$all_product_quantity_infos[$v['product_code']]:0?>
                                                </span>
                                              </span>&nbsp;&nbsp;&nbsp;
                                              <span style="font-size:13px">
                                                <?php //echo isset($stockProductQuantityInfo[$v['product_code']])?$stockProductQuantityInfo[$v['product_code']]:0;?>
                                              </span>
                                            </p>
                                            <?php if(isset($stockProductInfo)&&isset($stockProductInfo[$v['product_id']])){?>
                                                <?php foreach($stockProductInfo[$v['product_id']] as $kk=>$vv){ if($vv['Stock']['quantity']<=0){ continue;}?>
                                                    <p><?php echo $vv['Stock']['warehouse_name'];?>:<?php echo $vv['Stock']['quantity'];?></p>
                                                <?php }}?>
                                            <input type="hidden" name="order_product_quntity_old[]" id="order_product_quntity_<?php echo $v['id'];?>" value="<?php echo $v['product_quntity'];?>"  />
                                        </td>
                                        <!-- 折扣列 -->
                                        <td class="am-table-price" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>">
                                            <?php $discount=sprintf('%01.2f',($v['product_price']+$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity'])));?>
                                            <div style="width:150px;text-align:right;">
                                              <span id="order_product_shop_price_<?php echo $v['id'];?>">
                                                <?php echo isset($discount)?$discount:"";?>
                                              </span>
                                              <span style="color:#ccc;text-decoration: line-through;">
                                                <?php echo $v['product_price'];?>
                                              </span>
                                            </div>
                                            <input type="hidden" size="12" name="order_product_price[]"  id="order_product_price_<?php echo $v['id'];?>" value="<?php echo $v['lease_price'];?>" />
                                            <?php if((!($order_info['Order']['payment_status']==2 && ($order_info['Order']['shipping_status']==1 || $order_info['Order']['shipping_status']==2)) || ($v['refund_quantity']>0 && $v['refund_quantity'] != $v['product_quntity'])) && $admin['type']=='S'){?>
                                                <input type="text" onblur="changeDiscount(this.value,'<?php echo $v['id']?>','<?php echo $v['product_price']*$v['product_quntity'];?>','<?php echo $order_info['Order']['subtotal']?>')" value="<?php if($total_attr_price==0){echo isset($all_product_infos[$v['product_id'].$v['product_code']])&&$all_product_infos[$v['product_id'].$v['product_code']]>0?@sprintf('%01.2f',($v['product_price']+$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']))/($all_product_infos[$v['product_id'].$v['product_code']])*10):10;}else{echo isset($all_product_infos[$v['product_id'].$v['product_code']])&&$all_product_infos[$v['product_id'].$v['product_code']]>0?@sprintf('%01.2f',($v['product_price']+$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']))/($all_product_infos[$v['product_id'].$v['product_code']]+$total_attr_price)*10):10;}?>" style="width:60px;padding:0.625em 5px;float:left;" id="order_product_discount_<?php echo $v['id'];?>" />
                                                <span class="am-fl" style="padding:0.625em 2px;"><?php echo $backend_locale=="chi"?'折':'%'; ?>=</span>
                                                <input type = "text" name="order_product_discount[]" id="order_product_sumdiscount_<?php echo $v['id'];?>" value="<?php echo @sprintf("%01.2f",$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']));?>" style="width:60px;padding:0.625em 5px;float:left;"  onblur="changeSumDiscount(this.value,'<?php echo $v['id']?>','<?php echo isset($all_product_infos[$v['product_id'].$v['product_code']])?$all_product_infos[$v['product_id'].$v['product_code']]:'0';?>','<?php echo $v['product_price']*$v['product_quntity'];?>','<?php echo $order_info['Order']['subtotal']?>')" /><br />
                                            <?php }else{ $zhekou = isset($all_product_infos[$v['product_id'].$v['product_code']])&&$all_product_infos[$v['product_id'].$v['product_code']]>0?@sprintf('%01.2f',($v['product_price']+$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']))/$all_product_infos[$v['product_id'].$v['product_code']]*10):10;
                                                if($zhekou!=10){
                                                    echo $zhekou;
                                                    echo $backend_locale=="chi"?'折':'%'." =";
                                                    echo "<br />";
                                                }?>
                                                <input type = "hidden" name="order_product_discount[]" id="order_product_sumdiscount_<?php echo $v['id'];?>" value="<?php echo @sprintf("%01.2f",$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']));?>" />
                                            <?php }?>
                                        </td>
                                        <!-- 小计列 -->
                                        <td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>width:50px;">
                                            <span id="order_product_total_<?php echo $v['id'];?>" >
                                              <?php echo @sprintf('%01.2f',($v['product_price']*($v['product_quntity']-$v['refund_quantity'])+$v['adjust_fee']));?>
                                            </span>
                                            <?php if(isset($total_attr_price) && $total_attr_price>0){?>
                                                <div style="height:38px;"></div>
                                                <div><?php echo @sprintf("%01.2f",$total_attr_price);?></div>
                                            <?php }?>
                                        </td>
                                        <?php if(!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2))){?>
                                            <!-- 操作列 -->
                                            <td class="am-action" style="width:130px;white-space: nowrap;" >
                                                <?php if($v['product_type_id']!=""&&in_array($v['product_type_id'],$customize_product_type_list)&&isset($all_product_code_infos[$v['product_id']])&&$all_product_code_infos[$v['product_id']]!=$v['product_code']){ ?>
                                                    <a class="am-btn am-btn-default am-radius am-btn-sm" href="javascript:void(0);" data-am-modal="{target: '#update_pro_attr'}" onclick="update_pro_attr('<?php echo $v['product_code'];?>',<?php echo $v['product_id'];?>,<?php echo $v['id'];?>)"><?php echo $ld['attribute_edit'] ?></a><br> <?php  echo $html->link($ld['view_amendments'],'/orders/print_attr_value/'.$order_info['Order']['id'].'/'.$v['id'].'/'.$v['product_type_id'].'/'.$v['product_style_id'],array('target'=>'_blank','class'=>'am-btn am-btn-default am-btn-xs')); ?><br><?php } ?>
                                                <a class="am-btn am-btn-default am-text-danger am-radius am-btn-sm" href="javascript:;" onclick="delete_order_product(<?php echo $v['id'];?>,'<?php echo $v['product_code'];?>')"><?php echo $ld['delete']?></a>
                                            </td>
                                        <?php }else if($v['product_type_id']!=""&&in_array($v['product_type_id'],$customize_product_type_list)&&isset($all_product_code_infos[$v['product_id']])&&$all_product_code_infos[$v['product_id']]!=$v['product_code']){ ?>
                                            <td class="am-action" ><a class="am-btn am-btn-success am-radius am-btn-sm" href="javascript:void(0);" data-am-modal="{target: '#update_pro_attr'}" onclick="view_product_attr_value(<?php echo $v['product_id'];?>,'<?php echo $v['product_code'];?>',<?php echo $v['id'];?>)"><?php echo $ld['attribute_edit'] ?></a><br> <?php  echo $html->link($ld['view_amendments'],'/orders/print_attr_value/'.$order_info['Order']['id'].'/'.$v['id'].'/'.$v['product_type_id'].'/'.$v['product_style_id'],array('target'=>'_blank','class'=>'am-btn am-btn-success am-btn-xs')); ?>
                                            </td>
                                        <?php }else{ ?><td></td><?php } ?>
                                    </tr>
                                <?php }}?>
                            <tr>
                            	<td></td>
                                <?php if(isset($dealers_info)&&$dealers_info['Dealer']['min_num']!=''){?>
                                    <td><strong>最小起订数:</strong><span id="min_num"><?php echo $dealers_info['Dealer']['min_num'];?></span></td>
                                <?php	}else{?>
                                    <td></td>
                                <?php }?>
                                <td><strong><?php echo $ld['number_total']?>:</strong><span id="sum_quantity"><?php echo $sum_quantity;?></span></td>
                                <td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><strong><?php echo $ld['total_value_of_products']?>:</strong><span id="purchase_price_total"><?php printf($configs['price_format'],sprintf("%01.2f",$purchase_price_total));?></span></td>
                                <td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><strong><?php echo $ld['order_total']?>:</strong></td>
                                <td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>" colspan="2">
                                    <input type="hidden" id="order_subtotal" name="order_subtotal" value="<?php echo $order_info['Order']['subtotal']?>">
                                    <span id="last_order_subtotal"><?php printf($configs['price_format'],sprintf("%01.2f",$the_subtotal));?></span>
                                </td>
                            </tr>
                            <?php if($order_info['Order']['type']=='taobao'&&$the_subtotal!=$order_info['Order']['subtotal']){?>
                                <tr style="color:red">
                                    <td colspan="3"></td>
                                    <td><strong>淘宝数量总计:</strong><span id="sum_quantity"><?php echo $taobao_item_num;?></span></td>
                                    <td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><strong><?php echo '淘宝'.$ld['order_total']?></strong></td>
                                    <td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>" colspan="2">
                                        <span><?php echo sprintf('%01.2f',$taobao_subtotal);?></span>
                                    </td>
                                </tr>
                            <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="cost" class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" >
                    <?php echo $ld['expenses'] ?>
                </h4>
            </div>
            <div class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                    <table id="OrderProductTable" class="am-table" <?php if(!isset($order_info['OrderProduct'])||empty($order_info['OrderProduct'])){?>style="display:none"<?php }?>>
                        <tr>
                            <th rowspan="7"><?php echo $ld['message_to_customer']?></th>
                            <td rowspan="7" id="buyer_td">
                                <?php if(!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2))){?>
                                    <textarea id="order_to_buyer" style="height: 160px;width:200px;" onblur="order_total_change(this.id)"><?php echo $order_info['Order']['to_buyer']?></textarea>
                                <?php }else{?>
                                    <textarea id="order_to_buyer" style="display:none" ><?php echo $order_info['Order']['to_buyer']?></textarea>
                                    <?php echo $order_info['Order']['to_buyer'];}?>
                            </td>
                            <th style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none;';}?>"><?php echo $ld['shipping_fee']?></th>
                            <td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none;';}?>">
                                <span id="order_shipping_fee_span" <?php if(!($order_info['Order']['payment_status']==2 && ($order_info['Order']['shipping_status']==1 || $order_info['Order']['shipping_status']==2))){?> onclick="order_total_check('order_shipping_fee')" <?php }?>>
                                    <?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['shipping_fee']));?>
                                </span>
                                <input class="order_total_input" type="text" id="order_shipping_fee" value="<?php echo $order_info['Order']['shipping_fee'];?>" onblur="order_total_change(this.id)"/>
                            </td>
                            <th style="width:100px;<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none;';}?>"><?php echo $ld['order_total_amount']?></th>
                            <td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none;';}?>"><span id="order_total"><?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['total']));?></span></td>
                        </tr>
                        <tr style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>">
                            <th><?php echo $ld['lease_deposit_base']?></th>
                            <td>
                                <span id="order_insure_fee_span" <?php if(!($order_info['Order']['payment_status']==2 && ($order_info['Order']['shipping_status']==1 || $order_info['Order']['shipping_status']==2))){?> onclick="order_total_check('order_insure_fee')" <?php }?>>
                                    <?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['insure_fee']));?>
                                </span>
                                <input class="order_total_input" type="text" id="order_insure_fee" value="<?php echo $order_info['Order']['insure_fee'];?>" onblur="order_total_change(this.id)"/>
                            </td>
                            <th><?php echo $ld['amount_paid']?></th>
                            <td>
                                <span id="order_money_paid_span" <?php if((!($order_info['Order']['payment_status']==2 && ($order_info['Order']['shipping_status']==1 || $order_info['Order']['shipping_status']==2))) && $order_info['Order']['type_id']!='taobao'){?> onclick="order_total_check('order_money_paid')" <?php }?> >
                                    <?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['money_paid']));?>
                                </span>
                                <input class="order_total_input" type="text" id="order_money_paid" name="order_money_paid" value="<?php echo sprintf("%01.2f",$order_info['Order']['money_paid']);?>"  onblur="order_total_change(this.id)">
                            </td>
                        </tr>
                        <tr style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>">
                            <th><?php echo $ld['order_payment_fee']?></th>
                            <td>
                                <span id="order_payment_fee_span" <?php if(!($order_info['Order']['payment_status']==2 && ($order_info['Order']['shipping_status']==1 || $order_info['Order']['shipping_status']==2))){?> onclick="order_total_check('order_payment_fee')" <?php }?>>
                                    <?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['payment_fee']));?>
                                </span>
                                <input class="order_total_input" type="text" id="order_payment_fee" value="<?php echo $order_info['Order']['payment_fee'];?>" onblur="order_total_change(this.id)"/>
                            </td>
                            <th><?php echo $ld['discount_amount']?></th>
                            <td colspan="1">
                                <span id="order_discount_span" <?php if((!($order_info['Order']['payment_status']==2 && ($order_info['Order']['shipping_status']==1 || $order_info['Order']['shipping_status']==2))) && $order_info['Order']['type_id']!='taobao'){?> onclick="order_total_check('order_discount')" <?php }?>>
                                    <?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['discount']));?>
                                </span>
                                <input class="order_total_input" type="text" id="order_discount" value="<?php echo $order_info['Order']['discount']?>" onblur="order_total_change(this.id)"/>
                            </td>
                        </tr>
                        <tr>
                            <th style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><?php echo $ld['invoice_tax']?></th>
                            <td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>">
                                <span id="order_tax_span" <?php if(!($order_info['Order']['payment_status']==2 && ($order_info['Order']['shipping_status']==1 || $order_info['Order']['shipping_status']==2))){?> onclick="order_total_check('order_tax')" <?php }?>>
                                    <?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['tax']));?>
                                </span>
                                <input class="order_total_input" type="text" id="order_tax" value="<?php echo $order_info['Order']['tax']?>" onblur="order_total_change(this.id)"/>
                            </td>
                            <?php if(!empty($SVConfigs['rank']) && $SVConfigs['rank']>0){?>
                                <th><?php echo $ld['use_points']?></th>
                                <td><?php echo $order_info['Order']['point_use'];?></td>
                            <?php }else{?>
                                <td colspan="2"></td>
                            <?php } ?>
                        </tr>
                        <tr style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>">
                            <th><?php echo $ld['use_balance']?></th>
                            <td>
                                <?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['user_balance']));//printf($configs['price_format'],sprintf("%01.2f",isset($order_user_balance_log_info['UserBalanceLog']['amount'])?$order_user_balance_log_info['UserBalanceLog']['amount']:'0.00'));?>
                            </td>
                            <?php if(!empty($SVConfigs['rank']) && $SVConfigs['rank']>0){?>
                                <th><?php echo $ld['points_exchange']?></th>
                                <td><?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['point_fee']));?></td>
                            <?php }?>
                        </tr>
                        <tr style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>">
                            <th><?php echo $ld['points_exchange']; ?></th>
                            <td><?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['point_fee']))?></td>
                            <th><?php echo $ld['use_coupons']?>
                                <?php if(isset($order_info['Order']['coupon_id']) && $order_info['Order']['coupon_id']!=""){
                                    foreach($coupon_name_arr as $ca){
                                        if($ca == ""){
                                            continue;
                                        }
                                        echo  '['.$ca.']';
                                    }}?>
                            </th>
                            <td><?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['coupon_fee']))?></td>
                        </tr>
                        <tr>
                            <th><?php echo $ld['paymengts']?></th>
                            <td style="text-align:left;"><?php $sub_paymentlist=array(); ?>
                                <select id="order_payment_id" onchange="add_sub_pay(this.id);order_total_change('order_payment_id');" style="margin-bottom:10px;">
                                    <?php if(isset($payment_effective_list) && sizeof($payment_effective_list)>0){foreach($payment_effective_list as $k=>$v){
                                        if(!isset($v['SubMenu'])||empty($v['SubMenu'])){continue;}
                                        if($order_info['Order']['payment_id']==$v['Payment']['id']){
                                            $sub_paymentlist=$v['SubMenu'];
                                        }
                                        ?>
                                        <option value="<?php echo $v['Payment']['id']?>" <?php if($order_info['Order']['payment_id']==$v['Payment']['id']){echo "selected";}?> >
                                            <?php echo $v['PaymentI18n']['name'];?>
                                        </option>
                                    <?php }}?>
                                </select>
                                <select id="sub_pay" onchange="order_total_change('sub_pay');" <?php if(empty($order_info['Order']['sub_pay'])){?>style="width: auto;display: none;" <?php }?> >
                                    <?php
                                    if(!empty($order_info['Order']['sub_pay'])){?>
                                        <option value=""><?php echo $ld["please_select"];?></option>
                                        <?php foreach($sub_paymentlist as $v){?>
                                            <option value=<?php echo $v['Payment']['id'];?> <?php if(trim($order_info['Order']['sub_pay'])==trim($v['Payment']['id'])){?>selected<?php }?>><?php echo $v['PaymentI18n']['name'];?></option>
                                        <?php }
                                    }?>
                                </select>
                            </td>
                            <th style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><?php echo $ld['amount_payable']?></th>
                            <td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>">
                                <span id="need_pay">
                                    <?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['total']-$order_info["Order"]["coupon_fee"]-$order_info["Order"]["point_fee"]-$order_info['Order']['money_paid']-$order_info['Order']['discount']));?>
                                </span>
                            </td>
                        </tr>
                        <tfoot class="order_status">
                        <?php if(isset($apps['Applications']['APP-WAREHOUSE']) && $svshow->operator_privilege("order_shippings_mgt") && (!empty($warehouse_list) || (isset($apps['Applications']['APP-WAREHOUSE']['configs']['APP-WAREHOUSE-ALLOW-NO']) && $apps['Applications']['APP-WAREHOUSE']['configs']['APP-WAREHOUSE-ALLOW-NO']==1))){?>
                            <?php if(isset($apps['Applications']['APP-WAREHOUSE']['configs']['APP-WAREHOUSE-ORDER-SHIPPING']) && $apps['Applications']['APP-WAREHOUSE']['configs']['APP-WAREHOUSE-ORDER-SHIPPING']==1){?>
                                <tr id="order_outbound" class="order_status">
                                    <th>订单出库</th>
                                    <td colspan="5">
                                        <select name='warehouse'id='warehouse'>
                                            <?php if(isset($apps['Applications']['APP-WAREHOUSE']) && isset($apps['Applications']['APP-WAREHOUSE']['configs']['APP-WAREHOUSE-ALLOW-NO']) && $apps['Applications']['APP-WAREHOUSE']['configs']['APP-WAREHOUSE-ALLOW-NO']==1){?>
                                                <option value=''>无需出库</option>
                                            <?php }?>
                                            <?php if(!empty($warehouse_list)){foreach ($warehouse_list as $wk => $wv) {?>
                                                <option value="<?php echo $wv['Warehouse']['code']?>"><?php echo $wv['Warehouse']['warehouse_name']?></option>
                                            <?php }}?>
                                        </select>
                                    </td>
                                </tr>
                            <?php }?>
                        <?php }?>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div id="invoice" class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" >
                    <?php echo $ld['invoice_information'] ?>
                </h4>
            </div>
            <div class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                    <table class="am-table">
                        <tr>
                            <td><label class="am-form-label"><?php echo $ld['invoice_type']?></label></td>
                            <td>
                                <select id="order_invoice_type">
                                    <option value=''><?php echo $ld['please_select'];?></option>
                                    <?php if(isset($invoice_type_list) && sizeof($invoice_type_list)>0){foreach( $invoice_type_list as $k=>$v ){?>
                                        <option value='<?php echo $v["InvoiceType"]["id"];?>' <?php if($order_info['Order']['invoice_type']==$v["InvoiceType"]["id"]){echo "selected";}?>>
                                            <?php echo $v["InvoiceTypeI18n"]["name"];?>
                                        </option>
                                    <?php }}?>
                                </select>
                            </td>
                            <td><label class="am-form-label"><?php echo $ld['invoice_title']?></label></td>
                            <td><input type="text" id="order_invoice_payee" value="<?php echo $order_info['Order']['invoice_payee'];?>"/></td>
                        </tr>
                        <tr>
                            <td><label class="am-form-label"><?php echo $ld['invoice_content']?></label></td>
                            <td><textarea id="order_invoice_content"><?php echo $order_info['Order']['invoice_content'];?></textarea></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <!--		<tr>
		  <td><?php echo $ld['stock_handling']?></td>
		  <td colspan="3"><input type="text" id="order_how_oos" value="<?php echo $order_info['Order']['how_oos'];?>"/>
			<select onchange="document.getElementById('order_how_oos').value=this.value">
			  <option value=""><?php echo $ld['please_select']?>...</option>
			  <?php foreach( $information_resources_info["how_oos"] as $k=>$v){?>
			  <option value="<?php echo $k;?>"><?php echo $v;?></option>
			  <?php }?>
			</select>
		  </td>
		</tr>-->
                    </table>
                </div>
            </div>
        </div>
        <?php if(isset($configs['vendor_shipment'])&&$configs['vendor_shipment']=='1'&&$svshow->operator_privilege("view_factory_time")){ ?>
            <div id="supplier" class="am-panel am-panel-default">
                <div class="am-panel-hd">
                    <h4 class="am-panel-title" >
                        <?php echo $ld['vendor_information'] ?>
                    </h4>
                </div>
                <div class="am-panel-collapse am-collapse am-in">
                    <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                        <table class="am-table" style="margin-bottom:0px;">
                            <tr>
                                <th width="15%"><?php if($svshow->operator_privilege("edit_factory_time")){ ?><a class="am-btn am-btn-success am-radius am-btn-sm change_purchase_order" href="javascript:void(0);" data-am-modal="{target: '#po-vendor-popup', closeViaDimmer: 0}" onclick="change_purchase_order('<?php echo $order_info['Order']['order_code'];?>')"><?php echo $ld['edit'].$ld['vendor_information'] ?></a>
                                    <?php } ?></th>
                                <td>
                                    <ul class="am-avg-sm-3" style="margin-top:0px;">
                                        <li><?php echo $ld['pre_shipment'] ?> <span id="ESD_txt"><?php echo isset($purchase_order_data['PurchaseOrder']['ESD']) && $purchase_order_data['PurchaseOrder']['ESD'] != "0000-00-00"?$purchase_order_data['PurchaseOrder']['ESD']:'' ?></span></li>
                                        <li><?php echo $ld['real_shipment'] ?> <span id="ASD_txt"><?php echo isset($purchase_order_data['PurchaseOrder']['ASD']) && $purchase_order_data['PurchaseOrder']['ASD'] != "0000-00-00"?$purchase_order_data['PurchaseOrder']['ASD']:'' ?></span></li>
                                        <li><span id="po_logistics_company_txt"><?php if(isset($purchase_order_data['PurchaseOrder'])&&$purchase_order_data['PurchaseOrder']['logistics_company_id']!=0){
                                                echo isset($l_c_list[$purchase_order_data['PurchaseOrder']['logistics_company_id']]) ?$l_c_list[$purchase_order_data['PurchaseOrder']['logistics_company_id']]:'';
                                                ?></span><span id="po_invoice_no_txt"><?php
                                                echo $purchase_order_data['PurchaseOrder']['invoice_no']!=""?"-".$purchase_order_data['PurchaseOrder']['invoice_no']:'';
                                                } ?></span></li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 id="other_title" class="am-panel-title" data-am-collapse="{target: '#other_information'}"><?php echo $ld['other_information'] ?></h4>
            </div>
            <div id="other_information" class="am-panel-collapse am-collapse">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                    <table class="am-table">
                        <tr>
                            <td width="50%">
                                <div class="am-form-group">
                                    <label id="order_reffer_label" class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-form-label"><?php echo $ld['order_reffer']?></label>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                                        <label class="am-form-label">
                                            <?php echo isset($ld[$order_info['Order']['type']])?$ld[$order_info['Order']['type']]:"";
                                            if(isset($order_info['Order']['type_id'])&&$order_info['Order']['type_id']=="front"){echo "-".$ld['frontend'];}else{echo "-".$ld[$order_info['Order']['type_id']];}?>
                                        </label>
                                    </div>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="am-form-group">
                                    <label class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-form-label">
                                        <?php echo $ld["order_web_site_sources"];?>
                                    </label>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 referer" ><?php echo $order_info['Order']['referer'];?></div>
                                </div>
                            </td>
                            <td>
                                <div class="am-form-group">
                                    <label class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-form-label">
                                        <?php echo $ld["order_currency"];?>
                                    </label>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" ><label class="am-form-label"><?php echo $order_info['Order']['order_currency'];?></label></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="am-form-group">
                                    <label class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-form-label">
                                        <?php echo $ld["order_language"];?>
                                    </label>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" >
                                        <label class="am-form-label">
                                            <?php if(isset($lname)&&$lname!="" && isset($order_info['Order']['order_locale'])) echo isset($lname[$order_info['Order']['order_locale']])?$lname[$order_info['Order']['order_locale']]:"";?>
                                        </label>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="am-form-group">
                                    <label class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-form-label">
                                        <?php echo $ld["domain_from"];?>
                                    </label>
                                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" >
                                        <label class="am-form-label">
                                            <?php echo $order_info['Order']['order_domain']?>
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 id="operation_title" class="am-panel-title" data-am-collapse="{target: '#operation_records'}"><?php echo $ld['operation_records'] ?></h4>
            </div>
            <div id="operation_records" class="am-panel-collapse am-collapse">
                <table class="am-table">
                    <thead>
                    <tr>
                        <th class="thdate"><?php echo $ld['operation_time']?></th>
                        <th class="thname"><?php echo $ld['operator']?></th>
                        <th class="thtype" style="white-space: nowrap;"><?php echo $ld['order_status_operate']?></th>
                        <th class="thtype" style="white-space: nowrap;"><?php echo $ld['order_payment_status']?></th>
                        <th class="thtype" style="white-space: nowrap;"><?php echo $ld['shipping_status']?></th>
                        <td align="center" style="font-weight:bold;"><?php echo $ld['note2']?></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(isset($order_action_list) && sizeof($order_action_list)>0){foreach($order_action_list as $k=>$v){?>
                        <tr style="text-align: left;white-space: normal;">
                            <td class="tddate"><?php echo $v['OrderAction']['created']?></td>
                            <td style="white-space: normal;"><?php echo $v['OrderAction']['name']?></td>
                            <td><?php echo $Resource_info["order_status"][$v['OrderAction']['order_status']];?></td>
                            <td><?php echo $Resource_info["payment_status"][$v['OrderAction']['payment_status']];?></td>
                            <td><?php echo $Resource_info["shipping_status"][$v['OrderAction']['shipping_status']];?></td>
                            <td><?php echo $v['OrderAction']['action_note'];?></td>
                        </tr>
                    <?php }}?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="am-modal am-modal-no-btn" id="my-popup">
        <div class="am-popup-inner">
            <div class="am-popup-hd" style="height:49px;">
                <div class="am-popup-title" style="font-weight:400;line-height:53px;"><?php echo $ld['select_products'] ?></div>
      <span data-am-modal-close
            class="am-close">&times;</span>
            </div>
            <div class="am-popup-bd" style="max-height:500px;background-color:#fff;padding-left:0;padding-right:0">
                <div class="am-form-detail am-form am-form-horizontal">
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label">用户模板</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label"></div>
                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
                            <span>44 - 默认44</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="am-modal am-modal-no-btn" id="po-vendor-popup">
        <div class="am-popup-inner">
            <div class="am-popup-hd" >
                <h4 class="am-popup-title"><?php echo $ld['vendor_information'] ?></h4>
                <span data-am-modal-close class="am-close">&times;</span>
            </div>
            <div class="am-popup-bd">
                <table class="am-table am-form">
                    <tr>
                        <th width="20%"><?php echo $ld['pre_shipment'];?>:</th>
                        <td align="left"><label class='label_calendar'>
                                <input type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly id="ESD" value=""  /></label></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['real_shipment'];?>:</th>
                        <td align="left"><label class='label_calendar'>
                                <input type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly id="ASD" value="" /></label></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['order_logistics_company'] ?></th>
                        <td align="left">
                            <select id="po_logistics_company">
                                <option value="0"><?php echo $ld['please_select'] ?></option>
                                <?php if(isset($logistics_company_list)&&sizeof($logistics_company_list)>0){foreach($logistics_company_list as $k=>$v){ ?>
                                    <option value="<?php echo $v['LogisticsCompany']['id']; ?>"><?php echo $v['LogisticsCompany']['name']; ?></option>
                                <?php }} ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['invoice_number'] ?></th>
                        <td align="left"><label><input type="text" class="am-form-field am-input-sm" id="po_invoice_no" value="" /></label></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td align="left">
                            <input type="button" value="<?php echo $ld['save'] ?>" onclick="save_purchase_order()" class="am-btn am-btn-success am-radius am-btn-sm am-fl">
                            <input type="hidden" id="po_order_code" value="">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <script>
        function getProductInfo(code){
            document.getElementById('order_product_code').value=code;
        }
    </script>
<?php }else{ //ajax order product ?>
    <?php ob_start(); ?>
    <table class="am-table">
        <thead>
        <?php if((!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2)))){?>
            <tr>
                <td colspan="7">
                    <?php echo $form->create('',array('action'=>'/',"name"=>"OrdForm",'onsubmit'=>"search_order_product();return false;"));?>
                    <div class="am-form-group" style="margin-bottom:10px;">
                        <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
                            <?php if(isset($product_style_tree)&&sizeof($product_style_tree)>0){ ?>
                                <label class="am-form-label am-fl"><?php echo $ld['product_style'];?>:</label>
                                <div class="am-u-lg-4 am-u-md-5 am-u-sm-4">
                                    <select id="product_style" name="product_style">
                                        <option value=""><?php echo $ld['please_select']?></option>
                                        <?php foreach($product_style_tree as $v){?>
                                            <option value="<?php echo $v['ProductStyle']['id']?>"><?php echo $v['ProductStyleI18n']['style_name'];?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
                            <?php if(isset($product_type_tree)&&sizeof($product_type_tree)>0){ ?>
                                <label class="am-form-label am-fl"><?php echo $ld['all_product_type']?>:</label>
                                <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                                    <select id="product_type" name="product_type">
                                        <option value=""><?php echo $ld['please_select']?></option>
                                        <?php foreach($product_type_tree as $v){?>
                                            <option value="<?php echo $v['ProductType']['id']?>"><?php echo $v['ProductTypeI18n']['name'];?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
                            <?php if(isset($brands)&&!empty($brands)){?>
                                <label class="am-form-label am-fl"><?php echo $ld['brand']?>:</label>
                                <div class="am-u-lg-4 am-u-md-5 am-u-sm-4">
                                    <select id="product_brand" name="product_brand">
                                        <option value=""><?php echo $ld['please_select']?></option>
                                        <?php foreach($brands as $v){?>
                                            <option value="<?php echo $v['Brand']['id']?>"><?php echo $v['Brand']['code'].'-'.$v['BrandI18n']['name'];?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            <?php }?>
                        </div>
                        <div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
                            <label class="am-form-label am-fl"><?php echo $ld['product_sku_or_name']?>:</label>
                            <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                                <input type="text" id="order_product" onkeydown="if(event.keyCode==13){return false;}"/>
                            </div>
                            <input style="margin-left:10px;" type="button" id="add_product_button" onclick="search_order_product();"  class="am-btn am-btn-success am-radius am-btn-sm am-fl" value="<?php echo $ld['search'];?>" />
                            <select id="result" onchange="add_order_product(this.value)" class="selecthide am-fl" style="width:20%;margin:0px 5px 0 5px;display:none;">
                                <option value=""><?php echo $ld['please_select']?></option>
                            </select>
                            <span id="load_div"  class="order_status"></span>
                        </div>
                    </div>
                    <?php echo $form->end(); ?>
                </td>
            </tr>
        <?php }?>
        <tr>
            <th style="text-align:left;width:80px;"><?php echo $ld['product_image']?></th>
            <th><?php echo $ld['product_name']?></th>
            <!--	  <th style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><?php echo $ld["shop_price"];?><br>(<?php echo $ld["order_list_price"];?>)</th>-->
            <th class="am-table-order-qty"><?php echo $ld['order_quantity']?></th>
            <th class="am-table-price" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><?php echo $ld['price']?></th>
            <th style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><?php echo $ld['subtotal']?></th>
            <?php if(!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2))){?>
                <?php //if($order_info['Order']['type']!='taobao'){}?>
                <th><?php echo $ld['operate'];?></th>
            <?php }?>
        </tr>
        </thead>
        <tbody id="order_products_detail_innerhtml">
        <?php $the_subtotal=0;  $sum_quantity=0;$purchase_price_total=0;//产品总价值
        if(isset($order_info['OrderProduct']) && sizeof($order_info['OrderProduct'])>0){
            foreach($order_info['OrderProduct'] as $k=>$v){$total_attr_price=0;
		$purchase_price_total+=$v['purchase_price']*($v['product_quntity']-$v['refund_quantity']);
                $the_subtotal +=($v['product_price']*($v['product_quntity']-$v['refund_quantity'])+$v['adjust_fee']);$sum_quantity +=$v['product_quntity']?>
                <tr>
                    <td class="smail_img" style="text-align: right;padding: 5px 0px 3px;line-height: normal;">
                        <?php  if(!empty($v["file_url"])){?>
                            <a target="_blank" href="<?php echo $v['file_url'];?>"><?php echo $html->image($v['file_url'],array('style'=>'width: 100px;')); ?></a>
                        <?php }else{
                            if(isset($v['img_thumb'])) $img_thumb_format = explode("http://",$v['img_thumb']);
                            if(isset($img_thumb_format)&&count($img_thumb_format)==1){
                                echo empty($v['img_thumb'])?"":$html->image($v["img_thumb"]);
                            }else{
                                if(isset($v['parent_product_id'])&&!empty($v['parent_product_id'])){
                                    echo empty($v['img_thumb'])?"":$svshow->seo_link(array('type'=>'P','id'=>$v['parent_product_id'],'name'=>'','sub_name'=>$v['product_name'],'img'=>$v['img_thumb'],'style'=>'width: 100px;'));
                                }else{
                                    echo empty($v['img_thumb'])?"":$svshow->seo_link(array('type'=>'P','id'=>$v['product_id'],'name'=>'','sub_name'=>$v['product_name'],'img'=>$v['img_thumb'],'style'=>'width: 100px;'));
                                }
                            }
                        }?>
                    </td>
                    <!-- 名称 -->
                    <td>
                        <p style="line-height:20px;">
                            <?php if(isset($v['sku_product'])&&$v['sku_product']==1){
                                echo $svshow->seo_link(array('type'=>'P','id'=>$v['parent_product_id'],'name'=>'','sub_name'=>$v['product_name'],'style'=>'font-weight:bold'));
                            }else{
                                echo $svshow->seo_link(array('type'=>'P','id'=>$v['product_id'],'name'=>'','sub_name'=>$v['product_name'],'style'=>'font-weight:bold'));}?>
                        </p>
                        <!--循环套装商品-->
                        <?php if(isset($order_package_products[$v['product_id']])&&sizeof($order_package_products[$v['product_id']])>0){?>
                            <div>
                                <div style="font-size:12px;clear:both;"><?php echo $ld['package_product']?>:</div>
                                <?php foreach($order_package_products[$v['product_id']] as $pk=>$pv){?>
                                    <div class="pkg">
                                        <?php echo $pv['product_name']?>
                                    </div>
                                    <div style="width:20px;float:left;">*<?php echo $pv['product_quntity']?></div>
                                <?php }?>
                            </div>
                        <?php }?>
                        <div style="clear:both;">
                            <input type="hidden" name="order_product_id[]" value="<?php echo $v['id'];?>">
                            <input type="hidden" name="order_product_code[]" value="<?php echo $v['product_code'];?>">
                            <span><?php echo $ld['product_code']?>:&ensp;</span><?php echo $v['product_code'];?>
                        </div>
                        <?php if(isset($order_info['OrderProductValue'])&& count($order_info['OrderProductValue'])>0){?>
                            <div><?php //echo $ld['product_code'];?>属性:&ensp;</div>
                            <?php foreach($order_info['OrderProductValue'] as $opk=>$opv){ ?>
                                <?php if($opv['order_product_id']==$v['id'] && (int)$opv['attr_price']!=0){?>
                                    <?php $total_attr_price+=$opv['attr_price'];?>
                                    <div><?php echo $opv['attribute_value'];?>:&ensp;<?php echo $opv['attr_price'];?></div>
                                <?php }?>
                            <?php }?>
                        <?php }?>
                        <div>
                            <span><?php echo $ld['product_remark']?>:&ensp;</span>
                            <?php if(!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2))){?>
                                <input name="order_product_attr[]" onblur="order_products_data_save()" value="<?php if(isset($v['sku_product']['ProductAttribute'])){
                                    foreach($v['sku_product']['ProductAttribute'] as $attr_v){
                                        echo $attr_v['ProductAttribute']['product_type_attribute_value'].'\n';
                                    }
                                }else{ echo isset($v['product_attrbute'])&&!empty($v['product_attrbute'])?str_replace('<br />', '\n', $v['product_attrbute']):'';}?>" />
                            <?php }else{ if(isset($v['sku_product']['ProductAttribute'])){
                                foreach($v['sku_product']['ProductAttribute'] as $attr_v){
                                    echo $attr_v['ProductAttribute']['product_type_attribute_value']."\n";
                                }
                            }else{
                                echo isset($v['product_attrbute'])&&!empty($v['product_attrbute'])?str_replace("<br />", "\n", $v['product_attrbute']):'';}
                            }?>
                        </div>
                        <?php if(isset($configs['order_product_expires'])&&$configs['order_product_expires']=='1'&&$v['lease_type']=="L"){ ?>
                            <div class="orderproduct_lease_unit am-g am-margin-top-xs">
                                <div class="am-u-lg-3 am-u-md-4 am-u-sm-12 am-padding-0" style="line-height:37px;">
                                <span><?php echo $ld['lease_days'] ?>:&ensp;</span>
                                </div>
                                <div class="am-u-lg-6 am-u-md-5 am-u-sm-12">
                                <input type="text" name="order_product_lease_unit[]" value="<?php echo $v['lease_unit'];?>"/>
                                <input type="hidden" id="lease_unit" value="<?php echo $v['base_unit'];?>"/>
                                </div>
                            </div>
                              <div class="orderproduct_begin_date am-margin-top-xs am-margin-bottom-xs am-g">
                                                    <div class="am-u-lg-3 am-u-md-4 am-u-sm-12 am-padding-0" style="line-height:37px;">
                                                    <span><?php echo $ld['time_to_start']?>:&ensp;</span>
                                                    </div>
                                                    <div class="am-u-lg-6 am-u-md-5 am-u-sm-12">
                                                    <input type="text" name="order_product_begin_date[]" value="<?php echo $v['begin_date']!='2008-01-01 00:00:00'?date('Y-m-d',strtotime($v['begin_date'])):''; ?>"/>
                                                    </div>
                                                </div>
                            <div class="orderproduct_expire_date am-margin-top-xs am-margin-bottom-xs am-g">
                                <div class="am-u-lg-3 am-u-md-4 am-u-sm-12 am-padding-0" style="line-height:37px;">
                                <span><?php echo $ld['time_to_maturity']?>:&ensp;</span>
                                </div>
                                <div class="am-u-lg-6 am-u-md-5 am-u-sm-12">
                                <input type="text" name="order_product_expire_date[]" value="<?php echo $v['expire_date']!='2008-01-01 00:00:00'?date('Y-m-d',strtotime($v['expire_date'])):''; ?>"/>
                                </div>
                            </div>
                            <?php if($order_info['Order']['payment_status']==2&&$order_info['Order']['status']!=2&&$order_info['Order']['shipping_status']==1&&$v['status']==2){ ?>
                            <?php if($svshow->operator_privilege("lease_return")){  ?>
                                <a class="am-seevia-btn-edit am-btn am-btn-default am-btn-xs am-text-secondary" href="<?php echo $html->url('/orders/lease_order_status?order_id='.$order_info['Order']['id']).'&pro_id='.$v['id'].'&status=cancel'; ?>">
                                    <span class="am-icon-eye"></span><?php echo $ld['lease_return'] ?>
                                </a>
                            <?php } ?>
                            <?php if($svshow->operator_privilege("lease_return")&&$svshow->operator_privilege("lease_orders_add")){  ?>
                                <a class="am-seevia-btn-edit am-btn am-btn-default am-btn-xs am-text-secondary" href="<?php echo $html->url('/orders/lease_order_status?order_id='.$order_info['Order']['id']).'&pro_id='.$v['id'].'&status=change'; ?>">
                                    <span class="am-icon-eye"></span><?php echo $ld['exchange_goods'] ?>
                                </a>
                            <?php } ?>
                            <?php if($svshow->operator_privilege("lease_renew")){  ?>
                                <a class="am-seevia-btn-edit am-btn am-btn-default am-btn-xs am-text-secondary" onclick="show_time(<?php echo $order_info['Order']['id']?>,<?php echo $v['id']?>)">
                                    <span class="am-icon-eye"></span><?php echo $ld['renew'] ?>
                                </a>
                            <?php } ?>
                            <?php }} ?>
                    </td>
                    <!-- 价格列 -->
                    <!--	  <td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>width:100px;">
		<input type="hidden" size="12" name="order_product_price[]"  id="order_product_price_<?php echo $v['id'];?>" value="<?php echo isset($all_product_infos[$v['product_id']])?$all_product_infos[$v['product_id']]:'0';?>" />
		<?php echo $v['product_price'];?>(<?php echo isset($all_product_infos[$v['product_id']])?$all_product_infos[$v['product_id']]:'0';?>)
	  </td>-->
                    <!-- 数量列 -->
                    <td class="am-table-order-qty">
                        <?php if(!(($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2))){?>
                            <i class="am-icon-minus"></i><input type="text" size="2" name="order_product_quntity[]" id="order_product_quntity_<?php echo $v['id'];?>" value="<?php echo $v['product_quntity'];?>" onblur="changeNum(this.value,'<?php echo $v['id']?>','<?php echo $v['product_price']*$v['product_quntity'];?>','<?php echo $order_info['Order']['subtotal']?>')" /><i class="am-icon-plus"></i>
                        <?php }else{
                            echo '<span id="pq_'.$v["id"].'">'.$v['product_quntity'].'</span>&nbsp;';						          if(isset($refund_warehouse)&&!empty($refund_warehouse)&&$v['product_quntity']>$v['refund_quantity']){
                                echo $html->link($ld['return'],"javascript:;",array("onclick"=>"document.getElementById('refund_span_".$v['id']."').style.display=''",'escape' => false,'class'=>"cancelbtn"));?>
                                <span id='refund_span_<?php echo $v["id"]?>' style='display:none'>
		  <input type='text' value=0 id="refund_input_<?php echo $v['id']?>" style="width:30px">
		  <select id='refund_warehouse_<?php echo $v["id"]?>'>
              <option value=''><?php echo $ld['please_select']?></option>
              <?php foreach ($refund_warehouse as $rk => $rv) {?>
                  <option value="<?php echo $rv['Warehouse']['code']?>"><?php echo $rv['Warehouse']['warehouse_name']?></option>
              <?php }?>
          </select>
		  <input type='button' value="<?php echo $ld['save'];?>" onclick="changeRefund(<?php echo $v['id']?>)">
		</span>
                            <?php } if($v['refund_quantity']>0) { echo '退货数量：'.$v['refund_quantity'];}}?>
                        <input type='hidden' id='order_product_refund_<?php echo $v["id"]?>' value="<?php echo $v['refund_quantity']?>">
                        <p>
		  <span id="haveQuantity<?php echo $v['id'];?>" <?php if($v['product_quntity']>$all_product_quantity_infos[$v['product_code']]){?>style="color:red"<?php }?>>
              <input type="hidden" id="order_product_have_quntity_<?php echo $v['id'];?>" value="<?php echo $all_product_quantity_infos[$v['product_code']];?>">
		    <span style="font-size:13px;color:green;">
			  <?php //echo isset($all_product_quantity_infos[$v['product_code']])?$all_product_quantity_infos[$v['product_code']]:0?>
			</span>
		  </span>&nbsp;&nbsp;&nbsp;
                            <span style="font-size:13px">
			<?php //echo isset($stockProductQuantityInfo[$v['product_code']])?$stockProductQuantityInfo[$v['product_code']]:0;?>
		  </span>
                        </p>
                        <?php if(isset($stockProductInfo)&&isset($stockProductInfo[$v['product_id']])){?>
                            <?php foreach($stockProductInfo[$v['product_id']] as $kk=>$vv){ if($vv['Stock']['quantity']<=0){ continue;}?>
                                <p><?php echo $vv['Stock']['warehouse_name'];?>:<?php echo $vv['Stock']['quantity'];?></p>
                            <?php }}?>
                        <input type="hidden" name="order_product_quntity_old[]" id="order_product_quntity_<?php echo $v['id'];?>" value="<?php echo $v['product_quntity'];?>"  />
                    </td>
                    <!-- 折扣列 -->
                    <td class="am-table-price" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>">
                        <?php $discount=sprintf('%01.2f',($v['product_price']+$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity'])));?>
                        <div style="width:150px;text-align:right;">
		  <span id="order_product_shop_price_<?php echo $v['id'];?>">
		    <?php echo isset($discount)?$discount:"";?>
		  </span>
		  <span style="color:#ccc;text-decoration: line-through;">
		    <?php echo $v['product_price'];?>
		  </span>
                        </div>
                        <input type="hidden" size="12" name="order_product_price[]"  id="order_product_price_<?php echo $v['id'];?>" value="<?php echo $v['lease_price'];?>" />
                        <?php if((!($order_info['Order']['payment_status']==2 && ($order_info['Order']['shipping_status']==1 || $order_info['Order']['shipping_status']==2)) || ($v['refund_quantity']>0 && $v['refund_quantity'] != $v['product_quntity'])) && $admin['type']=='S'){?>
                            <input type="text" onblur="changeDiscount(this.value,'<?php echo $v['id']?>','<?php echo $v['product_price']*$v['product_quntity'];?>','<?php echo $order_info['Order']['subtotal']?>')" value="<?php if($total_attr_price==0){echo isset($all_product_infos[$v['product_id'].$v['product_code']])&&$all_product_infos[$v['product_id'].$v['product_code']]>0?@sprintf('%01.2f',($v['product_price']+$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']))/($all_product_infos[$v['product_id'].$v['product_code']])*10):10;}else{echo isset($all_product_infos[$v['product_id'].$v['product_code']])&&$all_product_infos[$v['product_id'].$v['product_code']]>0?@sprintf('%01.2f',($v['product_price']+$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']))/($all_product_infos[$v['product_id'].$v['product_code']]+$total_attr_price)*10):10;}?>" style="width:60px;padding:0.625em 5px;float:left;" id="order_product_discount_<?php echo $v['id'];?>" style="width:60px;padding:0.625em 5px;float:left;" id="order_product_discount_<?php echo $v['id'];?>" /><span class="am-fl" style="padding:0.625em 2px;">折 =</span>
                            <input type = "text" name="order_product_discount[]" id="order_product_sumdiscount_<?php echo $v['id'];?>" value="<?php echo @sprintf("%01.2f",$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']));?>" style="width:60px;padding:0.625em 5px;float:left;"  onblur="changeSumDiscount(this.value,'<?php echo $v['id']?>','<?php echo $all_product_infos[$v['product_id'].$v['product_code']];?>','<?php echo $v['product_price']*$v['product_quntity'];?>','<?php echo $order_info['Order']['subtotal']?>')" /><br />
                        <?php }else{ $zhekou = isset($all_product_infos[$v['product_id'].$v['product_code']])&&$all_product_infos[$v['product_id'].$v['product_code']]>0?@sprintf('%01.2f',($v['product_price']+$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']))/$all_product_infos[$v['product_id'].$v['product_code']]*10):10;
                            if($zhekou!=10){
                                echo $zhekou;
                                echo " 折 =";
                                echo "<br />";
                            }?>
                            <input type = "hidden" name="order_product_discount[]" id="order_product_sumdiscount_<?php echo $v['id'];?>" value="<?php echo @sprintf("%01.2f",$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']));?>" />
                        <?php }?>
                    </td>
                    <!-- 小计列 -->
                    <td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>width:50px;">
		<span id="order_product_total_<?php echo $v['id'];?>">
		  <?php echo @sprintf('%01.2f',($v['product_price']*($v['product_quntity']-$v['refund_quantity'])+$v['adjust_fee']));?>
            <span>
		<?php if(isset($total_attr_price) && $total_attr_price>0){?>
            <div style="height:38px;"></div>
            <div><?php echo @sprintf("%01.2f",$total_attr_price);?></div>
        <?php }?>
                    </td>
                    <?php if(!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2))){?>
                        <!-- 操作列 -->
                        <td class="am-action" style="width:130px;white-space: nowrap;">
                            <?php if($v['product_type_id']!=""&&in_array($v['product_type_id'],$customize_product_type_list)&&isset($all_product_code_infos[$v['product_id']])&&$all_product_code_infos[$v['product_id']]!=$v['product_code']){ ?>
                                <a class="am-btn am-btn-success am-radius am-btn-sm" href="javascript:void(0);" data-am-modal="{target: '#update_pro_attr'}" onclick="update_pro_attr('<?php echo $v['product_code'];?>',<?php echo $v['product_id'];?>,<?php echo $v['id'];?>)"><?php echo $ld['attribute_edit'] ?></a><br> <?php  echo $html->link($ld['view_amendments'],'/orders/print_attr_value/'.$order_info['Order']['id'].'/'.$v['id'].'/'.$v['product_type_id'].'/'.$v['product_style_id'],array('target'=>'_blank','class'=>'am-btn am-btn-success am-radius am-btn-sm')); ?><br><?php } ?>
                            <a class="am-btn am-btn-danger am-radius am-btn-sm" href="javascript:;" onclick="delete_order_product(<?php echo $v['id'];?>,'<?php echo $v['product_code'];?>')"><?php echo $ld['delete']?></a>
                        </td>
                    <?php }else if($v['product_type_id']!=""&&in_array($v['product_type_id'],$customize_product_type_list)&&isset($all_product_code_infos[$v['product_id']])&&$all_product_code_infos[$v['product_id']]!=$v['product_code']){ ?>
                        <td class="am-action"><a class="am-btn am-btn-success am-radius am-btn-sm" href="javascript:void(0);" data-am-modal="{target: '#update_pro_attr', closeViaDimmer: 0}" onclick="view_product_attr_value(<?php echo $v['product_id'];?>,'<?php echo $v['product_code'];?>',<?php echo $v['id'];?>)"><?php echo $ld['attribute_edit'] ?></a><br> <?php  echo $html->link($ld['view_amendments'],'/orders/print_attr_value/'.$order_info['Order']['id'].'/'.$v['id'].'/'.$v['product_type_id'].'/'.$v['product_style_id'],array('target'=>'_blank','class'=>'am-btn am-btn-success am-radius am-btn-sm')); ?>
                        </td>
                    <?php }else{ ?><td></td><?php } ?>
                </tr>
            <?php }}?>
        <tr>
            <td></td>
            <?php if(isset($dealers_info)&&$dealers_info['Dealer']['min_num']!=''){?>
                <td><strong>最小起订数:</strong><span id="min_num"><?php echo $dealers_info['Dealer']['min_num'];?></span></td>
            <?php	}else{?>
                <td></td>
            <?php }?>
            <td><strong><?php echo $ld['number_total']?>:</strong><span id="sum_quantity"><?php echo $sum_quantity;?></span></td>
            <td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><strong><?php echo $ld['total_value_of_products']?>:</strong><span id="purchase_price_total"><?php printf($configs['price_format'],sprintf("%01.2f",$purchase_price_total));?></span></td>
            <td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><strong><?php echo $ld['order_total']?>:</strong></td>
            <td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>" colspan="2">
                <input type="hidden" id="order_subtotal" name="order_subtotal" value="<?php echo $order_info['Order']['subtotal']?>">
                <span id="last_order_subtotal"><?php echo sprintf('%01.2f',$the_subtotal);?></span>
            </td>
        </tr>
        <?php if($order_info['Order']['type']=='taobao'&&$the_subtotal!=$order_info['Order']['subtotal']){?>
            <tr style="color:red">
                <td colspan="3"></td>
                <td><strong>淘宝数量总计:</strong><span id="sum_quantity"><?php echo $taobao_item_num;?></span></td>
                <td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><strong><?php echo '淘宝'.$ld['order_total']?></strong></td>
                <td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>" colspan="2">
                    <span><?php echo sprintf('%01.2f',$taobao_subtotal);?></span>
                </td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <?php
    $out1 = ob_get_contents();ob_end_clean();
    $result=array("result"=>$out1);
    die(json_encode($result));?>
<?php } // ajax order product end ?>
<!-- 属性修改弹窗start -->
<div class="am-popup" id="update_pro_attr">
    <div class="am-popup-inner">
        <div class="am-popup-hd" style=" z-index: 11;">
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-popup-bd"></div>
    </div>
</div>
<!-- 属性修改弹窗end -->
<!-- 物流跟踪弹窗start -->
<div class="am-modal am-modal-no-btn" id="express_info_popup">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title"><?php echo $ld['logistics_tracking']?></h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd am-text-left" id="ex_info"></div>
    </div>
</div>
<!-- 物流跟踪弹窗end -->
<div class="am-modal am-modal-no-btn tablemain" tabindex="-1" id="lease" >
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style="border-bottom:1px solid #ddd;">订单续租
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd" style="padding-top:15px;">
            <form id='lease3' method="POST" class="am-form am-form-horizontal">
                <div class="am-form-group">
                    <div class="am-g am-margin-top-xs">
                        <div class="am-u-sm-4 am-margin-top-0 am-text-right" style="line-height:37px;font-size:16px;"><?php echo $ld['lease_days'] ?>：</div>
                        <div class="am-u-sm-8 am-margin-top-0 am-text-left" style="line-height:37px">
                            <div class="am-u-sm-9">
                            <label style="font-size:16px;" id="unit"/></label>
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
<style type="text/css">
    .user_avatar img{max-width:150px;max-height:150px;width:100%;}
    .create_user_info{display:none;}
    .smail_img img{width:100px;}
    .order_address_info_tbody{display:none;}
    .am-form .address_span,.am-form .order_user_span{font-weight:normal;}
    .am-form .am-form-group .address,.am-form .am-form-group .order_user{ display:none;}
    /*#order_best_time{display:inline;}*/
    #country_select,#province_select,#city_select{display:none;}
    #order_products_detail_innerhtml textarea{width:180px;}
    #edit_order_user{margin:0 5px;position: relative;top: 1px;}
    #search_user_infos.selecthide {display: none !important;}
    #OrderProductTable{margin:0;}
    .po-vendor-popup .am-selected{padding:0px;}
    .am-form-label{font-weight:normal;}
    #OrderProductTable td{text-align:right;}
    #OrderProductTable td input[type="text"]{max-width:100px;}
    #OrderProductTable td#buyer_td{text-align:left;}
    .am-list > li > a.am-active, .am-list > li > a.am-active:hover, .am-list > li > a.am-active:focus{background:#5eb95e;}
    .operation_notes_action_hid{display:none;}
</style>
<script>
    var obj_lease_unit = $("#lease_unit").val();
    function change_lease_day (obj) {
        var lease_number = Number($(obj).val());
        if (!!lease_number == false) {
            alert("请输入正确的天数");
            $(obj).val('');
            return false;
        };
        var lease_days = obj_lease_unit*lease_number;
        $("#unit").html(obj_lease_unit+"x"+lease_number+"="+lease_days);
    }

    function show_time(order_id,pro_id){
        $("#lease_order").val(order_id);
        $("#lease_pro").val(pro_id);
        $("#unit").html($("#lease_unit").val());
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

    function openother(){
        $("#other_information").collapse('open');
    }

    function opencollapse(){
        $("#operation_records").collapse('open');
    }

    <?php if((isset($order_info['Order']['country']) || $order_info['Order']['country']!="") && (isset($order_info['Order']['province']) || $order_info['Order']['province']!="") && (isset($order_info['Order']['city']) || $order_info['Order']['city']!="")){?>
    <?php if(isset($order_info['Order']['regions'])){$region_arr=explode(" ",$order_info['Order']['regions']);}?>
    getRegions(0,'',"<?php echo isset($order_info['Order'])?$order_info['Order']['country']:'' ?>");
    <?php if(isset($order_info['Order'])&&isset($regions_infovalues[$order_info['Order']['country']])){ ?>
    getRegions(<?php echo $regions_infovalues[$order_info['Order']['country']]; ?>,'country',"<?php echo isset($order_info['Order'])?$order_info['Order']['province']:'' ?>");
    <?php } ?>
    <?php if(isset($order_info['Order'])&&isset($regions_infovalues[$order_info['Order']['province']])){ ?>
    getRegions(<?php echo $regions_infovalues[$order_info['Order']['province']]; ?>,'province',"<?php echo isset($order_info['Order'])?$order_info['Order']['city']:'' ?>");
    <?php } ?>
    <?php }?>
    <?php if($order_info['Order']['shipping_status']==1){?>
    //document.getElementById('order_logistics_company_id_tr').style.display='';
    <?php if(!empty($order_info['Order']['invoice_no'])){?>
    //document.getElementById('order_invoice_no_tr').style.display='';
    <?php }}?>

    $("#order_product_div").on('click',".am-icon-plus",function(){
        //找到当前数量框
        var quantity=$(this).parent().find("input[type='text']");
        var n=quantity.val();
        var num=parseInt(n)+1;
        if(num==0){alert("Purchase quantity can't equal to zero!");}
        quantity.val(num);
        quantity.blur();
    });

    $("#order_product_div").on('click',".am-icon-minus",function(){
        //找到当前数量框
        var quantity=$(this).parent().find("input[type='text']");
        var n=quantity.val();
        var num=parseInt(n)-1;
        if(num==0){alert("Purchase quantity can't equal to zero!"); return}
        quantity.val(num);
        quantity.blur();
    });

    //添加订单商品
    function order_export_flag(export_flag){
        var order_id = document.getElementById("order_id").value;//订单ID
        var postData = "order_id="+order_id;
        YUI().use("io",function(Y) {
            var cfg = {
                method: "POST",
                data: postData
            };
            var sUrl = admin_webroot+"orders/order_export_flag/"+export_flag;//访问的URL地址
            var request = Y.io(sUrl, cfg);//开始请求
            var handleSuccess = function(ioId, o){
                if(o.responseText !== undefined){
                    try{
                        eval('var result='+o.responseText);
                    }catch(e){
                        alert(j_object_transform_failed);
                        alert(o.responseText);
                    }
                    if(result.code==1){
                        if(export_flag==1){
                            document.getElementById("export_flag_button_1").style.display="none";
                            document.getElementById("export_flag_button_0").style.display="inline-block";
                            alert(result.message);
                        }
                        if(export_flag==0){
                            document.getElementById("export_flag_button_0").style.display="none";
                            document.getElementById("export_flag_button_1").style.display="inline-block";
                            alert(result.message);
                        }
                    }
                    else{
                        alert(j_failed_order_update);
                    }
                }
            }
            var handleFailure = function(ioId, o){}
            Y.on('io:success', handleSuccess);
            Y.on('io:failure', handleFailure);
        });
    }

    function change_order_to_type(obj,to_type){
        YUI().use("io",function(Y) {
            if(obj.value==''){
                Y.one("#order_product_div").setStyle('display','none');
                return;
            }
            if(obj=='0'){
                var type='0';
            }else{
                var type=obj.value;
            }
            var sUrl = admin_webroot+'orders/change_order_to_type';//访问的URL地址
            var postData = "to_type_id="+type+"&to_type="+to_type+"&oid="+Y.one('#order_id').get('value');
            var cfg = {
                method: "POST",
                data: postData
            };
            var request = Y.io(sUrl,cfg);//开始请求
            var handleSuccess = function(ioId, o){
                try{
                    eval('result='+o.responseText);
                }catch (e){
                    alert(j_object_transform_failed);
                    alert(o.responseText);
                }
                if(result.code==1){
                    //	Y.one("#order_product_div").setStyle('display','');
                    document.getElementById('order_product_div').style.display="";
                    if(result.reload){
                        window.location.reload();
                    }
                    //		order_reflash(result.hasproduct,result.total,result.need_pay);
                }
            }
            var handleFailure = function(ioId, o){
                //alert("异步请求失败!");
            }
            Y.on('io:success', handleSuccess);
            Y.on('io:failure', handleFailure);
        });
    }
    if(document.getElementById("order_invoice_no")!=null &&document.getElementById("logistics_company_id")!=null){
        var invoice_no=document.getElementById("order_invoice_no").value;
        var logistics_company_id=document.getElementById("logistics_company_id").value;
        if(invoice_no!=""&&logistics_company_id!=""){
            express_inquire(logistics_company_id,invoice_no);
        }
    }
    //YUI().use("node",function(Y) {
    //	if(document.getElementById("order_invoice_no")!=null &&document.getElementById("logistics_company_id")!=null){
    //		var invoice_no=document.getElementById("order_invoice_no").value;
    //		var logistics_company_id=document.getElementById("logistics_company_id").value;
    //		if(invoice_no!=""&&logistics_company_id!=""){
    //			express_inquire(logistics_company_id,invoice_no);
    //		}
    //	}
    //	if(document.getElementById("ec_export_flag")!=null ){
    //		export_flag_button();
    //	}
    //	if(document.getElementById("order_split").style.display=="none"){
    //		var one = Y.one('#splitorder'),
    //			two = Y.one('.splitorder');
    //		over=function(){two.addClass("hover")};
    //		out=function(){two.removeClass("hover")};
    //		one.on('mouseenter',over);
    //		one.on('mouseleave',out);
    //	}
    //});

    function export_flag_button(){
        YUI().use('io',function(Y){
            var order_id = document.getElementById("order_id").value;
            var postData ="order_id="+order_id;
            var cfg = {
                method: "POST",
                data: postData
            };
            var sUrl = admin_webroot+"orders/export_flag_button/";//访问的URL地址
            var request = Y.io(sUrl, cfg);//开始请求
            var handleSuccess = function(ioId, o){
                try{
                    eval('var result='+o.responseText);
                }catch(e){
                    alert(j_object_transform_failed);
                    alert(o.responseText);
                }
                if(result.flag==1){
                    var ec_flag=document.getElementById("ec_export_flag");
                    ec_flag.style.display="inline-block";
                    ec_flag.innerHTML = result.ec_detail;
                    if(result.message==1 || result.message==2){
                        document.getElementById("export_flag_button_0").style.display="inline-block";
                        document.getElementById("export_flag_button_0").style.background="#F87620";
                        document.getElementById("export_flag_button_0").style.border="1px solid #F87620";
                        //	document.getElementById("export_flag_button_0").disabled="disabled";
                    }
                    if(result.message==0){
                        document.getElementById("export_flag_button_1").style.display="inline-block";
                        //	document.getElementById("export_flag_button_1").style.background="#F87620";
                        //	document.getElementById("export_flag_button_1").style.border="1px solid #F87620";
                        //	document.getElementById("export_flag_button_1").disabled="disabled";
                    }
                }
            }
            var handleFailure = function(ioId, o){}
            Y.on('io:success', handleSuccess);
            Y.on('io:failure', handleFailure);
        })
    }

    function express_inquire(logistics_company_id,invoice_no){
        var express_code = document.getElementById("Company_express_code").value;
        var sel = document.getElementById('express_info');
        var sell = document.getElementById('ex_info');
        var githubAPI="http://www.kuaidi100.com/query?";
        //此处url需要改为不在本域的地址,该页需要返回json数据
        //var githubAPI ="http://www.kuaidi100.com/query?type=huitongkuaidi&postid=210252031223";
        var url=githubAPI;
        $.ajax({
            async:false,
            url: url,
            type:"GET",
            dataType:"jsonp",
            jsonp: 'callback',
            data:{"type":express_code,"postid":invoice_no},
            success: function(json){
                var message="";
                if(json.message=='ok'){
                    var data=json.data;
                    for(var v in data){
                        message	+=data[v].time+" =>"+data[v].context+"<br />";
                    }
                    sel.style.display="table-row";
                    sell.innerHTML = message;
                }else{
                    alert(json.message);
                }
            }
        });
    }

    function timestamp() {
        var timestamp = Date.parse(new Date());
        return timestamp;
    }

    function on_hide(){
        document.getElementById("order_to_type").style.display = (document.getElementById("radio_dealer").checked == true) ? "inline-block" : "none";
        document.getElementById("labden").style.display = (document.getElementById("radio_dealer").checked == true) ? "inline-block" : "none";
        if(document.getElementById("radio_system").checked == true){
            var radio_system=document.getElementById("radio_system").value;
            change_order_to_type('0',radio_system);
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
                    var sel = document.getElementById('order_to_type');
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
                    document.getElementById('order_to_type').className=""; }
            }
            var handleFailure = function(ioId,o){}
            Y.on('io:success', handleSuccess);
            Y.on('io:failure', handleFailure);
        });
    }

    //商品属性修改弹窗加载
    function update_pro_attr(pro_code,pro_id,order_product_id){
        if(order_product_id=="undefined"){
            order_product_id=0;
        }
        if(document.getElementById("opener_select_user_id")){
            var user_id=$("#opener_select_user_id").val();
            if(user_id==""){
                alert('未找到用户!');return false;
            }
            var order_id=$("#order_id").val();
            if(pro_code!=""){
                $.ajax({ url: admin_webroot+"orders/update_order_product_attr/",
                    type:"POST",
                    data:{pro_code:pro_code,pro_id:pro_id,user_id:user_id,order_id:order_id,order_product_id:order_product_id},
                    dataType:"html",
                    success: function(data){
                        $(".am-dimmer").css("display","block");
                        $("#update_pro_attr .am-popup-bd").html(data);
                    }
                });
            }
        }else{
            alert('用户不存在!');return false;
        }
    }

    function view_product_attr_value(pro_id,pro_code,order_pro_id){
        var user_id="<?php echo $order_info['Order']['user_id']; ?>";
        var order_id="<?php echo $order_info['Order']['id']; ?>";
        $.ajax({ url: admin_webroot+"orders/update_order_product_attr/attr_view",
            type:"POST",
            data:{pro_code:pro_code,pro_id:pro_id,user_id:user_id,order_id:order_id,order_product_id:order_pro_id},
            dataType:"html",
            success: function(data){
                $(".am-dimmer").css("display","block");
                $("#update_pro_attr .am-popup-bd").html(data);
            }
        });
    }

    var po_logistics_company_selhtml=$("#po_logistics_company").parent().html();

    function createPoLogisticsCompanySel(value){
        var slehtml="<select id='po_logistics_company'>";
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
        $("#po_order_code").val(order_code);
        $("#ESD").val("");
        $("#ASD").val("");
        $("#po_invoice_no").val("");
        var po_logistics_companyselect=po_logistics_company_selhtml;
        $.ajax({ url: admin_webroot+"orders/change_vendor_information/",
            type:"POST",
            data:{order_code:order_code},
            dataType:"json",
            success: function(data){
                try{
                    if(data.code==1){
                        var data=data.data;
                        if(data.ESD!="0000-00-00"){
                            $("#po-vendor-popup #ESD").val(data.ESD);
                        }
                        if(data.ASD!="0000-00-00"){
                            $("#po-vendor-popup #ASD").val(data.ASD);
                        }
                        po_logistics_companyselect=createPoLogisticsCompanySel(data.logistics_company_id);
                        if(data.invoice_no!=""){
                            $("#po-vendor-popup #po_invoice_no").val(data.invoice_no);
                        }
                    }
                }catch(e){
                    alert(j_object_transform_failed);
                }
                $("#po-vendor-popup #po_logistics_company").parent().html(po_logistics_companyselect);
                $("#po-vendor-popup #po_logistics_company").selected();
            }
        });
    }

    function save_purchase_order(){
        var order_code=$("#po_order_code").val();
        var ESD=$("#po-vendor-popup #ESD").val();
        var ASD=$("#po-vendor-popup #ASD").val();
        var logistics_company_id=$("#po-vendor-popup #po_logistics_company").val();
        var invoice_no=$("#po-vendor-popup #po_invoice_no").val();
        $.ajax({ url: admin_webroot+"orders/change_vendor_information/data_save",
            type:"POST",
            data:{'order_code':order_code,'ESD':ESD,'ASD':ASD,'logistics_company_id':logistics_company_id,'invoice_no':invoice_no},
            dataType:"json",
            success: function(data){
                try{
                    if(data.code==1){
                        if(ESD!=""){
                            $("#ESD_txt").html(ESD);
                        }
                        if(ASD!=""){
                            $("#ASD_txt").html(ASD);
                        }
                        var company_detail="";
                        if(logistics_company_id!="0"){
                            company_detail=$("#po-vendor-popup #po_logistics_company").find("option[value="+logistics_company_id+"]").text();
                        }
                        $("#po_logistics_company_txt").html(company_detail);
                        $("#po_invoice_no_txt").html(invoice_no!=""?"-"+invoice_no:"");
                        $(".am-close").click();
                    }else{
                        alert(result.msg);
                    }
                }catch(e){
                    alert(j_object_transform_failed);
                }
            }
        });
    }

    orderproduct_expire_date();
    orderproduct_begin_date();
</script>