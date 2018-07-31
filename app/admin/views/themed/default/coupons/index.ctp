<style type="text/css">
	.am-checkbox {margin-top:0px; margin-bottom:0px;display:inline-block;vertical-align:top;}
	.am-panel-title div{font-weight:bold;}
 
	.am-form-horizontal{padding-top: 0.5em;}
	.am-div-pages{background-color:#f9f9f9;height:40px;padding-top:3px;}
	.am-checkbox input[type="checkbox"]{margin-left:0px;}
</style>
<div>
	<?php echo $form->create('coupons',array('action'=>'/','name'=>"SeearchForm","type"=>"get","class"=>"am-form am-form-horizontal"));?>
		<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label-text" style="padding-right: 0;"><?php echo $ld['keyword'];?></label>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
					<input type="text" name="keywords" class="am-form-field am-radius" placeholder="电子优惠券" value="<?php echo @$keywords;?>" />
				</div>
			</li>
	
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['type']?></label>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
					<select name="send_type" data-am-selected >
						<option value="-1"><?php echo $ld['all_data']?></option>
						<?php foreach( $Resource_info["coupontype"] as $k=>$v ){?>
							<option value="<?php echo $k;?>" <?php if(isset($send_type) && $send_type == "$k"){echo "selected";}?>><?php echo $v;?></option>
						<?php }?>
					</select>
				</div>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-show-lg-only">
					<input type="submit" class="am-btn am-btn-success am-btn-sm am-radius am-hide-sm-down" value="<?php echo $ld['search']?>" />                      </div>
			</li>
			<li class="am-hide-lg-only">	
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label "> </label>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
					<input type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['search']?>" />
				</div></li>
		</ul>
	<?php echo $form->end()?>
	<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;">
		<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/coupons/view'); ?>">
			<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
		</a> 
	</div>
	<?php echo $form->create('',array('action'=>'','name'=>"CouponForm","type"=>"post",'onsubmit'=>"return false"));?>
		<div class="am-panel-group am-panel-tree">
			<div class="listtable_div_btm am-panel-header">
				<div class="am-panel-hd" >
					<div class="am-panel-title">
						<div class="am-u-lg-2  am-u-md-3 am-u-sm-3 ">
							<label class="am-checkbox am-success" style="font-weight:bold;">
								<span class="am-hide-sm-only"><input type="checkbox" name="chkall" value="checkbox" onclick="listTable.selectAll(this,'checkboxes[]');"  data-am-ucheck /></span>
							电子优惠券
							</label>
						</div>
						<div class="am-u-lg-1 am-u-md-2 am-show-lg-only"><?php echo $ld['rebate_003']?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['rebate_004']?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['rebate_005']?></div>
						<div class="am-u-lg-1 am-u-md-2 am-show-lg-only"><?php echo $ld['rebate_006']?></div>
						<div class="am-u-lg-1 am-u-md-2 am-show-lg-only"><?php echo $ld['rebate_007']?></div>
						<div class="am-u-lg-1 am-u-md-2 am-show-lg-only" style="padding-right: 0;"><?php echo $ld['rebate_008']?></div>
						<div class="am-u-lg-1 am-u-md-2 am-show-lg-only"><?php echo $ld['rebate_009']?></div>
						<div class="am-u-lg-3 am-u-md-2 am-u-sm-5"><?php echo $ld['operate']?></div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php if(isset($coupons) && sizeof($coupons) > 0){foreach($coupons as $k=>$c){?>	
				<div>
					<div class="listtable_div_top am-panel-body"  > 
						<div class="am-panel-bd" >
							<div class="am-u-lg-2  am-u-md-3 am-u-sm-3 " >
								<label class="am-checkbox am-success">
										<span class="am-hide-sm-only"><input type="checkbox" name="checkboxes[]" value="<?php echo $c['CouponType']['id']?>"  data-am-ucheck /></span>
									<?php echo $c['CouponTypeI18n']['name']?>&nbsp;
								</label>
							</div>
							<div class="am-u-lg-1 am-show-lg-only" ><?php echo $c['CouponType']['prefix']?>&nbsp;</div>
							<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
								<?php echo isset($Resource_info["coupontype"][$c['CouponType']['send_type']])?$Resource_info["coupontype"][$c['CouponType']['send_type']]:'';?>&nbsp;
							</div>
							<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $c['CouponType']['money']?>&nbsp;</div>
							<div class="am-u-lg-1 am-show-lg-only"><?php echo $c['CouponType']['min_amount']?>&nbsp;</div>
							<div class="am-u-lg-1 am-show-lg-only">
								<?php echo isset($count_coupons_data[$c['CouponType']['id']])?$count_coupons_data[$c['CouponType']['id']]:0 ?>&nbsp;
							</div>
							<div class="am-u-lg-1 am-show-lg-only">
								<?php if($c['CouponType']['send_type'] == 5){echo (isset($type_is_coupon_use_coupons_data[$c['CouponType']['id']]['max_use'])?$type_is_coupon_use_coupons_data[$c['CouponType']['id']]['max_use']:0)."/".(isset($type_is_coupon_use_coupons_data[$c['CouponType']['id']]['count_coupon'])?$type_is_coupon_use_coupons_data[$c['CouponType']['id']]['count_coupon']:0);}else{echo isset($use_coupons_data[$c['CouponType']['id']])?$use_coupons_data[$c['CouponType']['id']]:0;}?>&nbsp;
							</div>
							<div class="am-u-lg-1 am-show-lg-only"><?php echo $c['CouponType']['use_end_date']?>&nbsp;</div>
							<div class="am-u-lg-3 am-u-md-5 am-u-sm-5 am-btn-group-xs am-action">
							<a class="am-btn am-btn-success am-seevia-btn am-btn-xs am-" target='_blank' href="<?php echo $html->url('/coupons/list_view/'.$c['CouponType']['id'] )?>">
                        <span class="am-icon-eye"></span> <?php echo $ld['view']; ?>
                    </a>
							   <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/coupons/view/'.$c['CouponType']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a> <?php if($c['CouponType']['send_type'] == 0 ||$c['CouponType']['send_type'] == 3 ||$c['CouponType']['send_type'] == 5 ){	echo $html->link($ld['rebate_011'],'/coupons/send/'.$c['CouponType']['id'],array("class"=>"am-btn   am-btn-default am-btn-xs  am-btns"),'',false,false).'&nbsp;';}?><a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:void(0);" onclick="list_delete_submit(admin_webroot+'coupons/remove/<?php echo $c['CouponType']['id'] ?>');">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['remove']; ?>
                      </a>		<?php ?>
							</div>
							<div style="clear:both;"></div>
						</div>
					</div>
				</div>
			<?php }}else{?>
				<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
			<?php }?>	
		</div>
		<?php if($svshow->operator_privilege("coupons_remove") || true){?>
			<?php if(isset($coupons) && sizeof($coupons)>0){?>
				<div id="btnouterlist" class="am-div-pages">
					<div class="am-u-lg-5 am-u-md-4 am-u-sm-12 am-hide-sm-only"  style="margin-left:13px;">
						<label class="am-checkbox am-success" style="vertical-align:middle;">
							<input type="checkbox" name="chkall" value="checkbox" onclick="listTable.selectAll(this,'checkboxes[]');" data-am-ucheck />
							<?php echo $ld['select_all']?>
						</label>&nbsp;&nbsp;
						<input type="button" class="am-btn am-radius am-btn-danger am-btn-sm"  onclick="diachange()" value="<?php echo $ld['batch_delete']?>" />
					</div>
					<div class="am-u-lg-6 am-u-md-6 am-u-sm-12">
						<?php echo $this->element('pagers')?>
					</div>
                    <div class="am-cf"></div>
				</div>
			<?php }?>
		<?php }?>
	<?php echo $form->end();?>
</div>


<script type="text/javascript">
function diachange(){
    var id=document.getElementsByName('checkboxes[]');
    var i;
    var j=0;
    var image="";
    for( i=0;i<=parseInt(id.length)-1;i++ ){
      if(id[i].checked){
        j++;
      }
    }
    if( j>=1 ){
    // layer_dialog_show('确定删除?','batch_action()',5);
      if(confirm("<?php echo $ld['confirm_delete']?>"))
      {
        batch_action();
      }
    }else{
    // layer_dialog_show('请选择！','batch_action()',3);
      if(confirm(j_please_select))
      {
        return false;
      }
    }
  }
function batch_action()
{
document.CouponForm.action=admin_webroot+"coupons/batch";
document.CouponForm.onsubmit= "";
document.CouponForm.submit();
}
</script>
