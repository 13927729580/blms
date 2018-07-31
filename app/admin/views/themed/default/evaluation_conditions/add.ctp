<style>
    .am-form-label {
        font-weight: bold;
        margin-left: 10px;
        top: 0px;
    }
    .am-form-group{margin-top:10px;}
</style>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-g admin-content am-course  ">
    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-detail-menu">
        <ul class="am-list admin-sidebar-list">
            <li><a data-am-collapse="{parent: '#accordion'}" href="#basic_information"><?php echo $ld['basic_information']?></a></li>
        </ul>
    </div>
    <?php echo $form->create('/evaluation_conditions',array('action'=>'add/'.$code,'id'=>'evaluation_condition_add_form','name'=>'evaluation_condition_add','type'=>'POST','onsubmit'=>"return chechk_form()"));?>
    <input type="hidden" name="data[EvaluationCondition][id]" id="_id" value="" />
    <div class="am-panel-group admin-content" id="accordion">
        <!-- 编辑按钮区域 -->
        <div class="btnouter am-text-right" data-am-sticky="{top:'10%'}">
            <button type="submit" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_submit'] ?></button>
            <button type="reset" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_reset'] ?></button>
        </div>
        <!-- 编辑按钮区域 -->
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#basic_information'}"><?php echo $ld['basic_information'] ?>&nbsp;</h4>
            </div>
            <div id="basic_information" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal">
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">条件类型</label>
                        <div class="am-u-lg-3 am-u-md-4 am-u-sm-4">
                            <select id="params" name="data[EvaluationCondition][params]" onchange="changeType(this)">
                                <option value=''><?php echo $ld['please_select'];?></option>
                                <?php foreach ($condition_resource as $tid=>$t){ if(!in_array($tid,$condition_code)){?>
                                    <option value="<?php echo $tid;?>"><?php echo $t;?></option>
                                <?php }}?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group" id="change1" style="display: none;">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">条件值</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="value" name="data[EvaluationCondition][value]" value=""/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group" id="change2" style="display: none;">
                        <?php foreach($level_list as $lv_k=>$lv_v){?>
                            <label class="am-checkbox am-success" style="padding-top:0px">
                                <input type="checkbox" class="checkbox" name="data[EvaluationCondition][value][]" value="<?php echo $lv_v["AbilityLevel"]["id"];?>"  data-am-ucheck/>
                                <?php echo $lv_v["Ability"]["name"].$lv_v["AbilityLevel"]["name"];?>
                            </label>
                        <?php }?>
                    </div>
                    <div class="am-form-group" id="change3" style="display: none;">
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-12 am-text-center">
                            <div id="relative_evaluation"></div>
                            <table class="am-table">
                                <tr>
                                    <td colspan="3">
                                        <input style="width:200px;float:left;margin-right:5px;" type="text" name="evaluation_keyword" id="evaluation_keyword" /> <input  type="button" class="am-btn am-btn-success am-radius am-btn-sm " value="<?php echo $ld['search']?>" onclick="searchevaluation();" />
                                    </td>
                                </tr>
                            </table>
                            <div class="am-u-lg-10 am-u-md-6 am-u-sm-6 am-text-center">
                                <label class='am-show-sm-only'><?php echo $ld['option_products']?></label>
                                <div id="evaluation_select" class="related_dt"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $form->end();?>
</div>
<style type="text/css">
    .am-g.admin-content{margin:0 auto;}
    .am-form-label{text-align:right;}
    .am-form .am-form-group:last-child{margin-bottom:0;}
    #rank_operator select{width:50%;}
    #rank_operator em{float: left;margin: 0 5px;position: relative;top: 5px;}
    #rank_operator input[type="button"]{margin-right:1.2rem;}
    .related_dt dl {
        border: 1px solid #ccc;
        display: block;
        float: left;
        margin: 2px 5px;
        overflow: hidden;
        padding: 3px 5px;
        text-align: left;
        text-overflow: ellipsis;
        text-transform: capitalize;
        white-space: nowrap;
        width: 45%;
    }
    .related_dt dl span {
        color: #ccc;
        float: none;
        margin-right: 5px;
        padding: 3px 2px 0;
    }
    .related_dt dl:hover {
        border: 1px solid #5eb95e;
        color: #5eb95e;
        cursor: pointer;
    }
    .relative_evaluation_data:hover{
        border: 1px solid #5eb95e;
    }
    .relative_evaluation_data{
        border: 1px solid #fff;
        cursor: pointer;
    }
</style>
<script>
    function changeType(obj){
        $("input[name='data[EvaluationCondition][value][]']").each(function(){
            $(this).attr("checked",false);
        });
        $("input[name='data[EvaluationCondition][value]']").val("0");
        if(obj.value=="cycle"){
            $("#change2").hide();
            $("#change1").show();
            $("#change3").hide();
        }else if(obj.value=="ability_level"){
            $("#change1").hide();
            $("#change2").show();
            $("#change3").hide();
        }else if(obj.value=="parent_evaluation"){
            $("#change1").hide();
            $("#change2").hide();
            $("#change3").show();
        }else{
            $("#change1").hide();
            $("#change2").hide();
            $("#change3").hide();
        }
    }
    
	function chechk_form(){
		var params_obj = document.getElementById("params");
		if(params_obj.value==""){
			alert("条件不能为空");
			return false;
		}
		return true;
	}
	
    function searchevaluation(){
        var condition_id = document.getElementById("_id").value;
        var evaluation_keyword = document.getElementById("evaluation_keyword");//搜索关键字
        var sUrl = admin_webroot+"evaluation_conditions/searchEvaluation/";//访问的URL地址
        if(evaluation_keyword.value!=""){
            $.ajax({
                type: "POST",
                url: sUrl,
                dataType: 'json',
                data: {evaluation_keyword:evaluation_keyword.value,condition_id:condition_id},
                success: function (result) {
                    if(result.flag=="1"){
                        var evaluation_select_sel = document.getElementById('evaluation_select');
                        evaluation_select_sel.innerHTML = "";
                        if(result.content){
                            var selhtml="";
                            for(i=0;i<result.content.length;i++){
                                selhtml+="<dl onclick=\"add_relation_evaluation('"+result.content[i]['Evaluation'].id+"')\"><span class='am-icon-plus'></span>"+result.content[i]['Evaluation'].name+"</dl>";
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

    function add_relation_evaluation(evaluation_id){
        var condition_id = document.getElementById("_id").value;
        var code="<?php echo $code;?>";
        var newhtml = "";
        var sUrl = admin_webroot+"evaluation_conditions/add_relation_evaluation/";//访问的URL地址
        $.ajax({
            type: "POST",
            url:sUrl,
            dataType: 'json',
            data: {condition_id:condition_id,evaluation_id:evaluation_id,code:code},
            success: function (result) {
                if(result.flag=="1"){
                    for(i=0;i<result.content.length;i++){
                        newhtml+="<div class='am-u-lg-12 am-u-md-12 am-u-sm-12 relative_evaluation_data'><div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>"+result.content[i]['Evaluation'].name+"</div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'><span class='am-icon-close am-no' onMouseout='onMouseout_deleteimg(this);' onmouseover='onmouseover_deleteimg(this);' onclick=\"delete_relation_evaluation("+result.content[i]['Evaluation']['id']+");\"/></span></div></div>";
                    }
                    $("#relative_evaluation").html(newhtml);
                    $("#_id").val(result.condition_id);
                    return;
                }
                if(result.flag=="2"){
                    alert(result.content);
                }
            }
        });
    }

    function delete_relation_evaluation(evaluation_id){
        var condition_id = document.getElementById("_id").value;
        var sUrl = admin_webroot+"evaluation_conditions/delete_relation_evaluation/";//访问的URL地址
        var newhtml = "";
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {condition_id:condition_id,evaluation_id:evaluation_id},
            success: function (result) {
                if(result.flag=="1"){
                    for(i=0;i<result.content.length;i++){
                        newhtml+="<div class='am-u-lg-12 am-u-md-12 am-u-sm-12 relative_evaluation_data'><div class='am-u-lg-10 am-u-md-10 am-u-sm-10'>"+result.content[i]['Evaluation'].name+"</div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'><span class='am-icon-close am-no' onMouseout='onMouseout_deleteimg(this);' onmouseover='onmouseover_deleteimg(this);' onclick=\"delete_relation_evaluation("+result.content[i]['Evaluation']['id']+");\"/></span></div></div>";
                    }
                    $("#relative_evaluation").html(newhtml);
                    return;
                }
                if(result.flag=="2"){
                    alert(j_failed_delete);
                }
            }
        });
    }
</script>