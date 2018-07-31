<style type="text/css">
	.am-radio, .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
	.am-checkbox input[type="checkbox"]{margin-left:0px;}
	.am-yes{color:#5eb95e;}
	.am-no{color:#dd514c;}
	.am-panel-title div{font-weight:bold;}
	.am-form-group{margin:20px 0;} 
	label{font-weight:normal;}
</style>

<div>


		<div class="am-text-right am-btn-group-xs" style="margin-right:10px;margin-bottom:10px;">
			<button type="button" class="am-btn am-btn-warning am-btn-sm am-radius" data-am-modal="{target: '#addredittables', closeViaDimmer: 1, width: 900}" onclick="edit_product_lease_prices('0');">
				<span class="am-icon-plus"></span><?php echo $ld['add'] ?>
			</button>
		</div>
		<form name="Product_Lease_Prices_Form" onsubmit="return false;" id="Product_Lease_Prices_Form" method="get" action="<?php echo $admin_webroot; ?>product_lease_prices" accept-charset="utf-8">
		<div class="am-panel-group am-panel-tree">
			<div class=" listtable_div_btm am-panel-header">
				<div class="am-panel-hd">
					<div class="am-panel-title am-g">
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-hide-sm-only">
							<label class="am-checkbox am-success" style="font-weight:bold;">
								<input type="checkbox" onclick="listTable.selectAll(this,&quot;checkbox[]&quot;)" data-am-ucheck/>
								<?php echo $ld['start_price']; ?>
							</label>
						</div>
<!-- 表头 -->
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-show-sm-only">
							<label   style="font-weight:bold;">
								  <?php echo  $ld['start_price'];   ?>
							</label>
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['lease_price_percent']; ?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['lease_deposit_base']; ?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['lease_deposit_increase_percent']; ?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['lease_deposit_unit']; ?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['operate']?></div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php if(isset($product_lease_price_infos) && sizeof($product_lease_price_infos)>0){foreach($product_lease_price_infos as $k=>$v){?>
			<div>
				<div class="am-panel-body ">
				
					<div class="am-panel-bd listtable_div_top">
						<!-- 价格 -->
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-hide-sm-only" style="padding-top:3px;">
							<label class="am-checkbox am-success" >
								<input type="checkbox" name="checkbox[]" value="<?php echo $v['ProductLeasePrice']['id']?>"  data-am-ucheck />
							<?php echo $v['ProductLeasePrice']['price'];?>
							</label>
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-show-sm-only" style="padding-top:3px;" >
							<label>
								<?php echo $v['ProductLeasePrice']['price'];?>
							</label>
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 " style="padding-top:3px;">
							<label>
							 
								<?php echo $v['ProductLeasePrice']['lease_price_percent'];?>
							</label>
						</div>
							<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 " style="padding-top:3px;" >
							<label>
							 
								<?php echo $v['ProductLeasePrice']['lease_deposit_base'];?>
							</label>
						</div>
							<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 " style="padding-top:3px;">
							<label>
							 
								<?php echo $v['ProductLeasePrice']['lease_deposit_increase_percent'];?>
							</label>
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="padding-top:3px;">
							<label>
								<?php echo $v['ProductLeasePrice']['lease_deposit_unit']; ?>
							</label>
						</div>
					
	
						<!-- 操作 -->
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-btn-group-xs am-action" style="padding-top:3px;">
							 <button type="button" id="product_lease_prices_btn" class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" data-am-modal="{target: '#addredittables', closeViaDimmer: 1, width: 900}" onclick="edit_product_lease_prices('<?php echo $v['ProductLeasePrice']['id']  ?>')">
								        <span class="am-icon-pencil-square-o"> <?php echo $ld['edit']; ?></span>
							 </button> 
							 
							<button type="button" class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" onclick="del_product_lease_prices('<?php echo $v['ProductLeasePrice']['id'] ?>')" >
						                    	<span class="am-icon-trash-o"> <?php echo $ld['delete']; ?></span>
						      </button>
						    
						   
									
									
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php }}else{?>
				<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
			<?php }?>
					
			</div>
				<div id="btnouterlist" class="btnouterlist am-hide-sm-only" style="padding-left:18px;" >
				  
				 	<label  class="am-checkbox am-success">
						<input type="checkbox" onclick="listTable.selectAll(this,&quot;checkbox[]&quot;)" data-am-ucheck>
						<?php echo $ld['select_all']?>
					</label>&nbsp;&nbsp;
					<input type="button" class="am-btn am-btn-danger am-btn-sm am-radius" onclick="diachange()" value="<?php echo $ld['batch_delete']?>">
				</div>
		
	</form>
		
</div>
	
			<div class="am-modal am-modal-no-btn" tabindex="-1" id="addredittables" style="top:100px">
  								<div class="am-modal-dialog" >
    									<div class="am-modal-hd">
      									<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    									</div>
    									<div class="am-modal-bd" id="product_lease_prices_show">
    
    									</div>

								</div>
							</div>	
	
	

<script type="text/javascript">
function loadindex(){
				
						window.location.href=admin_webroot+"product_lease_prices/index";
}

function edit_product_lease_prices(Id){
	//$("#product_lease_prices_btn").click();
	$.ajax({ url: admin_webroot+"product_lease_prices/view/"+Id,
		type:"POST",
		data:{'Id':Id},
		dataType:"html",
		success: function(data){
	//	$("#addredittables .am-modal-bd").find('#product_lease_price').remove();
	//	$("#addredittables .am-modal-bd").html(data);	
		$("#product_lease_prices_show").html(data);
  		}
  	});
}
function del_product_lease_prices(Id){
	if(confirm("<?php echo $ld['confirm_delete'] ?>")){
		$.ajax({ url: admin_webroot+"product_lease_prices/remove/"+Id,
			type:"POST",
			data:{'Id':Id},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loadindex();
				}else{
					alert("<?php echo $ld['delete_failure'] ?>");
				}
			}
		});
	}
}
function diachange(){
		var id=document.getElementsByName('checkbox[]');
		var i;
		var j=0;
		var image="";
		for( i=0;i<=parseInt(id.length)-1;i++ ){
			if(id[i].checked){
				j++;
			}
		}
		if( j>=1 ){
		//	layer_dialog_show('确定删除?','batch_action()',5);
			if(confirm("<?php echo $ld['confirm_delete']?>"))
			{
				batch_action();
			}
		}else{
		//	layer_dialog_show('请选择！！','batch_action()',3);
			if(confirm(j_please_select))
			{
				return false;
			}
		}
}
function batch_action()
{
	document.Product_Lease_Prices_Form.action=admin_webroot+"product_lease_prices/batch";
	document.Product_Lease_Prices_Form.onsubmit= "";
	document.Product_Lease_Prices_Form.submit();
}

</script>

	
	
	
