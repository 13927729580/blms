<div class="user_index">
	<table class="am-table admin-content-table">
		<tr>
			<td colspan='2'><h4><?php echo sprintf($ld['user_welcome'],"");echo isset($user_list["User"]["name"])?$user_list["User"]["name"]:$user_list["User"]["user_sn"];?></h4><td>
		</tr>
		<tr >
			<td><?php echo $ld["member_level"] ?>: <span class="colorblue"><?php echo isset($user_list["User"]["rank_name"])?$user_list["User"]["rank_name"]:$ld["ordinary_members"];?></span></td>
			<?php if($svshow->check_module('B2C')){ ?>
			<td><?php echo $ld["consume_this_month"] ?>: <span class="colorred"><?php echo $svshow->price_format($order_month_count,$configs['price_format']);?></span></td>
			<?php } ?>
		</tr><?php if($svshow->check_module('B2C')){ ?>
		<tr>
			<td><?php echo $ld['account_balance'] ?>: <span class="colorred"><?php echo $svshow->price_format($user_list['User']["balance"],$configs['price_format']);?></span></td>
			
			<td><?php echo $ld["total_consumption"] ?>: <span class="colorred"><?php echo $svshow->price_format($order_all_count,$configs['price_format']);?></span></td>
			
		</tr><?php } ?>
		<tr>
			<td colspan='2'><?php echo $ld["account_points"]; ?>: <span><?php echo $user_list["User"]["point"]?></span></td>
		</tr>
	</table>
</div>
<style type='text/css'>
.user_index table{border-bottom:1px solid #ddd;}
.user_index table.am-table tr td{border:none;}
.user_index table td h4{color:#0e90d2;}
</style>