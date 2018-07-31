<style type="text/css">
label.am-checkbox{margin-top:0px;}
</style>
<?php echo $form->create('user_categories',array('action'=>'/','type'=>'get','class'=>'am-form-horizontal'));?>
	<ul class=" am-avg-md-2 am-avg-lg-3 am-avg-sm-1">
		<li  style="margin:0 0 10px 0">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text "><?php echo $ld['keyword'];?></label> 
			<div class="am-u-lg-7  am-u-md-7 am-u-sm-7">
				<input type="text" name="category_keyword" class="am-form-field am-radius"  value="<?php echo @$category_keyword; ?>" />
			</div>
		</li>
		<li  style="margin:0 0 10px 0">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text "><?php echo $ld['status'];?></label> 
			<div class="am-u-lg-7  am-u-md-7 am-u-sm-7">
				<select name="category_status"  data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?>'}">
					<option value=''><?php echo $ld['all_data']; ?></option>
					<option value="1" <?php echo @$category_status=='1'?'selected':''; ?>><?php echo $ld['valid']?> </option>
					<option value="0" <?php echo @$category_status=='0'?'selected':''; ?>><?php echo $ld['invalid']?> </option>
				</select>
			</div>
		</li>
		<li  style="margin:0 0 10px 0">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text">&nbsp;</label> 
			<div class="am-u-lg-7  am-u-md-7 am-u-sm-7">
				<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>"><?php echo $ld['search'];?></button>
			</div>
		</li>
	</ul>
<?php echo $form->end();?>
<?php if($svshow->operator_privilege('user_category_add')){?>
<div class="am-other_action  am-text-right am-btn-group-xs" style="margin-bottom:10px;">
	<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/user_categories/view/0'); ?>">
		<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
	</a> 
</div>
<?php } ?>
<div class="am-panel-group am-panel-tree">
	<div class="am-panel-header">
		<div class="am-panel-hd">
			<div class="am-panel-title am-g">
				<div class="am-u-lg-2 am-u-md-3 am-u-sm-4"><label class="am-checkbox am-success" ><input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck  type="checkbox" />&nbsp; <?php echo $ld['code'];?></label></div>
				<div class="am-u-lg-3 am-u-md-4 am-u-sm-4" ><?php echo $ld['category_name'];?></div>
				<div class="am-u-lg-4 am-show-lg-only"><?php echo $ld['description'];?></div>
				<div class="am-u-lg-1 am-u-md-2 am-hide-sm-only" ><?php echo $ld['status'];?></div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-4" ><?php echo $ld['operate'];?></div>
			</div>
		</div>
	</div>
	<?php if(isset($usercategory_data)&&sizeof($usercategory_data)>0){foreach($usercategory_data as $v){ ?>
	<div class="listtable_div_top am-panel-body">
		<div class="am-panel-bd am-g">
			<div class="am-u-lg-2 am-u-md-3 am-u-sm-4"><label class="am-checkbox am-success"><input type="checkbox" name="checkboxes[]" value="<?php echo $v['UserCategory']['id']?>"  data-am-ucheck />&nbsp;<?php echo $v['UserCategory']['code']; ?></label></div>
			<div class="am-u-lg-3 am-u-md-4 am-u-sm-4"><?php echo $v['UserCategory']['name']; ?></div>
			<div class="am-u-lg-4 am-show-lg-only am-word"><?php echo $v['UserCategory']['description'];?>&nbsp;</div>
			<div class="am-u-lg-1 am-u-md-2 am-hide-sm-only"><?php if($svshow->operator_privilege('user_category_edit')){if($v['UserCategory']['status'] == 1){ ?><span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'user_categories/toggle_on_status',<?php echo $v['UserCategory']['id'];?>)"></span><?php }else{ ?><span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'user_categories/toggle_on_status',<?php echo $v['UserCategory']['id'];?>)"></span><?php }}else{if($v['UserCategory']['status'] == 1){ ?><span class="am-icon-check am-yes" style="cursor:pointer;"></span><?php }else{ ?><span class="am-icon-close am-no" style="cursor:pointer;"></span><?php }}?></div>
			<div class="am-u-lg-2 am-u-md-2 am-u-sm-4">
				<?php if($svshow->operator_privilege('user_category_edit')){ ?>
				<a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/user_categories/view/'.$v['UserCategory']['id']); ?>"><span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?></a>
				<?php } ?>
				<?php if($svshow->operator_privilege('user_category_remove')){ ?>
				<a class="mt am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn" href="javascript:void(0);" onclick="list_delete_submit(admin_webroot+'user_categories/remove/<?php echo $v['UserCategory']['id'] ?>');"><span class="am-icon-trash-o"></span> <?php echo $ld['remove']; ?></a>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php }}else{ ?>
		<div class="listtable_div_top am-panel-body">
			<div class="am-panel-bd am-g">
				<div class="no_data_found">
					<?php echo $ld['no_data']; ?>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php if(isset($usercategory_data) && sizeof($usercategory_data)){?>
		<div id="btnouterlist" class="btnouterlist">
			<div class="am-u-lg-6 am-u-md-12 am-u-sm-12">
				<?php if($svshow->operator_privilege('user_category_remove')){ ?>
				<div class="am-fl">
		    			<label class="am-checkbox am-success" style="margin:5px 5px 5px 0px;">
		    				<input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck type="checkbox">
		    				<?php echo $ld['select_all']?>
		    			</label>
		            </div>
		    		<div class="am-fl"><button type="button" class="am-btn am-radius am-btn-danger am-btn-xs" onclick="batch_operations()"><?php echo $ld['batch_delete']; ?></button></div>
		    		<?php } ?>&nbsp;
			</div>
			<div class="am-u-lg-6 am-u-md-12 am-u-sm-12"><?php echo $this->element('pagers')?></div>
        		<div class="am-cf"></div>
		</div>
	<?php } ?>
</div>
<script type="text/javascript">
function change_state(obj,func,id){
	var ClassName=$(obj).attr('class');
	var val = (ClassName.match(/yes/i)) ? 0 : 1;
	$.ajax({
		url:admin_webroot+func,
		type:"POST",
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

function batch_operations(){
	var category_ids=[];
	$("input[type='checkbox'][name='checkboxes[]']:checked").each(function(){
		category_ids.push($(this).val());
	});
	if(category_ids.length==0){
		alert(j_please_select);
		return false;
	}
	if(confirm(j_confirm_delete)){
		$.ajax({
			url:admin_webroot+'user_categories/batch_operations',
			type:"POST",
			data: {'checkboxes':category_ids},
			dataType:"json",
			success:function(data){
				alert(data.message);
				if(data.flag=='1'){
					window.location.reload();
				}
			}
		});
	}
}
</script>