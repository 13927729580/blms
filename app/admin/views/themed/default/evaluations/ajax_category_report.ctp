<?php if(isset($evaluation_category_infos)&&sizeof($evaluation_category_infos)>0){foreach($evaluation_category_infos as $k=>$v){if(!isset($category_evaluations[$k]))continue; ?>
<div class='category_evaluation_report'>
	<table>
		<tr>
			<th colspan='2'><?php echo $v; echo "&nbsp;(".sizeof($category_evaluations[$k]).")"; ?></th>
		</tr>
		<?php foreach($category_evaluations[$k] as $vv){ ?>
		<tr>
			<td><?php echo $vv['name']; ?></td>
			<td><?php echo $html->link(isset($user_evaluation_list[$vv['id']])?$user_evaluation_list[$vv['id']]:0,'/user_evaluation_logs/index?keyword='.$vv['name'],array('target'=>'_blank')); ?></td>
		</tr>
		<?php } ?>
	</table>
</div>
<style type='text/css'>
.category_evaluation_report{width:100%;height:100%;overflow-y:scroll;}
.category_evaluation_report table{width:100%;}
.category_evaluation_report th{text-align:left;}
.category_evaluation_report th,.category_evaluation_report td{padding:0.25rem;}
</style>
<?php }} ?>