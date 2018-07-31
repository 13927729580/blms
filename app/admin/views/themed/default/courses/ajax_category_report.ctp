<?php if(isset($course_category_infos)&&sizeof($course_category_infos)>0){foreach($course_category_infos as $k=>$v){if(!isset($category_courses[$k]))continue; ?>
<div class='category_course_report'>
	<table>
		<tr>
			<th colspan='2'><?php echo $v; echo "&nbsp;(".sizeof($category_courses[$k]).")"; ?></th>
		</tr>
		<?php foreach($category_courses[$k] as $vv){ ?>
		<tr>
			<td><?php echo $vv['name']; ?></td>
			<td><?php echo $html->link(isset($user_course_list[$vv['id']])?$user_course_list[$vv['id']]:0,'/courses/user_course_detail?course_keyword='.$vv['name'],array('target'=>'_blank')); ?></td>
		</tr>
		<?php } ?>
	</table>
</div>
<style type='text/css'>
.category_course_report{width:100%;height:100%;overflow-y:scroll;}
.category_course_report table{width:100%;}
.category_course_report th{text-align:left;}
.category_course_report th,.category_course_report td{padding:0.25rem;}
</style>
<?php }} ?>