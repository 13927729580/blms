<div>
	<div class="am-text-right" style="margin-bottom:10px;">
		<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('area_view/0/'.$ids); ?>">
			<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
		</a>
	</div>	
	<div class="am-panel-group am-panel-tree">
		<div class="am-panel am-panel-default am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title">
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['number']?></div>
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['region_name']?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['available_regions']?></div>
					<div class="am-u-lg-3 am-u-md-2 am-u-sm-1"><?php echo $ld['description']?></div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-4"><?php echo $ld['operate']?></div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<?php if(isset($shippingarea) && sizeof($shippingarea)>0){foreach( $shippingarea as $k=>$v ){?>
			<div>
				<div class="am-panel am-panel-default am-panel-body">
					<div class="am-panel-bd">
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" ><?php echo $v['ShippingArea']['id']?>&nbsp;</div>
						<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
							<span onclick="javascript:listTable.edit(this, 'shippingments/update_shippingarea_name/', <?php echo $v['ShippingArea']['id']?>)"><?php echo $v['ShippingAreaI18n']['name']?></span>&nbsp;
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" ><?php echo $v['ShippingArea']['region_area_name']?>&nbsp;</div>
						<div class="am-u-lg-3 am-u-md-2 am-u-sm-1" > <?php echo $v['ShippingAreaI18n']['description']?>&nbsp;</div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-action" >
					  <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/shippingments/area_view/'.$v['ShippingArea']['id'].'/'.$ids); ?>"><span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?></a> <a class="am-btn am-btn-default am-btn-xs am-text-danger   am-seevia-btn-delete" href="javascript:void(0);" onclick="list_delete_submit(admin_webroot+'shippingments/remove/<?php echo $v['ShippingArea']['id'] ?>');"> <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?> </a>	
								
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
		<?php }}?>	
	</div>
	<div id="btnouterlist" class="btnouterlist">
		<?php echo $this->element('pagers')?>
	</div>	
</div>

