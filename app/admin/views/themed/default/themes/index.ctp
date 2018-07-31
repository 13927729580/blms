<style>
	.am-list>li{margin-bottom:0;border-style: none;}
	.admin-sidebar-list li a{color:#fff;background-color: #5eb95e;}
	.am-list > li > a.am-active, .am-list > li > a.am-active:hover, .am-list > li > a.am-active:focus{font-weight: bold;}
	.scrollspy-nav.am-sticky.am-animation-slide-top{width: 100%;}
	.am-sticky-placeholder{margin-top: 10px;}
	.scrollspy-nav {top: 0;z-index: 100;background: #5eb95e;width: 100%;padding: 0 10px}
	.scrollspy-nav ul {margin: 0;padding: 0;}
	.scrollspy-nav li {display: inline-block;list-style: none;}
	.scrollspy-nav a {color: #eee;padding: 10px 20px;display: inline-block;}
	.scrollspy-nav a.am-active {color: #fff;font-weight: bold;}
	.crumbs{padding-left:0;margin-bottom:22px;}
	.btnouter{margin:0;}
</style>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9  am-detail-view" id="accordion" style="width: 100%;">
		<!-- 导航 -->
		<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
		    <ul>
			   	<li><a href="#current"><?php echo $ld['current_template'];?></a></li>
        		<li><a href="#available"><?php echo $ld['available_templates'];?></a></li>
			</ul>
		</div>
		<!-- 导航结束 -->
	<div id="current" class="am-panel am-panel-default">
		<div class="am-panel-hd">
		    <h4 class="am-panel-title"><?php echo $ld['current_template']; ?></h4>
		</div>
		<div id="export_configuration" class="am-panel-collapse am-collapse am-in">
		    <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
		        <table class="am-table">
		            <tr><td>
		                    <div style="float:left;margin:10px;">
		                        <?php $curr_template_img = $curr_template['screenshot'];
		                        if(isset($curr_template['template_style']) && $curr_template['template_style'] != ""){
		                            $style = explode("_",$curr_template['screenshot']);
		                            $s_type = explode(".",$style[(sizeof($style)-1)]);
		                            $s_type[0] = $curr_template['template_style'];
		                            $style[(sizeof($style)-1)] = implode(".",$s_type);
		                            $curr_template_img = implode("_",$style);
		                        }
		                        if(isset($duoyu[$curr_template['name']]["template_img"][$curr_template_img])){
		                            $img_foo=$duoyu[$curr_template['name']]["template_img"][$curr_template_img];
		                        }else{
		                            $img_foo=@array_values($duoyu[$curr_template['name']]["template_img"]);
		                            $img_foo=$img_foo[0];
		                        }
		                        $img_foo=$img_foo==""?$configs['shop_default_img']:$img_foo;
		                        echo @$html->image($img_foo,array('height'=>'190','id'=>'theme_img'));
		                        ?>
		                    </div>
		                    <div>
		                        <p><?php if(isset($curr_template['description'])){ echo $curr_template['description'];}
		                            if(isset($curr_template['version'])){ echo '&nbsp;'.$curr_template['version'];
		                            }?>
		                        </p>
		                        <p><?php if(isset($curr_template['author']))echo $html->link($curr_template['author'],$curr_template['author_uri'],array('class'=>" am-btn am-btn-default am-btn-sm"),false,false);?></p>
		                        <p><?php if(isset($curr_template['desc']))echo $curr_template['desc']?>
		                        <p><?php if(isset($curr_template['style']) && sizeof($curr_template['style'])>0){
		                                if(!empty($curr_template['style'])){
		                                    foreach($curr_template['style'] as $key=>$val){
		                                        if($val != ""){
		                                            if(isset($curr_template['template_style']) && $curr_template['template_style'] == $val){
		                                            }else{
		                                                if(isset($curr_template['template_style']) ){
		                                                    $style = explode("_",$curr_template['screenshot']);
		                                                    $s_type[0] = $val;
		                                                    $this_template_img = implode("_",$style);
		                                                    ?><span onMouseOver="javascript:onSOver('theme_img','<?php echo $duoyu[$curr_template['name']]['template_img'][$val];?>');" onMouseOut="onSOut('theme_img','<?php echo $duoyu[$curr_template['name']]['template_img'][$val]?>');" onclick="select_style('<?php echo $curr_template['name'];?>','<?php echo $val?>');" style="margin:0 5px;"><?php echo $html->image('themes/'.$val.'.gif',array("title"=>$val));?></span><?php
		                                                }}}}}}?></p>
		                        <?php if(isset($curr_template)&&!empty($curr_template)){
		                            echo $html->link($ld['preview'],"/../?themes=".$curr_template['name'],array('class'=>" am-btn am-btn-default am-btn-sm"),false);?>&nbsp;<?php if($svshow->operator_privilege("themes_edit")&&isset($curr_template['id'])){echo $html->link($ld['edit'],"/themes/view/".$curr_template['id'],array('class'=>" am-btn am-btn-default am-btn-sm"),false);
		                            	echo "&nbsp;";
		                            	echo $html->link($ld['copy'],"javascript:void(0);",array('class'=>"am-btn am-btn-default am-btn-sm","onclick"=>"set_copy_theme(".$curr_template['id'].")"),false);}?>
		                        <?php }if(isset($page_type_list)&&sizeof($page_type_list)>0){?>
		                            <div class="module">
		                                <?php foreach($page_type_list as $k=>$v){
		                                    $name=""; if($v['PageType']['page_type']==1){
		                                        $name=$ld['mobilephone'];
		                                    }elseif($v['PageType']['page_type'] == 0){
		                                        $name=$ld['computer'];
		                                    }
		                                    $name.=$v['PageType']['code'];?><br/>
		                                    <?php echo $html->link($name.$ld['edit'],"/page_types/view/{$v['PageType']['id']}/{$curr_template['name']}",array('class'=>" am-btn am-btn-default am-btn-sm",'style'=>"margin-top:5px;" ),false);
		                                }?>
		                            </div>
		                        <?php }?>
		                        <input type='hidden' id='defs' value='<?php echo $curr_template["name"];?>'>
		                    </div>
		                </td></tr>
		        </table>
		    </div>
		</div>
	</div>
		
	
		
	<div id="available" class="am-panel am-panel-default">
	    <div class="am-panel-hd">
	        <h4 class="am-panel-title">
	            <?php echo $ld['available_templates']?>
	        </h4>
	    </div>
	    <div id="available_templates" class="am-panel-collapse am-collapse am-in">
	        <div class="am-panel-bd am-form-detail am-form am-form-horizontal am-btn-group-xs" style="padding-bottom:0;">
	            <?php if($svshow->operator_privilege("themes_edit")){?>
	                <p class="action-span am-text-right am-btn-group-xs" >
	                    <a class="am-btn am-btn-warning am-radius am-btn-sm" href="<?php echo $html->url('view/'); ?>">
	                        <span class="am-icon-plus"></span>
	                        <?php echo $ld['add_module'] ?>
	                    </a>
	                </p>
	            <?php }?>
	            <table class="am-table">
	                <thead>
	                <tr>
	                    <th><?php echo $ld['picture'] ?></th>
	                    <th><?php echo $ld['template'].$ld['name']?></th>
	                    <th><?php echo $ld['template'].$ld['description']?></th>
	                    <th><?php echo $ld['module_style']?></th>
	                    <th><?php echo $ld['status']?></th>
	                    <th><?php echo $ld['operate']?></th>
	                </tr>
	                </thead>
	                <tbody>
	                <?php if(isset($available_templates) && sizeof($available_templates)>0){foreach($available_templates as $k=>$themed){ ?>
	                    <tr>
	                        <td><?php echo $themed['Template']['template_img']!=''?@$html->image($themed['Template']['template_img'],array('class'=>'theme_img')):'' ?></td>
	                        <td><?php echo $themed['Template']['name'];?></td>
	                        <td><?php if($svshow->operator_privilege("themes_edit")){ ?>
	                                <span onclick="javascript:listTable.edit(this, 'themes/update_themes_desc/', <?php echo $themed['Template']['id']?>)"><?php echo $themed['Template']['description']; ?></span>
	                            <?php }else{echo $themed['Template']['description'];}?></td>
	                        <td><?php if($svshow->operator_privilege("themes_edit")){ ?>
	                                <span onclick="javascript:listTable.edit(this, 'themes/update_themes_style/', <?php echo $themed['Template']['id']?>)"><?php echo $themed['Template']['template_style']; ?></span>
	                            <?php }else{echo $themed['Template']['template_style'];}?></td>
	                        <td style="text-align: center;"><?php
	                            if($themed['Template']['status']==1){
	                                if($svshow->operator_privilege("themes_edit")){
	                                    echo '<div style="cursor:pointer;color:#5eb95e" class="am-icon-check" onclick=listTable.toggle(this,"themes/update_themes_status",'.$themed["Template"]["id"].')></div>';
	                                }else{
	                                    echo '<div style="color:#5eb95e" class="am-icon-check"></div>';
	                                }
	                            }else if($themed['Template']['status'] == 0){
	                                if($svshow->operator_privilege("themes_edit")){
	                                    echo '<div style="cursor:pointer;color:#dd514c" class="am-icon-close" onclick=listTable.toggle(this,"themes/update_themes_status",'.$themed["Template"]["id"].')></div>';
	                                }else{
	                                    echo '<div style="color:#dd514c" class="am-icon-close"></div>';
	                                }
	                            }?>
	                        </td>
	                        <td class="am-action">
	                            <?php if($svshow->operator_privilege("themes_edit")){?>
	                                <a class="am-btn am-btn-success am-btn-xs am-seevia-btn " target='_blank' href="<?php echo $html->url('/../?themes='.$themed['Template']['name'],array("target"=>"_blank")); ?>">
	                                    <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?>
	                                </a>
	                            <?php }
	                            if($svshow->operator_privilege("themes_edit")){?>
	                                <a class="am-btn am-btn-default am-btn-xs am-seevia-btn-edit" href="javascript:void(0);"  onclick="set_copy_theme('<?php echo $themed['Template']['id']; ?>')">
	                                    <span class="am-icon-copy"></span> <?php echo $ld['copy']; ?>
	                                </a>
	                            <?php }
	                            if($svshow->operator_privilege("themes_edit")){?>
	                                <a class="am-btn am-btn-default am-btn-xs  am-seevia-btn-edit" href="<?php echo $html->url('/themes/view/'.$themed['Template']['id']); ?>">
	                                    <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
	                                </a>
	                            <?php  }
	                            if($svshow->operator_privilege("themes_view")){?>
	                                <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'themes/remove/<?php echo $themed['Template']['id'] ?>')">
	                                    <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
	                                </a>
	                            <?php }?>
	                        </td>
	                    </tr>
	                <?php }}?>
	                </tbody>
	            </table>
	        </div>
	    </div>
	</div>
	
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="copy_theme">
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style="border-bottom: 1px solid #ddd;"><?php echo $ld['copy'].$ld['templates'] ?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <form class="am-form">
		<div class="am-modal-bd">
			<div class="am-g am-margin-top-sm am-margin-bottom-sm">
				<div class="am-u-lg-4 am-u-md-4 am-u-sm-4 div_lineheight"><?php echo $ld['template'].$ld['name']?>：</div>
				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
						<input type="text" name="copy_name" id="copy_name" onkeydown="templatesName(event)" />
				</div>
				<div class="am-u-sm-2">
					<em class="color_red">*</em>
				</div>
			</div>
			<div class="am-g">
				<div class="am-u-lg-4 am-u-md-5 am-u-sm-5 div_lineheight">&nbsp;</div>
				<div class="am-u-lg-8 am-u-md-7 am-u-sm-7 am-fl am-text-left"><input type="hidden" id="copy_theme_id" value="" /><input class="am-btn am-btn-success am-radius am-btn-sm" type="button" value="<?php echo $ld['copy']?>" onclick="copy_theme()">
				</div>
			</div>
		</div>
	</form>
    </div>
</div>

<script type="text/javascript">
    //造不刷新假象
    function tmp_show(){
        var sUrl = admin_webroot+"themes/tmp_show/?status=1";
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'html',
            success: function (html) {
                document.getElementById("tablemain").innerHTML=html;
            }
        });
    }

    //卸载
    function deletethemed(a){
        var sUrl = admin_webroot+"themes/deletethemed/?status=1";
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {code:a},
            success: function (json) {
                tmp_show();
            }
        });
    }

    //安装
    function installthemed(a){
        var sUrl = admin_webroot+"themes/installthemed/?status=1";
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {code:a},
            success: function (json) {
                tmp_show();
            }
        });
    }

    //选择默认
    function use_theme(a){
        var sUrl = admin_webroot+"themes/usethemed/?status=1";
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {code:a},
            success: function (json) {
                tmp_show();
            }
        });
    }

    //选色模板
    function select_style(theme_name,template_style){
        var sUrl = admin_webroot+"themes/select_style/"+theme_name;
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {template_style:template_style},
            success: function (json) {
                tmp_show();
            }
        });
    }

    function show_css_edit2(thm){
        window.location.href=admin_webroot+"themes/edit_css/"+thm;
    }

    function show_css_edit(thm){
        var sUrl = admin_webroot+"themes/show_css/";
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {code:thm,type:"show"},
            success: function (result) {
                $("#show_css").html(result.css);
                $("#show_css").val(result.css);
            }
        });
    }

    //选色特效
    function onSOver(theme_img,img){
        document.getElementById(theme_img).src = img
    }

    function onSOut(theme_img,img){
        document.getElementById(theme_img).src = img
    }

    function wopen($str){
        window.open ($str,'newwindow','height=800,width=1200,top=0,left=0,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no')
    }

    $(function(){
        $(".theme_img").each(function(){
            setImg($(this));
        });
    });

    function setImg(obj){
        var W=H=100;
        var img_src=obj.attr("src");
        var img=new Image();
        img.onload=function(){
            img_w=100;
            img_h=100;
            if(img.width/img.height >= img_w/img_h){
                if(img.width > img_w)
                {
                    W=img_w;
                    H=(img.height*img_w)/img.width;
                }
                else
                {
                    W=(img.width*img_h)/img.height;
                    H=img_h;
                }
            }else{
                if(img.width > img_w)
                {
                    W=img_w;
                    H=(img.height*img_w)/img.width;
                }else{
                    W=(img.width*img_h)/img.height;
                    H=img_h;
                }
            }
            if(W>100){
                W=100;
                H=(img.height*100)/img.width;
            }else if(H>100){
                W=(img.width*100)/img.height;
                H=100;
            }
            obj.css("width",W+"px").css("height",H+"px");
        }
        img.src=img_src;
    }
    /* ---------------模板复制------------- */
    function set_copy_theme(id){
    	 $("#copy_theme").modal({width: 400, height: 225});
        document.getElementById("copy_theme_id").value=id;
    }

    function copy_theme(){
        var theme_id=document.getElementById("copy_theme_id").value;
        var theme_name=document.getElementById("copy_name").value;
        if(theme_id!=""){
            if(theme_name!=""){
                var sUrl = admin_webroot+"themes/check_themes_name/";
                $.ajax({
                    type: "POST",
                    url: sUrl,
                    dataType: 'json',
                    data: {template_name: theme_name},
                    success: function (result) {
                        if(result.code==1){
                            window.location.href=admin_webroot+"themes/templatecopy/"+theme_id+"/"+theme_name;
                        }else{
                            alert(result.msg);
                        }
                    }
                });
            }else{
                alert("<?php printf($ld['name_not_be_empty'],$ld['template'].$ld['name']); ?>");
            }
        }else{
            alert("<?php echo $ld['add_failure'] ?>");
        }
    }

    function templatesName(e){
        var keynum;
        if(window.event) // IE
        {
            keynum = e.keyCode
        }
        else if(e.which) // Netscape/Firefox/Opera
        {
            keynum = e.which
        }
        if(keynum==13){
            copy_theme();
        }
    }
    /* ---------------模板复制 end------------- */
</script>