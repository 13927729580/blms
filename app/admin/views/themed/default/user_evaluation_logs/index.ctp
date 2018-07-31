<style type="text/css">
    .am-panel-title div{font-weight:bold;}
    #SearchForm{padding-top:8px;}
    #SearchForm li{margin-bottom:10px;}
    #SearchForm .am-selected-list li{margin-bottom: 0px;}
    .am-form-horizontal{padding-top:0px;}
</style>
<div>
    <?php echo $form->create('UserEvaluationLogs',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();','class'=>'am-form am-form-horizontal'));?>
    <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['type']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7  ">
                <select name="option_type_code" id='option_type_code' data-am-selected="{maxHeight:300}" >
                    <option value="-1"><?php echo $ld['all_data']?></option>
                    <?php foreach($evaluation_category as $kk=>$vv){ ?>
                        <option value="<?php echo $vv['EvaluationCategory']['code'] ?>" <?php if($option_type_code ==$vv['EvaluationCategory']['code']){?>selected<?php }?>><?php echo $vv['EvaluationCategory']['name'] ?></option>
                    <?php } ?>
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
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">评测时间</label>
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
    <div class="am-g">
        <label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-group-label">&nbsp;</label>
        <div id="changeAttr" class="am-u-lg-11 am-u-md-11 am-u-sm-11"></div>
        <div style="clear:both;"></div>
    </div>
    <?php echo $form->end()?>
</div>
<div>
    <div class="listtable_div_btm">
        <div class="am-g">
            <div class="am-u-lg-1 am-u-md-1" ><label class="am-checkbox am-success" style="top: 0px; margin: 0px;line-height: 1.6;"><input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck  type="checkbox" /><?php echo $ld['avatar'];?></label></div>
            <div class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $ld['user_name'];?></div>
            <div class="am-u-lg-3 am-u-md-2 am-u-sm-2">评测<?php echo $ld['name'];?></div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">评测分数</div>
            <div class="am-u-lg-3 am-u-md-2 am-u-sm-2">开始——提交</div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['operate']; ?></div>
        </div>
        <?php if(isset($evaluation_list) && sizeof($evaluation_list)>0){foreach($evaluation_list as $k=>$v){?>
            <div class="am-g">
                <div class="listtable_div_top" >
                    <div style="margin:10px auto;" class="am-g">
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                            <label class="am-checkbox am-success" style="top: 0px; margin: 0px;"><input type="checkbox" name="checkboxes[]" value="<?php echo $v['UserEvaluationLog']['id']?>"  data-am-ucheck />
                                <?php echo $html->image(isset($v['User']['img01'])?$v['User']['img01']:'/theme/default/img/no_head.png',array('style'=>'width:60px;height:60px;display:block;margin:0 auto;')); ?>
                            </label>
                        </div>
                        <div class="am-u-lg-3 am-u-md-2 am-u-sm-2">
                            <a href="<?php echo $html->url('/users/view/'.$v['UserEvaluationLog']['user_id']); ?>">
                                <?php echo isset($v['User']['name'])?$v['User']['name']:"--";?>
                            </a>
                        </div>
                        <div class="am-u-lg-3 am-u-md-2 am-u-sm-2">
                            <a href="<?php echo $html->url('/evaluations/view/'.$v['UserEvaluationLog']['evaluation_id']); ?>">
                                <?php echo isset($v['Evaluation']['name'])?htmlspecialchars($v['Evaluation']['name']):"--";?>
                            </a>
                        </div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['UserEvaluationLog']['status']=='1'?$v['UserEvaluationLog']['score']:'&nbsp;';?></div>
                        <div class="am-u-lg-3 am-u-md-2 am-u-sm-2">
                            <?php echo $v['UserEvaluationLog']['start_time'];?>——<?php echo $v['UserEvaluationLog']['submit_time'];?>
                        </div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                            <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/user_evaluation_logs/view/'.$v['UserEvaluationLog']['id']); ?>">
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
    <?php if(isset($evaluation_list) && sizeof($evaluation_list)){?>
        <div id="btnouterlist" class="btnouterlist am-form-group am-hide-sm-only">
            <div class="am-u-lg-6 am-u-md-12 am-u-sm-12">
                <div class="am-fl">
                    <label class="am-checkbox am-success" style="margin:5px 5px 5px 0px;"><input onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)"  class="am-btn am-radius am-btn-success am-btn-sm" type="checkbox" data-am-ucheck /><?php echo $ld['select_all']?></label>&nbsp;
                </div>
                <div class="am-fl">
                    <input type="button" id="btn" value="批量删除" class="am-btn am-btn-sm am-btn-danger am-btn-radius" onclick="batch_evaluation()" />&nbsp;
                </div>
            </div>
            <div><?php echo $this->element('pagers')?></div>
        </div>
    <?php }?>
</div>
<script type="text/javascript">
    function formsubmit(){
        var keyword=document.getElementById('keyword').value;
        var user_name=document.getElementById('user_name').value;
        var option_type_code=document.getElementById('option_type_code').value;
        var start_date_time = document.getElementsByName('start_date_time')[0].value;
        var end_date_time = document.getElementsByName('end_date_time')[0].value;
        var url = "user_name="+user_name+"&keyword="+keyword+"&option_type_code="+option_type_code+"&start_date_time="+start_date_time+"&end_date_time="+end_date_time;
        window.location.href = encodeURI(admin_webroot+"user_evaluation_logs?"+url);
    }

    //批量操作
    function batch_evaluation(){
        var bratch_operat_check = document.getElementsByName("checkboxes[]");
        var checkboxes=new Array();
        for(var i=0;i<bratch_operat_check.length;i++){
            if(bratch_operat_check[i].checked){
                checkboxes.push(bratch_operat_check[i].value);
            }
        }
        if(checkboxes.length != 0){
            if(confirm("确定删除吗？")){
                $.ajax({
                    url:admin_webroot+"user_evaluation_logs/delete_all/",
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
        }
    }
</script>