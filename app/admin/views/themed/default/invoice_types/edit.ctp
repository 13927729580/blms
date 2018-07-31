<?php 
/*****************************************************************************
 * SV-Cart 发票类型
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
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
 
.btnouter{}
.am-radio input[type="radio"]{margin-left:0px;}
.am-ucheck-icons .am-icon-unchecked{margin-top: 1px;}
.am-ucheck-icons .am-icon-checked{margin-top: 1px;}

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
		<?php echo $form->create('invoice_types',array('action'=>'/edit/'.$invoice_type_data['InvoiceType']['id'],'name'=>"theForm","onsubmit"=>"return InvoiceType_checks()","enctype"=>"multipart/form-data"));?>
			<!-- 导航 -->
			<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
			    <ul>
				   <li><a href="#edit_invoice_type"><?php echo $ld['edit_invoice_type']?></a></li>
				</ul>
			</div>
			<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
			    <button style="margin-right: 0;" type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
                <button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
			</div>
			<!-- 导航结束 -->
			<div id="edit_invoice_type"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['edit_invoice_type']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="margin-top:17px;"><?php echo $ld['invoice_type_name']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
			    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">
			    					<input id="invoicetype_<?php echo $v['Language']['locale']?>" type="text" name="data[InvoiceTypeI18n][<?php echo $k;?>][name]" id="InvoiceType_name" value="<?php echo @$invoice_type_data['InvoiceTypeI18n'][$v['Language']['locale']]['name']?>" />
			    				</div>
				    			<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-1 am-u-md-3 am-u-sm-3 am-form-group-label am-text-left" style="font-weight:normal;">
			    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;<em style="color:red;">*</em>
			    					</label>
				    			<?php }?>
			    			<?php }} ?>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="padding-top:3px;"><?php echo $ld['invoice_type_description']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
			    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">
			    					<input type="hidden" name="data[InvoiceTypeI18n][<?php echo $k;?>][locale]" value="<?php echo $v['Language']['locale']?>" />
			    					<input type="text" name="data[InvoiceTypeI18n][<?php echo $k;?>][direction]" value="<?php echo @$invoice_type_data['InvoiceTypeI18n'][$v['Language']['locale']]['direction']?>" />
			    				</div>
				    			<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-group-label am-text-left" style="font-weight:normal;">
			    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;
			    					</label>
				    			<?php }?>
			    			<?php }} ?>
			    			</div>
			    		</div>	
			    				
			    		<input type="hidden" name="data[InvoiceType][id]" value="<?php echo @$invoice_type_data['InvoiceType']['id']?>" />
			    		<input type="hidden" name="data[InvoiceType][invoice_type_id]" value="<?php echo @$invoice_type_data['InvoiceType']['id']?>"/>
			    			
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="padding-top:5px;"><?php echo $ld['invoice_tax_point']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[InvoiceType][tax_point]" id="InvoiceType_tax_point" value="<?php echo @$invoice_type_data['InvoiceType']['tax_point']?>" />
			    				</div>
			    			</div>
			    		</div>	
			    		
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" style="margin-top:16px;"><?php echo $ld['valid']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<label class="am-radio am-success" style="padding-top:2px;" >
			    						<input type="radio" name="data[InvoiceType][status]" data-am-ucheck   value="1" <?php if($invoice_type_data['InvoiceType']['status']=="1"){echo "checked";}?> />
			    						<?php echo $ld['yes']?>
			    					</label>&nbsp;&nbsp;
									<label class="am-radio am-success" style="padding-top:2px;"> 
										<input type="radio" name="data[InvoiceType][status]" data-am-ucheck   value="0" <?php if($invoice_type_data['InvoiceType']['status']=="0"){echo "checked";}?> />
										<?php echo $ld['no']?>
									</label>
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
function InvoiceType_checks(){

	var pay_name_obj = document.getElementById("invoicetype_"+backend_locale);
	
	if(pay_name_obj.value==""){
		alert("<?php echo $ld['enter_invoice_type_name']?>");
		return false;
	}
	return true;
	
}
</script>
