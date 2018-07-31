<?php
/*****************************************************************************
 * SV-Cart 添加菜单
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
<style>
.am-radio, .am-checkbox{display: inline-block;}
.am-radio input[type="radio"]{margin-left:0px;}
.am-checkbox, .am-radio{padding-left: 25px;}
.btnouter{}
.am-form-horizontal .am-form-label,.am-form-horizontal .am-radio{padding-top:0px;}
.am-icon-btn {font-size:14px;height:14px;line-height:14px;text-align:center;width:14px;}

.am-list>li{margin-bottom:0;border-style: none;}
.admin-sidebar-list li a{color:#fff;background-color: #5eb95e;}
.am-list > li > a.am-active, .am-list > li > a.am-active:hover, .am-list > li > a.am-active:focus{font-weight: bold;}
.scrollspy-nav.am-sticky.am-animation-slide-top{width: 100%;}
.am-sticky-placeholder{margin-top: 10px;}
.scrollspy-nav {top: 0;z-index: 100;background: #5eb95e;width: 100%;padding: 0 10px}
.scrollspy-nav ul {margin: 0;padding: 0;}
.scrollspy-nav li {display: inline-block;list-style: none;}
.scrollspy-nav a {color: #eee;padding: 10px 20px;display: inline-block;}
.scrollspy-nav a.am-active {color: #fff;font-weight: bold;}
.crumbs{padding-left:0;margin-bottom:22px;}
</style>
<div>	
	<div class="am-panel-group admin-content am-detail-view" id="accordion" style="width: 98%;margin-right: 1%">
		<?php echo $form->create('PaipaiShops',array('name'=>'EditPaipaiShop','action'=>'view/'.$paipai_shop['PaipaiShop']['id'],"onsubmit"=>"return taobao_shop_submit();"));?>
			<!-- 导航 -->
			<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
			    <ul>
			   		<li>
				   		<a href="#<?php if($paipai_shop['PaipaiShop']['id']==0) echo '添加店铺';else echo '编辑店铺';?>">
				   		<?php if($paipai_shop['PaipaiShop']['id']==0) echo '添加店铺';else echo '编辑店铺';?>
				   		</a>
				   	</li> 
				</ul>
			</div>

			<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
			    <input style="margin-right: 0;" type="submit" class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['d_submit']?>"  />
				<input style="margin-right: 0;" type="reset" class="am-btn am-btn-default am-btn-sm am-radius"  value="<?php echo $ld['d_reset']?>" />
				<?php echo $html->link("店铺列表","../paipai_shops/index/",array("class"=>"am-btn am-radius am-btn-warning am-btn-sm "),'',false,false);?>
			</div>
			<!-- 导航结束 -->
			<div id="<?php if($paipai_shop['PaipaiShop']['id']==0) echo '添加店铺';else echo '编辑店铺';?>"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php if($paipai_shop['PaipaiShop']['id']==0) echo '添加店铺';else echo '编辑店铺';?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
		      			<div style="padding-left: 10px;">
		      				<?php if(!$paipai_update_status){
								echo '<div class="popwarning" style="padding-left:18px;"><p>';
								echo '拍拍认证失效，';
								echo $html->link($ld['click_activate'], "/paipai_shops/get_session/".$paipai_shop['PaipaiShop']['id'],array('target'=>"_blank",'class'=>'taobtn','class'=>'taobtn am-btn am-radius am-btn-success am-btn-sm '));
								echo '</p></div>';
							}?>
						</div>
						<input type="hidden" name="data[PaipaiShop][id]" value="<?php echo $paipai_shop['PaipaiShop']['id']?>">	
						<?php if($paipai_shop['PaipaiShop']['id']==0){if(!$can_more){?>
							<div class="am-form-group">
					    		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:15px;text-align: left;">只能有一个淘宝绑定店铺</label>
							</div>
						<?php }else{ ?>
							<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:15px;text-align: left;">店铺类型</label>
								<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					    				<label class="am-radio am-success">
					    					未授权店铺
					    					<input type="radio" name="data[PaipaiShop][use_key]" id="new_type" data-am-ucheck value="0" checked onclick="show_tab(this.value)">
										</label>&nbsp;&nbsp;
										<label class="am-radio am-success">
											已授权店铺
											<input type="radio" name="data[PaipaiShop][use_key]" id="new_type" data-am-ucheck  value="1" onclick="show_tab(this.value)">
										</label>
					    			</div>
					    		</div>
							</div>
											
							<div class="am-form-group tbs_0">
				    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:19px;text-align: left;">access_token</label>
								<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					    				<input type="text" class="text_inputs" name="data[PaipaiShop][access_token_1]" value="<?php echo $paipai_shop['PaipaiShop']['access_token']?>" /><?php echo $html->link("获取SESSION","/paipai_shops/get_session/".$paipai_shop['PaipaiShop']['id'],array('target'=>"_blank",'class'=>'taobtn'));?>
					    			</div>
					    		</div>
							</div>
											
							<div class="am-form-group tbs_1"  style="display:none;">
				    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;margin-top: 20px;">店铺名称</label>
								<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					    				<input type="text" class="text_inputs" id="paipai_shop_shopName" name="data[PaipaiShop][shopName_2]" value="<?php echo $paipai_shop['PaipaiShop']['shopName']?>" />
					    			</div>
									<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-left" ><em style="color:red;padding-top: 20px;padding-left: 0;">*</em></label>
					    		</div>
							</div>
										
							<div class="am-form-group tbs_1"  style="display:none;">
				    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;margin-top: 20px;">app_key</label>
								<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					    				<input type="text" class="text_inputs" id="taobao_shop_app_key" name="data[PaipaiShop][appKey_2]" value="<?php echo $paipai_shop['PaipaiShop']['appKey']?>"/>
					    				<a target='_blank' href='http://www.ioco.cn/%E5%A6%82%E4%BD%95%E8%8E%B7%E5%8F%96%E6%B7%98%E5%AE%9DAPP+KEY-AIV576.html'>如何获取拍拍APP KEY</a>
					    			</div>
									<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-left"><em style="color:red;padding-top: 20px;padding-left: 0;">*</em></label>
					    		</div>
							</div>
										
							<div class="am-form-group tbs_1"  style="display:none;">
				    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;margin-top: 20px;">app_secret</label>
								<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					    				<input type="text" class="text_inputs" id="taobao_shop_app_secret" name="data[PaipaiShop][app_code_2]" value="<?php echo $paipai_shop['PaipaiShop']['app_code']?>"/>
					    			</div>
									<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-left"><em style="color:red;padding-top: 20px;padding-left: 0;">*</em></label>
					    		</div>
							</div>
						
						<?php }}else{ ?> 
							<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;margin-top: 20px;">店铺名称</label>
								<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="">
					    				<input type="text" class="text_inputs" id="paipai_shop_shopName" name="data[PaipaiShop][shopName]" value="<?php echo $paipai_shop['PaipaiShop']['shopName']?>" disabled />
					    			</div>
									<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-left " style="padding-top:20px;padding-left: 0;"><em style="color:red;">*</em></label>
					    		</div>
							</div>
										
							<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;margin-top: 20px;">店铺昵称</label>
								<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					    				<input type="text" class="text_inputs" id="paipai_shop_sellerUin" name="data[PaipaiShop][sellerUin]" value="<?php echo $paipai_shop['PaipaiShop']['sellerUin']?>"   />
					    			</div>
									<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-left"  style="padding-top:20px;padding-left: 0;"><em style="color:red;">*</em></label>
					    		</div>
							</div>
										
							<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;margin-top: 20px;">主要经营的项目</label>
								<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					    				<input type="text" class="text_inputs" id="paipai_shop_mainBusiness" name="data[PaipaiShop][mainBusiness]" value="<?php echo $paipai_shop['PaipaiShop']['mainBusiness']?>"/>
					    			</div>
									<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-left"  style="padding-top:20px;padding-left: 0;"><em style="color:red;">*</em></label>
					    		</div>
							</div>
										
							<div class="am-form-group aa" style="display: none;">
				    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;margin-top: 20px;">appKey</label>
								<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					    				<input type="text" class="text_inputs" id="paipai_shop_appKey" name="data[PaipaiShop][appKey]" value="<?php echo $paipai_shop['PaipaiShop']['appKey']?>"/>
					    			</div>
									<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-left"><em style="color:red;padding-top: 20px;padding-left: 0;">*</em></label>
					    		</div>
							</div>
										
							<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;margin-top: 20px;">access_token</label>
								<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					    				<input type="text" class="text_inputs" name="data[PaipaiShop][access_token]" value="<?php echo $paipai_shop['PaipaiShop']['access_token']?>" /><?php echo $html->link("获取SESSION","javascript:",array('target'=>"_blank",'class'=>'taobtn','onclick'=>'get_session()'));?>
					    			</div>
					    		</div>
							</div>
										
							<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;margin-top: 20px;">腾讯签名</label>
								<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					    				<textarea id="taobao_shop_desc" name="data[PaipaiShop][sigTencent]"><?php echo strip_tags($paipai_shop['PaipaiShop']['sigTencent'])?></textarea>
					    			</div>
					    		</div>
							</div>
										
							<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;margin-top: 20px;">场景签名</label>
								<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					    				<textarea name="data[PaipaiShop][sigPaipai]"><?php echo strip_tags($paipai_shop['PaipaiShop']['sigPaipai'])?></textarea>
					    			</div>
					    		</div>
							</div>
										
							<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;margin-top: 12px;">折扣类型</label>
								<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					    				<label class="am-radio am-success" style="">
					    					<input type="radio" name="data[PaipaiShop][promotions]" data-am-ucheck  value="1" <?php if($paipai_shop['PaipaiShop']['promotions']==1){echo "checked";}?> >有折扣
					    				</label>&nbsp;&nbsp;
					    				<label class="am-radio am-success" style="">
											<input type="radio" name="data[PaipaiShop][promotions]" data-am-ucheck  value="0" <?php if($paipai_shop['PaipaiShop']['promotions']==2){echo "checked";}?> >没折扣
										</label>&nbsp;&nbsp;
											<a class="helpbtn am-icon-btn am-icon-check-circle-o am-success" href="javascript:config_help(1)"> </a><br />
											<span id="config_help_1" style="display:none"><em>选择订单从淘宝导入本站时，折扣的计算方式</em></span>
					    			</div>
					    		</div>
							</div>
						<?php }?>
					</div>
				</div>
			</div>
		<?php echo $form->end();?>				
	</div>
</div>	
<!--ConfigValues-->
<?php echo $form->create('PaipaiShops',array('name'=>'EditPaipaiShop','action'=>'view/'.$paipai_shop['PaipaiShop']['id'],"onsubmit"=>"return taobao_shop_submit();"));?>
<div id="tablemain" class="tablemain">
	<!--编辑店铺-->
	<div>
		<h2></h2>
		<div class="show_border">
			
			<table class="alonetable">
				<?php if($paipai_shop['PaipaiShop']['id']==0){if(!$can_more){?>
				
				<?php }else{ ?>
					
				<?php }}else{ ?> 
			<!-- 编辑页面 -->
				<tr>
					<th></th>
					<td></td>
				</tr>
				<tr>
					<th></th>
					<td></td>
				</tr>
				<tr>
					<th></th>
					<td></td>
				</tr>
				<tr class='aa' style="display:none">
					<th></th>
					<td></td>
				</tr>
				<tr>
					<th></th>
					<td></td>
				</tr>
				<tr>
					<th></th>
					<td></td>
				</tr>
				<tr>
					<th></th>
					<td></td>
				</tr>
				<tr>
					<th></th>
					<td></td>
				</tr>
				<?php }?>
			</table>
		</div>
		
	</div>
</div>
<?php echo $form->end();?>
<script>
	function login_window(app_key){
		window.open ('http://container.open.taobao.com/container?appkey='+app_key, 'newwindow', 'height=600, width=800, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no')
	}
	var taobao_error = '<?php echo isset($taobao_error)?$taobao_error:""; ?>';
	window.onload = function(){
		if(taobao_error!="") alert(taobao_error);
	}

	function show_tab(n){

		var obj1=document.getElementsByClassName("tbs_"+n),
			obj2=document.getElementsByClassName("tbs_"+(1-n));
		 	for (var i=0; i < obj1.length; i++) {
		 		obj1[i].style.display='';
		 	};
		 	for (var i=0; i < obj2.length; i++) {
		 		obj2[i].style.display='none';
		 	};
	}

	function check_shop(){
		var nick = document.getElementById('taobao_shop_nick').value,
			app_key = document.getElementById('taobao_shop_app_key').value,
			app_secret = document.getElementById('taobao_shop_app_secret').value,
			taobao_shop_top_session = document.getElementById('taobao_shop_top_session').value;
		
			$.ajax({
			url:admin_webroot+"taobao_shops/ajax_check_shop",
			type:"POST",
			data:{nick:nick,ak:app_key,as:app_secret,tp:taobao_shop_top_session},
			dataType:"json",
			success:function(data){
				if(data.code == null){
					alert('验证成功');
				}else{
					alert('请填写正确信息');
				}
			}
			});
			
		
	/*	YUI().use('io',function(Y){

			var sUrl = admin_webroot+"taobao_shops/ajax_check_shop",
				postData = "nick="+nick+"&ak="+app_key+"&as="+app_secret+"&tp="+taobao_shop_top_session;
			var cfg = {
				method: "POST",
				data: postData
			};
			var request = Y.io(sUrl, cfg);//开始请求
			var handleSuccess = function(ioId, o){
				eval('result='+o.responseText);
				if(result.code == null){
					alert('验证成功');
				}else{
					alert('请填写正确信息');
				}
			}
			var handleFailure = function(ioId, o){
				alert("异步请求失败!");
			}

			Y.on('io:success', handleSuccess);
			Y.on('io:failure', handleFailure);

			});*/

	}
	function get_session(){
		var app_key = document.getElementById('taobao_shop_app_key').value;
		if(app_key==""){
			alert('app_key不能为空');
			return false;
		}
		window.open("http://container.open.taobao.com/container?appkey="+app_key);
	}
</script>
