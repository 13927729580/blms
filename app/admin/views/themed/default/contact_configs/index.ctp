 <style type="text/css">
 .am-form-label{font-weight:bold; text-align:center; margin-top:-5px;margin-left:20px;}
</style>
<div class="listsearch">
    <?php echo $form->create('',array('action'=>'/',"type"=>"get",'name'=>"SearchForm",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0;">
        <?php if(!empty($Resource_info['contact_us_type'])){ ?>
    
        <li style="margin:0 0 10px 0"> 
                <label class="am-u-sm-3 am-u-md-3 am-u-lg-3 am-form-label"><?php echo $ld['type']?></label>
                <div class="am-u-sm-7  am-u-md-7 am-u-lg-7">
                    <select name="contact_us_type" data-am-selected>
                        <option value=""><?php echo $ld['all_data']; ?></option>
                        <?php foreach($Resource_info['contact_us_type'] as $k=>$v){ ?><option value="<?php echo $k; ?>" <?php echo isset($contact_us_type)&&$contact_us_type==$k?'selected':''; ?>><?php echo $v; ?></option><?php } ?>
                    </select>
                </div>
         </li>
        <?php } ?>
	   <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3  am-u-md-3 am-u-lg-3  am-form-label"><?php echo $ld['status']?></label>
            <div class="am-u-sm-7 am-u-md-7 am-u-lg-8">
                <select name="status" data-am-selected>
					<option value=""><?php echo $ld['all_data']; ?></option>
					<option value="1" <?php echo isset($contact_status)&&$contact_status=='1'?"selected":''; ?>><?php echo $ld['valid']; ?></option>
					<option value="0" <?php echo isset($contact_status)&&$contact_status=='0'?"selected":''; ?>><?php echo $ld['invalid']; ?></option>
				</select>
            </div>
       </li>
       <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3  am-u-md-3 am-u-lg-3  am-form-label"><?php echo $ld['keyword']?></label>
            <div class="am-u-sm-7 am-u-md-7 am-u-lg-8">
                <input type="text" name="kword_name" placeholder="名称/编码" value="<?php echo @$kword_name;?>"/>
            </div>
       </li>
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3 am-u-md-3  am-u-lg-3  am-form-label"> </label>
            <div class="am-u-sm-7 am-u-md-7  am-u-lg-5 ">
                <input class="am-btn am-btn-success am-radius am-btn-sm search_article" type="submit" value="<?php echo $ld['search']?>" />
            </div>
        </li>
        	
    </ul>
    <?php echo $form->end();?>
</div>
<div class="am-g action-span" style="margin-bottom:10px;">	
	<div class="am-u-lg-12 am-u-md-12 am-u-sm-12" style="text-align:right;">
	<?php if(  isset($profile_id) && !empty($profile_id)   ) {  ?>
		<a class="am-btn am-btn-xs am-btn-default" href="<?php echo $html->url('/contact_configs/contact_config_upload'); ?>"><?php echo $ld['bulk_upload']?></a>
	<?php } ?>			
	<?php //echo $html->link($ld['add'],"/contact_configs/view/0",array("class"=>"addbutton am-btn am-btn-warning am-btn-sm am-radius","style"=>"padding-top:6px;padding-bottom:6px;height:28px;"));?>
	<a href="<?php echo $html->url('/contact_configs/view/0'); ?>" class="addbutton am-btn am-btn-warning am-btn-sm am-radius" style="padding-top:6px;padding-bottom:6px;height:28px;font-size:1.2rem;">
		<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
	</a>
</div>
</div>
<?php echo $form->create('',array("action"=>"/batch",'name'=>"DeleteForm","type"=>"get",'onsubmit'=>"return false"));?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table class="am-table  table-main">
        <thead>
	    <tr>
	        <th style="width:28%;max-width:30%;word-wrap:break-word;word-break:normal;"><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-only"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></span><b><?php echo $ld['name']?></b></label></th>
	         <th class="am-hide-md-down"><?php echo $ld['type'];?></th>
			 <th><?php echo $ld['code'];?></th>
			 <th><?php echo $ld['status']?></th>
			 <th><?php echo $ld['required'];?></th>
			 <th class="am-hide-md-down"><?php echo $ld['create_time'];?></th>
			 <th><?php echo $ld['operate']?></th>
		</tr>
		</thead>
	<tbody>
<?php
if(isset($contact_config_info) && sizeof($contact_config_info)>0){
    foreach($contact_config_info as $k=>$v){
        ?>
        <tr>
            <td style="width:28%;max-width:30%;word-wrap:break-word;word-break:normal; "><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-only"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['ContactConfig']['id']?>" /></span><?php echo $v["ContactConfigI18n"]["name"]."&nbsp;"; ?></label>
            </td>
            <td class="am-hide-md-down"><?php echo isset($Resource_info['contact_us_type'][$v['ContactConfig']['type']])?$Resource_info['contact_us_type'][$v['ContactConfig']['type']]:$v['ContactConfig']['type']; ?>
            </td>
            <td><?php echo $v["ContactConfig"]["code"]; ?></td>
            <td><?php if( $v['ContactConfig']['status'] == 1){?>
				<span class="am-icon-check am-yes" style="cursor:pointer;"></span>
			<?php }else{ ?>
				<span class="am-icon-close am-no" style="cursor:pointer;"></span>	
			<?php }?></td>
            <td><?php if( $v['ContactConfig']['is_required'] == 1){?>
				<span class="am-icon-check am-yes" style="cursor:pointer;"></span>
			<?php }else{ ?>
				<span class="am-icon-close am-no" style="cursor:pointer;"></span>	
			<?php }?></td>
			<td class="am-hide-md-down"><?php echo $v["ContactConfig"]["created"]; ?></td>
            <td><?php if($svshow->operator_privilege("contacts_detail")){ ?>
             <a  style="margin-top:5px;" class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/contact_configs/view/'.$v['ContactConfig']['id']);?>"> <span class="am-icon-eye"></span> <?php echo$ld['edit']; ?>
                    </a>
            	<?php }?>
               <?php  if($svshow->operator_privilege("contacts_detail")){?> <a style="margin-top:5px;" class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'ContactConfigs/remove/<?php echo $v['ContactConfig']['id']?>');">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a>
               <?php  }?></td>
        </tr>
    <?php } }else{ ?>
    <tr>
        <td colspan="7"  class="no_data_found"><?php echo $ld['no_data_found']?></td>
    </tr>
<?php }?>
</tbody>
</table>
<?php if(isset($contact_config_info) && sizeof($contact_config_info)){?>
    <div id="btnouterlist" class="btnouterlist">
        <?php if($svshow->operator_privilege("contacts_remove")){?>
        	<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-hide-sm-down" style="left:6px;">
						<div class="am-fl">
					          <label class="am-checkbox am-success" style="display: inline;">
					            <input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox"
								value="checkbox" data-am-ucheck><span><?php echo $ld['select_all']?></span>
					          </label>
			            	</div>
						<div class="am-fl" style="margin-left:3px;">
					            <select name="barch_opration_select" id="barch_opration_select" data-am-selected  onchange="barch_contact_config_opration_select_onchange(this)">
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
        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-fr"><?php echo $this->element('pagers')?></div>
        <div class="am-cf"></div>
    </div>
<?php }?>
</div>
<?php echo $form->end();?>
<script>
function select_batch_operations(){
	var barch_opration_select = document.getElementById("barch_opration_select");
      var export_csv = document.getElementById("export_csv");
      if(barch_opration_select.value==0){
      	  	alert(j_select_operation_type);
			return;
      }
      if(barch_opration_select.value=='delete'){
		batch_action();
	}
	if(barch_opration_select.value=='export_csv'){
		if(export_csv.value=='all_export_csv'){
			window.location.href=admin_webroot+"/contact_configs/all_export_csv";
		
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
	window.location.href=admin_webroot+"contact_configs/choice_export/"+postData;
	
	}
}	

//触发子下拉
function barch_contact_config_opration_select_onchange(obj){
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

    function batch_action(){
    	if(confirm(j_confirm_delete)){
            document.DeleteForm.submit();
        }
    }
</script>