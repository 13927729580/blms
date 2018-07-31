<link href="/plugins/AmazeUI/css/amazeui.min.css" type="text/css" rel="stylesheet">
<div class='am-g'>
	<table class='am-table'>
		<tr>
			<th><?php echo $ld['operator']; ?></th>
			<th><?php echo $ld['total']; ?></th>
		</tr>
		<?php if(isset($user_infos)&&sizeof($user_infos)>0){foreach($user_infos as $v){ ?>
		<tr>
			<td><?php echo isset($operator_list[$v['User']['operator_id']])?$operator_list[$v['User']['operator_id']]:$v['User']['operator_id']; ?></td>
			<td><?php echo $v[0]['user_count']; ?></td>
		</tr>
		<?php }} ?>
	</table>
</div>