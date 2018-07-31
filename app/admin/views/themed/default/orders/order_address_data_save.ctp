<?php if($action_type=="user"){ ?>
<!-- 移动端 -->
					<div class="am-form-group">
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-3" ><?php echo '服务类型'; ?>:</div>
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
			      <div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['order_status']?> :</div>
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
								<?php if(isset($operator_list)&&sizeof($operator_list)>0){foreach($operator_list as $k=>$v){ ?>
								<option value="<?php echo $k; ?>" <?php echo isset($order_info['Order']['order_manager'])&&$order_info['Order']['order_manager']==$k?'selected':''; ?>><?php echo $v; ?></option>
								<?php }} ?>
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
		    <!--
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
			-->
				<div class="am-form-group" style="margin-top:1rem;">
			      <div id="order_user_label" class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="margin-top:0.4rem;"><?php echo $ld['order_user']?></div>
			      <div class="am-u-lg-10 am-u-md-10 am-u-sm-9" style="max-width:50rem;">
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
			  	  <div class="am-u-lg-1 am-u-md-1 am-u-sm-3"><?php echo $ld['real_name']; ?></div>
			  	  <div class="am-u-lg-5 am-u-md-5 am-u-sm-9"><input type="text" id="create_user_name" style="max-width:17rem;" value="" /></div>

			  	  <div class="am-u-lg-1 am-u-md-1 am-u-sm-3" style="margin-top:1rem;"><?php echo $ld['mobile']; ?></div>
			  	  <div class="am-u-lg-5 am-u-md-5 am-u-sm-9" style="margin-top:1rem;"><input type="text" id="create_user_mobile" style="max-width:17rem;" value="" /></div>
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

		  <!-- 结束 -->
<script type="text/javascript">
    user_address_obj=<?php echo $user_addresses_json; ?>;
    var RegionList=<?php echo $RegionList; ?>;
    var sel_address_obj=document.getElementById('sel_address');
    sel_address_obj.innerHTML="";
    var sel_opt = document.createElement("OPTION");
	sel_opt.value = "";
	sel_opt.text = j_please_select;
	sel_address_obj.options.add(sel_opt);
    
    if(typeof(user_address_obj)=="object"&&typeof(RegionList)=="object"){
    	var sel_option_txt="";
    	for(var i=0;i<user_address_obj.length;i++){
    		sel_option_txt="";
    		var country=typeof(RegionList[user_address_obj[i].UserAddress.country])!="undefined"?RegionList[user_address_obj[i].UserAddress.country]:'';
    		var province=typeof(RegionList[user_address_obj[i].UserAddress.province])!="undefined"?RegionList[user_address_obj[i].UserAddress.province]:'';
    		var city=typeof(RegionList[user_address_obj[i].UserAddress.city])!="undefined"?RegionList[user_address_obj[i].UserAddress.city]:'';
    		var district=typeof(RegionList[user_address_obj[i].UserAddress.district])!="undefined"?RegionList[user_address_obj[i].UserAddress.district]:'';
    		sel_option_txt+=user_address_obj[i].UserAddress.consignee+",";
    		sel_option_txt+=country+",";
    		sel_option_txt+=province+",";
    		sel_option_txt+=city+",";
    		sel_option_txt+=district+",";
    		sel_option_txt+=user_address_obj[i].UserAddress.address+",";
    		sel_option_txt+=user_address_obj[i].UserAddress.zipcode;
    		//alert(sel_option_txt);
    		var sel_opt = document.createElement("OPTION");
    		sel_opt.value = i;
			sel_opt.text = sel_option_txt;
			sel_address_obj.options.add(sel_opt);
    	}
    }
    
</script>
<?php }else{ ?>

	<!-- 开始 -->
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
  <!-- 结束 -->

<script type="text/javascript">
var user_address_obj = <?php echo $user_addresses_json;?>;
getRegions(0,'',"<?php echo isset($order_info['Order'])?$order_info['Order']['country']:'' ?>");

getRegions(<?php echo $order_country_id; ?>,'country',"<?php echo isset($order_info['Order'])?$order_info['Order']['province']:'' ?>");

getRegions(<?php echo $order_province_id; ?>,'province',"<?php echo isset($order_info['Order'])?$order_info['Order']['city']:'' ?>");

$('#select_best_date').datepicker({theme: 'success'}).on('changeDate.datepicker.amui', function(event) {
      order_best_time();
});
</script>
<?php }?>
<script type="text/javascript">
	order_status_select_reload();
</script>		