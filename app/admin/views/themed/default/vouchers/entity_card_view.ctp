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
 
 $voucher_batch_sn=isset($voucher_data['Voucher']['batch_sn'])?$voucher_data['Voucher']['batch_sn']:'';
 $voucher_id=isset($voucher_data['Voucher']['id'])?$voucher_data['Voucher']['id']:0;
 $entity_card_id=isset($voucher_entity_card_data['VoucherEntityCard']['id'])?$voucher_entity_card_data['VoucherEntityCard']['id']:0;
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
	<div class="am-panel-group admin-content am-detail-view" id="accordion" style="width: 98%;margin-right: 1%;">
        <?php echo $form->create('vouchers',array('action'=>'/entity_card_view/'.$entity_card_id.'/'.$voucher_id,'id'=>'EntityCardForm'));?>
        	<!-- 导航 -->
			<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
			    <ul>
				   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>  
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
                    <div class="am-panel-bd am-form-detail am-form am-form-horizontal">
                		<input type="hidden" name="data[VoucherEntityCard][id]" id="entity_card_id" value="<?php echo $entity_card_id; ?>">
                		<input type="hidden" name="data[VoucherEntityCard][batch_sn]" value="<?php echo $voucher_batch_sn; ?>">
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="margin-top:17px;"><?php echo $ld['card'];?></label>
					<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
						<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" >
							<input type="text" name="data[VoucherEntityCard][card_sn]" class="card_sn_check" min="3" max="16" maxlength='16' value="<?php echo isset($voucher_entity_card_data['VoucherEntityCard']['card_sn'])?$voucher_entity_card_data['VoucherEntityCard']['card_sn']:''; ?>" />
						</div>
					</div>
				</div>
                       
	                <div class="am-form-group">
	                            <label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="margin-top:18px;"><?php echo $ld['password'];?></label>
	                            <div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
	                                <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" >
	                                    <input type="text" name="data[VoucherEntityCard][card_password]" minlength="3" value="<?php echo isset($voucher_entity_card_data['VoucherEntityCard']['card_password'])?$voucher_entity_card_data['VoucherEntityCard']['card_password']:''; ?>" />
	                                </div>
	                            </div>
	                </div>
                       
	                <div class="am-form-group">
	                            <label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="margin-top:19px;"><?php echo $ld['denomination'];?></label>
	                            <div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
	                                <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" >
	                                    <input type="text" class="amount_check" name="data[VoucherEntityCard][amount]" minlength="1" value="<?php echo isset($voucher_entity_card_data['VoucherEntityCard']['amount'])?$voucher_entity_card_data['VoucherEntityCard']['amount']:''; ?>" />
	                                </div>
	                            </div>
	                </div>
                       
                       <div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:3px;"><?php echo $ld['exchange_item'];?></label>
					<div class="am-u-lg-6 am-u-md-6 am-u-sm-9">
						<div class="am-u-lg-4 am-u-md-4 am-u-sm-12" >
							<input type="text"  name="data[VoucherEntityCard][product_code]" minlength="1" class="voucher_product_code" readonly value="<?php echo isset($voucher_entity_card_data['VoucherEntityCard']['product_code'])?$voucher_entity_card_data['VoucherEntityCard']['product_code']:''; ?>" />
						</div>
						<div class="am-u-lg-5 am-u-md-5 am-u-sm-8" >
							<input type="text" class="product_search" value=""  placeholder="<?php echo $ld['product_sku_or_name'];?>" />
						</div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
							<button type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick="product_search(this)"><?php echo $ld['search']; ?></button>
						</div>
					</div>
					<div class="am-hide-lg-only am-u-md-2 am-u-sm-3 am-form-group-label">&nbsp;</div>
					<div class="am-u-lg-4 am-u-md-12 am-u-sm-12 am-hide">
						<select class="product_search_list">
							<option value='0'><?php echo $ld['please_select'] ?></option>
						</select>
					</div>
				</div>
                       
			<div class="am-form-group">
				<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="padding-top:3px;"><?php echo $ld['status']?></label>
				<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
					<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
						<?php if(isset($entity_card_status_resource)&&sizeof($entity_card_status_resource)>0){foreach($entity_card_status_resource as $k=>$v){ ?>
						<label class="am-radio am-success">
							<input type="radio" name="data[VoucherEntityCard][status]" data-am-ucheck value="<?php echo $k; ?>" <?php echo (isset($voucher_entity_card_data['VoucherEntityCard']['status'])&&$voucher_entity_card_data['VoucherEntityCard']['status']==$k)||(!isset($voucher_entity_card_data['VoucherEntityCard'])&&$k=='0')?"checked":''; ?> /><?php echo $v; ?>
						</label>
						<?php }} ?>
					</div>
				</div>
			</div>
                       
                <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="margin-top:17px;"><?php echo $ld['variable_start_time'];?></label>
                        <div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
                            <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" >
                                <input type="text" name="data[VoucherEntityCard][start_date]"  minlength="1" value="<?php echo isset($voucher_entity_card_data['VoucherEntityCard']['start_date'])?$voucher_entity_card_data['VoucherEntityCard']['start_date']:''; ?>" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
                            </div>
                        </div>
                </div>
                       
                <div class="am-form-group">
                            <label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="margin-top:18px;"><?php echo $ld['variable_end_time'];?></label>
                            <div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
                                <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" >
                                    <input type="text" name="data[VoucherEntityCard][end_date]"  minlength="1" value="<?php echo isset($voucher_entity_card_data['VoucherEntityCard']['end_date'])?$voucher_entity_card_data['VoucherEntityCard']['end_date']:''; ?>" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
                                </div>
                            </div>
                </div>
           	  <div class="am-form-group">
                            <label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="margin-top:18px;"><?php echo $ld['remarks_notes'];?></label>
                            <div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
                                <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" >
                                    	<textarea name="data[VoucherEntityCard][remark]" maxlength="250"><?php echo isset($voucher_entity_card_data['VoucherEntityCard']['remark'])?$voucher_entity_card_data['VoucherEntityCard']['remark']:''; ?></textarea>
                                </div>
                            </div>
                </div> 
                <div class="am-form-group">
                    <label class="am-u-lg-2 am-u-md-3 am-u-sm-2 am-form-group-label"><?php echo $ld['order_number'];?></label>
                    <div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" >
                            <label><?php if(isset($order_data['Order']['order_code'])){echo $html->link($order_data['Order']['order_code'],"/orders/edit/".$order_data['Order']['id']);} ?>&nbsp;</label>
                        </div>
                    </div>
                </div>

                 <div class="am-form-group">
                    <label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="margin-top:9px;"><?php echo $ld['rebate_030'];?></label>
                    <div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" >
                          <label><?php echo $voucher_entity_card_data['VoucherEntityCard']['use_time']; ?></label>
                        </div>
                    </div>
                </div>
                 <div class="am-form-group">
                    <label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label"><?php echo $ld['ip_address'];?></label>
                    <div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" >
                           <label><?php echo $voucher_entity_card_data['VoucherEntityCard']['ipaddress']; ?></label>
                        </div>
                    </div>
                </div>
                 <!--<div class="am-form-group">
                    <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label"><?php echo $ld['operator'];?></label>
                    <div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" >
                            <label><?php echo $voucher_entity_card_data['VoucherEntityCard']['operator_id']; ?></label>
                        </div>
                    </div>
                </div>-->
                 <div class="am-form-group">
                    <label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label" style="margin-top:9px;"><?php echo $ld['frozen_time'];?></label>
                    <div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" >
                            <label><?php echo $voucher_entity_card_data['VoucherEntityCard']['frozen_time']; ?></label>
                        </div>
                    </div>
                </div>       
                </div>
            </div>
     </div>
     <?php echo $form->end()?>
 </div>
</div>
<script type="text/javascript">
$(function(){
	$('#EntityCardForm').validator({
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
      		
		      if ($(validity.field).is('.card_sn_check')) {
		        if(v.trim()!=""){
		        	if(v.length>=16){
		        		validity.valid = false;
		        		return validity; 
		        	}
		        	var entity_card_id=$("#entity_card_id").val();
		            return $.ajax({  
		                url: admin_webroot+"vouchers/check_card_sn",
		                type:"post",
		                data:{card_sn:v,entity_card_id:entity_card_id},
		                dataType: 'html'
		            }).then(function(data){
		                if(data>=1){
		                    validity.message="卡号重复";
		                    validity.valid = false;
		                    return validity; 
		                }else{
		                    validity.message=null;
		                    $(validity.field).closest('.am-form-group').find('.am-alert').hide();
		                    validity.valid =true;
		                    return validity; 
		                }
		            });
		        }else{
		        	validity.valid = false;
		        }
		        return validity; 
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
	 			$(product_search_list).selected({maxHeight:300,btnWidth:'100%'});
	 			$(product_search_list).parent().removeClass("am-hide");
	 		}
	 	});
	}
}
</script>