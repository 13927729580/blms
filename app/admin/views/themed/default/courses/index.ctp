<style type="text/css">
    .am-panel-title div{font-weight:bold;}
    #SearchForm{padding-top:8px;}
    #SearchForm li{margin-bottom:10px;}
    #SearchForm .am-selected-list li{margin-bottom: 0px;}
    .am-form-horizontal{padding-top:0px;}
</style>
<div>
    <?php echo $form->create('Course',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();','class'=>'am-form am-form-horizontal'));?>
    <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['type']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7  ">
                <select name="option_type_code" id='option_type_code' data-am-selected="{maxHeight:300}" >
                    <option value="-1"><?php echo $ld['all_data']?></option>
                    <?php foreach($course_type as $kk=>$vv){ ?>
                        <option value="<?php echo $vv['CourseType']['code'] ?>" <?php if($option_type_code ==$vv['CourseType']['code']){?>selected<?php }?>><?php echo $vv['CourseType']['name'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['status']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7  ">
                <select name="status" id='status' data-am-selected="{maxHeight:300}" >
                    <option value="-1"><?php echo $ld['all_data']?></option>
                    <option value="0" <?php if($status ==0){?>selected<?php }?>>无效</option>
                    <option value="1" <?php if($status ==1){?>selected<?php }?>>有效</option>
                </select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['operation_time']?></label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3" style="padding-left:0;padding-right:0.5rem;">
                <div class="am-input-group">
                <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" name="start_date_time" value="<?php echo isset($start_date_time)?$start_date_time:"";?>" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
            </div>
            </div>
            <div class=" am-text-center am-fl " style="margin-top:7px;">-</div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3" style="padding-left:0.5rem;padding-right:0;">
                <div class="am-input-group">
                <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="end_date_time" value="<?php echo isset($end_date_time)?$end_date_time:"";?>" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
            </div>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['keyword'];?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <input type="text" name="keyword" id="keyword" value="<?php echo isset($keyword)?$keyword:'';?>"/>
            </div>
        </li>
        <li >
            <label class="am-u-sm-3 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
            <div class="am-u-sm-8 am-u-md-8 am-u-sm-7 " style="float:left;">
                <input type="button"  class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>" onclick="formsubmit()" />
            </div>
        </li>
    </ul>
    <?php echo $form->end()?>
</div>
<div>
    <div class="am-text-right am-btn-group-xs" style="clear:both;margin:10px auto;">
    	<?php if($svshow->operator_privilege("user_learning"))echo $html->link("课程学习情况","/courses/user_course_detail",array("class"=>"mt am-btn am-btn-default am-seevia-btn-view"));?>
        <?php echo $html->link("分类管理","/course_categories/",array("class"=>"mt am-btn am-btn-default am-seevia-btn-view"));?>
        <?php echo $html->link("类型管理","/course_types/",array("class"=>"mt am-btn am-btn-default am-seevia-btn-view"));?>
        <?php if($svshow->operator_privilege("course_add")&&isset($can_to_add)&&$can_to_add){ ?>
        <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/courses/add'); ?>">
            <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
        </a>
        <?php } ?>
    </div>
    <div class="listtable_div_btm">
        <div class="am-g">
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">课程级别</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">课程图片</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['name'];?></div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['price'];?></div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-2">可获经验</div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">点击数</div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['status'];?></div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['operate']; ?></div>
        </div>
        <?php if(isset($course_list) && sizeof($course_list)>0){foreach($course_list as $k=>$v){?>
            <div class="am-g">
                <div class="listtable_div_top" >
                    <div style="margin:10px auto;" class="am-g">
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php if(isset($v['Course']['user_id'])&&$v['Course']['user_id']==0){echo '系统级别';}else{echo '个人级别';} ?></div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $html->image(isset($v['Course']['img'])?$v['Course']['img']:"",array('width'=>'50px','height'=>'50px')); ?></div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                            <?php echo $v['Course']['name']?><br/>
                            章节数：<?php echo $v['Course']['chapter_count']?><br/>
                            课时数：<?php echo $v['Course']['class_count']?><br/>
                            总时长：<?php echo $v['Course']['hour']?>分
                        </div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-2"><?php echo $v['Course']['price'];?></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-2"><?php echo isset($v['Course']['ecoerience_value'])?$v['Course']['ecoerience_value']:0;?></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-2"><?php echo $v['Course']['clicked'];?></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                            <?php if ($v['Course']['status'] == 1) {?>
                                <span class="am-icon-check am-yes"></span>
                            <?php }elseif($v['Course']['status'] == 0){ ?>
                                <span class="am-icon-close am-no"></span>
                            <?php } ?>
                        </div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                            <a class="mt am-btn am-btn-success am-btn-xs  am-seevia-btn-view" target='_blank' href="<?php echo $webroot.'courses/view/'.$v['Course']['id'];?>">
                                <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?>
                            </a>
                            <?php if($svshow->operator_privilege("course_edit")){ ?>
                            <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/courses/view/'.$v['Course']['id']); ?>">
                                <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                            </a>
                            <?php } if($svshow->operator_privilege("course_remove")){ ?>
                            <a class="mt am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'courses/remove/<?php echo $v['Course']['id'] ?>');">
                                <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                            </a>
                             <?php } if(isset($user_course_list[$v['Course']['id']])){ ?>
                             <a class="mt am-btn am-btn-secondary am-btn-xs  am-seevia-btn-view" href="javascript:void(0);" onclick="inivate_user(<?php echo $v['Course']['id']; ?>)">
                                <span class="am-icon-eye"></span> 邀请学习
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php }}else{?>
            <div>
                <div class="no_data_found" ><?php echo $ld['no_data_found']?></div>
            </div>
        <?php }?>
    </div>
    <?php if(isset($course_list) && sizeof($course_list)){?>
            <div class='am-margin-top-sm'><?php echo $this->element('pagers')?></div>
    <?php }?>
</div>

<div class="am-modal am-modal-no-btn" id="inivate_user">
	<div class="am-modal-dialog">
        <div class="am-modal-hd">
            <h4 class="am-popup-title">邀请学习</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <?php echo $form->create('/courses',array('action'=>'inivate_user','class'=>' am-form am-form-horizontal'));?>
    		  <input type='hidden' name="course_id" value='0' />
                <div class="am-form-group am-margin-bottom-xs">
                    <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['mobile']; ?></label>
                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				<div class="am-input-group am-margin-top-0">
					<input type="text" class="am-form-field" name="user_mobile"  value="">
					<span class="am-input-group-btn">
						<button class="am-btn am-btn-secondary am-btn-sm" type="button" onclick="ajax_inivate_user_list(this)">搜索</button>
					</span>
				</div>
                    </div>
                </div>
    		  <div class="am-form-group">
                    <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				<select name="user_id">
					<option value="0"><?php echo $ld['please_select'] ?></option>
				</select>
                    </div>
                </div>
    		  <div class="am-form-group am-hide">
                    <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['name_of_member']; ?></label>
                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
    			<input type="text" name="user_name" value="" />
                    </div>
                </div>
                <div class="am-form-group">
    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-text-left">
                    	<button type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick="ajax_inivate_user(this)">邀请</button>
    			</div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
function formsubmit(){
	var keyword=document.getElementById('keyword').value;
	var status=document.getElementById('status').value;
	var option_type_code=document.getElementById('option_type_code').value;
	var start_date_time = document.getElementsByName('start_date_time')[0].value;
	var end_date_time = document.getElementsByName('end_date_time')[0].value;
	var url = "status="+status+"&keyword="+keyword+"&option_type_code="+option_type_code+"&start_date_time="+start_date_time+"&end_date_time="+end_date_time;
	window.location.href = encodeURI(admin_webroot+"courses?"+url);
}

function inivate_user(course_id){
	$("#inivate_user input[type='hidden'][name='course_id']").val(course_id);
	$("#inivate_user").modal({closeViaDimmer:0});
}

function ajax_inivate_user_list(btn){
	var user_mobile=$("#inivate_user input[type='text'][name='user_mobile']").val().trim();
	if(user_mobile!=''){
		$("#inivate_user input[type='text'][name='user_name']").val("");
		$("#inivate_user input[type='text'][name='user_name']").parents("div.am-form-group").addClass('am-hide');
		var user_select=$("#inivate_user select[name='user_id']");
		$.ajax({
			url: admin_webroot+"courses/ajax_inivate_user_list",
			type:"POST",
			data:{'user_mobile':user_mobile},
			dataType:"json",
			success: function(result){
				if(result.code=='1'){
					user_select.find("option[value!='0']").remove();
					$.each(result.data,function(index,item){
						user_select.append("<option value='"+item['id']+"'>"+(item['first_name']!=null&&item['first_name']!=''?item['first_name']:item['name'])+'/'+item['mobile']+"</option>");
					});
					if(result.data.length==1){
						$(user_select).find("option:last-child").prop('selected',true);
					}
				}else{
					user_select.find("option[value!='0']").remove();
					user_select.append("<option value='-1'>创建该用户</option>");
					$(user_select).find("option:last-child").prop('selected',true);
					$("#inivate_user input[type='text'][name='user_name']").parents("div.am-form-group").removeClass('am-hide');
				}
			}
		});
	}
}

function ajax_inivate_user(btn){
	var user_mobile=$("#inivate_user input[type='text'][name='user_mobile']").val().trim();
	var inivate_user_id=$("#inivate_user select[name='user_id']").val();
	if(inivate_user_id=='-1'&&user_mobile!=''){
		var inivate_user_name=$("#inivate_user input[type='text'][name='user_name']").val();
		if(!/^1[3-9]\d{9}$/.test(user_mobile)){
			alert('手机号格式错误');
			return false;
		}else if(inivate_user_name==''){
			alert('请填写用户姓名');
			return false;
		}else{
			if(!confirm('确认创建该用户?')){
				return false;
			}
		}
	}else if(user_mobile==''||inivate_user_id=='0'){
		alert('请选择邀请用户');
		return false;
	}
	var PostData=$(btn).parents('form').serialize();
	$.ajax({
			url: admin_webroot+"courses/ajax_inivate_user",
			type:"POST",
			data:PostData,
			dataType:"json",
			success: function(result){
				alert(result.message);
				if(result.code=='1'){
					$("#inivate_user").modal('close');
				}
			}
		});
}
</script>