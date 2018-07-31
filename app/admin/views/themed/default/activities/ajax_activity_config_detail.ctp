<form class='am-form-detail am-form am-form-horizontal'>
	<input type='hidden' name="data[ActivityConfig][id]" value="<?php echo isset($activity_config_detail['ActivityConfig'])?$activity_config_detail['ActivityConfig']['id']:0; ?>" />
	<input type='hidden' name="data[ActivityConfig][activity_id]" value="<?php echo isset($activity_config_detail['ActivityConfig'])?$activity_config_detail['ActivityConfig']['activity_id']:(isset($_REQUEST['activity_id'])?$_REQUEST['activity_id']:0); ?>" />
	<div class='am-form-group'>
		<label class='am-u-lg-3'>名称</label>
		<div class='am-u-lg-8'>
			<input type='text' name="data[ActivityConfig][name]" value="<?php echo isset($activity_config_detail['ActivityConfig'])?$activity_config_detail['ActivityConfig']['name']:''; ?>" />
		</div>
	</div>
	<div class='am-form-group'>
		<label class='am-u-lg-3'>类型</label>
		<div class='am-u-lg-8'>
			<select name="data[ActivityConfig][type]" onchange="activity_config_type(this)">
				<option value="">请选择</option>
				<option value="text" <?php echo isset($activity_config_detail['ActivityConfig'])&&$activity_config_detail['ActivityConfig']['type']=='text'?'selected':''; ?>>文本框</option>
				<option value="radio" <?php echo isset($activity_config_detail['ActivityConfig'])&&$activity_config_detail['ActivityConfig']['type']=='radio'?'selected':''; ?>>单选框</option>
				<option value="checkbox" <?php echo isset($activity_config_detail['ActivityConfig'])&&$activity_config_detail['ActivityConfig']['type']=='checkbox'?'selected':''; ?>>多选框</option>
				<option value="image" <?php echo isset($activity_config_detail['ActivityConfig'])&&$activity_config_detail['ActivityConfig']['type']=='image'?'selected':''; ?>>图片</option>
			</select>
		</div>
	</div>
	<div class='am-form-group'>
		<label class='am-u-lg-3'><?php echo $ld['option']; ?></label>
		<div class='am-u-lg-8'>
			<textarea name="data[ActivityConfig][options]" cols="10" rows="5" placeholder="<?php echo implode(chr(13).chr(10),array($ld['option'].'1',$ld['option'].'2',$ld['option'].'3','...')); ?>"><?php echo isset($activity_config_detail['ActivityConfig'])?$activity_config_detail['ActivityConfig']['options']:''; ?></textarea>
		</div>
	</div>
	<div class='am-form-group'>
		<label class='am-u-lg-3'>状态</label>
		<div class='am-u-lg-8 am-text-left'>
			<label class='am-label am-radio am-success'>
				<input type='radio' name="data[ActivityConfig][status]" value='1' <?php echo isset($activity_config_detail['ActivityConfig'])&&$activity_config_detail['ActivityConfig']['status']=='1'||!isset($activity_config_detail['ActivityConfig'])?'checked':''; ?> /> 有效
			</label>
			<label class='am-label am-radio am-success'>
				<input type='radio' name="data[ActivityConfig][status]" value='0' <?php echo isset($activity_config_detail['ActivityConfig'])&&$activity_config_detail['ActivityConfig']['status']=='0'?'checked':''; ?>/> 无效
			</label>
		</div>
	</div>
	<div class='am-form-group'>
		<label class='am-u-lg-3'>&nbsp;</label>
		<div class='am-u-lg-8'>
			<button type='button' class='am-btn am-btn-success am-btn-xs am-radius' onclick="ajax_activity_config_detail_submit(this)">保存</button>
		</div>
	</div>
</form>
<script type='text/javascript'>
activity_config_type($("select[name='data[ActivityConfig][type]']").get(0));
function activity_config_type(select){
	var config_type=select.value;
	if(config_type=='radio'||config_type=='checkbox'){
		$(select).parents('div.am-form-group').next().show();
	}else{
		$(select).parents('div.am-form-group').next().hide();
	}
}
</script>