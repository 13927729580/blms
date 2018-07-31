<style type="text/css">
    .am-panel-title div{font-weight:bold;}
    #SearchForm{padding-top:8px;}
    #SearchForm li{margin-bottom:10px;}
    #SearchForm .am-selected-list li{margin-bottom: 0px;}
    .am-form-horizontal{padding-top:0px;}
</style>
<div>
    <?php echo $form->create('UserTaskGroup',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();','class'=>'am-form am-form-horizontal'));?>
    <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
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
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['keyword'];?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <input type="text" name="keyword" id="keyword" value="<?php echo isset($keyword)?$keyword:'';?>"/>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">任务组时间</label>
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
    <div class="am-text-right am-btn-group-xs" style="clear:both;margin:10px auto;">
        <?php echo $html->link("系统任务","/user_tasks/",array("class"=>"mt am-btn am-btn-default am-seevia-btn-view"));?>
        <?php if($svshow->operator_privilege("task_group_add")){ ?>
        <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/user_task_groups/add'); ?>">
            <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
        </a>
        <?php } ?>
    </div>
    <div class="listtable_div_btm">
        <div class="am-g">
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">ID</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['name'];?></div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">前置条件</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['start_time'];?></div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['end_time'];?></div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['status'];?></div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['operate']; ?></div>
        </div>
        <?php if(isset($task_group) && sizeof($task_group)>0){foreach($task_group as $k=>$v){?>
            <div class="am-g">
                <div class="listtable_div_top" >
                    <div style="margin:10px auto;" class="am-g">
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['UserTaskGroup']['id'];?></div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo empty($v['UserTaskGroup']['name'])?"-":$v['UserTaskGroup']['name'];?></div>
                		<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                			<?php foreach ($condition_resource as $tid=>$t){?>
                            	<?php echo $t;?>:
                            		<?php if(isset($v['task_condition'])){
                            		foreach ($v['task_condition'] as $kk=>$vv){
                            			if($kk==$tid){
                            			echo $vv['UserTaskCondition']['value'];
                            			}}
                            		?>
                            	<br/>
                            <?php }}?>
                		</div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo isset($v['UserTaskGroup']['start_time'])?$v['UserTaskGroup']['start_time']:"-"?></div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo isset($v['UserTaskGroup']['end_time'])?$v['UserTaskGroup']['end_time']:"-"?></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                            <?php if ($v['UserTaskGroup']['status'] == 1) {?>
                                <span class="am-icon-check am-yes"></span>
                            <?php }elseif($v['UserTaskGroup']['status'] == 0){ ?>
                                <span class="am-icon-close am-no"></span>
                            <?php } ?>
                        </div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                        	<?php if($svshow->operator_privilege("task_group_add")){ ?>
                            <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/user_task_groups/view/'.$v['UserTaskGroup']['id']); ?>">
                                <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                            </a>
                            <?php } if($svshow->operator_privilege("task_group_remove")){ ?>
                            <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'user_task_groups/remove/<?php echo $v['UserTaskGroup']['id'] ?>');">
                                <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
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
    <?php if(isset($task_group) && sizeof($task_group)){?>
        <div id="btnouterlist" class="btnouterlist am-form-group am-hide-sm-only">
            <div><?php echo $this->element('pagers')?></div>
            <div class="am-cf"></div>
        </div>
    <?php }?>
</div>
<script type="text/javascript">
    function formsubmit(){
        var keyword=document.getElementById('keyword').value;
        var status=document.getElementById('status').value;
        var start_date_time = document.getElementsByName('start_date_time')[0].value;
        var end_date_time = document.getElementsByName('end_date_time')[0].value;
        var url = "keyword="+keyword+"&status="+status+"&start_date_time="+start_date_time+"&end_date_time="+end_date_time;
        window.location.href = encodeURI(admin_webroot+"user_task_groups?"+url);
    }
</script>