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
<style type="text/css">
.am-radio, .am-checkbox{display:inline;}
.am-form-horizontal .am-form-label, .am-form-horizontal .am-radio, .am-form-horizontal .am-checkbox, .am-form-horizontal .am-radio-inline, .am-form-horizontal .am-checkbox-inline {padding-top:0px;}

.am-u-lg-12.am-u-md-12.am-u-sm-12.am-padding-left-0.am-padding-right-0 .lang{
	position: absolute;
	top: 7px;
	right: -23px;
}
.am-u-lg-12.am-u-md-12.am-u-sm-12.am-padding-left-0.am-padding-right-0 em{
	position: absolute;
	top: 8px;
	right: -25px;
}
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
  em{
  	color: red;
  }
</style>

<div class="am-g" style="margin-left:0;margin-right:0;">
	<!-- 导航条 -->
	<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
	  <ul>
	    <li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
	  </ul>
	</div>
	<?php echo $form->create('Menu',array('action'=>'view/'.(isset($this->data['Menu']['id'])?$this->data['Menu']['id']:0),'onsubmit'=>'return menus_check()'));?>
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
		        <input type="hidden" name="data[Menu][id]" value="<?php echo isset($this->data['Menu']['id'])?$this->data['Menu']['id']:'0'; ?>"/>
		        
		               <div class="am-form-group" style="margin-bottom:10px">
			          	<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="top:8px;"><?php echo $ld['system'] ?></label>
			          	<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
						<select data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}" name="data[Menu][system_code]">
							<option value=""><?php echo $ld['please_select']; ?></option>
							<?php if(isset($all_systems)&&sizeof($all_systems)>0){foreach($all_systems as $v){ ?>
							<option value="<?php echo $v; ?>" <?php echo isset($this->data['Menu']['system_code'])&&$this->data['Menu']['system_code']==$v?'selected':''; ?>><?php echo $v; ?></option>
							<?php }} ?>
						</select>
					  </div>
			        </div>
			        					
			  	<div class="am-form-group" style="margin-bottom:10px">
			          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="top:8px;"><?php echo $ld['module'] ?></label>
			          <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
			        		<input type='text' name="data[Menu][module_code]" value="<?php echo isset($this->data['Menu']['module_code'])?$this->data['Menu']['module_code']:''; ?>" />
					  </div>
			        </div>
		        
		        	<div class="am-form-group">
			          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="top:7px"><?php echo $ld['previous_menu'] ?></label>
			          <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
			            <select name="data[Menu][parent_id]" data-am-selected>
							<option value="0"><?php echo $ld['top_menu'] ?></option>
							<?php if(isset($parentmenu) && sizeof($parentmenu)>0){?>
							<?php foreach($parentmenu as $k=>$v){?>
							<option value="<?php echo $v['Menu']['id']?>" <?php if(isset($this->data['Menu']['parent_id'])&&$v['Menu']['id'] == $this->data['Menu']['parent_id']) echo "selected";?>><?php echo $v['MenuI18n']['name']?></option><?php }}?>
						</select>
					  </div>
			        </div>
			        
			        <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="top:17px;"><?php echo $ld['menu_name']; ?></label>
                        <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
			        	<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
				          <div class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-padding-left-0 am-padding-right-0" style="margin-bottom:5px;">
				            <input type="text" id="menu_name_<?php echo $v['Language']['locale']?>" maxlength="60" name="data[MenuI18n][<?php echo $v['Language']['locale'];?>][name]" value="<?php echo isset($this->data['MenuI18n'][$v['Language']['locale']])?$this->data['MenuI18n'][$v['Language']['locale']]['name']:'';?>" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']];?></span><?php }?><em>*</em>
						  </div>
					  	<?php }} ?>
                        </div>
			        </div>
					
			        
			        <div class="am-form-group" style="margin-bottom:10px" >
			          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="top:7px;" ><?php echo $ld['code'] ?></label>
			          <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
			            <input type="text" name="data[Menu][action_code]" value="<?php echo isset($this->data['Menu']['action_code'])?$this->data['Menu']['action_code']:''; ?>"/>
					  </div>
			        </div>
			        	
			        <div class="am-form-group" style="margin-bottom:10px">
			          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="top:8px;"><?php echo $ld['type'] ?></label>
			          <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
			            <input type="text" name="data[Menu][type]" value="<?php echo isset($this->data['Menu']['type'])?$this->data['Menu']['type']:''; ?>"/>
					  </div>
			        </div>
					
					<div class="am-form-group" style="margin-bottom:10px">
			          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="top:8px;"><?php echo $ld['link_address'] ?></label>
			          <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
			            <input type="text" name="data[Menu][link]" value="<?php echo isset($this->data['Menu']['link'])?$this->data['Menu']['link']:''; ?>"/>
					  </div>
			        </div>
			        
			        <div class="am-form-group" style="margin-bottom:10px">
			          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="top:8px;"><?php echo $ld['versions'] ?></label>
			          <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
			            <input type="text" name="data[Menu][section]" value="<?php echo isset($this->data['Menu']['section'])?$this->data['Menu']['section']:''; ?>"/>
					  </div>
			        </div>
			        
			        <div class="am-form-group" style="margin-bottom:10px">
			          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="top:4px;"><?php echo $ld['status'] ?></label>
			          <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
			            <label class="am-radio am-success"><input type="radio" name="data[Menu][status]" data-am-ucheck <?php if(isset($this->data['Menu']['status'])&&$this->data['Menu']['status'] == 1){?>checked="checked"<?php }?> value="1"/><?php echo $ld['yes']?></label>
						<label class="am-radio am-success"><input type="radio" name="data[Menu][status]" data-am-ucheck <?php if((isset($this->data['Menu']['status'])&&$this->data['Menu']['status'] == 0)||!isset($this->data['Menu']['status'])){?>checked="checked"<?php }?> value="0"/><?php echo $ld['no']?></label>
					  </div>
			        </div>
			        
			        <div class="am-form-group" style="margin-bottom:10px">
			          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="top:6px;"><?php echo $ld['orderby'] ?></label>
			          <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
			            <input type="text" name="data[Menu][orderby]" value="<?php echo isset($this->data['Menu']['orderby'])?$this->data['Menu']['orderby']:'50'; ?>"/>
					  </div>
			        </div>
		      </div>
		      
		    </div>
	  	 </div>
	  				  
	</div>
	<?php echo $form->end();?>
</div>
<script type="text/javascript">
function menus_check(){
	var menu_name=$("#menu_name_<?php echo $backend_locale ?>").val();
	if(menu_name==""){
		alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['menu_name']); ?>");
		return false;
	}
}
</script>