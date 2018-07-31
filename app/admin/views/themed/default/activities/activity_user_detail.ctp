<div class="am-g">
	<div class="am-panel-group admin-content am-detail-view" id="accordion">
		<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}">
			<ul>
			   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
			</ul>
		</div>
		<div id="basic_information"  class="am-panel am-panel-default">
			<div class="am-panel-hd">
				<h4 class="am-panel-title"><?php echo $ld['basic_information']?></h4>
			</div>
			<div class="am-panel-collapse am-collapse am-in">
				<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
					
					<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-padding-right-0"><?php echo $ld['activity'] ?></label>
			    			<div class="am-u-lg-10 am-u-md-10 am-u-sm-9">
			    				<?php echo $activity_user_detail['Activity']['name'];  ?>
			    			</div>
			    			<div class='am-cf'></div>
			    		</div>
					<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-padding-right-0"><?php echo $ld['j_isp_name']?></label>
			    			<div class="am-u-lg-10 am-u-md-10 am-u-sm-9">
			    				<?php echo $activity_user_detail['ActivityUser']['name'];  ?>&nbsp;
			    			</div>
			    			<div class='am-cf'></div>
			    		</div>
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-padding-right-0"><?php echo $ld['mobile']?></label>
			    			<div class="am-u-lg-10 am-u-md-10 am-u-sm-9">
			    				<?php echo $activity_user_detail['ActivityUser']['mobile'];  ?>&nbsp;
			    			</div>
			    			<div class='am-cf'></div>
			    		</div>
			    		<?php if(isset($activity_config_list)&&sizeof($activity_config_list)>0){foreach($activity_config_list as $v){ ?>
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-padding-right-0"><?php echo $v['ActivityConfig']['name']; ?></label>
			    			<div class="am-u-lg-10 am-u-md-10 am-u-sm-9">
			    				<?php $user_activity_config=isset($user_activity_config_list[$v['ActivityConfig']['id']])?$user_activity_config_list[$v['ActivityConfig']['id']]:''; 
			    					if($v['ActivityConfig']['type']=='image'){
			    						echo $user_activity_config!=''?"<img src='".$user_activity_config."' style='max-width:150px;max-height:150px;' />":'';
			    					}else if($v['ActivityConfig']['type']=='checkbox'||$v['ActivityConfig']['type']=='radio'){
			    						$user_activity_config_arr=explode(',',$user_activity_config);
			    						echo implode("&nbsp;&nbsp;",$user_activity_config_arr);
			    					}else{
			    						echo $user_activity_config;
			    					}
			    				?>
			    			</div>
			    			<div class='am-cf'></div>
			    		</div>
			    		<?php }} ?>
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-padding-right-0">报名时间</label>
			    			<div class="am-u-lg-10 am-u-md-10 am-u-sm-9">
			    				<?php echo $activity_user_detail['ActivityUser']['created'];  ?>&nbsp;
			    			</div>
			    			<div class='am-cf'></div>
			    		</div>
				</div>
			</div>
		</div>
	</div>
</div>