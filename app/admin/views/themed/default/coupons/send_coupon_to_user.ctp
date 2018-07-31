<table class="am-table  table-main">
	<thead>
		<tr>
			<th><?php echo $ld['coupon_name']?></th>
			<th><?php echo $ld['type']?></th>
			<th><?php echo $ld['rebate_005']?></th>
			<th><?php echo $ld['rebate_006']?></th>
            <th><?php echo $ld['rebate_026']?></th>
    		<th><?php echo $ld['rebate_027']?></th>
            <th><?php echo $ld['operate']?></th>
		</tr>
	</thead>
	<tbody>
	<?php if(isset($coupon_list)&&sizeof($coupon_list)>0){foreach($coupon_list as $v){ ?>
		<tr>
			<td><?php echo $v['CouponTypeI18n']['name']?></td>
			<td><?php echo $v['CouponType']['type']=='1'?$ld['discount']:$ld['relief'] ?></td>
			<td><?php echo $v['CouponType']['money'] ?></td>
			<td><?php echo $v['CouponType']['min_amount'] ?></td>
			<td><?php echo date("Y-m-d",strtotime($v['CouponType']['use_start_date'])); ?></td>
			<td><?php echo date("Y-m-d",strtotime($v['CouponType']['use_end_date'])); ?></td>
			<td style="text-align:right;"><a class="am-btn am-btn-default am-btn-sm am-text-secondary" href="javascript:void(0);" onclick="send_coupon_to_user('<?php echo $v['CouponType']['id']; ?>')"><?php echo $ld['rebate_011'] ?></a></td>
		</tr>
	<?php }} ?>
	</tbody>
</table>
<script type="text/javascript">
function send_coupon_to_user(coupon_id){
	var user_id="<?php echo $user_id; ?>";
	$.ajax({url: admin_webroot+"coupons/insert_link_users/"+user_id+"/"+coupon_id+"/"+Math.random(),
			type:"POST",
			data:{},
			dataType:"json",
			success: function(data){
				try{
					$(".am-close").click();
					get_user_coupons();
				}catch (e){
					alert(j_object_transform_failed);
				}
	  		}
	  	});
}
</script>