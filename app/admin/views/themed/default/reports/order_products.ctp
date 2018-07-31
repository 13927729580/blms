<div class="am-g">
	<?php echo $form->create('reports',array('action'=>'/order_products','type'=>'get','class'=>'am-form am-form-horizontal'));?>
	<div>
		<ul class="am-avg-lg-2 am-avg-md-1 am-avg-sm-1">
			<li>
				<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label am-text-center" style="font-weight:bold;">购买时间</label>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="padding-right:0;">
					<div class="am-input-group">
						<input type="text" name="order_date_start" value="<?php echo isset($order_date_start)?$order_date_start:''; ?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
						<span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;"><i class="am-icon-remove"></i></span>
					</div>
				</div>
				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center" style="padding-top:7px;"><em>-</em></label>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="padding-left:0;">
					<div class="am-input-group">
						<input type="text" name="order_date_end" value="<?php echo isset($order_date_end)?$order_date_end:''; ?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
						<span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;"><i class="am-icon-remove"></i></span>
					</div>
				</div>
			</li>
			<li>
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-center" style="font-weight:bold;"><?php echo $ld['keyword'] ?></label>
				<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
					<input type='text' name="keyword" value="<?php echo isset($keyword)?$keyword:''; ?>"  />
				</div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
					<input type="submit" class="btn am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>" />
				</div>
			</li>
		</ul>
	</div>
	<?php echo $form->end();?>
	<table class="am-table am-table-bordered am-margin-top-lg">
		<thead>
		        <tr>
				<th>商品名称</th>
				<th class="am-text-center">购买总数</th>
				<th class="am-text-center">总计</th>
				<th class="am-text-center"><?php echo $ld['operate'] ?></th>
		        </tr>
		</thead>
		<tbody>
			<?php if(isset($order_product_lists)&&sizeof($order_product_lists)>0){foreach($order_product_lists as $v){ ?>
			<tr>
				<td><?php echo isset($item_type_infos[$v['OrderProduct']['item_type']][$v['OrderProduct']['product_id']])?$item_type_infos[$v['OrderProduct']['item_type']][$v['OrderProduct']['product_id']]:'-'; ?></td>
				<td class="am-text-center"><?php echo $v[0]['buy_total']; ?></td>
				<td class="am-text-center"><?php echo $v[0]['sub_total']; ?></td>
				<td class="am-text-center"><?php echo $html->link($ld['view'],'/order_products/index?item_type='.$v['OrderProduct']['item_type'].'&item_type_id='.$v['OrderProduct']['product_id'],array('class'=>'am-btn am-btn-xs am-btn-success am-radius')); ?></td>
		        </tr>
			<?php }} ?>
		</tbody>
	</table>
	<?php if(isset($order_product_lists)&&sizeof($order_product_lists)>0)echo $this->element('pagers')?>
</div>