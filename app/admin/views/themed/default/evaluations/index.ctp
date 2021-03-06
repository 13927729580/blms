<style type="text/css">
    .am-panel-title div{font-weight:bold;}
    #SearchForm{padding-top:8px;}
    #SearchForm li{margin-bottom:10px;}
    #SearchForm .am-selected-list li{margin-bottom: 0px;}
    .am-form-horizontal{padding-top:0px;}
    .am-selected-content.am-dropdown-content{width: 100%;}
</style>
<div>
    <?php echo $form->create('Evaluation',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();','class'=>'am-form am-form-horizontal'));?>
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
        <?php echo $html->link("分类管理","/evaluation_categories/",array("class"=>"mt am-btn am-btn-default am-seevia-btn-view"));?>
        <?php if($svshow->operator_privilege("evaluation_add")&&isset($can_to_add)&&$can_to_add){ ?>
        <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/evaluations/add'); ?>">
            <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
        </a>
        <?php } ?>
    </div>
    <div class="listtable_div_btm">
        <div class="am-g">
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">课程级别</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">评测图片</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['name'];?></div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">题目数</div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">及格分数</div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">点击数</div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['status'];?></div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['operate']; ?></div>
        </div>
        <?php if(isset($evaluation_list) && sizeof($evaluation_list)>0){foreach($evaluation_list as $k=>$v){?>
            <div class="am-g">
                <div class="listtable_div_top" >
                    <div style="margin:10px auto;" class="am-g">
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php if(isset($v['Evaluation']['user_id'])&&$v['Evaluation']['user_id']==0){echo '系统级别';}else{echo '个人级别';} ?></div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $html->image(isset($v['Evaluation']['img'])?$v['Evaluation']['img']:"",array('width'=>'50px','height'=>'50px')); ?></div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                            <?php echo $v['Evaluation']['name']?><br/>
                            <?php echo $v['Evaluation']['code']?>
                        </div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['Evaluation']['question_count'];?></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['Evaluation']['pass_score'];?></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['Evaluation']['clicked'];?></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                            <?php if ($v['Evaluation']['status'] == 1) {?>
                                <span class="am-icon-check am-yes"></span>
                            <?php }elseif($v['Evaluation']['status'] == 0){ ?>
                                <span class="am-icon-close am-no"></span>
                            <?php } ?>
                        </div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                            <a class="mt am-btn am-btn-success am-btn-xs  am-seevia-btn-view" target='_blank' href="<?php echo $webroot.'evaluations/view/'.$v['Evaluation']['id'];?>">
                                <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?>
                            </a>
                            <?php if($svshow->operator_privilege("evaluation_edit")){ ?>
                            <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/evaluations/view/'.$v['Evaluation']['id']); ?>">
                                <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                            </a>
                            <?php } if($svshow->operator_privilege("evaluation_remove")){ ?>
                            <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'evaluations/remove/<?php echo $v['Evaluation']['id'] ?>');">
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
    <?php if(isset($evaluation_list) && sizeof($evaluation_list)){?>
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
        var option_type_code=document.getElementById('option_type_code').value;
        var start_date_time = document.getElementsByName('start_date_time')[0].value;
        var end_date_time = document.getElementsByName('end_date_time')[0].value;
        var url = "status="+status+"&keyword="+keyword+"&option_type_code="+option_type_code+"&start_date_time="+start_date_time+"&end_date_time="+end_date_time;
        window.location.href = encodeURI(admin_webroot+"evaluations?"+url);
    }
</script>