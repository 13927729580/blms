<style type="text/css">
    .am-panel-title div{font-weight:bold;}
    #SearchForm{padding-top:8px;}
    #SearchForm li{margin-bottom:10px;}
    #SearchForm .am-selected-list li{margin-bottom: 0px;}
    .am-form-horizontal{padding-top:0px;}
</style>
<div>
    <?php echo $form->create('CourseType',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();','class'=>'am-form am-form-horizontal'));?>
    <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
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
    <div class="am-g">
        <label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-group-label">&nbsp;</label>
        <div id="changeAttr" class="am-u-lg-11 am-u-md-11 am-u-sm-11"></div>
        <div style="clear:both;"></div>
    </div>
    <?php echo $form->end()?>
</div>
<div>
    <div class="am-text-right am-btn-group-xs" style="clear:both;margin:10px auto;">
        <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/course_types/add'); ?>">
            <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
        </a>
    </div>
    <div class="listtable_div_btm">
        <div class="am-g">
            <div class="am-u-lg-1 am-u-md-1" >ID</div>
            <div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['name'];?></div>
            <div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['code'];?></div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['status'];?></div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-5"><?php echo $ld['operate']; ?></div>
        </div>
        <?php if(isset($course_type_list) && sizeof($course_type_list)>0){foreach($course_type_list as $k=>$v){?>
            <div class="am-g">
                <div class="listtable_div_top" >
                    <div style="margin:10px auto;" class="am-g">
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['CourseType']['id'];?></div>
                        <div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $v['CourseType']['name']?></div>
                        <div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $v['CourseType']['code']?></div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                            <?php if ($v['CourseType']['status'] == 1) {?>
                                <span class="am-icon-check am-yes"></span>
                            <?php }elseif($v['CourseType']['status'] == 0){ ?>
                                <span class="am-icon-close am-no"></span>
                            <?php } ?>
                        </div>
                        <div class="am-u-lg-2 am-u-md-3 am-u-sm-5">
                            <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/course_types/view/'.$v['CourseType']['id']); ?>">
                                <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                            </a>
                            <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'course_types/remove/<?php echo $v['CourseType']['id'] ?>');">
                                <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
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
    <?php if(isset($course_list) && sizeof($course_list)){?>
        <div id="btnouterlist" class="btnouterlist am-form-group am-hide-sm-only">
            <div><?php echo $this->element('pagers')?></div>
            <div class="am-cf"></div>
        </div>
    <?php }?>
</div>
<script type="text/javascript">
    function formsubmit(){
        var keyword=document.getElementById('keyword').value;
        var url = "keyword="+keyword;
        window.location.href = encodeURI(admin_webroot+"course_types?"+url);
    }
</script>