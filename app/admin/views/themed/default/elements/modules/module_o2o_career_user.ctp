        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#work_list'}">作品列表</h4>
            </div>
            <div id="work_list" class="am-panel-collapse am-collapse">
                <div class="am-panel-bd" id="style">
                    <table class="am-table  table-main">
                        <thead>
                        <tr>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['name']?></th>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2">技能</th>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2">图片</th>
                            <th class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $ld['description']?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($work_list) && sizeof($work_list)>0){foreach($work_list as $k=>$v){ ?>
                            <tr >
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['UserWork']['works_name']; ?></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['UserWork']['skill']; ?></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $html->image(isset($v['UserWork']['works_img'])?$v['UserWork']['works_img']:"",array('width'=>'50px','height'=>'50px')); ?></td>
                                <td class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $v['UserWork']['description']; ?></td>
                            </tr>
                        <?php }}else{?>
                            <tr><td colspan="6" align="center"><?php echo $ld['no_data_found']?></td></tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#experience_list'}">工作经历</h4>
            </div>
            <div id="experience_list" class="am-panel-collapse am-collapse">
                <div class="am-panel-bd" id="style">
                    <table class="am-table  table-main">
                        <thead>
                        <tr>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['time']?></th>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['name'];?></th>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['department']?></th>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2">技能</th>
                            <th class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $ld['description']?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($experience_list) && sizeof($experience_list)>0){foreach($experience_list as $k=>$v){ ?>
                            <tr >
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['UserExperience']['start_time']; ?>到<?php echo $v['UserExperience']['end_time']; ?></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['UserExperience']['company_name']; ?></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['UserExperience']['department']; ?></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['UserExperience']['skill']; ?></td>
                                <th class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $v['UserExperience']['description']?></th>
                            </tr>
                        <?php }}else{?>
                            <tr><td colspan="6" align="center"><?php echo $ld['no_data_found']?></td></tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#education_list'}">教育经历</h4>
            </div>
            <div id="education_list" class="am-panel-collapse am-collapse">
                <div class="am-panel-bd" id="style">
                    <table class="am-table  table-main">
                        <thead>
                        <tr>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['time']?></th>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['name'];?></th>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['type']?></th>
                            <th class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $ld['description']?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($education_list) && sizeof($education_list)>0){foreach($education_list as $k=>$v){ ?>
                            <tr >
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['UserEducation']['start_time']; ?>到<?php echo $v['UserEducation']['end_time']; ?></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['UserEducation']['school_name']; ?></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['UserEducation']['major_type']; ?></td>
                                <td class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $v['UserEducation']['description']; ?></td>
                            </tr>
                        <?php }}else{?>
                            <tr><td colspan="6" align="center"><?php echo $ld['no_data_found']?></td></tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>