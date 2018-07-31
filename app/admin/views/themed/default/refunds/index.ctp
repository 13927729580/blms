<style>
.am-radio, .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align:text-top;}
.am-checkbox input[type="checkbox"]{margin-left:0px;}
 
.am-panel-title div{font-weight:bold;}
</style>
<div>
	<?php echo $form->create('Refund',array('type'=>'get','class'=>'am-form'));?>
		<div> 
			<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
			  <li style="margin-bottom:10px;"><!--1 -->	
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['refund_number']?></label>
					<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
						<input type="text" class="am-form-field am-radius"  name="refundid" id="refundid" value="<?php echo @$refundid?>"/>
					</div>
				</li>
				<li style="margin-bottom:10px;"><!--2 -->	
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['order_number']?></label>
					<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
						<input type="text" class="am-form-field am-radius"  name="ordercode" id="ordercode" value="<?php echo @$ordercode?>"/>
					</div>
				</li>
				<li style="margin-bottom:10px;"><!--3 -->	
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['product_name']  ?>
					</label>
					<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
						<input type="text" class="am-form-field am-radius" placeholder="<?php echo $ld['return'].$ld['product'].$ld['name']?>" name="productname" id="productname" value="<?php echo @$productname?>"/>
					</div>
				</li>
				<li style="margin-bottom:10px;"><!--4 -->	
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:8px;"><?php echo $ld['type']; ?></label>
					<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
						<select name="refund_type" id="refund_type" data-am-selected="{ }">
		    				<option selected="" value="-1"><?php echo $ld['all_data']?></option>
		    				<option value="0" <?php if (isset($refund_type)&&$refund_type == 0){?>selected<?php }?>>退款</option>
		    				<option value="1" <?php if (isset($refund_type)&&$refund_type == 1){?>selected<?php }?>>退货</option>
			    		</select>
					</div>
				</li>
				<li style="margin-bottom:4px;"><!--5-->	
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:8px;"><?php echo $ld['status']; ?></label>
					<div class="am-u-lg-7 am-u-md-7 am-u-sm-7" >
						<select name="status" id="status" data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?>',maxHeight:200}">
							<option selected value="-1"><?php echo $ld['all_data']?></option>
							<option value="WAIT_SELLER_AGREE" <?php if (isset($status)&&$status == 'WAIT_SELLER_AGREE'){?>selected<?php }?>>买家已经申请退款，等待卖家同意</option>
							<option value="WAIT_BUYER_RETURN_GOODS" <?php if (isset($status)&&$status == 'WAIT_BUYER_RETURN_GOODS'){?>selected<?php }?>>卖家已经同意退款，等待买家退货</option>
							<option value="WAIT_SELLER_CONFIRM_GOODS" <?php if (isset($status)&&$status == 'WAIT_SELLER_CONFIRM_GOODS'){?>selected<?php }?>>买家已经退货，等待卖家确认收货</option>
							<option value="SELLER_REFUSE_BUYER" <?php if (isset($status)&&$status == 'SELLER_REFUSE_BUYER'){?>selected<?php }?>>卖家拒绝退款</option>
							<option value="CLOSED" <?php if (isset($status)&&$status == 'CLOSED'){?>selected<?php }?>>退款关闭</option>
							<option value="SUCCESS" <?php if (isset($status)&&$status == 'SUCCESS'){?>selected<?php }?>>退款成功</option>
							<option value="BUYER_NOT_ASK" <?php if (isset($status)&&$status == 'BUYER_NOT_ASK'){?>selected<?php }?>>没有申请退款</option>
							<option value="SELLER_REFUSE_RETURN" <?php if (isset($status)&&$status == 'SELLER_REFUSE_RETURN'){?>selected<?php }?>>卖家拒绝确认收货</option>
							<option value="WAIT_SELLER_REFUND" <?php if (isset($status)&&$status == 'WAIT_SELLER_REFUND'){?>selected<?php }?>>同意退款，待打款</option>
						</select>
					</div>
				</li>
				<li style="margin-bottom:4px;"><!--7 -->	
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:8px;">更新时间</label>
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="padding-right:0;width:36%;">
						<div class="am-input-group">
						<input type="text" class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" name="start_date" value="<?php echo @$start_date;?>" />
						<span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                	<i class="am-icon-remove"></i>
              		</span>
					</div>
					</label><label class="label_calendar">

					</div>
					<label class="am-fl am-u-sm-1 am-text-center" style="padding-top:6px;width:4%;padding-left:0.5rem;padding-right:0.5rem;" >-</label>
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-u-end" style="padding-left:0;padding-right:0;width:32%;">
						<div class="am-input-group">
						<input type="text" class="am-form-field" readonly data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" name="end_date" value="<?php echo @$end_date;?>" />
						 <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
          </div>
					</div>
				
				</li>
			        <li style="margin-bottom:4px;"><label class="am-u-lg- am-u-md-3 am-u-sm-4  am-form-label"> </label>
					<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
				 	<input type="submit" class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search']?>" />
					</div>
				 
				</li>	
				</ul>			
		</div>
	<?php echo $form->end();?>
	<br/><br/>					
	<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;">
		<?php if( isset($profile_id) && !empty($profile_id) ){ ?>
		<a class="am-btn am-btn-xs am-btn-default" href="<?php echo $html->url('/refunds/refund_upload'); ?>"><?php echo $ld['bulk_upload']?></a>
		<?php } ?>
		<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('add/'); ?>">
			<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
		</a> 
	</div>
		
	<?php echo $form->create('Refund',array('action'=>'/','name'=>'RefundsForm','type'=>'get',"onsubmit"=>"return false;"));?>
	<div class="am-panel-group am-panel-tree">
		<div class="listtable_div_btm am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title">
					<div class="am-u-lg-2 am-show-lg-only" >
						<label class="am-checkbox am-success" style="font-weight:bold;">
						 
			 	                  <input onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)" type="checkbox" data-am-ucheck>
			                  	退款来源-<?php echo $ld['order_number']?>
						</label>&nbsp;&nbsp;
					</div>
					<div class="am-u-lg-2 am-u-md-5 am-u-sm-4"><?php echo $ld['refund_number']?>-<?php echo $ld['return'].$ld['status'];?></div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-4"><?php echo $ld['return'].$ld['type'];?></div>
					<div class="am-u-lg-2 am-show-lg-only"><?php echo $ld['return'].$ld['product'].$ld['name'];?></div>
					<div class="am-u-lg-1 am-show-lg-only">退款金额</div>
					<div class="am-u-lg-2 am-show-lg-only">更新时间</div>
					<div class="am-u-lg-1 am-u-md-4 am-u-sm-4"><?php echo $ld['operate']?></div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<?php if(isset($Refundslist)&&sizeof($Refundslist)){foreach($Refundslist as $k => $v){?>
			<div>
				<div class="listtable_div_top am-panel-body" >
				    <div class="am-panel-bd">
						<div class="am-u-lg-2 am-show-lg-only" >
							<label class="am-checkbox am-success">
								<input type='checkbox'  name="checkboxes[]" value="<?php echo $v['Refund']['id']; ?>" data-am-ucheck/>
								&nbsp;<?php echo isset($v['Refund']['source_type'])?$v['Refund']['source_type']:0;
			                    echo "-";
			            		echo isset($v['Refund']['source_type_id'])?$v['Refund']['source_type_id']:0;
			            	?><br/><?php echo $v['Refund']['order_code'];?>
							</label>
						</div>
						<div class="am-u-lg-2 am-u-md-5 am-u-sm-4" style="word-wrap:break-word;word-break:normal;">
							<?php echo $v['Refund']['refund_id'];?><br/><?php echo $v['Refund']['created'];?>&nbsp;<br />
							<?php if($v['Refund']['status']=="WAIT_SELLER_AGREE"){
			        				echo "买家已经申请退款，等待卖家同意";
			        			}elseif($v['Refund']['status']=="WAIT_BUYER_RETURN_GOODS"){
			        				echo "卖家已经同意退款，等待买家退货";
			        			}elseif($v['Refund']['status']=="WAIT_SELLER_CONFIRM_GOODS"){
			        				echo "买家已经退货，等待卖家确认收货";
			        			}elseif($v['Refund']['status']=="SELLER_REFUSE_BUYER"){
			        				echo "卖家拒绝退款";
			        			}elseif($v['Refund']['status']=="CLOSED"){
			        				echo "退款关闭";
			        			}elseif($v['Refund']['status']=="SUCCESS"){
			        				echo "退款成功";
			        			}elseif($v['Refund']['status']=="BUYER_NOT_ASK"){
			        				echo "没有申请退款";
			        			}elseif($v['Refund']['status']=="SELLER_REFUSE_RETURN"){
			        				echo "卖家拒绝确认收货";
			        			}elseif($v['Refund']['status']=="WAIT_SELLER_REFUND"){
			        				echo "同意退款，待打款";
			        			}
			        		?>
						</div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-4" >
							<?php echo $v['Refund']['return_reason_type']; ?><br />
                			<?php echo $ld['return'].$ld['reason']?>:<?php echo $v['Refund']['return_reason']; ?>&nbsp;
						</div>
							
						<div class="am-u-lg-2 am-show-lg-only" style="word-wrap:break-word;word-break:normal;">
							<?php echo $v['Refund']['product_name'];?><br /><?php echo $ld['product_code'] ?>：<?php echo $v['Refund']['product_code'];?>&nbsp;&nbsp;<br /><?php echo $v['Refund']['product_price'];?>×<?php echo $v['Refund']['product_quantity'];?>
							&nbsp;
						</div>
						<div class="am-u-lg-1 am-show-lg-only">运费: <?php echo $v['Refund']['shipping_fee'];?><br />合计: <?php echo $v['Refund']['total_fee'];?></div>
						<div class="am-u-lg-2 am-show-lg-only"  ><?php echo $v['Refund']['modified'];?></div>
						<div class="am-u-lg-1 am-u-md-4 am-u-sm-4 am-btn-group-xs am-action" >
							   <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/refunds/'.$v['Refund']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
						<?php echo $html->link($ld["order_redelivery"],"/orders/new_order/".$v['Refund']['order_code'],array("target"=>"_blank",'escape' => false,'class'=>'am-btn am-btn-default am-btns')).'&nbsp;';
							echo $html->link("换货","/orders/new_order/".$v['Refund']['order_code']."/change",array("target"=>"_blank",'escape' => false,'class'=>'am-btn am-btn-default am-btn-xs am-btns'));?>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
		<?php }}else{?>
				<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
			   <?php }?>	 
	</div>
	<?php if(isset($Refundslist) && sizeof($Refundslist)){?>
		<div id="btnouterlist" class="btnouterlist">
					<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-hide-sm-down" style="left:6px;">
						<div class="am-fl">
					          <label class="am-checkbox am-success" style="display: inline;">
					            <input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox"
								value="checkbox" data-am-ucheck><span><?php echo $ld['select_all']?></span>
					          </label>
			            	</div>
						<div class="am-fl" style="margin-left:3px;">
					            <select name="barch_opration_select" id="barch_opration_select" data-am-selected  onchange="barch_refund_opration_select_onchange(this)">
					              <option value="0"><?php echo $ld['batch_operate']?></option>
					              <option value="delete"><?php echo $ld['batch_delete']?></option>
							<?php if( isset($profile_id) && !empty($profile_id) ){ ?>
					    		  <option value="export_csv"><?php echo $ld['batch_export']?></option>
					    		  <?php } ?>
					            </select>
			            	</div> 
						<div class="am-fl" style="display:none;margin-left:3px;">
			                    <select id="export_csv" data-am-selected name="barch_opration_select_onchange" >
			                        <option value=""><?php echo $ld['click_select']?></option>
			                        <option value="all_export_csv"><?php echo  $ld['all_export']?></option>
			                        <option value="choice_export"><?php echo $ld['choice_export']?></option>
			                       
			                    </select>&nbsp;
			              	</div>
						<div class="am-fl" style="margin-left:3px;">
			               	   <button type="button" class="am-btn am-radius am-btn-danger am-btn-sm" onclick="select_batch_operations()"><?php echo $ld['submit']?></button>
			              	</div>
				</div>
			
	                 
				</div>
				<div  class="btnouterlist">
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">&nbsp;</div>
					<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					<?php echo $this->element('pagers')?>
					</div>
				</div>
	<?php }?>
	<?php echo $form->end();?>
</div>
<script type="text/javascript">
function select_batch_operations(){
	var barch_opration_select = document.getElementById("barch_opration_select");
      var export_csv = document.getElementById("export_csv");
      if(barch_opration_select.value==0){
      	  	alert(j_select_operation_type);
			return;
      }
      if(barch_opration_select.value=='delete'){
		batch_operations();
	}
	if(barch_opration_select.value=='export_csv'){
		if(export_csv.value=='all_export_csv'){
			window.location.href=admin_webroot+"/refunds/all_export_csv";
		
		}
		if(export_csv.value=='choice_export'){
			choice_upload();
		}
	}
}



//选择导出
function choice_upload(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
		}
	}
	if( postData=="" ){
		alert("<?php echo $ld['please_select'] ?>");
		return;
	}else{
	window.location.href=admin_webroot+"refunds/choice_export/"+postData;
	
	}
}	

//触发子下拉
function barch_refund_opration_select_onchange(obj){
	if(obj.value!="export_csv"){
		$("#export_csv").parent().hide();		
	}
	$("select[name='barch_opration_select_onchange[]']").parent().hide();
	
	var export_csv=document.getElementById("export_csv").value;
	
	if(obj.value=="export_csv"){
		if(export_csv=="all_export_csv"){
			$("#export_csv").parent().show();
		}else{
			$("#export_csv").parent().show();
		}
	}

}

function formsubmit(){
	var refundid=document.getElementById('refundid').value;
	var ordercode=document.getElementById('ordercode').value;
	var productname=document.getElementById('productname').value;
	var refund_type=document.getElementById('refund_type').value;
	var status=document.getElementById('status').value;
	var start_date = document.getElementsByName('start_date')[0].value;
	var end_date = document.getElementsByName('end_date')[0].value;
	var ta = checkbox();
	var str = '';
	str +="&"+"ta=" +ta.substring(ta,ta.length-1);
	var url = "refundid="+refundid+"&ordercode="+ordercode+"&productname="+productname+"&refund_type="+refund_type+"&status="+status+"&start_date="+start_date+"&end_date="+end_date+str;
	window.location.href = encodeURI(admin_webroot+"refunds?"+url);
}

//批量操作
  function batch_operations(){ 
   	   	var bratch_operat_check = document.getElementsByName("checkboxes[]");
   	   	var postData = "";
   	   		for(var i=0;i<bratch_operat_check.length;i++){
		     if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
	  	     }
	}
	
		if( postData=="" ){
		alert(j_please_select);
		return;
   	    }
   	    	if(confirm(j_confirm_delete)){
   	    		$.ajax({
   	    			type:"POST",
   	    		       url:admin_webroot+"refunds/batch_operations/",
   	    			data:postData,
   	    		      datatype: "json",
   	    			success:function(data){
				window.location.href = window.location.href;
			}
   	    		});
   	    	
   	    	
   	    	}
   	    
   	    
	}


</script>