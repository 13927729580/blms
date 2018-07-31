<style type="text/css">
label{font-weight:normal;}
@media only screen and (max-width: 640px){body {word-wrap: normal;}}
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
.img_select{max-width:150px;max-height:120px;}

.am-list>li{margin-bottom:0;border-style: none;}
.admin-sidebar-list li a{color:#fff;background-color: #5eb95e;}
.am-list > li > a.am-active, .am-list > li > a.am-active:hover, .am-list > li > a.am-active:focus{font-weight: bold;}
.scrollspy-nav.am-sticky.am-animation-slide-top{width: 100%;}
.am-sticky-placeholder{margin-top: 10px;}
.scrollspy-nav {top: 0;z-index: 100;background: #5eb95e;width: 100%;padding: 0 10px}
.scrollspy-nav ul {margin: 0;padding: 0;}
.scrollspy-nav li {display: inline-block;list-style: none;}
.scrollspy-nav a {color: #eee;padding: 10px 20px;display: inline-block;}
.scrollspy-nav a.am-active {color: #fff;font-weight: bold;}
.crumbs{padding-left:0;margin-bottom:22px;}
.related_dt dl {
    float: left;
    text-align: left;
    padding: 3px 5px;
    border: 1px solid #ccc;
    margin: 2px 5px;
    width: 45%;
    display: block;
    white-space: nowrap;
    text-overflow: ellipsis;
    text-transform: capitalize;
    overflow: hidden;
    cursor: pointer;
}
.related_dt dl:hover{
    color:green;
    border-color: green;
}
.am-form-horizontal label.am-checkbox{display: inline;margin-right:5px;padding-top:0px;}
</style>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-g">
	<div class="am-panel-group admin-content am-detail-view" id="accordion"  style="width: 95%;margin-right: 2.5%;">
		<?php echo $form->create('Department',array('action'=>'/view/'.(isset($departments_id)?$departments_id:''),'id'=>'DepartmentForm','onsubmit'=>'return pages_check();'));?>
	    <!-- 导航 -->
		<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
		    <ul>
			   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
			   	<?php if(isset($depart_id)&&$depart_id!=0){ ?><li><a href="#operator_information"><?php echo '操作员关联'?></a></li>
			   	<li><a href="#operator_source"><?php echo '渠道管理'?></a></li><?php } ?>
			</ul>
		</div>

		<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
		    <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" onclick="" value="<?php echo $ld['d_submit'];?>" />  
	        <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
		</div>
		<!-- 导航结束 -->
		<div id="basic_information"  class="am-panel am-panel-default">
	  		<div class="am-panel-hd">
				<h4 class="am-panel-title">
					<?php echo $ld['basic_information']?>
				</h4>
		    </div>
		    <div class="am-panel-collapse am-collapse am-in">
	      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
	      		<input type="hidden" name="data[Department][id]" value="<?php echo isset($departments['Department'])?$departments['Department']['id']:0; ?>">
		    		<!-- 部门名称 -->
	      			<div class="am-form-group">
		    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">部门名称</label>
		    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
		    				<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
			    					<input type="hidden" name="data[DepartmentI18n][<?php echo $k;?>][id]" value="<?php echo isset($departments['DepartmentI18n'][$v['Language']['locale']])?$departments['DepartmentI18n'][$v['Language']['locale']]['id']:'0'; ?>" />
			    					<input type="hidden" name="data[DepartmentI18n][<?php echo $k;?>][locale]" value="<?php echo $v['Language']['locale']; ?>" />
			    					<input type="text" name="data[DepartmentI18n][<?php echo $k;?>][name]" id="department_name" value="<?php echo isset($departments['DepartmentI18n'][$v['Language']['locale']])?$departments['DepartmentI18n'][$v['Language']['locale']]['name']:''; ?>" />
			    				</div>
				    			<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:20px;">
			    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;<em style="color:red;">*</em>
			    					</label>
				    			<?php }?>
			    			<?php }} ?>
		    			</div>
		    		</div>
		    		<!-- 部门描述 -->
	      			<div class="am-form-group">
		    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">部门描述</label>
		    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
		    				<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
			    					<input type="text" name="data[DepartmentI18n][<?php echo $k;?>][description]" value="<?php echo isset($departments['DepartmentI18n'][$v['Language']['locale']])?$departments['DepartmentI18n'][$v['Language']['locale']]['description']:''; ?>" />
			    				</div>
				    			<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:20px;">
			    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;<em style="color:red;">*</em>
			    					</label>
				    			<?php }?>
			    			<?php }} ?>
		    			</div>
		    		</div>
		    		<!-- 部门管理员 -->
	      			<div class="am-form-group">
		    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">部门管理员</label>
		    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
		    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
	    						<?php 
	    								$operator_manager=explode(',',isset($departments['Department']['manager'])?$departments['Department']['manager']:'');
	    								if(isset($operator_data)&&sizeof($operator_data)>0){foreach($operator_data as $k=>$v){ ?>
								<label class="am-checkbox am-success">
									<input type="checkbox" name="data[Department][manager][]" value="<?php echo $k; ?>" <?php echo in_array($k,$operator_manager)?'checked':''; ?> data-am-ucheck> <?php echo $v; ?>
								</label>
	    						<?php 	}} ?>
		    				</div>
		    			</div>
		    		</div>
		    		<!-- 状态 -->
	      			<div class="am-form-group">
		    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">状态</label>
		    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
		    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
		    					<label class="am-radio am-success">
								<input type="radio" name="data[Department][status]" value="1" data-am-ucheck <?php echo (isset($departments['Department']['status'])&&$departments['Department']['status']==1)||!isset($departments['Department']['status'])?'checked':''; ?>>
								有效
							</label>
							<label class="am-radio am-success">
								<input type="radio" name="data[Department][status]" value="0" data-am-ucheck <?php echo isset($departments['Department']['status'])&&$departments['Department']['status']==0?'checked':''; ?>>
								无效
							</label>
		    				</div>
		    			</div>
		    		</div>
		    						
				</div>
			</div>
		</div>
		<?php if($departments_id!=0){ ?>
		<div id="operator_information"  class="am-panel am-panel-default">
	  		<div class="am-panel-hd">
				<h4 class="am-panel-title">
					操作员关联
				</h4>
		    </div>
		    <div class="am-panel-collapse am-collapse am-in">
	      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
	      			<table class="am-table">
	                    <?php echo $form->create('Evaluation',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();','class'=>'am-form am-form-horizontal'));?>
					    <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
					        <li>
					            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">操作员</label>
					            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
					                <input type="text" name="keyword" id="keyword" value="<?php echo isset($keyword)?$keyword:'';?>"/>
					            </div>
					        </li>
					        <li >
					            <label class="am-u-sm-3 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
					            <div class="am-u-sm-8 am-u-md-8 am-u-sm-7 " style="float:left;">
					                <input type="button"  class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>" onclick="formsubmit()" />
					            </div>
					        </li>
					    </ul>
					    <?php echo $form->end()?>
	                </table>
	                <div class="am-form-group">
	                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-text-center"><label>可选操作员</label></div>
	                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-text-center"><label>与该部门关联的操作员</label></div>
	                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-12 am-text-center">
	                        <label class='am-show-sm-only'><?php echo $ld['option_products']?></label>
	                        <div class="related_dt" style="overflow-y:scroll;height:300px;">
	                        	<?php if(isset($operator_list) && sizeof($operator_list)>0)foreach($operator_list as $k=>$v){?>
					<dl onclick="add_relation_operator(this,<?php echo $departments_id; ?>,<?php echo $k; ?>)"><span  class="am-icon-plus" style="padding-right: 10px;"></span><?php echo $v; ?></dl>
					<?php }?>
	                        </div>
	                    </div>
	                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-12 am-text-center">
	                        <label class='am-show-sm-only'><?php echo $ld['product_products']?></label>
	                        <div id="relation_operator" style="overflow-y:scroll;height:300px;">
	                            <?php if(isset($operator_departments) && sizeof($operator_departments)>=1){foreach($operator_departments as $k=>$v){?>
                                    <div class="am-u-lg-12 am-u-md-12 am-u-sm-12 relation_operator">
						<input type="hidden" name="data[OperatorDepartment][id][]" value="<?php echo $v['OperatorDepartment']['id']; ?>">
						<input type="hidden" name="data[OperatorDepartment][operator_id][]" value="<?php echo $v['OperatorDepartment']['operator_id']; ?>">
						<div class="am-u-lg-10 am-u-md-10 am-u-sm-10 am-text-left"><?php echo isset($operator_data[$v['OperatorDepartment']['operator_id']])?$operator_data[$v['OperatorDepartment']['operator_id']]:'-'; ?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
							<span class="am-icon-close am-no" style="cursor:pointer;" onclick="relation_operator_remove(this)"></span>
						</div>
                                    </div>
                                <?php }}?>
	                        </div>
	                    </div>
	                </div>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php if(isset($depart_id)&&$depart_id!=0){ ?>
		<div id="operator_source"  class="am-panel am-panel-default">
	  		<div class="am-panel-hd">
				<h4 class="am-panel-title">渠道管理</h4>
		    </div>
		    <div class="am-panel-collapse am-collapse am-in">
	      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
	      		<?php if(isset($op_check)&&sizeof($op_check)>0){ ?>
						<?php foreach ($op_check as $k => $v) { ?>
							<div style="width:50%;margin-bottom:1rem;margin-left:10px;">
								<div class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-text-right" style="line-height:35px;font-weight:600;"><?php echo $v; ?></div>
								<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input type="text" value="<?php echo isset($relation_info_check[$k])?$relation_info_check[$k]['OperatorChannelRelation']['value']:''; ?>" name="channel_relation[<?php echo isset($k)?$k:0; ?>]"></div>
								<div class="am-cf"></div>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php echo $form->end();?>
	</div>	  
</div>
<script>
function pages_check(){
	if($('#department_name').val() == ''){
		alert('部门名称不能为空！');
		return false;
	}
}
function formsubmit(){
    var keyword=document.getElementById('keyword').value;
    var url = "keyword="+keyword;
    window.location.href = encodeURI(admin_webroot+"departments/view/<?php echo $departments_id ?>?"+url);
}
var newhtml = "";
function add_relation_operator(obj,departments_id,operator_id){
	var sUrl = admin_webroot+"departments/add_relation_operator/";//访问的URL地址
	$.ajax({
		type: "POST",
		url:sUrl,
		dataType: 'json',
		data: {departments_id:departments_id,operator_id:operator_id},
		success: function (result) {
	    		if(result.flag=="1"){
				newhtml ='<div class="am-u-lg-12 am-u-md-12 am-u-sm-12 relation_operator"><input type="hidden" name="data[OperatorDepartment][id][]" value="0"><input type="hidden" name="data[OperatorDepartment][operator_id][]" value="'+result.content.Operator['id']+'"><div class="am-u-lg-10 am-u-md-10 am-u-sm-10 am-text-left">'+result.content.Operator['name']+'</div><div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><span style="cursor:pointer;" class="am-icon-close am-no" onClick="relation_operator_remove(this)"></span></div></div>';
				$("#relation_operator").append(newhtml);
				$(obj).remove();
			}
			if(result.flag=="2"){
				alert(result.content);
			}
	    }
	});
}
function relation_operator_remove(btn){
	$(btn).parent().parent().remove();
}
</script>