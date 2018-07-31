<style type="text/css">
#priview_table{width:98%;margin:0 auto;}
.input_price,.input_qty{width:50%;}
.input_notes{width:100%;}
#tbody1 .no_record td{padding:10px 0;}
.email_info{margin-top:40px;}
.email_info input[type=text]{min-width:200px;}
.email_info select{min-width:205px;}
.email_info textarea{min-width:198px;}
.am-form-label{font-weight:bold;margin-top:5px;left:20px;}
</style>
<?php echo $form->create('QuoteProductForm',array('action'=>'/saveprouduct/','class'=>'am-form am-form-inline am-form-horizontal','name'=>"QuoteProductForm",'id'=>"QuoteProductForm","enctype"=>"multipart/form-data"));?>
<input id="type" name="type" value='<?php echo isset($_REQUEST["type"])?1:0?>' type="hidden"/>
<input id="enquiry_id" name="data1[Quote][enquiry_id]" value='<?php echo isset($enquiry_id)?$enquiry_id:0?>' type="hidden"/>
<div class="listsearch">
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-2" style="margin:10px 0 0 0">
        <li style="margin:0 0 10px 0">
             <div  class="am-u-lg-7 am-u-md-7 am-u-sm-7">
	            <label class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-form-label "><?php echo $ld['keyword'];?></label>
	            <div  class="am-u-lg-8 am-u-md-8 am-u-sm-8">
	                <input style="margin-right:10px;" type="text" id="product_keyword"/>
	            </div>
	        </div>
	        <div class="am-u-lg-3 am-u-md-1 am-u-sm-1 am-u-end">
	           <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" value="<?php echo $ld['search'];?>" onclick="quoteSearchProduct()" />
	          </div>
        </li>
        <li style="margin:0 0 10px 0">
        	  <div class="am-show-sm-only am-u-sm-2">&nbsp;</div>
              <div class="am-u-lg-6 am-u-md-5 am-u-sm-5" style="padding-left:8px;padding-right:29px;">
                <select name="select_goods" id="select_goods">
                    <option value=""><?php echo $ld['please_select'];?></option>
                </select>
            </div>
          <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 "style="top:3px;">
            <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="+" onclick="submit_single()"/>
          </div>
        </li>
    
    </ul>
    
</div>
<div id="basic_information" class="am-panel-collapse am-collapse am-in">
    <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
        <table class="am-table" id="priview_table">
            <thead>
            <tr>
                <th><?php echo $ld['sku'];?></th>
                <th width="10%"><?php echo $ld['brand'];?></th>
                <th width="15%"><?php echo $ld['attribute'];?></th>
                <th width="8%"><?php echo $ld['qty_offered'];?></th>
                <th width="8%"><?php echo $ld['qty_req'];?></th>
                <th width="8%"><?php echo $ld['offered_price'];?></th>
                <th width="8%"><?php echo $ld['target_price'];?></th>
                <th width="20%"><?php echo $ld['notes'];?></th>
                <th  width="8%"><?php echo $ld['operate'];?></th>
            </tr>
            </thead>
            <tbody id="tbody1">
            <?php if(isset($quote_products_list)&&!empty($quote_products_list)){foreach($quote_products_list as $k=>$v){?>
                <tr>
                    <td><?php if(isset($v['QuoteProduct']['product_code'])){echo $v['QuoteProduct']['product_code'];}?>
                        <input type="hidden" name="data[<?php echo $k;?>][QuoteProduct][product_code]" value="<?php if(isset($v['QuoteProduct']['product_code'])){echo $v['QuoteProduct']['product_code'];}?>" />
                        <input type="hidden" name="data[<?php echo $k;?>][QuoteProduct][product_name]" value="<?php if(isset($v['QuoteProduct']['product_name'])){echo $v['QuoteProduct']['product_name'];}?>" />
                    </td>
                    <td><?php if(isset($v['QuoteProduct']['brand_code'])){echo $v['QuoteProduct']['brand_code'];}?>
                        <input type="hidden" name="data[<?php echo $k;?>][QuoteProduct][brand_code]" value="<?php if(isset($v['QuoteProduct']['brand_code'])){echo $v['QuoteProduct']['brand_code'];}?>" />
                    </td>
                    <td><?php
                    		if(isset($public_attr_info)&&!empty($public_attr_info)){
                    			foreach($public_attr_info as $kk=>$vv){
                    				echo "<p>";
                    				echo $vv." : ".(isset($v['QuoteProduct']['attribute'][$kk])?$v['QuoteProduct']['attribute'][$kk]:'-');
                    				echo "</p>";
                    				echo "<input type='hidden' name='data[".$k."][QuoteProduct][attribute][".$kk."][title]' value='".$vv."' />";
                    				echo "<input type='hidden' name='data[".$k."][QuoteProduct][attribute][".$kk."][value]' value='".(isset($v['QuoteProduct']['attribute'][$kk])?$v['QuoteProduct']['attribute'][$kk]:'-')."' />";
                    			}
                    		}
                    	?></td>
                    <td>
                        <input type="text" class="input_qty" name="data[<?php echo $k;?>][QuoteProduct][qty_offered]" value="<?php if(isset($v['QuoteProduct']['qty_offered'])){echo $v['QuoteProduct']['qty_offered'];}?>" />
                    </td>
                    <td>
                        <input type="text" class="input_qty" name="data[<?php echo $k;?>][QuoteProduct][qty_requested]" value="<?php if(isset($v['QuoteProduct']['qty_requested'])){echo $v['QuoteProduct']['qty_requested'];}?>" />
                    </td>
                    <td>
                        <input type="text" id='offered_price<?php echo $k;?>' class="input_price" name="data[<?php echo $k;?>][QuoteProduct][offered_price]" value="<?php if(isset($v['QuoteProduct']['offered_price'])){echo $v['QuoteProduct']['offered_price'];}?>" />
                    </td>
                    <td>
                        <input type="text" class="input_price" name="data[<?php echo $k;?>][QuoteProduct][target_price]" value="<?php if(isset($v['QuoteProduct']['target_price'])){echo $v['QuoteProduct']['target_price'];}?>" />
                    </td>
                    <td>
                            <input type="text" class="input_notes" name="data[<?php echo $k;?>][QuoteProduct][payment_terms]" value="<?php if(isset($v['QuoteProduct']['payment_terms'])){echo $v['QuoteProduct']['payment_terms'];}?>" />
                    </td>
                    <td><a href="javascript:void(0)" onclick="delIndex(this)" style="margin-top:4px;" class="am-btn am-btn-default am-text-danger am-btn-xs am-radius"><?php echo $ld['delete'];?></a></td>
                </tr>
            <?php
            }
            }else{ ?>
                <tr class="no_record am-text-center"><td colspan="9"><?php echo $ld['no_record'] ?></td></tr>
            <?php }?>
            </tbody>
        </table>
        <div class="am-g">
        	  <div class="am-u-lg-10">
	        <ul class="email_info" style="list-style-type: none;">
	            <li style="float: left;margin-top: 5px;margin-left: 15px;width:100px;"><strong><?php echo $ld['member'];?>:</strong></li>
	            <li style="margin-bottom:10px;">
	            	<div class="am-g">
	            		<div class="am-u-lg-3 am-padding-0">
						<select style="width:200px;" name="data1[Quote][user_id]" onchange="member_set(this)">
							<option value='0'><?php echo $ld['please_select'] ?></option>
							<?php if(isset($quote_list['Quote']['user_id'])&&!empty($quote_list['Quote']['user_id'])){ ?>
							<option value="<?php echo $quote_list['Quote']['user_id']; ?>" selected><?php echo $quote_list['Quote']['customer_name']." / ".$quote_list['Quote']['email']; ?></option>
							<?php } ?>
						</select>
	            		</div>
	            		<div class="am-u-lg-3">
	            			<input type='text' value='' id='user_keywords'>
	            		</div>
	            		<div class="am-u-lg-3">
	            			<button type='button' class="am-btn am-btn-success am-radius am-btn-xs" onclick="member_search(this)"><?php echo $ld['search']; ?></button>
	            		</div>
	            		<div class="am-cf"></div>
	            	</div>
	            	
	            </li>
	            <li style="float: left;margin-top: 5px;margin-left: 15px;width: 100px;"><strong><?php echo $ld['name_of_member'];?>:</strong></li>
	            <li style="margin-bottom:10px;"><input type="text" style="width:200px;" id="customer_name" name="data1[Quote][customer_name]" value="<?php if(isset($quote_list['Quote']['customer_name'])){echo $quote_list['Quote']['customer_name'];}?>"/></li>
	            <li style="float: left;margin-top: 5px;margin-left: 15px;width: 100px;"><strong>Email:</strong></li>
	            <li style="margin-bottom:10px;"><input type="text" style="width:200px;" id="email" name="data1[Quote][email]" value="<?php if(isset($quote_list['Quote']['email'])){echo $quote_list['Quote']['email'];}?>"/></li>
	            <li style="float: left;margin-top: 5px;margin-left: 15px;width: 100px;"><strong>Email <?php echo $ld['title'];?>:</strong></li>
	            <li style="margin-bottom:10px;">
                    <select style="width:200px;" name="data1[Quote][mail_title]">
                        <option value="0"><?php echo $ld['please_select'];?></option>
                         <?php if(isset($informationresource_infos['quote_mail_tittle'])&&sizeof($informationresource_infos['quote_mail_tittle'])>0){foreach($informationresource_infos['quote_mail_tittle'] as $kk=>$vv){ ?>
                         <option <?php if($kk == $quote_list['Quote']['mail_title']){echo "selected";} ?> value="<?php echo $kk; ?>"><?php echo $vv; ?></option>
                        <?php }} ?>
                    </select>
                </li>
	            <li style="float: left;margin-top: 5px;margin-left: 15px;width: 100px;"><strong><?php echo $ld['contacter'];?>:</strong></li>
	            <li style="margin-bottom:10px;"><input style="width:200px;" type="text" id="contact_person" name="data1[Quote][contact_person]" value="<?php if(isset($quote_list['Quote']['contact_person'])){echo $quote_list['Quote']['contact_person'];}?>"/></li>
	            <li style="float: left;margin-top: 5px;margin-left: 15px;width: 100px;"><strong><?php echo $ld['inquire_date'];?>:</strong></li>
	            <li style="margin-bottom:10px;"><input style="width:200px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="data1[Quote][inquire_date]" value="<?php if(isset($quote_list['Quote']['inquire_date'])){echo $quote_list['Quote']['inquire_date'];} ?>" /></li>
	            <li style="float: left;margin-top: 5px;margin-left: 15px;width: 100px;"><strong><?php echo $ld['remark'];?>:</strong></li>
	            <li style="margin-bottom:10px;"><textarea style="width:200px;" id="remark" name="data1[Quote][remark]"><?php if(isset($quote_list['Quote']['remark'])){echo $quote_list['Quote']['remark'];}?></textarea></li>
	        </ul>
	        </div>
	        <div class="am-u-lg-10" style="padding-top:40px;">
	        		<?php if(isset($quote_products_list)&&sizeof($quote_products_list)>0){ ?>
	        		<table class="am-table am-table-bordered">
	        			<thead>
		        			<tr>
							<th><?php echo $ld['sku']; ?></th>
							<th><?php echo $ld['brand']; ?></th>
							<th><?php echo $ld['attribute']; ?></th>
							<th><?php echo $ld['qty_offered']; ?></th>
							<th><?php echo $ld['qty_req']; ?></th>
							<th><?php echo $ld['offered_price']; ?></th>
							<th><?php echo $ld['target_price']; ?></th>
							<th><?php echo $ld['attribute']; ?></th>
							<th><?php echo $ld['notes']; ?></th>
						</tr>
					</thead>
					<tbody>
	        		<?php foreach($quote_products_list as $v){ ?>
					<tr>
						<td><?php echo $v['QuoteProduct']['product_code']; ?></td>
						<td><?php echo $v['QuoteProduct']['brand_code']; ?></td>
						<td><?php
		                    		if(isset($public_attr_info)&&!empty($public_attr_info)){
		                    			foreach($public_attr_info as $kk=>$vv){
		                    				echo "<p>";
		                    				echo $vv." : ".(isset($vv['QuoteProduct']['attribute'][$kk])?$vv['QuoteProduct']['attribute'][$kk]:'-');
		                    				echo "</p>";
		                    			}
		                    		}
		                    	?></td>
						<td><?php echo isset($v['QuoteProduct']['qty_offered'])?$v['QuoteProduct']['qty_offered']:''; ?></td>
						<td><?php echo isset($v['QuoteProduct']['qty_requested'])?$v['QuoteProduct']['qty_requested']:''; ?></td>
						<td><?php echo isset($v['QuoteProduct']['offered_price'])?$v['QuoteProduct']['offered_price']:''; ?></td>
						<td><?php echo isset($v['QuoteProduct']['target_price'])?$v['QuoteProduct']['target_price']:''; ?></td>
						<td><?php echo isset($v['QuoteProduct']['data_code'])?$v['QuoteProduct']['data_code']:''; ?></td>
						<td><?php echo isset($v['QuoteProduct']['notes'])?$v['QuoteProduct']['notes']:''; ?></td>
					</tr>
					<?php } ?>
					</tbody>
				</table>
				<?php } ?>
	        </div>
	        <div class="am-cf"></div>
	  </div>
        <div id="btnouterlist" class="btnouter">
                <?php if(!isset($quote_list['Quote']['is_sendmail'])||$quote_list['Quote']['is_sendmail']=="0"){?>
                    <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" id="QuoteProduct_button" value="<?php echo $ld['save'];?>" onclick="Quote_submit(this,<?php if(isset($quote_list['Quote']['id'])){echo $quote_list['Quote']['id'];}else{echo '0';}?>)" />
                    <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" id="Email_button" value="<?php echo $ld['save_and_sendmail'];?>" onclick="Email_submit(this,<?php if(isset($quote_list['Quote']['id'])){echo $quote_list['Quote']['id'];}else{echo '0';}?>)" />
                <?php } ?>
                <?php if(isset($quote_list['Quote']['status'])&&($quote_list['Quote']['status']=='1'||$quote_list['Quote']['status']=='2')){ ?>
                	<input type='button' class="am-btn am-btn-warning am-radius am-btn-sm" value="<?php echo $ld['follow_up']; ?>" onclick='quote_follow_up()' />
                <?php } ?>
                <?php if(isset($quote_list['Quote'])){ ?>
                	<input type='button' class="am-btn am-btn-danger am-radius am-btn-sm" onclick="quote_follow_down()" value="<?php echo $ld['close']; ?>" />
        <div class="am-g">
		<h2 class="am-text-left"><?php echo $ld['logs']; ?></h2>
            <div class="am-u-sm-10">
                <table class="am-table am-table-bordered">
                        <thead>
                            <tr>
                            <th><?php echo $ld['operator']; ?></th>
                            <th><?php echo $ld['modify']; ?></th>
                            <th><?php echo $ld['operation_time']; ?></th>
                            <th><?php echo $ld['note2']; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($quote_log_lists as $k => $v) { ?>
                            <tr>
                                <td><?php echo $v['Operator']['name'] ?></td>
                                <td><?php echo $systemresource_info['quote_status'][$v['QuoteLog']['status']] ?></td>
                                <td><?php echo $v['QuoteLog']['created'] ?></td>
                                <td><?php echo $v['QuoteLog']['remark'] ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                </table>
            </div>
        </div>
                <?php } ?>
        </div>
    </div>
</div>
<?php echo $form->end();?>
</div>

<!-- 弹窗发送 -->

<div class="am-modal am-modal-no-btn" tabindex="-1" id="quites-modal">
  <div class="am-modal-dialog">
    <div class="am-modal-hd" style="border-bottom:1px solid #ddd;"><?php echo $ld['follow_up']; ?>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd am-margin-top-sm">
      <div class="am-g">
          <div class="am-u-sm-2" style="font-weight:600;font-size:16px;">
              <?php echo $ld['note2']; ?>：
          </div>
          <div class="am-u-sm-10">
              <textarea id="quote_text" rows="10" style="resize: none;width:80%"></textarea>
          </div>
      </div>
      <div class="am-g am-margin-top-sm">
          <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" onclick="quote_follow_send()" value="提交">
          <input type="reset" class="am-btn am-btn-default am-radius am-btn-sm" onclick="$('#quites-modal').modal('close');" value="取消">
      </div>
    </div>
  </div>
</div>

<!-- 弹窗关闭 -->
<div class="am-modal am-modal-no-btn" tabindex="-1" id="quites-close">
  <div class="am-modal-dialog">
    <div class="am-modal-hd" style="border-bottom:1px solid #ddd;"><?php echo $ld['close']; ?>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd am-margin-top-sm">
      <div class="am-g">
          <div class="am-u-sm-2" style="font-weight:600;font-size:16px;">
             <?php echo $ld['reason']; ?>：
          </div>
          <div class="am-u-sm-10">
              <textarea id="quote_text_close" rows="10" style="resize: none;width:80%"></textarea>
          </div>
      </div>
      <div class="am-g am-margin-top-sm">
          <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" onclick="quote_follow_close()" value="提交">
          <input type="reset" class="am-btn am-btn-default am-radius am-btn-sm" onclick="$('#quites-close').modal('close');" value="取消">
      </div>
    </div>
  </div>
</div>
<script>
    function Quote_submit(obj,id){
        var customer_name=document.getElementById('customer_name').value;
        var email=document.getElementById('email').value;
        var contact_person=document.getElementById('contact_person').value;
        if(customer_name==""){
            alert(j_customer_name_empty);
            return;
        }
        if(contact_person==""){
            alert(j_contact_person_empty);
            return;
        }
        if(confirm(j_sure_to_save)){
            var form = document.getElementById('QuoteProductForm');
            form.action = admin_webroot+'quotes/saveprouduct/'+id;
            form.submit();
        }
    }

    function Email_submit(obj,id){
        var customer_name=document.getElementById('customer_name').value;
        var email=document.getElementById('email').value;
        var contact_person=document.getElementById('contact_person').value;
        if(customer_name==""){
            alert(j_customer_name_empty);
            return;
        }
        if(email==""){
            alert(j_email_empty);
            return;
        }
        if(contact_person==""){
            alert(j_contact_person_empty);
            return;
        }
        if(confirm(j_save_send_email)){
            var form = document.getElementById('QuoteProductForm');
            form.action = admin_webroot+'quotes/sendemail/'+id;
            form.submit();
        }
    }

    //ajax关键字搜商品
    function quoteSearchProduct(){
        var product_keyword = Trim(document.getElementById("product_keyword").value);//搜索关键字
        var sUrl = admin_webroot+"quotes/searchProducts/";//访问的URL地址
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {product_keyword: product_keyword},
            success: function (result) {
                if(result.flag=="1"){
                    var product_select_sel = document.getElementById('select_goods');
                    product_select_sel.innerHTML = "";
                    if(result.content){
                        for(i=0;i<result.content.length;i++){
                            var opt = document.createElement("OPTION");
                            opt.value = result.content[i]['Product'].code;
                            opt.text  = result.content[i]['Product'].code+"--"+result.content[i]['ProductI18n'].name;
                            product_select_sel.options.add(opt);
                        }
                    }
                    return;
                }
                if(result.flag=="2"){
                    var product_select_sel = document.getElementById('select_goods');
                    product_select_sel.innerHTML = "";
                    var opt = document.createElement("OPTION");
                    opt.value = "";
                    opt.text  = "<?php echo $ld['please_select']?>";
                    product_select_sel.options.add(opt);
                    alert(result.content);
                }
            }
        });
    }

    function submit_single(){
        var product = $('#select_goods').val().trim();
        var tb = document.getElementById('priview_table');
        var index = tb.rows.length-1;
        var sUrl = admin_webroot+"quotes/submit_single/";//访问的URL地址
        if(product==""){return false;}
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {product: product, k:index},
            success: function (result) {
                if(result.flag=="1"){
                    $("#tbody1 .no_record").hide();
                    var public_attr_info=typeof(result.public_attr_info)!='undefined'?result.public_attr_info:[];
                    $.each(result.content,function(k,v){
                        	append_row(k,v,public_attr_info);
                    });
                    var tbody = document.getElementById('tbody1');
                    return;
                }
                if(result.flag=="2"){
                    alert(result.content);
                }
            }
        });
    }

    function delIndex(obj) {
        var rowIndex = obj.parentNode.parentNode.rowIndex;//获得行下标
        var tb = document.getElementById("priview_table");
        tb.deleteRow(rowIndex);//删除当前行
    }

    //动态增加一行
    function append_row(k,v,public_attr_info){
        var tbody = document.getElementById("tbody1");
        var newTR = tbody.insertRow(-1);
        var newTD0 = newTR.insertCell(-1);
        newTD0.innerHTML=v.code+'<input type="hidden" name="data['+k+'][QuoteProduct][product_code]" value="'+v.code+'" /><input type="hidden" name="data['+k+'][QuoteProduct][product_name]" value="'+v.name+'" />';
        var newTD1 = newTR.insertCell(-1);
        newTD1.innerHTML=v.brand_id+'<input type="hidden" name="data['+k+'][QuoteProduct][brand_code]" value="'+v.brand_id+'" />';
        var newTD2 = newTR.insertCell(-1);
        var attribute_html="";
        $(public_attr_info).each(function(index,item){
        		var attribute_id=item.Attribute.id;
        		var attribute_name=item.AttributeI18n.name;
        		var attribute_value=typeof(v.attr[attribute_id])!='undefined'?v.attr[attribute_id]:'-';
        		attribute_html+="<p>";
        		attribute_html+=item.AttributeI18n.name+" : "+attribute_value;
        		attribute_html+="</p>";
        		attribute_html+="<input type='hidden' name='data["+k+"][QuoteProduct][attribute]["+attribute_id+"][title]' value='"+attribute_name+"' />";
        		attribute_html+="<input type='hidden' name='data["+k+"][QuoteProduct][attribute]["+attribute_id+"][value]' value='"+attribute_value+"' />";
        });
        newTD2.innerHTML=attribute_html;
        var newTD3 = newTR.insertCell(-1);
        newTD3.innerHTML='<input type="text" class="input_qty" name="data['+k+'][QuoteProduct][qty_offered]" value="'+v.quantity+'" />';
        var newTD4 = newTR.insertCell(-1);
        newTD4.innerHTML='<input type="text" class="input_qty" name="data['+k+'][QuoteProduct][qty_requested]" value="" />';
        var newTD5 = newTR.insertCell(-1);
        if(v.hasOwnProperty("product_location")){
        		newTD5.innerHTML='<input type="text" id="offered_price'+k+'" class="input_qty" name="data['+k+'][QuoteProduct][offered_price]" value="" />';
        		for(var i=1;i<4;i++){
				newTD5.innerHTML+='<div style="width:60px;cursor:pointer;float:left;" onclick="change_price('+k+','+(v.pro_city_price[i]=='undefined'||v.pro_city_price[i]==null?'0.00':v.pro_city_price[i])+')">'+v.product_location[i]+':&nbsp;'+(v.pro_city_price[i]=='undefined'||v.pro_city_price[i]==null?'0.00':v.pro_city_price[i])+'</div>';
			}
        }else{
        		newTD5.innerHTML='<input type="text" class="input_price" name="data['+k+'][QuoteProduct][offered_price]" value="'+v.shop_price+'" />';
        }
        var newTD6 = newTR.insertCell(-1);
        newTD6.innerHTML='<input type="text" class="input_price" name="data['+k+'][QuoteProduct][target_price]" value="" />';
        var newTD7 = newTR.insertCell(-1);
        newTD7.innerHTML='<input type="text" class="input_notes" name="data['+k+'][QuoteProduct][payment_terms]" value="" />';
        var newTD8 = newTR.insertCell(-1);
        newTD8.innerHTML='<a href="javascript:void(0)" onclick="delIndex(this)" class="am-btn am-btn-danger am-btn-xs am-radius"><?php echo $ld['remove'] ?></a>';
    }
    
    function member_search(s_btn){
    		var user_keywords=$(s_btn).parent().parent().find("input[type='text']").val().trim();
    		if(user_keywords!=''){
    			var member_select=$(s_btn).parent().parent().find("select");
    			$(member_select).find("option[value!='0']").remove();
    			$.ajax({
		            type: "POST",
		            url: admin_webroot+"users/order_search_user_information",//访问的URL地址
		            dataType: 'json',
		            data: {keywords:user_keywords},
		            success: function (data) {
		                	$(data.message).each(function(index,item){
		                		$(member_select).append("<option value='"+item['User']['id']+"'>"+item['User']['name']+" / "+item['User']['email']+"</option>");
		                	});
		            }
		        });
    		}
    }
    
    function member_set(s_obj){
    		var user_id=$(s_obj).val();
    		if(user_id!="0"){
    			var option_txt=$(s_obj).find("option:selected").text();
    			var option_txt_arr=option_txt.split('/');
    			var customer_name=option_txt_arr[0].trim();
    			var customer_email=typeof(option_txt_arr[1])!='undefined'?option_txt_arr[1].trim():"";
    			$("#customer_name").val(customer_name);
    			$(".email_info #email").val(customer_email);
    		}else{
    			$("#customer_name").val('');
    			$(".email_info #email").val('');
    		}
    }
var quote_id = "<?php echo isset($quote_products_list['0']['QuoteProduct']['quote_id'])?$quote_products_list['0']['QuoteProduct']['quote_id']:"0" ?>";
    function quote_follow_up(){
    	$("#quites-modal").modal('open');
    }
    function quote_follow_down(){
        $("#quites-close").modal('open');
    }
    function quote_follow_close () {
        var quote_text = $("#quote_text_close").val();
        $.ajax({
            url:admin_webroot+"quotes/quote_close",
            type:"POST",
            dataType:"json",
            data:{"quote_id":quote_id,"quote_message":quote_text},
            success:function (data) {
                if (data.code == 1) {
                    alert(data.message);
                    window.location.href = window.location.href;
                };
            }
        })
    }   
    function quote_follow_send () {
        var quote_text = $("#quote_text").val();
        $.ajax({
            url:admin_webroot+"quotes/quote_follow_up",
            type:"POST",
            dataType:"json",
            data:{"quote_id":quote_id,"quote_message":quote_text},
            success:function (data) {
                if (data.code == 1) {
                    alert(data.message);
                    window.location.href = window.location.href;
                };
            }
        })
    }
</script>