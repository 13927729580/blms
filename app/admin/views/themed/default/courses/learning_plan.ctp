<div class='am-g learning_plan_detail'>
	<div class='am-g am-form'>
		<div class='am-u-sm-6'>
			<select multiple>
				<?php if(isset($course_class_tree)&&sizeof($course_class_tree)>0){foreach($course_class_tree as $v){if(!isset($v['CourseClass'])||empty($v['CourseClass']))continue; ?>
				<optgroup label="<?php echo $v['CourseChapter']['name']; ?>">
					<?php foreach($v['CourseClass'] as $vv){if(isset($learning_plan_class_ids)&&in_array($vv['id'],$learning_plan_class_ids)){ ?>
					<option value="<?php echo $vv['id']; ?>" disabled><?php echo $vv['name']; ?></option>
					<?php }else{ ?>
					<option value="<?php echo $vv['id']; ?>"><?php echo $vv['name']; ?></option>
					<?php }} ?>
				</optgroup>
				<?php }} ?>
			</select>
		</div>
		<div class='am-u-sm-3'><label class='am-u-sm-5 am-padding-left-0 am-padding-right-0 am-padding-top-xs'>周期:</label><div class='am-u-sm-7 am-padding-0'><input type='text' id='learning_plan_day' placeholder='天数' value='1' /></div><div class='am-cf'></div></div>
		<div class='am-u-sm-3'><button type='button' class='am-btn am-radius am-btn-sm am-btn-warning' onclick="add_learning_plan(this,<?php echo $course_data['Course']['id']; ?>)"><span class="am-icon-plus"></span><?php echo $ld['add']; ?></button></div>
		<div class='am-cf'></div>
	</div>
	<div class='am-g'>
		<table class='am-table'>
			<thead>
				<tr>
					<th class='am-text-center'>周期</th>
					<th>章节</th>
					<th>课时</th>
					<th>状态</th>
					<th class='am-hide'>排序</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(isset($course_learning_plan_list)&&sizeof($course_learning_plan_list)>0){foreach($course_learning_plan_list as $k=>$v){ ?>
				<?php if(is_array($v)&&sizeof($v)>0){foreach($v as $kk=>$vv){ ?>
					<tr>
						<?php if($kk==0){ ?>
						<td rowspan="<?php echo is_array($v)?sizeof($v):'1'; ?>" style="text-align:center;vertical-align:middle;">第<?php echo $k; ?>周</td>
						<?php } ?>
						<td><?php echo $vv['CourseChapter']['name']; ?></td>
						<td><?php echo $vv['CourseClass']['name']; ?></td>
						<td><?php if ($vv['CourseLearningPlan']['status'] == 1) {?>
							<span class="am-icon-check am-text-success" onclick="modify_learning_plan_status(this,<?php echo $vv['CourseLearningPlan']['id']; ?>,<?php echo $course_data['Course']['id']; ?>)"></span>
							<?php }elseif($vv['CourseLearningPlan']['status'] == 0){ ?>
							<span class="am-icon-close am-text-danger"  onclick="modify_learning_plan_status(this,<?php echo $vv['CourseLearningPlan']['id']; ?>,<?php echo $course_data['Course']['id']; ?>)"></span>
							<?php } ?>
						</td>
						<td class='am-hide'><?php echo $vv['CourseLearningPlan']['orderby']; ?></td>
						<td><a href='javascript:void(0)' onclick="remove_learning_plan(<?php echo $course_data['Course']['id']; ?>,<?php echo $vv['CourseLearningPlan']['id']; ?>)"><span class='am-icon am-icon-times am-text-danger'></span></a>&nbsp;</td>
					</tr>
				<?php }} ?>
				</tr>
				<?php }} ?>
			</tbody>
		</table>
		&nbsp;
	</div>
</div>
<style type="text/css">
div.learning_plan_detail{text-align:left;min-height:400px;}
div.learning_plan_detail div.am-g{width:100%;margin:0 auto;}
div.learning_plan_detail div.am-g:nth-child(2){min-height:400px;max-height:400px;overflow-y: auto;}
ul.am-tree{padding-left: 0;overflow-x: auto;overflow-y: auto;position: relative;list-style: none;min-height:400px;}
ul.am-tree .am-tree-item{position: relative;cursor: pointer;}
ul.am-tree .am-tree-item a{color:#000;}
ul.am-tree .am-tree-item span.am-icon{cursor:pointer;}
ul.am-tree-branch-children{padding-left:1rem;list-style: none;margin:0.5rem 0;}
ul.am-tree .am-tree-item a.am-disabled{text-decoration:line-through;color:#ddd;}
</style>
<script type='text/javascript'>
$(function(){
	$("ul.am-tree>li.am-tree-item>span.am-icon,ul.am-tree>li.am-tree-item>a.am-tree-item-name").click(function(){
		var tree_item=$(this).parent('li');
		var tree_icon=$(tree_item).children('span.am-icon');
		var tree_link=$(tree_item).children('a.am-tree-item-name');
		var tree_children=$(tree_item).children('ul.am-tree-branch-children');
		if(tree_icon.hasClass('am-icon-minus-square-o')){
			tree_children.hide();
			tree_icon.removeClass('am-icon-minus-square-o').addClass('am-icon-plus-square-o');
		}else{
			tree_children.show();
			tree_icon.removeClass('am-icon-plus-square-o').addClass('am-icon-minus-square-o');
		}
	});
});

function add_learning_plan(btn,course_id){
	var add_form=$(btn).parents('div.am-form');
	var course_class_id=[];
	var course_class_select=$(add_form).find('select option:selected');
	$(course_class_select).each(function(){
		course_class_id.push($(this).val());
	});
	var learning_plan_day=$("#learning_plan_day").val();
	if(course_class_id.length==0)return;
	$.ajax({
		url: admin_webroot+"courses/learning_plan/"+course_id,
		type:"POST",
		data:{'course_class_id':course_class_id,'learning_plan_day':learning_plan_day},
		dataType:"json",
		success: function(data){
			alert(data.message);
			if(data.code=='1'){
				learning_plan(null,course_id);
			}
		}
	});
}

function remove_learning_plan(course_id,learning_plan_id){
	if(confirm(j_confirm_delete)){
		$.ajax({
			url: admin_webroot+"courses/remove_learning_plan/"+course_id,
			type:"POST",
			data:{'learning_plan_id':learning_plan_id},
			dataType:"json",
			success: function(data){
				alert(data.message);
				if(data.code=='1'){
					learning_plan(null,course_id);
				}
			}
		});
	}
}

function modify_learning_plan_day(obj,learning_plan_id,course_id){
	var tag = obj.firstChild.tagName;
	if (typeof(tag) != "undefined" && (tag.toLowerCase() == "input")){
   		return;
  	}
  	var org = obj.innerHTML;
  	var val = Browser.isIE ? obj.innerText : obj.textContent;
  	
	/* 创建一个输入框 */
	var txt = document.createElement("INPUT");
	txt.type = "text" ;
	txt.value = (val == '')|| (val == '0')? '1' : val;
	txt.size = "3" ;
  	
  	/* 隐藏对象中的内容，并将输入框加入到对象中 */
	obj.innerHTML = "";
	obj.appendChild(txt);
	txt.focus();
	
	/* 编辑区输入事件处理函数 */
	txt.onkeypress = function(e){
		var evt = Utils.fixEvent(e);
		var obj = Utils.srcElement(e);
		if(evt.keyCode == 13){
			obj.blur();
			return false;
		}
		if(evt.keyCode == 27){
			obj.parentNode.innerHTML = org;
		}
	 }
	
	/* 编辑区失去焦点的处理函数 */
	txt.onblur = function(e){
		if(Utils.trim(txt.value).length > 0 || true){
			$.ajax({
				cache: true,
				type: "POST",
				url:admin_webroot+"courses/modify_learning_plan/"+course_id,
				data:{'learning_plan_id':learning_plan_id,'learning_field':'day','learning_field_value':Utils.trim(txt.value)},
				async: false,
				dataType:"json",
				success: function(data) {
					try{
						if(data.code=='1'){
							var result_content = Utils.trim(txt.value);
							if(Browser.isIE){
								obj.innerText=Utils.trim(result_content);
							}else{
								obj.innerHTML=Utils.trim(result_content);
							}
						}else{
							alert(data.message);
							obj.innerHTML = org;
						}
					}catch(e){
						alert(j_object_transform_failed);
						obj.innerHTML = org;
					}
				}
			});
		}else{
	  		alert(j_empty_content);
	    		obj.innerHTML = org;
	    	}
	}
}

function modify_learning_plan_status(obj,learning_plan_id,course_id){
	var status_flag=$(obj).hasClass('am-icon-close')?'1':'0';
	
	$.ajax({
		cache: true,
		type: "POST",
		url:admin_webroot+"courses/modify_learning_plan/"+course_id,
		data:{'learning_plan_id':learning_plan_id,'learning_field':'status','learning_field_value':status_flag},
		async: false,
		dataType:"json",
		success: function(data) {
			try{
				if(data.code=='1'){
					if(status_flag=='1'){
						$(obj).removeClass('am-icon-close am-text-danger').addClass('am-icon-check am-text-success');
					}else{
						$(obj).removeClass('am-icon-check am-text-success').addClass('am-icon-close am-text-danger');
					}
				}else{
					alert(data.message);
				}
			}catch(e){
				alert(j_object_transform_failed);
				obj.innerHTML = org;
			}
		}
	}); 
}
</script>