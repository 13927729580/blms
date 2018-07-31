<select id='ware_list' name="ware_list"> 
	<?php if(isset($info)&&!empty($info)){foreach($info as $v){?>
		<option value="<?php echo $v['id'];?>"><?php echo $v['val'];?></option>
	<?php }} ?>
</select>