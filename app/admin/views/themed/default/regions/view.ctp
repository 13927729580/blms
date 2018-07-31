<style>
  .am-form-label{font-weight:bold;}
  .am-form-horizontal .am-form-label{padding-top:4px;}
  .btnouter{margin:50px;}
  .scrollspy-nav {
    top: 0;
    z-index: 500;
    background: #5eb95e;
    width: 100%;
    padding: 0 10px;
  }

  .scrollspy-nav ul {
    margin: 0;
    padding: 0;
  }

  .scrollspy-nav li {
    display: inline-block;
    list-style: none;
  }

  .scrollspy-nav a {
    color: #eee;
    padding: 10px 20px;
    display: inline-block;
  }

  .scrollspy-nav a.am-active {
    color: #fff;
    font-weight: bold;
  }
  
  .crumbs{
  	padding-left:0;
  	margin-bottom:22px;
  }
</style>
<div>
	<!-- 导航条 -->
	<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
		<ul>
		    <li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
		</ul>
	</div>	
	<!-- 右上角按钮 -->
	<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;margin:0;">
		<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
		<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
	</div>
	<div class="am-panel-group admin-content  am-detail-view" id="accordion"  style="width:100%;">
		<?php echo $form->create('Region',array('action'=>'view/'.(isset($this->data['Region']['id'])?$this->data['Region']['id']:0),'onsubmit'=>'return name_required();'));?>
			<input id="Operator_emnuId" name="data[Region][id]" type="hidden" value="<?php echo  isset($this->data['Region']['id'])?$this->data['Region']['id']:0;?>">
			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
				<input name="data[RegionI18n][<?php echo $v['Language']['locale'] ;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
			<?php }}?>
			<div id="basic_information" class="am-panel am-panel-default">
				<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['basic_information'] ?>
					</h4>
			    </div>
				<div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2  am-u-sm-3 am-form-label am-text-left" style="padding-top:20px"><?php echo $ld['superior_region']; ?></label>
			    			<div class="am-u-lg-7 am-u-md-7  am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<select name="data[Region][parent_id]">
										<option value="0"><?php echo $ld['top_area'] ?></option>
										<?php if(isset($region_list)&&sizeof($region_list)>0){foreach($region_list as $v){ ?>
											<option value="<?php echo $v['Region']['id'] ?>" <?php echo isset($this->data['Region']['parent_id'])&&$this->data['Region']['parent_id']==$v['Region']['id']?"selected":''; ?>><?php echo $v['RegionI18n']['name']; ?></option>
										<?php }} ?>
									</select>
			    				</div>
			    			</div>
			    		</div>		
			    					
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2  am-u-sm-3 am-form-label am-text-left" style="padding-top:20px"><?php echo $ld['name']?></label>
			    			<div class="am-u-lg-7 am-u-md-7  am-u-sm-8">
			    			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
			    					<input type="text" class="required_name <?php echo $v['Language']['is_default']=='1'?'is_default':''; ?>" id="region_name_<?php echo $v['Language']['locale']?>"  maxlength="60" name="data[RegionI18n][<?php echo $v['Language']['locale'] ;?>][name]" value="<?php echo isset($this->data['RegionI18n'][$v['Language']['locale']])?$this->data['RegionI18n'][$v['Language']['locale']]['name']:'';?>" />
			    				</div>
		    					<?php if(sizeof($backend_locales)>1){?>
		    						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2" style="margin-top:15px;"><?php echo $ld[$v['Language']['locale']]?>
		    							<em style="color:red;">*</em>
		    						</div>
		    					<?php } ?>
			    			<?php }}?>
			    			</div>
			    		</div>
			    				
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2  am-u-sm-3 am-form-label am-text-left" style="padding-top:18px"><?php echo $ld['abbreviated'] ?></label>
			    			<div class="am-u-lg-7 am-u-md-7  am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[Region][abbreviated]" value="<?php echo isset($this->data['Region']['abbreviated'])?$this->data['Region']['abbreviated']:''; ?>" >
			    				</div>
			    			</div>
			    		</div>		
			    					
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2  am-u-sm-3 am-form-label am-text-left" style="padding-top:10px"><?php echo $ld['description']?></label>
			    			<div class="am-u-lg-7 am-u-md-7  am-u-sm-8">
			    			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
			    					<textarea name="data[RegionI18n][<?php echo $v['Language']['locale'] ;?>][description]" ><?php echo isset($this->data['RegionI18n'][$v['Language']['locale']])?$this->data['RegionI18n'][$v['Language']['locale']]['description']:'';?></textarea>
			    				</div>
			    				<?php if(sizeof($backend_locales)>1){?>
			    				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="margin-top:25px;">
			    					<?php echo $ld[$v['Language']['locale']];?>
			    				</div>			    				
			    				<?php }?>
			    			<?php }}?>
			    			</div>
			    		</div>		
			    					
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2  am-u-sm-3 am-form-label am-text-left" style="padding-top:20px"><?php echo $ld['sort'] ?></label>
			    			<div class="am-u-lg-7 am-u-md-7  am-u-sm-8">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" name="data[Region][orderby]" value="<?php echo isset($this->data['Region']['orderby'])?$this->data['Region']['orderby']:'50'; ?>">
			    				</div>
			    			</div>
			    		</div>		
					</div>
			    	
				</div>
			</div>
		<?php echo $form->end();?>
	</div>
</div>
<script type='text/javascript'>
function name_required(){
	var required_name = document.querySelectorAll('.required_name');
	if(required_name.length>0){
		// for(var i=0;i<required_name.length;i++){
		// 	var input_classname=required_name[i].className;
		// 	if(input_classname.indexOf('is_default')>=0&&required_name[i].value==''){
		// 		alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['name']); ?>");
		// 		return false;
		// 	}
		// }
		if(required_name[0].value == ''){
			alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['name']); ?>");
			return false;
		}else if(required_name[1].value == ''){
			alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['name']); ?>");
			return false;
		}
	}else{
		alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['name']); ?>");
		return false;
	}
    	return true;
}

</script>