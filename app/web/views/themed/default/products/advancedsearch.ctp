<?php 
// pr($category_array);
// pr($price_eye);
// pr($brand_names);
// pr($brand);
 //pr($categories);
// pr($keyword);
 //pr($product_attributes);
//pr($pro);
//pr($pubile_attr_info);
//pr($pages_list);
//pr($category_infos);
//pr($category_infos['tree']);
//$html->url();
 ?>
<div class="am-g" >
	<div class="am-u-lg-2 am-u-md-2" style="">
		<?php foreach ($category_infos['tree'] as $k1 => $v1)  {?>
			<div style="padding-top:0.5rem;padding-bottom:0.5rem;border-bottom:1px solid #ddd;cursor:pointer;"><a href="<?php echo $html->url('/products/advancedsearch/?category_id='.$k1) ?>"><?php echo $v1['CategoryProductI18n']['name'] ?></a></div>
		<?php } ?>
	</div>
	<div class="am-u-lg-10 am-u-md-10" style="padding-left:0;padding-right:0;">
	<div style="margin-bottom:1rem;">
		<div class="am-u-lg-6" style="padding-left:0;">
			<button onclick="clickrfq()" class="am-btn am-btn-warning am-radius am-btn-sm">批量查询</button>
		</div>
		<div class="am-u-lg-6 am-text-right" style="padding-right:0;">
			<form action="<?php echo $html->url('/products/advancedsearch/') ?>" method="post">
				<input type="hidden" value="1" name="flag">
				<input type="submit" value="<?php echo $ld['export'] ?>" class="am-btn am-btn-warning am-radius am-btn-sm">
			</form>
		</div>
		<div class="am-cf"></div>
	</div>
	<table class="am-table am-table-bordered">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th class="am-text-center"><?php echo $ld['product_name'] ?></th>
				<th class="am-text-center"><?php echo $ld['sku'] ?></th>
				<th class="am-text-center"><?php echo $ld['brand'] ?></th>
				<th class="am-text-center"><?php echo $ld['quantity'] ?></th>
				<?php foreach ($pubile_attr_info as $key => $value) { ?>
					<th><?php echo $value['AttributeI18n']['name'] ?></th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			
				<?php foreach ($pro as $k => $v) {?>
			<tr>
				<td style="width:4%;"><label class="am-checkbox" style="margin-left:12px;">
					<input type="checkbox" data-am-ucheck class="order-check" value="<?php echo $v['Product']['id'] ?>">
				    </label>
				</td>
				<td><?php echo $v['ProductI18n']['name'] ?>&nbsp;</td>
				<td><a href="<?php echo 
				$html->url('/enquiries?product_id='.$v['Product']['id']) ?>"><?php echo $v['Product']['code'] ?></a>&nbsp;</td>
				<td>
					<?php echo isset($brand_names[$v['Product']['brand_id']])?$brand_names[$v['Product']['brand_id']]:''; ?>
					&nbsp;</td>
				<td><?php echo $v['Product']['quantity'] ?>&nbsp;</td>
				<?php foreach ($pubile_attr_info as $k1 => $v1) { ?>
					<td>
						<?php 
						echo isset($product_attributes[$v['Product']['id']][$v1['Attribute']['id']])? $product_attributes[$v['Product']['id']][$v1['Attribute']['id']]:'';
						?>
					</td>
				<?php } ?>
			</tr>
		<?php } ?>

		</tbody>
	</table>
	
	</div>
		<!-- 商品列表分页 -->
					<?php if($pages_list['pageCount']>=1){?>
					<div class="pages" style="border:none;">
						<?php
						if($pagination->setPaging($pages_list)):
							$leftArrow = " < ".$ld['previous'];
							$rightArrow = $ld['next']." >";
							$prev = $pagination->prevPage($leftArrow,false);
							$prev = $prev?$prev:$leftArrow;
							$next = $pagination->nextPage($rightArrow,false);
							$next = $next?$next:$rightArrow;
							$pages = $pagination->pageNumbers("	 ");
							echo $prev." ".$pages." ".$next;
						endif;
						?>
					</div>
					<?php }?>
					<!-- 商品列表分页 end  -->
					<!-- 导出功能 -->
					
</div>
<script>
	function clickrfq(){
		var add = [];
		$(".order-check").each(function(){
			if($(this).is( ":checked" )){
				add.push($(this).val());
			}
		});
		var add1 = add.join(',');
		window.location.href = "<?php echo 
				$html->url('/enquiries?product_id=') ?>"+add1;
		
	}
</script>