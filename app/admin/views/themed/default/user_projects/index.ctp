<style type="text/css">
#SearchForm{padding-top:8px;}
#SearchForm li{margin-bottom:10px;}
#SearchForm .am-selected-list li{margin-bottom: 0px;}
#SearchForm label.am-form-label-text{margin-left: 0px;}
.am-form-horizontal{padding-top:0px;}
#add_student form{height:300px;}
#add_class form{max-height:450px;text-align: left;overflow-y:scroll;}
#project_print label.am-checkbox{display:block;padding-top:0px;margin-bottom:0.5rem;}
#add_project_fee form{max-height:500px;overflow-y:scroll;}
#add_project_fee label.am-radio{display:inline-block;padding-top:0px;margin-right:0.5rem;}
#add_project_fee label.am-radio:last-child{margin-right:0px;}
#add_project_fee div.am-form-group>label{text-align:left;}
div.listtable_div_btm div[class*=am-u-]{padding-left:0px;padding-right:0px;}

#SearchForm div[class*=am-u-]{padding-left:0px;padding-right:0px;}
#SearchForm .am-menu-nav li{margin-bottom:0px;}
#SearchForm .am-menu-sub{z-index:100;}
#SearchForm .am-menu-nav>li:hover a,#SearchForm .am-menu-nav>li:hover a:hover{background:#3bb4f2;color:#fff;}
#SearchForm .am-menu-nav>li.am-parent:hover a,#SearchForm .am-menu-nav>li.am-parent:hover a:hover{background:none;color:#333;}

#accordion .am-panel-title{font-weight:bold;}
span.project_class_total{font-weight:100;color:#999;margin-left:0.25rem;}
#accordion div[class*=am-u-]{padding-left:0px;padding-right:0px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
#accordion div[class*=am-u-] a,#accordion div[class*=am-u-] a:hover{color:#333;}
#accordion div[class*=am-u-] a span{margin-left:5px;color:#999;}
#accordion div.am-panel-bd{padding:0.6rem 0px 0.6rem 1.25rem;cursor:pointer;}
#action_old div.am-panel-bd{padding:0.6rem 0px 0.6rem 2.25rem;}

#add_project_fee{width:70%;margin-left:-35%;top:30%;}
#add_project_fee #project_user_dropdown{width:100%;display:none;}
#add_project_fee #project_user_dropdown.am-active{display:block;}
#add_project_fee #project_user_dropdown .am-dropdown-content{min-width:90%;}
#add_project_fee #project_user_dropdown .am-dropdown-content li{padding:5px;}
#add_project_fee .project_fee_list{width:97%;margin:0 auto;}
#add_project_fee .project_fee_list>li{border-top:1px solid #eee;}
#add_project_fee .project_fee_list>li:nth-child(2){border-top:1px solid #ddd;}
#add_project_fee .project_fee_list>li:first-child{border-top:none;}
#add_project_fee .project_fee_list>li table{width:90%;margin:1rem auto;}
#add_project_fee .project_fee_list>li table>tbody>tr>th,#add_project_fee .project_fee_list li table>tbody>tr>td{border-top:none;padding:0.7rem;}
#add_project_fee .project_fee_list>li table>tbody>tr>th{text-align:right;}
#add_project_fee .project_fee_list>li table>tbody>tr>td{text-align:left;max-width:120px;}
#add_project_fee .project_fee_list>li table>tbody>tr>td:nth-child(6){max-width:150px;}
#add_project_fee .project_fee_list>li table>tbody>tr>td .am-selected{width:100%;}
#add_project_fee .project_fee_list>li:nth-child(2) label.am-checkbox{display:none;}
#add_project_fee .project_fee_list>li div[class*=am-u-]{padding:0px;}

div.old_project_class_list{display:none;}
div.project_class_list div.am-panel-hd,div.old_project_class_list div.am-panel-hd{cursor:pointer;color:#999;}
div.project_class_list div.am-panel-hd span,div.old_project_class_list div.am-panel-hd span{margin-right:0.25rem;color:#999;}
div.project_class_list div.am-panel-bd,div.old_project_class_list div.am-panel-bd{display:none;}
div.project_class_list .search_selcted,div.old_project_class_list .search_selcted{background:#3bb4f2;color:#fff;}
#accordion div.project_class_list .search_selcted a,#accordion div.old_project_class_list .search_selcted a{color:#fff;}
#accordion div.project_class_list .search_selcted a:hover,#accordion div.old_project_class_list .search_selcted a:hover{color:#fff;}
img.remark_icon{max-width:20px;cursor:pointer;margin-left:5px;}

#accordion div[class*=am-u-] a.wait_class_time,#accordion div[class*=am-u-] a.wait_class_time:hover{background-color:#3bb4f2;color:#fff;}
</style>
<div>
    <?php echo $form->create('UserProject',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'class'=>'am-form am-form-horizontal')); ?>
    <div style="margin-bottom:20px;">
		<nav data-am-widget="menu" class="am-menu  am-menu-dropdown2">
			<a href="javascript: void(0)" class="am-menu-toggle">
				<i class="am-menu-toggle-icon am-icon-bars"></i>
			</a>
			<ul class="am-menu-nav am-avg-sm-1">
				<li>
					<a style="<?php if($user_project==-1){?>background-color:#3bb4f2;color:#fff;<?php }?>" href="<?php echo $html->url('/user_projects/index?user_project=-1'); ?>">所有学生</a>
				</li>
				<?php if(!empty($resource_info['user_project'])){
							foreach($resource_info['user_project'] as $k=>$v){
								if(isset($resource_info[$k])&&!empty($resource_info[$k])){
				?>
								<li class='am-parent'>
									<a style="cursor:pointer;" href="javascript:void(0);" ><?php echo $v;?></a>
									<ul class="am-menu-sub am-collapse  am-avg-sm-2">
										<?php	foreach($resource_info[$k] as $kk=>$vv){	?>
											<li>
												<a style="cursor:pointer;<?php if($user_project==$kk){?>background-color:#3bb4f2;color:#fff;<?php }?>" href="<?php echo $html->url('/user_projects/index?user_project='.$kk); ?>"><?php echo $vv;?></a>
											</li>
										<?php	} ?>
									</ul>
								</li>
						<?php  }else{	?>
								<li>
									<a style="cursor:pointer;<?php if($user_project==$k){?>background-color:#3bb4f2;color:#fff;<?php }?>" href="<?php echo $html->url('/user_projects/index?user_project='.$k); ?>" class="" ><?php echo $v;?></a>
								</li>
				<?php
								}
							}
						}
				?>
			</ul>
		</nav>
	</div>
    <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
        <li>
            <label class="am-u-lg-4 am-u-md-3 am-u-sm-4 am-form-label-text">项目名称</label>
            <div class="am-u-lg-7 am-u-md-8 am-u-sm-7">
                <select name="user_project" id='user_project' data-am-selected="{maxHeight:300}" >
			<optgroup label="<?php echo $ld['please_select']; ?>">
				<option value="-1"><?php echo $ld['all_data']?></option>
			</optgroup>
                    <?php if(isset($resource_info['user_project'])&&!empty($resource_info['user_project'])){foreach($resource_info['user_project'] as $k=>$v){
						if(isset($resource_info[$k])&&!empty($resource_info[$k])){
			?>
			<optgroup label="<?php echo $v; ?>">
				<?php 	foreach($resource_info[$k] as $kk=>$vv){ ?>
				<option value="<?php echo $kk; ?>" <?php if($user_project ==$kk){?>selected<?php }?>><?php echo $vv; ?></option>
				<?php		}	?>
			</optgroup>
			<?php
						}else{
			?>
			<optgroup label="<?php echo $v; ?>">
				<option value="<?php echo $k; ?>" <?php if($user_project ==$k){?>selected<?php }?>><?php echo $v; ?></option>
			</optgroup>
			<?php 	}	}} ?>
                </select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-4 am-u-md-3 am-u-sm-4 am-form-label-text">申请校区</label>
            <div class="am-u-lg-7 am-u-md-8 am-u-sm-7">
                <select name="user_project_site" id='user_project_site' data-am-selected="{maxHeight:300}" >
                    <option value="-1"><?php echo $ld['all_data']?></option>
                    <?php if(!empty($resource_info['user_project_site'])){ksort($resource_info['user_project_site']);
                        foreach($resource_info['user_project_site'] as $k=>$v){?>
                            <option <?php if($user_project_site ==$k){?>selected<?php }?> value="<?php echo $k;?>"><?php echo $v;?></option>
                        <?php }
                    }?>
                </select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-4 am-u-md-3 am-u-sm-4 am-form-label-text">申请上课时间</label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
            		<select name="start_date_time" data-am-selected="{maxHeight:250}">
				<option value="0"><?php echo $ld['all_data']; ?></option>
				<?php
					for($project_time_year=date('Y',strtotime('-1 year'));$project_time_year<=date('Y')+1;$project_time_year++){
						for($project_time_month=1;$project_time_month<=12;$project_time_month++){
								$project_time_month=str_pad($project_time_month,2,"0",STR_PAD_LEFT);
				?>
				<option value="<?php echo $project_time_year.'-'.$project_time_month; ?>" <?php echo isset($start_date_time)&&$start_date_time==($project_time_year.'-'.$project_time_month)?'selected':''; ?>><?php echo $project_time_year.'/'.$project_time_month; ?></option>
				<?php
						}
					}
				?>
			</select>
            </div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3">
                	<select name="end_date_time" data-am-selected="{maxHeight:250}">
				<option value="0"><?php echo $ld['all_data']; ?></option>
				<?php
					for($project_time_year=date('Y',strtotime('-1 year'));$project_time_year<=date('Y')+1;$project_time_year++){
						for($project_time_month=1;$project_time_month<=12;$project_time_month++){
								$project_time_month=str_pad($project_time_month,2,"0",STR_PAD_LEFT);
				?>
				<option value="<?php echo $project_time_year.'-'.$project_time_month; ?>" <?php echo isset($end_date_time)&&$end_date_time==($project_time_year.'-'.$project_time_month)?'selected':''; ?>><?php echo $project_time_year.'/'.$project_time_month; ?></option>
				<?php
						}
					}
				?>
			</select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-4 am-u-md-3 am-u-sm-4 am-form-label-text">申请上课时间段</label>
            <div class="am-u-lg-7 am-u-md-8 am-u-sm-7">
                <select name="user_project_hour" id='user_project_hour' data-am-selected="{maxHeight:300}" >
                    <option value="-1"><?php echo $ld['please_select']?></option>
                    <?php if(!empty($resource_info['user_project_time'])){
                        foreach($resource_info['user_project_time'] as $k=>$v){?>
                            <option <?php echo isset($user_project_hour)&&$user_project_hour==$k?'selected':''; ?> value="<?php echo $k;?>"><?php echo $v;?></option>
                        <?php }
                    }?>
                </select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-4 am-u-md-3 am-u-sm-4 am-form-label-text">课程顾问</label>
            <div class="am-u-lg-7 am-u-md-8 am-u-sm-7">
                <input type="text" name="manager_name" id="manager_name" value="<?php echo isset($manager_name)?$manager_name:'';?>"/>
            </div>
        </li>
        <li>
            <label class="am-u-lg-4 am-u-md-3 am-u-sm-4 am-form-label-text">项目状态</label>
            <div class="am-u-lg-7 am-u-md-8 am-u-sm-7">
                <select name="status" id='status' data-am-selected="{maxHeight:300}" >
                    <option value="-1"><?php echo $ld['please_select']?></option>
                    <option value="0" <?php if($status ==0){?>selected<?php }?> >待付款</option>
                    <option value="1" <?php if($status ==1){?>selected<?php }?> >已付款</option>
                    <option value="2" <?php if($status ==2){?>selected<?php }?> >待分班</option>
                    <option value="3" <?php if($status ==3){?>selected<?php }?> >已分班</option>
                    <option value="4" <?php if($status ==4){?>selected<?php }?> >已转班</option>
                    <option value="5" <?php if($status ==5){?>selected<?php }?> >已取消</option>
                    <option value="6" <?php if($status ==6){?>selected<?php }?> >变更中</option>
                </select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-4 am-u-md-3 am-u-sm-4 am-form-label-text">财务状态</label>
            <div class="am-u-lg-7 am-u-md-8 am-u-sm-7">
                <select name="fee_status" id='fee_status' data-am-selected="{maxHeight:300}" >
                    <option value="-1"><?php echo $ld['please_select']?></option>
                    <option value="0" <?php if($fee_status ==0){?>selected<?php }?> >未审核</option>
                    <option value="1" <?php if($fee_status ==1){?>selected<?php }?> >已审核</option>
                </select>
            </div>
        </li>
        <!--
        <li>
            <label class="am-u-lg-4 am-u-md-3 am-u-sm-4 am-form-label-text">学生</label>
            <div class="am-u-lg-7 am-u-md-8 am-u-sm-7">
                <input type="text" name="name_keyword" id="name_keyword" placeholder="姓名/手机" value="<?php echo isset($name_keyword)?$name_keyword:'';?>"/>
            </div>
        </li>
        -->
        <li>
            <label class="am-u-lg-4 am-u-md-3 am-u-sm-4 am-form-label-text">关键字</label>
            <div class="am-u-lg-5 am-u-md-6 am-u-sm-6">
                	<input type="text" name="keyword" id="keyword" placeholder="姓名/手机/学生备注" value="<?php echo isset($keyword)?$keyword:'';?>"/>
            </div>
            <div class="am-u-sm-2 am-u-md-2 am-u-sm-2 am-text-center">
                <input type="submit"  class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>" />
            </div>
        </li>
    </ul>
    <?php echo $form->end()?>
</div>
<!--左边菜单-->
<div class="am-u-lg-2  am-u-md-2 am-hide-sm-only am-padding-left-sm am-padding-right-xs">
    <div class="am-panel-group am-panel-tree" id="accordion">
        <div class="am-panel-header">
            <div class="am-panel-hd">
                <div class="am-panel-title">班级</div>
            </div>
        </div>
        <!--一级菜单-->
        <div>
            <div class="listtable_div_top am-panel-body" >
                <div class="am-panel-bd fuji <?php if($type==0){?>search_selcted<?php }?>">
                    <div class="am-u-lg-11 am-u-md-11 am-u-sm-11">
                        <a onclick="formsubmit(-1,'<?php echo $user_project;?>',<?php echo $select_id?>,2)" class="<?php echo isset($status)&&$status=='2'?'wait_class_time':''; ?>">未分班(<?php echo isset($none_class_total)?$none_class_total:0; ?>)</a>
                    </div>
                    <div style="clear:both"></div>
                </div>
            </div>
        </div>
        <div>
  	  	<div class="listtable_div_top am-panel-body">
  	  		<div class="am-panel-bd" onclick="$('div.project_class_list').toggle();">已分班</div>
  	  	</div>
  	</div>
        <?php
        		$is_old_class=isset($type)&&$type>0?true:false;
        		if(!empty($resource_info['user_project'])){foreach($resource_info['user_project'] as $k=>$v){
        			$project_class_break=true;
        			if(isset($resource_info[$k])&&!empty($resource_info[$k])){
        				foreach($resource_info[$k] as $kk=>$vv){
        					if(isset($all_class[$kk]))$project_class_break=false;
        				}
        			}else if(isset($all_class[$k]))$project_class_break=false;
        			if($project_class_break)continue;
        			if(isset($resource_info[$k])&&!empty($resource_info[$k])){foreach($resource_info[$k] as $kk=>$vv){if(!isset($all_class[$kk]))continue;
        ?>
        	<div class='project_class_list'>
        		<div class="listtable_div_top am-panel-body">
        			<div class="am-panel-hd"><span class="am-icon <?php echo (isset($type)&&isset($all_class[$kk][$type]))||(isset($user_project)&&$user_project==$kk)?'am-icon-minus':'am-icon-plus'; ?>"></span><?php echo $vv; ?><span class='project_class_total'>(<?php echo sizeof($all_class[$kk]); ?>)</span></div>
        			<div class='am-panel-bd am-padding-top-0 am-padding-left-sm' style="<?php echo (isset($type)&&isset($all_class[$kk][$type]))||(isset($user_project)&&$user_project==$kk)?'display:block;':''; ?>">
        				<?php foreach($all_class[$kk] as $kkk=>$vvv){$is_old_class=$type==$vvv['id']?false:$is_old_class; ?>
					<div class="am-g  am-padding-left-sm am-padding-top-xs am-padding-bottom-xs <?php echo isset($type)&&$type==$kkk?'search_selcted':''; ?>" style="<?php echo (isset($type)&&isset($all_class[$kk][$type]))||(isset($user_project)&&$user_project==$kk)?'display:block;':''; ?>">
						<div class="am-u-lg-11 am-u-md-10 am-u-sm-11">
							<a onclick="formsubmit(<?php echo $vvv['id'];?>,'<?php echo $user_project;?>',<?php echo $select_id?>,3)" class="list"><?php echo $vvv['class_name']; echo "<span>(".(isset($project_class_student_total[$vvv['id']])?$project_class_student_total[$vvv['id']]:0).")</span>";?></a>
						</div>
						<?php if($svshow->operator_privilege("class_edit")){ ?>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-1">
							<a href="javascript:void(0);" onclick="edit_project_class(<?php echo $vvv['id']; ?>)"><i class='am-icon am-icon-pencil-square-o'></i></a>
						</div>
						<?php } ?>
						<div class='am-cf'></div>
					</div>
					<?php } ?>
        			</div>
        		</div>
		</div>
        			<?php }}else if(isset($all_class[$k])){ ?>
        	<div class='project_class_list'>
        		<div class="listtable_div_top am-panel-body">
        			<div class="am-panel-hd"><span class="am-icon <?php echo (isset($type)&&isset($all_class[$k][$type]))||(isset($user_project)&&$user_project==$k)?'am-icon-minus':'am-icon-plus'; ?>"></span><?php echo $v; ?><span class='project_class_total'>(<?php echo sizeof($all_class[$k]); ?>)</span></div>
        			<div class='am-panel-bd am-padding-top-0 am-padding-left-sm' style="<?php echo (isset($type)&&isset($all_class[$k][$type]))||(isset($user_project)&&$user_project==$k)?'display:block;':''; ?>">
        				<?php foreach($all_class[$k] as $kkk=>$vvv){ ?>
					<div class="am-g  am-padding-left-sm am-padding-top-xs am-padding-bottom-xs <?php echo isset($type)&&$type==$kkk?'search_selcted':''; ?>" style="<?php echo (isset($type)&&isset($all_class[$k][$type]))||(isset($user_project)&&$user_project==$k)?'display:block;':''; ?>">
						<div class="am-u-lg-11 am-u-md-10 am-u-sm-11">
							<a onclick="formsubmit(<?php echo $vvv['id'];?>,'<?php echo $user_project;?>',<?php echo $select_id?>,3)" class="list"><?php echo $vvv['class_name'];echo "<span>(".(isset($project_class_student_total[$vvv['id']])?$project_class_student_total[$vvv['id']]:0).")</span>";?></a>
						</div>
						<?php if($svshow->operator_privilege("class_edit")){ ?>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-1">
							<a href="javascript:void(0);" onclick="edit_project_class(<?php echo $vvv['id']; ?>)"><i class='am-icon am-icon-pencil-square-o'></i></a>
						</div>
						<?php } ?>
						<div class='am-cf'></div>
					</div>
					<?php } ?>
        			</div>
			</div>
		</div>
		<?php 		}
			}}
		?>
       <?php
  		if(isset($old_class)&&sizeof($old_class)>0){
      ?>
      	<div>
      	  	<div class="listtable_div_top am-panel-body">
      	  		<div class="am-panel-bd" onclick="$('div.old_project_class_list').toggle();">已结束</div>
      	  	</div>
      	</div>
      <?php
      			if(!empty($resource_info['user_project'])){foreach($resource_info['user_project'] as $k=>$v){
      				$project_class_break=true;
	        			if(isset($resource_info[$k])&&!empty($resource_info[$k])){
	        				foreach($resource_info[$k] as $kk=>$vv){
	        					if(isset($old_class[$kk]))$project_class_break=false;
	        				}
	        			}else if(isset($old_class[$k]))$project_class_break=false;
	        			if($project_class_break)continue;
	        			if(isset($resource_info[$k])&&!empty($resource_info[$k])){foreach($resource_info[$k] as $kk=>$vv){if(!isset($old_class[$kk]))continue;
      ?>
      	<div class='old_project_class_list' style="<?php echo $is_old_class?'display:block;':''; ?>">
      	  	<div class="listtable_div_top am-panel-body">
				<div class="am-panel-hd"><span class="am-icon <?php echo (isset($type)&&isset($old_class[$kk][$type]))||(isset($user_project)&&$user_project==$kk)?'am-icon-minus':'am-icon-plus'; ?>"></span><?php echo $vv; ?><span class='project_class_total'>(<?php echo sizeof($old_class[$kk]); ?>)</span></div>
				<div class='am-panel-bd am-padding-top-0 am-padding-left-sm' style="<?php echo (isset($type)&&isset($old_class[$kk][$type]))||(isset($user_project)&&$user_project==$kk)?'display:block;':''; ?>">
					<?php foreach($old_class[$kk] as $kkk=>$vvv){ ?>
					<div class="am-g am-padding-left-sm am-padding-top-xs am-padding-bottom-xs <?php echo isset($type)&&$type==$kkk?'search_selcted':''; ?>" style="<?php echo (isset($type)&&isset($old_class[$kk][$type]))||(isset($user_project)&&$user_project==$kk)?'display:block;':''; ?>">
						<div class="am-u-lg-11 am-u-md-10 am-u-sm-11">
							<a onclick="formsubmit(<?php echo $vvv['id'];?>,'<?php echo $user_project;?>',<?php echo $select_id?>,3)" class="list"><?php echo $vvv['class_name'];echo "<span>(".(isset($project_class_student_total[$vvv['id']])?$project_class_student_total[$vvv['id']]:0).")</span>";?></a>
						</div>
						<?php if($svshow->operator_privilege("class_edit")){ ?>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-1">
							<a href="javascript:void(0);" onclick="edit_project_class(<?php echo $vvv['id']; ?>)"><i class='am-icon am-icon-pencil-square-o'></i></a>
						</div>
						<?php } ?>
						<div class='am-cf'></div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
				<?php }}else if(isset($old_class[$k])){ ?>
		<div class='old_project_class_list' style="<?php echo $is_old_class?'display:block;':''; ?>">
      	  	<div class="listtable_div_top am-panel-body">
      	  		<div class="am-panel-hd"><span class="am-icon <?php echo (isset($type)&&isset($old_class[$k][$type]))||(isset($user_project)&&$user_project==$k)?'am-icon-minus':'am-icon-plus'; ?>"></span><?php echo $v; ?><span class='project_class_total'>(<?php echo sizeof($old_class[$k]); ?>)</span></div>
				<div class='am-panel-bd am-padding-top-0 am-padding-left-sm' style="<?php echo (isset($type)&&isset($old_class[$k][$type]))||(isset($user_project)&&$user_project==$k)?'display:block;':''; ?>">
					<?php foreach($old_class[$k] as $kkk=>$vvv){ ?>
					<div class="am-g am-padding-left-sm am-padding-top-xs am-padding-bottom-xs <?php echo isset($type)&&$type==$kkk?'search_selcted':''; ?>" style="<?php echo (isset($type)&&isset($old_class[$k][$type]))||(isset($user_project)&&$user_project==$k)?'display:block;':''; ?>">
						<div class="am-u-lg-11 am-u-md-10 am-u-sm-11">
							<a onclick="formsubmit(<?php echo $vvv['id'];?>,'<?php echo $user_project;?>',<?php echo $select_id?>,3)" class="list"><?php echo $vvv['class_name'];echo "<span>(".(isset($project_class_student_total[$vvv['id']])?$project_class_student_total[$vvv['id']]:0).")</span>";?></a>
						</div>
						<?php if($svshow->operator_privilege("class_edit")){ ?>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-1">
							<a href="javascript:void(0);" onclick="edit_project_class(<?php echo $vvv['id']; ?>)"><i class='am-icon am-icon-pencil-square-o'></i></a>
						</div>
						<?php } ?>
						<div class='am-cf'></div>
					</div>
					<?php } ?>
				</div>
			</div>
      	</div>
	<?php 			}
	      		}
      		}
      	}
      	if($svshow->operator_privilege("view_canceled_student")){
       ?>
        <div>
            <div class="listtable_div_top am-panel-body" >
                <div class="am-panel-bd fuji" style="<?php if($status==5){?>background-color: #5eb95e;<?php }?>">
                    <div class="am-u-lg-11 am-u-md-11 am-u-sm-11">
                        <a onclick="formsubmit(-1,'<?php echo $user_project;?>','<?php echo $select_id?>',5)" <?php if($status=='5'){?>style="color: #fff;"<?php }else{ ?>style="color:#333;"<?php } ?> class="list">退款列表</a>
                    </div>
                    <div style="clear:both"></div>
                </div>
            </div>
        </div>
        <?php 
        	}
        ?>
    </div>
</div>
<?php echo $form->create('user_projects',array('action'=>'/',"name"=>"StudentForm",'onsubmit'=>"return false"));?>
<div class="am-u-lg-10 am-u-md-10 am-u-sm-12 am-padding-left-xs" id="right_list">
    <div class="am-text-right am-btn-group-xs" style="clear:both;margin:10px auto;">
    	<?php if($svshow->operator_privilege("fee_add")){ ?>
        	<a class="am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="javascript:void(0);" onclick="add_project_fee(this)">
                	<span class="am-icon-plus"></span>&nbsp;添加费用
            </a>
        <?php } ?>
        <?php if($svshow->operator_privilege("student_add")){ ?>
        	<a class="am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/user_projects/uploadprojects'); ?>">
                	<span class="am-icon-plus"></span>&nbsp;批量导入
            </a>
        <?php } ?>
        <?php if($svshow->operator_privilege("class_add")){?>
            <a class="am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="javascript:void(0);" onclick="add_class();">
                	<span class="am-icon-plus"></span>&nbsp;新开班
            </a>
        <?php }?>
        <?php if($svshow->operator_privilege("student_invite")){?>
            <a class="am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="javascript:void(0);" onclick="add_student();">
                <span class="am-icon-plus"></span>&nbsp;邀请学生
            </a>
        <?php }?>
    </div>
    <div class="listtable_div_btm">
        <div class="am-g">
            <div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><label class="am-checkbox am-success am-margin-top-0" ><input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck  type="checkbox" />学生</label></div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">项目</div>
            <div class="am-u-lg-1 am-u-md-2 am-u-sm-2">申请信息</div>
            <div class="am-u-lg-3 am-u-md-3 am-u-sm-2" style="padding-left:1rem;">班级</div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-2">项目状态</div>
            <div class="am-u-lg-2 am-u-md-1 am-u-sm-1" style="padding-left:1rem;">操作</div>
        </div>
        <?php if(isset($user_list) && sizeof($user_list)>0){foreach($user_list as $k=>$v){?>
            <div class="am-g">
                <div class="listtable_div_top" >
                    <div style="margin:10px auto;" class="am-g">
                        <div class="am-u-lg-3 am-u-md-3 am-u-sm-2">
                			<label class="am-checkbox am-success" style="top: 0px; margin: 0px;"><input type="checkbox" name="checkboxes[]" value="<?php echo $v['id']?>"  data-am-ucheck />
                				<div style="float:left;margin-right:5px;width:100px;overflow:hidden;text-overflow:ellipsis;white-space: nowrap;"><?php echo ($svshow->operator_privilege("student_edit")||$svshow->operator_privilege("student_detail"))?$html->link(!empty($v['user_name'])?$v['user_name']:"-",'/user_projects/view/'.$v['user_id']):(!empty($v['user_name'])?$v['user_name']:"-");?> [<?php echo $v['user_id'];?>]</div>
                				<?php if($v['identity_card_picture']!=''){?><img src="/theme/default/images/sfz.png" style='padding-right:3px;float:left;height:20px;display:block;margin:0 auto;'><?php }?>
	                			<?php if($v['user_education']!=''){?><img src="/theme/default/images/xl.png" style='padding-right:3px;float:left;height:20px;display:block;margin:0 auto;'><?php }?>
                				<?php if($v['img06']!=''){?><img src="/theme/default/images/qt.png" style='padding-right:3px;float:left;height:20px;display:block;margin:0 auto;'><?php }?>
	                			<?php if(!empty($v['user_mobile'])){echo "<br/>".$v['user_mobile'];}?>
	                    	</label>
                        </div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo isset($resource_info['all_user_project'][$v['project_code']])?$resource_info['all_user_project'][$v['project_code']]:"-"?><br/><?php echo !empty($v['manager_name'])?"<i class='am-icon am-icon-user'></i>&nbsp;".$v['manager_name']:"-";
                        		echo trim($v['manager_remark'])!=''?$html->image('/theme/hr163/img/manager_remark.jpg',array('class'=>'remark_icon','title'=>$v['manager_remark'])):'';
                        		echo trim($v['project_remark'])!=''?$html->image('/theme/hr163/img/project_remark.jpg',array('class'=>'remark_icon','title'=>$v['project_remark'])):'';
                        		echo trim($v['fee_remark'])!=''?$html->image('/theme/hr163/img/fee_remark.jpg',array('class'=>'remark_icon','title'=>$v['fee_remark'])):'';
                        			?></div>
                        <div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo isset($resource_info['user_project_site'][$v['project_site']])?$resource_info['user_project_site'][$v['project_site']]:"-"?><br/><?php echo date('Y-m',strtotime($v['project_time'])); echo isset($resource_info['user_project_time'][$v['project_hour']])?$resource_info['user_project_time'][$v['project_hour']]:$v['project_hour']; ?></div>
                        <div class="am-u-lg-3 am-u-md-3 am-u-sm-2" style="padding-left:1rem;"><?php echo (!empty($v['class_name'])?$v['class_name']:"-")."<br >";echo empty($v['class_time'])?'':"开班:".(date('Y-m-d',strtotime($v['class_time'])));?></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-2">
                            <?php if($v['status']=='0'){
                                echo "待付款";
                            }elseif($v['status']=='1'){
                                echo "已付款";
                            }elseif($v['status']=='2'){
                                echo "待分班";
                            }elseif($v['status']=='3'){
                                echo "已分班";
                            }elseif($v['status']=='4'){
                                echo "变更中";
                            }elseif($v['status']=='5'){
                                echo "已取消";
                            }elseif($v['status']=='6'){
                                echo "变更中";
                            }?>
                            <br/><span style='font-size:1rem;'><?php echo "报名:";echo date('Y-m-d',strtotime($v['created']));?></span>
                        </div>
                        <div class="am-u-lg-2 am-u-md-1 am-u-sm-1" style="padding-left:1rem;">
                            <?php if($svshow->operator_privilege("class_change")&&$v['status']=='2'){?>
                                <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="change_class('<?php echo $v['project_code'];?>',<?php echo $v['id']?>)">
                                    <span class="am-icon-pencil-square-o"></span> 分班
                                </a>
                            <?php }?>
                            <?php if($svshow->operator_privilege("project_edit")||$svshow->operator_privilege("project_detail")){?>
                                <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/user_projects/fee_view/'.$v['id']); ?>">
                                    <span class="am-icon-pencil-square-o"></span> <?php echo $ld['details']; ?>
                                </a>
                            <?php }?>
                            <?php if($v['identity_card_picture']!='' || $v['user_education']!='' || $v['img06']!=''){?>
	                            <?php if($svshow->operator_privilege("pdf_print")){?>
	                                <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="javascript:void(0);" onclick="show_project_print(<?php echo $v['id']; ?>)">
	                                	<span class="am-icon-pencil-square-o"></span> 打印
	                                </a>
	                            <?php }?>
                            <?php }?>
                            <?php if($v['status']=='0' || $v['status']=='5'){ ?>
                                <?php if($svshow->operator_privilege("student_delete")){?>
                                    <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'user_projects/remove/<?php echo $v['id'] ?>');">
                                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                    </a>
                                <?php }?>
                            <?php }?>
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
    <?php if(isset($user_list) && sizeof($user_list)){?>
        <div id="btnouterlist" class="btnouterlist am-form-group am-hide-sm-only">
            <div class="am-u-lg-8 am-u-md-12 am-u-sm-12">
                <div class="am-fl">
                    <label class="am-checkbox am-success" style="margin:5px 5px 5px 0px;"><input onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)"  class="am-btn am-radius am-btn-success am-btn-sm" type="checkbox" data-am-ucheck /><?php echo $ld['select_all']?></label>&nbsp;
                </div>
                <div class="am-fl" style='margin-right:5px;'>
                    <select id="barch_opration_select" onchange="batch_opration_select(this)" data-am-selected>
                        <option value="-1">请选择</option>
                    	<?php if($user_project!='-1'){ ?>
	                        <?php if($svshow->operator_privilege("class_change")){?>
	                            <option value="0">批量分班</option>
	                        <?php } ?>
                        	<?php 	}?>
                        <?php if($svshow->operator_privilege("pdf_print")){?>
                            <option value="1">批量打印</option>
                        <?php }?>
                        <?php if($svshow->operator_privilege("reset_project_class")||(isset($student_class_manager)&&$student_class_manager)){ ?>
                        	<option value="2">批量设置为未分班</option>
                        <?php } ?>
                        <?php if($svshow->operator_privilege("export_students")){ ?>
                        	<option value="3">导出学生</option>
                        <?php } ?>
			<?php if(isset($type)&&intval($type)>0&&isset($class_managers[$admin['id']])&&in_array($type,$class_managers[$admin['id']])){ ?>
			<option value="4">发送短信</option>
			<?php } ?>
                    </select>
                </div>
                <div class="am-fl" style='display:none;margin-right:5px;'>
                	<select name="export_type" data-am-selected>
                		<option value="0"><?php echo $ld['all_export']; ?></option>
                		<option value="1"><?php echo $ld['choice_export']; ?></option>
                        	<option value="2"><?php echo $ld['search_export']?></option>
                	</select>
                </div>
                <div class="am-fl">
                    <input type="button" id="btn" value="<?php echo $ld['submit']?>" class="am-btn am-btn-sm am-btn-danger am-btn-radius" onclick="submit_operations()" />&nbsp;
                </div>
            </div>
            <div><?php echo $this->element('pagers')?></div>
            <div class="am-cf"></div>
        </div>
    <?php }?>
	</div>
</div>
<?php echo $form->end()?>

<div class="am-modal am-modal-no-btn" id="add_class">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title" id="chapter_title">新开班</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
    		   <input type='hidden' name="data[UserProjectClass][id]" value="0" />
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:.6em;">项目名称</label>
                        <div class="am-u-lg-6 am-u-md-7 am-u-sm-7">
                            <select name='data[UserProjectClass][project_code]' id="class_project" data-am-selected="{maxHeight:300}">
                                	<optgroup label="<?php echo $ld['please_select']; ?>">
						<option value=" "><?php echo $ld['please_select']; ?></option>
					</optgroup>
		                    <?php if(isset($resource_info['user_project'])&&!empty($resource_info['user_project'])){foreach($resource_info['user_project'] as $k=>$v){
								if(isset($resource_info[$k])&&!empty($resource_info[$k])){
					?>
					<optgroup label="<?php echo $v; ?>">
						<?php 	foreach($resource_info[$k] as $kk=>$vv){ ?>
						<option value="<?php echo $kk; ?>" <?php if($user_project ==$kk){?>selected<?php }?>><?php echo $vv; ?></option>
						<?php		}	?>
					</optgroup>
					<?php
								}else{
					?>
					<optgroup label="<?php echo $v; ?>">
						<option value="<?php echo $k; ?>" <?php if($user_project ==$k){?>selected<?php }?>><?php echo $v; ?></option>
					</optgroup>
					<?php 	}	}} ?>
                            </select>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                        <div class='am-cf'></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:.6em;">校区</label>
                        <div class="am-u-lg-6 am-u-md-7 am-u-sm-7">
                            <select name='data[UserProjectClass][project_site]' id="class_site" data-am-selected="{maxHeight:300,}">
                                <option value=''><?php echo $ld['please_select']; ?></option>
                                <?php if(isset($resource_info['user_project_site'])&&sizeof($resource_info['user_project_site'])>0){foreach($resource_info['user_project_site'] as $k=>$v){ ?>
                                    <option value="<?php echo $k;?>"><?php echo $v;?></option>
                                <?php }} ?>
                            </select>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                        <div class='am-cf'></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:.6em;">班级名称</label>
                        <div class="am-u-lg-6 am-u-md-7 am-u-sm-7 am-text-left">
                            <input type="text" name="data[UserProjectClass][class_name]" id="class_name" value="" class="am-form-field am-input-sm">
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                        <div class='am-cf'></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:.6em;">开班日期</label>
                        <div class="am-u-lg-6 am-u-md-7 am-u-sm-7">
                        		<div class="am-input-group am-margin-top-0">
						<input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="data[UserProjectClass][project_time]" value="" />
						<span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
							<i class="am-icon-remove"></i>
						</span>
					</div>
                        </div>
                        <div class='am-cf'></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:.6em;">结束日期</label>
                        <div class="am-u-lg-6 am-u-md-7 am-u-sm-7">
                        		<div class="am-input-group am-margin-top-0">
						<input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="data[UserProjectClass][project_end_time]" value="" />
						<span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
							<i class="am-icon-remove"></i>
						</span>
					</div>
                        </div>
                        <div class='am-cf'></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:.6em;">班主任</label>
                        <div class="am-u-lg-6 am-u-md-7 am-u-sm-7">
                            <select name="data[UserProjectClass][manager]" id="class_manager" data-am-selected="{maxHeight:300}" >
                                <option value="-1"><?php echo $ld['please_select']?></option>
                                <?php if(!empty($manager_list)){
                                    foreach($manager_list as $v){?>
                                        <option value="<?php echo $v['Operator']['id'];?>"><?php echo $v['Operator']['name'];?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                        <div class='am-cf'></div>
                    </div>
		       <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:.6em;"></label>
                        <div class="am-u-lg-6 am-u-md-7 am-u-sm-7" style="text-align:left;">
                            <button type='button' style='margin-top:1rem;' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_modify_submit(this)"><?php echo $ld['confirm']; ?></button>
                         </div>
                         <?php if($svshow->operator_privilege("class_remove")){ ?>
                         <div class='am-u-lg-12 am-u-md-12 am-u-sm-12 am-text-right'>
                         	 <hr class='am-margin-top-xs am-margin-bottom-xs' />
                            <button type='button' class='am-btn am-btn-danger am-btn-sm am-radius' onclick="ajax_remove_project_class(this)"><?php echo $ld['delete']; ?></button>
                        </div>
                        <?php } ?>
                        <div class='am-cf'></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="add_student">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title" id="chapter_title">邀请学生</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">课程顾问</label>
                        <div class="am-u-lg-6 am-u-md-7 am-u-sm-7 am-text-left" style="padding-top: .6em;">
                            <?php echo $user_id['name'];?>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">选择项目</label>
                        <div class="am-u-lg-6 am-u-md-7 am-u-sm-7">
                            <select name="project_code" id='project_code' multiple data-am-selected="{maxHeight:300}" >
                                <optgroup label="<?php echo $ld['please_select']; ?>">
						<option value="-1"><?php echo $ld['please_select']; ?></option>
					</optgroup>
		                    <?php if(isset($resource_info['user_project'])&&!empty($resource_info['user_project'])){foreach($resource_info['user_project'] as $k=>$v){
								if(isset($resource_info[$k])&&!empty($resource_info[$k])){
					?>
					<optgroup label="<?php echo $v; ?>">
						<?php 	foreach($resource_info[$k] as $kk=>$vv){ ?>
						<option value="<?php echo $kk; ?>" <?php if($user_project ==$kk){?>selected<?php }?>><?php echo $vv; ?></option>
						<?php		}	?>
					</optgroup>
					<?php
								}else{
					?>
					<optgroup label="<?php echo $v; ?>">
						<option value="<?php echo $k; ?>" <?php if($user_project ==$k){?>selected<?php }?>><?php echo $v; ?></option>
					</optgroup>
					<?php 	}	}} ?>
                            </select>
                        </div>
                        <button type='button' style='float:left;' class='am-btn am-btn-success am-btn-sm am-radius' id='doc-gen-qr'>生成</button>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div id="doc-qrcode" class="am-text-center"></div>
                    <div class="am-text-midden" style="display:none;" id="code_text">
                        扫描二维码分享报名单
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="change_class">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title" id="chapter_title">分配班级</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-2 am-u-sm-2 am-form-label">班级名称</label>
                        <input type="hidden" id="class_name_id" name="class_name_id" value=""/>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5 am-text-left">
                            <select name="add_class_name" id='add_class_name' data-am-selected="{maxHeight:300}" >
                                <option value="-1"><?php echo $ld['please_select']?></option>
                            </select>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-text-midden">
                        <button type='button' style='margin-top:1rem;' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_modify_class_submit(this)"><?php echo $ld['confirm']; ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="project_print">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title" id="chapter_title">批量打印选择类型</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' action="<?php echo $html->url('/user_projects/batch_student_print_pdf'); ?>" class='am-form am-form-horizontal'>
    		<input type='hidden' name="user_project_id" value="" />
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <label class="am-u-lg-3 am-u-md-2 am-u-sm-2 am-form-label am-padding-top-0"><?php echo $ld['type']; ?></label>
                        <div class="am-u-lg-8 am-u-md-9 am-u-sm-9 am-text-left">
                            <label class='am-checkbox am-secondary'><input type='checkbox' name="project_print_type[]" value="0" checked data-am-ucheck /> <?php echo $ld['all']; ?></label>
                            <label class='am-checkbox am-secondary'><input type='checkbox' name="project_print_type[]" value="1" data-am-ucheck /> 身份证</label>
                            <label class='am-checkbox am-secondary'><input type='checkbox' name="project_print_type[]" value="2" data-am-ucheck /> 学历</label>
                            <label class='am-checkbox am-secondary'><input type='checkbox' name="project_print_type[]" value="3" data-am-ucheck /> 其它</label>
                        </div>
    			   <div class='am-cf'></div>
                    </div>
                    <div class="am-form-group">
   				<label class="am-u-lg-3 am-u-md-2 am-u-sm-2 am-form-label am-padding-top-0">&nbsp;</label>
   				<div class="am-u-lg-8 am-u-md-9 am-u-sm-9 am-text-left">
                        		<button type='button' class='am-btn am-btn-success am-btn-sm am-radius' onclick="project_print_submit(this)"><?php echo $ld['print']; ?></button>
   				</div>
    			   	<div class='am-cf'></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" id="add_project_fee">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title" id="chapter_title">添加费用</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
    	<div class="am-modal-bd">
		<form class='am-form am-form-horizontal'>
			<div class='am-form-group am-margin-bottom-xs'>
				<label class='am-u-lg-2 am-u-md-3 am-padding-top-xs'>学生姓名</label>
				<div class='am-u-lg-3 am-u-md-5'>
					<div class="am-input-group am-margin-top-0">
						<input type="text" class="am-form-field">
						<span class="am-input-group-btn">
							<button class="am-btn am-btn-default" type="button" onclick="ajax_project_user_search(this)"><span class="am-icon-search"></span></button>
						</span>
					</div>
					<div class="am-dropdown" id="project_user_dropdown" data-am-dropdown>
						<ul class="am-dropdown-content">
							<li class="am-dropdown-header"><?php echo $ld['please_select']; ?></li>
						</ul>
					</div>
				</div>
				<div style='clear:both;'></div>
				<label class='am-u-lg-2 am-u-md-3'>&nbsp;</label>
				<div class='am-u-lg-8 am-u-md-8 am-padding-top-xs am-text-left'></div>
				<div class='am-cf'></div>
			</div>
			<div class='am-form-group'>
				<label class='am-u-lg-2 am-u-md-3 am-padding-top-xs'>项目名称</label>
				<div class='am-u-lg-3 am-u-md-5'>
					<select id='user_fee_project' multiple data-am-selected="{maxHeight:300}">
						<option value="0"><?php echo $ld['please_select']?></option>
			                </select>
				</div>
				<div class='am-u-lg-2 am-u-md-3 am-text-left'>
					<button type='button' class='am-btn am-btn-warning am-btn-xs am-radius' onclick="add_user_fee_project(this)"><?php echo $ld['add']; ?></button>
				</div>
			</div>
			<div class='am-form-group am-hide'>
				<table class='am-table'>
					<tr>
						<th>类目</th>
						<td>
							<input type='hidden' name="data[UserProjectFee][0][id][]" value='0' />
							<input type='hidden' name="data[UserProjectFee][0][user_project_id][]" value='0' />
							<select name="data[UserProjectFee][0][fee_type][]">
								<?php if(!empty($resource_info['user_project_fee'])){
								foreach($resource_info['user_project_fee'] as $k=>$v){?>
									<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php }} ?>
							</select>
						</td>
						<th>支付方式</th>
						<td>
							<select name="data[UserProjectFee][0][payment_id][]">
								<option value='0'><?php echo $ld['please_select']; ?></option>
								<?php
									if(isset($payment_list)&&sizeof($payment_list)>0){foreach($payment_list as $v){
								?>
								<option value="<?php echo $v['Payment']['id']; ?>"><?php echo $v['PaymentI18n']['name']; ?></option>
								<?php
									}}
								?>
							</select>
						</td>
						<th>付款时间</th>
						<td>
							<div class="am-input-group am-margin-top-0">
								<input type="text" name="data[UserProjectFee][0][payment_time][]" class="am-form-field" readonly value="<?php echo date('Y-m-d'); ?>" />
								<span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
									<i class="am-icon-remove"></i>
								</span>
							</div>
						</td>
						<td class='am-padding-top-sm'><a href="javascript:void(0);" onclick="remove_user_fee_project(this)" class='am-text-danger am-text-default am-margin-sm'>&times;</a></td>
					</tr>
					<tr>
						<th>付款金额</th>
						<td><input type='text' name="data[UserProjectFee][0][amount][]" class='add_project_fee_input' onblur="add_project_fee_total()" value='' /></td>
						<th>收据编号</th>
						<td>
							<div class='am-u-lg-8'><input type='text' name="data[UserProjectFee][0][receipt_number][]" class='add_receipt_number_input' maxlength='7' value='' /></div>
							<div class='am-u-lg-4'><label class='am-checkbox am-success'><input type='checkbox' onclick="ditto_receipt_number(this)" />同上</label></div>
							<div class='am-cf'></div>
						</td>
						<th>备注</th>
						<td><input type='text' name="data[UserProjectFee][0][remark][]" value='' /></td>
						<td>&nbsp;</td>
					</tr>
				</table>
			</div>
			<div class='am-form-group' id="user_project_fee_list"></div>
			<div class='am-form-group'>
				<label class='am-u-lg-2 am-u-md-3 am-padding-top-xs'>总收入</label>
				<label class='am-u-lg-4 am-u-md-3 am-text-left am-padding-top-xs' id='add_project_fee_total'>0</label>
				<label class='am-u-lg-2 am-u-md-3 am-padding-top-xs'>收款人</label>
				<div class='am-u-lg-4 am-u-md-3 am-text-left am-padding-top-xs'><?php echo $admin['name']; ?></div>
			</div>
			<div class='am-form-group'>
				<label class='am-u-lg-3 am-u-md-3 am-padding-top-xs'>&nbsp;</label>
				<div class='am-u-lg-8 am-u-md-8 am-text-left'>
					<button type='button' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_add_project_fee(this)"><?php echo $ld['submit']; ?></button>
				</div>
			</div>
		</form>
    	 </div>
    </div>
</div>
<div class="am-modal am-modal-no-btn" id="project_class_message">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">
            	<h4 class="am-popup-title">班级群发信息</h4>
            	<span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
            		<input type='hidden' name="project_class_id" value="<?php echo isset($type)?$type:0; ?>" />
            	      <div class="am-form-group">
            	      	<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label am-padding-top-0"><?php echo $ld['remarks_notes']; ?></label>
            	      	<div class="am-u-lg-9 am-u-md-9 am-u-sm-9 am-text-left">
            	      		学生:{$student_name}<br />
            	      		项目:{$project_name}<br />
            	      		班级:{$class_name}
            	      	</div>
            	      </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label am-padding-top-0"><?php echo $ld['info']; ?></label>
                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-9 am-text-left">
                           		<textarea name="batch_message" maxlength='210' placeholder="{$student_name},你报名的{$project_name}将于<?php echo date('Y-m-d'); ?>开班,你所在的班级为:{$class_name}" value="{$student_name},你报名的{$project_name}将于<?php echo date('Y-m-d'); ?>开班,你所在的班级为:{$class_name}"></textarea>
                        </div>
    			   <div class='am-cf'></div>
                    </div>
                    <div class="am-form-group">
   				<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label am-padding-top-0">&nbsp;</label>
   				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9 am-text-left">
                        		<button type='button' class='am-btn am-btn-success am-btn-sm am-radius' onclick="ajax_class_message_send(this,0)"><?php echo $ld['send']; ?></button>
                        		<button type="button" class="am-btn am-btn-warning am-btn-sm am-radius"  onclick="ajax_class_message_send(this,1)">预览</button>
   				</div>
    			   	<div class='am-cf'></div>
                    </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function(){
	$("div.project_class_list div.am-panel-hd,div.old_project_class_list div.am-panel-hd").click(function(){
		var panelbd=$(this).parent().find('div.am-panel-bd');
		if(panelbd.is(':visible')){
			$(this).find("span.am-icon").removeClass('am-icon-minus').addClass('am-icon-plus');
			panelbd.hide();
		}else{
			$(this).find("span.am-icon").removeClass('am-icon-plus').addClass('am-icon-minus');
			panelbd.show();
		}
	});
});
    
    var user_id=<?php echo $user_id['id'];?>;
    function formsubmit(type,project,id,pro_status){
        var user_project=document.getElementById('user_project').value;
        var status=document.getElementById('status').value;
        if(project!='-1'){
        	user_project=project;
        }
        if(pro_status!='-1'){
        	status=pro_status;
        }
        var user_project_site=document.getElementById('user_project_site').value;
        var manager_name=document.getElementById('manager_name').value;
        var fee_status=document.getElementById('fee_status').value;
        //var name_keyword=document.getElementById('name_keyword').value;
        var keyword=document.getElementById('keyword').value;
        var start_date_time = document.getElementsByName('start_date_time')[0].value;
        var end_date_time = document.getElementsByName('end_date_time')[0].value;
        var type=type;
        var url = "id="+id+"&status="+status+"&keyword="+keyword+"&user_project="+user_project+"&start_date_time="+start_date_time+"&end_date_time="+end_date_time+"&user_project_site="+user_project_site+"&manager_name="+manager_name+"&fee_status="+fee_status+"&type="+type;
        window.location.href = encodeURI(admin_webroot+"user_projects?"+url);
    }

    function add_class(){
    		 $("#add_class h4.am-popup-title").html('新开班');
    		 $("#add_class input[name='data[UserProjectClass][id]']").val(0);
	        $("#add_class select[name='data[UserProjectClass][project_code]'] option[value=' ']" ).attr('selected',true);
	        $("#add_class select[name='data[UserProjectClass][project_code]']" ).trigger('changed.selected.amui');
	        $("#add_class select[name='data[UserProjectClass][project_site]'] option[value='']" ).attr('selected',true);
	        $("#add_class select[name='data[UserProjectClass][project_site]']" ).trigger('changed.selected.amui');
	        $("#add_class select[name='data[UserProjectClass][manager]']  option[value='-1']").attr('selected',true);
	        $("#add_class select[name='data[UserProjectClass][manager]']" ).trigger('changed.selected.amui');
	        $("#add_class input[name='data[UserProjectClass][class_name]']").val('');
	        $("#add_class input[name='data[UserProjectClass][project_time]']").val('');
	        $("#add_class input[name='data[UserProjectClass][project_end_time]']").val('');
	        $("#add_class button.am-btn-danger" ).hide();
	        $("#class_name").val("");
	        $("#start_time").val("");
	        $("#class_master").val("");
	        $("#add_class").modal('open');
    }
    
    function edit_project_class(project_class_id){
    		$.ajax({
		            url: admin_webroot+"user_projects/ajax_class_detail",
		            type:"POST",
		            data:{'project_class_id':project_class_id},
		            dataType:"json",
		            success: function(result){
		            		if(result.code=='1'){
		            			var project_detail=result.data;
		            			$("#add_class h4.am-popup-title").html('编辑班级');
		            			$("#add_class input[name='data[UserProjectClass][id]']").val(project_detail.id);
		            			$("#add_class select[name='data[UserProjectClass][project_code]'] option[value='"+project_detail.project_code+"']").attr('selected',true);
	        				$("#add_class select[name='data[UserProjectClass][project_code]']" ).trigger('changed.selected.amui');
		            			$("#add_class select[name='data[UserProjectClass][project_site]'] option[value='"+project_detail.project_site+"']" ).attr('selected',true);
	        				$("#add_class select[name='data[UserProjectClass][project_site]']" ).trigger('changed.selected.amui');
		            			$("#add_class input[name='data[UserProjectClass][class_name]']").val(project_detail.class_name);
		            			$("#add_class input[name='data[UserProjectClass][project_time]']").val(project_detail.project_time);
		            			$("#add_class input[name='data[UserProjectClass][project_end_time]']").val(project_detail.project_end_time);
		            			$("#add_class select[name='data[UserProjectClass][manager]'] option[value='"+project_detail.manager+"']").attr('selected',true);
	        				$("#add_class select[name='data[UserProjectClass][manager]']" ).trigger('changed.selected.amui');
	        				$("#add_class button.am-btn-danger").show();
		            			$("#add_class").modal('open');
		            		}else{
		            			alert('班级信息获取失败');
		            		}
		            }
	        });
    }
    
    function ajax_remove_project_class(btn){
		var requestForm=$(btn).parents('form');
		var project_class_id=$(requestForm).find("input[name='data[UserProjectClass][id]']").val().trim();
		if(project_class_id==''||project_class_id==0)return;
    		if(confirm('确认删除班级?')){
    			$.ajax({
		            url: admin_webroot+"user_projects/ajax_remove_project_class",
		            type:"POST",
		            data:{'project_class_id':project_class_id},
		            dataType:"json",
		            success: function(result){
		            		if(result.code=='1'){
		            			alert(result.message);
						window.location.reload();
		            		}else{
		            			var status_student_message="当前班级已有学生,无法删除";
		            			var status_student=[];
		            			$.each(result.message,function(index,item){
		            				var project_status="未知状态";
		            				if(index=='0'){
			                                project_status="待付款";
			                            }else if(index=='1'){
			                                project_status="已付款";
			                            }else if(index=='2'){
			                                project_status="待分班";
			                            }else if(index=='3'){
			                                project_status="已分班";
			                            }else if(index=='4'){
			                                project_status="变更中";
			                            }else if(index=='5'){
			                                project_status="已取消";
			                            }else if(index=='6'){
			                                project_status="变更中";
			                            }
		            				status_student.push(project_status+"("+item+")");
		            			});
		            			status_student_message=status_student_message+'\r\n'+status_student.join('\n');
		            			alert(status_student_message);
		            		}
		            }
	        	});
    		}
    }
    
    function add_student(){
        $("#add_student").modal('open');
    }

    var QRCode = $.AMUI.qrcode;
    function makeCode(text) {
        $('#doc-qrcode').html(new QRCode({text:text,width: 150,height: 150}));
        $('#code_text').show();
    }

    $('#doc-gen-qr').on('click', function() {
	        var text=j_server_host+webroot+"user_projects/entered?InvitingSource="+user_id+"&Inviting="+$('#project_code').val();
	        console.log(text);
	        makeCode(text);
    });

    function ajax_modify_submit(btn){
        var class_name = document.getElementById("class_name");
        var class_project = document.getElementById("class_project");
        var class_site = document.getElementById("class_site");
        var class_manager = document.getElementById("class_manager");
        if(class_name.value==""){
            alert("班级名称不能为空");
            return false;
        }
        if(class_project.value==""){
            alert("项目名称不能为空");
            return false;
        }
        if(class_site.value==""){
            alert("校区不能为空");
            return false;
        }
        if(class_manager.value==""){
            alert("班主任不能为空");
            return false;
        }
        var postForm=$(btn).parents('form');
        var postData=postForm.serialize();
        $.ajax({
            url: admin_webroot+"user_projects/class_ajax_modify",
            type:"POST",
            data:postData,
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    window.location.reload();
                }else{
                    alert(data.message);
                }
            }
        });
    }

    function ajax_modify_class_submit(btn){
        var add_class_name = document.getElementById("add_class_name");
        if(add_class_name.value==""){
            alert("班级名称不能为空");
            return false;
        }
        if(confirm("确定分班吗？")){
            var postForm=$(btn).parents('form');
            var postData=postForm.serialize();
            $.ajax({
                url: admin_webroot+"user_projects/class_change_ajax_modify",
                type:"POST",
                data:postData,
                dataType:"json",
                success: function(data){
                    if(data.code=='1'){
                        window.location.reload();
                    }else{
                        alert(data.message);
                    }
                }
            });
        }
    }

    function change_class(project_id,id){
    	if(id!=0){
    		$("#class_name_id").val(id);
    	}
        $("#add_class_name").html("<option value='-1'><?php echo $ld['please_select'];?></option>");
        $.ajax({
            url: admin_webroot+"user_projects/get_class_info",
            type:"POST",
            dataType:"json",
            data: {'id':project_id},
            success: function(data){
                if(data.code=="1"){
                    var name_list=data.data;
                    if(name_list.length!=0){
                        $(name_list).each(function(index,item){
                            if(name_list.length==1){
                                var aa = $("<option selected></option>").val(item['UserProjectClass']['id']).text(item['UserProjectClass']['class_name']);
                            }else{
                                var aa = $("<option></option>").val(item['UserProjectClass']['id']).text(item['UserProjectClass']['class_name']);
                            }
                            aa.appendTo($("#add_class_name"));
                        });
                        $("#change_class").modal("open");
                    }else{
                    	alert("此项目下无班级");
                    }
                }
            }
        });
    }
    
    function batch_opration_select(select){
    		if(select.value=='3'){
    			$("select[name='export_type']").parent().show();
    		}else{
    			$("select[name='export_type']").parent().hide();
    		}
    }
    
    function submit_operations(){
        var bratch_operat_check = document.getElementsByName("checkboxes[]");
        var operations_select = document.getElementById("barch_opration_select");
        if(operations_select.value==''){
            alert(j_select_operation_type+" !");
            return;
        }
        var postData = new Array();
        for(var i=0;i<bratch_operat_check.length;i++){
            if(bratch_operat_check[i].checked){
                postData.push(bratch_operat_check[i].value);
            }
        }
        if(operations_select.value=="0"){
            if(postData.length==0){
                	alert(j_please_select+"学生 !");
                	return false;
            }
            $("#class_name_id").val(postData.toString());
            var project_id='<?php echo $user_project;?>';
            if(project_id!='-1'){
                change_class(project_id,0);
            }else{
                alert("请选择项目");
            }
            return false;
        }
        if(operations_select.value=="1"){
        	show_project_print(postData.join(','));
        }
        if(operations_select.value=='2'){
        	$.ajax({
		            url: admin_webroot+"user_projects/ajax_reset_project_class",
		            type:"POST",
		            data:{'user_project_id':postData},
		            dataType:"json",
		            success: function(data){
		            		alert(data.message);
		            		if(data.code='1'){
		            			window.location.reload();
		            		}
		            }
        	});
        }
        if(operations_select.value=='3'){
        	var export_type=$("select[name='export_type']").val();
        	if(export_type=='2'){
        		document.SeearchForm.action=admin_webroot+"user_projects/project_export?export_type="+export_type;
        		document.SeearchForm.submit();
        	}else if(export_type=='1'){
        		document.StudentForm.action=admin_webroot+"user_projects/project_export";
        		document.StudentForm.submit();
        	}else{
        		window.location.href=admin_webroot+"user_projects/project_export?export_type="+export_type;
        	}
        }
        if(operations_select.value=='4'){
		if(postData.length==0){
			alert(j_please_select+"学生 !");
			return false;
		}
        	$("#project_class_message").modal({closeViaDimmer: false});
        	var placeholder=$("#project_class_message textarea").attr('placeholder');
        	if(typeof(placeholder)!='undefined')$("#project_class_message textarea").val(placeholder);
        }
    }
    
    function show_project_print(project_id){
    		$("#project_print input[type='checkbox'][value='0']").uCheck('check');
    		$("#project_print input[type='checkbox'][value!='0']").uCheck('uncheck');
    		$("#project_print input[type='hidden']").val(project_id);
    		$('#project_print').modal('open');
    }
    
    function project_print_submit(btn){
    		var print_types=$(btn).parents('form').find("input[type='checkbox']:checked").length;
    		if(print_types>0)$(btn).parents('form').submit();
    }
    
    function add_project_fee(btn){
    		$('#add_project_fee').modal('open');
    }
    
    function ajax_project_user_search(btn){
    		var user_keyword=$(btn).parents('div.am-input-group').find("input[type='text']").val().trim();
    		var requestDiv=$(btn).parents('div.am-form-group');
    		if(user_keyword!=''){
    			$.ajax({
				url: admin_webroot+"user_projects/ajax_project_user_search",
				type:"POST",
				dataType:"json",
				data: {'user_keyword':user_keyword},
				success: function(result){
					if(result.code=='1'){
						$(requestDiv).find("ul.am-dropdown-content li:gt(0)").remove();
						var project_user_length=0;
						$.each(result.data,function(index,item){
							$(requestDiv).find("ul.am-dropdown-content").append("<li><a href='javascript:void(0);' onclick='ajax_user_project_bind(this,"+item['id']+")'>"+item['first_name']+" / "+item['mobile']+"<br /><span style='color:#ccc;'>"+(item['identity_card']==null?'':item['identity_card'])+"</span></a></li>");
							project_user_length++;
						});
						if(project_user_length==1){
							$(requestDiv).find("ul.am-dropdown-content li:last-child a").click();
						}else{
							$(requestDiv).find("div.am-dropdown").dropdown('open');
						}
					}
				}
		        });
    		}
    }
    
    var ajax_project_manager=new Array();
    var ajax_class_manager=new Array();
    function ajax_user_project_bind(link,project_user){
    		var requestForm=$(link).parents('form');
    		if(project_user!=0){
    			var requestDiv=$(link).parents('div.am-form-group');
    			requestDiv.find("div.am-cf").prev("div").html($(link).html());
    			$(requestForm).find("div.am-dropdown").dropdown('close');
			var ProjectSelect=$("#user_fee_project");
			ProjectSelect.find("option[value!='0']").remove();
    			$.ajax({
				url: admin_webroot+"user_projects/ajax_user_project_bind",
				type:"POST",
				dataType:"json",
				data: {'project_user':project_user},
				success: function(result){
					if(result.code=='1'){
						var project_length=0;
						$.each(result.data,function(index,item){
							eval("ajax_project_manager["+item['id']+"]="+item['is_manager']+";");
							if(typeof(item['class_manager'])!='undefined'){
								eval("ajax_class_manager["+item['id']+"]="+item['class_manager']+";");
							}else{
								eval("ajax_class_manager["+item['id']+"]=0;");
							}
							ProjectSelect.append("<option value='"+item['id']+"'>"+item['project_name']+"</option>");
							project_length++;
						});
						if(project_length==1){
							$(ProjectSelect).find("option:last-child").attr('selected',true);
							var addBtn=$(ProjectSelect).parents("div.am-form-group").find('button.am-btn-warning');
							if(typeof(addBtn[0])!='undefined')add_user_fee_project(addBtn[0]);
						}
					}
					ProjectSelect.trigger('changed.selected.amui');
				}
		       });
    		}
    }
    
    function payment_time_input_init(input){
		var nowTemp = new Date();
		var nowDay = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0).valueOf();
		var nowMoth = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), 1, 0, 0, 0, 0).valueOf();
		var nowYear = new Date(nowTemp.getFullYear(), 0, 1, 0, 0, 0, 0).valueOf();
		$(input).datepicker({
		      theme: 'success',locale:'<?php echo $backend_locale; ?>',
		      onRender: function(date, viewMode) {
				// 默认 days 视图，与当前日期比较
				var viewDate = nowDay;
				switch (viewMode) {
					// moths 视图，与当前月份比较
					case 1:
						viewDate = nowMoth;
					break;
					// years 视图，与当前年份比较
					case 2:
						viewDate = nowYear;
					break;
				}
				return date.valueOf() > viewDate ? 'am-disabled' : '';
		      }
		}).data('amui.datepicker');
    }
    
    function add_user_fee_project(btn){
    		var defaultTemplate=$('#user_fee_project').parents('div.am-form-group').next('div.am-form-group');
    		var defaultHtml=typeof(defaultTemplate[0])!='undefined'?defaultTemplate[0].innerHTML:'';
    		var defaultObject=document.createElement('div');
    		defaultObject.innerHTML=defaultHtml;
    		
    		$("#user_fee_project option[value!='0']:selected").each(function(index,item){
    			if(item.selected==true){
    				var user_project_id=item.value;
    				if(!document.getElementById('project_fee_list_'+user_project_id)){
    					$('#user_project_fee_list').append("<ul class='am-avg-lg-1 am-avg-md-1 am-avg-sm-1 project_fee_list' id='project_fee_list_"+user_project_id+"'></ul>");
    					$("#project_fee_list_"+user_project_id).append("<li class='am-text-left am-text-lg am-padding-top-xs am-padding-bottom-xs'>"+item.text+"</li>");
    				}
    				$("#project_fee_list_"+user_project_id).append("<li>"+defaultObject.innerHTML+"</li>");
    				payment_time_input_init($('#project_fee_list_'+user_project_id).find("li:last-child div.am-input-group input")[0]);
    				$('#project_fee_list_'+user_project_id+" li:last-child select").selected({maxHeight:300});
    				$('#project_fee_list_'+user_project_id+" li:last-child select,#project_fee_list_"+user_project_id+" li:last-child input").each(function(index,item){
    					var itemName=$(item).attr('name');
    					if(typeof(itemName)!='undefined'){
    						itemName=itemName.replace(/0/g,user_project_id);
    						$(item).attr('name',itemName);
    					}
    				});
    				project_fee_type_bind(user_project_id);
    			}
    		});
    		$("#user_fee_project option:selected").attr("selected",false);
    		$('#user_fee_project').trigger('changed.selected.amui');
    }
    
    function remove_user_fee_project(btn){
    		if(confirm(j_confirm_delete)){
    			var parentList=$(btn).parents("ul");
    			$(btn).parents("li").remove();
    			if(parentList.find('li').length<=1)parentList.remove();
    			add_project_fee_total();
    		}
    }
    
    function ditto_receipt_number(box){
    		if(box.checked){
	    		var parentFeeRow=$(box).parents("li");
	    		var lastFeeRow=$(parentFeeRow).prev("li");
	    		var lastFee=lastFeeRow.find("input.add_receipt_number_input").val();
	    		if(typeof(lastFee)!='undefined'){
	    			$(box).parents('td').find("input.add_receipt_number_input").val(lastFee);
	    			add_project_fee_total();
	    		}
    		}
    }
    
    function project_fee_type_bind(user_project_id){
    		var requestForm=$("#add_project_fee form");
    		user_project_id=typeof(user_project_id)=='undefined'?0:user_project_id;
    		if(user_project_id==0)return;
    		if(typeof(ajax_project_manager[user_project_id])!='undefined'&&ajax_project_manager[user_project_id]=='1'){
    			requestForm.find("select[name='data[UserProjectFee]["+user_project_id+"][fee_type][]'] option[value='0']").attr('disabled',false);
    			requestForm.find("select[name='data[UserProjectFee]["+user_project_id+"][fee_type][]'] option[value='1']").attr('disabled',false);
    		}else if(typeof(ajax_class_manager[user_project_id])!='undefined'&&ajax_class_manager[user_project_id]=='1'){
    			requestForm.find("select[name='data[UserProjectFee]["+user_project_id+"][fee_type][]'] option[value='0']").attr('disabled',true);
    			requestForm.find("select[name='data[UserProjectFee]["+user_project_id+"][fee_type][]'] option[value='1']").attr('disabled',false);
    		}else{
    			requestForm.find("select[name='data[UserProjectFee]["+user_project_id+"][fee_type][]'] option[value='0']").attr('disabled',true);
    			requestForm.find("select[name='data[UserProjectFee]["+user_project_id+"][fee_type][]'] option[value='1']").attr('disabled',true);
    		}
    		requestForm.find("select[name='data[UserProjectFee]["+user_project_id+"][fee_type][]']").trigger('changed.selected.amui');
    }
    
    function add_project_fee_total(){
    		var fee_total=0;
    		$("input.add_project_fee_input").each(function(index,item){
    			var project_fee=item.value.trim();
    			project_fee=project_fee==''?0:project_fee;
    			fee_total+=parseFloat(project_fee);
    		});
    		$("#add_project_fee_total").html(fee_total);
    }
    
    function ajax_add_project_fee(btn){
    		var requestForm=$(btn).parents('form');
    		var formResult=true;
    		$("#user_project_fee_list input[type='text'],#user_project_fee_list select").each(function(index,item){
    			if(!formResult)return;
    			var itemName=$(item).attr('name');
    			if(typeof(itemName)!='undefined'){
    				console.log(itemName);
    				var itemTd=$(item).parents('td');
    				var itemTitle=$(itemTd).prev('th').html();
    				var itemValue=item.value;
    				var itemTag=item.tagName;
    				if(itemTag=='select'||itemTag=='SELECT'){
    					if(itemValue=='0'){
    						alert('请选择'+itemTitle);
    						formResult=false;
    						return;
    					}
    				}else if(itemName.indexOf("receipt_number") != -1){
    					if(itemValue==''){
    						alert('请填写'+itemTitle);
    						formResult=false;
    						return;
    					}else if(itemValue.length!=7){
    						alert(itemTitle+"格式错误");
    						formResult=false;
    						return;
    					}
    				}else if(itemName.indexOf("remark")==-1){
    					if(itemValue==''){
    						alert('请填写'+itemTitle);
    						formResult=false;
    						return;
    					}
    				}
    			}
    		});
    		if(!formResult)return;
    		$(btn).button('loading');
    		$.ajax({
			url:admin_webroot+"user_projects/ajax_add_project_fee",
			type:"POST",
			dataType:"json",
			data: requestForm.serialize(),
			success: function(result){
				alert(result.message);
				$(btn).button('reset');
				if(result.code=='1'){
					window.location.reload();
				}
			}
	       });
    }
    
    function ajax_class_message_send(btn,sendFlag){
    		var postData=new FormData($(btn).parents('form')[0]);
    		postData.append('sendFlag',sendFlag);
    		if(sendFlag=='0'){
			var bratch_operat_check = document.getElementsByName("checkboxes[]");
			var sendUser = new Array();
			for(var i=0;i<bratch_operat_check.length;i++){
				if(bratch_operat_check[i].checked){
					sendUser.push(bratch_operat_check[i].value);
				}
			}
			postData.append('sendUser',sendUser);
    		}
    		$(btn).button("loading");
    		$.ajax({
			type: "POST",
			url:admin_webroot+'user_projects/ajax_class_message_send',
			processData: false,// 不处理数据
			contentType: false,// 不设置内容类型
			data:postData,
			dataType:"json",
			async: false,
			success: function(result) {
    				$(btn).button("reset");
				var result_mesage=result.message;
				if(typeof(result.send_result)!='undefined'){
					var send_total=result.send_result.total;
					var send_success=result.send_result.success;
					var send_failed=result.send_result.failed;
					var send_result="发送总数:"+send_total+(parseInt(send_success)>0?",成功:"+send_success:'')+(parseInt(send_failed)>0?",失败:"+send_failed:'');
					result_mesage=send_result+"\r\n"+result_mesage;
				}
				alert(result_mesage);
				if(result.code=='1'){
					$("#project_class_message").modal('close');
				}
			}
		});
    }
</script>