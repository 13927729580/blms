<style type='text/css'>
.am-form-label {font-weight: bold;margin-left: 10px;top: 0px;}
.am-form-group{margin-top:10px;}
.user_config_file img{width:120px;height:120px;}
</style>
<script src="<?php echo $webroot; ?>plugins/ajaxfileupload.js" type="text/javascript"></script>

<div class="am-g admin-content am-user  ">
	<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}">
		<ul>
			<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
		<?php if($svshow->check_module('B2C')){ ?>
                    <li><a  href="#user_style"><?php echo $ld['user_template']; ?></a></li>
                    <li><a  href="#users_user_address"><?php echo $ld['users_user_address']?></a></li>
                    <li><a  href="#order"><?php echo $ld['order']?></a></li>
                    <li><a  href="#order_product"><?php echo $ld['order_goods']?></a></li>
                    <li><a  href="#user_coupons_list"><?php echo $ld['my_coupons']?></a></li>
             <?php } ?>
             <?php if(isset($configs['use_point'])&&$configs['use_point']=='1'){ ?>
                    <li><a  href="#user_point_list"><?php echo $ld['point']?></a></li>
             <?php } ?>
            <?php if($svshow->check_module('O2O','Career')){ ?>
                <li><a  href="#work_list">作品列表</a></li>
                <li><a  href="#experience_list">工作经历</a></li>
                <li><a  href="#education_list">教育经历</a></li>
                <?php } ?>
                <?php if($svshow->check_module('LMS')){ ?>
                <li><a  href="#course_list">课程记录</a></li>
                <li><a  href="#evaluation_list">评测记录</a></li>
                 <?php } ?>
                <li><a  href="#contact_list">联系记录</a></li>
                <li><a  href="#user_share">页面分享</a></li>
                 <?php if(isset($Resource_info['user_config_type'])&&!empty($Resource_info['user_config_type'])&&!empty($default_user_config_list)){ ?>
                    <li><a  href="#user_config"><?php echo $ld['user_config']?></a></li>
                <?php } ?>
                <?php if(isset($configs['enable_user_log'])&&$configs['enable_user_log']=='1'){ ?>
                <li><a  href="#user_action_list"><?php echo $ld['operator_logs']; ?></a></li>
                <?php } ?>
		</ul>
	</div>
    <?php echo $form->create('/users',array('action'=>'view/'.isset($user_info["User"]["id"])?$user_info["User"]["id"]:0,'id'=>'user_edit_form','name'=>'user_edit','type'=>'POST','onsubmit'=>"return check_all();"));?>
    <input type="hidden" name="data[User][id]" id="user_id" value="<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0;?>" />
    <div class="am-panel-group admin-content" id="accordion">
        <!-- 编辑按钮区域 -->
        <div class="btnouter am-text-right" data-am-sticky="{top:'15%'}">
            <?php if(isset($user_info['User'])&&$svshow->operator_privilege("users_confirm")){
                if($user_info['User']['verify_status']=='1'){?>
                    <button type="button" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom" onclick="user_verify_status('<?php echo $user_info['User']['id'];?>','3')"><?php echo $ld['verify']; ?></button>
                    <button type="button" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom" onclick="user_verify_status('<?php echo $user_info['User']['id'];?>','2')"><?php echo $ld['turn_down']; ?></button>
                <?php }
                if($user_info['User']['verify_status']=='3'){ ?>
                    <button type="button" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom" onclick="user_verify_status('<?php echo $user_info['User']['id'];?>','4')"><?php echo $ld['unverify']; ?></button>
                <?php } }?>
            <button type="button" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom" onclick="check_prev()"><?php echo $ld['d_submit'] ?></button>
            <button type="reset" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_reset'] ?></button>
        </div>
        <!-- 编辑按钮区域 -->
        <div class="am-panel am-panel-default" id="basic_information">
            <div class="am-panel-hd"><?php echo $ld['basic_information'] ?>&nbsp;<font color="<?php echo isset($user_info['User']['verify_status'])&&$user_info['User']['verify_status']=='2'?'red':(isset($user_info['User']['verify_status'])&&$user_info['User']['verify_status']=='3'?'#5eb95e':''); ?>"><?php echo isset($user_info['User'])&&isset($Resource_info['verify_status'][$user_info['User']['verify_status']])?("[".$Resource_info['verify_status'][$user_info['User']['verify_status']].(!empty($user_info['User']['unvalidate_note'])?" - ".$user_info['User']['unvalidate_note']:'')."]"):''; ?></font>
             
            </div>
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal">
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['user_reffer'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <select id="user_type" name="user_type">
                                <?php if(empty($user_info['User']['user_type'])&&sizeof($user_type)>1){?>
                                    <option value=''><?php echo $ld['please_select'];?></option>
                                <?php }?>
                                <?php foreach ($user_type as $tid=>$t){ ?>
                                    <?php if(!empty($t)){ foreach ($t as $k => $v) {?>
                                        <option value="<?php echo $tid.":".$k;?>" <?php if($user_info['User']['user_type']==$tid && $user_info['User']['user_type_id']==$k )echo "selected"?>><?php echo $user_type_arr[$tid]."--".$v?></option>
                                    <?php }}?>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['member_level'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8" style="position: relative;margin-top:6px;"><span id="showUserRank"><?php $user_rank_id=isset($user_info['User']['rank'])?$user_info['User']['rank']:'0';echo isset($user_rank_data[$user_rank_id])?$user_rank_data[$user_rank_id]:$ld['member']; ?></span>
                            <input type="hidden" id="hid_user_rank" name="data[User][rank]" value="<?php echo $user_rank_id; ?>" />
                            <?php if(isset($rank_list)&&sizeof($rank_list)>0){ ?>
                                <input type="button" value="<?php echo $ld['upgrade']; ?>/<?php echo $ld['ranew'] ?>" id="Upgrade_Renew_btn" onclick="Upgrade_Renew(this)" class="am-btn am-btn-success am-radius am-btn-sm"  />
                            <?php }?>
                        </div>
                    </div>
                    <div class="am-form-group" id="rank_operator">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <select id="user_rank" disabled>
                                <option value="0"><?php echo $ld['please_select']; ?></option>
                                <?php if(isset($rank_list)&&sizeof($rank_list)>0){ foreach($rank_list as $k=>$v){ ?>
                                    <option value="<?php echo $v['UserRank']['id']; ?>"><?php echo $v['UserRankI18n']['name']; ?></option>
                                <?php }} ?>
                            </select>
                            <label><?php echo $ld['app_period'] ?>:</label>
                            <div>
                                <div class="am-u-lg-5 am-u-md-5 am-u-sm-5"><input type="date" name="start_time" id="start_time" value="<?php echo isset($userrank_open_time)?$userrank_open_time:''; ?>" disabled /></div><em>-</em>
                                <div class="am-u-lg-5 am-u-md-5 am-u-sm-5"><input type="date" name="end_time" id="end_time" value="<?php echo isset($userrank_end_time)?$userrank_end_time:''; ?>" disabled /></div><div style="clear:both;"></div>
                            </div>
                            <div style="margin-top:1.2rem;"><input id="set_Upgrade" type="button" value="<?php echo $ld['submit'] ?>" onclick="setUpgrade()" class="am-btn am-btn-success am-radius am-btn-sm" disabled /><input id="no_Upgrade" type="button" value="<?php echo $ld['cancel'] ?>" class="am-btn am-btn-success am-radius am-btn-sm" onclick="noUpgrade()" disabled /></div>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['user_category'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <select name='data[User][category_id]' data-am-selected="{maxHeight:300}">
                                <option value='0'><?php echo $ld['please_select']; ?></option>
                                <?php if(isset($UserCategory_data)&&sizeof($UserCategory_data)>0){foreach($UserCategory_data as $k=>$v){ ?>
                                    <option value="<?php echo $k; ?>" <?php echo isset($user_info['User']['category_id'])&&$user_info['User']['category_id']==$k?'selected':''; ?>><?php echo $v; ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['user_manager'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <select name='data[User][operator_id]' data-am-selected="{maxHeight:300}">
                                <option value='0'><?php echo $ld['please_select']; ?></option>
                                <?php if(isset($Operator_list)&&sizeof($Operator_list)>0){foreach($Operator_list as $v){ ?>
                                    <option value="<?php echo $v['Operator']['id']; ?>" <?php echo isset($user_info['User']['operator_id'])&&$user_info['User']['operator_id']==$v['Operator']['id']?'selected':''; ?>><?php echo $v['Operator']['name']; ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['user_name'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><input type="text" onchange="check_user_sn(this)" name="data[User][user_sn]" id="user_sn" value="<?php echo isset($user_info['User']['user_sn'])?$user_info['User']['user_sn']:'';?>"></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="width:3%;padding-left:0;"><em style="color:red;">*</em></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['real_name'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><input type="text" name="data[User][first_name]" id="user_first_name" value="<?php echo isset($user_info['User']['first_name'])?$user_info['User']['first_name']:'';?>"></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['member_name'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><?php if(isset($user_info['User']['domain'])&&trim($user_info['User']['domain'])==''||!isset($user_info['User'])){?><input type="text" name="data[User][name]" value="<?php echo $user_info['User']['name'];?>"><?php }else{ ?><div style="position: relative;margin-top: 9px;"><input type="hidden" name="data[User][name]" value="<?php echo isset($user_info['User']['name'])?$user_info['User']['name']:'';?>"><?php echo isset($user_info['User']['name'])?$user_info['User']['name']:'';?></div><?php }?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['email'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><input type="text" id="user_email" name="data[User][email]" value="<?php echo isset($user_info['User']['email'])?$user_info['User']['email']:'';?>"/></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="width:3%;padding-left:0;"><em style="color:red;">*</em></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['mobile'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><input type="text" id="user_mobile" name="data[User][mobile]" value="<?php echo isset($user_info['User']['mobile'])?$user_info['User']['mobile']:'';?>"/></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="width:3%;padding-left:0;"><em style="color:red;">*</em></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['gender'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <label class="am-radio-inline"><input type="radio" name="data[User][sex]" <?php echo (isset($user_info['User']['sex'])&&$user_info['User']['sex']=='0')||!isset($user_info['User'])?'checked':'';?> value="0"/><?php echo $ld['secrecy']?></label>
                            <label class="am-radio-inline"><input type="radio" name="data[User][sex]" <?php echo isset($user_info['User']['sex'])&&$user_info['User']['sex']=='1'?'checked':'';?> value="1"/><?php echo $ld['male']?></label>
                            <label  class="am-radio-inline"><input type="radio" name="data[User][sex]" <?php echo isset($user_info['User']['sex'])&&$user_info['User']['sex']=='2'?'checked':'';?> value="2"/><?php echo $ld['female']?></label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['body_height'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <input type='text' name="data[User][height]" value="<?php echo isset($user_info['User']['height'])?$user_info['User']['height']:'';?>" maxlength='6' onkeyup="this.value=this.value.replace(/[^\d.]/g,'');"  />
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['body_weight'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <input type='text' name="data[User][height]" value="<?php echo isset($user_info['User']['body_weight'])?$user_info['User']['body_weight']:'';?>" maxlength='6'  onkeyup="this.value=this.value.replace(/[^\d.]/g,'');" />
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['birthday'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <input type="text" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" class="am-form-field am-input-sm" name="data[User][birthday]" value="<?php echo isset($user_info['User']['birthday'])?$user_info['User']['birthday']:'';?>" />
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['magazine_user'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <input type='hidden' name="data[User][email_flag]" value="0"  />
                            <label class='am-checkbox am-success'>
                                <input type='checkbox' name="data[User][email_flag]" value="1" <?php echo isset($user_info['User'])&&$user_info['User']['email_flag']=='1'?'checked':''; ?> />
                            </label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['discount'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><input type="text" id="admin_note2" name="data[User][admin_note2]" value="<?php echo isset($user_info['User']['admin_note2'])?$user_info['User']['admin_note2']:'';?>" /></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['note2'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><textarea id="user_admin_note" name="data[User][admin_note]" /><?php echo isset($user_info['User']['admin_note'])?$user_info['User']['admin_note']:'';?></textarea></div>
                    </div>

                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">&nbsp;</div>
                    </div>

                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['new_password'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><input type="password" id="user_new_password" name="data[User][new_password]"/></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="width:3%;padding-left:0;"><em style="color:red;">*</em></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['confirm_password_again'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><input type="password" id="user_new_password2" name="data[User][new_password2]"/></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="width:3%;padding-left:0;"><em style="color:red;">*</em></div>
                    </div>

                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">&nbsp;</div>
                    </div>
                    <?php if($svshow->operator_privilege("users_recharge")){ ?>
                        <div class="am-form-group">
                            <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['available_balance'] ?></label>
                            <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"  style="position: relative;margin-top:5px;"><span style="color:red;"><?php echo isset($user_info['User']['balance'])?$user_info['User']['balance']:'0';?></span> <?php echo $ld['app_yuan']; ?> <input type="button" value="<?php echo $ld['recharge_consumption']; ?>" id="recharge_consumption" onclick="Recharge_Consumption(this)" class="am-btn am-btn-success am-radius am-btn-sm"  /></div>
                        </div>

                        <div class="am-form-group" id="fund_flow">
                            <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['fund_flow']?></label>
                            <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                                <label class="am-radio-inline"><input type="radio" name="balance_type" value="1" checked><?php echo $ld['plus']?></label>
                                <label class="am-radio-inline"><input type="radio" name="balance_type" value="0"><?php echo $ld['minus'] ?></label>
                                <input type="text" style="width:30%;ime-mode:disabled;" id="user_balance" name="balance" value="0" />
                                <input type="hidden" name="data[User][balance]" value="<?php echo isset($user_info['User']['balance'])?$user_info['User']['balance']:'0';?>" />
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(isset($configs['use_point'])&&$configs['use_point']=='1'){ ?>
                        <div class="am-form-group">
                            <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['point']?></label>
                            <div class="am-u-lg-1 am-u-md-2 am-u-sm-3"  style="padding-top:8px;"><?php echo isset($user_info['User']['point'])?$user_info['User']['point']:'0';?></div>
                            <div class="am-u-lg-8 am-u-md-6 am-u-sm-6">
                                <label class="am-radio-inline" style="padding-top:2px"><input type="radio" name="point_type" value="1" checked><?php echo $ld['plus']?></label>
                                <label class="am-radio-inline" style="padding-top:2px"><input type="radio" name="point_type" value="0"><?php echo $ld['minus']?></label>
                                <label class="am-radio-inline" style="padding-top:2px"><input type="text" style="width:100px;" id="user_point" name="point" value="0"/></label>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['registration_time'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8" style="position: relative;margin-top:10px;"><?php echo isset($user_info['User']['point'])?$user_info['User']['created']:'-';?></div>
                    </div>

                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['user_last_login_time'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"  style="position: relative;margin-top:10px;"><?php echo isset($user_info['User']['last_login_time'])?$user_info['User']['last_login_time']:'-';?></div>
                    </div>

                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">&nbsp;</div>
                    </div>

                    <div class="am-form-group user_avatar">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['avatar']; ?></label>
                        <div class="am-u-lg-10 am-u-md-9 am-u-sm-9">

                            <div class="am-fl am-u-lg-4 am-u-md-4 am-u-sm-4">
                                <?php echo $html->image(isset($user_info['User']['img01']) && $user_info['User']['img01']!=''?$user_info['User']['img01']:'/theme/default/img/no_head.png',array('id'=>'avatar_img01_priview')); ?>
                                <input style="margin:8px 0;max-width:150px" type="file" id="avatar_img01" name="avatar_img01" onchange="ajaxFileUpload(<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0; ?>,'avatar_img01')" />
                                <input type="hidden" id="avatar_img01_hid" name="data[User][img01]" value="<?php echo isset($user_info['User']['img01'])?$user_info['User']['img01']:''; ?>" />
                            </div>
                            <div class="am-fl am-u-lg-4 am-u-md-4 am-u-sm-4" >
                                <?php echo $html->image(isset($user_info['User']['img02']) && $user_info['User']['img02']!=''?$user_info['User']['img02']:'/theme/default/img/no_head.png',array('id'=>'avatar_img02_priview')); ?>
                                <input style="margin:8px 0;max-width:150px" type="file" id="avatar_img02" name="avatar_img02" onchange="ajaxFileUpload(<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0; ?>,'avatar_img02')" />
                                <input type="hidden" id="avatar_img02_hid" name="data[User][img02]" value="<?php echo isset($user_info['User']['img02'])?$user_info['User']['img02']:''; ?>" />
                            </div>

                            <div class="am-fl am-u-lg-4 am-u-md-4 am-u-sm-4">
                                <?php echo $html->image(isset($user_info['User']['img03']) && $user_info['User']['img03']!=''?$user_info['User']['img03']:'/theme/default/img/no_head.png',array('id'=>'avatar_img03_priview')); ?>
                                <input style="margin:8px 0;max-width:150px" type="file" id="avatar_img03" name="avatar_img03" onchange="ajaxFileUpload(<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0; ?>,'avatar_img03')" />
                                <input type="hidden" id="avatar_img03_hid" name="data[User][img03]" value="<?php echo isset($user_info['User']['img03'])?$user_info['User']['img03']:''; ?>" />
                            </div>

                        </div>

                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
                        <div class="am-u-lg-10 am-u-md-9 am-u-sm-9">

                            <div class="am-fl am-u-lg-4 am-u-md-4 am-u-sm-4">
                                <?php echo $html->image(isset($user_info['User']['img04']) && $user_info['User']['img04']!=''?$user_info['User']['img04']:'/theme/default/img/no_head.png',array('id'=>'avatar_img04_priview')); ?>
                                <input style="margin:8px 0;max-width:150px" type="file" id="avatar_img04" name="avatar_img04" onchange="ajaxFileUpload(<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0; ?>,'avatar_img04')" />
                                <input type="hidden" id="avatar_img04_hid" name="data[User][img04]" value="<?php echo isset($user_info['User']['img04'])?$user_info['User']['img04']:''; ?>" />
                            </div>

                            <div class="am-fl am-u-lg-4 am-u-md-4 am-u-sm-4">
                                <?php echo $html->image(isset($user_info['User']['img05']) && $user_info['User']['img05']!=''?$user_info['User']['img05']:'/theme/default/img/no_head.png',array('id'=>'avatar_img05_priview')); ?>
                                <input style="margin:8px 0;max-width:150px" type="file" id="avatar_img05" name="avatar_img05" onchange="ajaxFileUpload(<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0; ?>,'avatar_img05')" />
                                <input type="hidden" id="avatar_img05_hid" name="data[User][img05]" value="<?php echo isset($user_info['User']['img05'])?$user_info['User']['img05']:''; ?>" />
                            </div>

                            <div class="am-fl am-u-lg-4 am-u-md-4 am-u-sm-4">
                                <?php echo $html->image(isset($user_info['User']['img06']) && $user_info['User']['img06']!=''?$user_info['User']['img06']:'/theme/default/img/no_head.png',array('id'=>'avatar_img06_priview')); ?>
                                <input style="margin:8px 0;max-width:150px" type="file" id="avatar_img06" name="avatar_img06" onchange="ajaxFileUpload(<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0; ?>,'avatar_img06')" />
                                <input type="hidden" id="avatar_img06_hid" name="data[User][img06]" value="<?php echo isset($user_info['User']['img06'])?$user_info['User']['img06']:''; ?>" />
                            </div>

                        </div>
                    </div>
                </div>
        </div>
        
        
	  <?php if($svshow->check_module('B2C')){ echo $this->element('modules/module_b2c_user');  } ?>
        <?php if(isset($configs['use_point'])&&$configs['use_point']=='1'){ ?>
            <div class="am-panel am-panel-default" id="user_point_list" >
                <div class="am-panel-hd"><?php echo $ld['user_point_log'] ?>
                </div>
                    <div class="am-panel-bd" id="show_user_point"></div>
            </div>
        <?php } ?>
        <?php if($svshow->check_module('O2O','Career')){ echo $this->element('modules/module_o2o_career_user'); } ?>
        <?php if($svshow->check_module('LMS')){ echo $this->element('modules/module_lms_user'); } ?>
        
        <div class="am-panel am-panel-default" id="contact_list">
            <div class="am-panel-hd">联系记录</div>
                <div class="am-panel-bd" id="style">
					<p style="text-align:right;">
                        <a class="mt am-btn am-btn-success am-radius am-btn-sm am-btn-bottom" onclick="add_contact();">
                            <span class="am-icon-plus"></span>添加联系记录
                        </a>
                    </p>
                    <table class="am-table  table-main">
                        <thead>
                        <tr>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2">操作员</th>
                            <th class="am-u-lg-4 am-u-md-3 am-u-sm-2">联系内容</th>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2">联系时间</th>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2">下次联系时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($contact_list) && sizeof($contact_list)>0){foreach($contact_list as $k=>$v){ ?>
                            <tr >
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['UserContact']['operator_name'];?></td>
                                <td class="am-u-lg-4 am-u-md-3 am-u-sm-2"><?php echo $v['UserContact']['content'];?></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo date("Y-m-d",strtotime($v['UserContact']['contact_date']));?></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo date("Y-m-d",strtotime($v['UserContact']['next_time_contact']));?></td>
                            </tr>
                        <?php }}else{?>
                            <tr><td colspan="6" align="center"><?php echo $ld['no_data_found']?></td></tr>
                        <?php }?>
                        </tbody>
                    </table>
            </div>
        </div>
        <div class="am-panel am-panel-default"  id="user_share">
            <div class="am-panel-hd">页面分享</div>
                <div class="am-panel-bd" id="style">
                    <table class="am-table  table-main">
                        <thead>
                        <tr>
                            <th width="60%"><?php echo '分享地址'?></th>
                            <th width="20%"><?php echo '是否赠送积分'?></th>
                            <th width="20%"><?php echo '分享时间'?></th>  
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($my_share_logs) && sizeof($my_share_logs)>0){foreach($my_share_logs as $k=>$v){ ?>
                            <tr>
                                <td><?php echo $v['UserShareLog']['share_link'];?></td>
                                <td>
                                    <?php if( $v['UserShareLog']['is_give_point'] == 1){?>
                                    <span class="am-icon-check am-yes" style="cursor:pointer;" ></span>&nbsp;
                                <?php }else{ ?>
                                    <span class="am-icon-close am-no" style="cursor:pointer;"></span>&nbsp; 
                                <?php }?>
                                </td>
                                <td><?php echo date("Y-m-d",strtotime($v['UserShareLog']['created']));?></td>
                            </tr>
                        <?php }}else{?>
                            <tr><td colspan="6" align="center"><?php echo $ld['no_data_found']?></td></tr>
                        <?php }?>
                        </tbody>
                    </table>
            </div>
        </div>
        <?php //pr($default_user_config_list) ?>
        <?php if(isset($user_info['User'])){ ?>
        <?php if(isset($Resource_info['user_config_type'])&&!empty($Resource_info['user_config_type'])&&!empty($default_user_config_list)){ ?>
            <div class="am-panel am-panel-default" id="user_config">
                <div class="am-panel-hd"><?php echo $ld['user_config'] ?></div>
                    <div class="am-panel-bd  am-form-detail am-form am-form-horizontal">
                        <?php foreach($Resource_info['user_config_type'] as $kk=>$vv){ ?>
                            <?php if(!empty($default_user_config_list[$kk])){ ?>
                                <div class="am-panel am-panel-default">
                                    <div class="am-panel-hd">
                                        <h4 class="am-panel-title"><?php echo $vv; ?></h4>
                                    </div>
                                    <div id="user_config_type_<?php echo $kk; ?>" class="am-panel-collapse ">
                                        <div class="am-panel-bd">
                                            <?php foreach($default_user_config_list[$kk] as $gk=>$gv){ if(empty($gv)){continue;} ?>

                                                <div class="am-panel am-panel-default">
                                                    <div class="am-panel-hd">
                                                        <h4 class="am-panel-title"><?php echo isset($user_config_group_list[$gk])?$user_config_group_list[$gk]:$ld['unclassified']; ?></h4>
                                                    </div>
                                                    <div class="am-panel-collapse">
                                                        <div class="am-panel-bd">

                                                            <?php foreach($gv as $ck=>$cv){ ?>

                                                                <div class="am-form-group">
                                                                    <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"  style="padding-top:0px;"><?php echo $cv['name'] ?></label>
                                                                    <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><?php
                                                                        $user_config_values_arr=split("\r\n",$cv['user_config_values']);
                                                                        $user_config_values=array();
                                                                        $user_config_value=isset($user_config_list[$kk][$ck])?$user_config_list[$kk][$ck]:$cv['value'];
                                                                        if(!empty($user_config_values_arr[0])){
                                                                            foreach($user_config_values_arr as $selk=>$selv){
                                                                                if(empty($selv)){continue;}
                                                                                $selv_txt_arr=split(':',$selv);
                                                                                if(empty($selv_txt_arr[1])){continue;}
                                                                                $user_config_values[$selv_txt_arr[0]]=$selv_txt_arr[1];
                                                                            }
                                                                        }
                                                                        if($cv['value_type']=='textarea'){ ?>
                                                                            <textarea name="data[UserConfig][<?php echo $kk; ?>][<?php echo $ck; ?>]"><?php echo $user_config_value; ?></textarea>
                                                                        <?php }else if($cv['value_type']=='radio'){ foreach($user_config_values as $k=>$v){ ?>
                                                                            <label class="am-radio-inline"><input type="radio" name="data[UserConfig][<?php echo $kk; ?>][<?php echo $ck; ?>]" value="<?php echo $k; ?>" <?php echo $user_config_value==$k?" checked='checked'":""; ?>><?php echo $v; ?></label>
                                                                        <?php } ?>
                                                                        <?php }else if($cv['value_type']=='select'){ ?>
                                                                            <select  name="data[UserConfig][<?php echo $kk; ?>][<?php echo $ck; ?>]">
                                                                                <?php foreach($user_config_values as $k=>$v){ ?>
                                                                                    <option value="<?php echo $k; ?>" <?php echo $user_config_value==$k?" selected='selected'":""; ?>><?php echo $v; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        <?php }else if($cv['value_type']=='file'){ ?>
                                                                            <input type="hidden" name="data[UserConfig][<?php echo $kk; ?>][<?php echo $ck; ?>]" value="<?php echo $user_config_value; ?>"><input type="file" accept="image/*" id="<?php echo $kk.'_'.$ck; ?>" name="<?php echo $kk.'_'.$ck; ?>" onchange="ajax_upload_files(this,'<?php echo $kk.'_'.$ck; ?>')">
                                                                            <?php if(!empty($user_config_value)&&file_exists(WWW_ROOT.$user_config_value)){
                                                                                $user_file_type=mime_content_type(WWW_ROOT.$user_config_value);
                                                                                if(strpos($user_file_type,'image')!==false){ ?>
                                                                                    <p class='user_config_file'><?php echo $html->image($user_config_value); ?></p>
                                                                                <?php   }else{ ?>
                                                                                    <p class='user_config_file'><a target='_blank' href="<?php echo $user_config_value; ?>">下载</a></p>
                                                                                <?php   }} ?>
                                                                        <?php }else{ ?>
                                                                            <input type="text" style="width:200px;" name="data[UserConfig][<?php echo $kk; ?>][<?php echo $ck; ?>]" value="<?php echo $user_config_value; ?>" />
                                                                        <?php } ?>
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
                            <?php } ?>
                        <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if(isset($configs['enable_user_log'])&&$configs['enable_user_log']=='1'){ ?>
        <div class="am-panel am-panel-default" id="user_action_list" >
            <div class="am-panel-hd"><?php echo $ld['operator_logs']; ?>
            </div>
                <div class="am-panel-bd">
                    <table class="am-table  table-main">
                        <thead>
                        <tr>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['operator'] ?></th>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['operation_time'] ?></th>
                            <th class="am-u-lg-8 am-u-md-8 am-u-sm-8"><?php echo $ld['operation_remarks'] ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($user_action_list) && sizeof($user_action_list)>0){foreach($user_action_list as $k=>$v){ ?>
                            <tr >
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo isset($v['Operator']['name'])&&$v['UserActionLog']['operator_id']>0?$v['Operator']['name']:($v['UserActionLog']['operator_id']>0?'-':$ld['member']);?></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['UserActionLog']['created'];?></td>
                                <td class="am-u-lg-8 am-u-md-8 am-u-sm-8"><?php echo $v['UserActionLog']['remark'];?></td>
                            </tr>
                        <?php }}else{?>
                            <tr><td colspan="6" align="center"><?php echo $ld['no_data_found']?></td></tr>
                        <?php }?>
                        </tbody>
                    </table>
            </div>
        </div>
        <?php } ?>
        
    </div>
<?php } ?>
    <?php echo $form->end();?>
</div>

<div class="am-modal am-modal-no-btn" id="contact">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title" id="chapter_title">添加联系记录</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
    			<input type="hidden" name="data[UserContact][user_id]" value="<?php echo isset($user_info['User']['id'])?$user_info['User']['id']:0;?>">
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">联系时间</label>
    					<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
							<input type='text' id="contact_date" value="" name="data[UserContact][contact_date]" readonly data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
						</div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">下次联系时间</label>
                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
							<input type='text' id="contact_date" value="" name="data[UserContact][next_time_contact]" readonly data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
						</div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">联系内容</label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <textarea cols="35" id="content" name="data[UserContact][content]" rows="10" style="width:auto;height:200px;"></textarea>
                        </div>
                    </div>
                    <div>
                        <label for="" class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5 am-text-left">
                            <button type='button' style='margin-top:1rem;' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_modify_submit(this)"><?php echo $ld['confirm']; ?></button>
                        </div>
                        <div class="am-cf"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!--用户模板弹窗-->
<div class="am-modal am-modal-no-btn" id="user_style_pop">
    <div class="am-modal-dialog" style="height: 500px;">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title" style="text-align:center;"><?php echo $ld['add'];?>-<?php echo $ld['user_template']; ?></h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form  method="POST" action="<?php echo $admin_webroot; ?>users/update_user_style" id="update_user_style">
                <input type="hidden" value="0" id="user_style_id" />
                <input class="userstyle_id" type="hidden" name="data[UserStyle][id]" value="<?php if(isset($user_style_data['UserStyle']['id'])){ echo $user_style_data['UserStyle']['id']; } ?>" />
                <div id="change_style" style="text-align: left;z-index: 0;">
                    <!--版型模块-->
                    <?php if(!empty($style_list)){?>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="padding-top:1rem;"><?php echo $ld['product_style'];?>:&emsp;</div>
                        <div class="am-u-lg-10 am-u-md-10 am-u-sm-9" id="product_style_list">
                            <div class="am-btn-group radio-btn" data-am-button>
                                <?php if(isset($style_list) && sizeof($style_list)>0){?>
                                    <?php foreach($style_list as $k=>$v){?>
                                        <label class="am-btn am-radius am-btn-sm am-btn-primary <?php if(isset($style_id)&&$style_id == $v['ProductStyle']['id']){echo 'am-active';}?>">
                                            <input type="radio" class="style_id" name="data[UserStyle][style_id]"  value="<?php echo $v['ProductStyle']['id']; ?>" <?php if(isset($style_id)&&$style_id == $v['ProductStyle']['id']){echo "checked";}?> /><?php echo $v['ProductStyleI18n']['style_name']; ?>
                                        </label>
                                    <?php }}?>
                            </div>
                        </div>
                    <?php }?>
                    <div style="clear:both;"></div>
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="padding-top:1rem;"><?php echo $ld['product_type'];?>:&emsp;</div>
                    <div id="product_type_list" class="am-u-lg-10 am-u-md-10 am-u-sm-9">
                        <div class="am-btn-group radio-btn" data-am-button>
                            <?php if(isset($product_type_tree) && sizeof($product_type_tree)>0){?>
                                <?php foreach($product_type_tree as $v){?>
                                    <label class="am-btn am-btn-primary am-radius am-btn-sm">
                                        <input type="radio" class="product_type" name="data[UserStyle][type_id]"  value="<?php echo $v['ProductType']['id']; ?>" /><?php echo $v['ProductTypeI18n']['name']; ?>
                                    </label>
                                <?php }}?>
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div id="group_label" style="display:none;">
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="padding-top:1rem;"><?php echo $ld['specification'];?>:&emsp;</div>
                        <div class="am-u-lg-10 am-u-md-10 am-u-sm-9">
                            <div class="am-btn-group radio-btn" data-am-button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="select_style"></div>
            </form>
        </div>
    </div>
</div>

<button id="user_address_btn" class="am-btn am-btn-primary am-radius am-btn-sm" style="display:none;" data-am-modal="{target: '#user_address_popup', closeViaDimmer:0,width:600,height:450}">Modal</button>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="user_address_popup">
    <div class="am-modal-dialog" style="overflow-y:auto;">
        <div class="am-modal-hd"><?php echo $ld['users_user_address'];?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd"></div>
    </div>
</div>

<!-- 用户认证 -->
<div class="am-modal am-modal-no-btn" tabindex="-1" id="user_verify_status">
    <div class="am-modal-dialog" style="overflow-y:auto;">
        <div class="am-modal-hd"><?php echo $ld['turn_down'];?>/<?php echo $ld['unverify'];?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            <table class="am-table">
                <tr>
                    <th width="10%"><?php echo '备注'; ?></th>
                    <td width="85%" class="am-text-left"><textarea style="width:95%;" maxlength="60"></textarea><em style="color:red;width:5%;">*</em></td>  
                </tr>
                <tr>
                    <td colspan="2" class="am-text-center"><button type="button" class="am-btn am-btn-success am-radius am-btn-sm" /><?php echo $ld['submit'];?></button></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<!-- 用户认证 -->

<style type="text/css">
    .am-g.admin-content{margin:0 auto;}
    .am-form-label{text-align:right;}
    .am-form .am-form-group:last-child{margin-bottom:0;}
    #rank_operator{display:none;}
    #rank_operator select{width:50%;}
    #rank_operator em{float: left;margin: 0 5px;position: relative;top: 5px;}
    #rank_operator input[type="button"]{margin-right:1.2rem;}
    #user_order{padding:0;}
    #user_style_pop{height:600px;top:50%; }
    #user_style_pop .am-modal-bd{overflow-y:auto;overflow-x: hidden;height:430px;}
    #user_style_pop .radio-btn{margin-top:0px;}
    #user_style_pop .radio-btn label{margin:5px 5px;}
    #user_style_pop .radio-btn .am-btn{background:#e6e6e6;border-color:#e6e6e6;color:#000; padding:0.375em 0.75em}
    #user_style_pop .radio-btn .am-btn.am-active{background:#c7c7c7;border-color:#c7c7c7;}
    #user_style_pop .radio-btn .am-btn:hover{background:#c7c7c7;border-color:#c7c7c7;}
    #user_balance{display: inline-block;position: relative;left:5px;top:5px;}
    .user_avatar img{max-width:150px;max-height:150px;width:100%;}
    #user_address_popup .am-close{margin-right:1em;}
    p.user_config_file{margin:5px auto;}
    p.user_config_file img{max-width:500px;}
</style>
<script type="text/javascript">
    var user_id=document.getElementsByName("data[User][id]")[0].value;
    <?php if(constant("Product")=="AllInOne"){ ?>
    /*
     加载地址薄数据
     */
    ajaxloaduseraddr(user_id);
    function ajaxloaduseraddr(user_id){
        $.ajax({ url: admin_webroot+"users/useraddress/"+user_id,
            type:"POST",
            dataType:"html",
            success: function(data){
                $("#user_addr_list_show").html(data);
            }
        });
    }

    	
    /*
     加载订单数据
     */
    ajaxloadorder(user_id);
    function ajaxloadorder(user_id){
        $.ajax({ url: admin_webroot+"users/user_order_list/"+user_id,
            type:"POST",
            dataType:"html",
            success: function(data){
                $("#user_order").html(data);
            }
        });
    }

    /*
     加载优惠劵数据
     */
    get_user_coupons(user_id);
    function get_user_coupons(user_id){
        $.ajax({ url: admin_webroot+"users/user_coupon_list/"+user_id,
            type:"POST",
            dataType:"html",
            success: function(data){
                $("#user_coupons").html(data);
            }
        });
    }
    <?php } ?>

    /*
     加载地址薄数据
     */
    get_user_point(user_id);
    function get_user_point(user_id){
        if(document.getElementById("show_user_point")){
            $.ajax({ url: admin_webroot+"users/user_point_list/"+user_id,
                type:"POST",
                dataType:"html",
                success: function(data){
                    $("#show_user_point").html(data);
                }
            });
        }
    }

    var user_sn_check=(user_id==0||user_id=='')?false:true;
    function check_user_sn(obj){
        user_sn_check=false;
        var user_sn=obj.value;
        if(user_sn==""){return false;}
        $.ajax({url: admin_webroot+"users/check_user_sn_exist/"+user_id,
            type:"POST",
            data:{'user_sn':user_sn},
            dataType:"json",
            success: function(data){
                try{
                    if(data.code==1){
                        user_sn_check=true;
                    }else{
                        alert(data.msg);
                    }
                }catch (e){
                    alert(j_object_transform_failed);
                }
            }
        });
    }

    function check_prev(){
        $.ajax({url: admin_webroot+"users/check_user_sn_exist/"+user_id,
            type:"POST",
            data:{'user_sn':$("#user_sn").val()},
            dataType:"json",
            success: function(data){
                try{
                    if(data.code==1){
                        user_sn_check=true;
                        $("#user_edit_form").submit();
                    }
                }catch (e){
                    alert(j_object_transform_failed);
                }
            }
        });
    }

    function check_all(){
        if(document.getElementById('user_sn').value==''){
            alert("<?php echo $ld['fill_in_user_name']?>");
            return false;
        }
        if(user_sn_check==false){
            alert("<?php echo $ld['username_already_exists']; ?>");
            return false;
        }
        if(document.getElementById('user_email').value==''&&document.getElementById('user_mobile').value==""){
            alert("邮箱和手机必须填一项！");
            return false;
        }
        if(document.getElementById('user_mobile').value!=""){
            var mobile=document.getElementById('user_mobile').value;
            if(!/^1[3-9]\d{9}$/.test(mobile)){
                alert("手机格式不正确");return false;
            }
        }
        var email=document.getElementById('user_email').value;
        if(email!=""){
            var myreg =/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/; //验证邮箱的正则表达式
            if(!myreg.test(email)){
                alert("<?php echo $ld['note'];?><?php echo $ld['enter_valid_email']?>");
                return false;
            }
        }
        if(user_id==0||user_id==''){
            if(document.getElementById('user_new_password').value==''){
                alert("<?php echo $ld['note'];?><?php echo $ld['please_fill_user_password']?>");
                return false;
            }
            if(document.getElementById('user_new_password').value!=document.getElementById('user_new_password2').value){
                alert("<?php echo $ld['note'];?><?php echo $ld['password_different']?>");
                return false;
            }
        }
        if(document.getElementById("user_balance")){
            var balance=document.getElementById("user_balance").value;
            if(balance!="0"&&balance!=""){
                if(!/^(([1-9]{1}\d*)|([0]{1}))(\.(\d){1,2})?$/.test(balance)){
                    alert('金额格式错误！');
                    return false;
                }
            }
        }
        var phone_reg = new RegExp(/^\d{3}-\d{7,8}|\d{4}-\d{7,8}$/); 
        var mobile_reg = new RegExp(/^1[3-9]\d{9}$/);
        var tel = $('input[name="data[UserConfig][user_review][dh]"]').val();
        if(typeof(tel)!='undefined'&&!phone_reg.test(tel)&&!mobile_reg.test(tel)){
            alert('电话格式有误！');
            return false;
        }
        return true;
    }

    /*
     用户升级
     */
    //会员升级、续费
    function Upgrade_Renew(e){
        var user_rank_list=document.getElementById("user_rank");
        var no_Upgrade_btn=document.getElementById("no_Upgrade");
        var set_Upgrade_btn=document.getElementById("set_Upgrade");
        var start_time=document.getElementById("start_time");
        var end_time=document.getElementById("end_time");
        var rank_operator_div=document.getElementById("rank_operator");
        user_rank_list.disabled=false;
        no_Upgrade_btn.disabled=false;
        set_Upgrade_btn.disabled=false;
        start_time.disabled=false;
        end_time.disabled=false;
        e.disabled=true;
        rank_operator_div.style.display="inherit";
    }

    function checkRankInfo(){
        var rank_id=document.getElementById("user_rank").value;
        //有效期
        var start_time=document.getElementById("start_time").value;
        var end_time=document.getElementById("end_time").value;
        if(rank_id!=''&&rank_id!='0'){
            if(start_time!=""&&end_time!=""){
                var start_date=new Date(start_time);
                var end_data=new Date(end_time);
                if(Date.parse(start_date) - Date.parse(end_data)>0){
                    alert("<?php echo $ld['end_time_less_start_time']; ?>");
                }else if(Date.parse(start_date) - Date.parse(end_data)==0){
                    alert("<?php echo $ld['start_time_not_empty']; ?>");
                }else{
                    return true;
                }
            }else if(start_time==""){
                alert("<?php echo $ld['start_time_not_empty']; ?>");
            }else if(end_time==""){
                alert("<?php echo $ld['end_time_not_empty']; ?>");
            }
            return false;
        }else{
            return true;
        }
    }

    //确认等级修改
    function setUpgrade(){
        var checkRankFlag=checkRankInfo();
        if(checkRankFlag){
            if(confirm(confirm_operation)){
                var rank_id=document.getElementById("user_rank").value;
                //有效期
                var start_time=document.getElementById("start_time").value;
                var end_time=document.getElementById("end_time").value;
                var postData;
                if(rank_id==''||rank_id=='0'){
                    postData={"rank_id":0};
                }else{
                    postData={"rank_id":rank_id,"start_time":start_time,"end_time":end_time};
                }
                $.ajax({
                    url: admin_webroot+"users/ajax_setupgrade/"+user_id,
                    type:"POST",
                    data:postData,
                    dataType:"json",
                    success: function(data){
                        try{
                            if(data.code==1){
                                document.getElementById("hid_user_rank").value=rank_id;
                                document.getElementById("showUserRank").innerHTML=data.name;
                            }
                            alert(data.message);
                            noUpgrade();
                        }catch(e){
                            alert("<?php echo $ld['modify_failed']; ?>");
                            noUpgrade();
                        }
                    }
                });
            }
        }
    }

    //取消会员升级、续费
    function noUpgrade(){
        var user_rank_list=document.getElementById("user_rank");
        var no_Upgrade_btn=document.getElementById("no_Upgrade");
        var set_Upgrade_btn=document.getElementById("set_Upgrade");
        var Upgrade_Renew_btn=document.getElementById("Upgrade_Renew_btn");
        var start_time=document.getElementById("start_time");
        var end_time=document.getElementById("end_time");
        var rank_operator_div=document.getElementById("rank_operator");
        rank_operator_div.style.display="none";
        user_rank_list.value="0";
        Upgrade_Renew_btn.disabled=false;
        user_rank_list.disabled=true;
        no_Upgrade_btn.disabled=true;
        set_Upgrade_btn.disabled=true;
        start_time.disabled=true;
        end_time.disabled=true;
    }

    /*
     会员充值
     */
    function Recharge_Consumption(e){
        var fund_flow=document.getElementById("fund_flow");
        var balance_type=document.getElementsByName("balance_type");
        var user_balance=document.getElementById("user_balance");
        if(fund_flow.style.display=="none"){
            balance_type.disabled=false;
            fund_flow.style.display="inherit";
        }else{
            balance_type.disabled=true;
            user_balance.value=0;
            fund_flow.style.display="none";
        }
    }
    
    //选择版型和属性组
    $("#user_style_pop input[type=radio][name*='style_id']").change(function(){
        //alert($(this).val());
        var style_id=$(this).val();
        var product_type=$("#product_type_list").find(".am-active input").val();
        if(style_id !="" && product_type !="" && typeof product_type !='undefined'){
            $.ajax({ url: admin_webroot+"users/show_group_type/",
                type:"POST",
                data:{"style_id":style_id,'product_type':product_type},
                dataType:"json",
                success: function(data){
                    if(data['code']==1){
                        $("#group_label .radio-btn").html("");
                        var group_html="";
                        $.each(data['group_name'],function(i,item){
                            group_html+="<label class='am-btn am-btn-primary am-radius am-btn-sm'><input type='radio' class='group_name' name='group_name'  value='"+item["StyleTypeGroup"]["id"]+"' />"+item["StyleTypeGroup"]["group_name"]+"<input type='hidden' class='attr_code' value='"+item["StyleTypeGroup"]["group_name"]+"' /></label>";
                            //$("<option></option>").val(item["StyleTypeGroup"]["id"]).text(item["StyleTypeGroup"]["group_name"]).appendTo($("#group_name"));
                        });
                        $("#group_label .radio-btn").html(group_html);
                        $("#group_label").show();
                    }else{
                        $("#group_label .radio-btn").html("");
                        $("#group_label").hide();
                    }
                    $("#select_style").html("");
                }
            });
        }
    });
    
    $("#user_style_pop input[type=radio][name*='type_id']").change(function(){
        var product_type=$(this).val();
        var style_id=$("#user_style_pop #product_style_list .radio-btn .am-active input[type=radio]").val();
        if(style_id !="" && product_type !="" && typeof style_id !='undefined'){
            $.ajax({ url: admin_webroot+"users/show_group_type/",
                type:"POST",
                data:{"style_id":style_id,'product_type':product_type},
                dataType:"json",
                success: function(data){
                    if(data['code']==1){
                        $("#group_label .radio-btn").html("");
                        var group_html="";
                        $.each(data['group_name'],function(i,item){
                            group_html+="<label class='am-btn am-btn-primary am-radius am-btn-sm'><input type='radio' class='group_name' name='group_name'  value='"+item["StyleTypeGroup"]["id"]+"' />"+item["StyleTypeGroup"]["group_name"]+"<input type='hidden' class='attr_code' value='"+item["StyleTypeGroup"]["group_name"]+"' /></label>";
                            //$("<option></option>").val(item["StyleTypeGroup"]["id"]).text(item["StyleTypeGroup"]["group_name"]).appendTo($("#group_name"));
                        });
                        $("#group_label .radio-btn").html(group_html);
                        $("#group_label").show();
                    }else{
                        $("#group_label .radio-btn").html("");
                        $("#group_label").hide();
                    }
                    $("#select_style").html("");
                }
            });
        }
    });
    
    //选规格显示属性
    $("#user_style_pop").on("change","input[type=radio][name='group_name']",function(){
        var style_id=$("#user_style_pop #product_style_list .radio-btn .am-active input[type=radio]").val();
        var product_type=$("#user_style_pop #product_type_list .radio-btn .am-active input[type=radio]").val();
        var group_name=$(this).val();
        var user_style_id=$("#user_style_id").val();
        if(group_name!="" && style_id!="" && product_type!="" && typeof style_id !='undefined' && typeof product_type !='undefined'){
            $.ajax({ url: admin_webroot+"users/show_attr_value/",
                type:"POST",
                data:{"style_id":style_id,'product_type':product_type,'group_name':group_name,'user_style_id':user_style_id},
                dataType:"html",
                success: function(data){
                    var select_style_html=$("#select_style").html();
                    if(user_style_id!=0){
                        $("#select_style").html(data);
                    }else{
                        if(select_style_html.length==0){
                            $("#select_style").html(data);
                        }else{
                            var editinfo_html_obj=document.createElement("div");
                            editinfo_html_obj.innerHTML=data;
                            var editinfo_html=$(editinfo_html_obj).find("#user_style_editinfo").html();
                            $("#select_style #user_style_editinfo").html(editinfo_html);
                        }
                    }
                    $("#user_style_editinfo select").selected();
                }
            });
        }
    });
    
    function show_attr_value(style_id,product_type,group_name,user_style_id){
        if(style_id!="" && product_type!="" && group_name!="" && typeof style_id !='undefined' && typeof product_type !='undefined' && typeof group_name !='undefined'){
            $.ajax({ url: admin_webroot+"users/show_attr_value/",
                type:"POST",
                data:{"style_id":style_id,'product_type':product_type,'group_name':group_name,'user_style_id':user_style_id},
                dataType:"html",
                success: function(data){
                    var select_style_html=$("#select_style").html();
                    if(user_style_id!=0){
                        $("#select_style").html(data);
                    }else{
                        if(select_style_html.length==0){
                            $("#select_style").html(data);
                        }else{
                            var editinfo_html_obj=document.createElement("div");
                            editinfo_html_obj.innerHTML=data;
                            var editinfo_html=$(editinfo_html_obj).find("#user_style_editinfo").html();
                            $("#select_style #user_style_editinfo").html(editinfo_html);
                        }
                    }
                    $("#user_style_editinfo select").selected();
                }
            });
        }
    }
    
    //用户模板弹窗打开
    $(".add_style").click(function(){
        $('#user_style_pop').modal('open');
        var style_id=$("#user_style_pop #product_style_list .radio-btn .am-active input[type=radio]").val();
        var product_type=$("#user_style_pop #product_type_list .radio-btn .am-active input[type=radio]").val();
        if(style_id !="" && product_type !="" && typeof style_id !='undefined' && typeof product_type !='undefined'){
            $.ajax({ url: admin_webroot+"users/show_group_type/",
                type:"POST",
                data:{"style_id":style_id,'product_type':product_type},
                dataType:"json",
                success: function(data){
                    if(data['code']==1){
                        $("#group_label .radio-btn").html("");
                        var group_html="";
                        $.each(data['group_name'],function(i,item){
                            group_html+="<label class='am-btn am-btn-primary am-radius am-btn-sm'><input type='radio' class='group_name' name='group_name'  value='"+item["StyleTypeGroup"]["id"]+"' />"+item["StyleTypeGroup"]["group_name"]+"<input type='hidden' class='attr_code' value='"+item["StyleTypeGroup"]["group_name"]+"' /></label>";
                            //$("<option></option>").val(item["StyleTypeGroup"]["id"]).text(item["StyleTypeGroup"]["group_name"]).appendTo($("#group_name"));
                        });
                        $("#group_label .radio-btn").html(group_html);
                        $("#group_label").show();
                    }else{
                        $("#group_label .radio-btn").html("");
                        $("#group_label").hide();
                    }
                    $("#select_style").html("");
                }
            });
        }
        $("#user_style_pop h4").html("<?php echo $ld['add'];?>-<?php echo $ld['user_template']; ?>");
    });
    
    //编辑用户模板
    $("#style").on("click",".edit_user_style",function(){
        var user_style_id=$(this).attr("id");
        $(".userstyle_id").val(user_style_id);
        var PostData={"edit_id":$(this).attr("id"),"user_id":$("#user_id").val()};
        $.ajax({url: admin_webroot+"users/edit_user_style/",
            type:"POST",
            data:PostData,
            dataType:"json",
            success: function(data){
                try{
                    $("#user_style_pop input[type=radio][name*='type_id']").parent().removeClass("am-active");
                    $("#user_style_pop input[type=radio][name*='style_id']").parent().removeClass("am-active");
                    $("#user_style_pop input[type=radio][name*='style_id'][value='"+data['user_style']['UserStyle']['style_id']+"']").attr('checked','checked');
                    $("#user_style_pop input[type=radio][name*='style_id'][value='"+data['user_style']['UserStyle']['style_id']+"']").parent().addClass("am-active");
                    $("#user_style_pop input[type=radio][name*='type_id'][value='"+data['user_style']['UserStyle']['type_id']+"']").attr('checked','checked');
                    $("#user_style_pop input[type=radio][name*='type_id'][value='"+data['user_style']['UserStyle']['type_id']+"']").parent().addClass("am-active");
                    $("#group_label .radio-btn").html("");
                    var group_html="";
                    var group_name="";
                    $.each(data['group_name'],function(i,item){
                        if(data['user_style']['UserStyle']['attribute_code']==item["StyleTypeGroup"]["group_name"]){
                            group_html+="<label class='am-btn am-btn-primary am-radius am-btn-sm am-active'><input type='radio' class='group_name' name='group_name' checked value='"+item["StyleTypeGroup"]["id"]+"' />"+item["StyleTypeGroup"]["group_name"]+"<input type='hidden' class='attr_code' value='"+item["StyleTypeGroup"]["group_name"]+"' /></label>";
                            group_name=item["StyleTypeGroup"]["id"];
                        }else{
                            group_html+="<label class='am-btn am-btn-primary am-radius am-btn-sm'><input type='radio' class='group_name' name='group_name'  value='"+item["StyleTypeGroup"]["id"]+"' />"+item["StyleTypeGroup"]["group_name"]+"<input type='hidden' class='attr_code' value='"+item["StyleTypeGroup"]["group_name"]+"' /></label>";
                        }
                    });
                    $("#group_label .radio-btn").html(group_html);
                    $("#group_label").show();
                    $("#user_style_pop h4").html(data['user_style']['UserStyle']['user_style_name']);
                    show_attr_value(data['user_style']['UserStyle']['style_id'],data['user_style']['UserStyle']['type_id'],group_name,user_style_id);
                    $('#user_style_pop').modal('open');
                }catch (e){
                    alert(j_object_transform_failed);
                }
            }
        });
    });
    
    //删除用户模板
    $("#style").on("click",".delete_user_style",function(){
        if(confirm("<?php echo $ld['confirm_delete_user_template'] ?>")){
            var PostData={"del_id":$(this).attr("id"),"user_id":$("#user_id").val()};
            $.ajax({url: admin_webroot+"users/update_user_style/",
                type:"POST",
                data:PostData,
                dataType:"html",
                success: function(data){
                    try{
                        $("#style .table-main").html(data);
                    }catch (e){
                        alert(j_object_transform_failed);
                    }
                }
            });
        }
    });

    /*
     上传图片文件
     */
    function ajaxFileUpload(Id,inputName){
        if(Id==0){alert('用户不存在!');return false;}
        $.ajaxFileUpload({
            url:admin_webroot+'users/ajaxuploadavatar/'+Id+'/'+inputName,
            secureuri:false,
            fileElementId:inputName,
            dataType: 'json',
            success: function (result){
                if(result.code==1){
                    var avatar_url=result.img_url;
                    $("#"+inputName+"_priview").attr("src",avatar_url);
                    $("#"+inputName+"_hid").val(avatar_url);
                }else{
                    
                }
            },
            error: function (data, status, e)//服务器响应失败处理函数
            {
                alert('上传失败');
            }
        });
        return false;
    }

    /*
     上传配置数据文件
     */
    function ajax_upload_files(inputFile,fileCode){
        var file_link_html="<p class='user_config_file'><a target='_blank' href='FILELINK'>下载</a></p>";
        var image_link_html="<p class='user_config_file'><img src='FILELINK'></p>";
        var Filehidden=$(inputFile).parent().find("input[type='hidden']");
        $.ajaxFileUpload({
            url:admin_webroot+'users/ajax_upload_files/',
            secureuri:false,
            fileElementId:fileCode,
            dataType: 'json',
            data:{'fileCode':fileCode},
            success: function (result){
                if(result.code=='1'){
                    console.log(result);
                    $(Filehidden).val(result.file_name);
                    $(Filehidden).parent().find("p.user_config_file").remove();
                    var FileType=result.file_type;
                    if(FileType.indexOf("image")>=0){
                        image_link_html=image_link_html.replace("FILELINK", result.file_name);
                        image_link_html=image_link_html.replace("DELFILELINK", result.file_name);
                        $(Filehidden).parent().append(image_link_html);
                    }else{
                        file_link_html=file_link_html.replace("FILELINK", webroot+result.file_name);
                        file_link_html=file_link_html.replace("DELFILELINK", webroot+result.file_name);
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

    function user_verify_status(user_id,status){
        $("#user_verify_status textarea").val("");
        $("#user_verify_status button").attr("onclick","");
        if(status=='4'||status=='2'){
            $("#user_verify_status").modal({closeViaDimmer:false});
            $("#user_verify_status button").attr("onclick","verify_status('"+user_id+"','"+status+"')");
        }else{
            verify_status(user_id,status);
        }
    }

    function verify_status(user_id,status_code){
        var remark=$("#user_verify_status textarea").val();
        if((status_code=='4'||status_code=='2')&&remark==""){
            alert('请填写备注');return false;
        }
        $.ajax({url: admin_webroot+"users/user_status/",
            type:"POST",
            data:{"user_id":user_id,"status_code":status_code,"remark":remark},
            dataType:"json",
            success: function(data){
                try{
                    window.location.reload();
                }catch (e){
                    alert(j_object_transform_failed);
                }
            }
        });
    }

    $(".user_config_file img").on('click',function(){
        if($(this).attr('style')){
            $(this).removeAttr('style');
        }else{
            $(this).css({'width':'400px','height':'400px'});
        }
        
    });
    
	function add_contact(){
        $("#chapter_id").val("");
        $("#chapter_code").val("");
        $("#chapter_name").val("");
        $("#contact").modal('open');
    }
    
	function ajax_modify_submit(btn){
        var postForm=$(btn).parents('form');
        var postData=postForm.serialize();
        $.ajax({
            url: admin_webroot+"users/ajax_modify",
            type:"POST",
            data:postData,
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    alert(data.message);
                    window.location.reload();
                }else{
                    alert(data.message);
                }
            }
        });
    }
</script>