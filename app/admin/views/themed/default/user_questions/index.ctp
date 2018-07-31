<style type="text/css">
    .am-panel-title div{font-weight:bold;}
    #SearchForm{padding-top:8px;}
    #SearchForm li{margin-bottom:10px;}
    #SearchForm .am-selected-list li{margin-bottom: 0px;}
    .am-form-horizontal{padding-top:0px;}
</style>
<div>
    <?php echo $form->create('Evaluation',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();','class'=>'am-form am-form-horizontal'));?>
    <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['type']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7  ">
                <select name="question_type" id='question_type' data-am-selected="{maxHeight:300}" >
                    <option value="-1"><?php echo $ld['all_data']?></option>
                    <option value="0"<?php if($question_type ==0){echo "selected";}?>>单选</option>
                    <option value="1"<?php if($question_type ==1){echo "selected";}?>>多选</option>
                </select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['status']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7  ">
                <select name="status" id='status' data-am-selected="{maxHeight:300}" >
                    <option value="-1"><?php echo $ld['all_data']?></option>
                    <option value="0" <?php if($status ==0){?>selected<?php }?>>未导入</option>
                    <option value="1" <?php if($status ==1){?>selected<?php }?>>已导入</option>
                </select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['keyword'];?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <input type="text" name="keyword" id="keyword" value="<?php echo isset($keyword)?$keyword:'';?>"/>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['user_name'];?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <input type="text" name="user_name" id="user_name" value="<?php echo isset($user_name)?$user_name:'';?>"/>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">题库标签</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <input type="text" name="tag" id="tag" value="<?php echo isset($tag)?$tag:'';?>"/>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['operation_time']?></label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3" style="padding-right:0.5rem;width:37%;">
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
    <div class="listtable_div_btm">
        <div class="am-g">
            <div class="am-u-lg-1 am-u-md-1" ><label class="am-checkbox am-success" ><input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck  type="checkbox" />&nbsp;ID</label></div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['user_name'];?></div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">题库标签</div>
            <div class="am-u-lg-3 am-u-md-3 am-u-sm-2">题目</div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['type'];?></div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">上传时间</div>
            <div class="am-u-lg-1 am-u-md-1"><?php echo $ld['status'];?></div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['operate']; ?></div>
        </div>
        <?php if(isset($question_list) && sizeof($question_list)>0){foreach($question_list as $k=>$v){?>
            <div class="am-g">
                <div class="listtable_div_top" >
                    <div style="margin:10px auto;" class="am-g">
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><label class="am-checkbox am-success" style="top: 0px; margin: 0px;"><input type="checkbox" name="checkboxes[]" value="<?php echo $v['UserQuestion']['id']?>"  data-am-ucheck />&nbsp;<?php echo $v['UserQuestion']['id'];?></label></div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo !empty($v['User']['name'])?$v['User']['name']:"--";?></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo !empty($v['UserQuestion']['tag'])?htmlspecialchars($v['UserQuestion']['tag']):'-';?></div>
                        <div class="am-u-lg-3 am-u-md-3 am-u-sm-2"><?php echo htmlspecialchars($v['UserQuestion']['name'])?></div>
                		<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                            <?php if ($v['UserQuestion']['question_type'] == 1) {?>
                                多选
                            <?php }elseif($v['UserQuestion']['question_type'] == 0){ ?>
                                单选
                            <?php } ?>
                        </div>
                		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['UserQuestion']['created'];?></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                            <?php if ($v['UserQuestion']['status'] == 1) {?>
                                已导入
                            <?php }elseif($v['UserQuestion']['status'] == 0){ ?>
                                未导入
                            <?php } ?>
                        </div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                            <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/user_questions/view/'.$v['UserQuestion']['id']); ?>">
                                <span class="am-icon-pencil-square-o"></span> <?php echo $ld['view']; ?>
                            </a>
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
    <?php if(isset($question_list) && sizeof($question_list)){?>
        <div id="btnouterlist" class="btnouterlist am-form-group am-hide-sm-only">
            <div class="am-u-lg-6 am-u-md-12 am-u-sm-12">
                <div class="am-fl">
                    <label class="am-checkbox am-success" style="margin:5px 5px 5px 0px;"><input onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)"  class="am-btn am-radius am-btn-success am-btn-sm" type="checkbox" data-am-ucheck /><?php echo $ld['select_all']?></label>&nbsp;
                </div>
                <div class="am-fl">
                    <select id="barch_opration_select" data-am-selected>
                        <option value="0">批量取消</option>
                    	<option value="2">批量删除</option>
                        <option value="1">批量导入题库</option>
                    </select>
                </div>
                <div class="am-fl">
                    <input type="button" id="btn" value="<?php echo $ld['submit']?>" class="am-btn am-btn-sm am-btn-danger am-btn-radius" onclick="batch_question()" />&nbsp;
                </div>
            </div>
            <div><?php echo $this->element('pagers')?></div>
            <div class="am-cf"></div>
        </div>
    <?php }?>
</div>
<div class="am-modal am-modal-no-btn tablemain" tabindex="-1" id="select_evaluation" style="width:640px;">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">
             选择评测
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            <form id='placementform3' method="POST" class="am-form am-form-horizontal">
                <div class="am-form-group">
                    <input style="width:200px;float:left;margin-right:5px;" type="text" name="evaluation_keyword" id="evaluation_keyword" /> <input  type="button" class="am-btn am-btn-success am-radius am-btn-sm " value="<?php echo $ld['search']?>" onclick="searchevaluation();" />
                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                        <select id="evaluation_select" data-am-selected>
	                        <option value="-1">请选择</option>
	                    </select>
                    </div>
                </div>
                <div><input type="button" id="mod" class="am-btn am-btn-success am-btn-sm am-radius"  name="changeprofileButton" value="<?php echo $ld['confirm']?>" onclick="javascript:set_question();"></div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function formsubmit(){
        var keyword=document.getElementById('keyword').value;
        var question_type=document.getElementById('question_type').value;
        var tag=document.getElementById('tag').value;
        var status=document.getElementById('status').value;
        var user_name=document.getElementById('user_name').value;
        var start_date_time = document.getElementsByName('start_date_time')[0].value;
        var end_date_time = document.getElementsByName('end_date_time')[0].value;
        var url = "user_name="+user_name+"&keyword="+keyword+"&question_type="+question_type+"&tag="+tag+"&status="+status+"&start_date_time="+start_date_time+"&end_date_time="+end_date_time;
        window.location.href = encodeURI(admin_webroot+"user_questions?"+url);
    }

    //批量操作
    function batch_question(){
        var bratch_operat_check = document.getElementsByName("checkboxes[]");
        var barch_opration_select = document.getElementById("barch_opration_select");
        var strsel = barch_opration_select.options[barch_opration_select.selectedIndex].text;
        var checkboxes=new Array();
        for(var i=0;i<bratch_operat_check.length;i++){
            if(bratch_operat_check[i].checked){
                checkboxes.push(bratch_operat_check[i].value);
            }
        }
        if(barch_opration_select.value == 0 && checkboxes.length != 0){
        	if(confirm(confirm_exports+" "+strsel+"？")){
	            $.ajax({
	                url:admin_webroot+"user_questions/changeStatus/",
	                type:"POST",
	                data:{ids:checkboxes},
	                dataType:"json",
	                success:function(data){
	                    try{
	                        alert(data.msg);
	                    }catch (e){
	                        alert(j_object_transform_failed);
	                    }
	                    window.location.href = window.location.href;
	                }
	            });
            }
        }else if(barch_opration_select.value == 2 && checkboxes.length != 0){
        	if(confirm(confirm_exports+" "+strsel+"？")){
	        	$.ajax({
	                url:admin_webroot+"user_questions/delete_all/",
	                type:"POST",
	                data:{ids:checkboxes},
	                dataType:"json",
	                success:function(data){
	                    try{
	                        alert(data.msg);
	                    }catch (e){
	                        alert(j_object_transform_failed);
	                    }
	                    window.location.href = window.location.href;
	                }
	            });
            }
        }else{
        	$("#select_evaluation").modal("open");
        }
    }
    
    function searchevaluation(){
        var evaluation_keyword = document.getElementById("evaluation_keyword");//搜索关键字
        var sUrl = admin_webroot+"evaluation_conditions/searchEvaluation/";//访问的URL地址
        if(evaluation_keyword.value!=""){
            $.ajax({
                type: "POST",
                url: sUrl,
                dataType: 'json',
                data: {evaluation_keyword:evaluation_keyword.value,condition_id:''},
                success: function (result) {
                    if(result.flag=="1"){
                        var evaluation_select_sel = document.getElementById('evaluation_select');
                        evaluation_select_sel.innerHTML = "";
                        if(result.content){
                            var selhtml="<option value='-1'>请选择</option>";
                            for(i=0;i<result.content.length;i++){
                            	selhtml+="<option value='"+result.content[i]['Evaluation'].code+"'>"+result.content[i]['Evaluation'].name+"</option>";
                            }
                            evaluation_select_sel.innerHTML = selhtml;
                        }
                        return;
                    }
                    if(result.flag=="2"){
                        alert(result.content);
                    }
                }
            });
        }
    }
    
    function set_question(){
    	var bratch_operat_check = document.getElementsByName("checkboxes[]");
        var evaluation_code = $("#evaluation_select").val();
        var checkboxes=new Array();
        for(var i=0;i<bratch_operat_check.length;i++){
            if(bratch_operat_check[i].checked){
                checkboxes.push(bratch_operat_check[i].value);
            }
        }
        if(evaluation_code!="-1" && checkboxes.length != 0){
        	$.ajax({
	        	url:admin_webroot+"user_questions/set_question/",
	            type:"POST",
	            data:{ids:checkboxes,evaluation_code:evaluation_code},
	            dataType:"json",
	            success:function(data){
	                try{
	                    alert(data.msg);
	                }catch (e){
	                    alert(j_object_transform_failed);
	                }
	                window.location.href = window.location.href;
	            }
	        });
		}
    }
</script>