<link href="/plugins/AmazeUI/css/amazeui.min.css" type="text/css" rel="stylesheet">
<div class='am-g'>
	<table class='am-table'>
		<tr>
			<th><?php echo $ld['operator']; ?></th>
			<th><?php echo $ld['status']; ?></th>
			<th><?php echo $ld['total']; ?></th>
		</tr>
		<?php if(isset($quote_infos)&&sizeof($quote_infos)>0){foreach($quote_infos as $v){ ?>
		<tr>
			<td><?php echo $v['Quote']['quoted_by']; ?></td>
			<td><?php echo isset($quote_status[$v['Quote']['status']])?$quote_status[$v['Quote']['status']]:$v['Quote']['status']; ?></td>
			<td><?php echo $v[0]['quote_count']; ?></td>
		</tr>
		<?php }} ?>
	</table>
</div>