<?php
	//pr($quote_data);
?>
<style>
	.quotes_view_title{
		color:#0e90d2;
		padding-left: 10px;
	}
	.quotes_title_line{
		line-height: 2.5;
		border-bottom: 1px solid #ddd;
		font-weight: 600;
	}
</style>
<div>
	<div class="am-cf" style="border-bottom:1px solid #ddd;padding:30px 0px 10px 0px;">
		<h2 class="quotes_view_title"><?php echo $ld['quotation_details'] ?></h2>
	</div>
		<div class="am-cf quotes_title_line">
			<div class="am-u-sm-2"><?php echo $ld['sku'] ?></div>
			<div class="am-u-sm-2"><?php echo $ld['brand'] ?></div>
			<div class="am-u-sm-2"><?php echo $ld['attribute'] ?></div>
			<div class="am-u-sm-2"><?php echo $ld['provide_quantity'] ?></div>
			<div class="am-u-sm-2"><?php echo $ld['quote'] ?></div>
			<div class="am-u-sm-2"><?php echo $ld['target_price'] ?></div>
		</div>

<?php if(isset($quote_data['QuoteProduct'])&&sizeof($quote_data['QuoteProduct'])>0){foreach ($quote_data['QuoteProduct'] as $k => $v) { ?>
		<div class="am-g" style="line-height:2;">
			<div class="am-u-sm-2"><?php echo $v['product_code'] ?>&nbsp;</div>
			<div class="am-u-sm-2"><?php echo $v['brand_code'] ?>&nbsp;</div>
			<div class="am-u-sm-2"><?php echo $v['data_code'] ?>&nbsp;</div>
			<div class="am-u-sm-2"><?php echo $v['qty_offered'] ?>&nbsp;</div>
			<div class="am-u-sm-2"><?php echo $v['offered_price'] ?>&nbsp;</div>
			<div class="am-u-sm-2"><?php echo $v['target_price'] ?>&nbsp;</div>
		</div>
<?php }} ?>
		<div class="am-g am-margin-top-lg">
			<div class="am-g" style="margin-top:5px;">
		  		<label class="am-u-lg-3 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['quoted_by'] ?></label>
		  	<div class="am-u-lg-8 am-u-md-7 am-u-sm-7">
		  		<?php echo $quote_data['Quote']['quoted_by'] ?>&nbsp;
		  	</div>
			</div>
			<div class="am-g" style="margin-top:5px;">
		  		<label class="am-u-lg-3 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['email'] ?></label>
		  	<div class="am-u-lg-8 am-u-md-7 am-u-sm-7">
		  		<?php echo $quote_data['Quote']['email'] ?>&nbsp;
		  	</div>
			</div>
			<div class="am-g" style="margin-top:5px;">
		  		<label class="am-u-lg-3 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['email_title'] ?></label>
		  	<div class="am-u-lg-8 am-u-md-7 am-u-sm-7">
		  		<?php echo $quote_data['Quote']['mail_title'] ?>&nbsp;
		  	</div>
			</div>
			<div class="am-g" style="margin-top:5px;">
		  		<label class="am-u-lg-3 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['contact'] ?></label>
		  	<div class="am-u-lg-8 am-u-md-7 am-u-sm-7">
		  		<?php echo $quote_data['Quote']['contact_person'] ?>&nbsp;
		  	</div>
			</div>
			<div class="am-g" style="margin-top:5px;">
		  		<label class="am-u-lg-3 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['inquire_date'] ?></label>
		  	<div class="am-u-lg-8 am-u-md-7 am-u-sm-7">
		  		<?php echo $quote_data['Quote']['inquire_date'] ?>&nbsp;
		  	</div>
			</div>
			<div class="am-g" style="margin-top:5px;">
		  		<label class="am-u-lg-3 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['help'] ?></label>
		  	<div class="am-u-lg-8 am-u-md-7 am-u-sm-7">
		  		<?php echo $quote_data['Quote']['remark'] ?>&nbsp;
		  	</div>
			</div>
		</div>	
</div>