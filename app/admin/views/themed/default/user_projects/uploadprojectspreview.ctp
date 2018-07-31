<style type="text/css">
.btnouterlist label{margin-left: -3px;}
.btnouterlist input{position: relative;bottom: 3px;*position:static;}
.am-radio, .am-checkbox{display:inline-block;margin-top:0px;}
</style>
<?php echo $form->create('user_projects',array('action'=>'/batch_add_projects/','name'=>"theForm"));?>
<div class="tablelist"  class="tablelist am-u-md-12 am-u-sm-12">
	<table  id="t1" class="am-table  table-main">
		<tr>
			<th><label class="am-checkbox am-success" style="font-weight:bold;"><input onclick='listTable.selectAll(this,"checkbox[]")' type="checkbox" checked  data-am-ucheck /><?php echo $ld['number']?></label></th>
			<?php foreach($profilefiled_info as $thk => $thv){?>
				<th><?php echo $thv['ProfilesFieldI18n']['description'];?></th>
			<?php }?>
		</tr>
		<?php if(isset($uploads_list) && sizeof($uploads_list)>0){foreach($uploads_list as $k=>$v){ if($k==0)continue;?>
		<tr>
			<td>
				<label class="am-checkbox am-success">
					<input type="checkbox" name="checkbox[]" value="<?php echo $k?>" checked data-am-ucheck />
					<?php echo $k;?>
				</label>
			</td>
			<?php foreach($profilefiled_info as $kk => $vv){
						$fields_key=$vv['ProfileFiled']['code'];
						$fields_kk=explode(".",$fields_key);
						$fields_desc=$vv['ProfilesFieldI18n']['description'];
						$data_name=isset($key_code[$fields_desc])?$key_code[$fields_desc]:'';
						$textarea_code=array("content","content2","meta_description");
			?>
				<td><?php if(in_array($fields_kk[1],$textarea_code)){ ?><textarea class="user_<?php echo $fields_kk[1]?>" name="data[<?php echo $k?>][<?php echo $fields_kk[1]?>]" ><?php echo isset($v[$fields_kk[1]])?$v[$fields_kk[1]]:"";?></textarea><?php }else{ ?><input type='text' class="user_<?php echo $fields_kk[1]?>" name="data[<?php echo $k?>][<?php echo $fields_kk[1]?>]" value="<?php echo isset($v[$fields_key])?$v[$fields_key]:"";?>" /><?php } ?></td>
			<?php }?>
		</tr>
		<?php }}?>
	</table>
	<div id="btnouterlist" class="btnouterlist" style="margin-left:0;">
		<div>
			<label class="am-checkbox am-success"  style="font-weight:bold;"><input onclick='listTable.selectAll(this,"checkbox[]")' type="checkbox" checked data-am-ucheck /><?php echo $ld['select_all']?></label>&nbsp;
			<input type="submit" class="am-btn am-btn-success am-radius am-btn-sm"  value="<?php echo $ld['d_submit']?>" />
			<input type="reset"  class="am-btn am-btn-success am-radius am-btn-sm"  value="<?php echo $ld['d_reset']?>" />
		</div>
	</div>
</div>
<?php $form->end();?>
<script type="text/javascript">
	$(function(){
        if(document.getElementById('msg')){
            var msg =document.getElementById('msg').value;
            if(msg !=""){
                alert(msg);
                var button=document.getElementById('btnouterlist');
                button.style.display="none";
            }
        }
    });
</script>