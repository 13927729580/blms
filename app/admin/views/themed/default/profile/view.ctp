<style>
    .am-radio, .am-checkbox{display:inline;}
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
<?php echo $form->create('Profile',array('action'=>'/view/','name'=>"ProfileForm","type"=>"POST","onsubmit"=>"return checkfrom();"));?>
<input type="hidden" id="data[CategoryType][id]"  name="data[Profile][id]" value="<?php echo isset($profile_data['Profile']['id'])?$profile_data['Profile']['id']:0 ?>" />
<!-- 导航条 -->
<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
    <ul>
        <li><a href="#export"><?php echo $ld['export_configuration'];?></a></li>
        <?php if(!empty($profile_data['Profile'])){ ?>
            <li><a href="#field"><?php echo $ld['field_list'];?></a></li>
        <?php }?>
    </ul>
</div>
<!-- 右上角按钮 -->
<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
    <input style="margin-left: 0;" class="am-btn am-btn-success am-radius am-btn-sm" type="submit" value="<?php echo $ld['submit'];?>" style="margin-right:20px;" /> 
    <input style="margin-left: 0;" class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['reset'];?>" />
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9  am-detail-view" id="accordion"  style="width:100%;padding-left:0;padding-right:0;">
    <div id="export" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title">
                <?php echo $ld['export_configuration']?>
            </h4>
        </div>
        <div id="export_configuration" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <div class="am-g">
                    
					<div class="am-form-group">
                       <div style="padding-top:6px;" class="am-u-lg-2 am-u-md-2 am-u-sm-3" rowspan="2"><?php echo $ld['system'] ?>
                       </div>
                   
                        <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
                           <select data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}" name="data[Profile][system_code]">
                            <option value=""><?php echo $ld['please_select']; ?></option>
                            <?php if(isset($SystemList)&&sizeof($SystemList)>0){foreach($SystemList as $v){ ?>
                            <option value="<?php echo $v; ?>" <?php echo isset($profile_data['Profile']['system_code'])&&$profile_data['Profile']['system_code']==$v?'selected':''; ?>><?php echo $v; ?></option>
                            <?php }} ?>
                        </select>
                        </div> 
                    </div>
                    <div class="am-form-group">  
                        <div style="padding-top:6px;" class="am-u-lg-2 am-u-md-2 am-u-sm-3" rowspan="2"><?php echo $ld['module'] ?></div>                 
                        <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
                            <input  style="float:left;" type='text' name="data[Profile][module_code]" value="<?php echo isset($profile_data['Profile']['module_code'])?$profile_data['Profile']['module_code']:''; ?>" />
                        </div>
                    </div>
                    <div class="am-form-group">
                        <div style="padding-top:6px;" class="am-u-lg-2 am-u-md-2 am-u-sm-3" rowspan="2"><?php echo $ld['classification'];?></div>
                  
                        <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
                            <input style="float:left;" type="text" id="group" name="data[Profile][group]" value="<?php echo empty($profile_data['Profile']['group'])?'':$profile_data['Profile']['group']?>">
                        </div>
                        <em style="padding-left: 0;padding-top: 10px;display: inline-block;">*</em>
                    </div>
                    <div class="am-form-group">
                        <div style="padding-top:6px;" class="am-u-lg-2 am-u-md-2 am-u-sm-3" rowspan="2"><?php echo $ld['code'];?></div>
                        <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
                            <input style="float:left;" type="text" id="code" name="data[Profile][code]" value="<?php echo empty($profile_data['Profile']['code'])?'':$profile_data['Profile']['code']?>">
                        </div>
                        <em style="padding-left: 0;padding-top: 10px;display: inline-block;">*</em>
                    </div>
                    <div class="am-form-group">
                        <div style="padding-top:6px;min-height:55px;" class="am-u-lg-2 am-u-md-2 am-u-sm-3" rowspan="<?php echo isset($backend_locales)?count($backend_locales)+1:1;?>"><?php echo $ld['name'];?></div>
                   
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                        
                            <div class="am-u-lg-5 am-u-md-5 am-u-sm-5" style="margin-bottom:0.8rem;">
                                <input type="hidden" name="data[ProfileI18n][<?php echo $k;?>][locale]" value="<?php echo $v['Language']['locale'] ?>"><input style="float:left;" type="text" id="profile_name<?php echo $v['Language']['locale'];?>" name="data[ProfileI18n][<?php echo $k;?>][name]" value="<?php echo isset($profile_data['ProfileI18n'][$v['Language']['locale']]['name'])?$profile_data['ProfileI18n'][$v['Language']['locale']]['name']:'';?>" /><?php if(sizeof($backend_locales)>1){?>
                            </div>
                            <div style="height: 45px;padding-left: 0;padding-top: 7px;"><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?><em>*</em></div>
                    <?php }}?>
                </div>
                    <div class="am-form-group">
                        <div  style="padding-top:6px;height: 300px;" class="am-u-lg-2 am-u-md-2 am-u-sm-3" rowspan="<?php echo isset($backend_locales)?count($backend_locales)+1:1;?>"><?php echo $ld['description'];?></div>
                   
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                        <div style="overflow:hidden;">
                            <div class="am-u-lg-6 am-u-md-6 am-u-sm-7" style="margin-bottom:0.8rem;margin-top:0;">
                                <textarea style="float:left;height: 150px;" id="profile_description<?php echo $v['Language']['locale'];?>" name="data[ProfileI18n][<?php echo $k;?>][description]"><?php echo isset($profile_data['ProfileI18n'][$v['Language']['locale']]['description'])?$profile_data['ProfileI18n'][$v['Language']['locale']]['description']:'';?></textarea><?php if(sizeof($backend_locales)>1){?>
                            </div>
                            <div style="padding-left: 0;padding-top: 0px;margin-top:0;"><span class="lang" style="margin-top:12px;"><?php echo $ld[$v['Language']['locale']]?></span><?php }?><em>*</em>
                            </div>
                            
                        </div>
                            
                     
                    <?php }}?>
                </div>
                    <div class="am-form-group">
                        <div style="padding-top:6px;" class="am-u-lg-2 am-u-md-2 am-u-sm-3" rowspan="2"><?php echo $ld['sort'];?></div>
                    
                        <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
                            <input style="" type="text" id="name" name="data[Profile][orderby]" value="<?php echo empty($profile_data['Profile']['orderby'])?'50':$profile_data['Profile']['orderby']?>">
                        </div>
                    </div>
                    <div class="am-form-group">
                        <div style="padding-top:6px" class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['status'];?></div>
                        <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
                            <label class="am-radio am-success" style="padding-top:2px;"><input type="radio" value="1" data-am-ucheck name="data[Profile][status]" checked/><?php echo $ld['yes'];?></label>
                            <label style="margin-left:10px;padding-top:2px;" class="am-radio am-success"><input type="radio" name="data[Profile][status]" value="0" data-am-ucheck <?php if(isset($profile_data['Profile'])&&$profile_data['Profile']['status'] == 0){ echo "checked"; } ?>/><?php echo $ld['no'];?></label>
                        </div>
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>
    <?php if(!empty($profile_data['Profile'])){ ?>
        <div id="field" class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title">
                    <?php echo $ld['field_list']?>
                </h4>
            </div>
            <div id="field_list" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                    <p class="am-u-md-12">
                        <?php if(isset($profilefiled) && sizeof($profilefiled)>=0){echo $html->link($ld['add'],'/profile_fileds/view/'.$id,array("class"=>"am-btn am-btn-warning am-btn-sm am-fr"));}else{echo "<div><br></div>";}?>
                    </p>
                    <table class="am-table">
                        <thead>
                        <tr>
                            <th class="thcode am-hide-sm-only" ><?php echo $ld['number'];?></th>
                            <th style="width:100px;"><?php echo $ld['code'];?></th>
                            <th><?php echo $ld['name'];?></th>
                            <th class="am-hide-sm-only" style="width:200px;"><?php echo $ld['prod_type_format'];?></th>
                            <th class="thicon am-hide-sm-only"><?php echo $ld['status'];?></th>
                            <th class="thsort am-hide-sm-only"><?php echo $ld['sort'];?></th>
                            <th style="width:150px;"><?php echo $ld['operate'];?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($profilefiled) && sizeof($profilefiled)>0){foreach($profilefiled as $t){?>
                            <tr>
							
                                <td><?php
                                    echo  $t['ProfileFiled']['id'];?>
                                </td>
                                <td>
                                    <?php
                                    echo $t['ProfileFiled']['code'];  ?></td>
                                <td class="am-hide-sm-only">
                                    <?php
                                    echo $t['ProfilesFieldI18n']['name'];  ?></td>
                                <td class="am-hide-sm-only">
                                    <?php
                                    echo $t['ProfileFiled']['format'];  ?></td>
                                <td class="am-hide-sm-only">
                                    <?php
                                    if($t['ProfileFiled']['status']=="0" ){
                                        echo '<div style="color:#dd514c" class="am-icon-close"></div>';
                                    }else{
                                        echo '<div style="color:#5eb95e" class="am-icon-check"></div>';
                                    }?>
                                </td>
                                <td class="am-hide-sm-only"><?php
                                    echo  $t['ProfileFiled']['orderby'];?>
                                </td>

                                <td>
                                    <?php
                                    if($svshow->operator_privilege("profiles_seeall_update")){
                                        echo $html->link($ld['edit'],"/profile_fileds/view/".$id."/".$t['ProfileFiled']['id'],array("class"=>"am-btn am-btn-default am-btn-xs am-radius")).'&nbsp;&nbsp;';
                                        echo $html->link($ld['remove'],"javascript:;",array("class"=>"am-btn am-btn-default am-text-danger am-btn-xs am-radius","onclick"=>"if(confirm('{$ld['confirm_delete']}')){window.location.href='{$admin_webroot}/profile_fileds/remove/".$id."/{$t['ProfileFiled']['id']}';}"));
                                    }

                                    ?>
                                </td>
                            </tr>
                        <?php }}else{ ?>
                            <tr>
                                <td colspan="7" class="no_data_found"><?php echo $ld['no_data_found']?></td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                    <div id="btnouterlist" class="btnouterlist">
                        <?php if(isset($profilefiled) && sizeof($profilefiled)>0){ echo $this->element('pagers');}?>
                    </div>
                </div>
            </div>
        </div>
    <?php }?>
</div>
<?php echo $form->end();?>
<script type="text/javascript">
    var formflag=true;
    function checkfrom(){
        if(formflag){
            formflag=false;
            return true;
        }
        return false;
    }
</script>