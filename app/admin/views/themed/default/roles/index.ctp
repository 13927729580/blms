<style type="text/css">
.am-checkbox {margin-top:0px; margin-bottom:0px;}
.am-panel-title div{font-weight:bold;} 
.am-form-label{font-weight:bold;top:-5px; left:10px;} 
</style>
<div class="listsearch" style="margin-top:10px;">
	<?php echo $form->create('',array('action'=>'/','name'=>'SearchForm','type'=>'get','class'=>'am-form am-form-horizontal'));?>
		<div class="am-form-group">
			<label class="am-u-lg-1 am-u-md-1 am-u-sm-3 am-form-label  "><?php echo $ld['keyword'];?></label>
			<div class="am-u-lg-3 am-u-md-4 am-u-sm-4">
				<input type="text" id="role_name" placeholder="<?php echo $ld['role_role_name']?>"  name="role_name" <?php if(isset($role_name)){?>value="<?php echo $role_name;?>"<?php }?> />
			</div>
			<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
				<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>"><?php echo $ld['search'];?></button>
			</div>
		</div>
	<?php echo $form->end();?>
</div>
				
<div class="am-g am-other_action">
	<div class="am-fr am-u-lg-12 am-btn-group-xs" style="text-align:right;margin-bottom:10px;margin-right:15px;">
	<?php if(  isset($profile_id) && !empty($profile_id)   ) {  ?>
		<a  class="am-btn am-radius  am-btn-default am-btn-sm"   href="<?php echo $html->url("/roles/role_upload/") ?>"  >
			<?php  echo $ld['bulk_upload'] ?>
		</a>
	<?php } ?>
		<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/roles/add/'); ?>">
			<span class="am-icon-plus"></span><?php echo $ld['add'] ?>
		</a>
	</div>
</div>
	
<?php echo $form->create('',array('action'=>''));?>
<div class="am-panel-group am-panel-tree">
	<div class="listtable_div_btm  am-panel-header">
		<div class="am-panel-hd">
			<div class="am-panel-title am-g">
				<div class="am-u-lg-2 am-u-md-5 am-u-sm-5">
					<label class="am-checkbox am-success" style="font-weight:bold;">
							<span class="am-hide-sm-only"><input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck type="checkbox" /></span>
						<span><?php echo $ld['role_role_name']?></span>
					</label>
				</div>
				<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['role_role_num']?></div>
				<div class="am-u-lg-4 am-show-lg-only"><?php echo $ld['role_rights_summary']?></div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['operate']?></div>
			</div>
		</div>
	</div>
	
	<?php if(isset($role_list) && sizeof($role_list)>0){foreach($role_list as $k=>$v){?>
		<div>	
		<div class="listtable_div_top am-panel-body">
			<div class="am-panel-bd am-g">
				<div class="am-u-lg-2 am-u-md-5 am-u-sm-5">
					<label class="am-checkbox am-success">
						<span class="am-hide-sm-only"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['Role']['id']?>" /></span>
						<?php echo $v['RoleI18n']['name']?>
					</label>
				</div>
				<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 "><?php echo $v['Role']['number']?>&nbsp;</div>
				<div class="am-u-lg-4  am-show-lg-only" style="text-overflow:ellipsis;overflow:hidden;">
					<?php echo $v['Role']['actions']?>
				</div>			
				<div class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-action">
					<?php if($svshow->operator_privilege("operator_roles_edit")){?>
					 
						 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/roles/edit/'.$v['Role']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
						
				<?php 	}
					if($svshow->operator_privilege("operator_roles_remove")){?>
					 
						
							<a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:void(0);" onclick="if(confirm(j_confirm_delete)){window.location.href=admin_webroot+'roles/remove/<?php echo $v['Role']['id'] ?>';}">
						<span class="am-icon-trash-o"></span> <?php echo $ld['remove']; ?>
						</a>
				<?php 	}
					?>
						
				</div>
			</div>
		</div>
		</div>
		<?php }	}else{?>
			<div style="text-align:center;margin-top:30px;"><?php echo $ld['no_records']?></div>
		<?php }?>
</div>
				
<?php if($svshow->operator_privilege("operator_roles_remove")){?>
	<?php if(isset($role_list) && sizeof($role_list)){?>
		<div id="btnouterlist" class="btnouterlist am-form-group ">
				<div class="am-u-lg-5 am-u-md-5 am-u-sm-5 am-hide-sm-down" style="left:6px;">
							<div class="am-fl">
								<label class="am-checkbox am-success" style="font-size:14px; line-height:14px;">
									<input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck type="checkbox" />
									<span><?php echo $ld['select_all']?></span>
								</label>
				            	</div>
							<div class="am-fl" style="margin-left:3px;">
						            <select name="barch_opration_select" id="barch_opration_select" data-am-selected  onchange="batch_opration_select_onchange(this)">
						              <option value="0"><?php echo $ld['batch_operate']?></option>
						              <option value="delete"><?php echo $ld['batch_delete']?></option>
								<?php if( isset($profile_id) && !empty($profile_id) ){ ?>
						    		  <option value="export_csv"><?php echo $ld['batch_export']?></option>
						    		 <?php } ?>
						            </select>
		            			</div> 
							<div class="am-fl" style="display:none;margin-left:3px;">
					                    <select id="export_csv" data-am-selected name="barch_opration_select_onchange" >
					                        <option value=""><?php echo $ld['click_select']?></option>
					                        <option value="all_export_csv"><?php echo  $ld['all_export']?></option>
					                        <option value="choice_export"><?php echo $ld['choice_export']?></option>
					                    </select>&nbsp;
				              	</div>
							<div class="am-fl" style="margin-left:3px;">
				               	   <button type="button" class="am-btn am-radius am-btn-danger am-btn-sm" onclick="select_batch_operations()"><?php echo $ld['submit']?></button>
				              	</div>
				</div>
		
				
            		<div class="am-cf">
            		</div>
		</div>
		<div class="btnouterlist am-form-group " >
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
						&nbsp;
				</div>		
				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" >
					<?php echo $this->element('pagers')?>
				</div>
				<div class="am-cf">
            		</div>
		</div>				
	<?php }?>
<?php }?>
<?php echo $form->end();?>
<script type="text/javascript">
function select_batch_operations(){
	var barch_opration_select = document.getElementById("barch_opration_select");
      var export_csv = document.getElementById("export_csv");
      if(barch_opration_select.value==0){
      	  	alert(j_select_operation_type);
			return;
      }
      if(barch_opration_select.value=='delete'){
		batch_operations();
	}
	if(barch_opration_select.value=='export_csv'){
		if(export_csv.value=='all_export_csv'){
			window.location.href=admin_webroot+"/roles/all_export_csv";
		
		}
		if(export_csv.value=='choice_export'){
			choice_upload();
		}
	}
}
//批量删除
function batch_operations(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
		}
	}
	if( postData=="" ){
		alert("<?php echo $ld['please_select'] ?>");
		return;
	}
	if(confirm("<?php echo $ld['confirm_delete']?>")){
		$.ajax({ 
			url:admin_webroot+"roles/batch_operations/",
			type:"GET",
			dataType:"json",
			data: postData,
			success:function(data){
				window.location.href = window.location.href;
			}
		});
	}
}	
//选择导出
function choice_upload(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
		}
	}
	if( postData=="" ){
		alert("<?php echo $ld['please_select'] ?>");
		return;
	}else{
	window.location.href=admin_webroot+"roles/choice_export/"+postData;
	
	}
}	
//触发子下拉
function batch_opration_select_onchange(obj){
	if(obj.value!="export_csv"){
		$("#export_csv").parent().hide();		
	}
	$("select[name='barch_opration_select_onchange[]']").parent().hide();
	
	var export_csv=document.getElementById("export_csv").value;
	
	if(obj.value=="export_csv"){
		if(export_csv=="all_export_csv"){
			$("#export_csv").parent().show();
		}else{
			$("#export_csv").parent().show();
		}
	}

}
</script>