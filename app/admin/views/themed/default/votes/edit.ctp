<?php
/*****************************************************************************
 * SV-Cart ÔÚÏß¹ÜÀíÐÂÔö
 * ===========================================================================
 * °æÈ¨ËùÓÐ ÉÏº£ÊµçâÍøÂç¿Æ¼¼ÓÐÏÞ¹«Ë¾£¬²¢±£ÁôËùÓÐÈ¨Àû¡£
 * ÍøÕ¾µØÖ·: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * Õâ²»ÊÇÒ»¸ö×ÔÓÉÈí¼þ£¡ÄúÖ»ÄÜÔÚ²»ÓÃÓÚÉÌÒµÄ¿µÄµÄÇ°ÌáÏÂ¶Ô³ÌÐò´úÂë½øÐÐÐÞ¸ÄºÍÊ¹ÓÃ£»
 * ²»ÔÊÐí¶Ô³ÌÐò´úÂëÒÔÈÎºÎÐÎÊ½ÈÎºÎÄ¿µÄµÄÔÙ·¢²¼¡£
 * ===========================================================================
 * $¿ª·¢: ÉÏº£Êµçâ$
 * $Id$
 *****************************************************************************/
?>
<style>
	.am-radio, .am-checkbox{display:inline;}
	 em{color:red;}
	.am-checkbox {margin-top:0px; margin-bottom:0px;}
	label{font-weight:normal;}
	.am-form-horizontal .am-radio{padding-top:0;position:relative;top:5px;}
	.am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
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
<?php echo $form->create('votes',array('action'=>'/edit/'.$vote_info["Vote"]["id"],'id'=>'vote_form'));?>
<!-- 导航条 -->
<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
    <ul>
        <li><a href="#basic_info"><?php echo $ld['basic_information']?></a></li>
        <?php if($svshow->operator_privilege("votes_option_list")){?>
            <li><a href="#vote"><?php echo $ld['vote_options']?></a></li>
        <?php }?>
    </ul>
</div>
<!-- 右上角按钮 -->
<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
    <input class="am-btn am-btn-success am-radius am-btn-sm" type="button" value="<?php echo $ld['d_submit']?>" onclick='chrck_form()' style="margin-right:20px;"/> 
    <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-detail-view" id="accordion"  style="width:100%;padding-left:0;padding-right:0;">
    <div id="basic_info" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title">
                <?php echo $ld['basic_information']?>
            </h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <div>
                    <div class="am-form-group">
                        <div  class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="padding-top:0px;padding-bottom:50px;" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['vote_investigat_subject']?></div>
                  
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                 
                            <div class="am-u-lg-6 am-u-md-6 am-u-sm-6" style="margin-bottom:1rem;">
                                <input style="float:left;" type="text"  name="data[VoteI18n][<?php echo $k?>][name]" value="<?php echo @$vote_info['VoteI18n'][$v['Language']['locale']]['name']?>" /></dd></dl>
                                <input type="hidden" name="data[VoteI18n][<?php echo $k?>][id]" value="<?php echo @$vote_info['VoteI18n'][$v['Language']['locale']]['id']?>" />
                                <input type="hidden" name="data[VoteI18n][<?php echo $k?>][locale]" value="<?php echo $v['Language']['locale']?>" /><?php if(sizeof($backend_locales)>1){?>
                                </div>
                                <div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
                                <span class="lang"><?php echo $ld[$v['Language']['locale']];?></span><?php }?><em>*</em>
                            </div>
                  
                        <?php }}?>
                    </div>
                    <div class="am-form-group">
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="padding-top:0px;padding-bottom:120px;" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['subject_description']?></div>
                 
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
              
                            <div class="am-u-lg-6 am-u-md-6 am-u-sm-6" style="margin-bottom:1rem;">
                                <textarea style="float:left;" id="<?php echo $v['Language']['locale'];?>_txt" name="data[VoteI18n][<?php echo $k?>][description]" ><?php echo @$vote_info['VoteI18n'][$v['Language']['locale']]['description']?></textarea><?php if(sizeof($backend_locales)>1){?>
                            </div>
                            <div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
                                <span class="lang" style="margin-top:0px;" ><?php echo $ld[$v['Language']['locale']];?></span><?php }?><em>*</em>
                            </div>
                  
                        <?php }}?>
                    </div>
                    <div class="am-form-group">
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="padding-top:0px"><?php echo $ld['start_date']?></div>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <input style="" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="data[Vote][start_time]" value="<?php echo date('Y-m-d',strtotime($vote_info['Vote']['start_time']));?>" />
                        </div>
                    </div>
                    <div class="am-form-group">
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="padding-top:0px"><?php echo $ld['end_date']?></div>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <input style="" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="data[Vote][end_time]" value="<?php echo date('Y-m-d',strtotime($vote_info['Vote']['end_time']));?>" />
                        </div>
                    </div>
                    <div class="am-form-group">
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="padding-top:7px"><?php echo $ld['choose_more']?></div>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <label class="am-radio am-success" style="padding-top:2px;"><input type="radio"  name="data[Vote][can_multi]" value="0" data-am-ucheck <?php if($vote_info['Vote']['can_multi']==0){ echo "checked";}?>  /><?php echo $ld['yes']?></label>
                        	<label style="margin-left:10px;padding-top:2px;" class="am-radio am-success"><input type="radio" name="data[Vote][can_multi]" value="1" data-am-ucheck <?php if($vote_info['Vote']['can_multi']==1){ echo "checked";}?> /><?php echo $ld['no']?><em style="top:3px;">*</em></label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-3" style="padding-top:7px"><?php echo $ld['valid']?></div>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <label class="am-radio am-success" style="padding-top:2px;"><input type="radio"  name="data[Vote][status]" value="1" data-am-ucheck <?php if($vote_info['Vote']['status']==1){ echo "checked";}?>  /><?php echo $ld['yes']?></label>
                        	<label style="margin-left:10px;padding-top:2px;" class="am-radio am-success"><input type="radio" name="data[Vote][status]" value="0" data-am-ucheck <?php if($vote_info['Vote']['status']==0){ echo "checked";}?> /><?php echo $ld['no']?><em style="top:2px;">*</em></label>
                        </div>
                    </div>
               
                </div>
            </div>
        </div>
    </div>
    <?php if($svshow->operator_privilege("votes_option_list")){?>
        <div id="vote" class="am-panel am-panel-default am-g">
            <div class="am-panel-hd">
                <h4 class="am-panel-title">
                    <?php echo $ld['vote_options']?>
                </h4>
            </div>
            <div id="vote_options" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding:0px;">                      
                        <div class="am-form-group" style="margin-top:0.5rem;border-bottom:1px solid #ddd;font-weight:700;">
                            <div align="left" class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['status']?></div>
                            <div align="left" class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $ld['title']?></div>
                            <div align="left" class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $ld['options_des']?></div>
                            <div align="left" class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['option_votes']?></div>
                            <div align="left" class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['sort']?></div>
                        </div>
                        
                    <div id="attrTable" class="am-table">
                        
                        <?php if(isset($voteoption_list)&&sizeof($voteoption_list)>0){ foreach($voteoption_list as $vk=>$vo){?>
                            <div class="am-form-group">
                                <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                                    <a href='javascript:;' onclick='removeaddr(this)' style="width:35px;">[-]</a>
                                    <input type="checkbox"  name="data[VoteOption][status][<?php echo $vk;?>]" value="1" <?php if($vo['VoteOption']['status']==1){ echo "checked";}?>>
                                </div>
                                <div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
                                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                                        <p><input style="width:200px;float:left;" type="text" name="data[VoteOptionI18n][<?php echo $vk;?>][<?php echo $v['Language']['locale'].'_name';?>][]" value="<?php echo isset($vo['VoteOptionI18n'][$v['Language']['locale']]['name'])? $vo['VoteOptionI18n'][$v['Language']['locale']]['name']:''; ?>" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']];?></span><?php }?><em style="color:red;">*</em></p>
                                        <?php }} ?>
                                </div>
                                <div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
                                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                                        <p style="margin:0;"><textarea style="width:200px;"  name="data[VoteOptionI18n][<?php echo $vk?>][<?php echo $v['Language']['locale'].'_description'; ?>][]" ><?php echo isset($vo['VoteOptionI18n'][$v['Language']['locale']]['description'])?$vo['VoteOptionI18n'][$v['Language']['locale']]['description']:''?></textarea><?php if(sizeof($backend_locales)>1){?><span style="position:relative;top:-40px;left:210px;" class="lang"><?php echo $ld[$v['Language']['locale']];?></span><?php }?></p>
                                        <?php }} ?>
                                </div>
                                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                                    <input type="hidden" name="data[VoteOption][option_count][<?php echo $vk?>]" value="<?php echo $vo['VoteOption']['option_count'];?>">
                                    <span><?php echo $vo['VoteOption']['option_count'];?></span>
                                </div>
                                <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:15px">
                                    <input style="width: 2.5em;" type="text" name="data[VoteOption][orderby][<?php echo $vk?>]" value="<?php echo $vo['VoteOption']['orderby']?>" >
                                </div>
                            </div>
                           
                        <?php }} if(isset($vk)){ $vk++; }else{ $vk=0; }?>
                        <div class="am-form-group">
                            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                                <a href='javascript:;' onclick='addaddr(this,<?php echo $vk?>)' style="width:35px;">[+]</a>
                                <input type="checkbox" name="data[VoteOption][status][<?php echo $vk?>]" value="1">
                            </div>
                            <div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
                                <?php
                                if(isset($backend_locales)&&sizeof($backend_locales)>0){
                                    foreach ($backend_locales as $k => $v){?>
                                        <p><input style="width:200px;float:left;" type="text" name="data[VoteOptionI18n][<?php echo $vk?>][<?php echo $v['Language']['locale'];?>_name][]" value=""/><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']];?></span><?php }?>
                                            <em style="color:red;top:3px;">*</em></p>
                                        <?php }}?>
                            </div>
                            <div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
                                <?php
                                if(isset($backend_locales)&&sizeof($backend_locales)>0){
                                    foreach ($backend_locales as $k => $v){?>
                                        <p style="margin:0px;"><textarea style="width:200px;" name="data[VoteOptionI18n][<?php echo $vk?>][<?php echo $v['Language']['locale'];?>_description][]" ></textarea><?php if(sizeof($backend_locales)>1){?><span class="lang" style="position:relative;top:-40px;left:210px;"><?php echo $ld[$v['Language']['locale']];?></span><?php }?></p>
                                        <?php }}?>
                            </div>
                            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="padding-top:15px;">
                                 <span>0</span>
                            </div>
                            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                                <input style="width: 2.5em;" type="text" name="data[VoteOption][orderby][<?php echo $vk?>]" value="" >
                            </div>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    <?php }?>
</div>
<?php echo $form->end();?>
<script type="text/javascript">
    function chrck_form(){
        if(exist('chi_txt')){
            if(!check_null('chi_txt')){alert('ÇëÌîÐ´ÖÐÎÄÖ÷ÌâÃèÊö');return;}
        }else if(exist('eng_txt')){
            if(!check_null('eng_txt')){alert('ÇëÌîÐ´Ó¢ÎÄÖ÷ÌâÃèÊö');return;}
        } else if(exist('jpn_txt')){
            if(!check_null('jpn_txt')){alert('ÇëÌîÐ´ÈÕÎÄÎÄÖ÷ÌâÃèÊö');return;}
        }
        var form=document.getElementById('vote_form');
        form.submit();
    }

    function check_null(id){
        var c=document.getElementById(id).value;
        if(c.replace(/(^\s*)|(\s*$)/g,"")==""){return false;}
        else{return true;}
    }

    function exist(id){
        var s=document.getElementById(id);
        if(s){return true}
        else{return false}
    }

    /**
     * ÐÂÔöÒ»¸ö¹æ¸ñ
     */
    function addaddr(obj,k){
        var src = obj.parentNode.parentNode;
        var idx = rowindex(src);
        var tbl = document.getElementById('attrTable');
        var row = tbl.insertRow(idx + 1);
        var cell = row.insertCell(-1);
        var img_str = src.cells[0].innerHTML.replace(/(.*)(addaddr)(.*)(\[)(\+)/i, "$1removeaddr$3$4-").replace("data[VoteOption][status]["+k+"]", "data[VoteOption][status]["+(parseInt(tbl.rows.length)-2)+"]");
        cell.innerHTML = img_str;
        var t1 = '['+k+']';
        for(var i=1;i<5;i++){
            if(i==1||i==2||i==4){
                //var reg = eval("/"+k+"/g");
                //row.insertCell(-1).innerHTML = src.cells[i].innerHTML.replace(reg, (parseInt(tbl.rows.length)-2));
                row.insertCell(-1).innerHTML = src.cells[i].innerHTML.replace(t1, '['+(parseInt(tbl.rows.length)-2)+']').replace(t1, '['+(parseInt(tbl.rows.length)-2)+']').replace(t1, '['+(parseInt(tbl.rows.length)-2)+']');
            }else{
                row.insertCell(-1).innerHTML=src.cells[i].innerHTML;
            }
        }
    }

    function removeaddr(obj){
        var row = rowindex(obj.parentNode.parentNode);
        var tbl = document.getElementById('attrTable');
        tbl.deleteRow(row);
    }
</script>