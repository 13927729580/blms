<style>
	.am-panel-title div{font-weight:bold;}
	.am-yes{color:#5eb95e;}
	.am-no{color:#dd514c;}
</style>
<div>
	<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;">
		<?php if($svshow->operator_privilege("invoice_types_add")){?>
			<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('add'); ?>">
				<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
			</a> 
		<?php }?>
	</div>
	<div class="am-panel-group am-panel-tree">
		<div class="listtable_div_btm am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title">
					
					<div class="am-u-lg-4 am-u-md-3 am-u-sm-3"><?php echo $ld['invoice_type_name']?></div>
					<div class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $ld['invoice_type_description']?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['invoice_tax_point']?></div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['valid']?></div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-4"><?php echo $ld['operate']?></div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<?php if(isset($invoice_type_data) && sizeof($invoice_type_data)>0){foreach( $invoice_type_data as $k=>$v ){?>
			<div>
				<div class="listtable_div_top am-panel-body">
					<div class="am-panel-bd am-g" >
						<div class="am-u-lg-4 am-u-md-3 am-u-sm-3"><?php echo $v["InvoiceTypeI18n"]["name"]?>&nbsp;</div>
						<div class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $v["InvoiceTypeI18n"]["direction"]?>&nbsp;</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v["InvoiceType"]["tax_point"]?>&nbsp;</div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
							<?php if($v["InvoiceType"]["status"]==1){?>
								<span class="am-icon-check am-yes"></span>
							<?php }else{ ?>
								<span class="am-icon-close am-no"></span>
							<?php }?>&nbsp;
						</div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-btn-group-xs am-action">
						     <?php if($svshow->operator_privilege('invoice_types_edit')){?>
							   <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/invoice_types/edit/'.$v["InvoiceType"]["id"]); ?>"><span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?></a><?php } ?>
						     <?php if($svshow->operator_privilege('invoice_types_remove')){?>
    			  	  	  		&nbsp;<a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'invoice_types/remove/<?php echo $v['InvoiceType']['id'] ?>');"><span class="am-icon-trash-o"></span> <?php echo $ld['remove']; ?></a>
						  	 <?php }?>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>	
			</div>
		<?php }}else{?>
			<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
		<?php }?>	
	</div>
</div>