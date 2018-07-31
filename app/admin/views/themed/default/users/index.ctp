<style type="text/css">
div>a{margin-top:10px;}	
.mr{margin-right:4px;}
.am-checkbox .am-icon-checked, .am-checkbox .am-icon-unchecked, .am-checkbox-inline .am-icon-checked, .am-checkbox-inline .am-icon-unchecked, .am-radio .am-icon-checked, .am-radio .am-icon-unchecked, .am-radio-inline .am-icon-checked, .am-radio-inline .am-icon-unchecked {
    background-color: transparent;
    display: inline-table;
    left: 0;
    margin: 0;
    position: absolute;
    top: 5px;
    transition: color 0.25s linear 0s;
}
.ellipsis{margin-top:25px;}
span.user_manager_change{cursor: pointer;}

.am-checkbox.am-success{
	text-align:center;
}
.am-ucheck-icons{
	margin-left:6px;
}

.ellipsis {
    margin-top: 0px;
}
#check_box{width:100%;}
</style>
<div class="am-user">
	<div class="am-g">
	  <div class="am-cf">
		<?php echo $form->create('User',array('action'=>'/','name'=>"SearchForm",'id'=>"SearchForm","type"=>"get"));?>
		<ul class=" am-avg-md-2 am-avg-lg-3 am-avg-sm-1">
			<li  style="margin:0 0 10px 0">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text "><?php echo $ld['user_category'];?></label> 
				<div class="am-u-lg-7  am-u-md-7 am-u-sm-7">
					<select name="category_id[]" multiple data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['all_data']; ?>'}">
						<?php if(isset($UserCategory_data)&&sizeof($UserCategory_data)>0){foreach($UserCategory_data as $k=>$v){ ?>
                        			<option value="<?php echo $k; ?>" <?php echo isset($category_id)&&in_array($k,$category_id)?'selected':''; ?>><?php echo $v; ?></option>
                        			<?php }} ?>
					</select>
				</div>
			</li>
			<li  style="margin:0 0 10px 0">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text "><?php echo $ld['member_level'];?></label> 
				<div class="am-u-lg-7  am-u-md-7 am-u-sm-7">
					<select name="rank[]" multiple data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['all_data']; ?>'}">
						<?php if(isset($UserRank_data)&&sizeof($UserRank_data)>0){foreach($UserRank_data as $k=>$v){ ?>
                        			<option value="<?php echo $v['UserRank']['id']; ?>" <?php echo isset($rank)&&in_array($v['UserRank']['id'],$rank)?'selected':''; ?>><?php echo $v['UserRankI18n']['name']; ?></option>
                        			<?php }} ?>
					</select>
				</div>
			</li>
	<?php if($svshow->check_module("O2O","Product")) { ?>
	        <li>
	        <?php //pr($band_arr); ?>
	
	            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['product_brand']; ?></label>
	            <?php //pr($band_arr); ?>
	            <?php //pr($unknow_brand); ?>
	            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
	                <div class="checkbox" id = 'y1' >
	                	<select data-am-selected="{noSelectedText:'所有',btnWidth:'250px'}" name="band_id">
	                	  <option value="">所有</option>
						  <option value="-1" <?php if(isset($unknow_brand)&&$unknow_brand == -1) echo 'selected';?>><?php echo $ld['unknown_classification']?></option>
						  <?php foreach($brand_tree as $bak=>$bav){ ?>
						  <option value="<?php echo $bav['Brand']['id'];?>" <?php if(in_array($bav['Brand']['id'],$band_arr)) echo 'selected';?>><?php echo $bav['BrandI18n']['name'];?></option>
						  <?php } ?>
				</select>
	                </div>
	            </div>
	        </li>
	       <?php }?>
	       <?php if($svshow->check_module("B2C")) { ?>
			<li  style="margin:0 0 10px 0">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text ">订单总金额</label> 
				<div class="am-u-lg-3  am-u-md-4 am-u-sm-4">
					<input type="text" class="am-form-field am-input-sm" name="order_price_min" id="order_price_min" value="<?php echo @$order_price_min?>" />
				</div>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">-</div>
				<div class="am-u-lg-3  am-u-md-4 am-u-sm-4">
					<input type="text" class="am-form-field am-input-sm" name="order_price_max" id="order_price_max" value="<?php echo @$order_price_max?>" />
				</div>
			</li>
			<li  style="margin:0 0 10px 0">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text ">销售顾问</label> 
				<div class="am-u-lg-7  am-u-md-7 am-u-sm-7">
					<input type="text" class="am-form-field am-input-sm" name="order_manager" id="order_manager" value="<?php echo @$order_manager?>" />
				</div>
			</li>
			<?php }?>
			<?php if($svshow->operator_privilege("users_advanced")) { ?>
	  		<li  class="am-margin-top-xs">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text"><?php echo $ld['user_manager'] ?></label>
	 			<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
					<select name="user_manager[]" multiple data-am-selected="{maxHeight:300,noSelectedText:'<?php echo $ld['all_data'] ?>',btnWidth:'250px'}">
						<?php if(isset($Operator_list)&&sizeof($Operator_list)>0){foreach($Operator_list as $k=>$v){ ?>
						<option value="<?php echo $k; ?>" <?php echo isset($user_manager)&&in_array($k,$user_manager)?'selected':''; ?>><?php echo $v; ?></option>
						<?php }} ?>
					</select>
	  			</div>
			</li>
			<?php } ?>

			<li  style="margin:0 0 10px 0">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text "><?php echo $ld['keyword'];?></label> 
				<div class="am-u-lg-7  am-u-md-7 am-u-sm-7">
					<input type="text" class="am-form-field am-input-sm" name="user_keyword" id="user_keyword" placeholder="<?php echo $ld['user_name'].' / '.$ld['mobile'].' / '.$ld['email'] ?>" value="<?php echo @$user_keyword?>" />
				</div>
			</li>
			<li  style="margin:0 0 10px 0">
	            <label class="am-u-sm-3 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
	            <div class="am-u-sm-8 am-u-md-8 am-u-sm-7 " style="float:left;">
	                <input type="button"  class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>" onclick="formsubmit()" />
	            </div>
			</li>
		</ul>
		<?php echo $form->end();?>
        <div class="am-cf"></div>
		<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;">
			<?php if($svshow->operator_privilege("user_configs_view")){echo $html->link($ld['user_config_management'],"/user_configs/",array("class"=>"am-btn am-btn-default am-seevia-btn-view"));} ?>&nbsp;
			<?php  if($svshow->operator_privilege('users_upload')){echo $html->link($ld['batch_upload'],'/users/uploadusers',array("class"=>"am-btn am-btn-warning am-seevia-btn-view"),false,false);} ?>&nbsp;<?php if($svshow->operator_privilege('users_add')){ ?>
			<a class="am-btn am-btn-warning am-radius am-btn-sm mr"  href="<?php echo $html->url('/users/view/0'); ?>">
				<span class="am-icon-plus"></span><?php echo $ld['add'] ?>
		      </a><?php } ?>
	    </div>
	  </div>
	</div>
    <?php echo $form->create('',array('action'=>'/batch_user_print/',"name"=>"UserForm",'onsubmit'=>"return false"));?>
          <table class="am-table  table-main" style="margin-top:30px;">
        	  <thead>
	              <tr>
                    <th class="am-hide-sm-down"><label class="am-checkbox am-success" style="margin:0px;top:0px;"><input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck  type="checkbox" /></label></th>
                    <th ><?php echo $ld['member_name']?></th>
                    <th class="am-hide-sm-down" ><?php echo $ld['email']; ?> / <?php echo $ld['mobile']; ?></th>
                	 <th class="am-show-lg-only"><?php echo $ld['user_manager']; ?></th>
               	 <th class="am-show-lg-only"><?php echo $ld['user_category']; ?></th>
                    <th><?php echo $ld['status']; ?></th>
                    <th class="am-text-left"><?php echo $ld['operate']?></th>
	              </tr>
	          </thead>
	          <tbody>
	    		<?php if(isset($users_list) && sizeof($users_list)>0){foreach($users_list as $k=>$v){?>
	    		  <tr>
		                     <td class="am-hide-sm-down"><label class="am-checkbox am-success" style="margin:0px;"><input type="checkbox" name="checkboxes[]" value="<?php echo $v['User']['id']?>" data-am-ucheck /></label>
		                     </td>
		    		  	<td >
		    		  <div class="am-g">
  <div class="am-u-sm-4"><?php echo $html->image($v['User']['img01']!=''?$v['User']['img01']:'/theme/default/img/no_head.png',array('style'=>'width:60px;height:60px;display:block;margin:0 auto;')); ?></div>
<div class=" am-u-sm-8 ellipsis" title="<?php echo $v['User']['name'].'&#10;'.$v['User']['first_name']; ?>"><?php echo $v['User']['name']; ?><br /><?php echo $v['User']['first_name']; ?>&nbsp;</div>
</div>
		    		  
		    		  	</td>
		    		        <td class="am-hide-sm-down"><div class="ellipsis" title="<?php echo $v['User']['email'].'&#10;'.$v['User']['mobile']; ?>"><?php echo $v['User']['email']; ?><br /><?php echo $v['User']['mobile']; ?>&nbsp;</div>
		    		  	</td>
		    		  	<td class="am-show-lg-only"><span <?php if($svshow->operator_privilege("users_edit")){ ?> class="user_manager_change" ondblclick="user_manager_change(this,'<?php echo $v['User']['id']; ?>')"<?php } ?>><?php echo isset($Operator_list[$v['User']['operator_id']])?$Operator_list[$v['User']['operator_id']]:'-'; ?></span></td>
		    		  	<td class="am-show-lg-only"><?php echo isset($UserCategory_data[$v['User']['category_id']])?$UserCategory_data[$v['User']['category_id']]:'-'; ?><br /><?php echo isset($rank_data[$v['User']['rank']])?$rank_data[$v['User']['rank']]:''; ?></td>
	                    	<td><?php echo isset($Resource_info['verify_status'][$v['User']['verify_status']])?$Resource_info['verify_status'][$v['User']['verify_status']]:$v['User']['verify_status']; ?></div>
		    		  	</td>
		    		  	<td style="min-width:230px;" class="am-action am-btn-group-xs am-text-left">
		    		  	  <?php if($svshow->operator_privilege("users_edit")){?>
		    		  	  <a class="am-btn am-btn-default am-seevia-btn-edit  am-btn-xs am-text-secondary" href="<?php echo $html->url('/users/view/'.$v['User']['id']); ?>" ><span class="am-icon-pencil-square-o"></span>&nbsp;<?php echo $ld['edit']; ?></a>

<?php } if($svshow->operator_privilege("users_remove")){?>
	                          <a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'users/remove/<?php echo $v['User']['id'] ?>');">
	                            <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
	                          </a>
	                          <?php }?>
	                          <?php if($svshow->check_module("B2C")) { ?>
		                     <?php   if($svshow->operator_privilege("orders_add")){ ?>
		                     	 <a class="am-btn am-btn-default am-btns am-seevia-btn " href="<?php echo $html->url('/orders/add/?user_id='.$v['User']['id']); ?>" ><?php echo $ld['user_place_order']; ?></a>
		                     	 <?php } ?>
		                     	<?php }?>
		    		  	</td>
	    		  		
	    		  </tr> 
	    		<?php }}  else{?> 
	    			<tr>   
		    		 	<td colspan="5"  style=" text-align:center;height:100px;vertical-align:middle; padding-top:30px; margin-top:-24px;"><?php echo $ld['no_data_found']?>
		    		    </td>
	    		    </tr>
	    			<?php }?>	
	    					
	    		</tbody> </table> 
	    						 
          <?php if(isset($users_list) && sizeof($users_list)){?>
          <div id="btnouterlist" class="btnouterlist am-form-group">
               <div class="am-u-lg-7 am-u-md-5  am-hide-sm-down">
			                     <div class="am-fl">
			                        <label class="am-checkbox am-success" style="margin-right:5px; display: inline"><input onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)"  class="am-btn am-radius am-btn-success am-btn-sm" type="checkbox" data-am-ucheck /><?php echo $ld['select_all']?></label>&nbsp;&nbsp;
			                    </div>
		                    <div class="am-fl  am-u-lg-5 am-u-md-7  ">
		                        <select id="select_type" data-am-selected>
		            				<option value="0" selected><?php echo $ld['please_select']?></option>
		            				<?php if($svshow->operator_privilege("users_remove")){?>
		            				<option value="operation_delete"><?php echo $ld['batch_delete']?></option>
		            				<?php }?>
		            				<?php if($svshow->operator_privilege('email_lists_view')){?>
		            				<option value="search_result"><?php echo $ld['search_results_subscribe'] ?></option>
		            				<?php }?>
		            				<option value="export_act"><?php echo $ld['batch_export']?></option>
		            			</select>
		                    </div> 
                    	<div  class=" am-u-lg-3 am-u-md-3  am-u-end"><input type="button" value="<?php echo $ld['submit']?>" class="am-btn am-btn-sm am-btn-danger am-btn-radius"  onclick="submit_operations()" /></div>
               </div>
               <div class="am-u-lg-5 am-u-md-6 am-u-sm-12"><?php echo $this->element('pagers'); ?></div>
               <div class="am-cf"></div>
          </div>
          <?php } ?>
     <?php echo $form->end();?>	
</div>


<div class="am-modal am-modal-no-btn tablemain" tabindex="-1" id="placement" >
    <div class="am-modal-dialog">
        <div class="am-modal-hd">
            <?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['file_allocation'].' '.$ld['templates']:$ld['file_allocation'].$ld['templates'];?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
	            <form id='placementform3' method="POST" class="am-form am-form-horizontal">
	                <div class="am-form-group">
	                    <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">
	                        <?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['choice_export'].' '.$ld['templates']:$ld['choice_export'].$ld['templates'];?>:
	                    </label>
	                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
	                        <select name="profilegroup" id="profilegroup" data-am-selected>
	                            <option value="0"><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['please_select'].' '.$ld['templates']:$ld['please_select'].$ld['templates'];?></option>
	                        </select>&nbsp;&nbsp;&nbsp;&nbsp;<em style="color:red;">*</em>
	                    </div>
	                </div>
	                <div><input type="button" id="mod" class="am-btn am-btn-success am-btn-sm am-radius"  name="changeprofileButton" value="<?php echo $ld['confirm']?>" onclick="javascript:changeprofile();"></div>
	            </form>
        </div>
    </div>
</div>

<style type="text/css">
.am-user{padding:10px 0;}
.am-user .am-table > thead > tr > th:last-child{width:20%;text-align:right;}
.am-user .am-table > tbody > tr > td:last-child{text-align:right;}
.am-user .am-table > tbody > tr > td:first-child img{width:50px;height:50px;}
.am-user .am-table > tbody > tr > td:first-child span{margin:0 5px;}
.ellipsis{width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
</style>
<script type="text/javascript">
	ct_checkbox();
    function ck_checkbox(){
        var dropdown = $('#check_box'),
            data = dropdown.data('amui.dropdown');
        if(data.active){
            dropdown.dropdown('close');
        }
        var str=document.getElementsByName("box");
        var leng=str.length;
        var chestr="";
        for(var i=0;i<leng;i++){
            if(str[i].checked == true)
            {
                chestr+=str[i].value+",";
            };
        };
        return chestr;
    }
    
function formsubmit(){
	// var user_keyword=document.getElementById('user_keyword').value;
	// var order_price_min=document.getElementById('order_price_min').value;
	// var order_price_max=document.getElementById('order_price_max').value;
	// var order_manager=document.getElementById('order_manager').value;
	// var user_manager = $('select[name="user_manager[]"]').value;
	// var ta = ck_checkbox();
	// var str = '';
	// var data = $("#SearchForm").serialize();
	// //alert(data);
	// str +="&"+"band_id=" +ta.substring(ta,ta.length-1);
	// var url = "user_keyword="+user_keyword+"&order_price_min="+order_price_min+"&order_price_max="+order_price_max+"&user_manager="+user_manager+"&order_manager="+order_manager+str;
	//window.location.href = encodeURI(admin_webroot+"users?"+data);
	// $.ajax({
	// 	url: admin_webroot+"users",
	// 	type:"GET",
	// 	data:data,
	// 	dataType:"json",
	// 	success: function(data){
			
 //  		}
 //  	});
	document.SearchForm.action=admin_webroot+"users/index";
    document.SearchForm.onsubmit= "";
    document.SearchForm.submit();
}

    $("#check_box  input[type='checkbox']").click(function(){
        ct_checkbox();
    });

    var all=$('#y1 .a1');
    bll=$('#y1 .b1'),
        cll=$('#y1 .btn'),
        allclick = function(){
            if(bll.prop("class")!="b1"){bll.removeClass('c1');all.removeClass('up');
            }else{
                bll.addClass('c1');all.addClass('up');}
        },
        removeclick = function(){
            all.removeClass('up');
            bll.removeClass('c1');
        };
    var checkbox =$('#y1 .b1 .checkbox');
    select = $('#y1 .b1 #select');
    $("#select").click(function(){
        $(".bb0 input[type='checkbox']").prop("checked",$(this).prop('checked'));
    });
    cll.on('click', removeclick);
	all.on('click', allclick);

    function ct_checkbox(){
        var ck_txt_arr=new Array();
        $(".bb0 input[type='checkbox']:checked").each(function(){
            var cl_value=$(this).val();
            if(cl_value!="-1"){
                var ck_html=$(this).parent().html().replace(/<[^>]+>/g,"").trim();
                ck_html=ck_html.replace("--","").replace("--","");
                ck_txt_arr.push(ck_html);
            }
        });
        if(ck_txt_arr.length>0){
            $("#check_box button span").html(ck_txt_arr.join(";"));
        }else{
            $("#check_box button span").html("<?php echo $ld['all_data']; ?>");
        }
    }

function change_user_status(type,user_id){
	if(confirm("<?php echo $ld['confirm_verify_the_user'];?>")){
		$.ajax({url: admin_webroot+"users/user_status/"+type+"/"+user_id,
			type:"POST",
			data:{},
			dataType:"json",
			success: function(data){
				try{
					window.location.reload(true); 
					//$("#send_coupon_list").html(data);
				}catch (e){
					alert(j_object_transform_failed);
				}
	  		}
	  	});
  	}
}

function submit_operations(){
    var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var opration_select_type = document.getElementById("select_type").value;
    if(opration_select_type=='0'){
		alert(j_select_operation_type+" !");
		return;
	}
    var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
		}
	}
	if(opration_select_type=='operation_delete'&&postData=="" ){
		alert(j_select_user);
		return;
	}
	if(opration_select_type=="export_act"&&postData==''){
		alert(j_select_user);
		return false;
	}else if(opration_select_type=="export_act"){
        var func="/profiles/getdropdownlist/";
		var group="User";
        $.ajax({url: admin_webroot+func,
			type:"POST",
			data:{group:group},
			dataType:"json",
			success: function(result){
				try{
					if(result.flag == 1){
    					var result_content = (result.flag == 1) ? result.content : "";
                        if(result_content!=""){
                            strbind(result_content);
                        }
                        $("#placement").modal("open");
    				}
    				if(result.flag == 2){
    					alert(result.content);
    				}
				}catch(e){
					alert(j_object_transform_failed);
					alert(o.responseText);
				}
	  		}
	  	});
    }else if(opration_select_type=='search_result'){
        if(confirm("确定订阅杂志")){
			search_result();
		}
    }else if(opration_select_type=='operation_delete'){
        if(confirm(j_confirm_delete_user)){
            $.ajax({
                url: admin_webroot+"users/batch_operations/",
    			type:"POST",
    			data:postData,
    			dataType:"html",
    			success: function(result){
    				window.location.href = window.location.href;
    	  		}
    	  	});
        }
    }
}

function strbind(arr){
	//先清空下拉中的值
	var profilegroup=document.getElementById("profilegroup");
    $("#profilegroup option").remove();
    var optiondefault=document.createElement("option");
	    profilegroup.appendChild(optiondefault);
	    optiondefault.value="0";
	    optiondefault.text=j_templates;
	for(var i=0;i<arr.length;i++){
		var option=document.createElement("option");
	    profilegroup.appendChild(option);
	    option.value=arr[i]['Profile']['code'];
	    option.text=arr[i]['ProfileI18n']['name'];
	}
	$("profilegroup").trigger('changed.selected.amui');
}

function changeprofile(){
	var select_type = document.getElementById("select_type");
	var code=document.getElementById("profilegroup").value;
	if(code==0){
		alert("请选择导出方式");
		return false;	
	}
	var strsel = select_type.options[select_type.selectedIndex].text;
	if(confirm(confirm_exports+" "+strsel+"？")){
		if(select_type.value=='search_result'){
			search_result(code);
		}else if(select_type.value=='export_act'){
			export_act(code);
		}
	}
    $("#placement").modal("close");
}
function export_act(code){
	
	document.UserForm.action=admin_webroot+"users/export_act/"+code;
    document.UserForm.onsubmit= "";
    document.UserForm.submit();
}

function search_result(){
	var form=document.getElementById('SearchForm');
	form.action='/admin/users/index/?email_flag=1';
	form.method="post";
	form.submit();
}

function user_manager_change(span_obj,user_id){
	var old_text=$(span_obj).text().trim();
	$(span_obj).data("old_text",old_text);
	var operator_text="";
	var operator_value="";
	if(old_text!="-"){operator_text=old_text;}
	var operator_list=$("select[name='user_manager']").html();
	var user_manager="<select>"+operator_list+"</select>";
	$(span_obj).html(user_manager);
	var user_manager_select=$(span_obj).find("select");
	$(user_manager_select).find("option").each(function(){
		if($(this).text()==operator_text){
			$(this).attr('selected',true);
			operator_value=$(this).val();
		}
	});
	$(user_manager_select).selected({'maxHeight':300,'noSelectedText':j_please_select});
	$(user_manager_select).change(function(){
		var operator_id=$(this).val();
		var operator_name="";
		$(this).find("option").each(function(){
			if($(this).val()==operator_id){
				operator_name=$(this).text();
			}
		});
		if(operator_id==''){operator_name="-";}
		if(operator_id!=operator_value){
			$.ajax({
	                url: admin_webroot+"users/user_manager_change",
	    			type:"POST",
	    			data:{'user_id':user_id,'operator_id':operator_id},
	    			dataType:"json",
	    			success: function(data){
	    				if(data.code=='1'){
	    					$(span_obj).html(operator_name);
	    				}else{
	    					alert(date.msg);
	    				}
	    	  		}
	    	  	});
		}
	});
}
</script>

