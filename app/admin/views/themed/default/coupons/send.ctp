<?php
/*****************************************************************************
 * SV-CART 电子优惠券管理
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
.btnouter{margin:50px;}
.am-no{color: #dd514c;cursor: pointer;}
.related_dt{width:100%;height:300px;overflow-y: auto;padding-left:10px;}
.related_dt dl{float:left;text-align:left;padding:3px 5px;;border:1px solid #ccc;margin:2px 5px;width:auto;display:block;white-space:nowrap}
.related_dt dl:hover{cursor: pointer;border: 1px solid #5eb95e;color:#5eb95e;}
.related_dt dl:hover span{color:#5eb95e;}
.related_dt dl span{float:none;color: #ccc;padding:3px 2px 0px 2px;margin-right:5px;}
</style>

<div class="am-text-right" style="margin-bottom:10px;">
	<?php echo $html->link($ld['rebate_037'],"/coupons/",array("class"=>"am-btn am-btn-warning am-btn-sm am-radius"),'',false,false);?>
</div>
<div>
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
			<?php if($coupontype['CouponType']['send_type'] == 0){?>
			   	<li><a href="#rebate_038"><?php echo $ld['rebate_038']?></a></li>
			<?php }elseif($coupontype['CouponType']['send_type'] == 3){?>  		
				<li><a href="#rebate_042"><?php echo $ld['rebate_042']?></a></li>
			<?php }elseif($coupontype['CouponType']['send_type'] == 5){?>		
				<li><a href="#rebate_046"><?php echo $ld['rebate_046']?></a></li>
			<?php }?>	
		</ul>
	</div>
	<div class="am-panel-group admin-content" id="accordion" style="width:83%;float:right;">
		<?php if($coupontype['CouponType']['send_type'] == 0){?>
			<?php echo $form->create('Coupon',array('action'=>'' ));?>
				<div id="rebate_038"  class="am-panel am-panel-default">
			  		<div class="am-panel-hd">
						<h4 class="am-panel-title">
							<?php echo $ld['rebate_038']?>
						</h4>
				    </div>
				    <div class="am-panel-collapse am-collapse am-in">
			      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
							<div class="am-form-group">
								<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"></label>
				    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
				    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
										<input type="text" name="user_keywords" id="user_keywords" />
				    				</div>
				    				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
										<input type="button" class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search']?>" onclick="search_user();" />
									</div>
				    			</div>
			    			</div>
			    			<div class="am-form-group">	
								<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-text-center">
									<label><?php echo $ld['rebate_039'];?></label>
				    				<div style=" height:300px;overflow:auto;"><dt id="user_infos" class="related_dt" style="font-weight:normal;">&nbsp;</dt></div>
								</div>
								<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-text-center">
									<label><?php echo $ld['rebate_040'];?></label>
					    			<div id="relative_product">
										<?php if(isset($user_relations) && sizeof($user_relations)>0){foreach($user_relations as $k=>$v){?>
											<div>
											<div class="am-u-lg-10 am-u-md-10 am-u-sm-10">
												<?php echo $v['User']['name'];?>
											</div>
											<div id='r<?php echo $v["User"]["id"]?>' class="am-u-lg-2 am-u-md-2 am-u-sm-2">
												<span class="am-icon-close am-no" onMouseout="onMouseout_deleteimg(this)" onmouseover="onmouseover_deleteimg(this)" onclick="dropCoupon('<?php echo $coupontype['CouponType']['id'];?>','drop_link_users','<?php echo $v['User']['id']?>')">
												</span>
											</div>		
											</div>							
										<?php }}?>
					    			</div>
					    		</div>
			    			</div>		
						</div>
						<div class="btnouter">
							<input type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['d_submit'];?>" />
							<input type="reset"  class="am-btn am-btn-default am-btn-sm am-radius" value="<?php echo $ld['d_reset']?>" />
						</div>
					</div>
				</div>
			<?php echo $form->end();?>
		<?php }elseif($coupontype['CouponType']['send_type'] == 3){?>
			<?php echo $form->create('Coupon',array('action'=>'send_print' ,'method'=>'POST' , 'onsubmit'=>'return num_check();'));?>
				<div id="rebate_042"  class="am-panel am-panel-default">
			  		<div class="am-panel-hd">
						<h4 class="am-panel-title">
							<?php echo $ld['rebate_042'];?>
						</h4>
				    </div>
				    <div class="am-panel-collapse am-collapse am-in">
			      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
							<div class="am-form-group">
								<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['rebate_043'];?></label>
				    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
				    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
										<?php $show_txt="";	if($coupontype['CouponType']['type']==1){$show_txt=$ld['discount'].' '.$coupontype['CouponType']['money']."%";}else if($coupontype['CouponType']['type']==2){$show_txt=$ld['relief'].' ￥'.$coupontype['CouponType']['money'].$ld['app_yuan'];}?>
			            				<input type="text"  name="coupontypename" value="<?php echo $coupontype['CouponTypeI18n']['name']?> [ <?php echo $show_txt; ?>]" readonly/>
	                    				<input type="hidden" style="width:290px;" name="coupon_type_id"  value="<?php echo $coupontype['CouponType']['id']?>"/>
									</div>
				    			</div>
							</div>
							<div class="am-form-group">
								<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['rebate_044'];?></label>
				    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
				    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    					<input type="text"  name="num" id="num"  onKeyUp="is_int(this);"/>
				    					<?php echo $ld['rebate_045'];?>
									</div>
				    			</div>
							</div>
						</div>
						<div class="btnouter">
							<input type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['d_submit'];?>" />
							<input type="reset"  class="am-btn am-btn-default am-btn-sm am-radius" value="<?php echo $ld['d_reset']?>" />
						</div>
					</div>
				</div>
			<?php $form->end();?>
		<?php }elseif($coupontype['CouponType']['send_type'] == 5){?>
			<?php echo $form->create('Coupon',array('action'=>'send_coupon' ,'method'=>'POST' , 'onsubmit'=>'return num_check();'));?>	
				<div id="rebate_046"  class="am-panel am-panel-default">
			  		<div class="am-panel-hd">
						<h4 class="am-panel-title">
							<?php echo $ld['rebate_046'];?>
						</h4>
				    </div>
				    <div class="am-panel-collapse am-collapse am-in">
			      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
							<div class="am-form-group">
								<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['rebate_043'];?></label>
				    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
				    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
										<?php $show_txt="";if($coupontype['CouponType']['type']==1){$show_txt=$ld['discount'].' '.$coupontype['CouponType']['money']."%";}else if($coupontype['CouponType']['type']==2){$show_txt=$ld['relief'].' ￥'.$coupontype['CouponType']['money'].$ld['app_yuan'];}?>
			            				<input type="text" name="coupontypename" value="<?php echo $coupontype['CouponTypeI18n']['name']?> <?php echo $show_txt; ?>" readonly/>
	                    				<input type="hidden" name="coupon_type_id"  value="<?php echo $coupontype['CouponType']['id']?>"/>
									</div>
				    			</div>
							</div>
							<div class="am-form-group">
								<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['rebate_047'];?></label>
				    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
				    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    					<input type="text" name="max_buy_quantity" id="max_buy_quantity"  onKeyUp="is_int(this);"/>
									</div>
				    			</div>
							</div>	
						</div>
						<div class="btnouter">
							<input type="submit" class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['d_submit'];?>" />
							<input type="reset"  class="am-btn am-btn-default am-btn-sm am-radius"  value="<?php echo $ld['d_reset']?>" />
						</div>
					</div>
				</div>
			<?php $form->end();?>
		<?php }?>
	</div>
</div>
<script>
//搜索用户
function search_user(){
    var keywords=document.getElementById('user_keywords').value;
	//var postData = "keywords="+keywords;
	$.ajax({
		url:admin_webroot+"users/order_search_user_information/",
		type:"POST",
		data:{keywords:keywords},
		dataType:"json",
		success:function(data){
			var sel = document.getElementById('user_infos');
				 sel.innerHTML = "";
				 if (data.message){
 	 				if(data.message.length==0){
						alert("<?php echo $ld['rebate_055'];?>");
		         		return;
			     	}else{
			     		var selhtml="";
						for(i=0;i<data.message.length;i++){
							selhtml+="<dl onclick=\"addCoupon('"+data.message[i]['User'].id+"')\">"+data.message[i]['User'].name+"<span class='am-icon-plus'></span></dl>";
						}
						sel.innerHTML = selhtml;
		            }
		         }
		}
	});
	
	/*YUI().use("io",function(Y) {
		var cfg = {
			method: "POST",
			data: postData
		};
		var sUrl = admin_webroot+"users/order_search_user_information/";//访问的URL地址
		var request = Y.io(sUrl, cfg);//开始请求
		var handleSuccess = function(ioId,o){
			if(o.responseText !== undefined){
				 try{
					eval('var result='+o.responseText);
				 }catch(e){
					alert(j_object_transform_failed);
					alert(o.responseText);
				 }
				 var sel = document.getElementById('user_infos');
				 sel.innerHTML = "";
				 if (result.message){
 	 				if(result.message.length==0){
						alert("<?php echo $ld['rebate_055'];?>");
		         		return;
			     	}else{
			     		var selhtml="";
						for(i=0;i<result.message.length;i++){
							selhtml+="<dl onclick=\"addCoupon('"+result.message[i]['User'].id+"')\">"+result.message[i]['User'].name+"<span>+</span></dl>";
						}
						sel.innerHTML = selhtml;
		            }
		         }
		    }
		}
		var handleFailure = function(ioId,o){}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
	});*/
}

//增加select项－－－按用户发放
function addCoupon (linkedId){
	var Id="<?php echo isset($coupontype['CouponType']['id'])?$coupontype['CouponType']['id']:0 ?>";
	var act="insert_link_users";
	$.ajax({
		url:admin_webroot+"coupons/"+act+"/"+linkedId+"/"+Id+"/"+Math.random(),
		type:"GET",
		data:{},
		dataType:"json",
		success:function(data){
			if(data.flag=="1"){
					var newhtml = "";
					for(i=0;i<data.content.length;i++){
						var code = "";
						if(data.content[i].code){
							code = data.content[i].code+'--';
						}
                		newhtml+="<div><div class='am-u-lg-10 am-u-md-10 am-u-sm-10' id='r"+data.content[i].id+"'>"+code+data.content[i].name+"</div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'><span class='am-icon-close am-no' onMouseout='onMouseout_deleteimg(this);' onmouseover='onmouseover_deleteimg(this);' onclick=\"dropCoupon("+data.coupon_type_id+",'"+data.action+"', "+data.content[i].id+");\"></span></div></div>";
					}
					document.getElementById("relative_product").innerHTML = newhtml;
					//alert('<?php echo $ld['add_successful'];?>');
					return;
				}
				if(data.flag=="2"){
					alert(data.msg);
				}
		
		}
	
	});
}


function dropCoupon(coupon_type_id,act,id){
	$.ajax({
		url:admin_webroot+"coupons/"+act+"/"+coupon_type_id+"/"+id+"/"+Math.random(),
		type:"GET",
		data:{},
		dataType:"json",
		success:function(data){
			if(data.flag=="1"){
				alert('<?php echo $ld['deleted_success'];?>');
				var obj=document.getElementById('r'+id);
				//alert(obj);
   	     		obj.parentNode.remove();
				return;
				}
			if(data.flag=="2"){
				alert(j_failed_delete);
			}
		}
	});
	
/*	YUI().use("io",function(Y) {
		var sUrl = admin_webroot+"coupons/"+act+"/"+coupon_type_id+"/"+id+"/"+Math.random();
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
					alert('<?php echo $ld['deleted_success'];?>');
					var obj=document.getElementById('r'+id);
	   	     		obj.parentNode.removeChild(obj);
					return;
				}
				if(result.flag=="2"){
					alert(j_failed_delete);
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
function num_check(){
	var num = document.getElementById('num');
	layer_dialog();
		if( Trim( num.value,'g' ) == "" ){
			layer_dialog_show("<?php echo $ld['rebate_056'];?>","",3);
			return false;
		}
}

function coupon_check(){
	var max_buy_quantity = document.getElementById('max_buy_quantity');
	var order_amount_discount = document.getElementById('order_amount_discount');
	layer_dialog();
		if( Trim( max_buy_quantity.value,'g' ) == "" ){
			layer_dialog_show("<?php echo $ld['rebate_057'];?>","",3);
			return false;
		}
		if( Trim( order_amount_discount.value,'g' ) == "" ){
			layer_dialog_show("<?php echo $ld['rebate_058'];?>","",3);
			return false;
		}
}
</script>