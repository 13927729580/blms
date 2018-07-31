<style>
	.am-form-label{font-weight:bold;}
	.am-panel-title div{font-weight:bold;}
</style>

<div>
	<div>
		<?php echo $form->create('',array('action'=>'/',"type"=>"get",'name'=>"SalesForm","class"=>"am-form am-form-horizontal"));?>
			<div class="am-form-group">
				<label class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-form-label am-text-center">关键字</label>
				<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
					<input type="text" name="keywords"  placeholder="店铺标题/卖家昵称"  value="<?php echo $keywords;?>"/>
				</div>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
					<input class="am-btn am-radius am-btn-success am-btn-sm"  type="submit" value="查询" />
				</div>
			</div>
		<?php echo $form->end()?>
	</div>

	<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;">
		<?php if($can_more){if($svshow->operator_privilege("paipai_shops_add")){?>
			<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('../paipai_shops/view/0'); ?>">
				<span class="am-icon-plus"></span> 新增店铺
			</a>
		<?php }}?>
	</div>
	<div class="am-panel-group am-panel-tree">
		<div class="am-panel am-panel-default am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title">
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">店铺标题</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">店铺名称</div>
					<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">主营项目</div>
					<div class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $ld['operate']?></div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<?php if(isset($paipaishops_list) && sizeof($paipaishops_list)>0){foreach ($paipaishops_list as $k => $u){?>
			<div>
				<div class="am-panel am-panel-default am-panel-body">
					<div class="am-panel-bd am-g">
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $u['PaipaiShop']['mainBusiness']?>&nbsp;</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $u['PaipaiShop']['shopName']?>&nbsp;</div>
						<div class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $u['PaipaiShop']['mainBusiness']?>&nbsp;</div>
						<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
							<?php
								echo $html->link("一键同步","/taobao_updates/quick_update/".$u['PaipaiShop']['id'],array('class'=>'taobtn',"style"=>"text-decoration:underline;color:green;")).'&nbsp;';
								echo $html->link("获取SESSION","/taobao_shops/get_session/".$u['PaipaiShop']['id'],array('target'=>"_blank",'class'=>'taobtn',"style"=>"text-decoration:underline;color:green;")).'&nbsp;';
								echo $html->link($ld['edit'],"/paipai_shops/view/".$u['PaipaiShop']['id'],array("class"=>"am-btn am-btn-success am-btn-sm am-radius"));
							?>
						</div>
						<div style="clear:both;"></div>
				
					</div>
				</div>			
			</div>
		<?php }}else{?>
			<div class="am-text-center" style="margin:50px;"><label>您还没有店铺！请先绑定店铺或新增店铺</label></div>
		<?php }?>
	</div>
	<?php if(isset($taobaoshops_list) && sizeof($taobaoshops_list)>0){?>
		<div id="btnouterlist" class="btnouterlist">
			<?php echo $this->element('pagers')?>
		</div>
	<?php }?>
</div>

<script language="javascript">
	function login_window(app_key,sid){
		window.open ('http://container.open.taobao.com/container?appkey='+app_key, 'newwindow', 'height=600, width=800, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no');
		window.location.href=admin_webroot+"taobao_shops/view/"+sid;
	}

	var taobao_error = '<?php echo isset($taobao_error)?$taobao_error:""; ?>';
window.onload = function(){if(taobao_error!="") alert(taobao_error);}

</script>
