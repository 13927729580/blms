<div class="listtable_div_btm am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title am-g">
					<div class="am-u-lg-3 am-u-md-2 am-u-sm-4">	
		
						<label class="am-checkbox am-success  am-hide-sm-only" style="font-weight:bold;">
							<input type="checkbox" name="checkbox" data-am-ucheck value="checkbox" onclick='listTable.selectAll(this,"checkboxes[]")'/> <?php echo $ld['picture']?>
						</label>
		                <label class=" am-show-sm-only" style="font-weight:bold;">
						<?php echo $ld['picture']?>
						</label>
					</div>
					<div   class="am-u-lg-2  am-u-md-2 am-u-sm-2"><?php echo $ld['type']?></div>
					<div class="am-u-lg-2  am-u-md-2 am-u-sm-2"><?php echo $ld['page']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-hide-md-down"><?php echo $ld['language']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-hide-sm-only"><?php echo $ld['valid']?></div>
					<div class="am-u-lg-1  am-u-md-2  am-u-sm-2 am-hide-sm-only"><?php echo $ld['sort']?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-4"><?php echo $ld['operate']?></div>
					<div style="clear:both"></div>
				</div>	
			</div>
		</div>
		<?php if(isset($flash_image_data) && sizeof($flash_image_data)>0){foreach($flash_image_data as $k=>$v){?>
			<div>
				<div class=" listtable_div_table am-panel-body  ">
					<div class="am-panel-bd am-g">
						<div class="am-u-lg-3 am-u-md-2 am-u-sm-4  ">
                            <label class="am-checkbox am-success am-flash-checkbox am-hide-sm-only">
                                <input type="checkbox" name="checkboxes[]" value="<?php echo $v['FlashImage']['id']?>" data-am-ucheck />
                              <?php if($v['FlashImage']['image']){echo $html->image($v['FlashImage']['image'],array('style'=>'height:50px;width:80px;padding:8px 0 0;')); }?> &nbsp;
                            </label>
						  	<label class="am-show-sm-only" >
						  		<?php if($v['FlashImage']['image']){echo $html->image($v['FlashImage']['image'],array('style'=>'height:50px;width:80px;padding:8px 0 0;')); }?> 
							</label>
						</div>
							<div class="am-u-lg-2  am-u-md-2 am-u-sm-2" style="margin-top:17px;">
							<?php echo $flash_info['Flashe']['type']=='0'?$ld['computer']:$ld['mobile']; ?>&nbsp;
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="margin-top:17px;">
							<?php echo @$Resource_info["flashtypes"][$flash_info["Flashe"]["page"]];?>&nbsp;
						</div>
						<div class="am-u-lg-1  am-u-md-2 am-u-sm-2  am-hide-md-down" style="margin-top:17px;">
							<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach($backend_locales as $vv){ if($vv['Language']['locale']==$v['FlashImage']['locale']){echo $vv['Language']['name'];} }}?>&nbsp;
						</div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2  am-hide-sm-only" style="margin-top:17px;">
							<?php if ($v['FlashImage']['status'] == 1){?>
								<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'flashs/toggle_on_status',<?php echo $v['FlashImage']['id'];?>)"></span>
							<?php }elseif($v['FlashImage']['status'] == 0){?>
								<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'flashs/toggle_on_status',<?php echo $v['FlashImage']['id'];?>)">&nbsp;</span>										
							<?php }?>&nbsp;
						</div>
						<div class="am-u-lg-1  am-u-md-2 am-u-sm-2  am-hide-sm-only"  style="margin-top:17px;">
							<?php if(count($flash_image_data)==1){echo "-";}elseif($k==0){?>
								<a onclick="changeOrder('down','<?php echo $v['FlashImage']['id'];?>','0',this)" style="cursor:pointer;">&#9660;</a>
							<?php }elseif($k==(count($flash_image_data)-1)){?>
								<a onclick="changeOrder('up','<?php echo $v['FlashImage']['id'];?>','0',this)" style="color:#cc0000;cursor:pointer">&#9650;</a>
							<?php }else{?>
								<a onclick="changeOrder('up','<?php echo $v['FlashImage']['id'];?>','0',this)" style="color:#cc0000;cursor:pointer">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $v['FlashImage']['id'];?>','0',this) " style="cursor:pointer;">&#9660;</a>
							<?php }?>&nbsp;
						</div>
					    
					 <div class="am-u-lg-5 am-u-md-2 am-u-sm-4" style="max-width:160px; margin-top:12px;padding-right:0;"> 
				 
						 
							<?php if($svshow->operator_privilege("flashs_edit")){?>
						 	<a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/flashs/view/'.$v['FlashImage']['id']); ?>" style="float:left;margin-right:0.5rem;"><span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?></a>
							<?php }
								if($svshow->operator_privilege("flashs_remove")){?>
								   <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:void(0);" onclick="list_delete_submit(admin_webroot+'flashs/remove/<?php echo $v['FlashImage']['id'] ?>');" style="float:left;"><span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?></a> <?php 	}?>&nbsp;
						</div>
						<div  class="am-cf"></div>
					</div>
				</div>
			</div>
<?php }}else{?>
	<div style="margin:50px;text-align:center;">
		<div><?php echo $ld['no_circle_image']?></div>
	</div>
<?php }?>