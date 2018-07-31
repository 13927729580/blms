<style>
	.quote_title{
		color:#0e90d2;
		padding-left: 10px;
	}
	.quote_add{
		background-color:#1E3867;
		color:#fff;
	}
	.quote_add:hover{
		color:#fff;
	}
	.quote_list_title{
		border-bottom: 1px solid #ddd;
		padding:10px 15px;
		
	}
	.quote_list_title div{
			font-weight: 600;
		}

	.quote_list_product{
		border-bottom: 1px solid #ddd;
		padding:10px 15px 10px 30px;

	}
	.quote_list_product div{
		font-weight:400;
		color:#555;
		font-size: 14px;
	}
	.quote_product_list{
		border-bottom:1px solid #ddd;
		padding:8px 15px 8px 30px;
	}
	.quote_list_body{
		border-bottom:1px solid #ddd;
		padding:8px 15px;
	}

</style>
<div class="quote-list">
	<!-- <div class="am-cf" style="border-bottom:1px solid #ddd;padding:30px 0px 10px 0px;">
		<div class="am-u-sm-6"><h2 class="quote_title" style="font-size:1.6rem;font-weight:600;"><?php echo $ld['my_offer'] ?></h2></div>	
	</div> -->
	<div style="text-align:left;font-size:20px;border-bottom:1px solid #ccc;padding-bottom:1rem;margin-top:1%;padding-left:5px;margin-bottom:1rem;" >
		<span style="float:left;"><?php echo $ld['my_offer'] ?></span>
		<div class="am-cf"></div>
	</div>
	<div class="am-cf quote_list_title am-g-collapse">
		<div class="am-u-sm-2"><?php echo $ld['quote_number'] ?></div>
		<div class="am-u-sm-3"><?php echo $ld['quote_person'] ?></div>
		<div class="am-u-sm-3"><?php echo $ld['quote_time'] ?></div>
		<div class="am-u-sm-2" style="float:right"><?php echo $ld['operation'] ?></div>
	</div>
	<span class="none_quote" style="text-align: center;width: 100%;display: inline-block; margin-top: 10px;">暂无报价</span>
<?php if (isset($quote_data)&&sizeof($quote_data)>0) {foreach ($quote_data as $k => $v) { ?>
	<div class="am-cf quote_list_body am-g-collapse">
		<div class="am-u-sm-2"><?php echo $v['Quote']['id'] ?></div>
		<div class="am-u-sm-3"><?php echo $v['Quote']['quoted_by'] ?>&nbsp;</div>
		<div class="am-u-sm-3"><?php if(isset($v['Quote']['inquire_date'])){echo date('Y-m-d',strtotime($v['Quote']['inquire_date']));} ?>&nbsp;</div>
		<div class="am-u-sm-2" style="float:right;">
			<a class="am-btn am-btn-default am-btn-xs am-text-secondary" href="<?php echo $html->url('/quotes/view/'.$v['Quote']['id']) ; ?>" ><span class="am-icon-eye"></span>&nbsp;<?php echo $ld['view'] ?></a>
		</div>
	</div>

<?php if (isset($v['QuoteProduct'])&&sizeof($v['QuoteProduct'])>0 ) { ?>
	<div class="am-cf quote_list_product am-g-collapse">
		<div class="am-u-sm-3"><?php echo $ld['product_code'] ?></div>
		<div class="am-u-sm-2"><?php echo $ld['brand'] ?></div>
		<div class="am-u-sm-3"><?php echo $ld['provide_number'] ?></div>
		<div class="am-u-sm-2"><?php echo $ld['qty_req'] ?></div>
		<div class="am-u-sm-2"><?php echo $ld['quote'] ?></div>
	</div>

<?php foreach ($v['QuoteProduct'] as $kk => $vv) { ?>
	<div class="am-cf quote_product_list am-g-collapse">
		<div class="am-u-sm-3"><?php echo $vv['product_code'] ?>&nbsp;</div>
		<div class="am-u-sm-2"><?php echo $vv['brand_code'] ?>&nbsp;</div>
		<div class="am-u-sm-3"><?php echo $vv['qty_offered'] ?>&nbsp;</div>
		<div class="am-u-sm-2"><?php echo $vv['qty_requested'] ?>&nbsp;</div>
		<div class="am-u-sm-2"><?php echo $vv['offered_price'] ?>&nbsp;</div>
	</div>
	<?php }} ?>	
<?php }} ?>
</div>
<div class="pagenum"><?php echo $this->element('pager');?></div>


<script>
var pagenum = document.querySelector('.pagenum');
var none_quote = document.querySelector('.none_quote');
var quote_list_title = document.querySelector(".quote_list_title");
console.log(pagenum);
if(pagenum.innerText == ''){
	none_quote.style.display="inline-block";
	quote_list_title.style.display="none";
}else{
	none_quote.style.display="none";
	quote_list_title.style.display="";
}
</script>