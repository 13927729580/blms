<style type="text/css">
    .am-panel-title div{font-weight:bold;}
    #SearchForm{padding-top:8px;}
    #SearchForm li{margin-bottom:10px;}
    #SearchForm .am-selected-list li{margin-bottom: 0px;}
    .am-form-horizontal{padding-top:0px;}
</style>
<div>
    <?php echo $form->create('UserProjectModification',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'class'=>'am-form am-form-horizontal'));?>
    <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">原项目</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7 am-padding-left-0">
                <select name="old_project_code" id='old_project_code' data-am-selected="{maxHeight:300}" >
    			<optgroup label="<?php echo $ld['please_select']; ?>">
				<option value="-1"><?php echo $ld['all_data']?></option>
			</optgroup>
    			<?php if(isset($resource_info['user_project'])&&!empty($resource_info['user_project'])){foreach($resource_info['user_project'] as $k=>$v){
						if(isset($resource_info[$k])&&!empty($resource_info[$k])){
			?>
			<optgroup label="<?php echo $v; ?>">
				<?php 	foreach($resource_info[$k] as $kk=>$vv){ ?>
				<option value="<?php echo $kk; ?>" <?php if($old_project_code ==$kk){?>selected<?php }?>><?php echo $vv; ?></option>
				<?php		}	?>
			</optgroup>
			<?php
						}else{
			?>
			<optgroup label="<?php echo $v; ?>">
				<option value="<?php echo $k; ?>" <?php if($old_project_code ==$k){?>selected<?php }?>><?php echo $v; ?></option>
			</optgroup>
			<?php 	}	}} ?>
                </select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">新项目</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7 am-padding-left-0">
                <select name="project_code" id='project_code' data-am-selected="{maxHeight:300}" >
                    <optgroup label="<?php echo $ld['please_select']; ?>">
				<option value="-1"><?php echo $ld['all_data']?></option>
			</optgroup>
    			<?php if(isset($resource_info['user_project'])&&!empty($resource_info['user_project'])){foreach($resource_info['user_project'] as $k=>$v){
						if(isset($resource_info[$k])&&!empty($resource_info[$k])){
			?>
			<optgroup label="<?php echo $v; ?>">
				<?php 	foreach($resource_info[$k] as $kk=>$vv){ ?>
				<option value="<?php echo $kk; ?>" <?php if($project_code ==$kk){?>selected<?php }?>><?php echo $vv; ?></option>
				<?php		}	?>
			</optgroup>
			<?php
						}else{
			?>
			<optgroup label="<?php echo $v; ?>">
				<option value="<?php echo $k; ?>" <?php if($project_code ==$k){?>selected<?php }?>><?php echo $v; ?></option>
			</optgroup>
			<?php 	}	}} ?>
                </select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">变更时间</label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-padding-left-0 am-padding-right-0">
                <div class="am-input-group">
                    <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" name="start_date_time" value="<?php echo isset($start_date_time)?$start_date_time:"";?>" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
                </div>
            </div>
            <div class=" am-text-center am-fl " style="margin-top:7px;">-</div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-padding-left-0 am-padding-right-0">
                <div class="am-input-group">
                    <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="end_date_time" value="<?php echo isset($end_date_time)?$end_date_time:"";?>" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
                </div>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">课程顾问</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7 am-padding-left-0">
            		<select name="manager" data-am-selected="{maxHeight:300}">
            			<option value="0"><?php echo $ld['please_select']; ?></option>
            			<?php if(isset($operator_list)&&sizeof($operator_list)>0){foreach($operator_list as $k=>$v){ ?>
            			<option value="<?php echo $k; ?>" <?php echo isset($manager)&&$manager==$k?'selected':''; ?>><?php echo $v; ?></option>
            			<?php }} ?>
            		</select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">姓名</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7 am-padding-left-0">
                <input type="text" name="name_keyword" id="name_keyword" value="<?php echo isset($name_keyword)?$name_keyword:'';?>"/>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">关键字</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7 am-padding-left-0">
                <input type="text" name="keyword" id="keyword" value="<?php echo isset($keyword)?$keyword:'';?>"/>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">审核状态</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7 am-padding-left-0">
                <select name="check_status" id='check_status' data-am-selected="{maxHeight:300}" >
                    <option value="-1"><?php echo $ld['all_data']?></option>
                    <option value="1" <?php if($check_status ==1){?>selected<?php }?> >是</option>
                    <option value="0" <?php if($check_status ==0){?>selected<?php }?> >否</option>
                </select>
            </div>
        </li>
        <li>
            <div class="am-u-sm-6 am-u-md-8 am-u-sm-7">
                <input type="submit"  class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>" />
            </div>
        </li>
    </ul>
    <div class="am-g">
        <label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-group-label">&nbsp;</label>
        <div id="changeAttr" class="am-u-lg-11 am-u-md-11 am-u-sm-11"></div>
        <div style="clear:both;"></div>
    </div>
    <?php echo $form->end()?>
</div>
<div class="listtable_div_btm">
    <div class="am-g">
        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success am-margin-top-0" ><input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck  type="checkbox" />学生</label></div>
        <div class="am-u-lg-3 am-u-md-2 am-u-sm-2">原项目</div>
        <div class="am-u-lg-3 am-u-md-2 am-u-sm-2">新项目</div>
        <div class="am-u-lg-1 am-show-lg-only">差额</div>
        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">审核状态</div>
        <div class="am-u-lg-1 am-u-md-2 am-u-sm-2">操作</div>
    </div>
    <?php if(isset($user_list) && sizeof($user_list)>0){foreach($user_list as $k=>$v){//pr($v); ?>
        <div class="am-g">
            <div class="listtable_div_top" >
                <div style="margin:10px auto;" class="am-g">
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class="am-checkbox am-success" style="top: 0px; margin: 0px;"><input type="checkbox" name="checkboxes[]" value="<?php echo $v['UserProjectModification']['id']?>"  data-am-ucheck /><?php echo ($svshow->operator_privilege("student_edit")||$svshow->operator_privilege("student_detail"))?$html->link($v['UserProjectModification']['user_name'],'/user_projects/view/'.$v['User']['id']):$v['UserProjectModification']['user_name'];?><br/><?php echo $v['UserProjectModification']['user_mobile'];?><br ><?php echo !empty($v['UserProjectModification']['manager'])?"<i class='am-icon am-icon-user'></i>&nbsp;".$v['UserProjectModification']['manager']:"-"?></label></div>
                    <div class="am-u-lg-3 am-u-md-2 am-u-sm-2">
            			<?php
            				$old_project_list=isset($project_modification_list[$v['UserProjectModification']['id']][0])?$project_modification_list[$v['UserProjectModification']['id']][0]:array();
        					foreach($old_project_list as $vv){
        				?>
        				<p class='am-margin-bottom-xs am-margin-top-xs'><?php 
        							echo $html->link(isset($resource_info['all_user_project'][$vv['UserProjectModificationDetail']['project_code']])?$resource_info['all_user_project'][$vv['UserProjectModificationDetail']['project_code']]:"-",'/user_projects/fee_view/'.$vv['UserProjectModificationDetail']['user_project_id']);
        							echo floor($vv['UserProjectModificationDetail']['train_fee'])>0?("&nbsp;培训费:".floor($vv['UserProjectModificationDetail']['train_fee'])):'';
        							echo floor($vv['UserProjectModificationDetail']['management_fee'])>0?("&nbsp;管理费:".floor($vv['UserProjectModificationDetail']['management_fee'])):'';
        					?>
        				</p>
        				<?php
        					}
            			?>
                    </div>
                    <div class="am-u-lg-3 am-u-md-2 am-u-sm-2">
            			<?php
            				$new_project_list=isset($project_modification_list[$v['UserProjectModification']['id']][1])?$project_modification_list[$v['UserProjectModification']['id']][1]:array();
        					foreach($new_project_list as $vv){
        				?>
        				<p class='am-margin-bottom-xs am-margin-top-xs'><?php 
        							echo $html->link(isset($resource_info['all_user_project'][$vv['UserProjectModificationDetail']['project_code']])?$resource_info['all_user_project'][$vv['UserProjectModificationDetail']['project_code']]:"-",'/user_projects/fee_view/'.$vv['UserProjectModificationDetail']['user_project_id']);
        							echo floor($vv['UserProjectModificationDetail']['train_fee'])>0?("&nbsp;培训费:".floor($vv['UserProjectModificationDetail']['train_fee'])):'';
        							echo floor($vv['UserProjectModificationDetail']['management_fee'])>0?("&nbsp;管理费:".floor($vv['UserProjectModificationDetail']['management_fee'])):'';
        					?>
        				</p>
        				<?php
        					}
            			?>
                    </div>
                    <div class="am-u-lg-1 am-show-lg-only"><?php echo (floatval($v['UserProjectModification']['new_fee_total'])-floatval($v['UserProjectModification']['old_fee_total']))!=0?(floatval($v['UserProjectModification']['new_fee_total'])-floatval($v['UserProjectModification']['old_fee_total'])):'-'; ?></div>
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                        <?php
                        	     echo date('Y-m-d',strtotime($v['UserProjectModification']['created']));
	                        if($v['UserProjectModification']['check_status']=='1'){
	                            echo "<br />已审核&nbsp;".(isset($operator_list[$v['UserProjectModification']['check_operator']])?$operator_list[$v['UserProjectModification']['check_operator']]:'')."&nbsp;";
	                            echo date('Y-m-d',strtotime($v['UserProjectModification']['check_time']));
	                        }else{
	                            echo "<br />未审核";
	                        }
                        ?><br /><?php echo trim($v['UserProjectModification']['remark'])!=''?"<span class='am-icon am-icon-pencil-square-o am-margin-left-xs' title='".($v['UserProjectModification']['remark'])."' alt='".($v['UserProjectModification']['remark'])."'></span>":"";?></div>
                    <div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
                        <?php if($v['UserProjectModification']['check_status']=='0'&&$svshow->operator_privilege("modification_check")){?>
                            <a class="mt am-btn am-btn-success am-btn-xs am-seevia-btn-edit" onclick="change_status(<?php echo $v['UserProjectModification']['id'];?>,1)">
                                <span class="am-icon-pencil-square-o"></span> 审核
                            </a>
                        <?php }?>
                        <?php if($v['UserProjectModification']['check_status']=='0'&&$svshow->operator_privilege("modification_delete")){?>
                            <a class="mt am-btn am-btn-danger am-btn-xs" href="javascript:void(0);" onclick="cancel_change_project('<?php echo $v['UserProjectModification']['id'] ?>');">
                                <span class="am-icon-trash-o"></span> <?php echo '取消变更'; ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php }}else{?>
        <div>
            <div class="no_data_found" ><?php echo $ld['no_data_found']?></div>
        </div>
    <?php }?>
</div>
<?php if(isset($user_list) && sizeof($user_list)){?>
    <div id="btnouterlist" class="btnouterlist am-form-group am-hide-sm-only">
        <div class="am-u-lg-6 am-u-md-12 am-u-sm-12">
            <div class="am-fl">
                <label class="am-checkbox am-success" style="margin:5px 5px 5px 0px;"><input onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)"  class="am-btn am-radius am-btn-success am-btn-sm" type="checkbox" data-am-ucheck /><?php echo $ld['select_all']?></label>&nbsp;
            </div>
            <div class="am-fl">
                <select id="barch_opration_select" data-am-selected>
                    <option value="1">批量审核</option>
                    <option value="0">批量取消审核</option>
                    <!-- <option value="2">批量导出</option>-->
                </select>
            </div>
            <div class="am-fl">
                <input type="button" id="btn" value="<?php echo $ld['submit']?>" class="am-btn am-btn-sm am-btn-danger am-btn-radius" onclick="submit_operations()" />&nbsp;
            </div>
        </div>
        <div><?php echo $this->element('pagers')?></div>
        <div class="am-cf"></div>
    </div>
<?php }?>
<script type="text/javascript">
    function change_status(id,type){
    	if(confirm("确定操作吗？")){
	        $.ajax({
	            url: admin_webroot+"user_project_modifications/change_status",
	            type:"POST",
	            data:{'id':id,'type':type},
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
    }

    function submit_operations(){
        var bratch_operat_check = document.getElementsByName("checkboxes[]");
        var operations_select = document.getElementById("barch_opration_select");
        if(operations_select.value==''){
            alert(j_select_operation_type+" !");
            return;
        }
        var postData = new Array();
        for(var i=0;i<bratch_operat_check.length;i++){
            if(bratch_operat_check[i].checked){
                postData.push(bratch_operat_check[i].value);
            }
        }
        if(operations_select.value=="0" || operations_select.value=="1"){
            if(postData==''){
                alert(j_please_select+orders+" !");
                return false;
            }
            change_status(postData,operations_select.value);
            return false;
        }
        if(operations_select.value=="2"){
            if(postData==''){
                alert(j_please_select+orders+" !");
                return false;
            }
            window.open(admin_webroot+"user_project_modifications/ajax_batch"+"?id="+postData);
            return false;
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