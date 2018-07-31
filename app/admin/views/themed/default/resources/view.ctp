<?php 
/*****************************************************************************
 * SV-Cart 编辑菜单
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
?>
<style>
 .am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;} 
 .am-form-horizontal .am-checkbox{padding-top:0;}
 .am-form-horizontal .am-form-label{top:10px;}
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
<div class="am-g" style="margin-left:0;margin-right:0;">
	<!-- 导航条 -->
	<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
		<ul>
	    	<li>
	    		<a href="#basic_information"><?php echo $ld['basic_information']?>
	    		</a>
	    	</li>
	  	</ul>
	</div>
<?php echo $form->create('Resource',array('action'=>'view'))?>
	<!-- 右上角按钮 -->
	<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
		<button style="margin-right: 0;" type="submit" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_submit'] ?></button>
		<button style="margin-right: 0;" type="reset" class="am-btn am-btn-default am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_reset'] ?></button>
	</div>
	<div class="am-panel-group am-u-lg-10 am-u-md-9 am-u-sm-8" id="accordion" style="width:100%;padding-left:0;padding-right:0;">
		 <div class="am-panel am-panel-default">
		    <div class="am-panel-hd">
		      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#basic_information'}"><?php echo $ld['basic_information'] ?></h4>
		    </div>
		    <div id="basic_information" class="am-panel-collapse am-collapse am-in">
		      	<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
					<input type="hidden"  name="data[Resource][id]" value="<?php echo isset($this->data['Resource']['id'])?$this->data['Resource']['id']:'';?>"> 
					
					<div class="am-form-group">
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="top:7px;"><?php echo $ld['system'] ?></div>
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-8">
						<div class="am-u-lg-10 am-u-md-10 am-u-sm-10 am-padding-left-0" style="margin-bottom:0px;">
							<select data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}" name="data[Resource][system_code]">
							<option value=""><?php echo $ld['please_select']; ?></option>
							<?php if(isset($all_systems)&&sizeof($all_systems)>0){foreach($all_systems as $v){ ?>
							<option value="<?php echo $v; ?>" <?php echo isset($this->data['Resource']['system_code'])&&$this->data['Resource']['system_code']==$v?'selected':''; ?>><?php echo $v; ?></option>
							<?php }} ?>
						</select>
						</div>
						</div>
					</div>
					<div class="am-form-group">
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="top:5px;"><?php echo $ld['module'] ?></div>
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-8">
						<div class="am-u-lg-10 am-u-md-10 am-u-sm-10 am-padding-left-0" style="margin-bottom:0px;">
							<input type='text' name="data[Resource][module_code]" value="<?php echo isset($this->data['Resource']['module_code'])?$this->data['Resource']['module_code']:''; ?>" />
							</div>
						</div>
					</div>
					<div class="am-form-group">
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="top:5px;"><?php echo $ld['resource_name'];?></div>
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-8">
				  		<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
							<div class="am-u-lg-10 am-u-md-10 am-u-sm-10 am-padding-left-0" style="margin-bottom:10px;">
								<input type="text" id="name<?php echo $v['Language']['locale']?>"  maxlength="60" name="data[ResourceI18n][<?php echo $k;?>][name]" value="<?php if(isset($this->data['ResourceI18n'][$v['Language']['locale']]['name'])){echo $this->data['ResourceI18n'][$v['Language']['locale']]['name'];}?>" />
							</div>
								<?php if(sizeof($backend_locales)>1){?>
								<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-left: 0;padding-top: 5px;">
									<?php echo $ld[$v['Language']['locale']];?>&nbsp;<em style="color:red;">*</em>
								</div>
								<?php }?>
								<input type="hidden" name="data[ResourceI18n][<?php echo $k?>][locale]" value="<?php echo $v['Language']['locale']?>" />
								<input id="ResourceI18n<?php echo $k;?>Id" name="data[ResourceI18n][<?php echo $k;?>][id]" type="hidden" value="<?php if(isset($this->data['ResourceI18n'][$v['Language']['locale']]['id'])){ echo $this->data['ResourceI18n'][$v['Language']['locale']]['id'];}?>">
								<input id="ResourceI18n<?php echo $k;?>ResourceI18nId" name="data[ResourceI18n][<?php echo $k;?>][resource_id]" type="hidden" value="<?php if(isset($this->data['ResourceI18n'][$v['Language']['locale']]['resource_id'])){ echo $this->data['ResourceI18n'][$v['Language']['locale']]['resource_id'];}?>">
						<?php }}?>
						</div>
					</div>
					<div class="am-form-group">
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="top:7px;padding-top:0;"><?php echo $ld['parent_resource'];?></div>
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-8">
							<div class="am-u-lg-10 am-u-md-10 am-u-sm-10" style="padding-left:0;margin-top:0;">
							<select data-am-selected="{maxHeight:300, btnSize: 'default ', btnStyle: '#CCCCCC'}" name="data[Resource][parent_id]">
									<option value="000"><?php echo $ld['top_level_resource'];?></option>
									<?php if(isset($parentmenu) && sizeof($parentmenu)>0){foreach($parentmenu as $kk=>$vv){?>
									<option value="<?php echo $vv['Resource']['id']?>" <?php if(isset($this->data['Resource']['parent_id'])){if($this->data['Resource']['parent_id'] == $vv['Resource']['id']){?>selected<?php }}?>><?php echo $vv['ResourceI18n']['name']?></option><?php }}?>
							</select>
						</div>
						</div>
					</div>
								
					<div class="am-form-group">
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['r_description'];?></div> 
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-8">
							<div class="am-u-lg-10 am-u-md-10 am-u-sm-10 am-padding-left-0">
								<input type="text" id="<?php echo $v['Language']['locale']?>" name="data[ResourceI18n][<?php echo $k;?>][description]" value="<?php if(isset($this->data['ResourceI18n'][$v['Language']['locale']]['description'])){echo $this->data['ResourceI18n'][$v['Language']['locale']]['description'];}?>"/>
							</div>
						</div>
					</div>
								
					<div class="am-form-group">
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['resource_code'];?></div>
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-8">
							<div class="am-u-lg-10 am-u-md-10 am-u-sm-10 am-padding-left-0">
								<input type="text" name="data[Resource][code]" value="<?php echo isset($this->data['Resource']['code'])?$this->data['Resource']['code']:""?>"/>
							</div>
						</div>
					</div>	
					<div class="am-form-group" style=""> 
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['z_resource_value'];?></div>
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-8">
							<div class="am-u-lg-10 am-u-md-10 am-u-sm-10 am-padding-left-0">				
								<input type="text" name="data[Resource][resource_value]" value="<?php echo isset($this->data['Resource']['resource_value'])?$this->data['Resource']['resource_value']:''?>"/>
							</div>
						</div>
					</div>
				<!--版本-->
					<div class="am-form-group" >
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="top:6px"><?php echo $ld['version'];?></div>
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-8">
							<div class="am-u-lg-10 am-u-md-10 am-u-sm-10" style="padding-left:0;">
							<select data-am-selected="{maxHeight:300, btnSize: 'default ', btnStyle: '#CCCCCC'}" name="data[Resource][section]" >
            					<?php if(isset($section)&&sizeof($section)>0){?>
            					
            					<?php foreach($section as $key=>$value){?>
            					<option value="<?php echo $key;?>" <?php if(isset($this->data['Resource']['section'])){if($key==$this->data['Resource']['section']){ echo "selected"; }}else if($key=="免费版"){echo "selected";}?>><?php echo $value;?></option>
            					<?php }}?>
        					</select>
        				</div>
						</div>
					</div>
					<!--是否可用-->
					<div class="am-form-group">
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="top:0px"><?php echo $ld['availability'];?></div>
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-8">
								<label class="am-radio am-success"><input type="radio" class="radio" data-am-ucheck name="data[Resource][status]" style="margin-left:0px;" value="1" checked="true" <?php if(isset($this->data['Resource']['status'])){if($this->data['Resource']['status']==1){echo "checked";}}?>/><?php echo $ld['yes'];?></label>&nbsp;&nbsp;&nbsp;
								<label class="am-radio am-success"><input type="radio" class="radio" data-am-ucheck  name="data[Resource][status]" style="margin-left:0px;" value="0" <?php if(isset($this->data['Resource']['status'])){if($this->data['Resource']['status']==0){echo "checked";}}?>/><?php echo $ld['no'];?></label>
						</div>
					</div>
					<!--排序-->
					<div class="am-form-group">
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['sort'];?></div> 
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-8">
							<div class="am-u-lg-10 am-u-md-10 am-u-sm-10 am-padding-left-0">
								<input type="text"  name="data[Resource][orderby]" value="<?php echo isset($this->data['Resource']['orderby'])?$this->data['Resource']['orderby']:"" ?>" onkeyup="check_input_num(this)"/>							
							</div><br /><br />
								<p style="margin-top:3px;"><?php echo $ld['sorting_prompt'];?></p> 
						</div>
					</div>
			</div>
				
		</div>
  	  </div>
  	 </div>
<?php echo $form->end();?>

</div>
