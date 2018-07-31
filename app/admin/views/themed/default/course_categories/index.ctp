<style type="text/css">
    .am-panel-bd {padding: 0.5rem;}
    .am-radio, .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
    .seolink a:first-child{text-decoration:underline;color:green;}
    .am-checkbox input[type="checkbox"]{margin-left:0;}
    .am-yes{color:#5eb95e;}
    .am-no{color:#dd514c;}
    .am-panel-title div{font-weight:bold;}
    div.fuji label{font-weight:100;display:inline!important;}
    div.fuji label span{font-weight:bold;}
</style>
<div>
    <?php echo $form->create('CourseCategory',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();','class'=>'am-form am-form-horizontal'));?>
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
        <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/course_categories/view/0'); ?>">
            <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
        </a>
    </div>
    <div id="tablelist">
        <div class="am-panel-group am-panel-tree" id="accordion">
            <div class="listtable_div_btm">
                <div class="am-panel-hd">
                    <div class="am-panel-title">
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['thumbnail'];?></div>
                        <div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['name'];?></div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['code'];?></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['status'];?></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['sort']?></div>
                        <div class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $ld['operate']; ?></div>
                        <div style="clear:both;"></div>
                    </div>
                </div>
            </div>
            <?php if(isset($course_category_list) && sizeof($course_category_list)>0){foreach($course_category_list as $k=>$v){?>
                <div>
                    <div class="listtable_div_top am-panel-body" >
                        <div class="am-panel-bd fuji">
                            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                                <label data-am-collapse="{parent: '#accordion', target: '#course_<?php echo $v['id']?>'}" class="<?php echo (isset($v['parent_id'])&&!empty($v['parent_id']))?"am-icon-plus":"am-icon-minus";?>"></label>
                                <?php echo $html->image($v['img01']!=''?$v['img01']:$configs['shop_default_img'],array('style'=>'width:60px;height:60px;display:block;margin:0 auto;')); ?>
                            </div>
                            <div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $v['name']?>&nbsp;</div>
                            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['code']?>&nbsp;</div>
                            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                                <?php if ($v['status'] == 1) {?>
                                    <span class="am-icon-check am-yes"></span>
                                <?php }elseif($v['status'] == 0){ ?>
                                    <span class="am-icon-close am-no"></span>
                                <?php } ?>&nbsp;
                            </div>
                            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['orderby']?>&nbsp;</div>
                            <div class="am-u-lg-3 am-u-md-2 am-u-sm-2">
                                <a class="mt am-btn am-btn-success am-btn-xs  am-seevia-btn-view" target='_blank' href="<?php echo $webroot.'course_categories/index?'.$v['id'];?>">
                                    <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?>
                                </a>
                                <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/course_categories/view/'.$v['id']); ?>">
                                    <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                                </a>
                                <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'course_categories/remove/<?php echo $v['id'] ?>');">
                                    <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                </a>
                            </div>
                            <div style="clear:both;"></div>
                        </div>
                        <?php if(isset($v['parent_id'])&& sizeof($v['parent_id'])>0){?>
                            <div class="am-panel-collapse am-collapse am-panel-child" id="course_<?php echo $v['id']?>">
                                <?php $j=0; foreach($v['parent_id'] as $kk=>$vv){$j++;?>
                                    <div class="am-panel-bd am-panel-childbd">
                                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                                            <label data-am-collapse="{parent: '#accordion', target: '#course_<?php echo $vv['id']?>'}" class="<?php echo (isset($vv['parent_id'])&&!empty($vv['parent_id']))?"am-icon-plus":"am-icon-minus";?>"  style="padding-left:30px;"></label>
                                            <?php echo $html->image($vv['img01']!=''?$vv['img01']:$configs['shop_default_img'],array('style'=>'width:60px;height:60px;display:block;margin:0 auto;')); ?>
                                        </div>
                                        <div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $vv['name']?></div>
                                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $vv['code']?></div>
                                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                                            <?php if ($vv['status'] == 1) {?>
                                                <span class="am-icon-check am-yes"></span>
                                            <?php }elseif($vv['status'] == 0){ ?>
                                                <span class="am-icon-close am-no"></span>
                                            <?php } ?>
                                        </div>
                                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $vv['orderby']?></div>
                                        <div class="am-u-lg-3 am-u-md-2 am-u-sm-2">
                                            <a class="mt am-btn am-btn-success am-btn-xs  am-seevia-btn-view" target='_blank' href="<?php echo $webroot.'course_categories/view/'.$vv['id'];?>">
                                                <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?>
                                            </a>
                                            <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/course_categories/view/'.$vv['id']); ?>">
                                                <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                                            </a>
                                            <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'course_categories/remove/<?php echo $vv['id'] ?>');">
                                                <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                            </a>
                                        </div>
                                        <div style="clear:both;"></div>
                                    </div>
                                    <!--三级 菜单-->
                                    <?php if(isset($vv['parent_id'])&& sizeof($vv['parent_id'])>0){?>
                                        <div class="am-panel-collapse am-collapse am-panel-subchild" id="course_<?php echo $vv['id']?>">
                                            <?php foreach($vv['parent_id'] as $kkk=>$vvv){?>
                                                <div class="am-panel-bd am-panel-childbd">
                                                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                                                        <label data-am-collapse="{parent: '#accordion', target: '#course_<?php echo $vvv['id']?>'}" class="<?php echo (isset($vvv['parent_id'])&&!empty($vvv['parent_id']))?"am-icon-plus":"am-icon-minus";?>" style="padding-left:60px;"></label>&nbsp;
                                                        <?php echo $html->image($vvv['img01']!=''?$vvv['img01']:$configs['shop_default_img'],array('style'=>'width:60px;height:60px;display:block;margin:0 auto;')); ?>
                                                    </div>
                                                    <div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $vvv['name']?></div>
                                                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $vvv['code']?></div>
                                                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                                                        <?php if ($vvv['status'] == 1) {?>
                                                            <span class="am-icon-check am-yes"></span>
                                                        <?php }elseif($vvv['status'] == 0){ ?>
                                                            <span class="am-icon-close am-no"></span>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $vvv['orderby']?></div>
                                                    <div class="am-u-lg-3 am-u-md-2 am-u-sm-2">
                                                        <a class="mt am-btn am-btn-success am-btn-xs  am-seevia-btn-view" target='_blank' href="<?php echo $webroot.'course_categories/view/'.$vvv['id'];?>">
                                                            <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?>
                                                        </a>
                                                        <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/course_categories/view/'.$vvv['id']); ?>">
                                                            <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                                                        </a>
                                                        <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'course_categories/remove/<?php echo $vvv['id'] ?>');">
                                                            <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                                        </a>
                                                    </div>
                                                    <div style="clear:both;"></div>
                                                </div>
                                            <?php }?>
                                        </div>
                                    <?php }?>
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
    </div>
</div>
<script type="text/javascript">
    function formsubmit(){
        var keyword=document.getElementById('keyword').value;
        var url = "keyword="+keyword;
        window.location.href = encodeURI(admin_webroot+"course_categories/index?"+url);
    }
</script>