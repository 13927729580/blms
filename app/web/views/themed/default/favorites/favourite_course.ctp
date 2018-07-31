<div id="fav_course">
	<?php if(isset($favourite_course_list)&&sizeof($favourite_course_list)>0){?>
	<table class="am-table am-table-striped">
		<tr>
			<th width="30" class='am-hide'><input type="checkbox" name="courseall" value="checkbox" /></th>
			<th colspan='2' class='am-text-center'>课程</th>
			<th style="white-space:nowrap;">内容简介</th>
			<th class="am-text-center">查看详情</th>
		</tr>
		<?php foreach($favourite_course_list as $v){ ?>
		<tr>
			<td class='am-hide'><input type="checkbox" name="checkbox_course" value="<?php echo $v['Course']['id']; ?>" /></td>
			<td class='am-text-center'><?php echo $html->image(trim($v['Course']['img'])!=''?$v['Course']['img']:'/theme/default/images/default.png'); ?></td>
			<td class='am-text-left'><?php echo $v['Course']['name']; ?></td>
			<td style="white-space:nowrap;"><?php echo $v['Course']['meta_description']; ?>&nbsp;</td>
			<td class="am-text-center"><a class="am-btn am-btn-secondary am-btn-xs" href="<?php echo $html->url('/courses/'.$v['Course']['id']) ?>">查看详情</a></td>
		</tr>
		<?php } ?>
	</table>
	<div class="pagenum"><?php if(isset($paging['total'])){ echo $this->element('pager');}?></div>
	<?php }else{ ?>
	<table class="am-table">
		<tr>
			<td  colspan="6" align="center" style="color:#909090;text-align:center;border:none;padding-top: 28px;border:none;">暂无收藏!</td>
		</tr>
	</table>
	<?php } ?>
</div>
<style type='text/css'>
#fav_course{width:100%;}
#fav_course table th{border:none;}
#fav_course table td:nth-child(2){min-height:150px;}
#fav_course table td img{max-width:150px;max-height:150px;margin:15px auto;}
#fav_course table td a{color:#fff;}
</style>
<script type='text/javascript'>
$(function(){
	$("#fav_course").find(".pages a").click(function(){
		var ajax_fav_course=$(this).attr('href');
		$.ajax({
			url:ajax_fav_course,
			type:"POST",
			dataType:"html",
			data: {},
			success: function(result){
				$("#fav_course").parent().html(result);
			}
		});
		return false;
	});
});
</script>