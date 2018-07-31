<?php	echo $form->create('Enquiries', array('controoler'=>'enquiries','action' => '/index','class'=>"am-form",'name'=>'EnquiryForm','onsubmit'=>'return false;'));	 ?>
<input type="hidden" name="data[Enquiry][user_id]" id="userid" value="<?php if(isset($_SESSION['User']['User']['id'])){echo $_SESSION['User']['User']['id'];}?>" />
<div class="am-container">
  <h1><?php echo $ld['enquiry_form']; ?></h1>
  <div class="search_product am-u-lg-12 am-u-md-12 am-u-sm-12 am-form-group">   
	<div class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-text-right" style="text-align: left!important;width: 104px;margin-left: -17px;"><div style="margin-top:20px;"><?php echo $ld['search'].$ld['product']?>: </div></div>
	<div class="am-u-lg-6 am-u-md-7 am-u-sm-5"><textarea id="code_name" style="height:68px;font-size:13px;resize:none;padding:0.625em;"></textarea></div>
	<div class="submit_btn am-u-lg-2 am-u-md-2 am-u-sm-2">
	  <div style="margin-top:20px;"><input id="ajax_search" class="am-btn am-btn-secondary am-btn-sm" type="button" value="<?php echo $ld['search']?>" ></div>
	</div>
    <div class="am-cf" style="margin-bottom:10px;"></div>
    <div class="am-u-lg-2 am-u-md-2 am-u-sm-3">&nbsp;</div>
	<div class="am-u-lg-6 am-u-md-7 am-u-sm-5" style="display:none;height:auto;"><select id="sel_product" multiple="multiple" size="4"></select></div>
	<div class="submit_btn am-u-lg-2 am-u-md-2 am-u-sm-2" style="display:none;">
	  <div style="margin-top:20px;"><input id="add" class="am-btn am-btn-secondary am-btn-sm" type="button" value="<?php echo $ld['add']?>" /></div>
	</div>
    <div class="am-cf"></div>
  </div>
    <ul id="add_ul" style="padding:0;clear:both;">
    		<?php
    				if(isset($pro_info)&&sizeof($pro_info)>0){foreach($pro_info as $v){
    					$product_attribute=isset($public_product_attributes[$v['Product']['id']])?implode(' ',$public_product_attributes[$v['Product']['id']]):'';
					//pr($product_attribute);
					//pr($v);
    		?>

    		<li class="series">
		    	<label id="addspec" class="mark am-u-lg-1 am-u-sm-2 am-u-md-2">[ - ]</label>
		        <div class="am-g am-u-lg-11 am-u-sm-10 am-u-md-10" style="padding-left: 0;">
		            <div class="am-form-group" style="margin-left:18px;">
		                <div class="am-padding0 am-text-left am-u-lg-12 am-u-sm-12 am-u-md-12"><div class="am-fl am-u-lg-2 am-u-sm-12 am-u-md-3" style="width: 50px;"><?php echo $html->image(isset($v['Product']['img_thumb'])&&!empty($v['Product']['img_thumb'])?$v['Product']['img_thumb']:'/theme/default/images/default.png');  ?></div><div class="am-fl am-u-lg-9 am-u-sm-12 am-u-md-8"><p><?php if(isset($v['ProductI18n']['name'])){echo $v['ProductI18n']['name'];}?></p><p><?php if(isset($v['Product']['code'])){echo $v['Product']['code'];}?></p><input type="hidden"  id="pro_id0" value="<?php if(isset($v['Product']['id'])){echo $v['Product']['id'];}?>" /><input type="hidden" name="data[Enquiry][part_num][]" id="part_num0" value="<?php if(isset($v['Product']['code'])){echo $v['Product']['code'];}?>" /></div></div>
		                <div class="am-cf"></div>
		            </div>
		            <div class="am-form-group" style="margin-left: 18px;">
		                <label  class="am-u-lg-1 am-u-sm-2 am-u-md-2 am-padding0 am-form-label" <?php if(isset($product_attribute)&&empty($product_attribute)){echo "style='display:none'";}?>><?php echo $ld['attribute']?></label>
		                <div class="am-u-lg-3 am-u-sm-4 am-u-md-4 am-padding0" <?php if(isset($product_attribute)&&empty($product_attribute)){echo "style='display:none'";}?>><label class="am-u-lg-10 am-u-sm-10 am-u-md-10 am-padding0"><input type="text" readonly name="show_attribute" value="<?php if(isset($product_attribute)){echo $product_attribute;}?>" /></label><font class="am-u-lg-1 am-u-sm-1 am-u-md-1" color="red">*</font><input type="hidden" name="data[Enquiry][attribute][]" id="mfg0" value="<?php if(isset($product_attribute)){echo $product_attribute;}?>" /></div>
		                <label  class="am-u-lg-1 am-u-sm-2 am-u-md-2 am-padding0 am-form-label"><?php echo $ld['budget']?></label>
		                <div class="am-u-lg-3 am-u-sm-4 am-u-md-4 am-padding0"><label class="am-u-lg-10 am-u-sm-10 am-u-md-10 am-padding0"><input type="text" name="data[Enquiry][target_price][]" id="price0"  value="<?php if(isset($v['Product']['shop_price'])){echo $v['Product']['shop_price'];}?>" /></label><font class="am-u-lg-1 am-u-sm-1 am-u-md-1" color="red">*</font></div>
		                <label class="am-u-lg-1 am-u-sm-2 am-u-md-2 am-padding0 am-form-label"><?php echo $ld['qty_f']?></label>
		    		    <div class="am-u-lg-3 am-u-sm-4 am-u-md-4 am-padding0"><label class="am-u-lg-10 am-u-sm-10 am-u-md-10 am-padding0"><input type="text" name="data[Enquiry][qty][]" id="qty0" value="1" /></label><font class="am-u-lg-1 am-u-sm-1 am-u-md-1" color="red">*</font><input type="hidden" id="userbalance" value="<?php if(isset($_SESSION['User']['User']['balance'])){echo $_SESSION['User']['User']['balance'];}?>" /><input type="hidden" id="check_price" value="<?php if(isset($configs['enquiry-check'])){echo $configs['enquiry-check'];}?>" /></div>
		                <div class="am-cf"></div>
		            </div>
		            <div class="am-cf"></div>
		        </div>
		        <div class="am-cf"></div>
		      </li>
    		
    		<?php 	}}	?>
    </ul>
    <hr id="border_1" style="border-top:1px solid #ccc;display: none;">
  <ul id="client_info" class="am-avg-sm-1">
	<li>
	  <div class="am-form-group">
	    <label class="am-u-lg-1 am-u-md-2 am-u-sm-3 am-form-label contact_person"><?php echo $ld['contact']?></label>
	    <div class="am-u-lg-5 am-u-md-7 am-u-sm-8"><input type="text" name="data[Enquiry][contact_person]" id="contact_person"></div>
	    <font class="contact_star am-u-lg-1 am-u-md-1 am-u-sm-1" color="red">*</font>
	  </div>
	</li>
	<li>
	  <div class="am-form-group">
	    <label class="am-u-lg-1 am-u-md-2 am-u-sm-3 am-form-label"><?php echo $ld['tele']?></label>
	    <div class="am-u-lg-5 am-u-md-7 am-u-sm-8"><input type="text" name="data[Enquiry][tel1]" id="tel1" /></div>
	    <font class="am-u-lg-1 am-u-md-1 am-u-sm-1" color="red">*</font>
	  </div>
	</li>
	<li>
	  <div class="am-form-group">
	    <label class="am-u-lg-1 am-u-md-2 am-u-sm-3 am-form-label"><?php echo $ld['email']?></label>
	    <div class="am-u-lg-5 am-u-md-7 am-u-sm-8"><input type="text" name="data[Enquiry][email]" id="email"></div>
	    <font class="am-u-lg-1 am-u-md-1 am-u-sm-1" color="red">*</font>
	  </div>
	</li>
	<li style="height:auto;">
	  <div class="am-form-group">
	    <label class="am-u-lg-1 am-u-md-2 am-u-sm-3 am-form-label"><?php echo $ld['remark']?></label>
	    <div class="am-u-lg-5 am-u-md-7 am-u-sm-8"><textarea rows="2" name="data[Enquiry][remark]" id="remark"></textarea>
	  </div>
	</li>
    <li>
	  <div class="am-form-group" style="margin-top:1em;">
	    <label class="am-u-lg-1 am-u-md-2 am-hide-sm-only am-form-label">&nbsp;</label>
	    <div class="am-u-lg-5 am-u-md-7 am-u-sm-12"><button type="submit" class="am-btn am-btn-secondary am-btn-block" onclick="check_form();"><?php echo $ld['submit']?></button></div>
	  </div>
	</li>
  </ul>
	
</div>
<?php echo $form->end();?>
<script type="text/javascript">
	 


	function remove_li(n){
		var parent=$("#empty"+n+"").parent();
		parent.remove();
	}

	function check_form(){
		if(js_login_user_data!=null){
            var userid=js_login_user_data['User']['id'];
			var n=$("#add_ul li.series").length;
			for(var i=0;i<n;i++){
				var part_num=$('#part_num'+i).val();
				var attribute=$('#mfg'+i).val();
				var qty=$('#qty'+i).val();
				var price=$('#price'+i).val();
				if(typeof(qty)=='undefined')continue;
				if(cTrim(qty,0) == ""){
					alert("<?php echo $ld['qty_f']." ".$ld['can_not_empty']?>");
					return false;
				}
			}
			$flag=0;
			if($("#check_price").val()=='1'){
				$.ajax({
		            type: "POST",
		            url:web_base+"/products/updatebalance",
		            data:{"user_id":userid},
					success: function(data){
						data=JSON.parse(data);
						$("#userbalance").val(data['balance']);
						if(parseFloat($("#userbalance").val())<parseFloat($("#price0").val())){
							$flag=1;
						}
						if($flag==1){
							alert("余额不足，请先充值");
							return false;
						}
						var contact_person=document.getElementById("contact_person").value;
						var tel1=document.getElementById("tel1").value;
						var email=document.getElementById("email").value;
						if(cTrim(contact_person,0) == ""){
							alert("<?php echo $ld['contact']." ".$ld['can_not_empty']?>");
							return false;
						}
						if(cTrim(tel1,0) == ""){
							alert("<?php echo $ld['tele']." ".$ld['can_not_empty']?>");
							return false;
						}
						if(cTrim(email,0) == ""){
							alert("<?php echo $ld['email']." ".$ld['can_not_empty']?>");
							return false;
						}
                        enquirie_submit();
                        return false;
						//document.EnquiryForm.submit();
		            }
				});
			}else{
				var contact_person=document.getElementById("contact_person").value;
				var tel1=document.getElementById("tel1").value;
				var email=document.getElementById("email").value;
				if(cTrim(contact_person,0) == ""){
					alert("<?php echo $ld['contact']." ".$ld['can_not_empty']?>");
					return false;
				}
				if(cTrim(tel1,0) == ""){
					alert("<?php echo $ld['tele']." ".$ld['can_not_empty']?>");
					return false;
				}
				if(cTrim(email,0) == ""){
					alert("<?php echo $ld['email']." ".$ld['can_not_empty']?>");
					return false;
				}
                enquirie_submit();
                return false;
				//document.EnquiryForm.submit();
			}
		}else{
			ajax_login_show();
			return false;
		}
	}

    function enquirie_submit(){
        $.ajax({
		type: "POST",
		url:web_base+"/enquiries/index",
		data:$(document.EnquiryForm).serialize(),
		dataType:"json",
		success: function(data){
			alert(data.content);
			if(data.flag=='2'){
				setTimeout("window.location.href='"+web_base+data.url+"';",3000);
			}
		}
	});
    }
</script>
<script type="text/javascript">
$(function(){
		var n=0;
		$('#addspec').click(function(){
			$(this).parent().remove();
		});
				$("#ajax_search").click(function(){
			var keyword=$("#code_name").val();
			if(keyword!=""){
				$.ajax({
		            type: "POST",
		            url:web_base+"/products/enquiry_search",
		            data:{"keyword":keyword},
					success: function(data) {
						data=JSON.parse(data);
						$("#sel_product").parent().show();
						$("#add").parent().parent().show();
						$("#sel_product").find("option").remove();
						$.each(data['product'],function(i,item){
							$("<option></option>").val(item['Product']['id']).text(item['ProductI18n']['name']).appendTo($("#sel_product"));
						});
		            }
				});
			}
		});
		$("#add").click(function(){
			var keyword=$("#sel_product").val();
			$.ajax({
	            type: "POST",
	            url:web_base+"/products/product_search_by_id",
	            data:{"keyword":keyword},
				success: function(data) {
					data=JSON.parse(data);
					var htm ="";
					$.each(data['product'],function(i,item){
						if(check_id(item['Product']['id'])){
							var attr="";
							if(item['ProductAttribute']!=""){
								$.each(item['ProductAttribute'],function(j,val){
									attr+=val['attribute_value']+" ";
								})
							}
							n++;
                            
                            htm="<label id='empty"+n+"' onclick='remove_li("+n+")' class='mark am-u-lg-1 am-u-sm-2 am-u-md-2'>[ - ]</label>";
                            htm+="<div class='am-g am-u-lg-11 am-u-sm-10 am-u-md-10'>";
                                htm+="<div class='am-form-group'>";
                                htm+="<div class='am-padding0 am-text-left am-u-lg-12 am-u-sm-12 am-u-md-12'><div class='am-fl am-u-lg-2 am-u-sm-12 am-u-md-3'><img src='"+web_base+(item['Product']['img_thumb'].trim()==''?'/theme/default/images/default.png':item['Product']['img_thumb'])+"'  onerror='javascript:this.src=\""+web_base+"/theme/default/images/default.png\";'></div>";
                                htm+="<div class='am-fl am-u-lg-9 am-u-sm-12 am-u-md-8'><p>"+item['ProductI18n']['name']+"</p><p>"+item['Product']['code']+"</p><input type='hidden' value='"+item['Product']['id']+"' id='pro_id"+n+"'><input type='hidden' value='"+item['Product']['code']+"' id='pro_id"+n+"' name='data[Enquiry][part_num][]'></div></div>";
                                htm+="<div class='am-cf'></div>";
                            htm+="</div>";
                            htm+="<div class='am-form-group'>";
                                htm+="<label class='am-u-lg-1 am-u-sm-2 am-u-md-2 am-padding0 am-form-label' "+(attr==''?'style=\"display:none\"':'')+"><?php echo $ld['attribute']; ?></label>";
                                htm+="<div class='am-u-lg-3 am-u-sm-4 am-u-md-4 am-padding0' "+(attr==''?'style=\"display:none\"':'')+"><label class='am-u-lg-10 am-u-sm-10 am-u-md-10 am-padding0'><input type='text' value='"+(attr!=''?attr:'')+"' name='show_attribute' readonly=''></label><font color='red' class='am-u-lg-1 am-u-sm-1 am-u-md-1'>*</font><input type='hidden' value='"+(attr!=''?attr:'')+"' id='mfg"+n+"' name='data[Enquiry][attribute][]'></div>";
                                htm+="<label class='am-u-lg-1 am-u-sm-2 am-u-md-2 am-padding0 am-form-label'><?php echo $ld['budget']; ?></label>";
                                htm+="<div class='am-u-lg-3 am-u-sm-4 am-u-md-4 am-padding0'><label class='am-u-lg-10 am-u-sm-10 am-u-md-10 am-padding0'><input type='text' value='"+item['Product']['shop_price']+"' id='price"+n+"'name='data[Enquiry][target_price][]'></label><font color='red' class='am-u-lg-1 am-u-sm-1 am-u-md-1'>*</font></div>";
                                htm+="<label class='am-u-lg-1 am-u-sm-2 am-u-md-2 am-padding0 am-form-label'><?php echo $ld['qty_f']; ?></label>";
			                    htm+="<div class='am-u-lg-3 am-u-sm-4 am-u-md-4 am-padding0'><label class='am-u-lg-10 am-u-sm-10 am-u-md-10 am-padding0'><input type='text' value='1' id='qty"+n+"' name='data[Enquiry][qty][]'></label><font color='red' class='am-u-lg-1 am-u-sm-1 am-u-md-1'>*</font></div>";
                                htm+="<div class='am-cf'></div>";
                            htm+="</div>";
                            
                            htm+="<div class='am-cf'></div>";
                            htm+="</div>";
                            htm+="<div class='am-cf'></div>";
						}
						if(htm!="" && flag){
							$('ul#add_ul').append(function(){
								return "<li class='series'>"+htm+"</li>";
							});
						}
					});

	            }
			});
		});
	});
	function check_id(pro_id){
		var ipts = $('input[id^="pro_id"]');
		flag=true;
        ipts.each(function () {
            if ($(this).val()==pro_id) {
				alert("重复添加商品");
				flag=false;
				return;
            }
        });
		return flag;
	}

	function remove_li(n){
		var parent=$("#empty"+n+"").parent();
		parent.remove();
	}

	function cTrim(sInputString,iType){
		var sTmpStr = ' '
		var i = -1
		if(iType == 0 || iType == 1){
			while(sTmpStr == ' '){
				++i
				sTmpStr = sInputString.substr(i,1)
			}
			sInputString = sInputString.substring(i)
		}
		if(iType == 0 || iType == 2){
			sTmpStr = ' '
			i = sInputString.length
			while(sTmpStr == ' '){
				--i
				sTmpStr = sInputString.substr(i,1)
			}
			sInputString = sInputString.substring(0,i+1)
		}
		return sInputString;
	}

</script>

<style>
.am-container li.series img {
    width: 50px;
    height: 50px;
}
#client_info{
	margin-top: 20px;
}
.am-container li.series .mark {
    width: 64px;
}
.am-fl.am-u-lg-2.am-u-sm-12.am-u-md-3{
	width: 50px;
	margin-left: -18px;

}
</style>

<script>
var add = document.querySelector('#add');
var add_ul = document.querySelector('#add_ul');
var border_1 = document.querySelector('#border_1');
var add = document.querySelector('#add');

window.onclick = function(){
	if(add_ul.innerText == ''){
		border_1.style.display ='';
	}else{
		border_1.style.display = 'none';
	}
}
</script>