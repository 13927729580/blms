<style>
    .am-form-group {margin-bottom:0px;}
    .btnouter{margin:50px;}
</style>
<div>
    <div>
        <div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
            <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
                <li><a href="#bulk_upload_article"><?php echo $ld['bulk_upload']?></a></li>
            </ul>
        </div>
        <div class="am-panel-group admin-content" id="accordion" style="width:83%;float:right;">
            <?php echo $form->create('user_projects',array('action'=>'/uploadprojectspreview/','onsubmit'=>'','name'=>"uploadprojectsForm","enctype"=>"multipart/form-data"));?>
            <div id="bulk_upload_article" class="am-panel am-panel-default">
                <div class="am-panel-hd">
                    <h4 class="am-panel-title">
                        <?php echo $ld['bulk_upload']?>
                    </h4>
                </div>
                <div class="am-panel-collapse am-collapse am-in">
                    <div class="am-panel-bd am-form-detail am-form am-form-horizontal">
                        <div class="am-form-group">
                            <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:21px;"><?php echo $ld['csv_file_bulk_upload']?></label>
                            <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                                <div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
                                    <p style="margin:10px 0px;"><input name="file" id="file" size="40" type="file" style="height:22px;" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/></p>
                                    <p style="padding:6px 0;"><?php echo $ld['articles_upload_file_encod']?></p>
                                </div>
                            </div>
                        </div>
                        <?php if(isset($profilefiled_codes)&&sizeof($profilefiled_codes)>0&&!empty($profilefiled_codes)){?>
                            <div class="am-form-group">
                                <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"></label>
                                <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                                    <div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
                                        <?php echo $html->link('下载实例文件',"/user_projects/download_csv_example/",'',false,false);?>
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                    </div>
                    <div class="btnouter">
                        <button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
                        <button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
                    </div>
                </div>
            </div>
            <?php echo $form->end();?>
        </div>
    </div>
</div>