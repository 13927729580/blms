<style>
.am-btn-primary{padding:8px 20px;}
.usercenter_fu .daoru_fu
{
	    margin: 20px 0 50px 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-shadow: 0 0 15px #ccc;
    padding: 10px 20px 10px 20px;
	
}
.am-table>tbody>tr>td{border:none;}
.am-table{margin-bottom:0;margin-top:10px;}
@media only screen and (max-width: 640px)
{.am-table
.am-table{font-size:12px;}
.am-btn-sm{font-size:12px;}
}
h3
{
	font-size: 25px;
    color: #424242;
    padding: 5px 0;
    font-weight: 500;
	border-bottom:1px solid #ddd;
}
.daochu>a{color:#149941;}
</style>
<div class="am-g daoru_fu">
	<h3>上传文件</h3>
	<?php echo $form->create('/evaluation_questions',array('action'=>'preview','class'=>' am-form am-form-horizontal',"enctype"=>"multipart/form-data"));?>
	<table class="am-table">
		<tr>
			<td>&nbsp;</td>
			<td><input type='file' name="evaluation_question" /></td>
		</tr>
		<?php if(isset($profile_info['Profile'])){ ?>
		<tr>
			<td>&nbsp;</td>
			<td class="daochu"><?php echo $html->link('导出实例文件','/evaluation_questions/download_csv_example'); ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td>&nbsp;</td>
			<td><button type='submit' class='am-btn am-btn-primary am-btn-sm'><?php echo $ld['submit']; ?></button></td>
		</tr>
	</table>
	<?php echo $form->end();?>
</div>