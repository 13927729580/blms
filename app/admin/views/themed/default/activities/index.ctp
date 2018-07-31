<div class='activity_list'>
	<?php echo $form->create('Activity',array('action'=>'/index','type'=>'get','class'=>'am-form-horizontal'));?>
		<ul class=" am-avg-md-2 am-avg-lg-3 am-avg-sm-1">
			<li style="margin:0 0 10px 0">  
				<label class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['type'];?></label>
				<div class="am-u-lg-7 am-u-md-7 am-u-sm-7 am-padding-left-0 am-padding-right-0">
					<select name="activity_type"  data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?> '}">
						<option value=""><?php echo $ld['all_data']; ?> </option>
	    					<option value="P" <?php echo isset($activity_type)&&$activity_type=='P'?'selected':''; ?>><?php echo $ld['product']; ?></option>
	    					<option value="A" <?php echo isset($activity_type)&&$activity_type=='A'?'selected':''; ?>><?php echo $ld['article']; ?></option>
	    					<option value="T" <?php echo isset($activity_type)&&$activity_type=='T'?'selected':''; ?>><?php echo $ld['topics']; ?></option>
	    					<option value="PC" <?php echo isset($activity_type)&&$activity_type=='PC'?'selected':''; ?>><?php echo $ld['product_categories']; ?></option>
				    		<option value="AC" <?php echo isset($activity_type)&&$activity_type=='AC'?'selected':''; ?>><?php echo $ld['article_categories']; ?></option>
					</select>
				</div>
			</li>
			<li style="margin:0 0 10px 0">  
				<label class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-form-label am-text-left">渠道</label>
				<div class="am-u-lg-7 am-u-md-7 am-u-sm-7 am-padding-left-0 am-padding-right-0">
					<select name="activity_channel" data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}">
						<option value="-1"><?php echo $ld['please_select']; ?></option>
						<option value="0" <?php echo isset($activity_channel)&&$activity_channel=='0'?'selected':''; ?>>线上</option>
						<option value="1" <?php echo isset($activity_channel)&&$activity_channel=='1'?'selected':''; ?>>线下</option>
					</select>
				</div>
			</li>
			<li style="margin:0 0 10px 0">  
				<label class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-form-label am-text-left" ><?php echo $ld['status'];?></label>
				<div class="am-u-lg-7 am-u-md-7 am-u-sm-7 am-padding-left-0 am-padding-right-0">
					<select name="activity_status"  data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?> '}">
						<option value=""><?php echo $ld['all_data']; ?> </option>
	    					<option value="1" <?php echo isset($activity_status)&&$activity_status=='1'?'selected':''; ?>><?php echo $ld['yes']; ?></option>
	    					<option value="0" <?php echo isset($activity_status)&&$activity_status=='0'?'selected':''; ?>><?php echo $ld['no']; ?></option>
					</select>
				</div>
			</li>
			<li style="margin:0 0 10px 0">  
				<label class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['date'];?></label>
				<div class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-padding-left-0 am-padding-right-0">
					<div class="am-input-group">
						<input type='text' class="am-form-field am-radius" value="<?php echo isset($activity_start_date)&&$activity_start_date!=''?date('Y-m-d',strtotime($activity_start_date)):''; ?>" name="activity_start_date" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
						<span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;"><i class="am-icon-remove"></i></span>
					</div>
				</div>
				<div class="am-u-lg-4 am-u-md-3 am-u-sm-4 am-padding-left-0 am-padding-right-0">
					<div class="am-input-group">
						<input type='text' class="am-form-field am-radius" value="<?php echo isset($activity_end_date)&&$activity_end_date!=''?date('Y-m-d',strtotime($activity_end_date)):''; ?>" name="activity_end_date" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
						<span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;"><i class="am-icon-remove"></i></span>
					</div>
				</div>
			</li>
			<li  style="margin:0 0 10px 0">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text "><?php echo $ld['keyword'];?></label> 
				<div class="am-u-lg-7  am-u-md-7 am-u-sm-7 am-padding-left-0 am-padding-right-0"  >
					<input type="text" name="activity_keyword" class="am-form-field am-radius"  value="<?php echo isset($activity_keyword)?$activity_keyword:''; ?>" placeholder="<?php echo $ld['title']?>/<?php echo $ld['description']?>" />
				</div>
			</li>
			<li style="margin:0 0 10px 0">
				<div class="am-u-sm-3 am-hide-lg-only">&nbsp;</div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-6 am-padding-left-0 am-padding-right-0" >
					<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm"><?php echo $ld['search'];?></button>
				</div>
			</li>
		</ul>
	<?php echo $form->end();?>
	<div class="am-other_action  am-text-right am-btn-group-xs" style="margin-bottom:10px;">
		<a class="am-btn am-btn-default am-btn-sm am-radius" href="<?php echo $html->url('/activities/activity_user'); ?>">活动用户</a> 
		<?php if($svshow->operator_privilege('activity_add')&&isset($can_to_add)&&$can_to_add){?>
			<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/activities/view/0'); ?>">
				<span class="am-icon-plus"></span> <?php echo $ld['add']; ?>
			</a> 
		<?php }?>
	</div>
	<?php echo $form->create('Activity',array('action'=>'/batch_operate','name'=>'ActivityForm','type'=>'get',"onsubmit"=>"return batch_operate();"));?>
	<div class="am-panel-group am-panel-tree">
		<div class="  listtable_div_btm am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title am-g">
					<div class="am-u-lg-3 am-u-md-4 am-u-sm-4">
						<label class="am-checkbox am-success  am-hide-sm-only" style="padding-top:0;margin-top:0;">
							<input type="checkbox" data-am-ucheck onclick='listTable.selectAll(this,"checkbox[]")'/>
							<?php echo $ld["title"]?>
						</label>
		                        	<label class="am-checkbox am-success  am-show-sm-only" style="padding-top:0;"><?php echo $ld["title"]?></label>
					</div>
					<div class='am-u-lg-2 am-u-md-2 am-u-sm-2'>渠道</div>
					<div class="am-u-lg-2 am-show-lg-only"><?php echo $ld['type']?></div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['date']?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['valid']?></div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['operate']?></div>
				</div>
			</div>
		</div>
		<?php if(isset($activity_list) && sizeof($activity_list)>0){foreach($activity_list as $k=>$v){?>
		<div>
		<div class="listtable_div_top am-panel-body">
			<div class="am-panel-bd am-g">
				<div class="am-u-lg-3 am-u-md-4 am-u-sm-4">
					<label class="am-checkbox am-success  am-hide-sm-only" style="padding-top:0;margin-top:0;">
						<input type="checkbox" name="checkbox[]" data-am-ucheck value="<?php echo $v['Activity']['id']?>" />
						<?php echo $v['Activity']['name'] ?>
					</label>
			              <label class="am-checkbox am-success  am-show-sm-only" style="padding-top:0"><?php echo $v['Activity']['name'] ?></label>
				</div>
				<div class='am-u-lg-2 am-u-md-2 am-u-sm-2'><?php
					echo $v['Activity']['channel']=='0'?'线上':($v['Activity']['channel']=='1'?'线下':('&nbsp;'));
				?></div>
				<div class="am-u-lg-2 am-show-lg-only"><?php switch($v['Activity']['type']){
					case "P":
						echo $ld['product'];
						break;
					case "A":
						echo $ld['article'];
						break;
					case "T":
						echo $ld['topics'];
						break;
					case "PC":
						echo $ld['product_categories'];
						break;
					case "AC":
						echo $ld['article_categories'];
						break;
				} ?>&nbsp;</div>
				<div class="am-u-lg-2 am-u-md-3 am-u-sm-3">
					<?php echo $v['Activity']['start_date']!='0000-00-00 00:00:00'?date("Y-m-d",strtotime($v['Activity']['start_date'])):''; ?>&nbsp;-&nbsp;<?php echo $v['Activity']['end_date']!='0000-00-00 00:00:00'?date("Y-m-d",strtotime($v['Activity']['end_date'])):''; ?>
				</div>
				<div class="am-u-lg-1 am-show-lg-only">
					<?php if( $v['Activity']['status'] == 1){?>
						<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'activities/toggle_on_status',<?php echo $v['Activity']['id'];?>)"></span>
					<?php }else{ ?>&nbsp;
						<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'activities/toggle_on_status',<?php echo $v['Activity']['id'];?>)"></span>	
					<?php }?>
				</div>
				<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-btn-group-xs am-action">
					<a class="am-btn am-btn-success am-btn-xs  am-seevia-btn-view" target='_blank' href="<?php echo str_replace('//','/',$webroot.'/activities/view/'.$v['Activity']['id']); ?>">
						<span class="am-icon-eye"></span> <?php echo $ld['preview']; ?>
					</a> 
					<?php if($svshow->operator_privilege("activity_edit")){?>
					<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/activities/view/'.$v['Activity']['id']); ?>">
					<span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
					</a>
					<?php } ?>
					<?php if($svshow->operator_privilege("activity_remove")){?>
						<a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:void(0);" 
						onclick="list_delete_submit(admin_webroot+'activities/remove/<?php echo $v['Activity']['id']; ?>')">
                        			<span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      			</a>
					<?php }?>
				</div>
			</div>
		</div>
		</div>
		<?php }}else{?>
			<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
		<?php }?>
	</div>
	<?php if(isset($activity_list) && sizeof($activity_list)){?>
	<div id="btnouterlist" class="btnouterlist" > 
		<div class="am-u-lg-6 am-u-md-6 am-u-sm-12 am-hide-sm-only">
	            <div class="am-fl">
	    			<label class="am-checkbox am-success" style="margin:5px 5px 5px 0px;">
	    				<input onclick='listTable.selectAll(this,"checkbox[]")' data-am-ucheck type="checkbox">
	    				<?php echo $ld['select_all']?>
	    			</label>
	            </div>
	            <div class="am-fl">
				<div class='am-fl'>
					<select name="act_type" id="act_type" onchange="operate_change(this)" data-am-selected>
						<option value="0"><?php echo $ld['all_data']?></option>
						<?php if($svshow->operator_privilege("activity_remove")){?>
						<option value="delete"><?php echo $ld['batch_delete']?></option>
						<?php } ?>
						<?php if($svshow->operator_privilege("activity_edit")){?>
						<option value="modified_status"><?php echo $ld['log_batch_change_status']?></option>
						<?php } ?>
					</select>
				</div>
				<div class='am-fl' style="display:none;margin-left:5px;">
					<select name="modified_status" id="modified_status" data-am-selected>
						<option value="1"><?php echo $ld['yes']?></option>
						<option value="0"><?php echo $ld['no']?></option>
					</select>
				</div>
				<div class="am-fr" style="margin-left:3px;">
					<button type="submit" class="am-btn am-radius am-btn-danger am-btn-sm"><?php echo $ld['submit']?></button>
				</div>
				<div class='am-cf'></div>
	            </div> 
        	</div>
		<div class="am-u-lg-6 am-u-md-12 am-u-sm-12">
			<?php echo $this->element('pagers');?>
		</div>
		<div class="am-cf"></div>
	</div>
	<?php }?>
	<?php echo $form->end();?>
</div>
<script type='text/javascript'>
function change_state(obj,func,id){
	var ClassName=$(obj).attr('class');
	var val = (ClassName.match(/yes/i)) ? 0 : 1;
	$.ajax({
		url:admin_webroot+func,
		Type:"POST",
		data: {'id':id,'val':val},
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

function operate_change(sel_obj){
	var operate_type=$(sel_obj).val();
	if(operate_type=='modified_status'){
		$('#modified_status').parent().show();
	}else{
		$('#modified_status').parent().hide();
	}
}

function batch_operate(){
	var act_type=$('#act_type').val();
	if(act_type=='0'||act_type==''){
		return false;
	}
	var activity_checkboxs=document.getElementsByName('checkbox[]');
	var activity_ids=[];
	for(var i=0;i<=parseInt(activity_checkboxs.length)-1;i++){
		if(activity_checkboxs[i].checked){
			activity_ids.push(activity_checkboxs[i].value);
		}
	}
	console.log(activity_ids);
	if(activity_ids.length==0){
		alert(j_please_select);
		return false;
	}
	if(act_type=='delete'){
		
	}else if(act_type=='modified_status'){
		
	}
}
</script>
