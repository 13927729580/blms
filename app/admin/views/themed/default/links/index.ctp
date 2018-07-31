<style>
 .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
 .am-checkbox input[type="checkbox"]{margin-left:0;}
 .am-panel-title{font-weight:bold;}
.am-checkbox .am-icon-checked, .am-checkbox .am-icon-unchecked, .am-checkbox-inline .am-icon-checked, .am-checkbox-inline .am-icon-unchecked, .am-radio .am-icon-checked, .am-radio .am-icon-unchecked, .am-radio-inline .am-icon-checked, .am-radio-inline .am-icon-unchecked {
    background-color: transparent;
    display: inline-table;
    left: 0;
    margin: 0;
    position: absolute;
    top: 2px;
    transition: color 0.25s linear 0s;
}
</style>
<div>
	<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;">
	<?php if(  isset($profile_id) && !empty($profile_id)   ) {  ?>
		<a  class="am-btn am-btn-default am-btn-sm"   href="<?php echo $html->url("/links/link_upload/") ?>"  >
			<?php  echo $ld['bulk_upload'] ?>
		</a>
	<?php } ?>
		<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/links/view/'); ?>">
			<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
		</a>
	</div>
	<div class="am-panel-group am-panel-tree" id="accordion">
	<!--标题栏-->
		<div class=" listtable_div_btm am-panel-header">
		    <div class="am-panel-hd">
		      	<div class="am-panel-title">
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-5">
						<label class="am-checkbox am-success" style="font-weight:bold;" >
						<span class="am-hide-sm-only"><input type="checkbox" data-am-ucheck value="checkbox" onclick='listTable.selectAll(this,"checkboxes[]")' /></span>
							<?php echo $ld['link_name']?>
						</label>
					</div>
					<div class="am-u-lg-2   am-u-md-3   am-hide-sm-down" ><?php echo $ld['link_address']?></div>
					<div class="am-u-lg-1   am-u-md-2 am-hide-sm-down"><?php echo $ld['link_logo']?></div>
					<div class="am-u-lg-1   am-hide-md-down"><?php echo $ld['hits']?></div>
	                            <div class="am-u-lg-2   am-hide-md-down"><?php echo $ld['new_window']?></div>
					<div class="am-u-lg-1  am-u-md-1  am-u-sm-3  "><?php echo $ld['valid']?></div>
					<div class="am-u-lg-1  am-hide-md-down"><?php echo $ld['sort']?></div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-4"  ><?php echo $ld['operate']?></div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>  
		<?php if(isset($links) && sizeof($links)>0){foreach($links as $k=>$link){?>
		<div>			
			<div class="listtable_div_top  am-panel-body">
				<div class="am-panel-bd am-g">
					<div class="am-u-lg-2  am-u-md-3 am-u-sm-5">
						<label class="am-checkbox am-success">
							<span class="am-hide-sm-only"><input type="checkbox" name="checkboxes[]" value="<?php echo $link['Link']['id']?>"  data-am-ucheck/></span>
							<?php echo $link['LinkI18n']['name']?> 
						</label>
					</div>
					<div class="am-u-lg-2 am-u-md-3 am-hide-sm-down" style="max-width:100%;word-wrap:break-word;  word-break:break-all;"><?php echo isset($link['LinkI18n']['url'])?$link['LinkI18n']['url']:"";?>&nbsp;</div>
					<div class="am-u-lg-1 am-u-md-2 am-hide-sm-down">
						<?php echo $html->image( $link['LinkI18n']['img01'].'?date='.time(),array('style'=>'max-width:100px;max-height:50px;','class'=>'linkImg','onload'=>'set_img(this,40,40)')); ?>&nbsp;
					</div>
					<div class="am-u-lg-1   am-hide-md-down"><?php echo $link['LinkI18n']['click_stat'];?></div>
					<div class="am-u-lg-2  am-hide-md-down">
						<?php  if ($link['Link']['target']=="_self"){echo $ld['no'];}?>
						<?php  if ($link['Link']['target']=="_blank"){echo $ld['yes'];}?>
						</div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-3 ">
						<?php if($svshow->operator_privilege("links_remove")){
									if($link['Link']['status']==1){
						?>
							<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'links/toggle_on_status',<?php echo $link['Link']['id'];?>)">&nbsp;</span>
						<?php 	}else{ ?>
							<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'links/toggle_on_status',<?php echo $link['Link']['id'];?>)">&nbsp;</span>
						<?php 	}
							      }else{
									if($link['Link']['status']==1){
						?><span  class="am-icon-check am-yes" style="cursor:pointer;"></span><?php }else if($link['Link']['status']==0){?><span  class="am-icon-close  am-no"style="cursor:pointer;" ></span><?php } }?> &nbsp;</div>
					<div class="am-u-lg-1 am-hide-md-down"><?php echo $link['Link']['orderby'];?>&nbsp;</div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-action" >
						<?php
							if($svshow->operator_privilege("links_edit")){ ?>
							  <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/links/view/'.$link['Link']['id']); ?>"><span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?></a> <?php } ?>
							<?php	if($svshow->operator_privilege("links_remove")){?>
						 	<a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'links/remove/<?php echo $link['Link']['id'] ?>')"><span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      				</a>
					<?php }?>
					</div>
					<div style="clear:both"></div>
				</div>
			</div>
		</div>			
		<?php }}else{?>
			<div  class="no_data_found"><?php echo $ld['no_data_found']?></div>
		<?php }?>			
	</div>
	<?php if(isset($links) && sizeof($links)){?>
		<div id="btnouterlist" class="btnouterlist">
			<div class="am-u-lg-6 am-u-md-6 am-u-sm-12  am-hide-sm-only" style="margin-left:6px;">
							<div class="am-fl">
								<label class="am-checkbox am-success" style="font-size:14px; line-height:14px;">
									<input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck />
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
		
            <div class="am-cf"></div>
		</div>
		<div  class="btnouterlist">
				<div class="am-u-lg-4 am-u-md-6 am-hide-sm-only">
					&nbsp;
				</div>
				<div class="am-u-lg-8 am-u-md-6 am-u-sm-12">		
				<?php echo $this->element('pagers')?>
				</div>
		</div>
	<?php }?>
</div>
<script>
function select_batch_operations(){
	var barch_opration_select = document.getElementById("barch_opration_select");
      var export_csv = document.getElementById("export_csv");
      if(barch_opration_select.value==0){
      	  	alert(j_select_operation_type);
			return;
      }
      if(barch_opration_select.value=='delete'){
		batch_delete();
	}
	if(barch_opration_select.value=='export_csv'){
		if(export_csv.value=='all_export_csv'){
			window.location.href=admin_webroot+"/links/all_export_csv";
		
		}
		if(export_csv.value=='choice_export'){
			choice_upload();
		}
	}
}
//批量删除
function batch_delete(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var postdata = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postdata+="&checkboxes[]="+bratch_operat_check[i].value;
		}
	}
	if( postdata=="" ){
		alert("<?php echo $ld['please_select'] ?>");
		return;
	}
	if(confirm("<?php echo $ld['confirm_delete'] ?>")){
		$.ajax({
			url:admin_webroot+"links/removeall/",
			type:"POST",
			data:postdata,
			datatype:"json",
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
	window.location.href=admin_webroot+"links/choice_export/"+postData;
	
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


function set_img(obj,wid,hei){
	var img_src=obj.src;
	var img=new Image();
	img.src=img_src;
	if(img.width>0){
		var img_w,img_h;
		if(img.width>wid){
			img_w=wid;
		}else{
			img_w=img.width;
		}
		if(img.height>hei){
			img_h=(img.height*img_w) / img.width;
		}else{
			img_h=img.height;
		}
		if(img_h>hei){
			img_h=hei;
			img_w=(img.width*img_h) / img.height;
		}
		obj.style.width=img_w+"px";
		obj.style.height=img_h+"px";
	}
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