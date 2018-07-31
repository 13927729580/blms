<style>
 .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
 .am-yes{color:#5eb95e;}
 .am-no{color:#dd514c;}
 .am-panel-title div{font-weight:bold;}
 .am-checkbox input[type="checkbox"]{margin-left:0px;}
 .br{word-wrap:break-word;  word-break:break-all;}
</style>
<div>
	<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;">
		<?php if(  isset($profile_id) && !empty($profile_id)   ) {  ?>
					 <a class="am-btn am-btn-xs am-btn-default" href="<?php echo $html->url('/logistics_companies/logistics_company_upload'); ?>"><?php echo $ld['bulk_upload']?></a>
					<?php } ?>
		<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('view/'); ?>">
			<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
		</a>
	</div>	
	<?php echo $form->create('',array('action'=>'/',"name"=>"ProForm","type"=>"POST"));?>
		<div class="am-panel-group am-panel-tree">
			<div class="listtable_div_btm am-panel-header">
				<div class="am-panel-hd">
					<div class="am-panel-title">
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-4">
							<label class="am-checkbox am-success" style="font-weight:bold;">
								<span  class="am-hide-sm-only"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck value="checkbox"></span>
							<?php echo $ld['logistics_code']?>
							</label>
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['logistics_company']?></div>
						<div class="am-u-lg-3 am-u-md-3 am-hide-sm-only"><?php echo $ld['logistics_website']?></div>
						<div class="am-u-lg-2 am-u-md-1 am-u-sm-1"><?php echo $ld['valid']?></div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-4"><?php echo $ld['operate']?></div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php if(isset($LogisticsCompanies) && sizeof($LogisticsCompanies)>0){foreach($LogisticsCompanies as $k=>$LogisticsCompany){?>
			<div>
				<div class="listtable_div_top am-panel-body">
					<div class="am-panel-bd">
						<div class="am-u-lg-2  am-u-md-2 am-u-sm-4" >
							<label class="am-checkbox am-success">
								<span class="am-hide-sm-only"><input type="checkbox" name="checkboxes[]" value="<?php echo $LogisticsCompany['LogisticsCompany']['id']?>" data-am-ucheck /></span>
							<?php echo $LogisticsCompany['LogisticsCompany']['code']?>&nbsp;
							</label>
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-3" ><?php echo $LogisticsCompany['LogisticsCompany']['name']?>&nbsp;</div>
						<div class="am-u-lg-3 am-u-md-3  br am-hide-sm-only" ><?php echo $LogisticsCompany['LogisticsCompany']['website']?>&nbsp;</div>
						<div class="am-u-lg-2 am-u-md-1 am-u-sm-1" >
							<?php if ($LogisticsCompany['LogisticsCompany']['fettle'] == 1){?>
				
								<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'logistics_companies/toggle_on_status',<?php echo $LogisticsCompany['LogisticsCompany']['id'];?>)"></span>									
							<?php }elseif($LogisticsCompany['LogisticsCompany']['fettle'] == 0){?>
								<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'logistics_companies/toggle_on_status',<?php echo $LogisticsCompany['LogisticsCompany']['id'];?>)"></span>								
							<?php }?>&nbsp;
						</div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-action">
							<?php
								if($svshow->operator_privilege("logistics_companies_edit")){?>
								 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/logistics_companies/view/'.$LogisticsCompany['LogisticsCompany']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
							<?php 	}?>
							<?php 	if($svshow->operator_privilege("logistics_companies_remove"))?><?php {?> 	<a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'/logistics_companies/remove/<?php echo $LogisticsCompany['LogisticsCompany']['id'] ?>');">
                        			    <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      			</a>
							<?php 	}?>
						&nbsp;
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php }}  else{?> 
				<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
                     <?php  }?>
		</div>
	<?php echo $form->end();?> 
			<?php if(isset($LogisticsCompanies) && sizeof($LogisticsCompanies)){?>	
	<div id="btnouterlist" class="btnouterlist">
		<?php if($svshow->operator_privilege("logistics_companies_remove")){?>
			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-hide-sm-down" style="left:6px;">
						<div class="am-fl">
					          <label class="am-checkbox am-success" style="display: inline;">
					            <input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox"
								value="checkbox" data-am-ucheck><span><?php echo $ld['select_all']?></span>
					          </label>
			            		</div>
						<div class="am-fl" style="margin-left:3px;">
					            <select name="barch_opration_select" id="barch_opration_select" data-am-selected  onchange="barch_company_select_onchange(this)">
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
		<?php }?>
	     <?php }?>	
		 <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $this->element('pagers')?></div>
         <div class="am-cf"></div>
	</div>
</div>


<script type="text/javascript">
function select_batch_operations(){
	var barch_opration_select = document.getElementById("barch_opration_select");
      var export_csv = document.getElementById("export_csv");
      if(barch_opration_select.value==0){
      	  	alert(j_select_operation_type);
			return;
      }
      if(barch_opration_select.value=='delete'){
		company_select();
	}
	if(barch_opration_select.value=='export_csv'){
		if(export_csv.value=='all_export_csv'){
			window.location.href=admin_webroot+"/logistics_companies/all_export_csv";
		
		}
		if(export_csv.value=='choice_export'){
			choice_upload();
		}
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
	window.location.href=admin_webroot+"logistics_companies/choice_export/"+postData;
	
	}
}	

//触发子下拉
function barch_company_select_onchange(obj){
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
	
	
	
function company_select(){
	if(confirm("<?php echo $ld['confirm_delete_the_selected_logistics_company']?>")){
		company_action();
	}
}
function company_action(){
	document.ProForm.action=admin_webroot+"LogisticsCompanies/delall";
	document.ProForm.onsubmit= "";
	document.ProForm.submit();
}

function change_state(obj,func,id){
	var ClassName=$(obj).attr('class');
	var val = (ClassName.match(/yes/i)) ? 0 : 1;
	var postData = "val="+val+"&id="+id;
	$.ajax({
		url:admin_webroot+func,
		Type:"POST",
		data: postData,
		dataType:"json",
		success:function(data){
			if(data.flag == 1){
				if(val==0){
					$(obj).removeClass("am-icon-check am-yes");
					$(obj).addClass("am-icon-close am-no");
				}
				if(val==1){
					$(obj).removeClass("am-icon-close am-no");
					$(obj).addClass("am-icon-check am-yes");
				}
			}
		
		}	
	});
}

</script>
