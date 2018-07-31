<?php if($is_ajax==0){?>
<script type="text/javascript">
	var user_address_obj = <?php echo $user_addresses_json;?>;
	var regions_info=<?php echo $regions_info;?>
</script>
<?php
	echo $javascript->link('/skins/default/js/order.amazeui');
?>
<script src="<?php echo $webroot; ?>plugins/ajaxfileupload.js" type="text/javascript"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript"></script>
<?php  foreach ($pro_type_attr_info as $k => $v) {
	foreach ($v['AttributeOption'] as $kk => $vv) {
		$pro_attr_check[$vv['option_value']] = $vv['option_name'];
	}
} ?>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-detail-menu" >
  <ul class="am-list admin-sidebar-list am-hide-sm-only" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
    <li><a href="#basic_info"><?php echo $ld['basic_information']?></a></li>
    <li><a href="#consignee_information"><?php echo $ld['receiving_information']?></a></li>
	<li><a href="#pro_info"><?php echo $ld['product_information']?></a></li>
	<li><a href="#cost"><?php echo $ld['expenses']?></a></li>
	<li><a href="#invoice"><?php echo $ld['invoice_information']?></a></li>
	<?php if(isset($configs['vendor_shipment'])&&$configs['vendor_shipment']=='1'&&$svshow->operator_privilege("view_factory_time")){ ?>
	<li><a href="#supplier"><?php echo $ld['vendor_information']?></a></li>
	<?php } ?>
    <li><a onclick="openother()" href="#other_title"><?php echo $ld['other_information']?></a></li>
    <li><a href="#logistics_information" onclick="$('#logistics_information').collapse('open');"><?php echo $ld['logistics_information']?></a></li>
    <li><a onclick="opencollapse()" href="#operation_title"><?php echo $ld['operation_records']?></a></li>
  </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-detail-view" id="accordion" >
<!-- 订单按钮操作 -->
<div class="btnouter am-text-right" id="ftr" data-am-sticky="{top:'7%'}" >
    <div id="OrderStatusChange">
		<div class="am-form-group">
			<div class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label" style="display:none;">
				<?php echo $ld['order_status_change']?>
				<input id="order_status_change" type="hidden" value="" />
			</div>
			<div id="status_change_td" class="am-u-lg-9 am-u-md-9 am-u-sm-8">
			
				<?php if(isset($order_action['check']) && $order_action['check']){?>
				<input id="order_check" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_chang" onclick="order_status_select(this.id)" value="<?php echo $ld['order_check']?>" />
				<?php } ?>
				<?php if(isset($order_action['cancel_check']) && $order_action['cancel_check']){?>
				<input id="order_check_remove" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_chang" onclick="order_status_select(this.id)" value="<?php echo $ld['remove_checked']?>" />
				<?php } ?>
				<?php if(isset($order_action['confirm']) && $order_action['confirm']){?>
					<input id="order_confirm" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_change" onclick="order_status_select(this.id)" value="<?php echo $ld['confirm']?>" />
				<?php }?>
				<?php if(isset($order_action['pickup']) && $order_action['pickup']){?>
					<input id="order_pickup" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_change" onclick="order_status_select(this.id)" value="<?php echo $ld['pick_up']; ?>" />
				<?php } ?>
				<?php if(isset($order_action['pay']) && $order_action['pay']){?>
					<input id="order_payment" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_change" onclick="order_status_select(this.id)" value="<?php echo $ld['payment_btn']?>" />
				  <?php } ?>
				<?php if(isset($order_action['unpay']) && $order_action['unpay']){ ?>
					<input id="order_make_no_payments" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_change" onclick="order_status_select(this.id)" value="<?php echo $ld['make_unpay']?>" />
				<?php }?>
				<?php if(isset($order_action['prepare']) && $order_action['prepare']){?>
				<input id="order_picking" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_change" onclick="order_status_select(this.id)" value="<?php echo $ld['picking_btn']?>" />
				<?php }?>
				<?php if(isset($order_action['ship']) && $order_action['ship']){?>
					<input id="order_delivery" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_change" onclick="order_status_select(this.id)" value="<?php echo $ld['delivery']?>" />
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
				<?php if(isset($order_action['return']) && $order_action['return']){?>
					<input id="order_returns" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_change" onclick="order_status_select(this.id)" value="<?php echo $ld['return']?>" />
				<?php }?>
				<?php if(isset($order_action['after_service']) && $order_action['after_service']){ ?>
					<input id="after_service" class="am-btn am-btn-success am-radius am-btn-sm" type="button" name="order_status_change" onclick="order_status_select(this.id)" value="<?php echo $ld['service']?>" />
				<?php }?>
			</div>
		</div>
	</div>
</div>
<div class="am-modal am-modal-no-btn" id="order_status_change_modal">
	<div class="am-modal-dialog">
		<div class="am-modal-hd">
			<div class="am-modal-title"></div>
			<span data-am-modal-close class="am-close">&times;</span>
		</div>
		<div class="am-modal-bd">
			<div class="am-form-detail am-form am-form-horizontal">
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" style="padding-right:0;"><?php echo $ld['operation_remarks'] ?></label>
					<div class="am-u-lg-9 am-u-md-8 am-u-sm-7">
						<textarea id="operation_notes" style='width:100%;'></textarea>
					</div>
					<div class='am-cf'></div>
				</div>
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
					<div class="am-u-lg-9 am-u-md-8 am-u-sm-7 am-text-left" style="padding-right:0;">
						<input type="hidden" id="order_id" value="<?php echo $order_info['Order']['id']?>" />
						<input class="am-btn am-btn-success am-radius am-btn-sm" type="button" id="order_status_change_btn" value="<?php echo $ld['d_submit'];?>" onclick="order_status_change();" />
				  		<input class="am-btn am-btn-success am-radius am-btn-sm" type="button" value="<?php echo $ld['cancel']?>" onclick="$('#order_status_change_modal').modal('close');" />
					</div>
					<div class='am-cf'></div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- 订单按钮操作 -->
  <div id="basic_info" class="am-panel am-panel-default">
    <div class="am-panel-hd">
      <h4 class="am-panel-title">
      	<?php echo $ld['basic_information'] ?>
      </h4>
    </div>
    <div id="basic_information" class="am-panel-collapse am-collapse am-in" style="padding-bottom:1rem">
      <div  class="am-panel-bd am-form-detail am-form am-form-horizontal" >
      	<!-- 开始 -->
		<div id="order_address_info_div ">
			
		  <!-- 基本信息移动端 -->
		   <div id="order_user_info" class="am-g" style="margin-bottom:0;">

					<div class="am-form-group">
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style=""><?php echo '服务类型'; ?>:</div>
						<div class="am-u-lg-10 am-u-md-10 am-u-sm-9" >
							<input type='hidden' name='service_type' value="<?php echo isset($order_info['Order']['service_type'])?$order_info['Order']['service_type']:''; ?>" /><?php
								echo isset($Resource_info['order_service_type'][$order_info['Order']['service_type']])?$Resource_info['order_service_type'][$order_info['Order']['service_type']]:'';
							?>
						</div>
					</div>
					
				<div class="am-form-group" style="margin-top:1rem;">
			      <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" ><?php echo $ld['order_code']?>:</div>
			      <div class="am-u-lg-10 am-u-md-10 am-u-sm-9" >
				    <span>#<?php echo $order_info['Order']['order_code'];?></span>
					<span>[<?php echo $order_info['Order']['created'];?>]</span>
				  </div>
			    </div>
				<div class="am-form-group" style="margin-top:1rem;">
			      <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" ><?php echo '审核状态' ?>:</div>
			      <div class="am-u-lg-10 am-u-md-10 am-u-sm-9" >
				    <span>
				    	<?php if($order_info['Order']['check_status'] == '1'){
				    		echo '已审核';
				    	} else{
				    		echo '未审核';
				    	}
				    	?>
				    	
				    </span>
					
				  </div>
			    </div>
			  
				<div class="am-form-group" style="margin-top:1rem;">
			      <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style=""><?php echo $ld['order_status']?> :</div>
				<?php //pr($Resource_info["payment_status"]); ?>
			      <div class="am-u-lg-10 am-u-md-10 am-u-sm-9">
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
					<div class="am-form-group" id="admin_sel" style="margin-top:1rem;">
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['administrator']?>:</div>
						<div class="am-u-lg-10 am-u-md-10 am-u-sm-9">
						<?php if($svshow->operator_privilege("order_advanced")){ ?>
							<select name="order_manager" onchange="order_manager_modify(this)" data-am-selected="{noSelectedText:'<?php echo $ld['please_select'] ?>',maxHeight:150}">
								<option value='0'><?php echo $ld['please_select'] ?></option>
								
								<?php if(isset($order_info['Order']['order_manager'])&&$order_info['Order']['order_manager']!='0'){ ?>
									<?php if(isset($operator_list)&&sizeof($operator_list)>0){foreach($operator_list as $k=>$v){ ?>
									<option value="<?php echo $k; ?>" <?php echo isset($order_info['Order']['order_manager'])&&$order_info['Order']['order_manager']==$k?'selected':''; ?>><?php echo $v; ?></option>
									<?php }} ?>
								<?php }else{ ?>
									<?php if(isset($operator_list)&&sizeof($operator_list)>0){foreach($operator_list as $k=>$v){ ?>
									<option value="<?php echo $k; ?>" <?php foreach ($orders_list_manager as $kk => $vv) {if($vv==$k){echo 'disabled';}} ?>><?php echo $v; ?></option>
									<?php }} ?>
								<?php } ?>
							</select>
							<?php }else{ ?>
							<span><?php echo isset($operator_list[$order_info['Order']['order_manager']])?$operator_list[$order_info['Order']['order_manager']]:''; ?></span>
							<?php } ?>
						</div>
					</div>
            <tr id="order_picking_type_tr" style="display:none">
                 <td colspan="2">
                    <div class="am-form-group" style="display:none;">
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
				<div class="am-form-group" style="display:none;">
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
				<div class="am-form-group" style="display:none;">
			      <div class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label"><?php echo $ld['invoice_number']?></div>
			  	  <div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
					<input type="text" id="order_invoice_no"  value="<?php echo $order_info['Order']['invoice_no'];?>"/>
				  </div>
				</div>
			  </td>
		    </tr>
		   
				<div class="am-form-group" style="margin-top:1rem;">
			      <div id="order_user_label" class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="margin-top:0.4rem;"><?php echo $ld['order_user']?></div>
			      <div class="am-u-lg-10 am-u-md-10 am-u-sm-9" style="max-width:50rem;padding-right:0;">
			  		<?php echo $form->create('',array('action'=>'/',"name"=>"OrdForm",'onsubmit'=>"search_user();return false;"));?>
			  		<span id="user_info">
					  <?php if(isset($order_info["User"]['name'])){
						  	echo $order_info["User"]['name'];
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
			<div id="create_user_info" class="create_user_info" style="margin-top:1rem;">
		
			  	<div class="am-form-group">
			  	  <div class="am-u-lg-1 am-u-md-1 am-u-sm-3" style="margin-top:1rem;padding-right:0;"><?php echo $ld['real_name']; ?></div>
			  	  <div class="am-u-lg-5 am-u-md-5 am-u-sm-9" style="margin-top:1rem;padding-right:0;"><input type="text" id="create_user_name" style="max-width:17rem;" value="" /></div>
					
			  	  <div class="am-u-lg-1 am-u-md-1 am-u-sm-3" style="margin-top:1rem;padding-right:0;"><?php echo $ld['mobile']; ?></div>
			  	  <div class="am-u-lg-5 am-u-md-5 am-u-sm-9" style="margin-top:1rem;padding-right:0;"><input type="text" id="create_user_mobile" style="max-width:17rem;" value="" /></div>
			  	  
			  	</div>
			
			</div>
			<?php if(isset($user_info['User'])){ ?>
			<tr>
			  <td  colspan="4" style="padding:0.7rem 0;">
			  	<!-- 用户头像开始 -->
			    <form id="order_user_avatar_from">
				<div class="user_avatar">
					 <div class="am-form-group am-container">
						<div class="am-g">
							<div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="padding-top:2rem;"><?php echo $ld['avatar']; ?></div>
							<div class="am-u-lg-10 am-u-md-10 am-u-sm-9">
								<ul class="am-avg-sm-3 am-avg-md-6 am-avg-lg-6 am-thumbnails">
									  <li class="am-fl am-u-lg-4 am-u-md-4 am-u-sm-3"><?php
											$user_img01_flag=false;
											if(isset($user_info['User']['img01'])&&$user_info['User']['img01']!=''){
												if(is_file(WWW_ROOT.$user_info['User']['img01'])){
													$user_img01_flag=true;
												}else{
													$ch = curl_init();
													curl_setopt($ch, CURLOPT_URL,$user_info['User']['img01']);
													curl_setopt($ch, CURLOPT_NOBODY, 1); // 不下载
													curl_setopt($ch, CURLOPT_FAILONERROR, 1);
													curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
													if(curl_exec($ch)!==false)$user_img01_flag=true;
												}
											}
											echo $html->image(isset($user_info['User']['img01']) && $user_info['User']['img01']!=''?$user_info['User']['img01']:'/theme/default/img/no_head.png',array('id'=>'avatar_img01_priview','class'=>$user_img01_flag?'':'order_user')); ?>
										<input style="margin:8px 0;" class="order_user" type="file" disabled="disabled" id="avatar_img01" name="avatar_img01" onchange="ajaxFileUpload(<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0; ?>,'avatar_img01')" />
										<input type="hidden" id="avatar_img01_hid" name="data[User][img01]" value="<?php echo isset($user_info['User']['img01'])?$user_info['User']['img01']:''; ?>" />
									  </li>
									  <li class="am-fl am-u-lg-4 am-u-md-4 am-u-sm-3"><?php
											$user_img02_flag=false;
											if(isset($user_info['User']['img02'])&&$user_info['User']['img02']!=''){
												if(is_file(WWW_ROOT.$user_info['User']['img02'])){
													$user_img02_flag=true;
												}else{
													$ch = curl_init();
													curl_setopt($ch, CURLOPT_URL,$user_info['User']['img02']);
													curl_setopt($ch, CURLOPT_NOBODY, 1); // 不下载
													curl_setopt($ch, CURLOPT_FAILONERROR, 1);
													curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
													if(curl_exec($ch)!==false)$user_img02_flag=true;
												}
											}
									  	echo $html->image(isset($user_info['User']['img02']) && $user_info['User']['img02']!=''?$user_info['User']['img02']:'/theme/default/img/no_head.png',array('id'=>'avatar_img02_priview','class'=>$user_img02_flag?'':'order_user')); ?>
									  <input style="margin:8px 0;" class="order_user"  type="file" disabled="disabled" id="avatar_img02" name="avatar_img02" onchange="ajaxFileUpload(<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0; ?>,'avatar_img02')" />
									  <input type="hidden" id="avatar_img02_hid" name="data[User][img02]" value="<?php echo isset($user_info['User']['img02'])?$user_info['User']['img02']:''; ?>" />
									  </li>
									  <li class="am-fl am-u-lg-4 am-u-md-4 am-u-sm-3"><?php
											$user_img03_flag=false;
											if(isset($user_info['User']['img03'])&&$user_info['User']['img03']!=''){
												if(is_file(WWW_ROOT.$user_info['User']['img03'])){
													$user_img03_flag=true;
												}else{
													$ch = curl_init();
													curl_setopt($ch, CURLOPT_URL,$user_info['User']['img03']);
													curl_setopt($ch, CURLOPT_NOBODY, 1); // 不下载
													curl_setopt($ch, CURLOPT_FAILONERROR, 1);
													curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
													if(curl_exec($ch)!==false)$user_img03_flag=true;
												}
											}
											echo $html->image(isset($user_info['User']['img03']) && $user_info['User']['img03']!=''?$user_info['User']['img03']:'/theme/default/img/no_head.png',array('id'=>'avatar_img03_priview','class'=>$user_img03_flag?'':'order_user')); ?>
										<input style="margin:8px 0;" class="order_user"  type="file" disabled="disabled" id="avatar_img03" name="avatar_img03" onchange="ajaxFileUpload(<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0; ?>,'avatar_img03')" />
									  	<input type="hidden" id="avatar_img03_hid" name="data[User][img03]" value="<?php echo isset($user_info['User']['img03'])?$user_info['User']['img03']:''; ?>" />
									  </li>
									  <li class="am-fl am-u-lg-4 am-u-md-4 am-u-sm-3"><?php
											$user_img04_flag=false;
											if(isset($user_info['User']['img04'])&&$user_info['User']['img04']!=''){
												if(is_file(WWW_ROOT.$user_info['User']['img04'])){
													$user_img04_flag=true;
												}else{
													$ch = curl_init();
													curl_setopt($ch, CURLOPT_URL,$user_info['User']['img04']);
													curl_setopt($ch, CURLOPT_NOBODY, 1); // 不下载
													curl_setopt($ch, CURLOPT_FAILONERROR, 1);
													curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
													if(curl_exec($ch)!==false)$user_img04_flag=true;
												}
											}
											echo $html->image(isset($user_info['User']['img04']) && $user_info['User']['img04']!=''?$user_info['User']['img04']:'/theme/default/img/no_head.png',array('id'=>'avatar_img04_priview','class'=>$user_img04_flag?'':'order_user')); ?>
										<input style="margin:8px 0;" class="order_user" type="file" disabled="disabled" id="avatar_img04" name="avatar_img04" onchange="ajaxFileUpload(<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0; ?>,'avatar_img04')" />
										<input type="hidden" id="avatar_img04_hid" name="data[User][img04]" value="<?php echo isset($user_info['User']['img04'])?$user_info['User']['img04']:''; ?>" />
									  </li>
									  <li class="am-fl am-u-lg-4 am-u-md-4 am-u-sm-3" ><?php 
									  		$user_img05_flag=false;
											if(isset($user_info['User']['img05'])&&$user_info['User']['img05']!=''){
												if(is_file(WWW_ROOT.$user_info['User']['img05'])){
													$user_img05_flag=true;
												}else{
													$ch = curl_init();
													curl_setopt($ch, CURLOPT_URL,$user_info['User']['img05']);
													curl_setopt($ch, CURLOPT_NOBODY, 1); // 不下载
													curl_setopt($ch, CURLOPT_FAILONERROR, 1);
													curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
													if(curl_exec($ch)!==false)$user_img05_flag=true;
												}
											}
											echo $html->image(isset($user_info['User']['img05']) && $user_info['User']['img05']!=''?$user_info['User']['img05']:'/theme/default/img/no_head.png',array('id'=>'avatar_img05_priview','class'=>$user_img05_flag?'':'order_user')); ?>
									  <input style="margin:8px 0;" class="order_user"  type="file" disabled="disabled" id="avatar_img05" name="avatar_img05" onchange="ajaxFileUpload(<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0; ?>,'avatar_img05')" />
									  <input type="hidden" id="avatar_img05_hid" name="data[User][img05]" value="<?php echo isset($user_info['User']['img05'])?$user_info['User']['img05']:''; ?>" />
									  </li>
									  <li class="am-fl am-u-lg-4 am-u-md-4 am-u-sm-3 am-u-end"><?php
									  		$user_img06_flag=false;
											if(isset($user_info['User']['img06'])&&$user_info['User']['img06']!=''){
												if(is_file(WWW_ROOT.$user_info['User']['img06'])){
													$user_img06_flag=true;
												}else{
													$ch = curl_init();
													curl_setopt($ch, CURLOPT_URL,$user_info['User']['img06']);
													curl_setopt($ch, CURLOPT_NOBODY, 1); // 不下载
													curl_setopt($ch, CURLOPT_FAILONERROR, 1);
													curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
													if(curl_exec($ch)!==false)$user_img06_flag=true;
												}
											}
									  		echo $html->image(isset($user_info['User']['img06']) && $user_info['User']['img06']!=''?$user_info['User']['img06']:'/theme/default/img/no_head.png',array('id'=>'avatar_img06_priview','class'=>$user_img06_flag?'':'order_user')); ?>
										<input style="margin:8px 0;" class="order_user"  type="file" disabled="disabled" id="avatar_img06" name="avatar_img06" onchange="ajaxFileUpload(<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0; ?>,'avatar_img06')" />
									  	<input type="hidden" id="avatar_img06_hid" name="data[User][img06]" value="<?php echo isset($user_info['User']['img06'])?$user_info['User']['img06']:''; ?>" />
									  </li>
								</ul>
							</div>
						</div>
						<div class="am-g">
							<div class="am-form-group" style="">
									<div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="margin-bottom:0.6rem;"><?php echo $ld['gender']; ?></div>
									<div class="am-u-lg-2 am-u-md-2 am-u-sm-9 am-text-left" style="margin-bottom:0.6rem;">
										<label class="order_user"><input name="data[User][sex]" style="width:1em;" type="radio" value="1" <?php echo isset($user_info['User']['sex'])&&$user_info['User']['sex']=='1'?'checked':''; ?>><?php echo $ld['male']?></label>
										<label class="order_user"><input name="data[User][sex]" style="width:1em;" type="radio" value="2" <?php echo isset($user_info['User']['sex'])&&$user_info['User']['sex']=='2'?'checked':''; ?>><?php echo $ld['female']?></label>
										<label class="order_user_span"><?php echo isset($user_info['User']['sex'])&&$user_info['User']['sex']!='0'||!isset($user_info['User']['sex'])?($user_info['User']['sex']=='1'?$ld['male']:$ld['female']):''; ?></label>
									</div>
									<div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="margin-bottom:0.6rem;"><?php echo $ld['body_height']; ?></div>
									<div class="am-u-lg-2 am-u-md-2 am-u-sm-9 am-text-left" style="margin-bottom:0.6rem;">
										<input class="order_user" style="width:5em;" type="text" name="data[User][height]" value="<?php echo isset($user_info['User']['height'])?$user_info['User']['height']:'';?>" onKeyUp="clearNoNum(event,this)" onBlur="checkNum(this)">
										<label class="order_user_span"><?php echo isset($user_info['User']['height'])?$user_info['User']['height']:'';?></label>
									</div>
									<div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="margin-bottom:0.6rem;"><?php echo $ld['body_weight']; ?></div>
									<div class="am-u-lg-2 am-u-md-2 am-u-sm-9 am-text-left" style="margin-bottom:0.6rem;">
										<input class="order_user" style="width:5em;" type="text" name="data[User][body_weight]" value="<?php echo isset($user_info['User']['body_weight'])?$user_info['User']['body_weight']:'';?>" onKeyUp="clearNoNum(event,this)" onBlur="checkNum(this)">
										<label class="order_user_span"><?php echo isset($user_info['User']['body_weight'])?$user_info['User']['body_weight']:'';?></label>
									</div>
									<div class="am-cf"></div>
							</div>
						</div>
					  </div>
					</div>
				</form>
				<!-- 头像结束 -->
				<!-- 用户量体信息 -->
				<?php if(!empty($default_user_config_list)){ $user_config_count=2; ?>
				<form id="order_user_config_from">
					<div class="am-form-group am-cf">
				   <?php foreach($default_user_config_list as $ck=>$cv){$user_config_count++; ?>
				  <?php if($user_config_count%3==0){ ?><?php } ?>
				  	
				    	<div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="margin-bottom:0.6rem;"><?php echo $cv['name']; ?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-9 am-text-left" style="margin-bottom:0.6rem;">
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
						    <input class="order_user" style="width:5em;" type="text" name="data[UserConfig][body_type][<?php echo $ck; ?>]" value="<?php echo $user_config_value; ?>" onKeyUp="clearNoNum(event,this)" onBlur="checkNum(this)">
					  		<label class="order_user_span" style="margin-bottom:0;"><?php echo $user_config_value; ?></label>
					  		<?php } ?>
					</div>
				
				<?php if($user_config_count%3+1==0){ ?><?php } ?>
				<?php } ?>
				</div>
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
					<input id="order_address_data_save" type="button" class="am-btn am-btn-success am-radius am-btn-sm order_user"  onclick="order_user_save()" value="<?php echo $ld['save'];?>" style="margin-top:1em;margin-left:9.5em;" />
				  </div>
			    </div>
			  </td>
			</tr>
		  </div>
		</div>
	</div>
</div>
<!-- 结束 -->
</div>

	

  <div id="consignee_information" class="am-panel am-panel-default">
    <div class="am-panel-hd">
      <h4 class="am-panel-title">
      	<?php echo $ld['receiving_information'] ?>
      </h4>
    </div>
    
	<!-- 移动端开始 -->
 <div id="consignee_info" class="am-panel-collapse am-collapse am-in am-show-sm-up">
	  <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
	  <div id="order_address_info_table" class="am-g am-form" style="margin-bottom:0;">

			<div class="am-form-group" style="">
		      <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="text-align:left;"><?php echo $ld['shipping']?></div>
		      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-u-end" style="max-width:250px;">
			    <select id="order_shipping_id" onchange="sendinfo('1');">
				<?php if(isset($shipping_effective_list) && sizeof($shipping_effective_list)>0){foreach($shipping_effective_list as $k=>$v){?>
				  <option value="<?php echo $v['Shipping']['id']?>" <?php if($order_info['Order']['shipping_id']==$v['Shipping']['id']){echo "selected";}?> ><?php echo $v['ShippingI18n']['name']?></option>
				<?php }}?>
			    </select>
			  </div>
		    </div>
		
		<div class="order_user_address_edit am-form-group" style="margin-top:1rem;">

		    <div class="am-form-group" id="order_address_info" > 
	          <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="text-align:left;"><?php echo $ld['select_from_delivery_address']?></div>
	          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-u-end" style="max-width:250px;">
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

		</div>
	<!-- 收货人和电话 -->
		    <div class="am-form-group">
	          <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="text-align:left;margin-top:1rem;"><?php echo $ld['consignee']?></div>
	          <div class="am-u-lg-4 am-u-md-4 am-u-sm-9" id="order_h" style="margin-top:1rem;">
	  		    <input type="text" style="max-width:50%;" id="order_consignee" value="<?php echo $order_info['Order']['consignee'];?>"  class="address"/>
			    <label class="address_span am-form-label" id="order_consignee_span" style=""><?php if(!empty($order_info['Order']['consignee'])){echo $order_info['Order']['consignee'];}else{echo "&nbsp;";}?></label>
			    <a onclick="edit_order_address()" href="javascript:void(0);"><?php echo $ld['edit'];?></a>
			  </div>
		          <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="text-align:left;margin-top:1rem;"><?php echo $ld['phone']?></div>
		          <div class="am-u-lg-4 am-u-md-4 am-u-sm-9" style="margin-top:1rem;">
				    <input type="text" style="max-width:50%;" id="order_telephone" value="<?php echo $order_info['Order']['telephone'];?>" class="address" />
				    <label class="address_span am-form-label"  style="margin-top:-1.5rem;padding-top:0.5rem;"><?php echo $order_info['Order']['telephone'];?></label>
			 	  </div>
		        </div>

		<div class="order_user_address_edit am-form-group">
			<!-- 区域 -->
		<div class="am-cf">
	        <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="text-align:left;margin-top:1rem;"><?php echo $ld['region']?>
			  <input type="hidden" id="order_country2" value="<?php echo isset($order_info['Order']['country'])?$order_info['Order']['country']:"";?>">
			  <input type="hidden" id="order_province2" value="<?php echo isset($order_info['Order']['province'])?$order_info['Order']['province']:"";?>">
			  <input type="hidden" id="order_city2" value="<?php echo isset($order_info['Order']['city'])?$order_info['Order']['city']:"";?>">
			</div>
	        <div class="am-u-lg-4 am-u-md-4 am-u-sm-9 am-u-end" style="margin-top:1rem;">
			  <div id="address_select_span" style="margin-top:0px;"  <?php if(!((!isset($order_info['Order']['country'])||$order_info['Order']['country']=="")&&(!isset($order_info['Order']['province'])||$order_info['Order']['province']=="")&&(!isset($order_info['Order']['city'])||$order_info['Order']['city']==""))){?>class="order_status"<?php }?>>
			  <select style="width:25%;" gtbfieldid="1" name="country_select" id="country_select" onchange="getRegions(this.value,'country')">
			  </select>
			  <select style="width:25%;" class="order_status" gtbfieldid="1" name="province_select" id="province_select" onchange="getRegions(this.value,'province')">
			  </select>
			  <select style="width:25%;" class="order_status" gtbfieldid="1"  name="city_select" id="city_select" onchange="getRegions(this.value,'city')">
			  </select>
			  </div>
			  <label class="address_span am-form-label" style="padding-top:0;"><?php echo isset($order_info['Order']['country'])?$order_info['Order']['country']:"";?> - 
				<?php echo isset($order_info['Order']['province'])?$order_info['Order']['province']:"";?> -
				<?php echo isset($order_info['Order']['city'])?$order_info['Order']['city']:"";?>
			  </label> 				
		 </div>
		
			<!-- 手机 -->
		 
	        <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="text-align:left;margin-top:1rem;"><?php echo $ld['mobile']?></div>
	        <div class="am-u-lg-4 am-u-md-4 am-u-sm-9" style="margin-top:1rem;">
			  <input type="text" id="order_mobile" style="max-width:50%;" value="<?php echo $order_info['Order']['mobile'];?>" class="address" />
			  <label class="address_span am-form-label" id="order_mobile_span" style="padding-top:0px;"><?php echo $order_info['Order']['mobile'];?></label>
			</div>
		</div>

		  <!-- 地址和email -->
		<div class="order_user_address_edit am-cf">
			
	          <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="text-align:left;margin-top:1rem;"><?php echo $ld['address']?></div>
	          <div class="am-u-lg-4 am-u-md-4 am-u-sm-9 am-u-end" style="margin-top:1rem;">
			    <input type="text" id="order_address" style="width:80%" value="<?php echo $order_info['Order']['address'];?>" class="address" />
			    <label class="address_span am-form-label" style="padding-top:0;"><?php echo $order_info['Order']['address'];?></label>
			  </div>
		
		
	          <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="text-align:left;margin-top:1rem;"><?php echo $ld['email']?></div>
	          <div class="am-u-lg-4 am-u-md-4 am-u-sm-9 am-u-end" style="margin-top:1rem;">
		  		<input type="text" id="order_email" style="width:50%;" value="<?php echo $order_info['Order']['email'];?>" class="address" />
		  		<label class="address_span am-form-label" style="padding-top:0px;"><?php echo $order_info['Order']['email'];?></label>			
			  </div>
	

		</div>
		</div>
		<!-- 邮编和发货备注 -->
		<div class="order_user_address_edit am-form-group">
			<div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="text-align:left;margin-top:1rem;"><?php echo $ld['zip_code']?></div>
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-9" style="margin-top:1rem;">
				<input type="text" id="order_zipcode" style="max-width:50%;" value="<?php echo $order_info['Order']['zipcode'];?>" class="address" />
				<label class="address_span am-form-label" style="padding-top:0px;"><?php echo $order_info['Order']['zipcode'];?></label>
			</div>
			<div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="text-align:left;margin-top:1rem;"><?php echo $ld['delivery_remark']?></div>
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-9" style="margin-top:1rem;">
				<textarea id="order_note" class="address" style="max-width:80%;"><?php echo $order_info['Order']['note'];?></textarea>
				<label class="address_span am-form-label" style="word-break:break-all;padding-top:0;"><?php echo $order_info['Order']['note'];?></label>
			</div>
		</div>
		<!-- 标志性建筑和顾客留言 -->
		<div class="order_user_address_edit am-cf">
			
			<div class="am-cf">
		      <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="margin-top:1rem;text-align:left;padding-right:0;"><?php echo $ld['address_to']?></div>
		      <div class="am-u-lg-4 am-u-md-4 am-u-sm-9 am-form-group" style="margin-top:1rem;">
			    <input type="text" id="order_sign_building" style="max-width:50%;" value="<?php echo $order_info['Order']['sign_building'];?>" class="address" />
			    <label class="address_span am-form-label" style="padding-top:0px;"><?php echo $order_info['Order']['sign_building'];?></label>
			  </div>
			</div>
		
		      <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="margin-top:1rem;text-align:left;"><?php echo $ld['customer_feedback']?></div>
		      <div class="am-u-lg-4 am-u-md-4 am-u-sm-9 am-form-group" style="margin-top:1rem;">
		  		<textarea id="order_postscript" style="max-width:80%;" class="address"><?php echo $order_info['Order']['postscript'];?></textarea>
		  		<label class="address_span am-form-label" style="word-break:break-all;padding-top:0px;"><?php echo $order_info['Order']['postscript'];?></label>		
			  </div>
	
		</div>	
		<div style="margin-top:1rem;margin-bottom:1rem;" class="am-form-group">
	
			<div class="am-form-group">
		      <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="text-align:left;margin-top:0;"><?php echo $ld['best_delivery_time']?></div>
		      <div class="am-u-lg-10 am-u-md-10 am-u-sm-9" style="margin-top:0;">
		        	<?php $best_time_info=explode(' ',trim($order_info['Order']['best_time'])); ?>
		        	<label class="address_span am-form-label" style="padding-top:0;"><?php echo $order_info['Order']['best_time'];?></label>
		        	<input type='hidden' id="order_best_time" value="<?php echo $order_info['Order']['best_time'];?>" />
		        	<?php if(isset($best_time_info)&&count($best_time_info)>1){ ?>
		        	<input type="text" id="select_best_date" style="max-width:100px;" value="<?php echo isset($best_time_info[0])?$best_time_info[0]:''; ?>" class="address" style="width:45%;" data-am-datepicker="{theme: 'success'}" onblur="order_best_time();" />
				<select id="select_best_time" class="address" style="max-width:150px;" onchange="order_best_time();">
					<option value=""><?php echo $ld['please_select']?>...</option>
					<?php if(isset($information_resources_info["best_time"])){foreach( $information_resources_info["best_time"] as $k=>$v){?>
					<option value="<?php echo $v?>" <?php echo isset($best_time_info[1])&&$best_time_info[1]==$v?'selected':''; ?>><?php echo $v?></option>
					<?php }}?>
				</select>
				<?php }else{ ?>
					<input type="text" id="select_best_date" style="max-width:100px;" value="<?php echo ''; ?>" class="address" style="width:45%;" data-am-datepicker="{theme: 'success'}" onblur="order_best_time();" />
					<select id="select_best_time" class="address" style="max-width:150px;" onchange="order_best_time();">
						<option value=""><?php echo $ld['please_select']?>...</option>
						<?php if(isset($information_resources_info["best_time"])){foreach( $information_resources_info["best_time"] as $k=>$v){?>
						<option value="<?php echo $v?>" <?php echo isset($best_time_info[0])&&$best_time_info[0]==$v?'selected':''; ?>><?php echo $v?></option>
						<?php }}?>
					</select>
				<?php } ?>
			  </div>
		    </div>
	
		</div>
		<div class="order_user_address_edit am-cf am-form-group" style="margin-top:1rem;">

			<div class="am-form-group" >
		      <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="text-align:left;margin-top:0;"><?php echo $ld['stock_handling']?></div>
			  <div class="am-u-lg-10 am-u-md-10 am-u-sm-9" style="margin-top:0;">
				<input type="text" id="order_how_oos" style="max-width:100px;" value="<?php echo $order_info['Order']['how_oos'];?>" class="address" style="width:55%;"/>
				<label class="address_span am-form-label" style="padding-top:0;"><?php echo $order_info['Order']['how_oos'];?></label>
				<select id="select_how_oos" onchange="document.getElementById('order_how_oos').value=this.value" class="address" style="max-width:150px;">
				  <option value=""><?php echo $ld['please_select']?>...</option>
				  <?php foreach( $information_resources_info["how_oos"] as $k=>$v){?>
				  <option value="<?php echo $v;?>"><?php echo $v;?></option>
				  <?php }?>
				</select>
			  </div>
		    </div>
	
		</div>
		<div class="order_user_address_edit address_save">

			<div class="am-form-group">
		     <!--  <div class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-form-label">&nbsp;</div> -->
		      <div class="" style="margin-bottom:1rem;margin-top:1rem;">
				<input id="order_address_data_save" type="button" class="am-btn am-btn-success am-radius am-btn-sm address"  onclick="order_address_data_save()" value="<?php echo $ld['save'];?>" />
			  </div>
		    </div>
	
		</div>
	  </div>
	  </div>
	</div>
	<!-- 移动端结束 -->
  </div>

  <div id="pro_info" class="am-panel am-panel-default">
    <div class="am-panel-hd">
      <h4 class="am-panel-title" ><?php echo $ld['product_information']; ?>
      </h4>
    </div>

    <div class="am-panel-collapse am-collapse am-in">
	  <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
	  	<!-- 商品信息 -->
		  <div id="order_product_div" style="<?php if(empty($order_info['Order']['type']) && sizeof($order_type)>1){echo 'display:none';}?>">
		  	<div class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-cf">
		  	<button id="order_search_btn" class="am-btn am-btn-warning am-radius am-btn-sm " style="float:right;">
  				<span class="am-icon-plus"></span>搜索商品
			</button>
		  	</div>
		  	<div class="am-cf"></div>
	
			<div class="am-modal am-modal-no-btn" tabindex="-1" id="order_search">
			  <div class="am-modal-dialog" style="display:none;">		
			      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
			    </div>
			    <div class="am-modal-bd">
			      <div class="am-g">
			      	<?php if((!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2)))){?>
				    <?php echo $form->create('',array('action'=>'/',"name"=>"OrdForm",'onsubmit'=>"search_order_product();return false;","class"=>"am-g"));?>
				<div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;border:none;">
				 
					  <div class="am-u-lg-5 am-u-md-5 am-u-sm-5" >
						<?php if(isset($product_style_tree)&&sizeof($product_style_tree)>0){ ?>
						        <label  class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-text-left" style="margin-top:8px;padding:0;"><?php echo $ld['product_style'];?>:</label>
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
						<div>
					  <div class="am-u-lg-6 am-u-md-6 am-u-sm-12" style="margin-bottom:1rem;">
						<?php if(isset($product_type_tree)&&sizeof($product_type_tree)>0){ ?>
			  			<label  class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-text-left" style="padding:0;"><?php echo $ld['all_product_type']?>:</label>
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
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-12">
								<?php if(isset($brands)&&!empty($brands)){?>
					  			  <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-text-left" style="padding:0;"><?php echo $ld['brand']?>:</label>
								  <div  class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								  <select id="product_brand" name="product_brand">
								    <option value=""><?php echo $ld['please_select']?></option>
								    <?php foreach($brands as $v){?>
								    <option value="<?php echo $v['Brand']['id']?>"><?php echo $v['Brand']['code'].'-'.$v['BrandI18n']['name'];?></option>
								    <?php }?>
								  </select>
								  </div>
								<?php }?>
					  	  </div>
					  	  <div class="am-cf"></div>
							</div>
				      	     <div class="am-u-lg-6 am-u-md-6 am-u-sm-12" style="margin-top:1rem;">
								<label  class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-text-left" style="padding:0;"><?php echo $ld['product']?>:</label>
									<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
									  <input type="text" id="order_product" onkeydown="if(event.keyCode==13){return false;}"/>
									</div>
						   			 <input style="margin-left:5px; margin-top:10px;margin-left:0;" type="button" id="add_product_button" onclick="search_order_product();"  class="am-btn am-btn-success am-radius am-btn-sm am-fl" value="<?php echo $ld['search'];?>" />
								    <select id="result" onchange="add_order_product(this.value)" class="selecthide am-fl" style="width:20%;margin:0px 5px 0 5px;display:none;"> 									<option value=""><?php echo $ld['please_select']?></option>
								    </select>
						    		<span id="load_div"  class="order_status"></span>
						       </div>
				  </div>
					<?php echo $form->end(); ?>
				<?php }?>
			      </div>
			    </div>
		
				</div>
			<!-- <div id="order_search" style="" class="am-g">
			  
			
		</div> -->

			
				<!-- 一直到这里，下面是商品信息里面的商品图片 -->
				<div class="am-g am-margin-top-sm am-hide-sm-only" style="line-height:30px;font-size:14px;font-weight:600;border-bottom:1px solid #ddd">
				  <div class="am-u-sm-1 am-u-lg-1 am-u-md-1 am-text-left" style="padding-right:0;"><?php echo $ld['product_image']?></div>
				  <div class="am-u-sm-3 am-u-lg-3 am-u-md-3 am-text-left" style="padding-left:3rem;"><?php echo $ld['product_name']?></div>
<!--				  <th style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><?php echo $ld["shop_price"];?><br>(<?php echo $ld["order_list_price"];?>)</th>-->
				  <div class="am-u-sm-2 am-u-lg-2 am-u-md-2 am-text-left" style="padding-left:0;"><?php echo $ld['order_quantity']?></div>
				  <div class="am-u-sm-3 am-u-lg-3 am-u-md-3 am-text-left" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>;padding-left:0em;"><?php echo $ld['price']?></div>
				  <div class="am-u-sm-1 am-u-lg-1 am-u-md-1 am-text-left" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>;padding-left:0.2rem;text-align:left;"><?php echo $ld['subtotal']?></div>
				  <?php //if(!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2))){?>
				  <?php //if($order_info['Order']['type']!='taobao'){}?>
				  <div class="am-u-sm-2 am-u-lg-2 am-u-md-2 am-text-left" style="text-align:left;padding-left:1em;"><?php echo $ld['operate'];?></div>
				  <?php //}?>
				</div>
			  <div class="am-g" id="order_products_detail_innerhtml" style="margin-bottom:1rem;">
			  <?php 	$the_subtotal=0;$sum_quantity=0;
			  	if(isset($order_info['OrderProduct']) && sizeof($order_info['OrderProduct'])>0){
		  			$package_product=array();
		  	  		foreach($order_info['OrderProduct'] as $vv){
		  	  			if(intval($vv['parent_product_id'])>0){
							$package_product[$vv['parent_product_id']]=$vv['parent_product_id'];
						}
		  	  		}
					foreach($order_info['OrderProduct'] as $k=>$v){ $total_attr_price=0;
						if(!isset($package_product[$v['product_id']])&&$order_info['Order']['service_type']!='appointment'){
							$sum_quantity+=$v['product_quntity'];
						}else if(isset($package_product[$v['product_id']])&&$v['parent_product_id']==0){
							$sum_quantity+=$v['product_quntity'];
						}else if($order_info['Order']['service_type']=='appointment'){
							if($v['parent_product_id']==0){
								$sum_quantity+=$v['product_quntity'];
							}
						}
						if ($v['parent_product_id'] == 0) {
								$the_subtotal +=($v['product_price']*($v['product_quntity']-$v['refund_quantity'])+$v['adjust_fee']);
						} ?>
				<div class="am-u-sm-12 am-margin-top-sm" style="border-bottom:1px solid #ddd;">
					
 				  <div class="smail_img am-u-sm-4 am-u-lg-1 am-u-md-1 am-text-left" id="product_1" style="text-align: left;padding: 5px 0 3px;line-height: normal;">
				  <figure data-am-widget="figure" class="am am-figure am-figure-default "
data-am-figure="{  pureview: 'true' }" style="margin-left:0;margin-top:0;">
					<?php echo $html->image($v['img_thumb']!=""?$v['img_thumb']:"/theme/default/images/default.png",array('date-rel'=>$v['img_thumb']!=""?$v['img_thumb']:"/theme/default/images/default.png")); ?>
				  </figure>
				  </div>
				  <!-- 名称 -->
				  <div class="am-u-lg-3 am-u-md-3 am-u-sm-8 am-text-left" id="product_2" style="min-height:130px;">
					<p style="line-height:20px;margin-bottom:0;">
					<?php
						if($v['item_type']==''){
							if(isset($v['sku_product'])&&$v['sku_product']==1){
								echo $svshow->seo_link(array('type'=>'P','id'=>$v['parent_product_id'],'name'=>'','sub_name'=>$v['product_name'],'style'=>'font-weight:bold'));
							}else{
								echo $svshow->seo_link(array('type'=>'P','id'=>$v['product_id'],'name'=>'','sub_name'=>$v['product_name'],'style'=>'font-weight:bold'));
							}
						}else{
							echo $html->link($v['product_name'],'javascript:void(0);',array('style'=>'font-weight:bold'));
						}
					  ?>
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
					<div class='am-cf'>
					  <input type="hidden" name="order_product_id[]" value="<?php echo $v['id'];?>">
					  <input type="hidden" name="order_product_code[]" value="<?php echo $v['product_code'];?>">
					  <span><?php echo $ld['product_code']?>:&ensp;</span><?php echo $v['product_code'];?>
					</div>
					<?php if(isset($order_info['Order']['service_type'])&&$order_info['Order']['service_type']=='appointment'){ ?>
					<div class='am-cf'>
						<input type="hidden" name="order_product_number[]" value="<?php echo $v['product_number'];?>">
						<span><?php echo '商品条码'; ?>:&ensp;</span><?php echo $v['product_number'];?>
					</div>
					<?php if((isset($sub_order_product_list[$v['product_id']])&&$v['parent_product_id']>0)||($order_info['Order']['service_type']=='appointment'&&$v['product_quntity']==1)){ ?>
					<div class='am-cf'>
						<span><?php echo '服务类型'; ?>:&ensp;</span>
						<span>
							<?php
									$order_product_service_type=explode(',',trim($v['service_type']));
						 if(!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2||$v['delivery_status']==5))){ ?>
							<?php if(isset($Resource_info['order_product_service_type'])&&sizeof($Resource_info['order_product_service_type'])>0){
										foreach($Resource_info['order_product_service_type'] as $kk=>$vv){
							?>
							<label class='am-checkbox order_product_service_type'><input type='checkbox' name="order_product_service_type[<?php echo $v['id']; ?>]" value="<?php echo $kk; ?>" onclick="order_product_service_type_modify(this,<?php echo $v['id']; ?>)" <?php echo in_array($kk,$order_product_service_type)?'checked':''; ?> /><?php echo $vv; ?></label>
							<?php }} ?>
							<?php }else{
									if(empty($order_product_service_type)){
										echo $ld['default'];
									}else{
										foreach($order_product_service_type as $vv){
											echo isset($Resource_info['order_product_service_type'][$vv])?$Resource_info['order_product_service_type'][$vv]:'';
										}
									}
							} ?>
						</span>
					</div>
					<?php } ?>
					<?php } ?>
					<?php if(isset($order_info['OrderProductValue'])&& count($order_info['OrderProductValue'])>0){?>
					<div><?php echo $ld['product_attribute'];?>:&ensp;</div>
					  <?php foreach($order_info['OrderProductValue'] as $opk=>$opv){ ?>
						<?php if($opv['order_product_id']==$v['id'] && (int)$opv['attr_price']!=0){?>
						<?php $total_attr_price+=$opv['attr_price'];?>
						<div><?php echo $pro_attr_check[explode(':', $opv['attribute_value'])[0]].':'.explode(':', $opv['attribute_value'])[1];?>:&ensp;<?php echo $opv['attr_price'];?></div>
						<?php }?>
					  <?php }?>
					<?php }?>
					<div>
					  <span><?php echo $ld['product_attribute']?>:&ensp;</span>
					  <?php if(!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2||$v['delivery_status']==5))){ ?>
					<?php if (isset($v['product_attrbute'])&& !empty($v['product_attrbute'])) { ?>

					  <span style="display:block" onclick="javascript:listTable.edit(this, 'orders/order_product_attribute_save', <?php echo $v['id']?>)"><?php echo $v['product_attrbute']; ?></span>

					<?php }else{ ?>
					<span style="display:block" onclick="javascript:listTable.edit(this, 'orders/order_product_attribute_save', <?php echo $v['id']?>)">-</span>
					<?php } ?>					

					  <?php }else{ if(isset($v['sku_product']['ProductAttribute'])){
						foreach($v['sku_product']['ProductAttribute'] as $attr_v){
						  echo $attr_v['ProductAttribute']['product_type_attribute_value']."\n";
						}
					  }else{
						echo isset($v['product_attrbute'])&&!empty($v['product_attrbute'])?str_replace("<br />", "\n", $v['product_attrbute']):'';}
					  }?>
					</div>
				  </div>
				
				  <!-- 数量列 -->
				  <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" id="product_3" style="padding-top:0px;text-align:left;padding-left:0;padding-right:0;">
					<?php
					  	$product_quntity_update=false;
						if(!(($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2||$v['delivery_status']==5))&&!(isset($order_info['Order']['service_type'])&&$order_info['Order']['service_type']=='appointment'&&$v['product_quntity']=='1'&&trim($v['product_number'])!='')&&(isset($order_info['Order']['service_type'])&&$order_info['Order']['service_type']=='appointment'&&!isset($sub_order_product_list[$v['id']]))){
							$product_quntity_update=true;
						}
						if($product_quntity_update){ ?>
					<div class="am-u-sm-3 am-text-right " style="line-height:37px;"><i style="color:#555" class="am-icon-minus <?php if ($v['parent_product_id']!=0) { echo "am-hide" ;} ?>"></i><?php if ($v['parent_product_id']!=0) { echo "&nbsp;" ;} ?></div>
					<div class="am-u-sm-6">
						<input class="<?php if ($v['parent_product_id']!=0) { echo "am-hide" ;} ?>" type="text" size="2" name="order_product_quntity[]" id="order_product_quntity_<?php echo $v['id'];?>" value="<?php echo $v['product_quntity'];?>" onblur="changeNum(this.value,'<?php echo $v['id']?>','<?php echo $v['product_price']*$v['product_quntity'];?>','<?php echo $order_info['Order']['subtotal']?>')" />
					   <div style="line-height:37px;text-align:center" class="<?php if ($v['parent_product_id']==0) { echo "am-hide" ;} ?>" ><?php echo $v['product_quntity'];?></div>
					</div>
					<div class="am-u-sm-3 am-text-left" style="line-height:37px;"><i style="color:#555" class="am-icon-plus <?php if ($v['parent_product_id']!=0) { echo "am-hide" ;} ?>"></i> <?php if ($v['parent_product_id']!=0) { echo "&nbsp;" ;} ?></div>
					<?php }else{
					  echo '<span id="pq_'.$v["id"].'">'.$v['product_quntity'].'</span>&nbsp;';
					  ?><input type="hidden" name="order_product_quntity[]" id="order_product_quntity_<?php echo $v['id'];?>" value="<?php echo $v['product_quntity'];?>" /><?php						          if(isset($refund_warehouse)&&!empty($refund_warehouse)&&$v['product_quntity']>$v['refund_quantity']&&$svshow->operator_privilege('orders_refund')){
  echo $html->link($ld['return'],"javascript:;",array("onclick"=>"document.getElementById('refund_span_".$v['id']."').style.display=''",'escape' => false,'class'=>"cancelbtn"));?>
					<span id='refund_span_<?php echo $v["id"]?>' style='display:none'>
					  <input type='text' value='0' id="refund_input_<?php echo $v['id']?>" style="width:30px">
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

					<div class="am-u-sm-12 am-text-left" style="padding-left:0;">
						<span style="display:inline-block;">库存：</span>
					  <span id="haveQuantity<?php echo $v['id'];?>" <?php if(isset($all_product_quantity_infos[$v['product_code']])&&$v['product_quntity']>$all_product_quantity_infos[$v['product_code']]){?>style="color:red"<?php }?>>
					  <input type="hidden" id="order_product_have_quntity_<?php echo $v['id'];?>" value="<?php echo isset($all_product_quantity_infos[$v['product_code']])?$all_product_quantity_infos[$v['product_code']]:0;?>">
					    <span style="font-size:13px;color:green;">
						  <?php echo isset($all_product_quantity_infos[$v['product_code']])?$all_product_quantity_infos[$v['product_code']]:0?>
						</span>
					  </span>&nbsp;&nbsp;&nbsp;  
					  <span style="font-size:13px">
						<?php //echo isset($stockProductQuantityInfo[$v['product_code']])?$stockProductQuantityInfo[$v['product_code']]:0;?>
					  </span>
					</div>

					<?php if(isset($stockProductInfo)&&isset($stockProductInfo[$v['product_id']])){?>
					  <?php foreach($stockProductInfo[$v['product_id']] as $kk=>$vv){ if($vv['Stock']['quantity']<=0){ continue;}?>
					  <p><?php echo $vv['Stock']['warehouse_name'];?>:<?php echo $vv['Stock']['quantity'];?></p>
					<?php }}?>
					<input type="hidden" name="order_product_quntity_old[]" id="order_product_quntity_<?php echo $v['id'];?>" value="<?php echo $v['product_quntity'];?>"  />
				  </div>
			
				  <!-- 折扣列 -->
				  <div class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-text-left" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none;';}?>padding-left:0;padding-right:0;">
					<?php $discount=sprintf('%01.2f',($v['product_price']+$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity'])));?>
					<div class="discount_number" style="text-align:left;line-height:30px;font-size:16px;padding-left:0em;">
					  <span id="order_product_shop_price_<?php echo $v['id'];?>">
					    <?php echo isset($discount)?$discount:"";?>
					  </span>
					  <span style="color:#ccc;text-decoration: line-through;">
					    <?php echo $v['product_price'];?>
					  </span>
					</div>
					<div class="<?php if ($v['parent_product_id']!=0) { echo 'am-hide'; } ?>">
					<input type="hidden" size="12" name="order_product_price[]"  id="order_product_price_<?php echo $v['id'];?>" value="<?php echo $v['product_price'];?>" />
				  <?php if((!($order_info['Order']['payment_status']==2 && ($order_info['Order']['shipping_status']==1 || $order_info['Order']['shipping_status']==2||$v['delivery_status']==5)) || ($v['refund_quantity']>0 && $v['refund_quantity'] != $v['product_quntity'])) && $admin['type']=='S'){?>
					<input type="text" onblur="changeDiscount(this.value,'<?php echo $v['id']?>','<?php echo $v['product_price']*$v['product_quntity'];?>','<?php echo $order_info['Order']['subtotal']?>')" value="<?php if($total_attr_price==0){echo isset($all_product_infos[$v['product_id'].$v['product_code']])&&$all_product_infos[$v['product_id'].$v['product_code']]>0?@sprintf('%01.2f',($v['product_price']+$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']))/($all_product_infos[$v['product_id'].$v['product_code']])*10):10;}else{echo isset($all_product_infos[$v['product_id'].$v['product_code']])&&$all_product_infos[$v['product_id'].$v['product_code']]>0?@sprintf('%01.2f',($v['product_price']+$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']))/($all_product_infos[$v['product_id'].$v['product_code']]+$total_attr_price)*10):10;}?>" style="width:60px;float:left;" id="order_product_discount_<?php echo $v['id'];?>" />
					<span class="am-fl" style="padding:0.625em 2px;"><?php echo $backend_locale=="chi"?'折':'%'; ?>=</span>
					<input type = "text" name="order_product_discount[]" id="order_product_sumdiscount_<?php echo $v['id'];?>" value="<?php echo @sprintf("%01.2f",$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']));?>" style="width:60px;float:left;"  onblur="changeSumDiscount(this.value,'<?php echo $v['id']?>','<?php echo isset($all_product_infos[$v['product_id'].$v['product_code']])?$all_product_infos[$v['product_id'].$v['product_code']]:'0';?>','<?php echo $v['product_price']*$v['product_quntity'];?>','<?php echo $order_info['Order']['subtotal']?>')" /><br />
				  <?php }else{ $zhekou = isset($all_product_infos[$v['product_id'].$v['product_code']])&&$all_product_infos[$v['product_id'].$v['product_code']]>0?@sprintf('%01.2f',($v['product_price']+$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']))/$all_product_infos[$v['product_id'].$v['product_code']]*10):10;
					if($zhekou!=10){
						echo $zhekou;
						echo $backend_locale=="chi"?'折':'%'." =";
						echo "<br />";
					}?>
					<input type = "hidden" name="order_product_discount[]" id="order_product_sumdiscount_<?php echo $v['id'];?>" value="<?php echo @sprintf("%01.2f",$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']));?>" />
					<?php }?>
					</div>
				  </div>
				  <!-- 小计列 -->
				  <div class="am-u-lg-1 am-u-md-1 am-u-sm-2 am-text-left" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none;';}?>padding-left:1rem;padding-right:0;">
					<span id="order_product_total_<?php echo $v['id'];?>" >
					  <?php echo @sprintf('%01.2f',($v['product_price']*($v['product_quntity']-$v['refund_quantity'])+$v['adjust_fee']));?>
					</span>
					<?php if(isset($total_attr_price) && $total_attr_price>0){?>
					<div style="height:38px;"></div>
					<div><?php echo @sprintf("%01.2f",$total_attr_price);?></div>
					<?php }?>
				  </div>
				  	  
				  <?php 
				  	  if(!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2||$v['delivery_status']==5))){?>
				  <!-- 操作列 -->
				  <div class="am-action am-u-lg-2 am-u-md-2 am-u-sm-2 <?php if ($v['parent_product_id']!=0) { echo 'am-hide' ;} ?>" style="white-space: nowrap;text-align:left;padding-left:1.8em;" >
					<?php if($v['product_type_id']!=""&&in_array($v['product_type_id'],$customize_product_type_list)){ ?>
					<a class="am-btn am-btn-default am-radius am-btn-xs" href="javascript:void(0);" data-am-modal="{target: '#update_pro_attr'}" onclick="update_pro_attr('<?php echo $v['product_code'];?>',<?php echo $v['product_id'];?>,<?php echo $v['id'];?>)"><?php echo $ld['attribute_edit'] ?></a><br> <?php  //echo $html->link($ld['view_amendments'],'/orders/print_attr_value/'.$order_info['Order']['id'].'/'.$v['id'].'/'.$v['product_type_id'].'/'.$v['product_style_id'],array('target'=>'_blank','class'=>'am-btn am-btn-default am-btn-xs')); ?><?php } ?>
					<?php if(isset($order_info['Order']['service_type'])&&$order_info['Order']['service_type']=='appointment'&&!(isset($sub_order_product_list[$v['id']])&&$sub_order_product_list[$v['id']]>=$v['product_quntity'])&&$v['product_number']==''){ ?>
					<a href="javascript:void(0);" class="am-btn am-btn-default am-text-warning am-radius am-btn-xs" onclick="modify_product_number(<?php echo $v['id'];?>,'<?php echo $v['product_number'] ?>')"><?php echo '设置商品条码'; ?></a><br />
					<?php } ?>
					<?php if(isset($order_info['Order']['service_type'])&&$order_info['Order']['service_type']=='appointment'&&$v['product_number']!=''){ ?>
						<span class="order_product_delivery_status"><?php echo isset($Resource_info['order_product_status'][$v['delivery_status']])?$Resource_info['order_product_status'][$v['delivery_status']]:''; ?>
						<?php
							if($v['delivery_status']==0){
								echo "<br />";
								echo $html->link('已取货','javascript:void(0);',array('onclick'=>"order_product_status_modify(this,".$v['id'].",'1')",'class'=>'am-btn am-btn-default am-text-success am-radius am-btn-xs'));
							}else if($v['delivery_status']==1){
								echo "<br />";
								echo $html->link('已检查','javascript:void(0);',array('onclick'=>"order_product_status_modify(this,".$v['id'].",'2')",'class'=>'am-btn am-btn-default am-text-success am-radius am-btn-xs'));
							}else if($v['delivery_status']==2){
								echo "<br />";
								echo $html->link('已修改','javascript:void(0);',array('onclick'=>"order_product_status_modify(this,".$v['id'].",'3')",'class'=>'am-btn am-btn-default am-text-success am-radius am-btn-xs'))."<br />";
								echo $html->link('取消','javascript:void(0);',array('class'=>'am-btn am-btn-default am-text-warning am-radius am-btn-xs'));
							}else if($v['delivery_status']==3){
								echo "<br />";
								echo $html->link('已质检','javascript:void(0);',array('onclick'=>"order_product_status_modify(this,".$v['id'].",'4')",'class'=>'am-btn am-btn-default am-text-success am-radius am-btn-xs'));
							}
						?></span><br />
						<a class="am-btn am-btn-default am-text-success am-radius am-btn-xs" href="javascript:void(0);" onclick="ajax_order_product_modify(<?php echo $v['id'];?>)"><?php echo '附加信息';?></a><br >
					<?php } ?>
					<?php if(!$order_info['Order']['payment_status']==2){ ?>
					<a class="am-btn am-btn-default am-text-danger am-radius am-btn-xs" href="javascript:void(0);" onclick="delete_order_product(<?php echo $v['id'];?>,'<?php echo $v['product_code'];?>')"><?php echo $ld['delete']?></a>
					<?php } ?>
				  </div>
				  <div class="am-action am-u-sm-1 am-u-end <?php if ($v['parent_product_id']==0) { echo 'am-hide' ;} ?>" style="white-space: nowrap;" >
				  	<?php if(isset($order_info['Order']['service_type'])&&$order_info['Order']['service_type']=='appointment'&&!(isset($sub_order_product_list[$v['id']])&&$sub_order_product_list[$v['id']]>=$v['product_quntity'])){ ?>
					<span class="order_product_delivery_status"><?php echo isset($Resource_info['order_product_status'][$v['delivery_status']])?$Resource_info['order_product_status'][$v['delivery_status']]:''; ?>
					<?php
						if($v['delivery_status']==0){
							echo "<br />";
							echo $html->link('已取货','javascript:void(0);',array('onclick'=>"order_product_status_modify(this,".$v['id'].",'1')",'class'=>'am-btn am-btn-default am-text-success am-radius am-btn-xs'));
						}else if($v['delivery_status']==1){
							echo "<br />";
							echo $html->link('已检查','javascript:void(0);',array('onclick'=>"order_product_status_modify(this,".$v['id'].",'2')",'class'=>'am-btn am-btn-default am-text-success am-radius am-btn-xs'));
						}else if($v['delivery_status']==2){
							echo "<br />";
							echo $html->link('已修改','javascript:void(0);',array('onclick'=>"order_product_status_modify(this,".$v['id'].",'3')",'class'=>'am-btn am-btn-default am-text-success am-radius am-btn-xs'))."<br />";
							echo $html->link('取消','javascript:void(0);',array('class'=>'am-btn am-btn-default am-text-warning am-radius am-btn-xs'));
						}else if($v['delivery_status']==3){
							echo "<br />";
							echo $html->link('已质检','javascript:void(0);',array('onclick'=>"order_product_status_modify(this,".$v['id'].",'4')",'class'=>'am-btn am-btn-default am-text-success am-radius am-btn-xs'));
						}
					?></span><br />
					<a class="am-btn am-btn-default am-text-success am-radius am-btn-xs" href="javascript:void(0);" onclick="ajax_order_product_modify(<?php echo $v['id'];?>)"><?php echo '附加信息';?></a><br >
					<?php } ?>
					<?php if(!$order_info['Order']['payment_status']==2){ ?>
					<a class="am-btn am-btn-default am-text-danger am-radius am-btn-xs" href="javascript:void(0);" onclick="delete_order_product(<?php echo $v['id'];?>,'<?php echo $v['product_code'];?>')"><?php echo $ld['delete']?></a>
					<?php } ?>
				</div>
				  <?php }else if($v['product_type_id']!=""&&in_array($v['product_type_id'],$customize_product_type_list)){ ?>
				  	<div class="am-action" ><a class="am-btn am-btn-success am-radius am-btn-sm" href="javascript:void(0);" data-am-modal="{target: '#update_pro_attr'}" onclick="view_product_attr_value(<?php echo $v['product_id'];?>,'<?php echo $v['product_code'];?>',<?php echo $v['id'];?>)"><?php echo $ld['attribute_edit'] ?></a><br> <?php  //echo $html->link($ld['view_amendments'],'/orders/print_attr_value/'.$order_info['Order']['id'].'/'.$v['id'].'/'.$v['product_type_id'].'/'.$v['product_style_id'],array('target'=>'_blank','class'=>'am-btn am-btn-success am-btn-xs')); ?>
				  	</div>
				  	<?php }else{ echo "&nbsp;"; } ?>
				</div>
				<?php }}?>
				<!-- 商品信息价格 -->
				<div class="am-u-sm-12 am-margin-top-sm">
				  <td></td>
				  <?php if(isset($dealers_info)&&$dealers_info['Dealer']['min_num']!=''){?>
				  <td><strong>最小起订数:</strong><span id="min_num"><?php echo $dealers_info['Dealer']['min_num'];?></span></td>
				  <?php	}else{?>
				  <td></td>
				  <?php }?>
				  <div class="am-u-sm-7 am-text-right" style="padding-right:30px;"><strong><?php echo $ld['number_total']?>:</strong><span id="sum_quantity"><?php echo $sum_quantity; ?></span></div>

				  <div class="am-u-sm-2 am-text-right" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>;padding-left:0;"><strong><?php echo $ld['order_total']?></strong></div>
				  <div class="am-u-sm-3" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>;padding-left:0;padding-right:0;">
					<input type="hidden" id="order_subtotal" name="order_subtotal" value="<?php echo $order_info['Order']['subtotal']?>">
					<span id="last_order_subtotal"><?php echo sprintf('%01.2f',$the_subtotal);?></span>
				  </div>
				</div>
				<!-- 商品价格end -->
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
			  </div>
		  </div>
	  </div>
	</div>
  </div>
  <!-- 费用开始 -->
  <div id="cost" class="am-panel am-panel-default">
    <div class="am-panel-hd">
      <h4 class="am-panel-title" >
      	<?php echo $ld['expenses'] ?>
      </h4>
    </div>
    <div class="am-panel-collapse am-collapse am-in" style="overflow:hidden;">
	  <div class="am-panel-bd am-form-detail am-form am-form-horizontal am-g" style="padding-bottom:0;position:relative;overflow:hidden;">
		<div id="OrderProductTable" style="overflow:hidden;" class="am-g" <?php if(!isset($order_info['OrderProduct'])||empty($order_info['OrderProduct'])){?>style="display:none"<?php }?>>
			<!-- 支付方式 -->
			<div class="am-u-lg-6 am-u-sm-12 am-u-md-6" style="padding-left:0;padding-right:0;">
			<tr id="pay_way" style="width:60%;">
				<div class="am-u-lg-4 am-u-sm-3 am-u-md-4">
			<th style="font-weight:normal;"><?php echo $ld['paymengts']?></th>
		</div>
		<div class="am-u-lg-8 am-u-sm-9 am-u-md-8">
			<td style="text-align:left;"><?php $sub_paymentlist=array(); ?>
			  <select id="order_payment_id" onchange="add_sub_pay(this.id);order_total_change('order_payment_id');" style="margin-bottom:10px;width:28%;min-width:90px;display:inline-block;">
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
			 	 <select id="sub_pay" onchange="order_total_change('sub_pay');" <?php if(empty($order_info['Order']['sub_pay'])){?>style="width: auto;display: none;margin-bottom:10px;width:28%;min-width:90px;display:inline-block;" <?php }?> >
					<option value=""><?php echo $ld["please_select"];?></option>
					<?php foreach($sub_paymentlist as $v){?>
					<option value=<?php echo $v['Payment']['id'];?> <?php if(trim($order_info['Order']['sub_pay'])==trim($v['Payment']['id'])){?>selected<?php }?>><?php echo $v['PaymentI18n']['name'];?></option>
					<?php } ?>
			  	</select>
			</td>
			</div>
			<!-- 修改的地方 -->
			
			  	</tr>

		<tr>
			<div class="am-u-lg-4 am-u-sm-3 am-u-md-4" style="margin-top:1em;margin-bottom:1rem;">
			<th rowspan="7" style="font-weight:normal;"><?php echo $ld['message_to_customer']?></th>
		</div>
		<div class="am-u-lg-8 am-u-sm-9 am-u-md-8" style="margin-top:1em;margin-bottom:1rem;">
			<td rowspan="7" id="buyer_td">
			<?php if(!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2))){?>
			  <textarea id="order_to_buyer" style="height: 160px;width:200px;" onblur="order_total_change(this.id)"><?php echo $order_info['Order']['to_buyer']?></textarea>
			<?php }else{?>
			  <textarea id="order_to_buyer" style="display:none" ><?php echo $order_info['Order']['to_buyer']?></textarea>
			  <?php echo $order_info['Order']['to_buyer'];}?>
			</td>
			</div>			
		  </tr>
		</div>
		  <!-- 下面是要修改的 -->
		<div class="am-u-lg-6 am-u-sm-12 am-u-md-6" style="padding-left:0;padding-right:0;">
		  <div class="am-cf">
		  	<div class="am-u-lg-3 am-u-sm-4 am-u-md-3">	  	
		  	<th style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none;';}?>"><?php echo $ld['shipping_fee']?></th>
			</div>
			<div class="am-u-lg-3 am-u-sm-8 am-u-md-3">
			<td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none;';}?>;margin-left:20px;">
			  <span id="order_shipping_fee_span" <?php if(!($order_info['Order']['payment_status']==2 && ($order_info['Order']['shipping_status']==1 || $order_info['Order']['shipping_status']==2))){?> onclick="order_total_check('order_shipping_fee')" <?php }?>> 
				<?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['shipping_fee']));?>
			  </span>
			  <input class="order_total_input" type="text" id="order_shipping_fee" value="<?php echo $order_info['Order']['shipping_fee'];?>" onblur="order_total_change(this.id)"/>
			</td>
		</div>
		<div class="am-u-lg-3 am-u-sm-4 am-u-md-3">
			<th style="width:100px;<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none;';}?>"><?php echo $ld['order_total_amount']?></th>
			</div>
			<div class="am-u-lg-3 am-u-sm-8 am-u-md-3">
			<td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none;';}?>"><span id="order_total"><?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['total']));?></span></td>
			</div>
		  </div>
		  <div class="am-cf" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>;" id="">

			<div class="am-u-lg-3 am-u-sm-4 am-u-md-3"><th><?php echo $ld['insured_costs']?></th></div>
			<div class="am-u-lg-3 am-u-sm-8 am-u-md-3"><td><span id="order_insure_fee_span" <?php if(!($order_info['Order']['payment_status']==2 && ($order_info['Order']['shipping_status']==1 || $order_info['Order']['shipping_status']==2))){?> onclick="order_total_check('order_insure_fee')" <?php }?>>
			  <?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['insure_fee']));?>
			  </span>
			  <input class="order_total_input" type="text" id="order_insure_fee" value="<?php echo $order_info['Order']['insure_fee'];?>" onblur="order_total_change(this.id)"/>
			</td></div>
			<div class="am-u-lg-3 am-u-sm-4 am-u-md-3"><th><?php echo $ld['amount_paid']?></th></div>
			<div class="am-u-lg-3 am-u-sm-8 am-u-md-3"><td>
			  <span id="order_money_paid_span" <?php if((!($order_info['Order']['payment_status']==2 && ($order_info['Order']['shipping_status']==1 || $order_info['Order']['shipping_status']==2))) && $order_info['Order']['type_id']!='taobao'){?> onclick="order_total_check('order_money_paid')" <?php }?> >
				<?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['money_paid']));?>
			  </span>
			  <input class="order_total_input" type="text" id="order_money_paid" name="order_money_paid" value="<?php echo sprintf("%01.2f",$order_info['Order']['money_paid']);?>"  onblur="order_total_change(this.id)">
			</td></div>
		  </div>
		  <div class="am-cf" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>">
			<div class="am-u-lg-3 am-u-sm-4 am-u-md-3"><th><?php echo $ld['order_payment_fee']?></th></div>
			<div class="am-u-lg-3 am-u-sm-8 am-u-md-3"><td>
			  <span id="order_payment_fee_span" <?php if(!($order_info['Order']['payment_status']==2 && ($order_info['Order']['shipping_status']==1 || $order_info['Order']['shipping_status']==2))){?> onclick="order_total_check('order_payment_fee')" <?php }?>>
				<?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['payment_fee']));?>
			  </span>
			  <input class="order_total_input" type="text" id="order_payment_fee" value="<?php echo $order_info['Order']['payment_fee'];?>" onblur="order_total_change(this.id)"/>
			</td></div>
			<div class="am-u-lg-3 am-u-sm-4 am-u-md-3"><th><?php echo $ld['discount_amount']?></th></div>
			<div class="am-u-lg-3 am-u-sm-8 am-u-md-3"><td colspan="1">
			  <span id="order_discount_span" <?php if((!($order_info['Order']['payment_status']==2 && ($order_info['Order']['shipping_status']==1 || $order_info['Order']['shipping_status']==2))) && $order_info['Order']['type_id']!='taobao'){?> onclick="order_total_check('order_discount')" <?php }?>>
				<?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['discount']));?>
			  </span>
			  <input class="order_total_input" type="text" id="order_discount" value="<?php echo $order_info['Order']['discount']?>" onblur="order_total_change(this.id)"/>
			</td></div>
		  </div>
		  <div class="am-cf">
			<div class="am-u-lg-3 am-u-sm-4 am-u-md-3"><th style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><?php echo $ld['invoice_tax']?></th></div>
			<div class="am-u-lg-3 am-u-sm-8 am-u-md-3"><td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>">
			  <span id="order_tax_span" <?php if(!($order_info['Order']['payment_status']==2 && ($order_info['Order']['shipping_status']==1 || $order_info['Order']['shipping_status']==2))){?> onclick="order_total_check('order_tax')" <?php }?>>
				<?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['tax']));?>
			  </span>
			  <input class="order_total_input" type="text" id="order_tax" value="<?php echo $order_info['Order']['tax']?>" onblur="order_total_change(this.id)"/>
			</td></div>
			<?php if(!empty($SVConfigs['rank']) && $SVConfigs['rank']>0){?>
			<div class="am-u-lg-3 am-u-sm-4 am-u-md-3"><th><?php echo $ld['use_points']?></th></div>
			<div class="am-u-lg-3 am-u-sm-8 am-u-md-3"><td><?php echo $order_info['Order']['point_use'];?></td></div>
			<?php }else{?>
			<td colspan="2"></td>
			<?php } ?>
		  </div>
		  <div class="am-cf" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>">
			<div class="am-u-lg-3 am-u-sm-4 am-u-md-3"><th><?php echo $ld['use_balance']?></th></div>
			<div class="am-u-lg-3 am-u-sm-8 am-u-md-3"><td>
			  <?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['user_balance']));//printf($configs['price_format'],sprintf("%01.2f",isset($order_user_balance_log_info['UserBalanceLog']['amount'])?$order_user_balance_log_info['UserBalanceLog']['amount']:'0.00'));?>
			</td></div>
			<?php if(!empty($SVConfigs['rank']) && $SVConfigs['rank']>0){?>
			<div class="am-u-lg-3 am-u-sm-4 am-u-md-3"><th><?php echo $ld['points_exchange']?></th></div>
			<div class="am-u-lg-3 am-u-sm-8 am-u-md-3"><td><?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['point_fee']));?></td></div>
			<?php }?>
		  </div>
		  <div class="am-cf" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>">
			<div class="am-u-lg-3 am-u-sm-4 am-u-md-3"><th><?php echo $ld['points_exchange']; ?></th></div>
			<div class="am-u-lg-3 am-u-sm-8 am-u-md-3"><td><?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['point_fee']))?></td></div>
			<div class="am-u-lg-3 am-u-sm-4 am-u-md-3"><th><?php echo $ld['use_coupons']?>
			<?php if(isset($order_info['Order']['coupon_id']) && $order_info['Order']['coupon_id']!=""){
					foreach($coupon_name_arr as $ca){
						if($ca == ""){
							continue;
						}
						echo  '['.$ca.']';
					}}?>
			</th></div>
			<div class="am-u-lg-3 am-u-sm-8 am-u-md-3"><td><?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['coupon_fee']))?></td></div>
		  </div>
		  <tr>

			<div class="am-u-lg-3 am-u-sm-4 am-u-md-3"><th style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><?php echo $ld['amount_payable']?></th></div>
			<div class="am-u-lg-3 am-u-sm-8 am-u-md-3 am-u-end"><td style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>">
			  <span id="need_pay">
			  <?php printf($configs['price_format'],sprintf("%01.2f",$order_info['Order']['total']-$order_info["Order"]["coupon_fee"]-$order_info["Order"]["point_fee"]-$order_info['Order']['money_paid']-$order_info['Order']['discount']));?>
			  </span>
			</td></div>
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
	</div>
		</div>

	  </div>
	</div>
  </div>
  <!-- 费用结束 -->
  <!-- 发票信息 -->
  <div id="invoice" class="am-panel am-panel-default">
    <div class="am-panel-hd">
      <h4 class="am-panel-title" >
      	<?php echo $ld['invoice_information'] ?>
      </h4>
    </div>
    <div class="am-panel-collapse am-collapse am-in">
	  <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
	  <div class="am-g">
	  	<div class="am-cf">
		<div class="am-u-lg-6 am-u-md-6 am-u-sm-12">
		  <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="margin-top:1rem;text-align:left;padding-left:0;padding-right:0;"><label class="am-form-label" style="padding-top:0;margin-left:0;"><?php echo $ld['invoice_type']?></label></div>
		  <div class="am-u-lg-8 am-u-md-8 am-u-sm-9" style="margin-top:1rem;">
			<select id="order_invoice_type">
			  <option value=''><?php echo $ld['please_select'];?></option>
			  <?php if(isset($invoice_type_list) && sizeof($invoice_type_list)>0){foreach( $invoice_type_list as $k=>$v ){?>
			  <option value='<?php echo $v["InvoiceType"]["id"];?>' <?php if($order_info['Order']['invoice_type']==$v["InvoiceType"]["id"]){echo "selected";}?>>
				<?php echo $v["InvoiceTypeI18n"]["name"];?>
			  </option>
			  <?php }}?>
			</select>
		  </div>
		</div>
		  <div class="am-cf am-u-lg-6 am-u-md-6 am-u-sm-12" id="invoice_h" style="">
			  <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="margin-top:1rem;padding-left:0;padding-right:0;"><label class="am-form-label" style="padding-top:0;margin-left:0;"><?php echo $ld['invoice_title']?></label></div>
			  <div class="am-u-lg-8 am-u-md-8 am-u-sm-9" style="margin-top:1rem;"><input type="text" id="order_invoice_payee" value="<?php echo $order_info['Order']['invoice_payee'];?>"/></div>
		  </div>
		  </div>
		<div class="am-cf am-u-lg-6 am-u-md-6 am-u-sm-12">
		  <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="margin-top:1rem;padding-left:0;padding-right:0;"><label class="am-form-label" style="padding-top:0;margin-left:0;"><?php echo $ld['invoice_content']?></label></div>
		  <div class="am-u-lg-8 am-u-md-8 am-u-sm-9" style="margin-top:1rem;"><textarea id="order_invoice_content"><?php echo $order_info['Order']['invoice_content'];?></textarea></div>
		</div>
	  </div>
	  <!-- 确定和重置按钮 -->
	  <div class="btnouter" style="margin-top:1rem;">
		  <input type="hidden" id="order_id" value="<?php echo $order_info['Order']['id']?>" />
		  <input type="hidden" name="lease_type" id="lease_type" value="P" />
		  <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" value="<?php echo $ld['d_submit'];?>" onclick="order_data_save();" />
		  <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" onclick="order_reflash();" />
		</div>
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
    
	<!-- 移动端开始 -->
	 <div id="other_information" class="am-panel-collapse am-collapse">
	  <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
	  <div class="am-g">
		<div class="am-cf" style="width:100%;">
			<div class="am-form-group am-u-lg-6 am-u-md-6 am-u-sm-12" style="padding-left:0;">
		      <label id="order_reffer_label" class="am-u-lg-2 am-u-md-2 am-u-sm-8" style="font-weight:400;padding-right:0;"><?php echo $ld['order_reffer']?></label>
		      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				<label class="" style="font-weight:400;">
					<?php echo isset($ld[$order_info['Order']['type']])?$ld[$order_info['Order']['type']]:"";
                    if(isset($order_info['Order']['type_id'])&&$order_info['Order']['type_id']=="front"){echo "-".$ld['frontend'];}else{echo "-".$ld[$order_info['Order']['type_id']];}?>
				</label>

			  </div>
		    </div>
		</div>
			<div class="am-form-group am-u-lg-6 am-u-md-6 am-u-sm-12" style="padding-left:0;">
			  <label class="am-u-lg-2 am-u-md-2 am-u-sm-4" style="font-weight:400;padding-right:0;">	
				<?php echo $ld["order_web_site_sources"];?>
			  </label>
			  <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 referer" ><?php echo $order_info['Order']['referer'];?></div>
			</div>
		
			<div class="am-form-group am-u-lg-6 am-u-md-6 am-u-sm-12" style="padding-left:0;">
			  <label class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="font-weight:400;padding-right:0;">	
				<?php echo $ld["order_currency"];?>
			  </label>
			  <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" ><label class="am-form-label"><?php echo $order_info['Order']['order_currency'];?></label></div>
			</div>
		
		
			<div class="am-form-group am-u-lg-6 am-u-md-6 am-u-sm-12" style="padding-left:0;">
			  <label class="am-u-lg-2 am-u-md-2 am-u-sm-4" style="font-weight:400;padding-right:0;">
				<?php echo $ld["order_language"];?>
			  </label>
			  <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="">
				<label class="" style="font-weight:400;padding-right:0;">
				<?php if(isset($lname)&&$lname!="" && isset($order_info['Order']['order_locale'])) echo isset($lname[$order_info['Order']['order_locale']])?$lname[$order_info['Order']['order_locale']]:"";?>
				</label>
			  </div>
			</div>
		  
			<div class="am-form-group am-u-lg-6 am-u-md-6 am-u-sm-12" style="padding-left:0;">
			  <label class="am-u-lg-2 am-u-md-2 am-u-sm-4" style="font-weight:400;padding-right:0;">	
				<?php echo $ld["domain_from"];?>
			  </label>
			  <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" >
				<label class="">
				<?php echo $order_info['Order']['order_domain']?>
				</label>
			  </div>
			</div>
		
	  </div>
	  </div>
	  <div class="btnouter" style="margin-top:0.5rem;">		
		  <input type="hidden" id="order_id" value="<?php echo $order_info['Order']['id']?>" />
		  <input type="hidden" name="lease_type" id="lease_type" value="P" />
		  <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" value="<?php echo $ld['d_submit'];?>" onclick="order_data_save();" />
		  <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" onclick="order_reflash();" />	
		</div>
	</div>
	<!-- 移动端结束 -->
  </div>
  <div class="am-panel am-panel-default">
	<div class="am-panel-hd">
		<h4 id="operation_title" class="am-panel-title" data-am-collapse="{target: '#logistics_information'}"><?php echo $ld['logistics_information'] ?></h4>
	</div>
	<div id="logistics_information" class="am-panel-collapse am-collapse">
		
		<!-- 移动端 -->
		<div class='am-g'>
				<div class="am-cf" style="border-bottom:2px solid #ddd;line-height:1.6;padding-top:0.5rem;padding-bottom:0.5rem;">
					<div class="thdate am-u-lg-2 am-u-md-3 am-u-sm-3 am-hide-sm-only" style="padding-left:2.9rem;width:20%;font-weight:700;text-align:left;"><?php echo $ld['operation_time']?></div>
					<div class="thdate am-u-lg-3 am-u-md-3 am-u-sm-3 am-show-sm-only" style="padding-left:2.3rem;width:32%;font-weight:700;text-align:left;padding-right:0;"><?php echo $ld['operation_time']?></div>
					
					<div class="thname am-u-lg-2 am-u-md-3 am-u-sm-3 am-hide-sm-only" style="font-weight:700;text-align:left;"><?php echo $ld['order_logistics_company']?><br><?php echo $ld['tracking_number']?></div>

					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-show-sm-only" style="font-weight:700;text-align:left;"><?php echo $ld['order_logistics_company']?><br><?php echo $ld['tracking_number']?></div>
					<div class="thdate am-u-lg-2 am-u-md-3 am-u-sm-3 am-hide-sm-only" style="padding-left:2.9rem;width:20%;font-weight:700;text-align:left;"><?php echo '收货人'?></div>
					<div class="am-text-left am-u-lg-2 am-u-md-2 am-hide-sm-only" style="font-weight:700;text-align:left;"><?php echo $ld['address']?></div>
					<div class="am-text-left am-u-lg-2 am-u-md-2 am-u-sm-2 am-u-end" style="font-weight:700;text-align:left;"><?php echo $ld['product']?></div>
					<div class="am-text-left am-u-lg-1 am-u-md-1 am-hide-sm-only" style="font-weight:700;text-align:left;padding-left:0;"><?php echo $ld['status']?></div>
				</div>
			
				<?php //pr($order_shipment_info); ?>
				<?php if(isset($order_shipment_info)&&sizeof($order_shipment_info)>0){foreach($order_shipment_info as $v){ //pr($v); ?>
				<div class="am-cf" style="border-bottom:1px solid #ddd;line-height:1.6;padding-top:0.5rem;padding-bottom:0.5rem;">
					<div style="padding-left:2.9rem;width:20%;text-align:left;" class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-hide-sm-only"><?php echo $v['OrderShipment']['created']; ?></div>
					<div style="padding-left:2.3rem;width:32%;text-align:left;padding-right:0;" class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-show-sm-only"><?php echo $v['OrderShipment']['created']; ?></div>
					
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-hide-sm-only" style="text-align:left;"><?php echo $v['OrderShipment']['logistics_company_id']==0?$ld['order_logistics']:$v['LogisticsCompany']['name']; ?><br><?php echo $v['OrderShipment']['invoice_no'] ?></div>
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-show-sm-only">
						<?php echo $v['OrderShipment']['logistics_company_id']==0?$ld['order_logistics']:$v['LogisticsCompany']['name']; ?><br>
						<?php echo $v['OrderShipment']['invoice_no'] ?>
					</div>
					<div class="thdate am-u-lg-2 am-u-md-3 am-u-sm-3 am-hide-sm-only" style="padding-left:2.9rem;width:20%;text-align:left;"><?php echo $v['OrderShipment']['consignee'].'<br>'?><?php echo isset($v['OrderShipment']['telephone'])&&$v['OrderShipment']['telephone'] != ''?$v['OrderShipment']['telephone'].'<br>':''; ?><?php echo $v['OrderShipment']['mobile'] ?></div>
					<div class="am-u-lg-2 am-u-md-2 am-hide-sm-only" style="text-align:left;">
						<?php //pr($v['OrderShipment']['city']); ?>  
						<?php echo isset($regions_list[$v['OrderShipment']['country']])&&$regions_list[$v['OrderShipment']['country']]!=''?$regions_list[$v['OrderShipment']['country']].' ':''; ?>
						<?php echo isset($regions_list[$v['OrderShipment']['province']])&&$regions_list[$v['OrderShipment']['province']]!=''?$regions_list[$v['OrderShipment']['province']].' ':''; ?>
						<?php echo isset($regions_list[$v['OrderShipment']['city']])&&$regions_list[$v['OrderShipment']['city']]!=''?$regions_list[$v['OrderShipment']['city']]:''; ?><br>
						<?php echo isset($v['OrderShipment']['address'])&&$v['OrderShipment']['address']!=''?$v['OrderShipment']['address']:' '; ?>
					</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-5" style="text-align:left;"><?php $order_shipment_product_list=isset($order_shipment_product_data[$v['OrderShipment']['id']])?$order_shipment_product_data[$v['OrderShipment']['id']]:array();
						foreach($order_shipment_product_list as $vv)echo "<div>".$vv['OrderProduct']['product_code'].($vv['OrderProduct']['product_number']!=''?"(".$vv['OrderProduct']['product_number'].")*":'*').$vv['OrderShipmentProduct']['product_quantity']."</div>";
						 ?>&nbsp;
					</div>
					<div class="am-u-lg-1 am-hide-sm-only">
					<?php  ?>
						<?php if(isset($v['OrderShipment']['status'])&&$v['OrderShipment']['status'] == 1){ ?>
						<button class="am-btn am-btn-default am-btn-sm" onclick="ajax_logistic_remove(<?php echo $v['OrderShipment']['order_id'] ?>,<?php echo $v['OrderShipment']['id'] ?>)">取消</button>
						<?php }else{ ?>
						<span>已取消</span>
						<?php } ?>
					</div>
				</div>
				<?php }} ?>
			
		</div>
		<!-- 结束 -->
	</div>
  </div>
  
  <div class="am-panel am-panel-default">
    <div class="am-panel-hd">
      <h4 id="operation_title" class="am-panel-title" data-am-collapse="{target: '#operation_records'}"><?php echo $ld['operation_records'] ?></h4>
    </div>
    <div id="operation_records" class="am-panel-collapse am-collapse">
    	
	  <!-- 移动端开始 -->
	   <div class="am-g">
		<div style="border-bottom:2px solid #ddd;line-height:1.6;padding-top:0.5rem;padding-bottom:0.5rem;" class="am-cf">
			<div class="thdate am-u-lg-2 am-u-md-2 am-u-sm-2 am-hide-sm-only" style="font-weight:700;text-align:left;padding-left:2.9rem;"><?php echo $ld['operation_time']?></div>
			<div class="thname am-u-lg-2 am-u-md-2 am-u-sm-2 am-hide-sm-only" style="font-weight:700;text-align:left;"><?php echo $ld['operator']?></div>
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-5 am-show-sm-only" style="font-weight:700;text-align:left;padding-left:2.3rem;">操作</div>
			<div class="thtype am-u-lg-2 am-u-md-2 am-u-sm-2" style="font-weight:700;text-align:left;"><?php echo $ld['order_status_operate']?></div>
			<div class="thtype am-u-lg-2 am-u-md-2 am-u-sm-2" style="font-weight:700;text-align:left;"><?php echo $ld['order_payment_status']?></div>
			<div class="thtype am-u-lg-1 am-u-md-1 am-u-sm-2" style="font-weight:700;text-align:left;"><?php echo $ld['shipping_status']?></div>
			<div align="center" class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-u-end am-hide-sm-only" style="font-weight:700;text-align:left;padding-left:2.3rem;"><?php echo $ld['note2']?></div>
		 </div>
		
		<?php if(isset($order_action_list) && sizeof($order_action_list)>0){foreach($order_action_list as $k=>$v){?>
		  <div style="border-bottom:1px solid #ddd;line-height:1.6;padding-top:0.5rem;padding-bottom:0.5rem;" class="am-cf">
			<div class="tddate am-u-lg-2 am-u-md-2 am-u-sm-5 am-hide-sm-only" style="padding-left:2.9rem;text-align:left;"><?php echo $v['OrderAction']['created']?></div>
			<div style="white-space: normal;text-align:left;" class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-hide-sm-only"><?php echo $v['OrderAction']['name']?></div>
			<div class="am-show-sm-only am-u-sm-5" style="padding-left:2.3rem;text-align:left;"><?php echo $v['OrderAction']['created']?><br><?php echo $v['OrderAction']['name']?></div>
			<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="text-align:left;"><?php echo $Resource_info["order_status"][$v['OrderAction']['order_status']];?></div>
			<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="text-align:left;"><?php echo $Resource_info["payment_status"][$v['OrderAction']['payment_status']];?></div>
			<div class="am-u-lg-1 am-u-md-1 am-u-sm-2" style="text-align:left;"><?php echo $Resource_info["shipping_status"][$v['OrderAction']['shipping_status']];?></div>
			<div class="am-u-lg-3 am-u-md-3 am-u-sm-12" style="text-align:left;padding-left:2.3rem;"><?php echo $v['OrderAction']['action_note'];?></div>
		  </div>
		<?php }}?>
		
	  </div> 
	  <!-- 结束 -->
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
    <div class="am-popup-bd" style="max-height:500px;background-color:#fff;padding-left:0;padding-right:0;">
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


<!-- ajax删除返回数据来自这里↓↓ -->


<?php }else{ //ajax order product ?>



<?php ob_start(); ?>

  	
  	<div class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-cf">
		  		<button id="order_search_btn" class="am-btn am-btn-warning am-radius am-btn-sm " style="float:right;">
  				<span class="am-icon-plus"></span>搜索商品
			</button>
		
		  	<div class="am-cf"></div>
			</div>
			<div class="am-modal am-modal-no-btn" tabindex="-1" id="order_search">
			  <div class="am-modal-dialog" style="display:none;">		
			      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
			    </div>
			    <div class="am-modal-bd">
			      <div class="am-g">
			      	<?php if((!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2)))){?>
				    <?php echo $form->create('',array('action'=>'/',"name"=>"OrdForm",'onsubmit'=>"search_order_product();return false;","class"=>"am-g"));?>
				<div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;border:none;">
				 
					  <div class="am-u-lg-5 am-u-md-5 am-u-sm-5" >
						<?php if(isset($product_style_tree)&&sizeof($product_style_tree)>0){ ?>
						        <label  class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-text-left" style="margin-top:8px;padding:0;"><?php echo $ld['product_style'];?>:</label>
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
						<div>
					  <div class="am-u-lg-6 am-u-md-6 am-u-sm-12" style="margin-bottom:1rem;">
						<?php if(isset($product_type_tree)&&sizeof($product_type_tree)>0){ ?>
			  			<label  class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-text-left" style="padding:0;"><?php echo $ld['all_product_type']?>:</label>
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
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-12">
								<?php if(isset($brands)&&!empty($brands)){?>
					  			  <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-text-left" style="padding:0;"><?php echo $ld['brand']?>:</label>
								  <div  class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								  <select id="product_brand" name="product_brand">
								    <option value=""><?php echo $ld['please_select']?></option>
								    <?php foreach($brands as $v){?>
								    <option value="<?php echo $v['Brand']['id']?>"><?php echo $v['Brand']['code'].'-'.$v['BrandI18n']['name'];?></option>
								    <?php }?>
								  </select>
								  </div>
								<?php }?>
					  	  </div>
					  	  <div class="am-cf"></div>
							</div>
				      	     <div class="am-u-lg-6 am-u-md-6 am-u-sm-12" style="margin-top:1rem;">
								<label  class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-text-left" style="padding:0;"><?php echo $ld['product']?>:</label>
									<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
									  <input type="text" id="order_product" onkeydown="if(event.keyCode==13){return false;}"/>
									</div>
						   			 <input style="margin-left:5px; margin-top:10px;margin-left:0;" type="button" id="add_product_button" onclick="search_order_product();"  class="am-btn am-btn-success am-radius am-btn-sm am-fl" value="<?php echo $ld['search'];?>" />
								    <select id="result" onchange="add_order_product(this.value)" class="selecthide am-fl" style="width:20%;margin:0px 5px 0 5px;display:none;"> 									<option value=""><?php echo $ld['please_select']?></option>
								    </select>
						    		<span id="load_div"  class="order_status"></span>
						       </div>
				  </div>
					<?php echo $form->end(); ?>
				<?php }?>
			      </div>
			    </div>
		
				</div>
			<!-- <div id="order_search" style="" class="am-g">
			  
			
		</div> -->

			
				<!-- 一直到这里，下面是商品信息里面的商品图片 -->
				<div class="am-g am-margin-top-sm am-hide-sm-only" style="line-height:30px;font-size:14px;font-weight:600;border-bottom:1px solid #ddd">
				  <div class="am-u-sm-1 am-u-lg-1 am-u-md-1 am-text-left" style="padding-right:0;"><?php echo $ld['product_image']?></div>
				  <div class="am-u-sm-3 am-u-lg-3 am-u-md-3 am-text-left" style="padding-left:3rem;"><?php echo $ld['product_name']?></div>
<!--				  <th style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>"><?php echo $ld["shop_price"];?><br>(<?php echo $ld["order_list_price"];?>)</th>-->
				  <div class="am-u-sm-2 am-u-lg-2 am-u-md-2 am-text-left" style="padding-left:0;"><?php echo $ld['order_quantity']?></div>
				  <div class="am-u-sm-3 am-u-lg-3 am-u-md-3 am-text-left" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>;padding-left:0em;"><?php echo $ld['price']?></div>
				  <div class="am-u-sm-1 am-u-lg-1 am-u-md-1 am-text-left" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>;padding-left:0.2rem;text-align:left;"><?php echo $ld['subtotal']?></div>
				  <?php //if(!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2))){?>
				  <?php //if($order_info['Order']['type']!='taobao'){}?>
				  <div class="am-u-sm-2 am-u-lg-2 am-u-md-2 am-text-left" style="text-align:left;padding-left:1em;"><?php echo $ld['operate'];?></div>
				  <?php //}?>
				</div>
			  <div class="am-g" id="order_products_detail_innerhtml" style="margin-bottom:1rem;">
			  <?php 	$the_subtotal=0;$sum_quantity=0;
			  	if(isset($order_info['OrderProduct']) && sizeof($order_info['OrderProduct'])>0){
		  			$package_product=array();
		  	  		foreach($order_info['OrderProduct'] as $vv){
		  	  			if(intval($vv['parent_product_id'])>0){
							$package_product[$vv['parent_product_id']]=$vv['parent_product_id'];
						}
		  	  		}
					foreach($order_info['OrderProduct'] as $k=>$v){ $total_attr_price=0;
						if(!isset($package_product[$v['product_id']])&&$order_info['Order']['service_type']!='appointment'){
							$sum_quantity+=$v['product_quntity'];
						}else if(isset($package_product[$v['product_id']])&&$v['parent_product_id']==0){
							$sum_quantity+=$v['product_quntity'];
						}else if($order_info['Order']['service_type']=='appointment'){
							if($v['parent_product_id']==0){
								$sum_quantity+=$v['product_quntity'];
							}
						}
						if ($v['parent_product_id'] == 0) {
								$the_subtotal +=($v['product_price']*($v['product_quntity']-$v['refund_quantity'])+$v['adjust_fee']);
						} ?>
				<div class="am-u-sm-12 am-margin-top-sm" style="border-bottom:1px solid #ddd;">
					
 				  <div class="smail_img am-u-sm-4 am-u-lg-1 am-u-md-1 am-text-left" id="product_1" style="text-align: left;padding: 5px 0 3px;line-height: normal;">
				  <figure data-am-widget="figure" class="am am-figure am-figure-default "
data-am-figure="{  pureview: 'true' }" style="margin-left:0;margin-top:0;">
					<?php echo $html->image($v['img_thumb']!=""?$v['img_thumb']:"/theme/default/images/default.png",array('date-rel'=>$v['img_thumb']!=""?$v['img_thumb']:"/theme/default/images/default.png")); ?>
				  </figure>
				  </div>
				  <!-- 名称 -->
				  <div class="am-u-lg-3 am-u-md-3 am-u-sm-8 am-text-left" id="product_2" style="min-height:130px;">
					<p style="line-height:20px;margin-bottom:0;">
					<?php if($v['item_type']==''){
							if(isset($v['sku_product'])&&$v['sku_product']==1){
								echo $svshow->seo_link(array('type'=>'P','id'=>$v['parent_product_id'],'name'=>'','sub_name'=>$v['product_name'],'style'=>'font-weight:bold'));
							}else{
								echo $svshow->seo_link(array('type'=>'P','id'=>$v['product_id'],'name'=>'','sub_name'=>$v['product_name'],'style'=>'font-weight:bold'));
							}
						}else{
							echo $html->link($v['product_name'],'javascript:void(0);',array('style'=>'font-weight:bold'));
						}
					?>
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
					<div class='am-cf'>
					  <input type="hidden" name="order_product_id[]" value="<?php echo $v['id'];?>">
					  <input type="hidden" name="order_product_code[]" value="<?php echo $v['product_code'];?>">
					  <span><?php echo $ld['product_code']?>:&ensp;</span><?php echo $v['product_code'];?>
					</div>
					<?php if(isset($order_info['Order']['service_type'])&&$order_info['Order']['service_type']=='appointment'){ ?>
					<div class='am-cf'>
						<input type="hidden" name="order_product_number[]" value="<?php echo $v['product_number'];?>">
						<span><?php echo '商品条码'; ?>:&ensp;</span><?php echo $v['product_number'];?>
					</div>
					<?php if((isset($sub_order_product_list[$v['product_id']])&&$v['parent_product_id']>0)||($order_info['Order']['service_type']=='appointment'&&$v['product_quntity']==1)){ ?>
					<div class='am-cf'>
						<span><?php echo '服务类型'; ?>:&ensp;</span>
						<span>
							<?php
									$order_product_service_type=explode(',',trim($v['service_type']));
						 if(!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2||$v['delivery_status']==5))){ ?>
							<?php if(isset($Resource_info['order_product_service_type'])&&sizeof($Resource_info['order_product_service_type'])>0){
										foreach($Resource_info['order_product_service_type'] as $kk=>$vv){
							?>
							<label class='am-checkbox order_product_service_type'><input type='checkbox' name="order_product_service_type[<?php echo $v['id']; ?>]" value="<?php echo $kk; ?>" onclick="order_product_service_type_modify(this,<?php echo $v['id']; ?>)" <?php echo in_array($kk,$order_product_service_type)?'checked':''; ?> /><?php echo $vv; ?></label>
							<?php }} ?>
							<?php }else{
									if(empty($order_product_service_type)){
										echo $ld['default'];
									}else{
										foreach($order_product_service_type as $vv){
											echo isset($Resource_info['order_product_service_type'][$vv])?$Resource_info['order_product_service_type'][$vv]:'';
										}
									}
							} ?>
						</span>
					</div>
					<?php } ?>
					<?php } ?>
					<?php if(isset($order_info['OrderProductValue'])&& count($order_info['OrderProductValue'])>0){?>
					<div><?php echo $ld['product_attribute'];?>:&ensp;</div>
					  <?php foreach($order_info['OrderProductValue'] as $opk=>$opv){ ?>
						<?php if($opv['order_product_id']==$v['id'] && (int)$opv['attr_price']!=0){?>
						<?php $total_attr_price+=$opv['attr_price'];?>
						<div><?php echo $opv['attribute_value'];?>:&ensp;<?php echo $opv['attr_price'];?></div>
						<?php }?>
					  <?php }?>
					<?php }?>
					<div>
					  <span><?php echo $ld['product_attribute']?>:&ensp;</span>
					  <?php if(!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2||$v['delivery_status']==5))){ ?>
					<?php if (isset($v['product_attrbute'])&& !empty($v['product_attrbute'])) { ?>

					  <span style="display:block" onclick="javascript:listTable.edit(this, 'orders/order_product_attribute_save', <?php echo $v['id']?>)"><?php echo $v['product_attrbute']; ?></span>

					<?php }else{ ?>
					<span style="display:block" onclick="javascript:listTable.edit(this, 'orders/order_product_attribute_save', <?php echo $v['id']?>)">-</span>
					<?php } ?>					

					  <?php }else{ if(isset($v['sku_product']['ProductAttribute'])){
						foreach($v['sku_product']['ProductAttribute'] as $attr_v){
						  echo $attr_v['ProductAttribute']['product_type_attribute_value']."\n";
						}
					  }else{
						echo isset($v['product_attrbute'])&&!empty($v['product_attrbute'])?str_replace("<br />", "\n", $v['product_attrbute']):'';}
					  }?>
					</div>
				  </div>
				
				  <!-- 数量列 -->
				  <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" id="product_3" style="padding-top:0px;text-align:left;padding-left:0;padding-right:0;">
					<?php
					  	$product_quntity_update=false;
						if(!(($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2||$v['delivery_status']==5))&&!(isset($order_info['Order']['service_type'])&&$order_info['Order']['service_type']=='appointment'&&$v['product_quntity']=='1'&&trim($v['product_number'])!='')&&(isset($order_info['Order']['service_type'])&&$order_info['Order']['service_type']=='appointment'&&!isset($sub_order_product_list[$v['id']]))){
							$product_quntity_update=true;
						}
						if($product_quntity_update){ ?>
					<div class="am-u-sm-3 am-text-right " style="line-height:37px;"><i style="color:#555" class="am-icon-minus <?php if ($v['parent_product_id']!=0) { echo "am-hide" ;} ?>"></i><?php if ($v['parent_product_id']!=0) { echo "&nbsp;" ;} ?></div>
					<div class="am-u-sm-6">
						<input class="<?php if ($v['parent_product_id']!=0) { echo "am-hide" ;} ?>" type="text" size="2" name="order_product_quntity[]" id="order_product_quntity_<?php echo $v['id'];?>" value="<?php echo $v['product_quntity'];?>" onblur="changeNum(this.value,'<?php echo $v['id']?>','<?php echo $v['product_price']*$v['product_quntity'];?>','<?php echo $order_info['Order']['subtotal']?>')" />
					   <div style="line-height:37px;text-align:center" class="<?php if ($v['parent_product_id']==0) { echo "am-hide" ;} ?>" ><?php echo $v['product_quntity'];?></div>
					</div>
					<div class="am-u-sm-3 am-text-left" style="line-height:37px;"><i style="color:#555" class="am-icon-plus <?php if ($v['parent_product_id']!=0) { echo "am-hide" ;} ?>"></i> <?php if ($v['parent_product_id']!=0) { echo "&nbsp;" ;} ?></div>
					<?php }else{
					  echo '<span id="pq_'.$v["id"].'">'.$v['product_quntity'].'</span>&nbsp;';
					  ?><input type="hidden" name="order_product_quntity[]" id="order_product_quntity_<?php echo $v['id'];?>" value="<?php echo $v['product_quntity'];?>" /><?php						          if(isset($refund_warehouse)&&!empty($refund_warehouse)&&$v['product_quntity']>$v['refund_quantity']&&$svshow->operator_privilege('orders_refund')){
  echo $html->link($ld['return'],"javascript:;",array("onclick"=>"document.getElementById('refund_span_".$v['id']."').style.display=''",'escape' => false,'class'=>"cancelbtn"));?>
					<span id='refund_span_<?php echo $v["id"]?>' style='display:none'>
					  <input type='text' value='0' id="refund_input_<?php echo $v['id']?>" style="width:30px">
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

					<div class="am-u-sm-12 am-text-left" style="padding-left:0;">
						<span style="display:inline-block;">库存：</span>
					  <span id="haveQuantity<?php echo $v['id'];?>" <?php if(isset($all_product_quantity_infos[$v['product_code']])&&$v['product_quntity']>$all_product_quantity_infos[$v['product_code']]){?>style="color:red"<?php }?>>
					  <input type="hidden" id="order_product_have_quntity_<?php echo $v['id'];?>" value="<?php echo isset($all_product_quantity_infos[$v['product_code']])?$all_product_quantity_infos[$v['product_code']]:0;?>">
					    <span style="font-size:13px;color:green;">
						  <?php echo isset($all_product_quantity_infos[$v['product_code']])?$all_product_quantity_infos[$v['product_code']]:0?>
						</span>
					  </span>&nbsp;&nbsp;&nbsp;  
					  <span style="font-size:13px">
						<?php //echo isset($stockProductQuantityInfo[$v['product_code']])?$stockProductQuantityInfo[$v['product_code']]:0;?>
					  </span>
					</div>

					<?php if(isset($stockProductInfo)&&isset($stockProductInfo[$v['product_id']])){?>
					  <?php foreach($stockProductInfo[$v['product_id']] as $kk=>$vv){ if($vv['Stock']['quantity']<=0){ continue;}?>
					  <p><?php echo $vv['Stock']['warehouse_name'];?>:<?php echo $vv['Stock']['quantity'];?></p>
					<?php }}?>
					<input type="hidden" name="order_product_quntity_old[]" id="order_product_quntity_<?php echo $v['id'];?>" value="<?php echo $v['product_quntity'];?>"  />
				  </div>
			
				  <!-- 折扣列 -->
				  <div class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-text-left" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none;';}?>padding-left:0;padding-right:0;">
					<?php $discount=sprintf('%01.2f',($v['product_price']+$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity'])));?>
					<div class="discount_number" style="text-align:left;line-height:30px;font-size:16px;padding-left:0em;">
					  <span id="order_product_shop_price_<?php echo $v['id'];?>">
					    <?php echo isset($discount)?$discount:"";?>
					  </span>
					  <span style="color:#ccc;text-decoration: line-through;">
					    <?php echo $v['product_price'];?>
					  </span>
					</div>
					<div class="<?php if ($v['parent_product_id']!=0) { echo 'am-hide'; } ?>">
					<input type="hidden" size="12" name="order_product_price[]"  id="order_product_price_<?php echo $v['id'];?>" value="<?php echo $v['product_price'];?>" />
				  <?php if((!($order_info['Order']['payment_status']==2 && ($order_info['Order']['shipping_status']==1 || $order_info['Order']['shipping_status']==2||$v['delivery_status']==5)) || ($v['refund_quantity']>0 && $v['refund_quantity'] != $v['product_quntity'])) && $admin['type']=='S'){?>
					<input type="text" onblur="changeDiscount(this.value,'<?php echo $v['id']?>','<?php echo $v['product_price']*$v['product_quntity'];?>','<?php echo $order_info['Order']['subtotal']?>')" value="<?php if($total_attr_price==0){echo isset($all_product_infos[$v['product_id'].$v['product_code']])&&$all_product_infos[$v['product_id'].$v['product_code']]>0?@sprintf('%01.2f',($v['product_price']+$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']))/($all_product_infos[$v['product_id'].$v['product_code']])*10):10;}else{echo isset($all_product_infos[$v['product_id'].$v['product_code']])&&$all_product_infos[$v['product_id'].$v['product_code']]>0?@sprintf('%01.2f',($v['product_price']+$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']))/($all_product_infos[$v['product_id'].$v['product_code']]+$total_attr_price)*10):10;}?>" style="width:60px;float:left;" id="order_product_discount_<?php echo $v['id'];?>" />
					<span class="am-fl" style="padding:0.625em 2px;"><?php echo $backend_locale=="chi"?'折':'%'; ?>=</span>
					<input type = "text" name="order_product_discount[]" id="order_product_sumdiscount_<?php echo $v['id'];?>" value="<?php echo @sprintf("%01.2f",$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']));?>" style="width:60px;float:left;"  onblur="changeSumDiscount(this.value,'<?php echo $v['id']?>','<?php echo isset($all_product_infos[$v['product_id'].$v['product_code']])?$all_product_infos[$v['product_id'].$v['product_code']]:'0';?>','<?php echo $v['product_price']*$v['product_quntity'];?>','<?php echo $order_info['Order']['subtotal']?>')" /><br />
				  <?php }else{ $zhekou = isset($all_product_infos[$v['product_id'].$v['product_code']])&&$all_product_infos[$v['product_id'].$v['product_code']]>0?@sprintf('%01.2f',($v['product_price']+$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']))/$all_product_infos[$v['product_id'].$v['product_code']]*10):10;
					if($zhekou!=10){
						echo $zhekou;
						echo $backend_locale=="chi"?'折':'%'." =";
						echo "<br />";
					}?>
					<input type = "hidden" name="order_product_discount[]" id="order_product_sumdiscount_<?php echo $v['id'];?>" value="<?php echo @sprintf("%01.2f",$v['adjust_fee']/($v['product_quntity']-$v['refund_quantity']));?>" />
					<?php }?>
					</div>
				  </div>
				  <!-- 小计列 -->
				  <div class="am-u-lg-1 am-u-md-1 am-u-sm-2 am-text-left" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none;';}?>padding-left:1rem;padding-right:0;">
					<span id="order_product_total_<?php echo $v['id'];?>" >
					  <?php echo @sprintf('%01.2f',($v['product_price']*($v['product_quntity']-$v['refund_quantity'])+$v['adjust_fee']));?>
					</span>
					<?php if(isset($total_attr_price) && $total_attr_price>0){?>
					<div style="height:38px;"></div>
					<div><?php echo @sprintf("%01.2f",$total_attr_price);?></div>
					<?php }?>
				  </div>
				  	  
				  <?php 
				  	  if(!($order_info['Order']['payment_status']==2&&($order_info['Order']['shipping_status']==1||$order_info['Order']['shipping_status']==2||$v['delivery_status']==5))){?>
				  <!-- 操作列 -->
				  <div class="am-action am-u-lg-2 am-u-md-2 am-u-sm-2 <?php if ($v['parent_product_id']!=0) { echo 'am-hide' ;} ?>" style="white-space: nowrap;text-align:left;padding-left:1.8em;" >
					<?php if($v['product_type_id']!=""&&in_array($v['product_type_id'],$customize_product_type_list)){ ?>
					<a class="am-btn am-btn-default am-radius am-btn-xs" href="javascript:void(0);" data-am-modal="{target: '#update_pro_attr'}" onclick="update_pro_attr('<?php echo $v['product_code'];?>',<?php echo $v['product_id'];?>,<?php echo $v['id'];?>)"><?php echo $ld['attribute_edit'] ?></a><br> <?php  //echo $html->link($ld['view_amendments'],'/orders/print_attr_value/'.$order_info['Order']['id'].'/'.$v['id'].'/'.$v['product_type_id'].'/'.$v['product_style_id'],array('target'=>'_blank','class'=>'am-btn am-btn-default am-btn-xs')); ?><?php } ?>
					<?php if(isset($order_info['Order']['service_type'])&&$order_info['Order']['service_type']=='appointment'&&!(isset($sub_order_product_list[$v['id']])&&$sub_order_product_list[$v['id']]>=$v['product_quntity'])&&$v['product_number']==''){ ?>
					<a href="javascript:void(0);" class="am-btn am-btn-default am-text-warning am-radius am-btn-xs" onclick="modify_product_number(<?php echo $v['id'];?>,'<?php echo $v['product_number'] ?>')"><?php echo '设置商品条码'; ?></a><br />
					<?php } ?>
					<?php if(isset($order_info['Order']['service_type'])&&$order_info['Order']['service_type']=='appointment'&&$v['product_number']!=''){ ?>
						<span class="order_product_delivery_status"><?php echo isset($Resource_info['order_product_status'][$v['delivery_status']])?$Resource_info['order_product_status'][$v['delivery_status']]:''; ?>
						<?php
							if($v['delivery_status']==0){
								echo "<br />";
								echo $html->link('已取货','javascript:void(0);',array('onclick'=>"order_product_status_modify(this,".$v['id'].",'1')",'class'=>'am-btn am-btn-default am-text-success am-radius am-btn-xs'));
							}else if($v['delivery_status']==1){
								echo "<br />";
								echo $html->link('已检查','javascript:void(0);',array('onclick'=>"order_product_status_modify(this,".$v['id'].",'2')",'class'=>'am-btn am-btn-default am-text-success am-radius am-btn-xs'));
							}else if($v['delivery_status']==2){
								echo "<br />";
								echo $html->link('已修改','javascript:void(0);',array('onclick'=>"order_product_status_modify(this,".$v['id'].",'3')",'class'=>'am-btn am-btn-default am-text-success am-radius am-btn-xs'))."<br />";
								echo $html->link('取消','javascript:void(0);',array('class'=>'am-btn am-btn-default am-text-warning am-radius am-btn-xs'));
							}else if($v['delivery_status']==3){
								echo "<br />";
								echo $html->link('已质检','javascript:void(0);',array('onclick'=>"order_product_status_modify(this,".$v['id'].",'4')",'class'=>'am-btn am-btn-default am-text-success am-radius am-btn-xs'));
							}
						?></span><br />
						<a class="am-btn am-btn-default am-text-success am-radius am-btn-xs" href="javascript:void(0);" onclick="ajax_order_product_modify(<?php echo $v['id'];?>)"><?php echo '附加信息';?></a><br >
					<?php } ?>
					<?php if(!$order_info['Order']['payment_status']==2){ ?>
					<a class="am-btn am-btn-default am-text-danger am-radius am-btn-xs" href="javascript:void(0);" onclick="delete_order_product(<?php echo $v['id'];?>,'<?php echo $v['product_code'];?>')"><?php echo $ld['delete']?></a>
					<?php } ?>
				  </div>
				  <div class="am-action am-u-sm-1 am-u-end <?php if ($v['parent_product_id']==0) { echo 'am-hide' ;} ?>" style="white-space: nowrap;" >
				  	<?php if(isset($order_info['Order']['service_type'])&&$order_info['Order']['service_type']=='appointment'&&!(isset($sub_order_product_list[$v['id']])&&$sub_order_product_list[$v['id']]>=$v['product_quntity'])){ ?>
					<span class="order_product_delivery_status"><?php echo isset($Resource_info['order_product_status'][$v['delivery_status']])?$Resource_info['order_product_status'][$v['delivery_status']]:''; ?>
					<?php
						if($v['delivery_status']==0){
							echo "<br />";
							echo $html->link('已取货','javascript:void(0);',array('onclick'=>"order_product_status_modify(this,".$v['id'].",'1')",'class'=>'am-btn am-btn-default am-text-success am-radius am-btn-xs'));
						}else if($v['delivery_status']==1){
							echo "<br />";
							echo $html->link('已检查','javascript:void(0);',array('onclick'=>"order_product_status_modify(this,".$v['id'].",'2')",'class'=>'am-btn am-btn-default am-text-success am-radius am-btn-xs'));
						}else if($v['delivery_status']==2){
							echo "<br />";
							echo $html->link('已修改','javascript:void(0);',array('onclick'=>"order_product_status_modify(this,".$v['id'].",'3')",'class'=>'am-btn am-btn-default am-text-success am-radius am-btn-xs'))."<br />";
							echo $html->link('取消','javascript:void(0);',array('class'=>'am-btn am-btn-default am-text-warning am-radius am-btn-xs'));
						}else if($v['delivery_status']==3){
							echo "<br />";
							echo $html->link('已质检','javascript:void(0);',array('onclick'=>"order_product_status_modify(this,".$v['id'].",'4')",'class'=>'am-btn am-btn-default am-text-success am-radius am-btn-xs'));
						}
					?></span><br />
					<a class="am-btn am-btn-default am-text-success am-radius am-btn-xs" href="javascript:void(0);" onclick="ajax_order_product_modify(<?php echo $v['id'];?>)"><?php echo '附加信息';?></a><br >
					<?php } ?>
					<?php if(!$order_info['Order']['payment_status']==2){ ?>
					<a class="am-btn am-btn-default am-text-danger am-radius am-btn-xs" href="javascript:void(0);" onclick="delete_order_product(<?php echo $v['id'];?>,'<?php echo $v['product_code'];?>')"><?php echo $ld['delete']?></a>
					<?php } ?>
				</div>
				  <?php }else if($v['product_type_id']!=""&&in_array($v['product_type_id'],$customize_product_type_list)){ ?>
				  	<div class="am-action" ><a class="am-btn am-btn-success am-radius am-btn-sm" href="javascript:void(0);" data-am-modal="{target: '#update_pro_attr'}" onclick="view_product_attr_value(<?php echo $v['product_id'];?>,'<?php echo $v['product_code'];?>',<?php echo $v['id'];?>)"><?php echo $ld['attribute_edit'] ?></a><br> <?php  //echo $html->link($ld['view_amendments'],'/orders/print_attr_value/'.$order_info['Order']['id'].'/'.$v['id'].'/'.$v['product_type_id'].'/'.$v['product_style_id'],array('target'=>'_blank','class'=>'am-btn am-btn-success am-btn-xs')); ?>
				  	</div>
				  	<?php }else{ echo "&nbsp;"; } ?>
				</div>
				<?php }}?>
				<!-- 商品信息价格 -->
				<div class="am-u-sm-12 am-margin-top-sm">
				  <td></td>
				  <?php if(isset($dealers_info)&&$dealers_info['Dealer']['min_num']!=''){?>
				  <td><strong>最小起订数:</strong><span id="min_num"><?php echo $dealers_info['Dealer']['min_num'];?></span></td>
				  <?php	}else{?>
				  <td></td>
				  <?php }?>
				  <div class="am-u-sm-7 am-text-right" style="padding-right:30px;"><strong><?php echo $ld['number_total']?>:</strong><span id="sum_quantity"><?php echo $sum_quantity; ?></span></div>

				  <div class="am-u-sm-2 am-text-right" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>;padding-left:0;"><strong><?php echo $ld['order_total']?></strong></div>
				  <div class="am-u-sm-3" style="<?php if(isset($dp_displaed)&&$dp_displaed==0){echo 'display:none';}?>;padding-left:0;padding-right:0;">
					<input type="hidden" id="order_subtotal" name="order_subtotal" value="<?php echo $order_info['Order']['subtotal']?>">
					<span id="last_order_subtotal"><?php echo sprintf('%01.2f',$the_subtotal);?></span>
				  </div>
				</div>
				<!-- 商品价格end -->
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
			  </div>
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

<!-- 设置商品条码 -->
<div class="am-modal am-modal-no-btn" id="modify_product_number">
  <div class="am-modal-dialog">
    <div class="am-modal-hd" style=" z-index: 11;">
		<h4 class="am-popup-title"><?php echo '设置商品条码'; ?></h4>
		<span data-am-modal-close class="am-close">&times;</span>
    </div>
    <div class="am-modal-bd">
    		<form method='POST' class='am-form am-form-horizontal'>
    			<input type='hidden' name='order_product_id' value='0' />
    			<div class='am-form-group'>
    				<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label am-text-right"><?php echo '商品条码';?>:</label>
    				<div class='am-u-lg-7 am-u-md-7 am-u-sm-7'>
    					<input type='text' class='am-form-field' id="product_number" name='product_number' value='' maxlength='100' />
    				</div>
    			</div>
    			<div class='am-form-group am-hide'>
    				<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label am-text-right"><?php echo $ld['order_quantity'];?>:</label>
    				<div class='am-u-lg-7 am-u-md-7 am-u-sm-7'>
    					<input type='text' class='am-form-field' name='quantity' value='1' onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}if(this.value=='')this.value=1;" onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}if(this.value=='')this.value=1;" />
    				</div>
    			</div>
    			<div class='am-form-group'>
    				<label class="am-u-lg-4 am-u-md-4 am-u-sm-2 am-form-label am-text-right">&nbsp;</label>
    				<div class='am-u-lg-7 am-u-md-7 am-u-sm-9 am-text-left'>
    					<?php if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false){ ?>
    					<button class="am-btn am-btn-success am-btn-sm am-radius" type="button" id="scan" style="margin-right:1rem;">扫一扫</button>
    					<?php } ?>
    					<button type='button' id="modify_pro_number" class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_modify_product_number(this)"><?php echo $ld['confirm']; ?></button>
    				</div>
    			</div>
    			<div class='am-form-group'>
    				<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label am-text-right">&nbsp;</label>
    				<div class='am-u-lg-7 am-u-md-7 am-u-sm-7 am-text-left'>
    					
    				</div>
    			</div>
    		</form>
    </div>
  </div>
</div>

<!-- 待发货订单商品 -->
<div class="am-modal am-modal-no-btn" id="ajax_order_product_modify">
  <div class="am-modal-dialog">
    <div class="am-modal-hd" style=" z-index: 11;">
		<h4 class="am-popup-title"><?php echo $ld['edit_product']; ?></h4>
		<span data-am-modal-close class="am-close">&times;</span>
    </div>
    <div class="am-modal-bd">
	<form method='POST' class='am-form am-form-horizontal'>
		<input type='hidden' name='order_id' value='0'>
		<input type='hidden' name='order_product_id' value='0'>
			<div class="am-g">				
			</div>			
			<div class="am-g">
				<div class="am-cf" style="padding-top:1rem;padding-bottom:1rem;border-bottom:1px solid #ddd;">
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-4" style="font-weight:700;"><?php echo '取货'; ?></div>
					<div class='am-text-left am-u-lg-9 am-u-md-9 am-u-sm-8'>
						<select name='order_product_picker' data-am-selected="{maxHeight:200}">
							<option value='0'><?php echo $ld['please_select']; ?></option>
							<?php if(isset($operator_list)&&sizeof($operator_list)>0){foreach($operator_list as $k=>$v){ ?>
							<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
							<?php }} ?>
						</select>
					</div>
				</div>
				<div class="am-cf" style="padding-top:1rem;padding-bottom:1rem;border-bottom:1px solid #ddd;">
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-4" style="font-weight:700;"><?php echo '质检'; ?></div>
					<div class='am-text-left am-u-lg-9 am-u-md-9 am-u-sm-8'>
						<select name='order_product_qc' data-am-selected="{maxHeight:200}">
							<option value='0'><?php echo $ld['please_select']; ?></option>
							<?php if(isset($operator_list)&&sizeof($operator_list)>0){foreach($operator_list as $k=>$v){ ?>
							<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
							<?php }} ?>
						</select>
					</div>
				</div>
				<tr>
					<th></th>
					<td class='am-text-left'>
						<button type='button' style='margin-top:1rem;' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_order_product_modify_submit(this)"><?php echo $ld['confirm']; ?></button>
					</td>
				</tr>
			</div>
		
	</form>
    </div>
  </div>
</div>

<!-- 待发货订单商品 -->
<div class="am-modal am-modal-no-btn" id="to_be_delivered">
  <div class="am-modal-dialog">
    <div class="am-modal-hd" style=" z-index: 11;">
		<h4 class="am-popup-title"><?php echo $ld['delivery']; ?></h4>
		<span data-am-modal-close class="am-close">&times;</span>
    </div>
    <div class="am-modal-bd" style="overflow-y:auto;max-height:400px;">
	<form method='POST' class='am-form am-form-horizontal'>
		
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
.order_none-border td{border:none;}
#modify_product_number .am-form-group{margin-bottom:10px;}
#to_be_delivered table.am-table th,#to_be_delivered table.am-table td{text-align:left;padding:0.5rem;}
#ajax_order_product_modify form{max-height:300px;overflow-y:scroll;}
#ajax_order_product_modify form th{vertical-align: middle;border-bottom:0px;}
select.order_product_status{width:95%;padding:5px;margin-bottom:5px;}
td select.order_product_status{width:65%;}
.am-form-horizontal label.order_product_service_type{display: inline-block;padding-top:0px;}
#select_best_date,#select_best_time{display:inline-block;}
#select_best_date.address,#select_best_time.address{display:none;}
#order_status_change_modal .am-form-group{margin-bottom:1rem;}
</style>
<script>
function openother(){
	$("#other_information").collapse('open');
}
function opencollapse(){
  	$("#operation_records").collapse('open');
}
<?php if((isset($order_info['Order']['country']) || $order_info['Order']['country']!="") && (isset($order_info['Order']['province']) || $order_info['Order']['province']!="") && (isset($order_info['Order']['city']) || $order_info['Order']['city']!="")){?>
<?php if(isset($order_info['Order']['regions'])){$region_arr=explode(" ",$order_info['Order']['regions']);}?>
	//1
	getRegions(0,'',"<?php echo isset($order_info['Order'])?$order_info['Order']['country']:'' ?>");
	//2
<?php if(isset($order_info['Order'])&&isset($regions_infovalues[$order_info['Order']['country']])){ ?>
	getRegions(<?php echo $regions_infovalues[$order_info['Order']['country']]; ?>,'country',"<?php echo isset($order_info['Order'])?$order_info['Order']['province']:'' ?>");
<?php } ?> 
	//3
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
  var quantity=$(this).parent().parent().find("input[name='order_product_quntity[]']");
  var n=quantity.val();
  var num=parseInt(n)+1;
 if(num==0){alert("Purchase quantity can't equal to zero!");return false;}
  quantity.val(num);
  order_products_data_save();
});

$("#order_product_div").on('click',".am-icon-minus",function(){
  //找到当前数量框
  var quantity=$(this).parent().parent().find("input[name='order_product_quntity[]']");
  var n=quantity.val();
  var num=parseInt(n)-1;
 if(num==0){alert("Purchase quantity can't equal to zero!");return false;}
  quantity.val(num);
  order_products_data_save();
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

function discount_number () {
	var dis_num = document.getElementsByClassName('discount_number');
	for(var i =0;i<dis_num.length;i++){
	var dis_num_span = dis_num[i].getElementsByTagName('span');
	if (Number(dis_num_span[0].innerHTML) == Number(dis_num_span[1].innerHTML)) {
		dis_num_span[1].style.display = 'none';
	};
	}
}
discount_number();

//ajax设置商品条码
$("#modify_product_number").on('closed.modal.amui', function(){
	$(this).find("input[name='order_product_id']").val('0');
	$(this).find("input[type='text']").val('');
	$(this).find("input[name='quantity']").val('1');
});

function modify_product_number(order_product_id,order_product_number){
	$("#modify_product_number input[name='order_product_id']").val(order_product_id);
	$("#modify_product_number input[name='product_number']").val(order_product_number);
	$("#modify_product_number").modal('open');
}

function ajax_modify_product_number(btn){
	var postForm=$(btn).parents('form');
	var postData=postForm.serialize();
	$.ajax({
		url: admin_webroot+"orders/ajax_modify_product_number",
		type:"POST",
		data:postData,
		dataType:"json",
		success: function(data){
			if(data.code=='1'){
				order_reflash(true,data.total,data.need_pay,data.insure_fee);
			}else{
				alert(data.message);
			}
  		}
  	});
}

//加载待发货订单商品
function ajax_to_be_delivered(){
	var order_id = document.getElementById("order_id").value;
	$.ajax({
		url: admin_webroot+"orders/ajax_to_be_delivered/"+order_id,
		type:"POST",
		data:{},
		dataType:"html",
		success: function(data){
			$('#to_be_delivered form').html(data);
			$("#to_be_delivered select[name='logistics_company_id']").selected({maxHeight:100,btnWidth:'100%',btnSize:'sm'});
			$("#to_be_delivered input[type='checkbox']").uCheck();
			$("#to_be_delivered").modal('open');

			// $("#to_be_delivered select[id='Shipment_country_select']").selected({maxHeight:100,btnWidth:'100%',btnSize:'sm'});
			// $("#to_be_delivered select[id='Shipment_province_select']").selected({maxHeight:100,btnWidth:'100%',btnSize:'sm'});
			// $("#to_be_delivered select[id='Shipment_city_select']").selected({maxHeight:100,btnWidth:'100%',btnSize:'sm'});

			// $("#to_be_delivered select[id='Shipment_country_select']").trigger('changed.selected.amui');
			// $("#to_be_delivered select[id='Shipment_province_select']").trigger('changed.selected.amui');
			// $("#to_be_delivered select[id='Shipment_city_select']").trigger('changed.selected.amui');
  		}
  	});
}

//订单商品发货操作
function ajax_order_delivered(btn,code){
	var postForm=$(btn).parents('form');
	var logistics_company_id=postForm.find("select[name='logistics_company_id']").val();
	var invoice_no=postForm.find("input[name='invoice_no']").val().trim();
	if(logistics_company_id!='0'&&invoice_no==''){
		alert(j_empty_invoice_number);
		return false;
	}
	var postData=postForm.serializeArray();
	var option=new Object();
	option.name="code";
	option.value=code;
	postData.push(option);
	var order_id = document.getElementById("order_id").value;
	$.ajax({
		url: admin_webroot+"orders/ajax_order_delivered/"+order_id,
		type:"POST",
		data:postData,
		dataType:"json",
		success: function(data){
			//alert(data.message);
			if(data.code=='1'){
				alert('发货完成')
				window.location.reload();
			}else{
				ajax_to_be_delivered();
			}
  		}
  	});
}
// 订单审核
function order_check(num){
	var order_id = document.getElementById("order_id").value;
	$.ajax({
		url: admin_webroot+"orders/ajax_order_check",
		type:"POST",
		data:{'order_id':order_id,'order_check':num},
		dataType:"json",
		success: function(data){
			// if(data.code=='1'){
			// 	order_reflash(true,data.total,data.need_pay,data.insure_fee);
			// 	order_status_select_reload();
			// }else{
			// 	alert(data.message);
			// }
  		}
  	});
}

//订单商品状态修改
function order_product_status_modify(btn,order_product_id,order_product_status){
	var order_id = document.getElementById("order_id").value;
	$.ajax({
		url: admin_webroot+"orders/ajax_order_product_status_modify",
		type:"POST",
		data:{'order_id':order_id,'order_product_id':order_product_id,'order_product_status':order_product_status},
		dataType:"json",
		success: function(data){
			if(data.code=='1'){
				order_reflash(true,data.total,data.need_pay,data.insure_fee);
				order_status_select_reload();
			}else{
				alert(data.message);
			}
  		}
  	});
}

//订单商品修改
$("#ajax_order_product_modify").on('closed.modal.amui', function(){
	$(this).find("input[name='order_product_id']").val('0');
	$(this).find("input[name='order_id']").val('0');
	$(this).find("select option[value=0]").prop('selected',true);
	$("#ajax_order_product_modify select[name='order_product_picker']").trigger('changed.selected.amui');
	$("#ajax_order_product_modify select[name='order_product_qc']").trigger('changed.selected.amui');
	$(this).find("table:eq(0) thead").html('');
});


function ajax_order_product_modify(order_product_id){
	var order_id = document.getElementById("order_id").value;
	$.ajax({
		url: admin_webroot+"orders/ajax_order_product_modify",
		type:"get",
		data:{'order_id':order_id,'order_product_id':order_product_id},
		dataType:"json",
		success: function(data){
			if(data.code=='1'){
				$("#ajax_order_product_modify input[name='order_id']").val(order_id);
				$("#ajax_order_product_modify input[name='order_product_id']").val(order_product_id);
				$("#ajax_order_product_modify").modal('open');
				var modify_table=$("#ajax_order_product_modify form div:eq(0)");
				modify_table.html('');
				var resource_info=data.data.resource_info;
				var order_product_additional=data.data.order_product_additional;
				var additional_info={};
				if(typeof(order_product_additional.value)!='undefined'){
					additional_info=JSON.parse(order_product_additional.value);
				}
				$.each(resource_info,function(index,item){
					var additional_value=typeof(additional_info[index])!='undefined'?additional_info[index]:'';
					var tr_html="<div class='am-cf' style='padding-top:1rem;padding-bottom:1rem;border-bottom:1px solid #ddd;'>";
					tr_html+="<div class='am-u-lg-3 am-u-md-3 am-u-sm-4' style='font-weight:700;'>"+item+"</div>";
					tr_html+="<div class='am-u-lg-9 am-u-md-9 am-u-sm-8'><input type='text' name='data["+index+"]' value='"+additional_value+"' /></div>";
					tr_html+="</div>";
					modify_table.append(tr_html);
				});
				var order_product_picker=data.data.order_product.picker;
				var order_product_QC=data.data.order_product.QC;
				$("#ajax_order_product_modify select[name='order_product_picker'] option[value='"+order_product_picker+"']").prop("selected",true);
				$("#ajax_order_product_modify select[name='order_product_qc'] option[value='"+order_product_QC+"']").prop("selected",true);
				$("#ajax_order_product_modify select[name='order_product_picker']").trigger('changed.selected.amui');
				$("#ajax_order_product_modify select[name='order_product_qc']").trigger('changed.selected.amui');
			}else{
				alert(data.message);
			}
  		}
  	});
}

function ajax_order_product_modify_submit(btn){
	var postForm=$(btn).parents('form');
	var postData=postForm.serialize();
	$.ajax({
		url: admin_webroot+"orders/ajax_order_product_modify",
		type:"POST",
		data:postData,
		dataType:"json",
		success: function(data){
			if(data.code=='1'){
				order_reflash(true,data.total,data.need_pay,data.insure_fee);
				order_status_select_reload();
			}else{
				alert(data.message);
			}
  		}
  	});
}

//订单管理员修改
var default_order_manager=$("select[name='order_manager']").val();
function order_manager_modify(select){
	var order_id = document.getElementById("order_id").value;
	var order_manager=$(select).val();
	if(order_manager==default_order_manager)return false;
	$.ajax({
		type: "POST",
		url:admin_webroot+'orders/ajax_batch_order_manager',
		data:{'order_manager':order_manager,'order_ids':order_id},
		dataType:"json",
		success: function(data) {
			try{
				alert(data.message);
				if(data.code=='1'){
					default_order_manager=null;
					window.location.reload();
				}
			}catch(e){
				alert(j_object_transform_failed);
			}
		}
	});
}

//订单商品服务类型
function order_product_service_type_modify(checkbox,order_product_id){
	var order_id = document.getElementById("order_id").value;
	var service_type_list=[];
	$(checkbox).parents('span').find("input[type='checkbox']:checked").each(function(){
		service_type_list.push($(this).val());
	});
	var service_type=service_type_list.join(',');
	$.ajax({
		type: "POST",
		url:admin_webroot+'orders/order_product_service_type_modify',
		data:{'order_id':order_id,'order_product_id':order_product_id,'service_type':service_type},
		dataType:"json",
		success: function(data) {
			
			try{
				if(data.code=='1'){
					
					order_reflash(true,data.total,data.need_pay,data.insure_fee);

				}else{
					alert(data.message);
				}
			}catch(e){
				alert(j_object_transform_failed);
			}
		}
	});
}

function order_best_time(){
	var order_best_time='';
	order_best_time=document.getElementById('select_best_date').value;
	if(document.getElementById('select_best_time').value!=''){
		order_best_time+=" "+document.getElementById('select_best_time').value;
	}
	document.getElementById('order_best_time').value=order_best_time.trim();
}

//弹层功能实现
$(document).ready(function(){
	$("#order_search").on('click','div div #add_product_button',function(){
	});
	$(".curtain").on('click',function(){
		$("#order_search").css("display","none");
		$(".curtain").css("display","none");
	});
	$("#order_product_div").on('click','#order_search_btn',function(){
		$("#order_search").modal();
	});
});

//头像上传功能
$("#basic_info").on("click","#avatar_img01_priview",function(){
	$('#avatar_img01').click();  
});
$("#basic_info").on("click","#avatar_img02_priview",function(){
	$('#avatar_img02').click();  
});
$("#basic_info").on("click","#avatar_img03_priview",function(){
	$('#avatar_img03').click();  
});
$("#basic_info").on("click","#avatar_img04_priview",function(){
	$('#avatar_img04').click();  
});
$("#basic_info").on("click","#avatar_img05_priview",function(){
	$('#avatar_img05').click();  
});
$("#basic_info").on("click","#avatar_img06_priview",function(){
	$('#avatar_img06').click();  
});

//解决浮动的问题
if(window.screen.availWidth<640){
	
	$("#order_reffer_label").css("width","32.6%");
	
}else{
	
	$("#order_reffer_label").css("width","16.6%");
	$("#invoice_h").css("min-height","40px");
}

$('#select_best_date').on('changeDate.datepicker.amui', function(event) {
      order_best_time();
});
<?php if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false){ ?>
ajax_wechat_sign();
<?php } ?>
function ajax_wechat_sign(){
	 $.ajax({  
        url : "<?php echo $html->url('/open_models/ajax_wechat_sign') ?>",
        type : "get", 
        dataType: "json",  
        data: {  
           
        },  
        success : function (data) {
        if(typeof(data.wechat_config)=="undefined")return false;
		wx.config({
			debug: false,
			appId: data.wechat_config.appId,
			timestamp: data.wechat_config.timestamp,
			nonceStr: data.wechat_config.nonceStr,
			signature: data.wechat_config.signature,
			jsApiList: [
				'checkJsApi',
				'onMenuShareTimeline',
				'onMenuShareAppMessage',
				'scanQRCode'
			  ]
		});
		
		wx.ready(function () {
			document.getElementById('scan').onclick=function(){
				wx.scanQRCode({
					needResult:1,//0:微信处理，1:自行处理
					scanType:['qrCode','barCode'],
					success:function(data){
						
						if(typeof(data.resultStr)!="undefined"){
							$("#product_number").val(data.resultStr);
							$("#modify_pro_number").click();
							// alert("扫描结果:"+data.resultStr);
						}
					}
				});
			};
		});
		
		wx.error(function (res) {
		  	alert(res.errMsg);
		});
	        }  
	    }); 
}

function ajax_logistic_remove(order_id,shipment_id){
	$.ajax({
		type: "POST",
		url:admin_webroot+'orders/ajax_order_cancel_delivered',
		data:{'order_id':order_id,'shipment_id':shipment_id},
		dataType:"json",
		success: function(data) {
			if(data.code == 1){

			}else{
				alert(data.message);
			}
		}
	});
}



</script>