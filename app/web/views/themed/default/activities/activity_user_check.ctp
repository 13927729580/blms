<table class='activity_user_table'>
	<?php if(isset($activity_data)&&!empty($activity_data)){ ?>
	<tr>
		<th>活动</th>
		<td><?php echo $activity_data['Activity']['name']; ?></td>
	</tr>
	<tr>
		<th>活动时间</th>
		<td><?php echo date('Y-m-d',strtotime($activity_data['Activity']['start_date'])); ?></td>
	</tr>
	<?php 	if($activity_data['Activity']['channel']=='1'&&trim($activity_data['Activity']['address'])!=''){ ?>
	<tr>
		<th>地址</th>
		<td><?php echo $activity_data['Activity']['address']; ?></td>
	</tr>
	<?php 	}	?>
	<?php 	if(isset($activity_user_data['ActivityUser'])){ ?>
	<tr>
		<th>&nbsp;</th>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<th>姓名</th>
		<td><?php echo $activity_user_data['ActivityUser']['name']; ?></td>
	</tr>
	<tr>
		<th>手机号</th>
		<td><?php echo $activity_user_data['ActivityUser']['mobile']; ?></td>
	</tr>
		<?php		if($activity_user_data['ActivityUser']['payment_status']=='0'){ ?>
		<tr>
			<td colspan='2' class='am-text-center am-text-xl am-text-danger'>未付款</td>
		</tr>
		<?php		}	?>
	<?php		}else{ ?>
		<tr>
			<td colspan='2' class='am-text-center am-text-xl am-text-danger'>活动用户无法匹配</td>
		</tr>
	<?php 	} ?>
	<?php }else{ ?>
		<tr>
			<td colspan='2' class='am-text-center am-text-xl am-text-danger'>活动已过期</td>
		</tr>
	<?php } ?>
</table>
<style type='text/css'>
.activity_user_table{margin:3rem auto;width:95%;max-width:1200px;margin-bottom:5rem;}
.activity_user_table th{width:20%;text-align:right;}
.activity_user_table th,.activity_user_table td{padding:0.5rem;}
</style>