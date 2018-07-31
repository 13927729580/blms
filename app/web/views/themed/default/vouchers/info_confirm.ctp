<div class="am-g am-g-fixed">
	<div class="am-radius bor-shadow" style="padding:0;;overflow:hidden">
		<div class="am-text-center" style="padding:20px 0;">
			<h3 style="margin:0;color:#000;font-size:20px;">订单确认</h3>
		</div>
	</div>
	<div class="am-cf am-g" style="margin:18px 18px 0">
		<div class="am-g am-form-group">
		<label class="am-u-sm-5 am-text-right">姓名：</label>
		<div class="am-u-sm-6"><?php echo $receiver_data['consignee'];  ?></div>
		</div>
		<div class="am-g am-form-group">
		<label class="am-u-sm-5 am-text-right">联系方式：</label>
		<div class="am-u-sm-6"><?php echo preg_replace("/(1\d{1,2})\d\d(\d{0,3})/", "\$1*****\$3", $receiver_data['mobile']); ?>&nbsp;</div>
		</div>
		<div class="am-g am-form-group">
		<label class="am-u-sm-5 am-text-right">收货地址：</label>
		<div class="am-u-sm-6"><?php 
			$country_id=isset($receiver_data['Address']['RegionUpdate'][0])?$receiver_data['Address']['RegionUpdate'][0]:0;
			$province_id=isset($receiver_data['Address']['RegionUpdate'][1])?$receiver_data['Address']['RegionUpdate'][1]:0;
			$city_id=isset($receiver_data['Address']['RegionUpdate'][2])?$receiver_data['Address']['RegionUpdate'][2]:0;
			echo isset($region_data[$country_id])?$region_data[$country_id]:'';echo isset($region_data[$province_id])?$region_data[$province_id]:'';echo isset($region_data[$city_id])?$region_data[$city_id]:'';echo $receiver_data['address'];  ?>

	</div>

</div>
	</div>
	<div class="am-u-sm-centered" >
		<table class="am-table">
	<?php foreach ($product_list as $k => $v) { ?>
	<tbody>
		<tr>
			<td width="20%"><?php echo $html->image($v['Product']['img_detail'],array('style'=>'max-width:120px;height:auto;'));  ?></td>
			<td width="80%"><?php echo $v['ProductI18n']['name']; ?></td>
		</tr>
	</tbody>
	   <?php }?>
	    </table>
	</div>
	<?php echo $form->create('vouchers',array('action'=>'ajax_info_confirm','id'=>'VoucherForm','class'=>' am-form am-form-horizontal','type'=>'POST'));?>
		<input type="hidden" name="order_created" value="1" />
		<input type="hidden" name="receiver_data" value='<?php echo json_encode($receiver_data); ?>' />
	<div class="am-text-center am-show-md-up">
		<p style="padding:13px 18px 0;">
			<button type="button" class="am-btn am-radius am-btn-warning" style="width:120px;margin-bottom:10px" onclick="ajax_info_confirm(this)">确认并兑换</button>
		</p>
		<p style="padding:0 18px;">
			<button type="button" class="am-btn am-radius" style="background:#0e90d2;color:#fff;width:120px;" onclick="window.location.href='<?php echo $html->url('/vouchers/receiver_info'); ?>';">返回上一页</button>
		</p>
	</div>
	<div class="am-text-center am-show-sm-only">
		<p style="padding:13px 18px 0;"> 
			<button type="button" class="am-btn am-radius am-btn-warning" style="width:100%;margin-bottom:10px">确认并兑换</button>
		</p>
		<p style="padding:0 18px;">
			<button type="button" class="am-btn am-radius" style="background:#0e90d2;color:#fff;width:100%;" onclick="window.location.href='<?php echo $html->url('/vouchers/receiver_info'); ?>';">返回上一页</button>
		</p>
	</div>
	<?php echo $form->end(); ?>
	
</div>
<style type="text/css">
  a{
    color: #fff;
  }
  a:hover{
    color:#fff;
  }
.am-btn:active:focus, .am-btn:focus{
  outline:none;
}
.am-table>tbody>tr>td{
	border:0;
}
</style>

<script type="text/javascript">

function ajax_info_confirm(btn_obj){
	$(btn_obj).button('loading');
	$.ajax({
            type: "POST",
            url: $("#VoucherForm").prop("action"),
            dataType: 'json',
            data: $("#VoucherForm").serialize(),
            success: function (result) {
            		alert(result.message);
                	if(result.flag=='1'){
                		window.location.href=web_base+"/orders/view/"+result.order_id;
                	}
            },
            error:function(){
            		alert('订单创建失败');
            },
            complete: function(XMLHttpRequest, textStatus) {
            		$(btn_obj).button('reset');
            		if(XMLHttpRequest.status!=200){
            			alert('订单创建失败');
            		}
            }
        });
	
}

//	$.ajax({
//		url:"<?php echo $html->url('/vouchers/search_amount'); ?>",
//		data:{'card_sn':card_sn},
//		type:'POST',
//		dataType: 'json',
//		success:function(data){
//			if(data.flag=='1'){
//				var EntityCard=data.data;
//				var product_info=data.product_info;
//				if(EntityCard.status=='1'){
//					$("#EntityCard_amount").modal();
//					$("#EntityCard_amount table.am-table td:eq(0)").html(EntityCard.amount);
//					if(typeof(product_info['ProductI18n'])!='undefined'){
//						$("#EntityCard_amount table.am-table td:eq(1)").html(product_info['ProductI18n']['name']);
//					}else{
//						$("#EntityCard_amount table.am-table td:eq(1)").html('');
//					}
//				}else{
//					alert('无效兑换券');
//				}
//			}else{
//				alert('无效兑换券');
//			}
//		}
//	});
</script>