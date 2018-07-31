<?php if(isset($view_model)&&$view_model=='chapter'){ ?>
<div class="am-panel-group am-panel-tree" id="course_chapter_list">
    <div class="listtable_div_btm">
        <div class="am-panel-hd">
            <div class="am-panel-title">
                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><label class='am-checkbox am-success am-padding-top-0'><input type='checkbox' class='chapter_check_all' data-am-ucheck/><?php echo $ld['code']?></label></div>
                <div class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $ld['name'];?></div>
                <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center"><?php echo $ld['orderby'];?></div>
                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-center"><?php echo $ld['status']?></div>
                <div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['operate']?></div>
                <div class="am-cf"></div>
            </div>
        </div>
    </div>
    <?php if(isset($course_chapter_info) && sizeof($course_chapter_info)>0){foreach($course_chapter_info as $k=>$v){?>
        <div>
            <div class="listtable_div_top am-panel-body" >
                <div class="am-panel-bd fuji">
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
				<label class='am-checkbox am-success am-fl'><input type='checkbox' name="CourseChapter[]" value="<?php echo $v['CourseChapter']['id']?>" data-am-ucheck/></label>
				<label data-am-collapse="{parent: '#course_chapter_list', target: '#course_<?php echo $v['CourseChapter']['code']?>'}" class="am-icon <?php echo (isset($v['CourseClass'])&&!empty($v['CourseClass']))?"am-icon-plus":"am-icon-minus";?>"></label>
				<?php echo $v['CourseChapter']['code']; ?>&nbsp;
                    </div>
                    <div class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $v['CourseChapter']['name']; ?>&nbsp;</div>
                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center"><?php echo $v['CourseChapter']['orderby']; ?></div>
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-center">
                        <?php if ($v['CourseChapter']['status'] == 1) {?>
                            <span class="am-icon-check am-yes"></span>
                        <?php }elseif($v['CourseChapter']['status'] == 0){ ?>
                            <span class="am-icon-close am-no"></span>
                        <?php } ?>
                    </div>
                    <div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
                        <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit"  onclick="add_class('<?php echo $v['CourseChapter']['code']; ?>');">
                            <?php echo $ld['add'] ?>课时
                        </a>
                        <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_chapter(<?php echo $v['CourseChapter']['id']; ?>)">
                            <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                        </a>
                        <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'course_chapters/remove/<?php echo $v['CourseChapter']['id'] ?>');">
                            <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                        </a>
                    </div>
                    <div class="am-cf"></div>
                </div>
                <?php if(isset($v['CourseClass'])&& sizeof($v['CourseClass'])>0){?>
                    <div class="am-panel-collapse am-collapse am-panel-child" id="course_<?php echo $v['CourseChapter']['code']?>">
                        <?php foreach($v['CourseClass'] as $kk=>$vv){?>
                            <div class="am-panel-bd am-panel-childbd course_<?php echo $v['CourseChapter']['code']?>">
                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                                    <label class="am-checkbox am-success am-fl am-margin-right-sm <?php echo 'CourseChapter'.$v['CourseChapter']['id']; ?>"><input type='checkbox' name="CourseClass[]" value="<?php echo $vv['CourseClass']['id']?>" data-am-ucheck/></label>
                                    <label data-am-collapse="{parent: '#course_<?php echo $v['CourseChapter']['code']?>', target: '#course_<?php echo $vv['CourseClass']['code']?>'}" class="am-icon <?php echo (isset($vv['CourseClassWare']) && !empty($vv['CourseClassWare']))?"am-icon-plus":"am-icon-minus";?>"></label>
                                    <?php echo $vv['CourseClass']['code']; ?>&nbsp;
                                </div>
                                <div class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $vv['CourseClass']['name']; ?>&nbsp;</div>
                                <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center"><?php echo $vv['CourseClass']['orderby']; ?></div>
                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-center">
                                    <?php if ($vv['CourseClass']['status'] == 1) {?>
                                        <span class="am-icon-check am-yes"></span>
                                    <?php }elseif($vv['CourseClass']['status'] == 0){ ?>
                                        <span class="am-icon-close am-no"></span>
                                    <?php } ?>
                                </div>
                                <div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
                                    <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit"  onclick="add_ware('<?php echo $vv['CourseClass']['code']; ?>');"><?php echo $ld['add']; ?>课件</a>
                                    <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_class(<?php echo $vv['CourseClass']['id']; ?>)">
                                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                                    </a>
                                    <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'course_classes/remove/<?php echo $vv['CourseClass']['id'] ?>');">
                                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                    </a>
                                </div>
                                <div style="clear:both;"></div>
                                <?php if(isset($vv['CourseClassWare'])&& sizeof($vv['CourseClassWare'])>0){?>
                                    <div class="am-panel-collapse am-collapse am-panel-subchild" id="course_<?php echo $vv['CourseClass']['code']?>">
                                        <?php foreach($vv['CourseClassWare'] as $kkk=>$vvv){?>
                                            <div class="am-panel-bd am-panel-childbd am-padding-left-0 am-padding-right-0">
                                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                                                    <label class="am-checkbox am-success am-fl am-padding-top-0 am-padding-left-lg <?php echo 'CourseChapter'.$v['CourseChapter']['id'];echo ' CourseClass'.$vv['CourseClass']['id']; ?>"><input type='checkbox' name="CourseClassWare[]" value="<?php echo $vvv['CourseClassWare']['id']?>" data-am-ucheck/><span class='am-margin-left-lg'><?php echo $vvv['CourseClassWare']['code']; ?>&nbsp;</span></label>
                                                </div>
                                                <div class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $vvv['CourseClassWare']['name']; ?>&nbsp;</div>
                                                <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center"><?php echo $vvv['CourseClassWare']['orderby']; ?></div>
                                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-center">
                                                    <?php if ($vvv['CourseClassWare']['status'] == 1) {?>
                                                        <span class="am-icon-check am-yes"></span>
                                                    <?php }elseif($vvv['CourseClassWare']['status'] == 0){ ?>
                                                        <span class="am-icon-close am-no"></span>
                                                    <?php } ?>
                                                </div>
                                                <div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
                                                    <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_ware(<?php echo $vvv['CourseClassWare']['id']; ?>)">
                                                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                                                    </a>
                                                    <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'course_classes/ware_remove/<?php echo $vvv['CourseClassWare']['id'] ?>');">
                                                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                                    </a>
                                                </div>
                                                <div class="am-cf"></div>
                                            </div>
                                        <?php }?>
                                    </div>
                                <?php }?>
                            </div>
                        <?php }?>
                    </div>
                <?php }?>
            </div>
        </div>
    <?php }}else{?>
        <div>
            <div class="no_data_found" ><?php echo $ld['no_data_found']?></div>
        </div>
    <?php }?>
</div>
<script type='text/javascript'>
$(function(){
	$("#course_chapter_list input[type='checkbox']").uCheck();
	
	$("#course_chapter_list input.chapter_check_all").click(function(){
		if($(this).prop('checked')){
			$("#course_chapter_list input[type='checkbox']").uCheck('check');
		}else{
			$("#course_chapter_list input[type='checkbox']").uCheck('uncheck');
		}
	});
	
	$("#course_chapter_list input[name='CourseChapter[]']").click(function(){
		var chapter_id=$(this).val();
		if($(this).prop('checked')){
			$("#course_chapter_list label.CourseChapter"+chapter_id+" input[name='CourseClass[]']").uCheck('check');
			$("#course_chapter_list label.CourseChapter"+chapter_id+" input[name='CourseClassWare[]']").uCheck('check');
		}else{
			$("#course_chapter_list label.CourseChapter"+chapter_id+" input[name='CourseClass[]']").uCheck('uncheck');
			$("#course_chapter_list label.CourseChapter"+chapter_id+" input[name='CourseClassWare[]']").uCheck('uncheck');
		}
	});
	
	$("#course_chapter_list input[name='CourseClass[]']").click(function(){
		var class_id=$(this).val();
		if($(this).prop('checked')){
			$("#course_chapter_list label.CourseClass"+class_id+" input[name='CourseClassWare[]']").uCheck('check');
		}else{
			$("#course_chapter_list label.CourseClass"+class_ids+" input[name='CourseClassWare[]']").uCheck('uncheck');
		}
	});
});
</script>
<?php }else if(isset($view_model)&&$view_model=='ware'){ ?>
<table class='am-table'>
	<thead>
		<tr>
			<th>章节</th>
			<th>课时</th>
			<th>编码</th>
			<th>名称</th>
			<th>类型</th>
			<th>排序</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<?php if(isset($course_class_ware_list)&&sizeof($course_class_ware_list)>0){foreach($course_class_ware_list as $v){ ?>
		<tr>
			<td><?php echo isset($v['CourseChapter']['name'])?$v['CourseChapter']['name']:''; ?></td>
			<td><?php echo isset($v['CourseClass']['name'])?$v['CourseClass']['name']:''; ?></td>
			<td><?php echo $v['CourseClassWare']['code']; ?></td>
			<td><?php echo $v['CourseClassWare']['name']; ?></td>
			<td><?php echo isset($resource_info['courseware_type'][$v['CourseClassWare']['type']])?$resource_info['courseware_type'][$v['CourseClassWare']['type']]:$v['CourseClassWare']['type']; ?></td>
			<td><?php echo $v['CourseClassWare']['orderby']; ?></td>
			<td>
				<a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_ware(<?php echo $v['CourseClassWare']['id']; ?>)">
                                <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                            </a>
                            <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'course_classes/ware_remove/<?php echo $v['CourseClassWare']['id'] ?>');">
                                <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                            </a>
			</td>
		</tr>
		<?php }} ?>
	</tbody>
</table>
<?php }else if(isset($view_model)&&$view_model=='course_class_condition'){ ?>
<ul class='am-avg-sm-1'>
	<?php 
			if(isset($resource_info['course_class_condition'])&&sizeof($resource_info['course_class_condition'])>0){
			//	pr($resource_info['course_class_condition']);
				foreach($resource_info['course_class_condition'] as $k=>$v){
				$class_condition=isset($course_class_condition[$k])?$course_class_condition[$k]:'';
				$share_type_list=array('course_class'=>'课时','home'=>$ld['home'],'page'=>$ld['static_page'],'article'=>$ld['article'],'topic'=>$ld['topics']);
	?>
	<li class='am-margin-bottom-sm'>
		<div class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-padding-left-0 am-padding-right-0 am-padding-top-xs am-text-left"><?php echo $v; ?></div>
			<?php if($k=='shared_access'||$k=='share_count'){$class_condition=trim($class_condition)!=''?explode(chr(13).chr(10),$class_condition):array(); ?>
		<div class="am-u-lg-10 am-u-md-10 am-u-sm-8 am-padding-left-0">
			<div class='am-g'>
				<div class='am-u-lg-4'>
					<select class='share_type' onchange="ajax_course_share_type(this)">
						<option value=''><?php echo $ld['please_select']; ?></option>
						<?php foreach($share_type_list as $kk=>$vv){ ?>
						<option value="<?php echo $kk; ?>"><?php echo $vv; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class='am-u-lg-4'>
					<select class='share_page'>
						<?php if(isset($course_chapter_list)&&sizeof($course_chapter_list)>0){foreach($course_chapter_list as $vv){ ?>
							<optgroup label="<?php echo $vv['CourseChapter']['name'] ?>">
								<?php foreach($vv['CourseClass'] as $vv){ ?>
								<option value="<?php echo $vv['id']; ?>"><?php echo $vv['name']; ?></option>
								<?php } ?>
							</optgroup>
						<?php }} ?>
					</select>
				</div>
				<div class='am-u-lg-2'>
					<input type='text' class='share_count' value="1" /> 
				</div>
				<div class='am-u-lg-2'>
					<button type='button' class='am-btn am-btn-warning am-btn-xs' onclick="share_condition_add(this,'<?php echo $k;?>')"><span class="am-icon-plus"></span> <?php echo $ld['add']; ?></button>
				</div>
				<div class='am-cf'></div>
			</div>
			<table class='am-table am-margin-top-sm'>
				<tr>
					<td><?php echo $ld['type']; ?></td>
					<td width="60%"><?php echo $ld['page']; ?></td>
					<td>次数</td>
					<td><?php echo $ld['operate']; ?></td>
				</tr>
				<?php foreach($class_condition as $vv){$share_cond_data=explode(',',$vv); ?>
				<tr>
					<td><?php echo isset($share_type_list[$share_cond_data[0]])?$share_type_list[$share_cond_data[0]]:'-';  ?><input type='hidden' name="<?php echo 'data[Precondition]['.$k.'][share_type][]'; ?>" value="<?php echo $share_cond_data[0]; ?>" /></td>
					<td><?php echo isset($share_cond_data[1])&&!empty($share_cond_data[1])&&isset($share_page_data[$share_cond_data[0]][$share_cond_data[1]])?$share_page_data[$share_cond_data[0]][$share_cond_data[1]]:'-';  ?><input type='hidden' name="<?php echo 'data[Precondition]['.$k.'][share_page][]'; ?>" value="<?php echo isset($share_cond_data[1])?$share_cond_data[1]:0; ?>" /></td>
					<td><?php echo isset($share_cond_data[2])?$share_cond_data[2]:1; ?><input type='hidden' name="<?php echo 'data[Precondition]['.$k.'][share_count][]'; ?>" value="<?php echo isset($share_cond_data[2])?$share_cond_data[2]:1; ?>" /></td>
					<td><a href='javascript:void(0);' onclick='share_condition_remove(this)'><i class='am-icon am-icon-close am-text-danger'></i></a></td>
				</tr>
				<?php } ?>
			</table>
			<?php }else if($k=='parent_course_class'){$class_condition=trim($class_condition)!=''?explode(chr(13).chr(10),$class_condition):array(); ?>
		<div class="am-u-lg-5 am-u-md-6 am-u-sm-8 am-padding-left-0">
			<select name="<?php echo 'data[Precondition]['.$k.'][]'; ?>" id="parent_course_class" multiple>
				<?php if(isset($course_chapter_list)&&sizeof($course_chapter_list)>0){foreach($course_chapter_list as $vv){ ?>
					<optgroup label="<?php echo $vv['CourseChapter']['name'] ?>">
						<?php foreach($vv['CourseClass'] as $vv){ ?>
						<option value="<?php echo $vv['id']; ?>" <?php echo in_array($vv['id'],$class_condition)?'selected':''; ?>><?php echo $vv['name']; ?></option>
						<?php } ?>
					</optgroup>
				<?php }} ?>
			</select>
			<?php }else if($k=='task'){$class_condition=trim($class_condition)!=''?explode(chr(13).chr(10),$class_condition):array(); ?>
		<div class="am-u-lg-5 am-u-md-6 am-u-sm-8 am-padding-left-0">
			<select name="<?php echo 'data[Precondition]['.$k.'][]'; ?>" multiple>
				<?php if(isset($user_task_infos)&&sizeof($user_task_infos)>0){foreach($user_task_infos as $kk=>$vv){ ?>
					<option value="<?php echo $kk; ?>" <?php echo in_array($kk,$class_condition)?'selected':''; ?>><?php echo $vv; ?></option>
				<?php }} ?>
			</select>
			<?php }else{ ?>
		<div class="am-u-lg-5 am-u-md-6 am-u-sm-8 am-padding-left-0">
			<input type='text' name="<?php echo 'data[Precondition]['.$k.']'; ?>" value="<?php echo $class_condition; ?>" />
			<?php } ?>
		</div>
		<div class='am-cf'></div>
	</li>
	<?php }} ?>
</ul>
<style type='text/css'>
#course_class_condition>ul>li{border-bottom:1px solid #ddd;padding-bottom:1rem;}
#course_class_condition>ul>li:last-child{border-bottom:none;padding-bottom:0px;margin-bottom:15rem;}
#course_class_condition div[class*=am-u-] div[class*=am-u-]{padding-left:0px;}
</style>
<script type='text/javascript'>
$(function(){
	$("#course_class_condition select").not("#parent_course_class").each(function(){
		if($(this).find("option").length==0){
			$(this).html("<option value=''>"+j_please_select+"</option>");
		}
		$(this).selected({
			noSelectedText:j_please_select,
			maxHeight: '150px'
		});
	});
	
	$("select#parent_course_class").selected({
		noSelectedText:j_please_select,
		maxHeight: '150px'
	});
});

function ajax_course_share_type(select){
	var parentDiv=$(select).parent().parent();
	var share_type=$(select).val();
	var share_page=$(parentDiv).find("select.share_page");
	if(share_type==''||share_type=='home'){
		share_page.html("<option value=''>"+j_please_select+"</option>");
		share_page.trigger('changed.selected.amui');
	}else if(share_type=='course_class'){
		var class_select_option=$('#course_class_condition select#parent_course_class').html();
		share_page.html(class_select_option);
		share_page.trigger('changed.selected.amui');
	}else{
		share_page.html("<option value=''>"+j_please_select+"</option>");
		$.ajax({
			url: admin_webroot+"courses/ajax_course_detail",
			type:"POST",
			data:{'view_model':'course_share_type','share_type':share_type},
			dataType:"json",
			success:function(result){
				if(result.code=='1'){
					$.each(result.data,function(index,item){
						var item_value=item.value;
						if(item_value.replace(/[^\x00-\xff]/g,"**").length>20){
							item_value=getByteVal(item_value,20)+'...';
						}
						share_page.append("<option value='"+item.key+"'>"+item_value+"</option>");
					});
				}
				share_page.trigger('changed.selected.amui');
			}
    		});
	}
}

function share_condition_add(btn,cond_code){
	var parentDiv=$(btn).parent().parent();
	var share_type=$(parentDiv).find("select.share_type").val();
	var share_type_txt="";
	var share_page=$(parentDiv).find("select.share_page").val();
	var share_page_txt="";
	var share_count=$(parentDiv).find("input[type='text'].share_count").val();
	if(share_type=='')return;
	share_type_txt=$(parentDiv).find("select.share_type option:checked").text();
	if(share_type!='home'){
		if(share_page=='')return;
		share_page_txt=$(parentDiv).find("select.share_page option:checked").text();
	}else{
		share_page=0;
		share_page_txt='-';
	}
	var share_condition_table=$(parentDiv).parent().find("table");
	share_condition_table.append("<tr><td>"+share_type_txt+"<input type='hidden' name='data[Precondition]["+cond_code+"][share_type][]' value='"+share_type+"' /></td><td>"+share_page_txt+"<input type='hidden' name='data[Precondition]["+cond_code+"][share_page][]' value='"+share_page+"' /></td><td>"+share_count+"<input type='hidden' name='data[Precondition]["+cond_code+"][share_count][]' value='"+share_count+"' /></td><td><a href='javascript:void(0);' onclick='share_condition_remove(this)'><i class='am-icon am-icon-close am-text-danger'></i></a></td></tr>");
}

function share_condition_remove(link){
	if(confirm(j_confirm_delete)){
		$(link).parents("tr").remove();
	}
}

function getByteVal(val, max) {
    var returnValue = '';
    var byteValLen = 0;
    for (var i = 0; i < val.length; i++) {
        if (val[i].match(/[^\x00-\xff]/ig) != null)
        byteValLen += 2;
        else
        byteValLen += 1;
        if (byteValLen > max)
        break;
        returnValue += val[i];
    }
    return returnValue;
}
</script>
<?php } ?>