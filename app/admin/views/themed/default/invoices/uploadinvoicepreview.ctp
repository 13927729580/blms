<style type="text/css">
.btnouterlist label{margin-left: -3px;}
.btnouterlist input{position: relative;bottom: 3px;*position:static;}
.am-radio, .am-checkbox{display:inline-block;margin-top:0px;}
.user_status,.user_sex,.user_birthday,.user_country,.user_province,.user_city{width:80px;}
</style>
<?php echo $form->create('invoices',array('action'=>'/batch_add_invoice/','name'=>"theForm"));?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
	<table id="t1" class="am-table  table-main">
		<tr>
			<th>
				<label class="am-checkbox am-success" style="font-weight:bold;">
					<input onclick='listTable.selectAll(this,"checkbox[]")' type="checkbox" checked  checked data-am-ucheck />
					<?php echo $ld['number']?>
				</label>
			</th>
			<?php if(isset($uploads_list) && sizeof($uploads_list)>0){foreach($uploads_list[0] as $k=>$v){ ?>
				<th>
				<?php echo $v ?>
				<input type="hidden" name="trs[]" value="<?php echo isset($code_transfer[$v])?$code_transfer[$v]:''; ?>">
				</th>
			<?php }} ?>
		</tr>
		<?php //pr($uploads_list); ?>
		<?php if(isset($uploads_list) && sizeof($uploads_list)>0){foreach($uploads_list as $k=>$v){ if($k==0)continue;?>
		<tr>
			<td>
				<label class="am-checkbox am-success">
					<input type="checkbox" name="checkbox[]" value="<?php echo $k?>" checked  checked data-am-ucheck /><?php echo $k;?>
					<?php if(isset($discount[$k])&&$discount[$k]=="discount"){echo $html->image('/admin/skins/default/img/unfound.png');} ?>
				</label>
			</td>
			<?php foreach ($uploads_list[$k] as $kk => $vv) { ?>
				<td><input type="text" value="<?php echo $vv ?>" name="data[<?php echo $k ?>][]"></td>
			<?php } ?>
		</tr>
		<?php }}?>
	</table>
	<div id="btnouterlist" class="btnouterlist" style="margin-left:0;">
		<div>
			<label class="am-checkbox am-success" style="font-weight:bold;">
				<input onclick='listTable.selectAll(this,"checkbox[]")' type="checkbox" checked  checked data-am-ucheck />
				<?php echo $ld['select_all']?>
			</label>&nbsp;
			<input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit']?>"  onclick="sub(this)" />
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

	function sub(obj){
		var postData = $(obj).parents('form').serialize();
		$.ajax({
			url: admin_webroot+"/invoices/batch_add_invoice/",
			type:"POST",
			data:postData,
			dataType:"json",
			success: function(data){
				if(data.code == 1){
					alert('上传成功！');
					window.location.href=admin_webroot+'/invoices/';
				}else{
					alert(data.message);
				}
	  		}
	  	});	
	}
</script>