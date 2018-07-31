<?php echo $form->create('/preconditions/',array('action'=>'view','class'=>'am-form am-form-horizontal','type'=>'POST')); ?>
<input type='hidden' name="data[Precondition][id]" value="<?php echo isset($precondition_info['Precondition'])?$precondition_info['Precondition']['id']:'0'; ?>" />
<input type='hidden' name="data[Precondition][object]" value="<?php echo isset($precondition_info['Precondition'])?$precondition_info['Precondition']['object']:''; ?>" />
<input type='hidden' name="data[Precondition][object_code]" value="<?php echo isset($precondition_info['Precondition'])?$precondition_info['Precondition']['object_code']:''; ?>" />
<div class='am-form-group'>
	<label class='am-u-lg-3 am-u-md-4  am-u-sm-4 am-text-right'>条件类型</label>
	<div class='am-u-lg-4 am-u-md-5  am-u-sm-6'>
		<select name="data[Precondition][params]" onchange="precondition_params(this)">
			<option value=''><?php echo $ld['please_select']; ?></option>
			<?php if(isset($condition_resource)&&sizeof($condition_resource)>0){foreach($condition_resource as $k=>$v){ ?>
			<option value="<?php echo $k; ?>" <?php echo isset($precondition_info['Precondition'])&&$precondition_info['Precondition']['params']==$k?'selected':''; ?>><?php echo $v; ?></option>
			<?php }} ?>
		</select>
	</div>
	<div class='am-cf'></div>
</div>
<div class='am-form-group'>
	<label class='am-u-lg-3 am-u-md-4  am-u-sm-4 am-text-right'>条件值</label>
	<div class='am-u-lg-8 am-u-md-8  am-u-sm-8' id="precondition_params">
		<?php $PreconditionValue=isset($precondition_info['Precondition'])?$precondition_info['Precondition']['value']:'';$PreconditionValueList=$PreconditionValue!=''?explode(',',$PreconditionValue):array(); ?>
		<div class='am-g' id='precondition_parent_evaluation'>
			<div class="am-input-group">
				<input type="text" class="am-form-field">
				<span class="am-input-group-btn"><button class="am-btn am-btn-success" type="button" onclick="ajax_pre_object(this)"><?php echo $ld['search'] ?></button></span>
			</div>
			<div class='am-g am-text-left am-margin-top-xs'>
				<select name="data[Precondition][value][]" multiple data-am-selected="{maxHeight:150}">
					<?php if(isset($pre_evaluation_list)&&sizeof($pre_evaluation_list)>0){foreach($pre_evaluation_list as $k=>$v){ ?>
					<option value="<?php echo $k; ?>" <?php echo in_array($k,$PreconditionValueList)?'selected':''; ?>><?php echo $v; ?></option>
					<?php }}else{ ?>
					<option value='0'><?php echo $ld['please_select']; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class='am-g' id='precondition_parent_course'>
			<div class="am-input-group">
				<input type="text" class="am-form-field">
				<span class="am-input-group-btn"><button class="am-btn am-btn-success" type="button" onclick="ajax_pre_object(this)"><?php echo $ld['search'] ?></button></span>
			</div>
			<div class='am-g am-text-left am-margin-top-xs'>
				<select name="data[Precondition][value][]" multiple data-am-selected="{maxHeight:150}">
					<?php if(isset($pre_course_list)&&sizeof($pre_course_list)>0){foreach($pre_course_list as $k=>$v){ ?>
					<option value="<?php echo $k; ?>" <?php echo in_array($k,$PreconditionValueList)?'selected':''; ?>><?php echo $v; ?></option>
					<?php }}else{ ?>
					<option value='0'><?php echo $ld['please_select']; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class='am-g'  id='precondition_ability_level'>
			<?php if(isset($ability_level_list)&&sizeof($ability_level_list)>0){foreach($ability_level_list as $v){ ?>
			<label class="am-checkbox am-success">
				<input type="checkbox" name="data[Precondition][value][]" value="<?php echo $v['AbilityLevel']['id']; ?>" data-am-ucheck <?php echo in_array($v['AbilityLevel']['id'],$PreconditionValueList)?'checked':''; ?>> <?php echo $v['Ability']['name'].$v['AbilityLevel']['name']; ?>
			</label>
			<?php }} ?>
		</div>
		<div class='am-g' id='precondition_cycle'>
			<input type='text' value="<?php echo $PreconditionValue; ?>" name="data[Precondition][value]"/>
		</div>
	</div>
	<div class='am-cf'></div>
</div>
<div class='am-form-group'>
	<label class='am-u-lg-3'>&nbsp;</label>
	<div class='am-u-lg-8 am-text-left'>
		<button class='am-btn am-btn-success am-radius' type='button' onclick="ajax_add_precondition(this)"><?php echo $ld['save']; ?></button>
	</div>
	<div class='am-cf'></div>
</div>
<?php echo $form->end(); ?>
<style type='text/css'>
#precondition_params>div.am-g{display:none;margin-top:0px;}
#precondition_ability_level{max-height:250px;overflow-y:scroll;}
</style>
<script type='text/javascript'>
function precondition_params(select){
	var object_params=$(select).val();
	$("#precondition_params>div.am-g").hide();
	if(object_params!=''){
		$("#precondition_params>div#precondition_"+object_params).show();
	}
}

function ajax_pre_object(btn){
	var RequestForm=$(btn).parents('form');
	var object_type=$(RequestForm).find("input[name='data[Precondition][object]']").val();
	var object_code=$(RequestForm).find("input[name='data[Precondition][object_code]']").val();
	var object_keyword=$(btn).parents('div.am-input-group').find("input[type='text']").val();
	var object_select=$(btn).parents('div.am-g').find("select");
	
	$(btn).button('loading');
	$.ajax({
		url: admin_webroot+"preconditions/ajax_pre_object",
		type:"POST",
		data:{'object_type':object_type,'object_code':object_code,'object_keyword':object_keyword},
		dataType:"JSON",
		success:function(result){
			if(result.code==1){
				$(object_select).find('option').remove();
				$.each(result.data,function(index,item){
					$(object_select).append("<option value='"+index+"'>"+item+"</option>")
				});
			}else{
				$(object_select).find("option[value!='0']").remove();
			}
			$(object_select).trigger('changed.selected.amui');
		},complete:function(){
			$(btn).button('reset');
		}
	});
}

function ajax_add_precondition(btn){
	var RequestForm=$(btn).parents('form');
	var object_params=$(RequestForm).find("select[name='data[Precondition][params]']").val();
	if(object_params=='')return;
	$(RequestForm).find("div.am-g:hidden input").attr('disabled',true);
	$(RequestForm).find("div.am-g:hidden select").attr('disabled',true);
	$(btn).button('loading');
	$.ajax({
		url: admin_webroot+"preconditions/add",
		type:"POST",
		data:RequestForm.serialize(),
		dataType:"JSON",
		success:function(result){
			if(result.code=='1'){
				window.location.reload();
			}else{
				alert(result.message);
			}
		},complete:function(){
			$(btn).button('reset');
			$(RequestForm).find("div.am-g:hidden input").attr('disabled',false);
			$(RequestForm).find("div.am-g:hidden select").attr('disabled',false);
		}
	});
}
</script>