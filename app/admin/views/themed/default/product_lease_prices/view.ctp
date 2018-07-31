<style type="text/css" >
		.am-form-group{margin-top:20px ;} 
</style>
		<div style="margin-top:10px;"  id="product_lease_price">
    			 <?php echo $form->create('',array('action'=>'','name'=>'product_lease_priceform'));?>
    			 
		
      		<div class="am-tab-panel am-fade am-active am-in am-form-detail am-form am-form-horizontal" >
		      		
				<input type="hidden" value="<?php echo isset($product_lease_price_info['ProductLeasePrice']['id'])?$product_lease_price_info['ProductLeasePrice']['id']:'0'; ?>" name="data[ProductLeasePrice][id]" >
				 <div class="am-form-group">
						      <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo  $ld['start_price'];   ?></label>
						      <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
						     		<input type="text"  value="<?php echo $product_lease_price_info['ProductLeasePrice']['price'] ?>" class="am-form-field" name="data[ProductLeasePrice][price]" onkeydown="if(event.keyCode==13){return false;}">
						      </div>
				  </div>
				  					  
				  <div class="am-form-group">
						      <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" ><?php echo $ld['lease_price_percent']; ?></label>
						      <div class="am-u-lg-5 am-u-md-5 am-u-sm-5 ">
						     		<input type="text"  value="<?php echo $product_lease_price_info['ProductLeasePrice']['lease_price_percent'] ?>" class="am-form-field" name="data[ProductLeasePrice][lease_price_percent]" onkeydown="if(event.keyCode==13){return false;}">					      	
						      </div>
				    </div>
				    	<div class="am-form-group">
						      <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="margin-top:2px;"><?php echo $ld['lease_deposit_base']; ?></label>
						      <div class="am-u-lg-5 am-u-md-5 am-u-sm-5 ">
						        		<input type="text"  value="<?php echo $product_lease_price_info['ProductLeasePrice']['lease_deposit_base'] ?>" class="am-form-field" name="data[ProductLeasePrice][lease_deposit_base]" onkeydown="if(event.keyCode==13){return false;}">					      	

						      </div>
				    </div>	
    					<div class="am-form-group">
						      <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" > <?php echo $ld['lease_deposit_increase_percent']; ?></label>
						      <div class="am-u-lg-5 am-u-md-5 am-u-sm-5 ">
						        	
						      	<input type="text"  value="<?php echo $product_lease_price_info['ProductLeasePrice']['lease_deposit_increase_percent'] ?>" class="am-form-field" name="data[ProductLeasePrice][lease_deposit_increase_percent]" onkeydown="if(event.keyCode==13){return false;}">
						      </div>
				    </div>
    							
				    <div class="am-form-group">
						      <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" ><?php echo $ld['lease_deposit_unit']; ?></label>
						      <div class="am-u-lg-5 am-u-md-5 am-u-sm-5 ">
								<input type="text"  value="<?php echo $product_lease_price_info['ProductLeasePrice']['lease_deposit_unit'] ?>" class="am-form-field" name="data[ProductLeasePrice][lease_deposit_unit]" onkeydown="if(event.keyCode==13){return false;}">

						      </div>
				    </div>
				    
				    
    
					    				
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
					
					<div class="am-u-lg-5 am-u-md-5 am-u-sm-5"><button type="button"   class="am-btn am-btn-success am-btn-sm am-radius" onclick="saveajax()"  ><?php echo $ld['save'];?></button>
						<button type="button" class="am-btn am-btn-default am-btn-sm am-radius" value="" onclick="clearaddr()"><?php echo $ld['cancel']?></button>　　　　　 </div>
				</div>
      		</div>			
      		<?php echo $form->end(); ?>						
   </div>
	
	<script type="text/javascript">
    function loadindex(){
				
						window.location.href=admin_webroot+"product_lease_prices/index";
}
    
    
	function saveajax(){
	var id=document.getElementsByName("data[ProductLeasePrice][id]")[0].value;
	var prices=document.getElementsByName("data[ProductLeasePrice][price]")[0].value;
	var price=prices.replace( /^\s*/, '');
	var lease_deposit_bases=document.getElementsByName("data[ProductLeasePrice][lease_deposit_base]")[0].value;
	var lease_deposit_base=lease_deposit_bases.replace( /^\s*/, '');
	var lease_deposit_increase_percents=document.getElementsByName("data[ProductLeasePrice][lease_deposit_increase_percent]")[0].value;
	var lease_deposit_increase_percent=lease_deposit_increase_percents.replace( /^\s*/, '');
	var lease_price_percents=document.getElementsByName("data[ProductLeasePrice][lease_price_percent]")[0].value;
	var lease_price_percent=lease_price_percents.replace( /^\s*/, '');
	var lease_deposit_units=document.getElementsByName("data[ProductLeasePrice][lease_deposit_unit]")[0].value;
	var lease_deposit_unit=lease_deposit_units.replace( /^\s*/, '');

	 if(price=="" ){
		alert("请填写价格");
		return false;
	}else if(lease_deposit_base == ""){
		alert("请填写基本保证金");return false;
	}else if(lease_deposit_unit == ""){
		alert("请选择保证金单位");return false;
	}else if(lease_deposit_increase_percent == ""){
		alert("请选择增长百分比");return false;			
	}else if(lease_price_percent == ""){
		alert("请填写增长百分比");return false;
	}else{
		$.ajax({ url: admin_webroot+"product_lease_prices/view/0/",
			type:"POST",
			data:{
					'data[ProductLeasePrice][id]':id,
					'data[ProductLeasePrice][price]':price,		
					'data[ProductLeasePrice][lease_deposit_base]':lease_deposit_base,
					'data[ProductLeasePrice][lease_deposit_unit]':lease_deposit_unit,
					'data[ProductLeasePrice][lease_deposit_increase_percent]':lease_deposit_increase_percent,
					'data[ProductLeasePrice][lease_price_percent]':lease_price_percent,
				},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loadindex();
					//$("#addredittables").modal('close');
				}else{
					alert("失败");
				}
	  		}
	  	});
	  	return false;
  	}
}

function clearaddr(){
//loadindex();
$("#addredittables").modal('close');

}
</script>