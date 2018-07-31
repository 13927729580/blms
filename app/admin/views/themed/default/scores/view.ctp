<style>
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

  .am-selected-btn{
    float: left;
    width:80%;
  }
</style>

<?php echo $form->create('Score',array('action'=>'view/'.(isset($this->data['Score']['id'])?$this->data['Score']['id']:0),'onsubmit'=>'return check_all()'));?>
<input type="hidden" name="data[Score][id]" value="<?php echo isset($this->data['Score']['id'])?$this->data['Score']['id']:0;?>">
<!-- 导航条 -->
<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
    <ul>
        <li><a href="#tablemain"><?php echo $ld['basic_information']?></a></li>
    </ul>
</div>
<!-- 右上角按钮 -->
<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
    <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" style="margin-right:20px;" value="<?php echo $ld['d_submit']?>" /> 
    <input class="am-btn am-btn-success  am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:100%;padding-left:0;padding-right:0;">
    <div id="tablemain" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['basic_information']?></h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <div class="am-g">
                    <div class="am-form-group">
                        <label style="padding-top:0px;margin-top:0;min-height:50px;" rowspan="<?php echo count($backend_locales)+1;?>" class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['name'] ?></label>
                   
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k=>$v){?>
                 
                            <div class="am-u-lg-7 am-u-md-7 am-u-sm-7" style="margin-bottom:1rem;"><input style="float:left;width:80%;" type="text" id="score_name_<?php echo $v['Language']['locale'];?>"  maxlength="50" name="data[ScoreI18n][<?php echo $v['Language']['locale'];?>][name]" value="<?php echo isset($this->data['ScoreI18n'][$v['Language']['locale']])?$this->data['ScoreI18n'][$v['Language']['locale']]['name']:'';?>" />
                                <?php if(sizeof($backend_locales)>1){?>
                                    <span class="lang" style="float:left;"><?php echo $ld[$v['Language']['locale']];?></span><?php }?><em style="color:red;float:left;">*</em>
                            </div>
        
                        <?php }}?>
                    </div>
                    <div class="am-form-group">
                        <label style="padding-top:0px;margin-top:0;" class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['type'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6" style="width:47.2%;">
                            
                            <select id="score_type" name="data[Score][type]" data-am-selected="{noSelectedText:''}">
                                <option value=""><?php echo $ld['please_select']?></option>
                                <?php if(isset($score_type_list)&&sizeof($score_type_list)>0){foreach($score_type_list as $k=>$v){ ?>
                                    <option value="<?php echo $k; ?>" <?php if(@$this->data['Score']['type']==$k){echo "selected";}?> ><?php echo $v; ?></option>
                                <?php }} ?>
                            </select>
                           
                            
                        </div>
                        <em style="color:red;top:5px;float:left;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label rowspan="<?php echo count($backend_locales)+1;?>" class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label" style="margin-top:0;min-height:90px;"><?php echo $ld['option_list']?></label>
                  
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                      
                            <div class="am-u-lg-7 am-u-md-7 am-u-sm-7" style="margin-bottom:1rem;">
                                <textarea style="width:80%;float:left;" id="score_value_<?php echo $v['Language']['locale'];?>"  name="data[ScoreI18n][<?php echo $v['Language']['locale'];?>][value]"><?php echo isset($this->data['ScoreI18n'][$v['Language']['locale']])?$this->data['ScoreI18n'][$v['Language']['locale']]['value']:'';?></textarea>
                                <?php if(sizeof($backend_locales)>1){?>
                                <span class="lang" style="top:15px"><?php echo $ld[$v['Language']['locale']];?></span><?php }?><em style="color:red;top:15px;">*</em>
                            </div>
                      
                        <?php }}?>
                    </div>
                    <div class="am-form-group">
                        <label style="padding-top:0px;margin-top:6px;" class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['status'] ?></label>
                        <div class="am-u-lg-8"><label class="am-radio-inline"><input type="radio" name="data[Score][status]" value="1" <?php echo !isset($this->data['Score']['status'])||(isset($this->data['Score']['status'])&&$this->data['Score']['status']==1)?"checked":""; ?> /><?php echo $ld['yes']?></label>
                            <label class="am-radio-inline"><input type="radio" name="data[Score][status]" value="0" <?php echo isset($this->data['Score']['status'])&&$this->data['Score']['status']==0?"checked":"";?> /><?php echo $ld['no']?></label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php echo $form->end();?>
<script type="text/javascript">
    function check_all(){
        var score_name=document.getElementById("score_name_"+backend_locale).value;
        if(score_name==""){
            alert("<?php printf($ld['name_not_be_empty'],$ld['name']); ?>");
            return false;
        }
        var score_type=document.getElementById("score_type").value;
        if(score_type==""){
            alert("<?php echo $ld['please_select'].$ld['type']; ?>");
            return false;
        }
        var score_value=document.getElementById("score_value_"+backend_locale).value;
        if(score_value==""){
            alert("<?php printf($ld['name_not_be_empty'],$ld['option_list']); ?>");
            return false;
        }
        return true;
    }
</script>