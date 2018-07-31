<style type='text/css'>
#add_fee form{max-height:500px;overflow-y:scroll;}
#change_project label.am-form-label{margin-left:0px;padding-right:0px;}
#change_project .am-form-group{text-align: left;margin-bottom: 1rem;}
@media only screen and (min-width: 641px){
	#change_project{width:90%;margin-left:-45%;}
}
@media only screen and (min-width: 1025px){
	#change_project{width:60%;margin-left:-30%;}
}
#cancel_project{width:60%;margin-left:-30%;}
#change_project{top:50%;}
#change_project form{min-height:400px;max-height:450px;overflow-y:scroll;}
#change_project ul.am-list>li{background:none;border-top:none;}
#change_project ul.am-list>li>div.am-g{margin-bottom:0.5rem;}
#change_project ul.am-list>li .ModificationRemove,#change_project ul.am-list>li .ModificationRemove:hover{color:#dd514c;text-decoration:none;}
#change_project #ModificationToList>li:first-child{display:none;}
#change_project #ModificationToList>li{padding-bottom:1rem;}
#change_project #ModificationToList>li input[type='text'],#change_project #ModificationToList>li .am-selected-btn{padding-top:3px;padding-bottom:3px;}
</style>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-product">
    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
        <?php echo $form->create('/user_projects',array('action'=>'fee_view/'.$project_list['id'],'id'=>'user_project_edit_form','class'=>'am-form am-form-horizontal','name'=>'user_project_edit','type'=>'POST'));?>
        <input type='hidden' id="admin_user" value="<?php echo isset($admin['id'])?$admin['id']:0; ?>" />
    	<input type='hidden' id="project_manager" value="<?php echo isset($project_list['manager_id'])?$project_list['manager_id']:0; ?>" />
    	<input type='hidden' id="class_manager" value="<?php echo isset($project_list['class_user_id'])?$project_list['class_user_id']:0; ?>" />
        <input type="hidden" name="data[UserProject][id]" id="_id" value="<?php echo $project_list['id'];?>" />
        <div class="am-g">
            <div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:<?php echo isset($configs['is_qa_environment'])&&$configs['is_qa_environment']=='1'?90:56; ?>,animation:'slide-top'}" style="height: 42px; margin: 0px 0px 8px;">
                <ul>
			<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
			<li><a href="#project">费用信息</a></li>
                </ul>
            </div>
            <div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
                <?php if($project_list['status']=='3'&&($svshow->operator_privilege("reset_project_class")||(isset($project_list['class_user_id'])&&$project_list['class_user_id']==$admin['id']))){ 
                	
                	?>
                	   <input type='button' class='am-btn am-btn-danger am-radius am-btn-sm' onclick="ajax_reset_project_class(<?php echo $project_list['id'];?>)" value='设为未分班' >
                <?php } ?>
		<?php if($can_edit){?>
			<input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit'];?>" />
			<input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
		<?php } ?>
            </div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Basic_information_pancel'}"><?php echo $ld['basic_information'] ?>&nbsp;</h4>
            </div>
            <div id="Basic_information_pancel" class="am-panel-collapse am-collapse am-in">
                <div id="basic_information" class="scrollspy_nav_hid"></div>
                <div class="am-panel-bd">
			<div class='am-form-group'>
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">学生</label>
				<div class='am-u-lg-5 am-u-md-5 am-u-sm-5 am-padding-top-xs'>
					<?php echo isset($project_user['User']['first_name'])&&trim($project_user['User']['first_name'])!=''?$project_user['User']['first_name']:''; ?>&nbsp;
				</div>
			</div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">项目名称</label>
                        <div class="am-u-lg-5 am-u-md-5 am-u-sm-5 am-padding-top-xs">
                		<input type='hidden' id="project_code" name='data[UserProject][project_code]' value="<?php echo $project_list['project_code']; ?>" />
                		<?php echo isset($resource_info['all_user_project'][$project_list['project_code']])?$resource_info['all_user_project'][$project_list['project_code']]:''; ?>
                        </div>
                	   <div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
				<?php if($svshow->operator_privilege("project_change")&&(($admin['actions']=='all')||($project_list['manager_id']==$admin['id']))){?>
					<?php if($change==1 && $project_list['status']!='0' && $project_list['status']!='4' && $project_list['status']!='5' && $project_list['status']!='6'){?>
					<input class="am-btn am-btn-warning am-radius am-btn-sm" type="button" value="变更项目" onclick="change_project(<?php echo $project_list['id'];?>);"/>
					<?php }else if(isset($ModificationData)&&$project_list['status']=='6'){?>
					<input class="am-btn am-btn-danger am-radius am-btn-sm" type="button" value="取消变更项目" onclick="cancel_change_project(<?php echo $ModificationData['UserProjectModification']['id'];?>);"/>
					<?php } ?>
				<?php }?>
                	   </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">班级名称</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8 am-padding-top-xs">
                        		<?php echo isset($project_list['class_name'])?$project_list['class_name']:'';echo isset($project_list['class_user_name'])?('-'.$project_list['class_user_name']):''; ?>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">申请上课月份</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
				<select name="data[UserProject][project_time]" id="project_time" data-am-selected="{maxHeight:250}">
					<option value="0"><?php echo $ld['please_select']; ?></option>
					<?php
						for($project_time_year=date('Y',strtotime('-1 year'));$project_time_year<=date('Y')+1;$project_time_year++){
							for($project_time_month=1;$project_time_month<=12;$project_time_month++){
									$project_time_month=str_pad($project_time_month,2,"0",STR_PAD_LEFT);
					?>
					<option value="<?php echo $project_time_year.'-'.$project_time_month; ?>" <?php echo isset($project_list['project_time'])&&date('Y/m',strtotime($project_list['project_time']))==$project_time_year.'/'.$project_time_month?'selected':''; ?>><?php echo $project_time_year.'/'.$project_time_month; ?></option>
					<?php
							}
						}
					?>
				</select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">申请上课时段</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <select name="data[UserProject][project_hour]" id='user_project_hour' data-am-selected="{maxHeight:300}" >
                                <option value="-1"><?php echo $ld['please_select']?></option>
                                <?php if(!empty($resource_info['user_project_time'])){
                                    foreach($resource_info['user_project_time'] as $k=>$v){?>
                                        <option <?php if($project_list['project_hour'] ==$k){?>selected<?php }?> value="<?php echo $k;?>"><?php echo $v;?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">申请校区</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <select name="data[UserProject][project_site]" id='user_project_site' data-am-selected="{maxHeight:300}" >
                                <option value="-1"><?php echo $ld['please_select']?></option>
                                <?php if(!empty($resource_info['user_project_site'])){
                                    foreach($resource_info['user_project_site'] as $k=>$v){?>
                                        <option <?php if($project_list['project_site'] ==$k){?>selected<?php }?> value="<?php echo $k;?>"><?php echo $v;?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">课程顾问</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <select name="data[UserProject][manager]" id="project_manager" data-am-selected="{maxHeight:300}" >
                                <option value="-1"><?php echo $ld['please_select']?></option>
                                <?php if(!empty($manager_list)){
                                    foreach($manager_list as $v){?>
                                        <option value="<?php echo $v['Operator']['id'];?>" <?php if($project_list['manager_id'] ==$v['Operator']['id']){?>selected<?php }?>><?php echo $v['Operator']['name'];?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">报名日期</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <input type="text" readonly required data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" class="am-form-field am-input-sm" name="data[UserProject][created]" value="<?php echo isset($project_list['created'])?date('Y-m-d',strtotime($project_list['created'])):'';?>" />
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">项目状态</label>
                        <div class="am-u-lg-5 am-u-md-5 am-u-sm-5 am-padding-top-xs"><?php
	                        	if($project_list['status']=='0'){
	                                echo "待付款";
	                            }elseif($project_list['status']=='1'){
	                                echo "已付款";
	                            }elseif($project_list['status']=='2'){
	                                echo "待分班";
	                            }elseif($project_list['status']=='3'){
	                                echo "已分班";
	                            }elseif($project_list['status']=='4'){
	                                echo "变更中";
	                            }elseif($project_list['status']=='5'){
	                                echo "已取消";
	                            }elseif($project_list['status']=='6'){
	                                echo "变更中";
	                            }
                        	?>
                        </div>
                        <div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
		                <?php if($svshow->operator_privilege("fee_add")&&($project_list['manager_id']==$admin['id'])&&($project_list['status']=='2'||$project_list['status']=='3')){ ?>
		                	   <input type='button' class='am-btn am-btn-danger am-radius am-btn-sm' onclick="cancel_project(<?php echo $project_list['id'];?>)" value='退款' >
		                <?php } ?>
                	   </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">学生备注</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <textarea name="data[UserProject][remark]"><?php echo isset($project_list['remark'])?$project_list['remark']:'';?></textarea>
                        </div>
                    </div>
     			<div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">顾问备注</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <textarea name="data[UserProject][manager_remark]"><?php echo isset($project_list['manager_remark'])?$project_list['manager_remark']:'';?></textarea>
                        </div>
                    </div>
     			<div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">教务备注</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <textarea name="data[UserProject][project_remark]"><?php echo isset($project_list['project_remark'])?$project_list['project_remark']:'';?></textarea>
                        </div>
                    </div>
     			<div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">财务备注</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <textarea name="data[UserProject][fee_remark]"><?php echo isset($project_list['fee_remark'])?$project_list['fee_remark']:'';?></textarea>
                        </div>
                    </div>
     			
                </div>
            </div>
        </div>
            <div class="am-panel am-panel-default">
                <div class="am-panel-hd">
                    <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#project_pancel'}">费用信息&nbsp;</h4>
                </div>
                <div id="project_pancel" class="am-panel-collapse am-collapse am-in">
                    <div id="project" class="scrollspy_nav_hid"></div>
                    <div class="am-panel-bd">
                        <p class='am-text-right'>
                   		<?php if($svshow->operator_privilege("fee_add")){?>
                            <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick="add_fee();">
                                    <span class="am-icon-plus"></span>&nbsp;<?php echo $ld['add'] ?>
                            </a>
                            <?php }?>
                        </p>
                        <table class="am-table table-main">
                            <thead>
                            <tr>
                                <th>类目</th>
                                <th>付款金额</th>
                                <th>付款方式</th>
                                <th>付款日期</th>
                                <th>收据编号</th>
                                <th>财务状态</th>
                                <th>备注</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($fee_data) && sizeof($fee_data)>0){foreach($fee_data as $k=>$v){?>
                                <tr>
                                    <td><?php echo isset($resource_info['user_project_fee'][$v['UserProjectFee']['fee_type']])?$resource_info['user_project_fee'][$v['UserProjectFee']['fee_type']]:"-"?></td>
                                    <td><?php echo $v['UserProjectFee']['amount'];?></td>
                                    <td><?php echo isset($payment_names[$v['UserProjectFee']['payment_id']])?$payment_names[$v['UserProjectFee']['payment_id']]:"-";?></td>
                                    <td><?php echo date('Y-m-d',strtotime($v['UserProjectFee']['payment_time']));?></td>
                                    <td><?php echo $v['UserProjectFee']['receipt_number'];?></td>
                                    <td>
                                        <?php if($v['UserProjectFee']['check_status']==1){
                                            echo "已审核";
                                        }else{
                                            echo "未审核";
                                        }?>
                                    </td>
                                    <td><?php echo $v['UserProjectFee']['remark'];?></td>
                                    <td>
                                    	<?php if($svshow->operator_privilege("fee_edit")){?>
	                                        <?php if($v['UserProjectFee']['check_status']==0){?>
	                                            <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_fee(<?php echo $v['UserProjectFee']['id'];?>)">
	                                                <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
	                                            </a>
	                                        <?php }?>
                                        <?php }?>
                                        <?php if($svshow->operator_privilege("fee_delete")){?>
	                                        <?php if($v['UserProjectFee']['check_status']==0&&$project_list['status']!='5'){?>
	                                            <a class="mt am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'user_projects/fee_remove/<?php echo $v['UserProjectFee']['id'] ?>');">
	                                                <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
	                                            </a>
	                                        <?php }?>
                                        <?php }?>
                                    </td>
                                </tr>
                            <?php }}?>
                            </tbody>
                        </table>
                    </div>
                </div>
               </div>
                <?php echo $form->end(); ?>
      </div>
</div>

<div class="am-modal am-modal-no-btn" id="add_fee" style="top:50%;">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title">添加费用</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
                <input type="hidden" id="fee_id" name="data[UserProjectFee][id]" value="">
                <input type="hidden" id="fee_project" name="data[UserProjectFee][user_project_id]" value="<?php echo $project_list['id'];?>">
                <div class="am-panel-bd">
   			<div class="am-form-group">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">学生</label>
				<div class='am-u-lg-5 am-u-md-5 am-u-sm-5 am-padding-top-xs am-text-left'>
					<?php echo isset($project_user['User']['first_name'])&&trim($project_user['User']['first_name'])!=''?$project_user['User']['first_name']:''; ?>&nbsp;
				</div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">类目</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7">
                            <select name="data[UserProjectFee][fee_type]" id='fee_type' data-am-selected="{maxHeight:300}" >
                                <option value="-1"><?php echo $ld['please_select']?></option>
                                <?php if(!empty($resource_info['user_project_fee'])){
                                    foreach($resource_info['user_project_fee'] as $k=>$v){?>
                                        <option value="<?php echo $k;?>"><?php echo $v;?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">付款金额</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7"><input type="text" name="data[UserProjectFee][amount]" id="amount" value=""></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">付款方式</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7 am-text-left">
                            <select name="data[UserProjectFee][payment_id]" id='payment_id' data-am-selected="{maxHeight:300}" >
                                <option value="-1"><?php echo $ld['please_select']?></option>
                                <?php if(!empty($payment_names)){
                                    foreach($payment_names as $k=>$v){?>
                                        <option value="<?php echo $k;?>"><?php echo $v;?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">付款日期</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7">
                        	<div class="am-input-group am-margin-top-0">
					<input type="text"  class="am-form-field" readonly name="data[UserProjectFee][payment_time]" value="" />
					<span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
						<i class="am-icon-remove"></i>
					</span>
				</div>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">收据编号</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7"><input id="receipt_number" maxlength='7' type="text" name="data[UserProjectFee][receipt_number]" value=""/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">备注</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7"><input type="text" name="data[UserProjectFee][remark]" value=""/></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">收款人</label>
                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7 am-text-left am-padding-top-xs" id='fee_payee' placeholder="<?php echo $admin['name']; ?>"><?php //echo $admin['name']; ?></div>
                    </div>
                    <div>
                        <button type='button' style='margin-top:1rem;' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_modify_submit(this)"><?php echo $ld['confirm']; ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="cancel_project">
	<div class="am-modal-dialog">
		<div class="am-modal-hd" style=" z-index: 11;">
			<h4 class="am-popup-title">退款</h4>
			<span data-am-modal-close class="am-close">&times;</span>
		</div>
		<div class="am-modal-bd">
			<form method='POST' class='am-form am-form-horizontal'>
				<input type='hidden' name="data[UserProject][id]" value="0" />
				<div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-text-left">学生</label>
	                        <div class="am-u-lg-7 am-u-md-7 am-u-sm-7 am-text-left">
						<?php echo isset($project_user['User']['first_name'])&&trim($project_user['User']['first_name'])!=''?$project_user['User']['first_name']:''; ?>&nbsp;
	                        </div>
	                    </div>
				<div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-text-left">项目</label>
	                        <div class="am-u-lg-7 am-u-md-7 am-u-sm-7 am-text-left">
						<?php echo isset($resource_info['all_user_project'][$project_list['project_code']])?$resource_info['all_user_project'][$project_list['project_code']]:$project_list['project_code']; ?>
	                        </div>
	                    </div>
				<?php
					$check_project_fee_total=0;
					if(isset($fee_data)&&sizeof($fee_data)>0){foreach($fee_data as $k=>$v){
						if($v['UserProjectFee']['check_status']=='0')continue;
						$check_project_fee_total+=$v['UserProjectFee']['amount'];
				?>
				<div class="am-form-group">
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
						<label class='am-checkbox am-success am-padding-top-0'>
							<input type='checkbox' name="data[UserProjectFee][<?php echo $k; ?>][id]" value="<?php echo $v['UserProjectFee']['id']; ?>" data-am-ucheck checked /><input type='hidden' name="data[UserProjectFee][<?php echo $k; ?>][fee_type]" value="<?php echo $v['UserProjectFee']['fee_type']; ?>" /><?php echo isset($resource_info['user_project_fee'][$v['UserProjectFee']['fee_type']])?$resource_info['user_project_fee'][$v['UserProjectFee']['fee_type']]:"-"; ?>
						</label>
					</div>
					<div class='am-u-lg-3 am-u-md-3 am-u-sm-3'>
						原金额:&nbsp;<?php echo $v['UserProjectFee']['amount']; ?>
					</div>
					<div class='am-u-lg-3 am-u-md-3 am-u-sm-3'>
						<div class='am-fl am-margin-0'>退款金额:&nbsp;</div>
						<div class='am-fl am-margin-0'><input type='text' name="data[UserProjectFee][<?php echo $k; ?>][payment_amount]" value="<?php echo $v['UserProjectFee']['amount']; ?>" size="5" class='am-padding-0' onchange="cancel_project_refund(this)" /></div>
						<div class='am-cf'></div>
					</div>
					<div class='am-u-lg-3 am-u-md-3 am-u-sm-3'>
						回收收据:&nbsp;<?php echo $v['UserProjectFee']['receipt_number']; ?><input type='hidden' name="data[UserProjectFee][<?php echo $k; ?>][receipt_number]" value="<?php echo $v['UserProjectFee']['receipt_number']; ?>" />
					</div>
					<div class='am-cf'></div>
				</div>
				<?php
					}}
				?>
				<div class="am-form-group">
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">&nbsp;</div>
					<div class='am-u-lg-3 am-u-md-3 am-u-sm-3 am-text-center'>原金额总计:&nbsp;<?php echo $check_project_fee_total; ?></div>
					<div class='am-u-lg-3 am-u-md-3 am-u-sm-3 am-text-center'>退款金额:&nbsp;<span id='cancel_project_refund'><?php echo $check_project_fee_total; ?></span></div>
					<div class='am-cf'></div>
				</div>
				<div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-text-left am-padding-top-xs">退款方式</label>
	                        <div class="am-u-lg-4 am-u-md-5 am-u-sm-7">
					<select name="data[AccountInformation][payment_id]" data-am-selected="{maxHeight:250}">
						<option value='-1'><?php echo $ld['please_select'] ?></option>
						<?php if(isset($payment_names)&&sizeof($payment_names)>0){foreach($payment_names as $k=>$v){ ?>
						<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
						<?php }} ?>
					</select>
	                        </div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-text-left am-padding-top-xs">备注</label>
	                        <div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
	                			<textarea name="data[AccountInformation][note]" maxlength='250'></textarea>
	                        </div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-left">&nbsp;</label>
	                        <div class="am-u-lg-5 am-u-md-7 am-u-sm-7 am-text-left">
	                    	<button type='button' class='am-btn am-btn-success am-radius' onclick="ajax_cancel_project(this)"><?php echo $ld['submit']; ?></button>
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
										<?php if(isset($payment_names)&&sizeof($payment_names)>0){foreach($payment_names as $k=>$v){ ?>
										<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
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
    					<input type='hidden' name="data[UserProjectModification][user_id]" value="<?php echo $project_user['User']['id']; ?>" />
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
	
	$("input[name='data[UserProjectFee][payment_time]']").datepicker({
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
	    
	    var admin_user=$("#admin_user").val();
	    var project_manager=$("#project_manager").val();
	    var class_manager=$("#class_manager").val();
	    if(admin_user==0){
	    		$("#add_fee select[name='data[UserProjectFee][fee_type]'] option[value!='-1']").attr("disabled",true);
	    }else if(admin_user!=project_manager&&admin_user!=class_manager){
	    		$("#add_fee select[name='data[UserProjectFee][fee_type]'] option[value='0']").attr("disabled",true);
	    		$("#add_fee select[name='data[UserProjectFee][fee_type]'] option[value='1']").attr("disabled",true);
	    }else if(admin_user!=project_manager){
	    		$("#add_fee select[name='data[UserProjectFee][fee_type]'] option[value='0']").attr("disabled",true);
	    }
	    $("#add_fee select[name='data[UserProjectFee][fee_type]']").trigger('changed.selected.amui');
	    
	    $("#cancel_project div.am-form-group input[type='checkbox']").click(function(){
	    		var refundTotal=0;
	    		$("#cancel_project div.am-form-group input[type='checkbox']:checked").each(function(){
	    			var refund_fee=$(this).parents("div.am-form-group").find("input[type='text']").val().trim();
	    			refund_fee=refund_fee==''?0:parseFloat(refund_fee);
	    			refundTotal+=refund_fee;
	    		});
	    		$("span#cancel_project_refund").html(refundTotal);
	    });
	    
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

    function add_fee(){
        $("#add_fee select[name='data[UserProjectFee][fee_type]'] option:eq(0)" ).attr('selected',true);
        $("#add_fee select[name='data[UserProjectFee][fee_type]']").trigger('changed.selected.amui');
        $("#add_fee select[name='data[UserProjectFee][payment_id]'] option:eq(0)" ).attr('selected',true);
        $("#add_fee select[name='data[UserProjectFee][payment_id]']").trigger('changed.selected.amui');
        $("#add_fee h4").html("添加费用");
        $("#fee_id").val("0");
        $("#add_fee input[name='data[UserProjectFee][amount]']").val('');
        $("#add_fee input[name='data[UserProjectFee][receipt_number]']").val('');
        $("#add_fee input[name='data[UserProjectFee][remark]']").val('');
        $("#add_fee #fee_payee").html($("#add_fee #fee_payee").attr('placeholder'));
        $("#add_fee").modal('open');
    }

    function edit_fee(id){
        $.ajax({
            url: admin_webroot+"user_projects/get_fee_info/"+id,
            type:"GET",
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    $("#add_fee select[name='data[UserProjectFee][fee_type]'] option[value='"+data.data.UserProjectFee.fee_type+"']" ).attr('selected',true);
                    $("#add_fee select[name='data[UserProjectFee][fee_type]']").trigger('changed.selected.amui');
                    $("#add_fee select[name='data[UserProjectFee][payment_id]'] option[value='"+data.data.UserProjectFee.payment_id+"']" ).attr('selected',true);
                    $("#add_fee select[name='data[UserProjectFee][payment_id]']").trigger('changed.selected.amui');
                    $("#add_fee h4").html("编辑费用");
                    $("#fee_id").val(data.data.UserProjectFee.id);
                    $("#add_fee input[name='data[UserProjectFee][payment_time]']").val(data.data.UserProjectFee.payment_time);
                    $("#add_fee input[name='data[UserProjectFee][amount]']").val(data.data.UserProjectFee.amount);
                    $("#add_fee input[name='data[UserProjectFee][receipt_number]']").val(data.data.UserProjectFee.receipt_number);
                    $("#add_fee input[name='data[UserProjectFee][remark]']").val(data.data.UserProjectFee.remark);
                    $("#add_fee #fee_payee").html(data.data.UserProjectFee.fee_payee);
                    $("#add_fee").modal('open');
                }else{
                    alert(data.message);
                }
            }
        });
    }
    
    function change_status(id){
        $.ajax({
            url: admin_webroot+"user_projects/change_status",
            type:"POST",
            data:{'id':id},
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

    function ajax_modify_submit(btn){
    	var fee_form_check=false;
    	$("#add_fee .am-form-group").each(function(){
    		if(fee_form_check)return;
		var field_title=$(this).find("label").html();
		var input_field_value=$(this).find("input[type='text']").val();
		var select_field_value=$(this).find("select").val();
		if(typeof(input_field_value)!='undefined'&&input_field_value==""){
			if($(this).find("input[type='text']").attr('name')=='data[UserProjectFee][remark]')return;
			fee_form_check=true;
			alert("请填写"+field_title);
			return;
		}else if(typeof(select_field_value)!='undefined'&&select_field_value=='-1'){
			fee_form_check=true;
			alert("请选择"+field_title);
			return;
		}
    	});
    	if(fee_form_check)return false;
    	var receipt_number=document.getElementById('receipt_number').value;
    	if(receipt_number.length!=7){
    		alert("收据编号格式错误");
		return;
    	}
    	$(btn).button('loading');
        var postForm=$(btn).parents('form');
        var postData=postForm.serialize();
        $.ajax({
            url: admin_webroot+"user_projects/fee_ajax_modify",
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
    
    function cancel_project(user_project_id){
    		$("#cancel_project input[name='data[UserProject][id]']").val(user_project_id);
    		$('#cancel_project').modal('open');
    }
    
    function ajax_cancel_project(btn){
    		var postForm=$(btn).parents("form");
    		var payment_id=$(postForm).find("select").val();
    		var payment_amount=$(postForm).find("span#cancel_project_refund").html().trim();
    		payment_amount=payment_amount==''?0:payment_amount;
    		if(payment_id==-1){
    			alert('请选择退款方式');
    			return;
    		}
    		if(parseFloat(payment_amount)<=0){
    			alert('请输入退款金额');
    			return;
    		}
    		var postData=postForm.serialize();
	       $.ajax({
		            url: admin_webroot+"user_projects/ajax_cancel_project",
		            type:"POST",
		            data:postData,
		            dataType:"json",
		            success: function(data){
		            		alert(data.message);
		            		if(data.code='1'){
		            			window.location.reload();
		            		}
		            }
	        });
    }
    
    function ajax_reset_project_class(user_project_id){
    		if(confirm('确认设置为未分班?')){
    			$.ajax({
			            url: admin_webroot+"user_projects/ajax_reset_project_class",
			            type:"POST",
			            data:{'user_project_id':user_project_id},
			            dataType:"json",
			            success: function(data){
			            		alert(data.message);
			            		if(data.code='1'){
			            			window.location.reload();
			            		}
			            }
	        	});
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
    
    function cancel_project_refund(){
    		var refundTotal=0;
    		$("#cancel_project div.am-form-group input[type='checkbox']:checked").each(function(){
    			var refund_fee=$(this).parents("div.am-form-group").find("input[type='text']").val().trim();
    			refund_fee=refund_fee==''?0:parseFloat(refund_fee);
    			refundTotal+=refund_fee;
    		});
    		$("span#cancel_project_refund").html(refundTotal);
    }
</script>