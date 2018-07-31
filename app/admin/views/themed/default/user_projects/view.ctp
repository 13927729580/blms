<style type='text/css'>
#add_project form{max-height:400px;overflow-y:scroll;}
#change_project label.am-form-label{margin-left:0px;padding-right:0px;}
#change_project .am-form-group{text-align: left;margin-bottom: 1rem;}
@media only screen and (min-width: 641px){
	#change_project{width:90%;margin-left:-45%;}
}
@media only screen and (min-width: 1025px){
	#change_project{width:60%;margin-left:-30%;}
}
#project_log_list table tr td:last-child p{margin:0 auto;padding:0px;display: block;}
#change_project{top:50%;}
#change_project form{min-height:400px;max-height:450px;overflow-y:scroll;}
#change_project ul.am-list>li{background:none;border-top:none;}
#change_project ul.am-list>li>div.am-g{margin-bottom:0.5rem;}
#change_project ul.am-list>li .ModificationRemove,#change_project ul.am-list>li .ModificationRemove:hover{color:#dd514c;text-decoration:none;}
#change_project #ModificationToList>li:first-child{display:none;}
#change_project #ModificationToList>li{padding-bottom:1rem;}
#change_project #ModificationToList>li input[type='text'],#change_project #ModificationToList>li .am-selected-btn{padding-top:3px;padding-bottom:3px;}
</style>
<script src="<?php echo $webroot; ?>plugins/ajaxfileupload.js" type="text/javascript"></script>
<div class="am-product">
    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
        <?php echo $form->create('/user_projects',array('action'=>'view/'.$user_list["User"]["id"],'id'=>'user_project_edit_form','class'=>'am-form am-form-horizontal','name'=>'user_project_edit','type'=>'POST'));?>
        <input type="hidden" name="data[User][id]" id="_id" value="<?php echo $user_list['User']['id'];?>" />
        <div class="am-g">
            <div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:<?php echo isset($configs['is_qa_environment'])&&$configs['is_qa_environment']=='1'?90:56; ?>,animation:'slide-top'}" style="height: 42px; margin: 0px 0px 8px;">
                <ul>
                    <li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
                    <?php if($svshow->operator_privilege("project_view")||$svshow->operator_privilege("project_detail")){?>
                        <li><a href="#project">项目信息</a></li>
                    <?php }?>
                    <?php if($svshow->operator_privilege("project_log_view")){ ?>
			<li><a href="#project_log"><?php echo $ld['logs']; ?></a></li>
			<?php } ?>
                </ul>
            </div>
            <?php if(($svshow->operator_privilege("student_edit")&&$admin['id']==$user_list['User']['operator_id'])||$admin['actions']=='all'){?>
            <div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
                <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit'];?>" onclick="return check_student()"/>
                <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
            </div>
            <?php } ?>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Basic_information_pancel'}"><?php echo $ld['basic_information'] ?>&nbsp;</h4>
            </div>
            <div id="Basic_information_pancel" class="am-panel-collapse am-collapse am-in">
                <div id="basic_information" class="scrollspy_nav_hid"></div>
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">课程顾问</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <select name="data[User][operator_id]" data-am-selected="{maxHeight:300}" >
                                <option value="-1"><?php echo $ld['please_select']?></option>
                                <?php if(!empty($manager_list2)){
                                    foreach($manager_list2 as $v){?>
                                        <option value="<?php echo $v['Operator']['id'];?>" <?php if($user_list['User']['operator_id']==$v['Operator']['id']){echo "selected";}?>><?php echo $v['Operator']['name'];?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['user_name'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><input type="text" onchange="check_user_sn(this)" name="data[User][first_name]" id="first_name" value="<?php echo isset($user_list['User']['first_name'])?$user_list['User']['first_name']:'';?>"></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="width:3%;padding-left:0;"><em style="color:red;">*</em></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['gender'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <label class="am-radio-inline"><input type="radio" name="data[User][sex]" <?php echo (isset($user_list['User']['sex'])&&$user_list['User']['sex']=='0')||!isset($user_list['User'])?'checked':'';?> value="0"/><?php echo $ld['secrecy']?></label>
                            <label class="am-radio-inline"><input type="radio" name="data[User][sex]" <?php echo isset($user_list['User']['sex'])&&$user_list['User']['sex']=='1'?'checked':'';?> value="1"/><?php echo $ld['male']?></label>
                            <label  class="am-radio-inline"><input type="radio" name="data[User][sex]" <?php echo isset($user_list['User']['sex'])&&$user_list['User']['sex']=='2'?'checked':'';?> value="2"/><?php echo $ld['female']?></label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['mobile'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><input type="text" id="user_mobile" name="data[User][mobile]" value="<?php echo isset($user_list['User']['mobile'])?$user_list['User']['mobile']:'';?>"/></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['email'] ?></label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><input type="text" id="user_email" name="data[User][email]" value="<?php echo isset($user_list['User']['email'])?$user_list['User']['email']:'';?>"/></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">QQ</label>
                        <?php foreach($user_list['User']['qq'] as $k=>$v){?>
                            <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><input type="text" name="data[UserConfig][<?php echo $k;?>]" value="<?php echo $v;?>"/></div>
                        <?php }?>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">身份证号</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><input type="text" id="identity_card" name="data[User][identity_card]" value="<?php echo isset($user_list['User']['identity_card'])?$user_list['User']['identity_card']:'';?>"/></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">学历</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <select name="data[UserEducation][education_id]" data-am-selected="{maxHeight:300}">
                                <option value="0"><?php echo $ld['please_select']; ?></option>
                                <?php if(isset($resource_info['education_type'])&&!empty($resource_info['education_type'])){foreach($resource_info['education_type'] as $k=>$v){ ?>
                                    <option value="<?php echo $k; ?>" <?php if($user_list['User']['education_id']==$k){?> selected<?php }?>><?php echo $v; ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">身份证</label>
                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
                            <?php echo $html->image(isset($user_list['User']['identity_card_picture']) && $user_list['User']['identity_card_picture']!=''?$user_list['User']['identity_card_picture']:'/theme/default/img/no_head.png',array('id'=>'avatar_img01_priview')); ?>
                            <input style="margin:8px 0;max-width:150px" type="file" id="avatar_img01" name="avatar_img01" onchange="ajaxFileUpload(<?php echo isset($user_list['User']['id'])?$user_list['User']['id']:0; ?>,'avatar_img01')" />
                            <input type="hidden" id="avatar_img01_hid" name="data[User][identity_card_picture]" value="<?php echo isset($user_list['User']['identity_card_picture'])?$user_list['User']['identity_card_picture']:''; ?>" />
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">学历证书</label>
                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
                            <input type="hidden" name="data[UserEducation][id]" value="<?php echo $user_list['User']['user_education_id'];?>" />
                            <?php echo $html->image(isset($user_list['User']['diploma']) && $user_list['User']['diploma']!=''?$user_list['User']['diploma']:'/theme/default/img/no_head.png',array('id'=>'avatar_img02_priview')); ?>
                            <input style="margin:8px 0;max-width:150px" type="file" id="avatar_img02" name="avatar_img02" onchange="ajaxFileUpload(<?php echo isset($user_list['User']['id'])?$user_list['User']['id']:0; ?>,'avatar_img02')" />
                            <input type="hidden" id="avatar_img02_hid" name="data[UserEducation][diploma]" value="<?php echo isset($user_list['User']['diploma'])?$user_list['User']['diploma']:''; ?>" />
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">其他证件</label>
                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
                            <?php echo $html->image(isset($user_list['User']['img06']) && $user_list['User']['img06']!=''?$user_list['User']['img06']:'/theme/default/img/no_head.png',array('id'=>'avatar_img03_priview')); ?>
                            <input style="margin:8px 0;max-width:150px" type="file" id="avatar_img03" name="avatar_img03" onchange="ajaxFileUpload(<?php echo isset($user_list['User']['id'])?$user_list['User']['id']:0; ?>,'avatar_img03')" />
                            <input type="hidden" id="avatar_img03_hid" name="data[User][img06]" value="<?php echo isset($user_list['User']['img06'])?$user_list['User']['img06']:''; ?>" />
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">行业</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <select name="data[UserExperience][company_industry]" data-am-selected="{maxHeight:300}">
                                <option value=""><?php echo $ld['please_select']; ?></option>
                                <?php if(isset($resource_info['company_type'])&&!empty($resource_info['company_type'])){foreach($resource_info['company_type'] as $k=>$v){ ?>
                                    <option value="<?php echo $v; ?>" <?php if($user_list['User']['company_industry']==$v){echo "selected";}?>><?php echo $v; ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                    </div>
                        <div class="am-form-group">
                            <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">单位</label>
                            <input type="hidden" name="data[UserExperience][id]" value="<?php echo $user_list['User']['user_experience_id'];?>" />
                            <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><input type="text" id="company_name" name="data[UserExperience][company_name]" value="<?php echo isset($user_list['User']['company_name'])?$user_list['User']['company_name']:'';?>"/></div>
                        </div>
                        <div class="am-form-group">
                            <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">职位</label>
                            <div class="am-u-lg-9 am-u-md-8 am-u-sm-8"><input type="text" id="position" name="data[UserExperience][position]" value="<?php echo isset($user_list['User']['position'])?$user_list['User']['position']:'';?>"/></div>
                        </div>
                        <div class="am-form-group">
                            <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">学生备注</label>
                            <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                                <?php foreach($user_list['User']['remark'] as $k=>$v){?>
                                    <textarea name="data[UserConfig][<?php echo $k;?>]"><?php echo $v;?></textarea>
                                <?php }?>
                            </div>
                        </div>
                        <div class="am-form-group am-hide">
                            <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">顾问备注</label>
                            <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                                <?php foreach($user_list['User']['manager_remark'] as $k=>$v){?>
                                    <textarea name="data[UserConfig][<?php echo $k;?>]"><?php echo $v;?></textarea>
                                <?php }?>
                            </div>
                        </div>
                        <div class="am-form-group am-hide">
                            <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">教务备注</label>
                            <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                                <?php foreach($user_list['User']['project_remark'] as $k=>$v){?>
                                    <textarea name="data[UserConfig][<?php echo $k;?>]"><?php echo $v;?></textarea>
                                <?php }?>
                            </div>
                        </div>
                        <div class="am-form-group am-hide">
                            <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">财务备注</label>
                            <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                                <?php foreach($user_list['User']['fee_remark'] as $k=>$v){?>
                                    <textarea name="data[UserConfig][<?php echo $k;?>]"><?php echo $v;?></textarea>
                                <?php }?>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <?php if($svshow->operator_privilege("project_view")||$svshow->operator_privilege("project_detail")){?>
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd">
                    <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#project_pancel'}">项目信息&nbsp;</h4>
                </div>
                <div id="project_pancel" class="am-panel-collapse am-collapse am-in">
                    <div id="project" class="scrollspy_nav_hid"></div>
                    <div class="am-panel-bd">
                        <p class='am-text-right'>
                            <?php if($svshow->operator_privilege("project_add")){?>
                                <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick="add_project();">
                                    <span class="am-icon-plus"></span>&nbsp;<?php echo $ld['add'] ?>
                                </a>
                            <?php }?>
                        </p>
                        <table class="am-table table-main">
                            <thead>
                            <tr>
                                <th class='am-text-middle'>项目名称</th>
                                <th class='am-text-middle'>申请信息</th>
                                <th class='am-text-middle'>班级名称</th>
                                <?php if(isset($resource_info['user_project_fee'])&&sizeof($resource_info['user_project_fee'])>0){foreach($resource_info['user_project_fee'] as $v){ ?>
                                <th class='am-text-middle'><?php echo $v; ?></th>
                                <?php }} ?>
                                <th class='am-text-middle'>项目状态</th>
                                <th class='am-text-middle'>报名日期</th>
                                <th class='am-text-middle' width='10%'>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($project_list) && sizeof($project_list)>0){$project_datas=array();
                            		foreach($project_list as $k=>$v){
                            			$project_datas[$v['id']]=isset($resource_info['all_user_project'][$v['project_code']])?$resource_info['all_user_project'][$v['project_code']]:$v['project_code'];
                            ?>
                                <tr>
                                    <td><?php echo isset($resource_info['all_user_project'][$v['project_code']])?$resource_info['all_user_project'][$v['project_code']]:"-"?><br ><i class='am-icon am-icon-user'></i>&nbsp;<?php echo !empty($v['manager_name'])?$v['manager_name']:"-"?></td>
                                    <td><?php echo isset($resource_info['user_project_site'][$v['project_site']])?$resource_info['user_project_site'][$v['project_site']]:"-";?><br/><?php echo date('Y-m',strtotime($v['project_time']));echo isset($resource_info['user_project_time'][$v['project_hour']])?$resource_info['user_project_time'][$v['project_hour']]:""?></td>
                                    <td><?php echo !empty($v['class_name'])?$v['class_name']:"-"?><br/><?php echo !empty($v['class_user_name'])?$v['class_user_name']:"-"?></td>
                                    <?php if(isset($resource_info['user_project_fee'])&&sizeof($resource_info['user_project_fee'])>0){foreach($resource_info['user_project_fee'] as $kk=>$vv){ ?>
                                    <td><?php echo isset($v['fee'][$kk][0])?('未审:'.intval($v['fee'][$kk][0])):''; ?><?php echo isset($v['fee'][$kk][1])?('<br />已审:'.intval($v['fee'][$kk][1])):''; ?></td>
                                    <?php }} ?>
                                    <td>
                                        <?php if($v['status']=='0'){
                                            echo "待付款";
                                        }elseif($v['status']=='1'){
                                            echo "已付款";
                                        }elseif($v['status']=='2'){
                                            echo "待分班";
                                        }elseif($v['status']=='3'){
                                            echo "已分班";
                                        }elseif($v['status']=='4'){
                                            echo "变更项目财务待审核";
                                        }elseif($v['status']=='5'){
                                            echo "已取消";
                                        }elseif($v['status']=='6'){
                                            echo "变更项目待审核";
                                        }?>
                                    </td>
                                    <td><?php echo date('Y-m-d',strtotime($v['created']));?></td>
                                    <td>
                                        <?php if(($svshow->operator_privilege("project_edit")&&(($admin['actions']=='all')||($v['manager_id']==$admin['id'])||in_array($v['manager_id'],$child_operators)||($v['class_manager']==$admin['id'])||in_array($v['class_manager'],$child_operators)))||$svshow->operator_privilege("project_detail")||($svshow->operator_privilege("project_edit_all")&&in_array($v['status'],array('2','3','5')))||($svshow->operator_privilege("project_edit_all_unpaid")&&in_array($v['status'],array('0','1','4','6')))){?>
                                            <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/user_projects/fee_view/'.$v['id']); ?>">
                                                <span class="am-icon-pencil-square-o"></span> <?php echo $ld['details']; ?>
                                            </a>
                                        <?php }?>
                                        <?php if($svshow->operator_privilege("project_delete")&&(($admin['actions']=='all')||($v['manager_id']==$admin['id']))){?>
                                            <?php if($v['status']!='1' && $v['status']!='6' &&!isset($v['fee'][0][1])){?>
                                                <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'user_projects/remove/<?php echo $v['id'] ?>');">
                                                    <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                                </a>
                                            <?php }?>
                                        <?php }?>
                                        <?php if($svshow->operator_privilege("project_change")&&(($admin['actions']=='all')||($v['manager_id']==$admin['id']))){?>
                                            <?php if($v['status']!='0' && $v['status']!='4' && $v['status']!='5' && $v['status']!='6' && isset($v['fee'][0][1])){?>
                                            <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="change_project(<?php echo $v['id'] ?>);">
                                                <span class="am-icon-pencil-square-o"></span> 变更
                                            </a>
                                            <?php }else if(isset($v['ModificationId'])&&$v['status']=='6'){ ?>
                                            <a class="mt am-btn am-btn-danger am-btn-xs" href="javascript:void(0);" onclick="cancel_change_project(<?php echo $v['ModificationId'] ?>);">
                                                	<span class="am-icon-trash-o"></span> 取消变更
                                            </a>
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php }}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php }?>
        <?php if($svshow->operator_privilege("project_log_view")){ ?>
                <div class="am-panel am-panel-default">
                <div class="am-panel-hd">
                    <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#project_log_list'}"><?php echo $ld['logs']; ?>&nbsp;</h4>
                </div>
                <div id="project_log_list" class="am-panel-collapse am-collapse am-in">
                    <div id="project_log" class="scrollspy_nav_hid"></div>
                    <div class="am-panel-bd">
                        <table class="am-table table-main">
                            <thead>
	                            <tr>
	                                <th>操作时间</th>
	                                <th>操作人</th>
	                                <th>项目</th>
	                                <th>状态</th>
	                                <th width='65%'>备注</th>
	                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($user_project_logs) && sizeof($user_project_logs)>0){foreach($user_project_logs as $k=>$v){?>
                                <tr>
                                    <td><?php echo $v['UserProjectLog']['created'];?></td>
                                    <td><?php echo isset($log_operators[$v['UserProjectLog']['operator_id']])?$log_operators[$v['UserProjectLog']['operator_id']]:$v['UserProjectLog']['operator'];?></td>
                                	<td><?php echo isset($resource_info['all_user_project'][$v['UserProjectLog']['project_code']])?$resource_info['all_user_project'][$v['UserProjectLog']['project_code']]:"-"; ?></td>
                                    <td><?php if($v['UserProjectLog']['status']=='0'){
				                                echo "待付款";
				                            }elseif($v['UserProjectLog']['status']=='1'){
				                                echo "已付款";
				                            }elseif($v['UserProjectLog']['status']=='2'){
				                                echo "待分班";
				                            }elseif($v['UserProjectLog']['status']=='3'){
				                                echo "已分班";
				                            }elseif($v['UserProjectLog']['status']=='4'){
				                                echo "变更中";
				                            }elseif($v['UserProjectLog']['status']=='5'){
				                                echo "已取消";
				                            }elseif($v['UserProjectLog']['status']=='6'){
				                                echo "变更中";
                            			       }else{
                            			       	echo "-";
                            			       }
                            	?></td>
                                    <td class='am-text-left'><?php $log_remark=json_decode($v['UserProjectLog']['remark'],true);
                                    		if(!is_null($log_remark)){foreach($log_remark as $vv){echo "<p>";print_r($vv);echo "</p>";}}else{echo $v['UserProjectLog']['remark'];}?></td>
                                </tr>
                            <?php }}?>
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            <?php } ?>
            <?php echo $form->end(); ?>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="add_project">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title">添加项目</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
                <input type="hidden" name="data[UserProject][user_id]" value="<?php echo $user_list['User']['id'];?>">
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">项目名称</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7">
                            <select name="data[UserProject][project_code]" id='project_code' data-am-selected="{maxHeight:300}" >
                                	<optgroup label="<?php echo $ld['please_select']; ?>">
						<option value="-1"><?php echo $ld['please_select']?></option>
					</optgroup>
		                    <?php if(isset($resource_info['user_project'])&&!empty($resource_info['user_project'])){foreach($resource_info['user_project'] as $k=>$v){
								if(isset($resource_info[$k])&&!empty($resource_info[$k])){
					?>
					<optgroup label="<?php echo $v; ?>">
						<?php 	foreach($resource_info[$k] as $kk=>$vv){ ?>
						<option value="<?php echo $kk; ?>" ><?php echo $vv; ?></option>
						<?php		}	?>
					</optgroup>
					<?php
								}else{
					?>
					<optgroup label="<?php echo $v; ?>">
						<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
					</optgroup>
					<?php 	}	}} ?>
                            </select>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group am-hide">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">班级名称</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7">
                            <select name="data[UserProject][project_class_id]" id='project_class_id' data-am-selected="{maxHeight:300}" >
                                <option value="-1"><?php echo $ld['please_select']?></option>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">申请上课月份</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7">
                        		<select name="data[UserProject][project_time]" id="project_time" data-am-selected="{maxHeight:250}">
                        			<option value=" "><?php echo $ld['please_select']; ?></option>
						<?php for($project_time_year=date('Y');$project_time_year<=date('Y')+1;$project_time_year++){for($project_time_month=($project_time_year==date('Y')?date('m'):1);$project_time_month<=12;$project_time_month++){$project_time_month=str_pad($project_time_month,2,"0",STR_PAD_LEFT); ?>
						<option value="<?php echo $project_time_year.'/'.$project_time_month; ?>"><?php echo $project_time_year.'/'.$project_time_month; ?></option>
						<?php }} ?>
                        		</select>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">申请上课时段</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7">
                		<select name="data[UserProject][project_hour]" id="project_hour" data-am-selected="{maxHeight:250}">
                			<option value=" "><?php echo $ld['please_select']; ?></option>
					<?php if(!empty($resource_info['user_project_time'])){
                                    foreach($resource_info['user_project_time'] as $k=>$v){?>
                                        <option value="<?php echo $k;?>"><?php echo $v;?></option>
                                    <?php }
                                }?>
                		</select>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">校区</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7">
                            <select name="data[UserProject][project_site]" id='project_site' data-am-selected="{maxHeight:300}" >
                                <option value="-1"><?php echo $ld['please_select']?></option>
                                <?php if(!empty($resource_info['user_project_site'])){
                                    foreach($resource_info['user_project_site'] as $k=>$v){?>
                                        <option value="<?php echo $k;?>"><?php echo $v;?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">课程顾问</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7">
                            <select name="data[UserProject][manager]" id="project_manager" data-am-selected="{maxHeight:300}" >
                                <option value="-1"><?php echo $ld['please_select']?></option>
                                <?php if(!empty($manager_list)){
                                    foreach($manager_list as $v){?>
                                        <option value="<?php echo $v['Operator']['id'];?>" <?php if($admin_id==$v['Operator']['id']){?> selected<?php }?>><?php echo $v['Operator']['name'];?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">报名日期</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7">
                        		<div class="am-input-group am-margin-top-0">
						<input type="text"  class="am-form-field" readonly name="data[UserProject][created]" value=""  id='project_created' />
						<span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
							<i class="am-icon-remove"></i>
						</span>
					</div>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">项目状态</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7">
                            <select name="data[UserProject][status]" id='project_status' data-am-selected="{maxHeight:300}" onchange="add_project_status_modify(this)">
                                <option value="-1"><?php echo $ld['please_select']?></option>
                                <option value="0">待付款</option>
                                <option value="1">已付款</option>
                            </select>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group add_fee_detail">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">培训费</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7">
                        	<input type='hidden' name="data[UserProjectFee][fee_type]" value='0' />
                        	<input type='text' name="data[UserProjectFee][amount]" value='' />
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group add_fee_detail">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">支付方式</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7">
                        	<select name="data[UserProjectFee][payment_id]" data-am-selected="{maxHeight:300}">
                        		<option value="0"><?php echo $ld['please_select']?></option>
                        		<?php if(isset($payment_list)&&sizeof($payment_list)>0){foreach($payment_list as $v){ ?>
                        		<option value="<?php echo $v['Payment']['id']; ?>"><?php echo $v['PaymentI18n']['name']; ?></option>
                        		<?php }} ?>
                        	</select>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group add_fee_detail">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">收据编号</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7">
                        	<input type='text' name="data[UserProjectFee][receipt_number]" value='' />
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">备注</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7"><textarea rows="3" cols="20" id="remark" name="data[UserProject][remark]"></textarea></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">&nbsp;</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7 am-text-left"><button type='button' style='margin-top:1rem;' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_modify_submit(this)"><?php echo $ld['confirm']; ?></button></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="change_project">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title">变更项目</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
    			<div class='am-form-group'>
    				<div class='am-u-lg-6 am-u-md-6  am-u-sm-6'>
    					<?php if(isset($CanModificationList)&&sizeof($CanModificationList)>0){ ?>
    					<select data-am-selected="{maxHeight:250}" id="ModificationFrom" multiple>
    						<?php foreach($CanModificationList as $k=>$v){ ?>
    						<option value="<?php echo $k; ?>"><?php echo isset($resource_info['all_user_project'][$v])?$resource_info['all_user_project'][$v]:$v; ?></option>
    						<?php } ?>
    					</select>
    					<?php }else{echo "&nbsp;";} ?>
    				</div>
    				<div class='am-u-lg-6 am-u-md-6  am-u-sm-6'>
    					<select data-am-selected="{maxHeight:250}" id="ModificationTo" multiple>
    						<?php if(isset($resource_info['user_project'])&&!empty($resource_info['user_project'])){foreach($resource_info['user_project'] as $k=>$v){
									if(isset($resource_info[$k])&&!empty($resource_info[$k])){
						?>
						<optgroup label="<?php echo $v; ?>">
							<?php 	foreach($resource_info[$k] as $kk=>$vv){ ?>
							<option value="<?php echo $kk; ?>"><?php echo $vv; ?></option>
							<?php		}	?>
						</optgroup>
						<?php
									}else{
						?>
						<optgroup label="<?php echo $v; ?>">
							<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
						</optgroup>
						<?php 	}	}} ?>
    					</select>
    				</div>
    				<div class='am-cf'></div>
    			</div>
    			<div class="am-form-group">
    				<div class='am-u-lg-6 am-u-md-6  am-u-sm-6'>
    					<ul class='am-list' id="ModificationFromList"></ul>
    					&nbsp;
    				</div>
    				<div class='am-u-lg-6 am-u-md-6 am-u-sm-6'>
    					<ul class='am-list' id="ModificationToList">
    						<li>
    							<div class='am-g'>
    								<div class='am-u-lg-4 am-u-md-5 am-u-sm-6'>新月份</div>
    								<div class='am-u-lg-8 am-u-md-7 am-u-sm-6'>
    									<select name="data[UserProjectModificationDetail][project_time][]" disabled>
    										<?php for($project_time_year=date('Y');$project_time_year<=date('Y')+1;$project_time_year++){for($project_time_month=($project_time_year==date('Y')?date('m'):1);$project_time_month<=12;$project_time_month++){$project_time_month=str_pad($project_time_month,2,"0",STR_PAD_LEFT); ?>
										<option value="<?php echo $project_time_year.'-'.$project_time_month; ?>"><?php echo $project_time_year.'/'.$project_time_month; ?></option>
										<?php }} ?>
    									</select>
    								</div>
    								<div class='am-cf'></div>
    							</div>
    							<div class='am-g'>
    								<div class='am-u-lg-4 am-u-md-5 am-u-sm-6'>新时段</div>
    								<div class='am-u-lg-8 am-u-md-7 am-u-sm-6'>
    									<select name="data[UserProjectModificationDetail][project_hour][]" disabled>
									<?php if(isset($resource_info['user_project_time'])){foreach($resource_info['user_project_time'] as $k=>$v){ ?>
										<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
									<?php }} ?>
    									</select>
    								</div>
    								<div class='am-cf'></div>
    							</div>
    							<div class='am-g'>
    								<div class='am-u-lg-4 am-u-md-5 am-u-sm-6'>新校区</div>
    								<div class='am-u-lg-8 am-u-md-7 am-u-sm-6'>
    									<select name="data[UserProjectModificationDetail][project_site][]" disabled>
										<?php if(isset($resource_info['user_project_site'])){foreach($resource_info['user_project_site'] as $k=>$v){ ?>
										<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
										<?php }} ?>
    									</select>
    								</div>
    								<div class='am-cf'></div>
    							</div>
    							<div class='am-g'>
    								<div class='am-u-lg-4 am-u-md-5 am-u-sm-6'>新培训费</div>
    								<div class='am-u-lg-4 am-u-md-7 am-u-sm-6'>
    									<input type='text' value='0' name="data[UserProjectModificationDetail][train_fee][]" onchange="change_difference(this)" disabled />
    								</div>
    								<div class='am-cf'></div>
    							</div>
    							<div class='am-g'>
    								<div class='am-u-lg-4 am-u-md-5 am-u-sm-6'>新管理费</div>
    								<div class='am-u-lg-4 am-u-md-7 am-u-sm-6'>
    									<input type='text' value='0' name="data[UserProjectModificationDetail][management_fee][]" disabled onchange="change_difference(this)"  />
    								</div>
    								<div class='am-cf'></div>
    							</div>
    							<div class='am-g'>
    								<div class='am-u-lg-4 am-u-md-5 am-u-sm-6'>支付方式</div>
    								<div class='am-u-lg-8 am-u-md-7 am-u-sm-6'>
    									<select name="data[UserProjectModificationDetail][payment_id][]" disabled>
										<option value="0"><?php echo $ld['please_select']?></option>
										<?php if(isset($payment_list)&&sizeof($payment_list)>0){foreach($payment_list as $v){ ?>
										<option value="<?php echo $v['Payment']['id']; ?>"><?php echo $v['PaymentI18n']['name']; ?></option>
										<?php }} ?>
    									</select>
    								</div>
    								<div class='am-cf'></div>
    							</div>
    							<div class='am-g'>
    								<div class='am-u-lg-4 am-u-md-5 am-u-sm-6'>新收据编号</div>
    								<div class='am-u-lg-4 am-u-md-7 am-u-sm-6'>
    									<input type='text' value='' name="data[UserProjectModificationDetail][receipt_number][]" maxlength='7' disabled />
    								</div>
    								<div class='am-cf'></div>
    							</div>
    						</li>
    					</ul>
    				</div>
    				<div class='am-cf'></div>
    			</div>
    			<div class='am-form-group'>
    				<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-label">变更备注</label>
    				<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
    					<input type='hidden' name="data[UserProjectModification][id]" value="0" />
    					<input type='hidden' name="data[UserProjectModification][user_id]" value="<?php echo $user_list['User']['id']; ?>" />
    					<textarea name="data[UserProjectModification][remark]" maxlength="250"></textarea>
    				</div>
    				<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
    					<label class="am-form-label">差额:</label>&nbsp;<span id="difference">0</span>
    				</div>
    				<div class='am-cf'></div>
    			</div>
    			<div class="am-form-group">
    				<label class="am-u-lg-2 am-u-md-4 am-u-sm-4 am-form-label">&nbsp;</label>
    				<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
    					<button type='button' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_change_modify_submit(this)"><?php echo $ld['confirm']; ?></button>
    				</div>
    				<div class='am-cf'></div>
    			</div>
            </form>
        </div>
    </div>
</div>

<script type='text/javascript'>
	$(function(){
		var nowTemp = new Date();
		var nowDay = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0).valueOf();
		var nowMoth = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), 1, 0, 0, 0, 0).valueOf();
		var nowYear = new Date(nowTemp.getFullYear(), 0, 1, 0, 0, 0, 0).valueOf();
		$("input[name='data[UserProject][created]']").datepicker({
		      theme: 'success',locale:'<?php echo $backend_locale; ?>',
		      onRender: function(date, viewMode) {
				// 默认 days 视图，与当前日期比较
				var viewDate = nowDay;
				switch (viewMode) {
					// moths 视图，与当前月份比较
					case 1:
						viewDate = nowMoth;
					break;
					// years 视图，与当前年份比较
					case 2:
						viewDate = nowYear;
					break;
				}
				return date.valueOf() > viewDate ? 'am-disabled' : '';
		      }
		    }).data('amui.datepicker');
		    
		    $("#ModificationFrom").change(function(){
		    		var project_ids=[];
		    		$(this).find("option:selected").each(function(){
		    			project_ids.push($(this).val());
		    		});
		    		ModificationFrom(project_ids);
		    });
		    
		    $("#ModificationTo").change(function(){
		    		$(this).find("option").each(function(index,item){
		    			var project_code=$(item).val();
		    			var project_name=$(item).text();
		    			if($(item).prop('selected')==false){
		    				if(document.getElementById('ModificationTo'+project_code))$("#ModificationTo"+project_code).remove();
		    				return;
		    			}
		    			if(!document.getElementById('ModificationTo'+project_code)){
			    			var DefaultProjectHtml=$("#ModificationToList li").html();
			    			var ProjectHtml="<li id='ModificationTo"+project_code+"'>";
			    			ProjectHtml+="<div class='am-g'><div class='am-u-lg-9 am-u-md-9 am-u-sm-9'>"+project_name+"<input type='hidden' value='"+project_code+"' name='data[UserProjectModificationDetail][project_code][]' /></div><div class='am-u-lg-3 am-u-md-3 am-u-sm-3 am-text-right'><a href='javascript:void(0);' class='ModificationRemove' onclick='ModificationRemove(this)'>&times;</a></div><div class='am-cf'></div></div>";
			    			ProjectHtml+=DefaultProjectHtml;
			    			ProjectHtml+="</li>";
			    			$("#ModificationToList").append(ProjectHtml);
			    			$("#ModificationTo"+project_code+" input[type='text']").attr('disabled',false);
			    			$("#ModificationTo"+project_code+" select").attr('disabled',false).selected({maxHeight:200});
		    			}
		    		});
		    		change_difference();
		    });
	});
	
	
    function add_project(){
        $("#add_project").modal('open');
    }

    function change_difference(){
    		var old_fee_total=0,old_fee_length=0;
    		$("div.old_fee_amount").each(function(){
    			var fee_amount=$(this).html().trim();
    			fee_amount=fee_amount==''?0:parseFloat(fee_amount);
    			old_fee_total+=fee_amount;
    			old_fee_length++;
    		});
    		var fee_total=0,fee_length=0;
    		$("input[name='data[UserProjectModificationDetail][train_fee][]']").each(function(){
    			var fee_amount=$(this).val().trim();
    			fee_amount=fee_amount==''?0:parseFloat(fee_amount);
    			fee_total+=fee_amount;
    			fee_length++;
    		});
    		$("input[name='data[UserProjectModificationDetail][management_fee][]']").each(function(){
    			var fee_amount=$(this).val().trim();
    			fee_amount=fee_amount==''?0:parseFloat(fee_amount);
    			fee_total+=fee_amount;
    		});
    		if(old_fee_length==0||fee_length==1){
    			$('#difference').html('&nbsp;');
    		}else{
    			var difference=parseFloat(fee_total)-parseFloat(old_fee_total);
    			$('#difference').html(difference);
    		}
    }

    function change_project(id){
    		console.log('change project:'+id);
    	 	$("#ModificationFrom option:selected").attr("selected",false);
    	 	$("#ModificationFrom option[value='"+id+"']").attr("selected",true);
    	 	$("#ModificationFrom").trigger('changed.selected.amui');
    	 	$("#ModificationTo option:selected").attr("selected",false);
    	 	$("#ModificationTo").trigger('changed.selected.amui');
    	 	$("#ModificationToList li:gt(0)").remove();
    	 	var project_ids=[];
    		project_ids.push(id);
    	 	ModificationFrom(project_ids);
    	 	$("#change_project").modal({closeViaDimmer:false});
    }

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

    function check_student(btn){
        var first_name = document.getElementById("first_name");
        if(first_name.value==""){
            alert("用户名不能为空");
            return false;
        }
        return true;
    }

    function ajax_modify_submit(btn){
        var project_code = document.getElementById("project_code");
        var project_site = document.getElementById("project_site");
        var project_manager = document.getElementById("project_manager");
        var project_created = document.getElementById("project_created");
        var project_status = document.getElementById("project_status");
        var project_time=document.getElementById("project_time");
        var project_hour=document.getElementById("project_hour");
        if(project_code.value==""){
            alert("项目名称不能为空");
            return false;
        }
        if(project_manager.value==""){
            alert("课程顾问不能为空");
            return false;
        }
        if(project_time.value.trim()==""){
            alert("申请上课月份不能为空");
            return false;
        }
        if(project_hour.value.trim()==""){
            alert("申请上课时段不能为空");
            return false;
        }
        if(project_created.value==""){
            alert("报名日期不能为空");
            return false;
        }
        if(project_site.value=="-1"){
            alert("校区不能为空");
            return false;
        }
        if(project_status.value==""){
            alert("项目状态不能为空");
            return false;
        }
        if(project_status=='1'){
        	var postForm=$(btn).parents("form");
        	var amount_fee=$(postForm).find("input[name='data[UserProjectFee][amount]']").val().trim();
        	amount_fee=amount_fee==''?0:parseFloat(amount_fee);
        	var payment_id=$(postForm).find("select[name='data[UserProjectFee][payment_id]']").val();
        	var receipt_number=$(postForm).find("input[name='data[UserProjectFee][receipt_number]']").val().trim();
        	if(amount_fee<=0){
        		alert("请填写培训费");
            		return false;
        	}
        	if(payment_id==0){
        		alert("请选择支付方式");
            		return false;
        	}
        	if(receipt_number==''){
        		alert("请填写收据编号");
            		return false;
        	}
        }
        
        var postForm=$(btn).parents('form');
        var postData=postForm.serialize();
        $.ajax({
            url: admin_webroot+"user_projects/project_ajax_modify",
            type:"POST",
            data:postData,
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    window.location.reload();
                }else{
                    alert(data.message);
                }
            }
        });
    }
    
    function ajax_change_modify_submit(btn){
    	 if($('#ModificationFromList>li').length==0){
    	 	alert('请选择需要变更的项目');
    	 	return;
    	 }
    	 var project_codes=[],project_message_list=[];
    	 $("#ModificationToList>li:gt(0)").each(function(){
    			var project_code=$(this).find("input[type='hidden'][name='data[UserProjectModificationDetail][project_code][]']").val();
    			project_codes.push(project_code);
    			var project_name=$(this).find("input[type='hidden'][name='data[UserProjectModificationDetail][project_code][]']").parent().text();
    			var train_fee=$(this).find("input[name='data[UserProjectModificationDetail][train_fee][]']").val();
    			var management_fee=$(this).find("input[name='data[UserProjectModificationDetail][management_fee][]']").val();
    			var payment_id=$(this).find("select[name='data[UserProjectModificationDetail][payment_id][]']").val();
    			var receipt_number=$(this).find("input[name='data[UserProjectModificationDetail][receipt_number][]']").val();
    			train_fee=train_fee==''?0:parseFloat(train_fee);
    			management_fee=management_fee==''?0:parseFloat(management_fee);
    			if(train_fee<=0&&management_fee<=0){
    				project_message_list.push(project_name+' 输入培训费');
    			}else if(train_fee==0&&management_fee<=0){
    				project_message_list.push(project_name+' 输入管理费');
    			}
    			if(payment_id=='0'){
    				project_message_list.push(project_name+' 选择支付方式');
    			}
    			if(receipt_number==''){
    				project_message_list.push(project_name+' 输入收据编号');
    			}else if(!/^[0-9]{7,}$/.test(receipt_number)){
    				project_message_list.push(project_name+' 收据编号格式错误('+receipt_number+')');
    			}
    	 });
    	 if(project_codes.length==0){
    	 	alert('请选择变更的项目');
    	 	return;
    	 }else if(project_codes.length==1&&$('#ModificationFromList>li').length==1){
    	 	var old_project_code=$("#ModificationFromList>li:first-child input.ModificationFromProject").val();
    	 	var new_project_code=project_codes[0];
    	 	if(old_project_code==new_project_code){
    	 		alert('禁止变更同一项目');
    	 		return;
    	 	}
    	 }
    	 if(project_message_list.length>0){
    	 	alert(project_message_list.join('\n'));
    	 	return;
    	 }
        if(confirm("确定变更吗？")){
            var postForm=$(btn).parents('form');
            var postData=postForm.serialize();
            $.ajax({
                url: admin_webroot+"user_projects/change_project_ajax_modify",
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
    }
    
    function add_project_status_modify(select){
    		var status=select.value;
    		var postForm=$(select).parents('form');
    		if(status=='1'){
    			postForm.find("div.add_fee_detail").show();
    		}else{
    			postForm.find("div.add_fee_detail").hide();
    			postForm.find("div.add_fee_detail input[type='text']").val('');
    		}
    }
    
    function ModificationFrom(project_ids){
    		if(project_ids.length==0)return;
    		$.ajax({
	            url: admin_webroot+"user_projects/get_info",
	            type:"POST",
	            data:{'project_ids':project_ids},
	            dataType:"json",
	            success: function(result){
	            	$("#ModificationFromList").html("");
	                	if(result.code=='1'){
	                		$.each(result.data,function(index,item){
	                			var ProjectHtml="<li>";
	                			ProjectHtml+="<div class='am-g'><div class='am-u-lg-9 am-u-md-9 am-u-sm-9'>"+item['project_name']+"<input type='hidden' name='ModificationFrom[]' value='"+item['id']+"' /><input type='hidden' class='ModificationFromProject' value='"+item['project_code']+"' /></div><div class='am-u-lg-3 am-u-md-3 am-u-sm-3 am-text-right'><a href='javascript:void(0);' class='ModificationRemove' onclick='ModificationRemove(this)'>&times;</a></div><div class='am-cf'></div></div>";
    						ProjectHtml+="<div class='am-g'><div class='am-u-lg-4 am-u-md-5 am-u-sm-6'>原月份</div><div class='am-u-lg-8 am-u-md-7 am-u-sm-6'>"+item['project_time']+"</div><div class='am-cf'></div></div>";
    						ProjectHtml+="<div class='am-g'><div class='am-u-lg-4 am-u-md-5 am-u-sm-6'>原时段</div><div class='am-u-lg-8 am-u-md-7 am-u-sm-6'>"+item['project_hour_name']+"</div><div class='am-cf'></div></div>";
    						ProjectHtml+="<div class='am-g'><div class='am-u-lg-4 am-u-md-5 am-u-sm-6'>原校区</div><div class='am-u-lg-8 am-u-md-7 am-u-sm-6'>"+item['site_name']+"</div><div class='am-cf'></div></div>";
    						for(var ProjectFeeKey in item['UserProjectFee']){
    							var ProjectFee=item['UserProjectFee'][ProjectFeeKey];
    							ProjectHtml+="<div class='am-g'><div class='am-u-lg-4 am-u-md-5 am-u-sm-6'>原"+ProjectFee['fee_name']+"</div><div class='am-u-lg-4 am-u-md-7 am-u-sm-6 old_fee_amount'>"+ProjectFee['amount']+"</div><div class='am-u-lg-4 am-show-lg-only'>"+ProjectFee['receipt_number']+"</div><div class='am-cf'></div></div>";
    							ProjectHtml+="<div class='am-g am-hide-lg-only'><div class='am-u-lg-4 am-u-md-5 am-u-sm-6'>原收据编号</div><div class='am-u-lg-8 am-u-md-7 am-u-sm-6'>"+ProjectFee['receipt_number']+"</div><div class='am-cf'></div></div>";
    						}
    						ProjectHtml+="</li>";
    						$("#ModificationFromList").append(ProjectHtml);
	                		});
	                	}
	                	change_difference();
	            }
	        });
    }
    
    function ModificationRemove(btn){
    		if(confirm(j_confirm_delete)){
    			$(btn).parents('li').remove();
    			change_difference();
    			var old_projects=[];
    			$("input[type='hidden'][name='ModificationFrom[]']").each(function(){
    				old_projects.push($(this).val());
    			});
    			$("#ModificationFrom option:selected").each(function(){
    				if(old_projects.indexOf($(this).val()) ==-1){
    					$(this).attr('selected',false);
    				}
    			});
    			$("#ModificationFrom").trigger('changed.selected.amui');
    			
    			var new_projects=[];
    			$("input[type='hidden'][name='data[UserProjectModificationDetail][project_code][]']").each(function(){
    				new_projects.push($(this).val());
    			});
    			$("#ModificationTo option:selected").each(function(){
    				if(new_projects.indexOf($(this).val()) ==-1){
    					$(this).attr('selected',false);
    				}
    			});
    			$("#ModificationTo").trigger('changed.selected.amui');
    		}
    }
    
    function cancel_change_project(ModificationId){
    		if(confirm('取消变更项目?')){
			$.ajax({
		            url: admin_webroot+"user_project_modifications/remove/"+ModificationId,
		            type:"POST",
		            data:{},
		            dataType:"json",
		            success: function(result){
		            	alert(result.message);
		            	if(result.flag=='1'){
		            		window.location.reload();
		            	}
		            }
	        	});
    		}
    }
</script>