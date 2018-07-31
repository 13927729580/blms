<?php
/*****************************************************************************
 * SV-Cart 编辑优惠卷
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
 
 $Voucher_id=isset($voucher_data['Voucher']['id'])?$voucher_data['Voucher']['id']:0;
 $Voucher_batch_sn=isset($voucher_data['Voucher']['batch_sn'])?$voucher_data['Voucher']['batch_sn']:'';
?>
<style type="text/css">
.status{ display:none;}
.btnouter{}
.am-no{color: #dd514c;cursor: pointer;}
.related_dt{width:100%;height:300px;overflow-y: auto;padding-left:10px;}
.related_dt dl{float:left;text-align:left;padding:3px 5px;;border:1px solid #ccc;margin:2px 5px;width:auto;display:block;white-space:nowrap}
.related_dt dl:hover{cursor: pointer;border: 1px solid #5eb95e;color:#5eb95e;}
.related_dt dl:hover span{color:#5eb95e;}
.related_dt dl span{float:none;color: #ccc;padding:3px 2px 0px 2px;margin-right:5px;}
.am-radio input[type="radio"]{margin-left:0px;}
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline-block;;position:relative;top:5px;}
.am-form-label {
    font-weight: bold;
    margin-left: 10px;
    top: 0px;
}
#accordion [class*="am-u-"] + [class*="am-u-"]:last-child{float:left;}
.am-u-lg-2.am-u-md-3.am-u-sm-3.am-form-label-text{padding-right: 0;}
.am-table>tbody>tr>td, .am-table>tbody>tr>th, .am-table>tfoot>tr>td, .am-table>tfoot>tr>th, .am-table>thead>tr>td, .am-table>thead>tr>th {border-style: none;}

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
<div class="am-g">
	<div class="am-panel-group admin-content am-detail-view" id="accordion" style="width: 95%;margin-right: 2.5%;">
		<?php echo $form->create('vouchers',array('action'=>'/view/'.$Voucher_id,'id'=>'VoucherForm'));?>
				<!-- 导航 -->
					<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
					    <ul>
						   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
				            <?php if(!empty($voucher_data)){ ?>
					            <li><a href="#solid_card"><?php echo $ld['solid_card']?></a></li>
					            <li><a href="#operator_logs"><?php echo $ld['operator_logs']?></a></li>
				            <?php } ?>
						</ul>
					</div>

					<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
					    <button style="margin-right: 0;" type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
						<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
					</div>
				<!-- 导航结束 -->
          <div id="basic_information"  class="am-panel am-panel-default">
                <div class="am-panel-hd">
                    <h4 class="am-panel-title">
                        <?php echo $ld['basic_information']?>
                    </h4>
                </div>
                <div class="am-panel-collapse am-collapse am-in">
                	<input type="hidden" id='Voucher_id' name="data[Voucher][id]" value="<?php echo $Voucher_id; ?>">
                    <div class="am-panel-bd am-form-detail am-form am-form-horizontal">
				<!-- 兑换券批次号 -->
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="margin-top:18px;"><?php echo $ld['exchange_coupon'];?></label>
					<div class="am-u-lg-7 am-u-md-6 am-u-sm-9">
						<div class="am-u-lg-9 am-u-md-9 am-u-sm-12" >
							<?php if(empty($voucher_data['Voucher'])){ ?>
								<input type="text"  name="data[Voucher][batch_sn]" minlength="3" id="Voucher_batch_sn" class='batch_sn_check' value="<?php echo isset($voucher_data['Voucher']['batch_sn'])?$voucher_data['Voucher']['batch_sn']:''; ?>" />
							<?php }else{   echo "<label style='margin-top:8px;'>".$voucher_data['Voucher']['batch_sn']."</label>";?><input type="hidden" id="Voucher_batch_sn" name="data[Voucher][batch_sn]" id="Voucher_batch_sn"  value="<?php echo isset($voucher_data['Voucher']['batch_sn'])?$voucher_data['Voucher']['batch_sn']:''; ?>" ><?php } ?>
						</div>
					</div>
				</div>
				<!-- 名称 -->
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="margin-top:18px;"><?php echo $ld['name'];?></label>
					<div class="am-u-lg-7 am-u-md-6 am-u-sm-9">
						<div class="am-u-lg-9 am-u-md-9 am-u-sm-12" >
							<input type="text"  name="data[Voucher][name]" minlength="1" value="<?php echo isset($voucher_data['Voucher']['name'])?$voucher_data['Voucher']['name']:''; ?>" />
						</div>
					</div>
				</div>
				<!-- 金额 
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="margin-top:18px;"><?php echo $ld['amount'];?></label>
					<div class="am-u-lg-7 am-u-md-6 am-u-sm-9">
						<div class="am-u-lg-9 am-u-md-9 am-u-sm-12" >
							<input type="text"  name="data[Voucher][value]" class="amount_check" value="<?php echo isset($voucher_data['Voucher']['value'])?$voucher_data['Voucher']['value']:''; ?>" minlength="1" />
						</div>
					</div>
				</div>
				兑换的商品货号
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="padding-top:3px;"><?php echo $ld['exchange_item'];?></label>
					<div class="am-u-lg-7 am-u-md-6 am-u-sm-9">
						<div class="am-u-lg-4 am-u-md-4 am-u-sm-12" >
							<input type="text"  name="data[Voucher][product_code]" minlength="1" class="voucher_product_code" readonly value="<?php echo isset($voucher_data['Voucher']['product_code'])?$voucher_data['Voucher']['product_code']:''; ?>" />
						</div>
						<div class="am-u-lg-5 am-u-md-5 am-u-sm-8" >
							<input type="text" class="product_search" value=""  placeholder="<?php echo $ld['product_sku_or_name'];?>" />
						</div>
						<div class="am-u-lg-3 am-u-md-3 am-u-sm-4">
							<button type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick="product_search(this)"><?php echo $ld['search']; ?></button>
						</div>
					</div>
					<div class="am-hide-lg-only am-u-md-3 am-u-sm-3 am-form-group-label">&nbsp;</div>
					<div class="am-u-lg-3 am-u-md-12 am-u-sm-12 am-hide">
						<select class="product_search_list">
							<option value='0'><?php echo $ld['please_select'] ?></option>
						</select>
					</div>
				</div>
				-->
				<!-- 状态 -->
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:8px;"><?php echo $ld['status']?></label>
					<div class="am-u-lg-7 am-u-md-6 am-u-sm-9">
						<div class="am-u-lg-9 am-u-md-9 am-u-sm-12">
							<label class="am-radio am-success">
								<input type="radio" name="data[Voucher][status]" data-am-ucheck
				value="1" <?php echo !isset($voucher_data['Voucher']['status'])||(isset($voucher_data['Voucher']['status'])&&$voucher_data['Voucher']['status']==1)?"checked":"";?> />
				<?php echo $ld['yes']?>
							</label>&nbsp;&nbsp;  
							<label class="am-radio am-success"  style="margin-top:7px;">
								<input name="data[Voucher][status]" type="radio" data-am-ucheck
				value="0" <?php echo isset($voucher_data['Voucher']['status'])&&$voucher_data['Voucher']['status']==0?"checked":"";?> />
				<?php echo $ld['no']?>
							</label>                             
						</div>       
					</div>
				</div>              
				<!-- 使用起始日期
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="padding-top:4px;"><?php echo $ld['rebate_026'];?></label>
					<div class="am-u-lg-7 am-u-md-6 am-u-sm-9">
						<div class="am-u-lg-9 am-u-md-9 am-u-sm-12" >
							<input type="text"  name="data[Voucher][start_date]" value="<?php echo isset($voucher_data['Voucher']['start_date'])?$voucher_data['Voucher']['start_date']:''; ?>" minlength="1" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  />
						</div>
					</div>
				</div>
				使用结束日期
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="padding-top:4px;"><?php echo $ld['rebate_027'];?></label>
					<div class="am-u-lg-7 am-u-md-6 am-u-sm-9">
						<div class="am-u-lg-9 am-u-md-9 am-u-sm-12" >
							<input type="text"  name="data[Voucher][end_date]" value="<?php echo isset($voucher_data['Voucher']['end_date'])?$voucher_data['Voucher']['end_date']:''; ?>" minlength="1" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  />
						</div>
					</div>
				</div>
				-->
				<!-- 备注 -->
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:10px;"><?php echo $ld['note2'];?></label>
					<div class="am-u-lg-7 am-u-md-6 am-u-sm-9">
						<div class="am-u-lg-9 am-u-md-9 am-u-sm-12" >
							<textarea name="data[Voucher][remark]"><?php echo isset($voucher_data['Voucher']['remark'])?$voucher_data['Voucher']['remark']:''; ?></textarea>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		<?php echo $form->end()?>
        </div>
        <?php if(!empty($voucher_data)){ ?>
 	<div id="solid_card"  class="am-panel am-panel-default">
		<div class="am-panel-hd">
			<h4 class="am-panel-title"><?php echo $ld['solid_card']?></h4>
		</div>
		<div class="am-panel-group am-panel-tree">
			<?php if($svshow->operator_privilege("voucher_edit")){ ?>
			<div class="am-text-right am-btn-group-xs" style="margin:10px;">
				<a class="am-btn am-btn-warning am-btn-sm am-radius" href="javascript:void(0);" data-am-modal="{target: '#entity_card_auto_add', closeViaDimmer: 0}">
					<span class="am-icon-plus"></span> <?php echo '自动生成'; ?>
				</a>
				<a class="am-btn am-btn-warning am-btn-sm am-radius" target="_blank" href="<?php echo $html->url('/vouchers/upload/'.$Voucher_batch_sn); ?>">
					<span class="am-icon-plus"></span> <?php echo $ld['bulk_upload'] ?>
				</a>&nbsp;
				<a class="am-btn am-btn-warning am-btn-sm am-radius" target="_blank" href="<?php echo $html->url('/vouchers/entity_card_view/0/'.$Voucher_id); ?>">
					<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
				</a>
			</div>
			<?php } ?>
			<div id="entity_card_list"></div>
		</div>
  	</div>
	<div id="operator_logs"  class="am-panel am-panel-default">
	        <div class="am-panel-hd">
	                    <h4 class="am-panel-title"><?php echo $ld['operator_logs']?></h4>
	        </div>
		<div class="am-panel-group am-panel-tree">
			<div class="listtable_div_btm am-panel-header">
				<div class="am-panel-hd">
					<div class="am-panel-title">
						<div class="am-u-lg-2 am-u-md-2  am-u-sm-2"><?php echo $ld['operator']?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['operations_num']?></div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['ip_address']?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['mac_addr']?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['operation_time']?></div>
						<div class="am-u-lg-4 am-u-md-3 am-u-sm-3"><?php echo $ld['note2']?> </div>
						<div style="clear:both;"></div>
					</div>
				</div>
				<div class="am-panel-bd">
					<?php if(isset($voucher_operation_list)&&sizeof($voucher_operation_list)>0){foreach($voucher_operation_list as $v){ ?>
					<div class="am-panel-body">
						<div class="am-u-lg-2 am-u-md-2  am-u-sm-2"><?php echo isset($voucher_operator_list[$v['VoucherOperation']['operator_id']])?$voucher_operator_list[$v['VoucherOperation']['operator_id']]:$v['VoucherOperation']['operator_id']; ?>&nbsp;</div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $v['VoucherOperation']['num']?>&nbsp;</div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['VoucherOperation']['ip_address']?>&nbsp;</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['VoucherOperation']['macaddr']?>&nbsp;</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['VoucherOperation']['modified']?>&nbsp;</div>
						<div class="am-u-lg-4 am-u-md-3 am-u-sm-3" style="word-break:break-all;word-wrap:break-word;"><?php echo $v['VoucherOperation']['remark']?> </div>
						<div style="clear:both;"></div>
					</div>
					<?php }} ?>
				</div>
			</div>
           	</div>
 	</div>
 	<?php } ?>
  </div>
</div>

<!-- 实体卡自动生成 -->
<div class="am-modal am-modal-no-btn" tabindex="-1" id="entity_card_auto_add">
  <div class="am-modal-dialog" style="overflow-y:auto;">
    <div class="am-modal-hd"><?php echo '自动生成';?>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd am-form-detail am-form am-form-horizontal">
        <table class="am-table">
    		<tr style="border-style: none;">
                	<th width="30%" style="padding-top: 15px;"><?php echo $ld['denomination']; ?></th>
                	<td width="70%" class="am-text-left"><input type="text" id="entity_card_auto_amount" maxlength="8"/></td>
            </tr>
    		<tr>
                	<th width="30%" style="padding-top: 15px;"><?php echo $ld['exchange_item']; ?></th>
                	<td width="30%" class="am-text-left"><input type="text" class="product_search" /></td>
    		   	<td width="30%" style="padding-top: 15px;"><button type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick="product_search(this)"><?php echo $ld['search']; ?></button></td>
            </tr>
    		<tr>
    			<th width="30%">&nbsp;</th>
    			<td width="70%" class="am-text-left">
    				<select id="entity_card_auto_product" class="product_search_list">
    					<option value=""><?php echo $ld['please_select']; ?></option>
    				</select>
    			</td>
    		</tr>
    		<tr>
    			<th width="30%" style="padding-top: 15px;"><?php echo $ld['variable_start_time'];?></th>
    			<td width="70%" class="am-text-left">
    				<input type="text" id="entity_card_auto_start_time" value="" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
    			</td>
    		</tr>
    		<tr>
    			<th width="30%" style="padding-top: 15px;"><?php echo $ld['variable_end_time'];?></th>
    			<td width="70%" class="am-text-left">
    				<input type="text" id="entity_card_auto_end_time" value="" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
    			</td>
    		</tr>
            <tr>
                	<th width="30%" style="padding-top: 15px;"><?php echo $ld['app_qty']; ?></th>
                	<td width="70%" class="am-text-left"><input type="text" id="entity_card_auto_number" maxlength="5" /></td>
            </tr>
            <tr>
            	<th width="30%">&nbsp;</th>
                	<td width="70%" class="am-text-left"><table style="width: 29%;display: inline-block;height: 1px;"></table><button type="button" class="am-btn am-btn-success am-radius am-btn-sm" onclick="entity_card_auto_add()"><?php echo $ld['submit'];?></button></td>
            </tr>
        </table>
    </div>
  </div>
</div>
<!-- 实体卡自动生成 -->
<script type="text/javascript">
$(function(){
	$('#VoucherForm').validator({
	    keyboardEvents:'focusout,change',
	    validateOnSubmit:true,
    	    validate: function(validity) {
      		var v = $(validity.field).val();
      		if ($(validity.field).is('.amount_check')) {
      			var exp = /^([1-9][\d]{0,7}|0)(\.[\d]{1,2})?$/;
				if(exp.test(v)){
					validity.valid = true;
				}else{
					validity.valid = false;
				}
				return validity; 
      		}
      		
		      if ($(validity.field).is('.batch_sn_check')) {
		        if(v.trim()!=""){
		        	var Voucher_id=$("#Voucher_id").val();
		            return $.ajax({  
		                url: admin_webroot+"vouchers/check_batch_sn",
		                type:"post",
		                data:{batch_sn:v,voucher_id:Voucher_id},
		                dataType: 'html'
		            }).then(function(data){
		                if(data>=1){
		                    validity.message="域名重复";
		                    validity.valid = false;
		                    return validity; 
		                }else{
		                    validity.message=null;
		                    $(validity.field).closest('.am-form-group').find('.am-alert').hide();
		                    validity.valid =true;
		                    return validity; 
		                }
		            });
		        }
		     }
    	    }
  });
  
  $(".product_search_list").change(function(){
  	  var product_code=$(this).val().trim();
  	  var voucher_product_code=$(this).parent().parent().find('.voucher_product_code');
  	  if(product_code=='0'){
  	  	product_code="";
  	  }
  	  $(voucher_product_code).val(product_code);
  });
  
 })
 	 
 function product_search(obj){
 	var keyword=$(obj).parent().parent().find('.product_search').val().trim();
 	var product_search_list=$(obj).parent().parent().parent().find('select.product_search_list');
 	if(keyword!=""){
	 	$.ajax({
	 		url:admin_webroot+"products/searchProducts",
	 		data:{product_keyword:keyword},
	 		type:'POST',
	 		dataType:'json',
	 		success:function(data){
				$(product_search_list).find("option").remove();
				$(product_search_list).append("<option value='0'>"+j_please_select+"</option>"); 
	 			if(data.flag=='1'){
	 				var pro_data=data.content;
	 				$.each(pro_data,function(index,value){
	 					$(product_search_list).append("<option value='"+value['Product']['code']+"'>"+value['Product']['code']+" - "+value['ProductI18n']['name']+"</option>");
	 				});
	 			}
	 			$(product_search_list).selected({maxHeight:150});
	 			$(product_search_list).parent().removeClass("am-hide");
	 		}
	 	});
 	}
 }
 entity_card_load();
 
 function entity_card_load(){
 	var Voucher_batch_sn=$("#Voucher_batch_sn").val().trim();
 	var entity_card_status="";
 	if(document.getElementById("entity_card_status")){
 		entity_card_status=$("#entity_card_status").val();
 	}
 	var entity_card_keywords="";
 	if(document.getElementById("entity_card_keywords")){
 		entity_card_keywords=$("#entity_card_keywords").val();
 	}
 	var entity_card_sn_start="";
 	if(document.getElementById("entity_card_sn_start")){
 		entity_card_sn_start=$("#entity_card_sn_start").val();
 	}
 	var entity_card_sn_end="";
 	if(document.getElementById("entity_card_sn_end")){
 		entity_card_sn_end=$("#entity_card_sn_end").val();
 	}
 	if(Voucher_batch_sn!=""){
 		$.ajax({
	 		url:admin_webroot+"vouchers/entity_card",
	 		data:{'batch_sn':Voucher_batch_sn,'entity_card_status':entity_card_status,'entity_card_keywords':entity_card_keywords,'entity_card_sn_start':entity_card_sn_start,'entity_card_sn_end':entity_card_sn_end},
	 		type:'get',
	 		dataType:'html',
	 		success:function(data){
				$("#entity_card_list").html(data);
	 		}
	 	});
 	}
 }
 
 function entity_card_auto_add(){
 	var Voucher_batch_sn=$("#Voucher_batch_sn").val().trim();
 	var card_count=$("#entity_card_auto_number").val().trim();
 	var entity_card_auto_amount=$("#entity_card_auto_amount").val().trim();
 	var entity_card_auto_product=$("#entity_card_auto_product").val().trim();
 	var entity_card_auto_start_time=$("#entity_card_auto_start_time").val().trim();
 	var entity_card_auto_end_time=$("#entity_card_auto_end_time").val().trim();
 	
 	var amount_exp = /^([1-9][\d]{0,7}|0)(\.[\d]{1,2})?$/;
 	var number_exp=/^[0-9]*$/;
 	if(Voucher_batch_sn==""){
 		return false;
 	}else if(entity_card_auto_amount==""||!amount_exp.test(entity_card_auto_amount)){
 		alert('请正确填写实体卡的有效面额');
 		return false;
 	}else if(entity_card_auto_product=="0"||entity_card_auto_product==""){
 		alert('请选择实体卡的商品');
 		return false;
 	}else if(entity_card_auto_start_time==""||entity_card_auto_end_time==""){
 		alert('请选择实体卡的有效期');
 		return false;
 	}else if(card_count==""||!number_exp.test(card_count)||card_count=="0"){
 		alert('请正确填写实体卡自动生成的数量');
 		return false;
 	}else{
	 	 if(confirm(confirm_operation)){
	 		$.ajax({
		 		url:admin_webroot+"vouchers/entity_card_auto_add",
		 		data:{
		 				'batch_sn':Voucher_batch_sn,
		 				'card_count':card_count,
		 				'entity_card_auto_amount':entity_card_auto_amount,
		 				'entity_card_auto_product':entity_card_auto_product,
		 				'entity_card_auto_start_time':entity_card_auto_start_time,
		 				'entity_card_auto_end_time':entity_card_auto_end_time,
		 			},
		 		type:'post',
		 		dataType:'json',
		 		success:function(data){
		 			alert(data.message);
		 			if(data.flag=='1'){
		 				$("#entity_card_auto_add").modal("close");
		 				entity_card_load();
		 			}
		 		}
		 	});
	 	}
	}
}
</script>