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
.am-form-label{font-weight:bold;}
.btnouter{margin:50px;}
.am-radio input[type="radio"]{margin-left:0px;}
</style>
<div>
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
		   	<li><a href="#new_invoice_type"><?php echo $ld['new_invoice_type']?></a></li>
		</ul>
	</div>
	<div class="am-panel-group admin-content" id="accordion" style="width:83%;float:right;">
		<?php echo $form->create('invoice_types',array('action'=>'/add/','name'=>"theForm","onsubmit"=>"return InvoiceType_checks();","enctype"=>"multipart/form-data"));?>
			<div id="new_invoice_type"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['new_invoice_type']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						<div class="am-form-group">
			    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:18px;"><?php echo $ld['invoice_type_name']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
			    					<input type="text" name="data[InvoiceTypeI18n][<?php echo $k;?>][name]" id="InvoiceType_name" />
			    				</div>
				    			<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:20px;">
			    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;<em style="color:red;">*</em>
			    					</label>
				    			<?php }?>
			    			<?php }} ?>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:18px;"><?php echo $ld['invoice_type_description']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
			    					<input type="text" name="data[InvoiceTypeI18n][<?php echo $k;?>][direction]" />
			    					<input type="hidden" name="data[InvoiceTypeI18n][<?php echo $k;?>][locale]" value="<?php echo $v['Language']['locale']?>" />
			    				</div>
				    			<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:20px;">
			    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;<em style="color:red;">*</em>
			    					</label>
				    			<?php }?>
			    			<?php }} ?>
			    			</div>
			    		</div>	
			    		<input type="hidden" name="data[InvoiceType][id]" />
						<div class="am-form-group">
			    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:18px;"><?php echo $ld['invoice_tax_point']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[InvoiceType][tax_point]" id="InvoiceType_tax_point" />
			    				</div>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['valid']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<label class="am-radio am-success">
			    						<input type="radio" name="data[InvoiceType][status]" data-am-ucheck  value="1" checked />
			    						<?php echo $ld['yes']?>
			    					</label>&nbsp;&nbsp;
									<label class="am-radio am-success">
										<input type="radio" name="data[InvoiceType][status]" data-am-ucheck  value="0" />
										<?php echo $ld['no']?>
									</label>
			    				</div>
			    			</div>
			    		</div>	
						<div class="btnouter">
							<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
							<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
						</div>
					</div>
				</div>
			</div>
		<?php echo $form->end();?>
	</div>
</div>
<script type="text/javascript">
//用户设置管理
function InvoiceType_checks(){
	var InvoiceType_name = GetId("InvoiceType_name");
	//alert(InvoiceType_name);
	var InvoiceType_tax_point = GetId("InvoiceType_tax_point");
	if( Trim(InvoiceType_name.value,'g') == "" ){
		layer_dialog();
		layer_dialog_show("<?php echo $ld['invoice_type_name_can_not_empty']?>","",3);
		return false;
	}
	if( Trim(InvoiceType_tax_point.value,'g') == "" ){
		layer_dialog();
		layer_dialog_show("<?php echo $ld['invoice_tax_point_can_not_empty']?>","",3);
		return false;
	}
}


</script>
