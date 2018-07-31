<?php
/*****************************************************************************
 * SV-Cart 公共类型树
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
 *****************************************************************************/
?>
<style type="text/css">
.product_type_tree div[class*="am-u-"]:last-child{float:left;}
#changeAttr>div[class*="am-u-"]{margin-right:8px;margin-bottom:8px;}
#changeAttr button.am-dropdown-toggle{width:100%;text-align:left;}
#changeAttr .attribute_list ul{margin-top:5px;padding-top:15px;height:300px;overflow-x:hidden;overflow-y:scroll;}
</style>
<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1 product_type_tree">
   <li>
        <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['attribute'];?></label>
        <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
            <?php if(!empty($product_type_tree)){?>
                <select name="product_type_id" id="product_type_id" onchange="getAttr()">
                    <option value="0"><?php echo $ld['all_data']?></option>
                    <?php if(isset($product_type_tree) && sizeof($product_type_tree)>0){foreach($product_type_tree as $k=>$v){?>
                        <option value="<?php echo $v['ProductType']['id']?>" <?php if($product_type_id == $v['ProductType']['id']){?>selected<?php }?>><?php echo $v['ProductTypeI18n']['name']?></option>
                    <?php }}?>
                </select>
            <?php }?>
        </div>
   </li>
   <li <?php if(empty($attr_cate)){echo 'style="display:none;"';} ?>>
        <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['related_attributes'];?></label>
        <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
            <select name="attr_cate_id" id='attr_cate_id' >
                <option value=""><?php echo $ld['all_data']; ?></option>
                <?php if(!empty($attr_cate)){foreach($attr_cate as $atk=>$atv){?>
                    <option value="<?php echo $atv['CategoryProduct']['id'];?>" <?php if(isset($attr_cate_sel)&& $attr_cate_sel==$atv['CategoryProduct']['id']){ echo "selected";}?>><?php echo $atv['CategoryProductI18n']['name'];?></option>
                <?php }}?>
            </select>
        </div>
    </li>
</ul>
<ul class="am-avg-lg-1 am-avg-md-1 am-avg-sm-1">
	<li>
		<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label">&nbsp;</label>
		<div class="am-u-lg-10 am-u-md-10 am-u-sm-10" id='changeAttr'></div>
	</li>
</ul>
<script type="text/javascript">
if(typeof(getAttr)!='undefined'){
	getAttr();
}

/*
	获取属性关联分类
*/
function getAttr(){
	getAttronlond();
	if(document.getElementById('attr_cate_id')==null)return false;
	var product_type_id=0;
	if(document.getElementById('product_type_id')){
		product_type_id=document.getElementById('product_type_id').value;
	}
	$.ajax({
		url:admin_webroot+"products/getCate/",
		type:"POST",
		data:{'attrval':product_type_id},
		dataType:"json",
		success:function(data){
			var liObj=document.getElementById("attr_cate_id").parentNode.parentNode;
			if(data.length>0){
				var attr_html="";
				var attr_cate_id_value=document.getElementById("attr_cate_id").value;
				document.all('attr_cate_id').options.length = 0;
				document.getElementById("attr_cate_id").options.add(new Option("<?php echo $ld['all_data']; ?>", 0));
				for(var i=0;i<data.length;i++){
					var attr_value=data[i].name.split("\r\n");
					document.getElementById("attr_cate_id").add(new Option(data[i].name,data[i].id,true,attr_cate_id_value==data[i].id));
				}
				liObj.style.display="inline-block";
			}else{
				liObj.style.display='none';
			}
		}
	});
}

function getAttronlond(){
	if(document.getElementById('changeAttr')==null)return false;
	var changeAttr=document.getElementById('changeAttr');
	var attr_val=0;
	if(document.getElementById('product_type_id')){
		attr_val=document.getElementById('product_type_id').value;
	}
	var attr_cate_id=0;
	if(document.getElementById('attr_cate_id')){
		attr_cate_id=document.getElementById('attr_cate_id').value;
	}
	var attr_value='';
	if(document.getElementById('attr_value')){
		attr_value=document.getElementById('attr_value').value;
	}
	changeAttr.innerHTML="";
	if(attr_val==0)return false;
	var select_attr=attr_value.split(",");//当前搜索项
	var attr_Data={'attrval':attr_val,'attr_cate_id':attr_cate_id};
	$.ajax({
		url:admin_webroot+"productstypes/getAttrInfo",
		type:"POST",
		data:attr_Data,
		dataType:"json",
		success:function(data){
			var attr_data=typeof(data.msg)!='undefined'?data.msg:[];
			$.each(attr_data,function(attr_key,attr_info){
				var attr_id=attr_info.id;
				var attr_name=attr_info.name;
				
				var attr_parent_div=document.createElement('div');
				attr_parent_div.setAttribute('class','am-u-lg-4');
				attr_parent_div.setAttribute('id','attribute_list_parent_'+attr_id);
				
				var attr_div=document.createElement('div');
				attr_div.setAttribute('class','am-dropdown attribute_list');
				attr_div.setAttribute('id','attribute_list_'+attr_id);
				
				var attr_title_btn="<button type='button' value='"+attr_name+"' class='am-selected-btn am-btn am-dropdown-toggle am-btn-default am-btn-sm' data-am-dropdown-toggle><span class='am-selected-status am-fl'>"+attr_name+"</span> <i class='am-selected-icon am-icon-caret-down'></i></button>";
				$(attr_div).append(attr_title_btn);
				
				var attr_list=document.createElement('ul');
				attr_list.setAttribute('class','am-dropdown-content am-avg-lg-1 am-avg-md-1 am-avg-sm-1');
				var attr_option_value=typeof(attr_info.value)!='undefined'&&attr_info.value!=''?attr_info.value:'';
				if(attr_option_value.trim()!=''){
					attr_option_value=attr_option_value.trim();
					var attr_options=attr_option_value.split("\r\n");
					$.each(attr_options,function(index,item){
						var attr_option_info=item.split("|");
						var option_value=attr_id+";"+escape(attr_option_info[0]);
						var option_text=attr_option_info[1];
						var is_selected=$.inArray(option_value, select_attr);
						
						var attr_li=document.createElement('li');
						attr_li.innerHTML="<label class='am-checkbox am-success'><input type='checkbox' onclick='attr_checkbox_selected()' name='attr_box' value='"+option_value+"'  "+(is_selected>=0?'checked':'')+"/>"+option_text+"</label>";
						attr_list.appendChild(attr_li);
					});
				}
				attr_div.appendChild(attr_list);
				attr_parent_div.appendChild(attr_div);
				changeAttr.appendChild(attr_parent_div);
				$('#attribute_list_'+attr_id+" input[type='checkbox']").uCheck();
				$('#attribute_list_'+attr_id).dropdown({justify: '#attribute_list_parent_'+attr_id});
			});
			attr_checkbox_selected();
		}
	});
}

function attr_checkbox_selected(){
	$(".attribute_list").each(function(){
		var attr_name=$(this).find("button").attr('value');
		var ck_txt_arr=new Array();
		$(this).find("input[type='checkbox']:checked").each(function(){
			var cl_value=$(this).val();
			if(cl_value!="-1"){
				var ck_html=$(this).parent().html().replace(/<[^>]+>/g,"").trim();
				ck_html=ck_html.replace("--","").replace("--","");
				ck_txt_arr.push(ck_html);
			}
		});
		if(ck_txt_arr.length>0){
			$(this).find("button span").html(ck_txt_arr.join(" ; "));
		}else{
			$(this).find("button span").html(attr_name);
		}
	});
}
</script>