<style>.am-table-striped>tbody>tr:nth-child(odd)>th{background-color:white;}
.am-table-striped>tbody>tr:nth-child(odd)>td, .am-table-striped>tbody>tr:nth-child(odd)>th{background-color:white;}</style>
<div style="text-align:left;font-size:20px;border-bottom:1px solid #ccc;padding-bottom:1rem;margin-top:1%;padding-left:5px;margin-bottom:1rem;" >
	<span style="float:left;"><?php echo $ld['enquiry']; ?></span>
	<div class="am-cf"></div>
</div>
<div class="am-u-ser-enquiry">
<?php if(isset($enquiries_list)&&sizeof($enquiries_list)>0){ ?>
  <table class="am-table am-table-bd am-table-striped am-table-hover">
	<tr class="" style="height:60px">
	  <th style="border-top: 0px;" width="36%"><?php echo $ld['product_name']?></th>
	  <th style="border-top: 0px;" width="20%" class="am-hide-sm-only" style="width:10px"><?php echo $ld['attribute']?></th>
	  <th style="border-top: 0px;" width="10%"><?php echo $ld['price']?></th>
	  <th style="border-top: 0px;" width="8%"><?php echo $ld['quantity']?></th>
	  <th style="border-top: 0px;" width="10%"><?php echo $ld['status']?></th>
	  <th style="border-top: 0px;" width="20%" class="am-hide-sm-only"><?php echo $ld['submit_time']?></th>
	</tr>
	<?php foreach($enquiries_list as $k=>$v){ ?>
	<tr>
	  <td style="padding:10px 10px;">
		<?php 
			$sku_code=$v['Enquiry']['part_num']; 
			$sku_code_arr=split(';',$v['Enquiry']['part_num']);
			if(sizeof($sku_code_arr)>1){
				foreach($sku_code_arr as $kk=>$vv){
					echo isset($product_code_list[$vv])?"<a href='".$html->url('/products/'.$product_id_list[$vv])."' target='_blank'>".$product_code_list[$vv]."</a><br>":'&nbsp;&nbsp;';
				}
			}else{
				echo isset($product_code_list[$sku_code])?"<a href='".$html->url('/products/'.$product_id_list[$sku_code])."' target='_blank'>".$product_code_list[$sku_code]."</a>":'&nbsp;&nbsp;';
			}
		?>
	  </td>
	  <td  class="am-hide-sm-only">
		<?php 
			$attribute=$v['Enquiry']['attribute']; 
			$attribute_arr=split(';',$v['Enquiry']['attribute']);
			if(sizeof($attribute_arr)>1){
				foreach($attribute_arr as $kk=>$vv){
					echo isset($vv)&&!empty($vv)?$vv."<br>":'&nbsp;&nbsp;<br>';
				}
			}else{
				echo $v['Enquiry']['attribute'];
			}
		?>
		<?php //echo $v['Enquiry']['attribute'];?>
	  </td>
	  <td>
		<?php 
			$price=$v['Enquiry']['target_price']; 
			$price_arr=split(';',$v['Enquiry']['target_price']);
			if(sizeof($price_arr)>1){
				foreach($price_arr as $kk=>$vv){
					echo isset($vv)&&!empty($vv)?$vv."<br>":'&nbsp;&nbsp;<br>';
				}
			}else{
				echo $v['Enquiry']['target_price'];
			}
		?>
	  </td>
	  <td>
		<?php 
			$qty=$v['Enquiry']['qty']; 
			$qty_arr=split(';',$v['Enquiry']['qty']);
			if(sizeof($qty_arr)>1){
				foreach($qty_arr as $kk=>$vv){
					echo isset($vv)&&!empty($vv)?$vv."<br>":'&nbsp;&nbsp;<br>';
				}
			}else{
				echo $v['Enquiry']['qty'];
			}
		?>
	  </td>
	  <td><?php
	    	switch($v['Enquiry']['status']){
			  case 0:
			    echo $ld['unrecognized'];
				break;
			  case 1:
				echo $ld['confirmed'];
				break;
			  case 2:
				echo $ld['canceled'];
				break;
			  case 3:
				echo $ld['complete'];
				break;
			} 
			?>
      </td>
	  <td  class="am-hide-sm-only"><?php echo $v['Enquiry']['created']; ?></td>
	</tr>
	<?php }?>
  </table>
  <?php echo $this->element('pager'); ?>
  <?php }else{?>
	<div style="text-align:center;"><span><?php echo $ld['no_record'];?></span></div>
  <?php }?>
</div>
