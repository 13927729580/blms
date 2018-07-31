<style type="text/css">
    .am-panel-title div{font-weight:bold;}
    #SearchForm{padding-top:8px;}
    #SearchForm li{margin-bottom:10px;}
    #SearchForm .am-selected-list li{margin-bottom: 0px;}
    .am-form-horizontal{padding-top:0px;}
</style>
<div>
    <?php echo $form->create('UserTaskLog',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();','class'=>'am-form am-form-horizontal'));?>
    <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4"><?php echo $ld['type']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <select name="type" id='type' data-am-selected="{maxHeight:300}" >
                    <option value="-1"><?php echo $ld['all_data']?></option>
                    <?php if(isset($task_resource) && sizeof($task_resource)>0){
                        foreach ($task_resource as $tid=>$t){?>
                            <option value="<?php echo $tid;?>" <?php if($type ==$tid){?>selected<?php }?>><?php echo $t;?></option>
                        <?php }
                    }?>
                </select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4"><?php echo $ld['group']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <select name="option_type_code" id='option_type_code' data-am-selected="{maxHeight:300}" >
                    <option value="-1"><?php echo $ld['all_data']?></option>
                    <?php foreach($task_group as $kk=>$vv){ ?>
                        <option value="<?php echo $vv['UserTaskGroup']['id'] ?>" <?php if($option_type_code ==$vv['UserTaskGroup']['id']){?>selected<?php }?>><?php echo $vv['UserTaskGroup']['name'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4"><?php echo $ld['user_name'];?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <input type="text" name="user_name" id="user_name" value="<?php echo isset($user_name)?$user_name:'';?>"/>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4"><?php echo $ld['keyword'];?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <input type="text" name="keyword" id="keyword" value="<?php echo isset($keyword)?$keyword:'';?>"/>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4"  ><?php echo $ld['operation_time']?></label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3" style="padding-right:0.5rem;width:37%;">
                <div class="am-input-group">
                    <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" name="start_date" value="<?php echo isset($start_date)?$start_date:"";?>" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
                </div>
            </div>
            <div class=" am-text-center am-fl " style="margin-top:7px;">-</div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3" style="padding-left:0.5rem;padding-right:0;">
                <div class="am-input-group">
                    <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="end_date" value="<?php echo isset($end_date)?$end_date:"";?>" />
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
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['user_name'];?></div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">任务</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['type'];?></div>
            <div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['remark'];?></div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">完成时间</div>
        </div>
        <?php if(isset($task_log) && sizeof($task_log)>0){foreach($task_log as $k=>$v){?>
            <div class="am-g">
                <div class="listtable_div_top" >
                    <div style="margin:10px auto;" class="am-g">
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo isset($v['User']['name'])?$v['User']['name']:"-";?></div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['group_name']?></div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $task_resource[$v['type']];?></div>
                        <div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo isset($v['UserTaskLog']['remark'])?$v['UserTaskLog']['remark']:"-"?></div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['UserTaskLog']['created'];?></div>
                    </div>
                </div>
            </div>
        <?php }}else{?>
            <div>
                <div class="no_data_found" ><?php echo $ld['no_data_found']?></div>
            </div>
        <?php }?>
    </div>
    <?php if(isset($task_log) && sizeof($task_log)){?>
        <div id="btnouterlist" class="btnouterlist am-form-group am-hide-sm-only">
            <div><?php echo $this->element('pagers')?></div>
            <div class="am-cf"></div>
        </div>
    <?php }?>
</div>
<script type="text/javascript">
    function formsubmit(){
        var keyword=document.getElementById('keyword').value;
        var option_type_code=document.getElementById('option_type_code').value;
        var type=document.getElementById('type').value;
        var user_name=document.getElementById('user_name').value;
        var start_date = document.getElementsByName('start_date')[0].value;
        var end_date = document.getElementsByName('end_date')[0].value;
        var url = "option_type_code="+option_type_code+"&type="+type+"&keyword="+keyword+"&user_name="+user_name+"&start_date="+start_date+"&end_date="+end_date;
        window.location.href = encodeURI(admin_webroot+"user_task_logs?"+url);
    }
</script>