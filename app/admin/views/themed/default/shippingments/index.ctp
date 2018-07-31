<style>
 .am-yes{color:#5eb95e;}
 .am-no{color:#dd514c;}
 .am-panel-title div{font-weight:bold;}
</style>
<div>
	<div class="am-panel-group am-panel-tree">
		<div class="listtable_div_btm am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title">
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['code']?></div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['delivery_name']?></div>
					<div class="am-u-lg-2 am-show-lg-only"><?php echo $ld['describe_distribution']?></div>
					<div class="am-u-lg-2 am-show-lg-only"><?php echo $ld['region_name']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['support_cod']?></div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['valid']?></div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-4"><?php echo $ld['operate']?></div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<?php if(isset($shippings) && sizeof($shippings)>0){foreach($shippings as $k=>$shipping){?>
			<div>
				<div class="listtable_div_top am-panel-body">
					<div class="am-panel-bd">
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" ><?php echo $shipping['Shipping']['code']?>&nbsp;</div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3" ><?php echo $shipping['ShippingI18n']['name']?>&nbsp;</div>
						<div class="am-u-lg-2 am-show-lg-only" ><?php echo $shipping['ShippingI18n']['description']?></div>
						<div class="am-u-lg-2 am-show-lg-only"><?php echo $shipping['Shipping']['area_name'];?>&nbsp;</div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2" >
							<?php if($shipping['Shipping']['support_cod']){?>
								<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'shippingments/toggle_on_cod',<?php echo $shipping['Shipping']['id'];?>)"></span>
							<?php }else{ ;?>
								<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'shippingments/toggle_on_cod',<?php echo $shipping['Shipping']['id'];?>)">&nbsp;</span>
							<?php }?>&nbsp;
						</div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" >
							<?php if($shipping['Shipping']['status']){ ?>
								<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'shippingments/toggle_on_status',<?php echo $shipping['Shipping']['id'];?>)"></span>
							<?php }else{ ?>
								<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'shippingments/toggle_on_status',<?php echo $shipping['Shipping']['id'];?>)">&nbsp;</span>
							<?php }?>&nbsp;
						</div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-btn-group-xs am-action" >
							<?php if(!($shipping['Shipping']['status']))
							echo $html->link($ld['install'],'/shippingments/install/'.$shipping['Shipping']['id'],array("class"=>"am-btn am-btn-default am-btn-xs"),'',false,false).'&nbsp;';
							else{if($svshow->operator_privilege("shippingments_edit")) ?><?php {?>
								 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/shippingments/edit/'.$shipping['Shipping']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
									<?php }?>
						<?php 	if($svshow->operator_privilege("shippingments_area")){
							echo $html->link($ld['set_region'],'/shippingments/area/'.$shipping['Shipping']['id'],array("class"=>"am-btn am-btn-default am-btn-xs am-seevia-btn"),'',false,false).'&nbsp;';}}?>
						</div>
							
							
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
		<?php }}else{?>
			<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
		<?php }?>
	</div>
	<div id="btnouterlist" class="btnouterlist">
		<?php echo $this->element('pagers')?>
	</div>
</div>
<script>
function change_state(obj,func,id){
	var ClassName=$(obj).attr('class');
	var val = (ClassName.match(/yes/i)) ? 0 : 1;
	var postData = "val="+val+"&id="+id;
	$.ajax({
		url:admin_webroot+func,
		Type:"POST",
		data: postData,
		dataType:"json",
		success:function(data){
			if(data.flag == 1){
				if(val==0){
					$(obj).removeClass("am-icon-check am-yes");
					$(obj).addClass("am-icon-close am-no");
				}
				if(val==1){
					$(obj).removeClass("am-icon-close am-no");
					$(obj).addClass("am-icon-check am-yes");
				}
			}
		
		}	
	});
}




</script>
