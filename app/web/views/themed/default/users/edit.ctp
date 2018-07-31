<style type='text/css'>
.am-form-detail select,.am-form-detail input[type="text"]{border-radius:3px;}
.am-form-success,.am-form-success label{color:blue}
.am-selected-text{color:#000;}
select{display: none!important;}
.am-selected.am-dropdown{margin-bottom: 10px;}
@media only screen and (max-width: 640px){
	#email_modal [class*=am-u-]{padding-right:0px;padding-left:0px;}
}
</style>
<script src="/plugins/ajaxfileupload.js" type="text/javascript"></script>
<?php echo $htmlSeevia->js(array("region")); ?>
<div style="text-align:left;font-size:20px;border-bottom:1px solid #ccc;padding-bottom:1rem;margin-top:1%;padding-left:5px;margin-bottom:1rem;" >
  <span style="float:left;"><?php echo $ld['account_profile'] ?></span>
  <div class="am-cf"></div>
</div>
<div class="am-u-user-edit">
	<?php echo $form->create('/users',array('action'=>'edit','id'=>'user_edit_form','class'=>' am-form am-form-horizontal','name'=>'user_edit','type'=>'POST','onsubmit'=>"return(check_form(this));"));?>
	<input type="hidden"  name="data[UserAddress][id]" value="<?php echo isset($user_list['User']['UserAddress']['UserAddress'])?$user_list['User']['UserAddress']['UserAddress']['id']:"";?>"/>
	<input type="hidden"  name="data[Users][id]"  value="<?php echo  $user_list['User']['id'];?>"/>
	<div class="am-form-detail">
		<div class="am-form-group am-margin-top-lg am-margin-bottom-lg">
			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['user_id'] ?></label>
			<div class="am-u-lg-3 am-u-md-6 am-u-sm-6 am-padding-top-xs"><?php echo $user_list["User"]["user_sn"];?>&nbsp;</div>
			<div class="am-u-lg-4 am-u-md-3 am-u-sm-2"><button type='button' data-am-modal="{target: '#user_sn_modal', closeViaDimmer: 0}"  class="am-btn am-btn-sm am-btn-primary"><?php echo trim($user_list["User"]["user_sn"])==''?$ld['edit']:$ld['modify']; ?></button></div>
		</div>
        
	    	<div class="am-form-group">
	          	<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label real_name" ><?php echo $ld['real_name'] ?></label>
	          	<div class="am-u-lg-8 am-u-md-9 am-u-sm-8"><input type="text" name="data[Users][first_name]" value="<?php echo $user_list['User']['first_name'];?>" style="margin-left:0;" />
			  &nbsp;<em style="margin-top: -3px;"><font color="red">*</font></em></div>
	        </div>
		<div class="am-form-group">
			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" ><?php echo $ld['nickname'] ?></label>
			<div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
			<input type="text"   name="data[Users][name]" id="account" value="<?php echo $user_list['User']['name'];?>" style="margin-left:0;" /><em style="margin-top: -3px;"><font color="red">*</font><font></font></em>
			</div>
		</div>
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" ><?php echo $ld['user_gender'] ?></label>
          <div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
    		<select data-am-selected name="data[Users][sex]">
    			<option value='0'><?php echo $ld['privacy'] ?></option>
    			<option value='1' <?php echo $user_list['User']['sex']==1?'selected':'';?>><?php echo $ld['user_male'] ?></option>
    			<option value='2' <?php echo $user_list['User']['sex']==2?'selected':'';?>><?php echo $ld['user_female'] ?></option>
    		</select>
          </div>
        </div>
    	<div class="am-form-group">
          	<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" >Email</label>
          	<div class="am-u-lg-3 am-u-md-6 am-u-sm-6 am-padding-top-xs"><?php echo $user_list['User']['email'];?></div>
            	<div class="am-u-lg-4 am-u-md-3 am-u-sm-2"><button type='button' data-am-modal="{target: '#email_modal', closeViaDimmer: 0}" class="am-btn am-btn-sm am-btn-primary" style="display: inline-block;margin-left: 5px;"><?php if($user_list['User']['email']!=''){echo '更换';}else{echo '绑定';} ?></button></div>
        </div>
        <div class="am-form-group">
          	<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" ><?php echo $ld['mobile'] ?></label>
          	<div class="am-u-lg-3 am-u-md-6 am-u-sm-6 am-padding-top-xs"><?php echo $user_list['User']['mobile'];?></div>
            	<div class="am-u-lg-4 am-u-md-3 am-u-sm-2"><button type='button' data-am-modal="{target: '#mobile_modal', closeViaDimmer: 0}" class="am-btn am-btn-sm am-btn-primary" style="display: inline-block;margin-left: 5px;"><?php if($user_list['User']['mobile']!=''){echo '更换';}else{echo '绑定';} ?></button></div>
        </div>
        <div class="am-form-group" style="margin-bottom:0px;">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['region'] ?></label>
          <div class="am-u-lg-8 am-u-md-9 am-u-sm-8 "><input type="hidden" id="local" value="<?php echo LOCALE; ?>" /><span id="regionsupdate">
            		<span class='am-hide'>
					<select data-am-selected="{noSelectedText:'请选择',maxHeight:100}" gtbfieldid="1" name="data[Address][RegionUpdate][0]" id="region" onchange="reload_region_1(this)">
						<option><?php echo $ld['state_province'] ?></option>
						<option>...</option>
					</select>
				</span>
				<select data-am-selected="{noSelectedText:'请选择',maxHeight:100}" gtbfieldid="2" name="data[Address][RegionUpdate][1]" onchange="reload_region_1(this)">
					<option><?php echo $ld['city'] ?></option>
					<option>...</option>
				</select>
				<select data-am-selected="{noSelectedText:'请选择',maxHeight:100}" gtbfieldid="3" name="data[Address][RegionUpdate][2]" onchange="reload_region_1(this)">
					<option><?php echo $ld['counties'] ?></option>
					<option>...</option>
				</select>
				</span><em><font color="red"></font><font></font></em>
          </div>
        </div>
        <div class="am-form-group" style="margin-top: 10px;">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
          <div class="am-u-lg-8 am-u-md-9 am-u-sm-8 ">
          <select data-am-selected name="data[UserAddress][address_type]">
            <option value="0" <?php if(isset($user_list['User']['UserAddress']['UserAddress'])){ if($user_list['User']['UserAddress']['UserAddress']['address_type']==0){echo "selected='selected'";}else{echo "";}}else{echo "selected='selected'";}?>>
              <?php echo $ld['home_address']  ?>
            </option>
            <option value="1" <?php if(isset($user_list['User']['UserAddress']['UserAddress'])){ if($user_list['User']['UserAddress']['UserAddress']['address_type']==1){echo "selected='selected'";}else{echo "";}}else{echo "selected='selected'";}?>>
              <?php echo $ld['company_address']  ?>
            </option>
            <option value="2" <?php if(isset($user_list['User']['UserAddress']['UserAddress'])){ if($user_list['User']['UserAddress']['UserAddress']['address_type']==2){echo "selected='selected'";}else{echo "";}}else{echo "selected='selected'";}?>>
              <?php echo $ld['school_address']  ?>
            </option>
            <option value="3" <?php if(isset($user_list['User']['UserAddress']['UserAddress'])){ if($user_list['User']['UserAddress']['UserAddress']['address_type']==3){echo "selected='selected'";}else{echo "";}}else{echo "selected='selected'";}?>>
              <?php echo $ld['other']  ?>
            </option>
          </select>
          </div>
          
        </div>
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['address'] ?></label>
          <div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
<input class="detail" type="text" name="data[UserAddress][address]" value="<?php echo isset($user_list['User']['UserAddress']['UserAddress'])?$user_list['User']['UserAddress']['UserAddress']['address']:"";?>" style="margin-left:0;" />
				<em><font color="red"></font><font></font></em>
          </div>
        </div>
    	<div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['address_to'] ?></label>
          <div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
			<input class="detail" type="text" name="data[UserAddress][sign_building]" 
					value="<?php echo isset($user_list['User']['UserAddress']['UserAddress'])?$user_list['User']['UserAddress']['UserAddress']['sign_building']:"";?>" style="margin-left:0;" /><em><font color="red"></font><font></font></em>
          </div>
        </div>
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['zip'] ?></label>
          <div class="am-u-lg-4 am-u-md-9 am-u-sm-8">
			<input type="text"  name="data[UserAddress][zipcode]" maxlength="6" chkRules="zip_code:<?php echo $ld['zipcode_incorrectly']?>"  value="<?php echo isset($user_list['User']['UserAddress']['UserAddress'])?$user_list['User']['UserAddress']['UserAddress']['zipcode']:"";?>" style="margin-left:0;" />
				<em><font color="red"></font><font></font></em>
          </div>
        </div>
        
        <?php if(isset($review_configs)&&!empty($review_configs)){ ?>
        <div class="am-panel-collapse am-collapse am-in user_config_data">
            <div class="am-panel-bd">
                <div class="am-panel am-panel-default">
                    <div class="am-panel-hd">
				      <h4 class="am-panel-title">用户认证</h4>
				    </div>
                </div>
                <div class="am-panel-collapse">
			    	<div>
                        <?php foreach($review_configs as $gk=>$gv){ if(empty($gv)){continue;} ?>
                            
                            <div class="am-panel am-panel-default">
            	    			<div class="am-panel-hd">
            				      <h4 class="am-panel-title"><?php echo isset($user_config_group_list[$gk])?$user_config_group_list[$gk]:'未分组'; ?></h4>
            				    </div>
				    			<div class="am-panel-collapse" style="margin-top: 17px;">
                                        <div class="am-panel-bd">
                                           <?php foreach($gv as $v){ ?>
                                           
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-text-left am-form-label" ><?php echo $v['UserConfigI18n']['name']; ?></label>
          <div class="am-u-lg-8 am-u-md-9 am-u-sm-8" style="padding-left:0;" ><?php
            $user_config_id=isset($user_review_data[$v['UserConfig']['type']][$v['UserConfig']['code']])?$user_review_data[$v['UserConfig']['type']][$v['UserConfig']['code']]['id']:0;
            $user_config_value=isset($user_review_data[$v['UserConfig']['type']][$v['UserConfig']['code']])?$user_review_data[$v['UserConfig']['type']][$v['UserConfig']['code']]['value']:$v['UserConfig']['value'];
            $user_config_values_arr=array();
            if(!empty($v['UserConfigI18n']['user_config_values'])){
                $user_config_values_arr=split("\r\n",$v['UserConfigI18n']['user_config_values']);
            }
            if(!empty($user_config_values_arr[0])){
        		foreach($user_config_values_arr as $selk=>$selv){
        			if(empty($selv)){continue;}
        			$selv_txt_arr=split(':',$selv);
        			if(empty($selv_txt_arr[1])){continue;}
        			$user_config_values[$selv_txt_arr[0]]=$selv_txt_arr[1];
        		}
        	}?>
            <input type="hidden" name="data[UserConfig][<?php echo $v['UserConfig']['type']; ?>][<?php echo $v['UserConfig']['code']; ?>][id]" value="<?php echo $user_config_id; ?>" />
            <?php 
                if($v['UserConfig']['value_type']=='textarea'){ ?>
					<textarea name="data[UserConfig][<?php echo $v['UserConfig']['type']; ?>][<?php echo $v['UserConfig']['code']; ?>]" <?php echo $v['UserConfig']['is_required']=='1'?'required':''; ?>><?php echo $user_config_value; ?></textarea>
			<?php }else if($v['UserConfig']['value_type']=='radio'){ foreach($user_config_values as $k=>$v){ ?>
					<label class="am-radio-inline"><input type="radio" name="data[UserConfig][<?php echo $v['UserConfig']['type']; ?>][<?php echo $v['UserConfig']['code']; ?>][value]" value="<?php echo $kk; ?>" <?php echo $user_config_value==$kk?" checked='checked'":""; ?> <?php echo $v['UserConfig']['is_required']=='1'?'required':''; ?>><?php echo $vv; ?></label>
					<?php } ?>
			<?php }else if($v['UserConfig']['value_type']=='select'){ ?>
					<select name="data[UserConfig][<?php echo $v['UserConfig']['type']; ?>][<?php echo $v['UserConfig']['code']; ?>][value]" <?php echo $v['UserConfig']['is_required']=='1'?'required':''; ?>>
						<?php foreach($user_config_values as $kk=>$vv){ ?>
						<option value="<?php echo $kk; ?>" <?php echo $user_config_value==$kk?" selected='selected'":""; ?>><?php echo $vv; ?></option>
						<?php } ?>
					</select>
			<?php }else if($v['UserConfig']['value_type']=='file'){ ?>
                    <span><input type="hidden" name="data[UserConfig][<?php echo $v['UserConfig']['type']; ?>][<?php echo $v['UserConfig']['code']; ?>][value]" value="<?php echo $user_config_value; ?>" <?php echo $v['UserConfig']['is_required']=='1'?'required':''; ?>><input type="file" class="am-fl" style="margin-top:5px;width:40%" id="<?php echo $v['UserConfig']['type'].'_'.$v['UserConfig']['code']; ?>" name="<?php echo $v['UserConfig']['type'].'_'.$v['UserConfig']['code']; ?>" onchange="ajax_upload_files(this,'<?php echo $v['UserConfig']['type'].'_'.$v['UserConfig']['code']; ?>')"><?php if($v['UserConfig']['is_required']=='1'){ ?><!-- <em class="am-fl" style="top:5px;"><font color="red">*</font><font></font></em> --><?php } ?>
                        <?php if(!empty($user_config_value)&&file_exists(WWW_ROOT.$user_config_value)){
                                $user_file_type=mime_content_type(WWW_ROOT.$user_config_value);
                                if(strpos($user_file_type,'image')!==false){ ?>
                                <p class='user_config_file'><?php echo $html->image($user_config_value);  ?><a href='javascript:void(0);' onclick="clean_user_file(this,'<?php echo $user_config_value; ?>')"><?php echo $ld['delete'] ?></a></p>
                        <?php   }else{ ?>
                            <p class='user_config_file'><a target='_blank' href="<?php echo $user_config_value; ?>">下载</a><a href="javascript:void(0);" onclick="clean_user_file(this,'<?php echo $user_config_value; ?>')"><?php echo $ld['delete']; ?></a></p>
                        <?php }} ?>
                        </span>
            <?php }else if($v['UserConfig']['value_type']=='numbertext'){ ?>
                    <input type="text" class="js-pattern-number" name="data[UserConfig][<?php echo $v['UserConfig']['type']; ?>][<?php echo $v['UserConfig']['code']; ?>][value]" value="<?php echo $user_config_value; ?>" <?php echo $v['UserConfig']['is_required']=='1'?'required':''; ?> style="margin-left:0;" />
            <?php }else{ ?>
					<input type="text" chkRules="nnull:<?php echo $ld['no_empty_company']?>;"  name="data[UserConfig][<?php echo $v['UserConfig']['type']; ?>][<?php echo $v['UserConfig']['code']; ?>][value]" value="<?php echo $user_config_value; ?>" <?php echo $v['UserConfig']['is_required']=='1'?'required':''; ?> style="margin-left:0;" />
			<?php } if($v['UserConfig']['is_required']=='1'&&$v['UserConfig']['value_type']!='file'){ ?><em style="margin-top:-3px"><font color="red">*</font><font></font></em><?php } ?>
            </div>
        </div>
                                           <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
          <div class="am-u-lg-8 am-u-md-9 am-u-sm-8 am-text-left" style="margin-left:18px">
                <!-- <?php if(isset($configs['enable_auditing'])&&$configs['enable_auditing']=='1'&&$user_list['User']['verify_status']!='1'&&$user_list['User']['verify_status']!='3'){ ?><input class="am-btn am-btn-secondary am-btn-primary am-btn-sm" type="submit" name="submit_review" value="<?php echo $ld['submit_review'] ?>" /><?php } ?> -->
            	<input style="width:100px;height:32px" class="am-btn am-btn-secondary am-btn-primary am-btn-sm" name="user_save" onclick="user_edit_save()" type="button" value="<?php echo $ld['user_save'] ?>" />
          </div>
        </div>
	</div>
	<?php echo $form->end();?>
</div>

<!-- 修改用户名弹层 -->
<div class="am-modal am-modal-no-btn" tabindex="-1" id="user_sn_modal">
	<div class="am-modal-dialog">
		<div class="am-modal-hd"><?php echo $ld['modify'] ?>
			<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
		</div>
		<div class="am-modal-bd">
			<form method='POST' class='am-form am-form-horizontal'>
				<div class="am-form-group am-margin-top-lg">
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-padding-top-xs"><?php echo $ld['user_id'] ?></label>
					<div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
						<input type="text" name="user_sn" class="am-form-field" maxlength='15'>
					</div>
					<div class='am-cf'></div>
				</div>
				<div class="am-form-group">
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</div>
					<div class="am-u-lg-9 am-u-md-9 am-u-sm-8 am-text-left">
						<button type='button' class="am-btn am-btn-sm am-btn-success" onclick="ajax_modify_user_sn(this)"><?php echo $ld['submit']; ?></button>
					</div>
					<div class='am-cf'></div>
				</div>
				<div class="am-form-group am-margin-bottom-lg">
					<div class='am-text-danger'>&nbsp;</div>
					<div class='am-cf'></div>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- 绑定邮箱弹层 -->
<div class="am-modal am-modal-no-btn" tabindex="-1" id="email_modal">
	<div class="am-modal-dialog">
		<div class="am-modal-hd">绑定邮箱
			<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
		</div>
		<div class="am-modal-bd">
			<form method='POST' class='am-form am-form-horizontal'>
				<?php if($user_list['User']['email']!=''){ ?>
				<div class="am-form-group am-margin-top-lg am-padding-top-lg">
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-padding-top-xs">原Email</label>
					<div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
						<input type="text" id="old_bind_email" value="<?php echo $user_list['User']['email'];?>" class="am-form-field" readonly>
					</div>
					<div class='am-cf'></div>
				</div>
				<div class="am-form-group">
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-padding-top-xs">验证码</label>
					<div class="am-u-lg-9 am-u-md-9 am-u-sm-8 am-text-left">
						<div class="am-input-group">
							<input type="text" class="am-form-field" name="old_email_code">
							<span class="am-input-group-btn">
								<button class="am-btn am-btn-primary" type="button" onclick="email_code_send('#old_bind_email',this)">获取验证码</button>
							</span>
						</div>
					</div>
					<div class='am-cf'></div>
				</div>
				<div class="am-form-group">
				<?php }else{ ?>
				<div class="am-form-group am-margin-top-lg">
				<?php } ?>
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-padding-top-xs">Email</label>
					<div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
						<input type="text" name="bind_email" class="am-form-field">
					</div>
					<div class='am-cf'></div>
				</div>
				<div class="am-form-group">
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-padding-top-xs">验证码</label>
					<div class="am-u-lg-9 am-u-md-9 am-u-sm-8 am-text-left">
						<div class="am-input-group">
							<input type="text" class="am-form-field" name="bind_email_code">
							<span class="am-input-group-btn">
								<button class="am-btn am-btn-primary" type="button" onclick="email_bind_check(this)">获取验证码</button>
							</span>
						</div>
					</div>
					<div class='am-cf'></div>
				</div>
				<div class="am-form-group">
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</div>
					<div class="am-u-lg-9 am-u-md-9 am-u-sm-8 am-text-left">
						<button type='button' class="am-btn am-btn-sm am-btn-success" onclick='ajax_bind_email(this)'><?php echo $ld['submit']; ?></button>
					</div>
					<div class='am-cf'></div>
				</div>
				<div class="am-form-group am-margin-bottom-lg">
					<div class='am-text-danger'>&nbsp;</div>
					<div class='am-cf'></div>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- 绑定手机弹层 -->
<div class="am-modal am-modal-no-btn" tabindex="-1" id="mobile_modal">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">绑定手机
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
    		<form class='am-form' method="POST">
    			<?php if(isset($user_list['User']['mobile'])&&trim($user_list['User']['mobile'])!=''){ ?>
    			<div class="am-form-group">
    				<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-padding-top-sm">原手机</label>
    				<div class="am-u-lg-6 am-u-md-9 am-u-sm-8">
					<input type="text" class="am-form-field" name="old_mobile" id='old_mobile' value="<?php echo $user_list['User']['mobile']; ?>" readonly />
    				</div>
    				<div class='am-cf'></div>
    			</div>
    			<div class="am-form-group">
    				<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-padding-top-sm"><?php echo $ld['verify_code']; ?></label>
    				<div class="am-u-lg-6 am-u-md-9 am-u-sm-8">
					<div class="am-input-group">
						<input type="text" class="am-form-field" name="old_mobile_code">
						<span class="am-input-group-btn"><button class="am-btn am-btn-default" type="button" onclick="mobile_code_send('#old_mobile',this);"><?php echo $ld['send']; ?></button></span>
					</div>
    				</div>
    				<div class='am-cf'></div>
    			</div>
    			<?php } ?>
    			<div class="am-form-group">
    				<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-padding-top-sm"><?php echo $ld['mobile'] ?></label>
    				<div class="am-u-lg-6 am-u-md-9 am-u-sm-8">
					<input type="text" class="am-form-field" name="bind_mobile" id="bind_mobile" value="" />
    				</div>
    				<div class='am-cf'></div>
    			</div>
    			<div class="am-form-group">
    				<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-padding-top-sm"><?php echo $ld['verify_code']; ?></label>
    				<div class="am-u-lg-6 am-u-md-9 am-u-sm-8">
					<div class="am-input-group">
						<input type="text" class="am-form-field" name="bind_mobile_code">
						<span class="am-input-group-btn"><button class="am-btn am-btn-default" type="button" onclick="bind_mobile_code_send('#bind_mobile',this)"><?php echo $ld['send']; ?></button></span>
					</div>
    				</div>
    				<div class='am-cf'></div>
    			</div>
    			<div class="am-form-group">
    				<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label am-padding-top-sm">&nbsp;</label>
    				<div class="am-u-lg-6 am-u-md-9 am-u-sm-8 am-text-left">
    					<button class='am-btn am-btn-primary am-btn-sm' type='button' onclick="ajax_mobile_bind(this)"><?php echo $ld['submit']; ?></button>
    				</div>
    				<div class='am-cf'></div>
    			</div>
    		</form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var submit_review_flag="<?php echo isset($configs['enable_auditing'])?$configs['enable_auditing']:'0'; ?>";
$(document).ready(function(){
    auto_check_form("user_edit_form",false);
});

$('#user_edit_form').validator({
  validate: function(validity) {
      if($(validity.field).is("input[type='file']")){
        var required_attr=$(validity.field).prop("required");
        if(typeof(required_attr)!='undefined'){
            var filelink=$(validity.field).parent().find("input[type='hidden']").val();
            if(filelink.trim()==""){
                validity.valid = false;
            }
        }
      }
  },
  submit: function(){
    var formValidity = this.isFormValid();
    if(!formValidity&&submit_review_flag=='1'){
        return false;
    }
  }
});

function user_edit_save(){
    var check_form_flag=check_form(document.getElementById("user_edit_form"));
    if(check_form_flag==true){
        document.user_edit.submit();
    }
}

/*
    上传配置数据文件
*/
function ajax_upload_files(inputFile,fileCode){
    var file_link_html="<p class='user_config_file'><a target='_blank' href='FILELINK'>下载</a><a href='javascript:void(0);' onclick=\"clean_user_file(this,'DELFILELINK')\"><?php echo $ld['delete'] ?></a></p>";
    var image_link_html="<p class='user_config_file'><img src='FILELINK'><a href='javascript:void(0);' onclick=\"clean_user_file(this,'DELFILELINK')\"><?php echo $ld['delete'] ?></a></p>";
    var Filehidden=$(inputFile).parent().find("input[type='hidden']");
    $.ajaxFileUpload({
		  // url:"<?php echo $html->url('/users/ajax_upload_files/'); ?>",
      url:web_base+"/users/ajax_upload_files/",
		  secureuri:false,
		  fileElementId:fileCode,
		  dataType: 'json',
          data:{'fileCode':fileCode},
		  success: function (result){
              if(result.code=='1'){
                 $(Filehidden).val(result.file_name);
                 $(Filehidden).parent().find("p.user_config_file").remove();
                 var FileType=result.file_type;
                 if(FileType.indexOf("image")>=0){
                    image_link_html=image_link_html.replace("FILELINK", web_base+result.file_name);
                    image_link_html=image_link_html.replace("DELFILELINK", web_base+result.file_name);
                    $(Filehidden).parent().append(image_link_html);
                 }else{
                    file_link_html=file_link_html.replace("FILELINK", result.file_name);
                    file_link_html=file_link_html.replace("DELFILELINK", result.file_name);
                    $(Filehidden).parent().append(file_link_html);
                 }
              }else{
                alert(result.msg);
              }
		  },
		  error: function (data, status, e)//服务器响应失败处理函数
		  {
		  	  alert('上传失败');
          }
	 });
}

function clean_user_file(FileLink,FileUrl){
    if(confirm(confirm_delete)){
        $.ajax({
      url: web_base+"/users/ajax_remove_files/",
			type:"POST",
			data:{'FileUrl':FileUrl},
			dataType:"json",
			success: function(data){
				if(data.code=='1'){
                    $(FileLink).parent().parent().find("input[type='hidden']").val("");
                    $(FileLink).parent().remove();
                }else{
                    alert(data.msg);
                }
	  		}
	  	});
    }
}

var user_address_data=<?php $user_address_data=isset($user_list['User']['UserAddress']['UserAddress'])?$user_list['User']['UserAddress']['UserAddress']:array();echo json_encode($user_address_data); ?>;
load_region(user_address_data);

function ajax_mobile_bind(btn){
	var post_form=$(btn).parents('form');
	if(document.getElementById('old_mobile')){
		var old_mobile_code=$(post_form).find("input[name='old_mobile_code']").val();
		if(old_mobile_code==''){
			alert('请输入原手机验证码');
			return false;
		}
	}
	var bind_mobile=$(post_form).find("input#bind_mobile").val();
	var bind_mobile_code=$(post_form).find("input[name='bind_mobile_code']").val();
	if(bind_mobile==''){
		alert('请输入手机号');
		return false;
	}
	if(bind_mobile_code==''){
		alert('请输入手机验证码');
		return false;
	}
	$(btn).button('loading');
	$.ajax({
		url: web_base+"/users/ajax_mobile_bind",
		type:"POST",
		dataType:"json", 
		data:post_form.serialize(),
		success: function(data){
			alert(data.msg);
			if(data.code=='1'){
				window.location.reload();
			}
		},
		complete:function(){
			$(btn).button('reset');
		}
	});
}

function bind_mobile_code_send(input_str,btn){
	var mobile=$(input_str).val();
	if(mobile!=''&& /^1[3-9]\d{9}$/.test(mobile)){
		$.ajax({
			url: web_base+"/users/check_input",
			type:"POST",
			dataType:"json", 
			data: {'mobile':mobile,'type_id':mobile},
			success: function(data){
				if(data.error=='0'){
					mobile_code_send(input_str);
				}else{
					alert('手机号已被使用');
				}
			}
		});
	}else if(mobile!=''){
		alert('手机号格式错误');
	}
}

var country = "<?php echo isset($user_list['User']['UserAddress']['UserAddress']['country'])?$user_list['User']['UserAddress']['UserAddress']['country']:''; ?>";
var province = "<?php echo isset($user_list['User']['UserAddress']['UserAddress']['province'])?$user_list['User']['UserAddress']['UserAddress']['province']:''; ?>";
var city = "<?php echo isset($user_list['User']['UserAddress']['UserAddress']['city'])?$user_list['User']['UserAddress']['UserAddress']['city']:''; ?>";

function reload_region_1(region_select){
    var region_id=$(region_select).val();
    var post_data={'parent_id':region_id};
    $(region_select).nextAll("input.region_input").val('').attr('disabled',true).hide();
    $(region_select).nextAll('select').each(function(){
        $(this).html("<option value=''>"+j_please_select+"</option>").attr('disabled',false).show();
    });
    var NextRegionSelect=$(region_select).nextAll('select')[0];
    if(typeof(NextRegionSelect)=='undefined')return;
    if(region_id!=""){
        $.ajax({
            url: web_base+"/regions/index",
            type:"POST",
            data:post_data,
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    var region_list=data.data;
                    if(typeof(region_list[region_id])!='undefined'){
                        $(region_list[region_id]).each(function(index,item){
                            var aa = $("<option></option>").val(item['Region']['id']).text(item['RegionI18n']['name']);
                            if(item['Region']['id']==country||item['Region']['id']==province||item['Region']['id']==city){
                                aa.attr('selected',true);
                            }
                            aa.appendTo(NextRegionSelect);
                        });
                    }
                }else{
                    $(region_select).nextAll('select').each(function(){
                        var NextRegionInput=$(this).next('input.region_input')[0];
                        $(this).attr('disabled',true).hide();
                        if(typeof(NextRegionInput)!='undefined'){
                            $(NextRegionInput).attr('disabled',false).show();
                        }else{
                            var region_name=$(this).attr('name');
                            region_name=typeof(region_name)=='undefined'?'':region_name;
                            $(this).after("<input type='text' style='margin-left:5px;' class='region_input' name='"+region_name+"' value=''/>");
                        }
                    });
                }
            }
        });
    }
}

function email_bind_check(btn){
	var emailForm=$(btn).parents('form');
	var emailInput=$(emailForm).find("input[name='bind_email']");
	var email=$(emailInput).val().trim();
	var reg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w{2,4}$/;
	var MessagDiv=$(emailForm).find('div.am-form-group:last-child div.am-text-danger');
	MessagDiv.html('&nbsp;');
	if(email!=''&&reg.test(email)){
		$.ajax({
			url: web_base+"/users/check_input",
			type:"POST",
			dataType:"json", 
			data: {'email':email,'type_id':email},
			success: function(data){
				if(data.error=='0'){
					email_code_send(emailInput,btn);
				}else{
					MessagDiv.html(data.msg);
				}
			}
		});
	}else{
		MessagDiv.html('邮箱格式错误');
	}
}

function ajax_bind_email(btn){
	var emailForm=$(btn).parents('form');
	var MessagDiv=$(emailForm).find('div.am-form-group:last-child div.am-text-danger');
	MessagDiv.html('&nbsp;');
	if(document.getElementById('old_bind_email')){
		var old_email_code=$(emailForm).find("input[name='old_email_code']").val();
		if(old_email_code==""){
			MessagDiv.html('请输入验证码');
			return false;
		}
	}
	var bind_email=$(emailForm).find("input[name='bind_email']").val().trim();
	var reg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w{2,4}$/;
	if(!(bind_email!=''&&reg.test(bind_email))){
		MessagDiv.html('邮箱格式错误');
		return false;
	}
	var bind_email_code=$(emailForm).find("input[name='bind_email_code']").val().trim();
	if(bind_email_code==""){
		MessagDiv.html('请输入验证码');
		return false;
	}
	$(btn).button('loading');
	var postForm=emailForm.serialize();
	$.ajax({
		url: web_base+"/users/ajax_email_bind",
		type:"POST",
		dataType:"json", 
		data: postForm,
		success: function(data){
			if(data.msg!='')MessagDiv.html(data.msg);
			if(data.code=='1'){
				window.location.reload();
			}
		},
		complete:function(){
			$(btn).button('reset');
		}
	});
}

function ajax_modify_user_sn(btn){
	var UsersnForm=$(btn).parents('form');
	var MessagDiv=$(UsersnForm).find('div.am-form-group:last-child div.am-text-danger');
	MessagDiv.html('&nbsp;');
	var bind_user_sn=$(UsersnForm).find("input[name='user_sn']").val().trim();
	if(bind_user_sn!=''){
		$(btn).button('loading');
		var postdata=UsersnForm.serialize();
		$.ajax({
			url: web_base+"/users/ajax_modify_user_sn",
			type:"POST",
			dataType:"json", 
			data: postdata,
			success: function(data){
				if(data.msg!='')MessagDiv.html(data.msg);
				if(data.code=='1'){
					window.location.reload();
				}
			},
			complete:function(){
				$(btn).button('reset');
			}
		});
	}
}
</script>