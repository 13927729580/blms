<div class='uploadpreview'>
	<form action="">
		<table class='am-table'>
			<thead>
				<tr>
					<th><label class='am-checkbox am-success am-margin-0'><input type='checkbox' data-am-ucheck /></label></th>
					<?php if(isset($profilefiled_info)&&sizeof($profilefiled_info)>0){foreach($profilefiled_info as $v){ ?>
					<th><?php echo $v['ProfilesFieldI18n']['description']; ?></th>
					<?php }} ?>
				</tr>
			</thead>
			<tbody>
				<?php for($i=0;$i<=14;$i++){ ?>
					<tr>
						<td><label class='am-checkbox am-success am-margin-0'><input type='checkbox' data-am-ucheck <?php echo $i%3==0?'disabled':''; ?>  /></label></td>
						<?php if(isset($profilefiled_info)&&sizeof($profilefiled_info)>0){foreach($profilefiled_info as $v){ ?>
						<td><input type='text' value='' /></td>
						<?php }} ?>
					</tr>
					<?php if($i%3==0){ ?>
					<tr class='warming_row'>
						<td>&nbsp;</td>
						<td colspan="<?php echo isset($profilefiled_info)?sizeof($profilefiled_info)-1:1; ?>"><?php echo $i%3==0?$html->image('/admin/skins/default/img/unfound.png',array('class'=>'am-margin-right-xs')):''; ?>该记录无法上传</td>
					</tr>
				<?php }} ?>
			</tbody>
		</table>
		<button type='submit' class='am-btn am-btn-success am-btn-xs am-radius am-margin-sm am-margin-bottom-lg' onclick="$(this).button('loading');"><?php echo $ld['upload']; ?></button>
	</form>
</div>
<style type='text/css'>
.uploadpreview{width:100%;max-height:600px;margin:0 auto;overflow:scroll;}
.uploadpreview td label.am-checkbox{display: initial;}
.uploadpreview tr.warming_row td{border-top:none;padding-top:0;}
</style>