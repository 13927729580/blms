<?php
/*****************************************************************************
 * SV-Cart 编辑专题
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
// pr($invoice_data['Invoice']['id']);
$Invoice_id=isset($invoice_data['invoice']['id'])?$invoice_data['invoice']['id']:0;
?>
<style type="text/css">
label{font-weight:normal;}
@media only screen and (max-width: 640px){body {word-wrap: normal;}}
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
.btnouter{}
.img_select{max-width:150px;max-height:120px;}

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
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-g">
	<div class="am-panel-group admin-content am-detail-view" id="accordion"  style="width: 95%;margin-right: 2.5%;">
		<?php echo $form->create('Invoice',array('action'=>'/view/'.(isset($this->data['Invoice']['id'])?$this->data['Invoice']['id']:''),'id'=>'InvoiceForm','onsubmit'=>'return pages_check();'
		));?>
	    <!-- 导航 -->
		<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
		    <ul>
			   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
			</ul>
		</div>

		<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
		    <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" onclick="" value="<?php echo $ld['d_submit'];?>" />  
	        <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
		</div>
		<!-- 导航结束 -->
		<input type="hidden" id="invoice_id" name="data[Invoice][id]" value="<?php echo isset($invoice_data['Invoice']['id'])?$invoice_data['Invoice']['id']:'';?>" />
			<div id="basic_information"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['basic_information']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
		      			<!-- 发票类型 -->
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['invoice_type']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
			    					<select data-am-selected id="invoice_type" name="data[Invoice][invoice_type]">
										<option value=""><?php echo $ld['please_select']?></option>
			                            <option value="0" <?php if ($invoice_data['Invoice']['invoice_type']=='0') {echo 'selected';};?>>普通发票 </option>
			                            <option value="1" <?php if ($invoice_data['Invoice']['invoice_type']=='1') {echo 'selected';}; ?>>增值税普通发票 </option>
			                            <option value="2" <?php if ($invoice_data['Invoice']['invoice_type']=='2') {echo 'selected';}; ?>>增值税专用发票 </option>
				    				</select>
				    				<span style="position: absolute;right: 0;top: 9px;color: red;">*</span>
			    				</div>
			    			</div>
			    		</div>
			    		<!-- 发票号 -->
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">发票号</label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
			    					<input id="invoice_number" type="text" name="data[Invoice][invoice_number]" value="<?php echo $invoice_data['Invoice']['invoice_number']; ?>" />
			    					<span style="position: absolute;right: 0;top: 9px;color: red;">*</span>
			    				</div>
			    			</div>
			    		</div>
			    		<!-- 发票内容 -->
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['invoice_content']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
			    					<textarea id="invoice_content" name="data[Invoice][invoice_content]" cols="30" rows="8" maxlength="250"><?php echo $invoice_data['Invoice']['invoice_content']; ?></textarea>
			    					<span style="position: absolute;right: 0;top: 9px;color: red;">*</span>
			    				</div>
			    			</div>
			    		</div>
			    		<!-- 发票金额 -->
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['invoice_money']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
			    					<input id="invoice_money" type="text" name="data[Invoice][invoice_money]" value="<?php echo $invoice_data['Invoice']['invoice_money']; ?>" maxlength="10" />
			    					<span style="position: absolute;right: 0;top: 9px;color: red;">*</span>
			    				</div>
			    			</div>
			    		</div>
			    		<!-- 开票日期 -->
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['builling_date']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
			    					<input id="builling_date" type="text" name="data[Invoice][builling_date]" class="am-form-field" value="<?php if(isset($invoice_data['Invoice']['builling_date'])!=''){echo date('Y-m-d',strtotime($invoice_data['Invoice']['builling_date']));}?>" data-am-datepicker="{theme:'success'}" readonly required />
			    					<span style="position: absolute;right: 0;top: 9px;color: red;">*</span>
			    				</div>
			    			</div>
			    		</div>
			    		<!-- 开票人 -->
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['issuer']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
			    					<input id="issuer" type="text" name="data[Invoice][issuer]" value="<?php echo $invoice_data['Invoice']['issuer']; ?>" />
			    					<span style="position: absolute;right: 0;top: 9px;color: red;">*</span>
			    				</div>
			    			</div>
			    		</div>
			    		<!-- 购买方名称 -->
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['purchaser_name']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
			    					<input id="purchaser_name" type="text" name="data[Invoice][purchaser_name]" value="<?php echo $invoice_data['Invoice']['purchaser_name']; ?>" />
			    				</div>
			    			</div>
			    		</div>
			    		<!-- 纳税人识别号 -->
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['taxpayer_identification_number']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
			    					<input id="taxpayer_identification_number" type="text" name="data[Invoice][taxpayer_identification_number]" value="<?php echo $invoice_data['Invoice']['taxpayer_identification_number']; ?>" />
			    				</div>
			    			</div>
			    		</div>
			    		<!-- 地址 -->
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['address']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
			    					<input id="address" type="text" name="data[Invoice][address]" value="<?php echo $invoice_data['Invoice']['address']; ?>" />
			    				</div>
			    			</div>
			    		</div>
			    		<!-- 电话 -->
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['phone']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
			    					<input id="phone" type="text" name="data[Invoice][phone]" value="<?php echo $invoice_data['Invoice']['phone']; ?>" />
			    				</div>
			    			</div>
			    		</div>
			    		<!-- 开户行 -->
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['open_bank']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
			    					<input id="open_bank" type="text" id="Route_url" name="data[Invoice][open_bank]" value="<?php echo $invoice_data['Invoice']['open_bank']; ?>" />
			    				</div>
			    			</div>
			    		</div>
			    		<!-- 账号 -->
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['account_number']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
			    					<input id="account_number" type="text" name="data[Invoice][account_number]" value="<?php echo $invoice_data['Invoice']['account_number']; ?>" />
			    				</div>
			    			</div>
			    		</div>
			    		<!-- 备注 -->
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['remark']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
			    					<textarea id="remark" name="data[Invoice][remark]" cols="30" rows="10"  maxlength="255"><?php echo $invoice_data['Invoice']['remark']; ?></textarea>
			    				</div>
			    			</div>
			    		</div>
			    		<!-- 状态 -->
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['status']?></label>
			    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
			    				<div class="am-u-lg-9 am-u-md-10 am-u-sm-6">
				    				<label class="am-radio am-success" style="padding-top:2px;">
									<input type="radio" class="radio"  data-am-ucheck name="data[Invoice][status]" value="0" <?php if(isset($this->data['Invoice']['status'])&&$this->data['Invoice']['status'] == 0){echo "checked";}else{echo "checked";} ?>/>未处理</label>
									<label class="am-radio am-success" style="padding-top:2px;">
				    				<input type="radio" class="radio"  data-am-ucheck value="1" name="data[Invoice][status]" <?php if(isset($this->data['Invoice']['status'])&&$this->data['Invoice']['status'] == 1){echo "checked";} ?>/>已处理</label> 
				    			</div>
				    			</div>
			    			</div>
			    		</div>	
					</div>
				</div>
			</div>
		    </div>
		<?php echo $form->end();?>
	</div>	  
</div>
<script type="text/javascript">
function pages_check(check_flag){
	var invoice_type = document.querySelector('#invoice_type');
	var invoice_number = document.querySelector('#invoice_number');
	var invoice_content = document.querySelector('#invoice_content');
	var invoice_money = document.querySelector('#invoice_money');
	var builling_date = document.querySelector('#builling_date');
	var issuer = document.querySelector('#issuer');
	if(invoice_type.value ==''){
		alert("发票类型不能为空");
		return false;
	}else if(invoice_number.value==""){
		alert("发票号不能为空");
		return false;
	}else{
		var form_flag=false;
		var invoice_id=$("#invoice_id").val();
		$.ajax({  
			url: admin_webroot+"invoices/check",
			type:"post",
			async:false,
			data:{invoice_number:invoice_number.value,invoice_id:invoice_id},
			dataType: 'html',
			success:function(data){
				if(data>0){
					alert('发票号重复');
					form_flag=false;
				}else if(invoice_content.value==""){
					alert("发表内容不能为空");
					form_flag=false;
				}else if(invoice_money.value==""){
					alert("发票金额不能为空");
					form_flag=false;
				}else if(builling_date.value==""){
					alert("开票日期不能为空");
					form_flag=false;
				}else if(issuer.value==""){
					alert("开票人不能为空");
					form_flag=false;
				}else{
					form_flag=true;
				}
			}
		});
		return form_flag;
	}
}
</script>
