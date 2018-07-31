<div class="order-list">
	<div class="am-panel am-panel-default">
		<div class="am-panel-hd" style="background-color:#ffffff;padding:0;">
			<!-- <h4 class="am-text-primary am-text-lg" style="font-size:1.6rem;font-weight:600;"><?php echo $ld['account_orders']?></h4> -->
			<div style="text-align:left;font-size:20px;border-bottom:1px solid #ccc;padding-bottom:1rem;margin-top:1%;padding-left:5px;" >
			    <span style="float:left;"><?php echo $ld['account_orders'] ?></span>
			    
			    <div class="am-cf"></div>
			</div>
		</div>
		<div class="am-panel-bd" style="padding:0;margin-top:30px;">
			<table class="am-table am-table-bd admin-content-table" style="table-layout:fixed">
				<tr id="order_tr_1">
					<th style="width:30%;padding-left:2%;"><?php echo $ld["order_no."] ?></th>
					<th style="white-space:nowrap;width:25%"><?php echo $ld["order_status"] ?></th>
					<th style="width:30%;padding-left:5%;" ><?php echo $ld["operation"] ;?></th>
				</tr>
				<?php //pr($my_orders) ?>
				<?php if(!empty($my_orders)) foreach($my_orders as $k=>$v){?>
				<tr id="order_tr_2">
					<td style="padding-left:2%;"><?php echo $svshow->link($v['Order']['order_code'],'/orders/view/'.$v['Order']['id']);?><br /><?php echo date("Y-m-d",strtotime($v['Order']['created']));?><br /><?php echo $v['Order']['consignee'];?></td>
					
					<td><?php echo $v["Order"]["payment_name"];?><?php echo $v['Order']['sub_pay_name']!=""?("-".$v['Order']['sub_pay_name']):""; ?><br /><?php 
					if($v['Order']['status']==0){echo $ld['unrecognized'];}else if($v['Order']['status']==2){ ?>
						<?php echo $ld['order_canceled'] ?>
						<?php  }elseif($v['Order']['payment_status']==0){?>
						<?php if($v['Order']['payment_is_cod']==1 && $v['Order']['shipping_status']==1){echo $ld['order_shipped'];}else{ echo $ld['order_unpaid'] ;}?>
						<?php }elseif($v['Order']['status']==1 && $v['Order']['shipping_status']==0 && $v['Order']['payment_status']==2){ ?>
						<?php echo $ld['order_processing'] ?>
						<?php }elseif($v['Order']['status']==1 && $v['Order']['shipping_status']==1 && $v['Order']['payment_status']==2){ ?>
						<?php echo $ld['order_shipped'] ?>
						<?php }elseif($v['Order']['status']==1 && $v['Order']['shipping_status']==2 && $v['Order']['payment_status']==2){ ?>
						<?php echo $ld['order_complete'] ?>
						<?php }elseif($v['Order']['status']==1 && $v['Order']['shipping_status']==3 && $v['Order']['payment_status']==2){ ?>
						<?php echo $ld['order_processing'] ?>
						<?php }elseif($v['Order']['status']==4){echo $ld['product_returns'];}elseif($v['Order']['shipping_status']==3){echo $ld['order_processing'];}else if($v['Order']['shipping_status']==5){echo $ld['product_returns'];}?>
                             <br />
                         <?php echo $svshow->price_format($v['Order']['total'],$configs['price_format']);?>
					</td>
					
			               <td style="min-width:90px;padding-left:5%;"> <?php echo $svshow->link($ld['details'],'/orders/view/'.$v['Order']['id'],array('class'=>'colorblue'));?>  <?php if($v['Order']['status']==1 && $v['Order']['shipping_status']==1 && $v['Order']['payment_status']==2){ ?><br/><?php echo $svshow->link($ld['confirm_receipt'],'/orders/receiving_order/'.$v['Order']['id'],array('class'=>'colorblue')); }?> <?php  if($v['Order']['status']!=2 && $v['Order']['shipping_status']!=2 && $v['Order']['payment_status']!=2 ){ ?></br><?php echo $svshow->link($ld['cancel_order'],'/orders/cancle_order/'.$v['Order']['id'],array('class'=>'colorblue','onclick'=>"if(confirm('".$ld['confirm_cancel_order']."')){return true;}else{return false;}"));?> <?php
				if($v['Order']['order_currency']=='Euro'){
					$currency_code='EUR';
				}else if($v['Order']['order_currency']=='Dollar'){
					$currency_code='USD';
				}else if($v['Order']['order_currency']=='Pound'){
					$currency_code='GBP';
				}else if($v['Order']['order_currency']=='CA_Dollar'){
					$currency_code='CAD';
				}else if($v['Order']['order_currency']=='AU_Dollar'){
					$currency_code='AUD';
				}else if($v['Order']['order_currency']=='Francs'){
					$currency_code='CHF';
				}else if($v['Order']['order_currency']=='hk'){
					$currency_code='HKD';
				}else if($v['Order']['order_currency']=='CNY'){
					$currency_code='CNY';
				} ?><?php if($v['Order']['payment_is_cod']== 0){ ?><br/><?php echo $svshow->link($ld['pay_now2'],"javascript:void(0);",array('class'=>'colorblue red_txt','onclick'=>"orderpay('".$v['Order']['id']."')"));} ?>
							<?php }?> 
						</td>
				</tr>
				<?php //pr($v["Product"]) ?>
				<?php if(isset($v["Product"])){foreach($v["Product"] as $pk=>$pv){  ?>
				<tr id="order_tr_3">
					<td style="padding-left:2%;" style="line-height:"><a href="<?php echo $html->url('/products/'.$pv['id']);?>"><?php echo $html->image(empty($pv['img_detail'])?('/theme/default/images/default.png'):$pv['img_detail'],array('id'=>$pv['id'],'style'=>'width:80px;'));  ?></a></td>
					<td><?php echo $v['ProductI18n'][$pk]['name'];?> X <?php echo $v['OrderProduct'][$pk]['product_quntity']-$v['OrderProduct'][$pk]['refund_quantity'] ?>
					<br>
					<?php if($v['Order']['status']==1 && $v['Order']['shipping_status']==2 && $v['Order']['payment_status']==2){ ?>
					<a class="am-btn am-btn-warning am-seevia-btn-add am-btn-xs am-radius" href="<?php echo $html->url('/products/'.$pv['id']);?>#Commodity_review">评论</a>
					<?php } ?>
					</td>
					<td style="padding-left:5%;"><?php echo $svshow->price_format($pv['shop_price'],$configs['price_format']);?></td>
				</tr>
				<?php }}?>
				<?php }else{?>
				<tr>
					<td colspan="7"><div class="order_no_record"><?php echo $ld['no_record']; ?></div></td>
				</tr>		
				<?php }?>
			</table>
			<div class="pagenum"><?php echo $this->element('pager');?></div>
		</div>
	</div>
</div>
<div class="am-modal am-modal-no-btn" id="order_pay">
	<div class="am-modal-dialog">
		<div class="am-modal-hd" style=" z-index: 11;" >
	      <h4 class="am-modal-title"><?php echo $ld['fill_payment_method'];?></h4>
	      <span data-am-modal-close class="am-close">&times;</span>
	    </div>
	    <div id="order_pay_content">
	    		
	    </div>
 </div>
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="wechat_ajax_payaction">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
      <div class="am-text-center"><?php echo $html->image('/theme/default/images/loading.gif');  ?></div>
    </div>
  </div>
</div>
<script type="text/javascript">
function orderpay(order_id){
	$.ajax({ 
		url:web_base+"/orders/orderpay/"+order_id,
		dataType:"html",
		type:"POST",
		success: function(data){
			if(data.length>0){
				$("#order_pay_content").html(data);
				$('#order_pay').modal();
			}
	    }
	});
}
</script>
<style type="text/css">
.am-modal-hd .am-close{top:4px;}
</style>