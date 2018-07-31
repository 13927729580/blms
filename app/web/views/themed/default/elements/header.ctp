<div class="am-modal am-modal-no-btn" tabindex="-1" id="my_org_modal" style="min-width:340px;">
  <div class="am-modal-dialog">
    <div class="am-modal-hd" style="padding-bottom:10px;">我的组织
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd" >
      
    </div>
  </div>
</div>
<header id="amz-header">
  <div class="am-topbar am-topbar-fixed-top" >
  	<div>
    <?php if($this->params['controller']=="categories" && $this->params['action']="view"){ ?>
        <button class="am-btn am-btn-xs am-btn-secondary am-show-sm-only" data-am-offcanvas="{target: '#prodcut_category', effect: 'push'}"><span>商品分类</span> </button>
    <?php } ?>
    <h1 class="am-topbar-brand">
        <?php if(!empty($configs['shop_logo'])){
          	echo $svshow->link($svshow->image($configs['shop_logo'],LOCALE),"/");
        }else{
          	echo $svshow->link($svshow->image('/theme/default/img/'.$template_style.'/logo.jpg',LOCALE),"/");
        }?>
    </h1>
    <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-xs am-btn-secondary am-show-sm-only" data-am-collapse="{target: '#collapse-head'}"><span class="am-sr-only" >导航切换</span><span class="am-icon-bars changenav am-icon-md"></span></button>
	<!-- sm搜索框 -->
	<button type="button" class="am-topbar-btn am-topbar-toggle am-btn am-btn-xs am-btn-secondary am-show-sm-only" data-am-modal="{target: '#header_search', closeViaDimmer: 0,height: 110}">
	  	<span class="am-icon-search am-icon-md"></span>
	</button>
    <div class="am-collapse am-topbar-collapse" id="collapse-head">
      <?php if(isset($navigations['T'])){ ?>
      <ul class="am-nav am-nav-pills am-topbar-nav am-top-nav">
      <?php $navigations_t_count=count($navigations['T']);
        foreach($navigations['T'] as $k=>$v){?>
      <?php if(isset($v['SubMenu']) && sizeof($v['SubMenu']) >0) {  //含二级菜单 
          ?>
          <li class="am-dropdown" data-am-dropdown style="">
          <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;" >
            <?php echo (isset($v['NavigationI18n']['name']))?$v['NavigationI18n']['name']:"-";?><span class="am-icon-caret-down" style="margin-left:8px;"></span>
          </a>
          <ul class="am-dropdown-content" style="margin-top:-2px;left:0px;">
            <li class="am-dropdown-header" style="padding:6px 10px"><?php echo $v['NavigationI18n']['name'];?></li>
            <?php foreach($v['SubMenu'] as $kk=>$vv){ ?>
            <li><?php echo $svshow->link($vv['NavigationI18n']['name'],$vv['NavigationI18n']['url'],array('target'=>$vv['Navigation']['target']));?></li>
            <?php }  // foreach top2?>
          </ul>
        </li>
  <?php }?>
  <?php if(!isset($v['SubMenu']) ) { ?>
    <li><?php echo $svshow->link($v['NavigationI18n']['name'],$v['NavigationI18n']['url'],array('target'=>$v['Navigation']['target']));?></li>
        <?php }?>
        <?php } // foreach top1?>
      </ul>
  <?php }?>
  <?php if(isset($navigations['B'])&&sizeof($navigations['B'])>0){?>
    <div class="am-topbar-right am-show-sm-only am-topbar am-bottom-nav am-padding-0">
  <ul class="am-nav am-nav-pills am-topbar-nav" >
    <?php $navigations_t_count=count($navigations['B']);
      foreach($navigations['B'] as $k=>$v){?>
      <?php if(isset($v['SubMenu']) && sizeof($v['SubMenu']) >0) {  //含二级菜单 ?>
    <li class="am-dropdown am-dropdown-up" data-am-dropdown>
      <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
        <?php echo (isset($v['NavigationI18n']['name']))?$v['NavigationI18n']['name']:"-";?><span class="am-icon-caret-up" style="margin-left:7px;"></span>
      </a>
      <ul class="am-dropdown-content" style="position:absolute;margin:0">
        <li class="am-dropdown-header" style="padding:6px 10px"><?php echo $v['NavigationI18n']['name'];?></li>
        <?php foreach($v['SubMenu'] as $kk=>$vv){ ?>
          <li><?php echo $svshow->link($vv['NavigationI18n']['name'],$vv['NavigationI18n']['url'],array('target'=>$vv['Navigation']['target']));?></li>
        <?php }  // foreach top2?>
      </ul>
    </li>
  <?php }?>
  <?php if(!isset($v['SubMenu']) ) { ?>
  <li><?php echo $svshow->link($v['NavigationI18n']['name'],$v['NavigationI18n']['url'],array('target'=>$v['Navigation']['target']));?></li>
    <?php }?>
  <?php } // foreach top1?>
  </ul>
    </div>
      <?php }?>
  <?php if(count($languages)>1){?>
  <div class="am-topbar-right">
	<!--语言弹窗开始-->
      <!--<a href="javascript:void(0)" class="language_change" data-am-modal="{target: '#language',width: 330, height: 225}"><?php echo $ld['language_switcher'];?></a>-->
	  <!--语言弹窗结束-->
	  <?php //pr($languages);?>
	  <?php if(count($languages)>1){?>
	  
		<div id="language">
		
		<?php $languages_count=count($languages);$i=0;foreach($languages as $k=>$v){?>
		
		<?php echo $svshow->link(strtoupper($v['Language']['map']),(LOCALE==$k)?'javascript:void(0);':$v['Language']['url'],array('class'=>(LOCALE==$k)?'':'weixuanzhong'));?>
		<?php if($i<$languages_count-1){?>
		<div class="shuxian">|</div>
		<?php }?>
		<?php $i++;}?>
		<!-- 记录当前语言 -->
		<input type='hidden' id='local' value="<?php echo LOCALE;?>">
		<!-- 记录当前语言 end -->
		</div>
	  <?php }?>
   </div>
    <?php }?>
  <!-- 用户中心登录按钮 -->
  <?php if(constant("Version")=="allinone"){?>
    <div id="shoppingcart"><?php echo $svshow->link(empty($_SESSION['svcart']['products'])? $ld['cart'].'(0)':$ld['cart'].'('.count($_SESSION['svcart']['products']).')',"/carts");?></div>
  <?php }?> 
  <?php if(isset($_SESSION['User']['User']) && !empty($_SESSION['User']['User'])){?>
      <div class="am-topbar-right am-login-btn">
        <a class="am-btn am-btn-primary am-topbar-btn am-btn-xs" href="<?php echo $html->url('/users/index'); ?>"style="position:relative;"><span class="am-icon-user"></span> <?php echo $_SESSION['User']['User']['name'];?><span style="display:inline-block;position:absolute;top:-8px;right:-8px;min-width:18px;height:18px;background:#f37b1d;display:none;border-radius:50%;line-height:18px;" id="header_unread_message"></span></a>
      </div>
  <?php }else{?>
    <?php if(isset($configs['enable_registration_closed']) && $configs['enable_registration_closed']==0){?>
      <div class="am-topbar-right am-login-btn">
        	<a class="am-btn am-btn-primary am-topbar-btn am-btn-xs" href="<?php echo $html->url('/users/login'); ?>"><?php echo $ld['login'];?></a>
      </div>
    <?php }?>
  <?php }?>
  <!-- 用户中心登录按钮end -->
    <!-- 搜索框 -->
    <div class="am-topbar-right am-u-lg-2 am-u-md-3 am-hide-sm-only am-search" >
      <form action="<?php echo $html->url('/searchs/index'); ?>" method="get" id="am-search-form">
      <div class="am-input-group am-input-group-sm am-u-lg-12 am-u-md-11 am-u-sm-12 am-fr"  >
        <input style="outline:none;border-bottom-left-radius: 3px;border-top-left-radius: 3px;" type="text" class="am-form-field" AUTOCOMPLETE="OFF" id="search_keyword" name="keyword" placeholder="请输入关键字" value="<?php echo isset($search_keyword)?$search_keyword:''; ?>" />
        <span class="am-input-group-btn">
          <button class="am-btn am-btn-secondary am-btn-sm" style="border-bottom-right-radius: 3px;border-top-right-radius: 3px;" type="button"><span  class="am-icon-search"></span></button>
        </span>
      </div>
      <div class="am-input-group am-u-sm-12 search_date"></div>
    </form>
  </div>
     <!-- 搜索框 end -->
    </div>
  </div>
 <!-- 搜索框 end -->
 </div>
  <input type='hidden' id='local' value="<?php echo LOCALE;?>">
</header>


<div class="am-modal am-modal-no-btn" tabindex="-1" id="header_search">
  <div class="am-modal-dialog">
    <div class="am-modal-hd"><span>搜索</span>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close style="top:0">&times;</a>
    </div>
    <div class="am-modal-bd am-u-sm-12">
        <div class="am-input-group am-u-sm-12 am-input-group-sm am-fr am-search-sm">
          <input type="text" placeholder="请输入关键字" autofocus="autofocus" class="am-form-field" AUTOCOMPLETE="OFF" name="keyword" value="<?php echo isset($search_keyword)?$search_keyword:''; ?>" />
          <span class="am-input-group-btn">
            <button class="am-btn am-btn-secondary am-btn-sm" style="border:none"  type="button" ><span class="am-icon-search"></span></button>
          </span>
        </div>
       <div class="am-input-group am-u-sm-12 search_date"></div>
    </div>
  </div>
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="language">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">
    <?php echo $ld['language_switcher'];?>
      <a href="javascript: void(0)" class="am-close am-close-spin" style="top:20px;right:0px" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
  <!-- 多语言选择 -->
  <?php if(count($languages)>1){?>
    <div id="language">
    <?php $languages_count=count($languages);$i=0;foreach($languages as $k=>$v){?>
    
    <?php echo $svshow->link(strtoupper($v['Language']['name']),(LOCALE==$k)?'javascript:void(0);':$v['Language']['url'],array('class'=>(LOCALE==$k)?'color':'am-btn am-btn-default'));?>
    <?php if($i<$languages_count-1){?>
    
    <?php }?>
    <?php $i++;}?>
    <!-- 记录当前语言 -->
    <input type='hidden' id='local' value="<?php echo LOCALE;?>">
    <!-- 记录当前语言 end -->
    </div>
  <?php }?>
  <!-- 多语言选择 end -->
    </div>
  </div>
</div>
<script type="text/javascript">
if(js_login_user_data!=null)get_unread_message();

function get_unread_message(){
  $.ajax({
    url: web_base+"/user_socials/get_unread_message/",
    type:"POST",
    data:{},
    dataType:"json",
    success: function(data){
      if(data&&data>0){
        $("#header_unread_message").show();
        $("#header_unread_message").html(data);
      }
    }
  });
}

function ajax_get_org_info(){
  var content = '';
  $.ajax({
    url: web_base+"/organizations/ajax_get_org_info/",
    type:"POST",
    data:{},
    dataType:"json",
    success: function(data){
      content = '<div style="max-height:300px;overflow:auto;">';
      if(data.length>0){
        for(var i = 0;i<data.length;i++){
          if(data[i].Organization.logo != null){
            content += '<div style="" class="org_list_all"><div style="cursor:pointer;" class="org_list_top" onclick="jump_org('+data[i].Organization.id+')"><img src="'+data[i].Organization.logo+'" style="max-height:60px;" title="'+data[i].Organization.name+'"></div><div style="" class="org_list_bottom">'+data[i].Organization.name+'</div></div>';
          }else{
            content += '<div style="" class="org_list_all"><div style="cursor:pointer;" class="org_list_top" onclick="jump_org('+data[i].Organization.id+')"><img src="/theme/default/images/default.png" style="max-height:60px;" title="'+data[i].Organization.name+'"></div><div style="" class="org_list_bottom">'+data[i].Organization.name+'</div></div>';
          }
        }
      }else{
        content += '<span style="line-height:60px;">暂无组织</span>';
      }
      content += '</div><div class="am-text-left"><a href="<?php echo $html->url("/organizations/view/0") ?>" style="margin-top:10px;float:right;margin-right:15px;" class="am-btn am-btn-secondary am-btn-sm"><i class="am-icon-plus"></i> 新建组织</a><div class="am-cf"></div></div>';
      $("#my_org_modal .am-modal-bd").html(content);
      $("#my_org_modal").modal();
    }
  });
}

function jump_org(org_id){
  	window.location.href=web_base+'/organizations/view/'+org_id;
}
</script>