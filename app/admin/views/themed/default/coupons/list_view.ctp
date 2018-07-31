<?php
/*****************************************************************************
 * SV-Cart 查看电子优惠券
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
?>
<style type="text/css">
	.am-checkbox {margin-top:0px; margin-bottom:0px;display:inline-block;vertical-align:top;}
	.am-panel-title div{font-weight:bold;}
	.am-form-label{font-weight:bold;}
	.am-form-horizontal .am-form-label{padding-top: 0.5em;}
	.am-checkbox input[type="checkbox"]{margin-left:0px;}
</style>
<div>
	<div class="am-text-right" style="margin-bottom:10px;">
		<?php echo $html->link($ld['rebate_037'],"/coupons/",array("class"=>"am-btn am-btn-warning am-btn-sm am-radius"),'',false,false);?>
	</div>
	<?php echo $form->create('',array('action'=>'','name'=>"CouponForm","type"=>"post",'onsubmit'=>"return false"));?>
		<input type="hidden" name="coupon_type_id" id="coupon_type_id" value="<?php echo $coupon_type_id;?>">
		<?php if($coupon_type == 0){?>
			<div class="am-panel-group am-panel-tree">
				<div class="am-panel am-panel-default am-panel-header">
					<div class="am-panel-hd">
						<div class="am-panel-title">
							<div class="am-u-lg-1 am-show-lg-only">
								<label class="am-checkbox am-success" style="font-weight:bold;">
									<input type="checkbox" name="chkall" value="checkbox" onclick="listTable.selectAll(this,'checkboxes[]');" data-am-ucheck />
									<?php echo $ld['number']?>
								</label>
							</div>
							<div class="am-u-lg-2 am-u-md-5 am-u-sm-5"><?php echo $ld['rebate_004'];?></div>
							<div class="am-u-lg-3 am-show-lg-only"><?php echo $ld['order_code'];?></div>
							<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['rebate_029'];?></div>
							<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['rebate_030'];?></div>
							<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['rebate_031'];?></div>
							<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['operate'];?></div>
							<div style="clear:both;"></div>
						</div>
					</div>
				</div>
				<?php if(isset($coupons) && sizeof($coupons)>0){foreach($coupons as $k=>$coupon){?>
					<div>
						<div class="am-panel am-panel-default am-panel-body">
							<div class="am-panel-bd">
								<div class="am-u-lg-1 am-show-lg-only">
									<label class="am-checkbox am-success">
										<input type="checkbox" name="checkboxes[]" value="<?php echo $coupon['Coupon']['id']?>" data-am-ucheck />
										<?php echo $coupon['Coupon']['id']?>
									</label>
								</div>
								<div class="am-u-lg-2 am-u-md-5 am-u-sm-5"><?php echo $coupon['Coupon']['sn_code']?>&nbsp;</div>
								<div class="am-u-lg-3 am-show-lg-only">
									<?php if($coupon['Coupon']['order_id'] > 0){echo $html->link(empty($coupon['Coupon']['order_id'])?"":$coupon['Coupon']['order_id'],"/orders/view/".$coupon['Coupon']['order_id'],array("target"=>"_blank"));}?>&nbsp;
								</div>
								<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
									<?php if($coupon['Coupon']['user_id'] > 0){echo $coupon['Coupon']['user_name'];}?>&nbsp;
								</div>
								<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
									<?php if($coupon['Coupon']['order_id'] > 0){echo $coupon['Coupon']['used_time'];}else{echo $ld["unused"];}?>&nbsp;
								</div>
								<div class="am-u-lg-1 am-show-lg-only">
									<?php if($coupon['Coupon']['emailed'] == 0){?><?php echo $ld['rebate_032'];?><?php }else if($coupon['Coupon']['emailed'] == 1){?><?php echo $ld['rebate_033'];?><?php }?>&nbsp;
								</div>
								<div class="am-u-lg-2 am-u-md-3 am-u-sm-3">
									<?php echo $html->link($ld['remove'],"javascript:;",array("class"=>"am-btn am-radius am-btn-danger am-btn-sm","onclick"=>"list_delete_submit('{$admin_webroot}coupons/remove_coupon/{$coupon['Coupon']['id']}')"),false,false).'&nbsp;';?>
									<?php if($coupon['Coupon']['emailed'] == 0 && $coupon['Coupon']['order_id'] == 0 ){?>
									<?php echo $html->link($ld['rebate_034'],"javascript:;",array("onclick"=>"send_coupon_email({$coupon['Coupon']['id']});","style"=>"text-decoration:underline;color:green;"));}?>
								</div>
								<div style="clear:both;"></div>
							</div>
						</div>
					</div>
				<?php }}else{?>
					<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
				<?php }?>
			</div>
		<?php }elseif($coupon_type != 0 && $coupon_type != 5){?>
			<div class="am-panel-group am-panel-tree">
				<div class="am-panel am-panel-default am-panel-header">
					<div class="am-panel-hd">
						<div class="am-panel-title">
							<div class="am-u-lg-1 am-show-lg-only">
								<label class="am-checkbox am-success" style="font-weight:bold;">
									<input type="checkbox" name="chkall" value="checkbox" onclick="listTable.selectAll(this,'checkboxes[]');" data-am-ucheck />
									<?php echo $ld['number']?>
								</label>
							</div>	
							<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['rebate_004'];?></div>
							<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['order_code'];?></div>
							<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['rebate_029'];?></div>
							<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['rebate_030'];?></div>
							<div style="clear:both;"></div>
						</div>
					</div>
				</div>
				<?php if(isset($coupons) && sizeof($coupons)>0){foreach($coupons as $k=>$coupon){?>
					<div>
						<div class="am-panel am-panel-default am-panel-body">
							<div class="am-panel-bd">
								<div class="am-u-lg-1 am-show-lg-only">
									<label class="am-checkbox am-success">
										<input type="checkbox" name="checkboxes[]" value="<?php echo $coupon['Coupon']['id']?>" data-am-ucheck />
										<?php echo $coupon['Coupon']['id']?>
									</label>
								</div>
								<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $coupon['Coupon']['sn_code']?>&nbsp;</div>
								<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
									<?php if($coupon['Coupon']['order_id'] > 0){echo $html->link(empty($coupon['Coupon']['order_id'])?"":$coupon['Coupon']['order_id'],"/orders/view/".$coupon['Coupon']['order_id'],array("target"=>"_blank"));}?>&nbsp;
								</div>
								<div class="am-u-lg-2 am-u-md-3 am-u-sm-3">
									<?php if($coupon['Coupon']['user_id'] > 0){echo $coupon['Coupon']['user_name'];}?>&nbsp;
								</div>
								<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
									<?php if($coupon['Coupon']['order_id'] > 0){echo $coupon['Coupon']['used_time'];}else{echo $ld['rebate_093'];}?>&nbsp;
								</div>
								<div style="clear:both;"></div>
							</div>
						</div>
					</div>
				<?php }}else{?>
					<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
				<?php }?>
			</div>
		<?php }?>	
		<?php if($coupon_type == 5){?>
			<div class="am-panel-group am-panel-tree">
				<div class="am-panel am-panel-default am-panel-header">
					<div class="am-panel-hd">
						<div class="am-panel-title">
							<div class="am-u-lg-1 am-show-lg-only">
								<label class="am-checkbox am-success" style="font-weight:bold;">
									<input type="checkbox" name="chkall" value="checkbox" onclick="listTable.selectAll(this,'checkboxes[]');" data-am-ucheck />
									<?php echo $ld['number']?>
								</label>
							</div>	
							<div class="am-u-lg-3 am-u-md-6 am-u-sm-6"><?php echo $ld['rebate_004'];?></div>
							<div class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $ld['rebate_036'];?></div>
							<div class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $ld['rebate_035'];?></div>
							<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['operate'];?></div>
							<div style="clear:both;"></div>
						</div>
					</div>
				</div>
				<?php if(isset($coupons) && sizeof($coupons)>0){foreach($coupons as $k=>$coupon){?>
					<div>
						<div class="am-panel am-panel-default am-panel-body">
							<div class="am-panel-bd">
								<div class="am-u-lg-1 am-show-lg-only">
									<label class="am-checkbox am-success">
										<input type="checkbox" name="checkboxes[]" value="<?php echo $coupon['Coupon']['id']?>"  data-am-ucheck />
										<?php echo $coupon['Coupon']['id']?>
									</label>
								</div>
								<div class="am-u-lg-3 am-u-md-6 am-u-sm-6"><?php echo $coupon['Coupon']['sn_code']?>&nbsp;</div>
								<div class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $coupon['Coupon']['max_use_quantity'];?>&nbsp;</div>
								<div class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $coupon['Coupon']['max_buy_quantity'];?>&nbsp;</div>
								<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
									<?php echo $html->link($ld["delete"],"javascript:void(0);",array("class"=>"am-btn am-radius am-btn-danger am-btn-sm","onclick"=>"if(confirm('{$ld['confirm_delete']}')){window.location.href='{$admin_webroot}coupons/remove_coupon/{$coupon['Coupon']['id']}';}"));?>&nbsp;
								</div>
								<div style="clear:both;"></div>
							</div>
						</div>
					</div>
				<?php }}else{?>
					<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
				<?php }?>
			</div>
		<?php }?>
		<?php if(isset($coupons) && sizeof($coupons)>0){?>
			<div id="btnouterlist" class="btnouterlist">
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
					<label class="am-checkbox am-success"  style="vertical-align:middle;">
						<input type="checkbox" name="chkall" value="checkbox" onclick="listTable.selectAll(this,'checkboxes[]');" data-am-ucheck  />
						<?php echo $ld['select_all']?>
					</label>&nbsp;&nbsp;
					<input type="button" class="am-btn am-radius am-btn-danger am-btn-sm" onclick="diachange()" value="<?php echo $ld['delete']?>" />
				</div>
				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					<?php echo $this->element('pagers')?>
				</div>
			</div>
		<?php }?>
	<?php echo $form->end();?>						
</div>

<!--Main Start-->
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
function batch_action(){
	document.CouponForm.action=admin_webroot+"coupons/batch_remove_coupon";
	document.CouponForm.onsubmit= "";
	document.CouponForm.submit();
}
function send_coupon_email(id){
	$.ajax({
		url:admin_webroot+"coupons/user_coupon_email/"+id+"/"+Math.random(),
		type:"GET",
		dataType:"json",
		success:function(data){
			if(data.flag=="1"){
					alert("<?php echo $ld['mail_sent_successfully'];?>");
					window.location.href = window.location.href;
			}
			if(data.flag=="0"){
				alert("<?php echo $ld['send_mail_failed'];?>");
			}
		}
	});
	
/*	YUI().use("io",function(Y) {
		var sUrl = admin_webroot+"coupons/user_coupon_email/"+id+"/"+Math.random();
		var cfg = {
			method: "GET"
		};
		var request = Y.io(sUrl, cfg);//开始请求
		var newhtml = "";
		var handleSuccess = function(ioId, o){
			if(o.responseText !== undefined){
				try{
					eval('result='+o.responseText);
				}catch(e){
					alert(j_object_transform_failed);
					alert(o.responseText);
				}
				if(result.flag=="1"){
					alert(<?php echo $ld['mail_sent_successfully'];?>);
				}
				if(result.flag=="0"){
					alert(<?php echo $ld['send_mail_failed'];?>);
				}
			}
		}
		var handleFailure = function(ioId, o){
			//alert("异步请求失败!");
		}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
	});*/
}
</script>