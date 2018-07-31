<style>
.am-radio-inline{padding-top: 0!important;}
<?php if($organizations_id!=''){ ?>
.am-u-lg-3.am-u-md-3.am-u-sm-12.am-user-menu.am-hide-sm-only.am-padding-right-0{display: none!important;}
.am-u-lg-9.am-u-md-8.am-u-sm-12{width:100%;}
.am-btn.am-btn-sm.am-btn-secondary.am-show-sm-only{display:none!important;}
.am-u-lg-2.am-u-md-2.am-u-sm-2.am-panel-group.am-hide-sm-only{margin-right:5%;}
<?php } ?>
</style>
<div class="am-g am-g-fixed">
	<?php if($organizations_id!=''){ ?>
	<?php echo $this->element('organization_menu');?>
	<?php echo $this->element('org_menu')?>

	<button style="margin:10px 0;" class="am-btn am-btn-primary am-show-sm-only" data-am-offcanvas="{target: '#org_menu2', effect: 'push'}">组织菜单</button>
	<?php } ?>
	<div class="am-panel am-panel-default <?php if($organizations_id!=''){echo 'am-u-lg-9';} ?>" id="course_study" style="font-size: 14px;margin-left: 0;margin-top: 15px;">
	    <div style="text-align:left;font-size:20px;padding-bottom:1rem;margin-top:1%;padding-left:5px;margin-bottom:1rem;border-bottom: 1px solid #ccc;" >
	      <span style="float:left;"><?php echo isset($evaluation_info['Evaluation']['name'])?$evaluation_info['Evaluation']['name']:''; ?></span>
	      <div class="am-cf"></div>
	    </div>
	    <div class="am-panel am-panel-default" id="evaluation_study" style="margin:0;">
	        <div class="am-panel-hd" style="font-size: 15px;">
	            <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Evaluation_record'}">评测记录&nbsp;</h4>
	        </div>

	        <ul class="am-avg-lg-2 am-avg-md-2 am-avg-sm-1">
	            <li style="margin-top: 10px;">
	                <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label-text" style="margin-top: 10px;">分数</label>
	                <div class="am-u-lg-4 am-u-md-4 am-u-sm-5" style="padding-right:0.5rem;">
	                    <div class="am-input-group">
	                    <input type="text" class="am-form-field" name="start_score_time" style="font-size: 1.4rem;height: 35px;" value="<?php echo isset($start_score_time)?$start_score_time:"";?>" />
	                    <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;height: 32px;line-height: 30px;">
	                    <i class="am-icon-remove" style="font-size: 10px;"></i>
	                  </span>
	                </div>
	                </div>
	                <div class=" am-text-center am-fl " style="margin-top:10px;">-</div>
	                <div class="am-u-lg-4 am-u-md-4 am-u-sm-5" style="padding-left:0.5rem;">
	                    <div class="am-input-group">
	                    <input type="text"  class="am-form-field" name="end_score_time" style="font-size: 1.4rem;height: 35px;" value="<?php echo isset($end_score_time)?$end_score_time:"";?>" />
	                    <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;height: 32px;line-height: 30px;">
	                    <i class="am-icon-remove" style="font-size: 10px;"></i>
	                  </span>
	                </div>
	                </div>
	            </li>
	            <li style="margin-top: 10px;">
	                <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label-text" style="margin-top: 10px;">操作时间</label>
	                <div class="am-u-lg-4 am-u-md-4 am-u-sm-5" style="padding-right:0.5rem;">
	                    <div class="am-input-group">
	                    <input type="text" class="am-form-field" readonly  data-am-datepicker="{theme: 'success'}" name="start_date_time" style="font-size: 1.4rem;background-color: #fff;height: 35px;" value="<?php echo isset($start_date_time)?$start_date_time:"";?>" />
	                    <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;height: 32px;line-height: 30px;">
	                    <i class="am-icon-remove" style="font-size: 10px;"></i>
	                  </span>
	                </div>
	                </div>
	                <div class=" am-text-center am-fl " style="margin-top:10px;">-</div>
	                <div class="am-u-lg-4 am-u-md-4 am-u-sm-5" style="padding-left:0.5rem;">
	                    <div class="am-input-group">
	                    <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success'}"  name="end_date_time" style="font-size: 1.4rem;background-color: #fff;height: 35px;" value="<?php echo isset($end_date_time)?$end_date_time:"";?>" />
	                    <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;height: 32px;line-height: 30px;">
	                    <i class="am-icon-remove" style="font-size: 10px;"></i>
	                  </span>
	                </div>
	                </div>
	            </li>
	            <li style="margin-top: 10px;">
	                <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 11px;">名称</label>
	                <div class="am-u-lg-6 am-u-md-6 am-u-sm-8"><input type="text" class="am-form-field" name="keyword" id="keyword" style="font-size: 1.4rem;padding:8px;" placeholder="用户名/手机号/邮箱" value="<?php echo isset($keyword)?$keyword:'';?>"/></div>
	            </li>
	            <li style="margin-top: 10px;">
	                <div class="am-u-sm-8 am-u-md-8 am-u-sm-7 " style="float:left;">
	                    <input type="button"  class="am-btn am-btn-success am-btn-md am-radius" style="font-size: 14px;" value="<?php echo $ld['search'];?>" onclick="formsubmit()" />
	                </div>
	            </li>
	        </ul>

	        <div id="Evaluation_record" class="am-panel-collapse am-collapse am-in" style="margin-top: 15px;">
	            <div id="user_log" class="scrollspy_nav_hid"></div>
	            <div class="am-panel-bd">
	                <table class="am-table  table-main">
	                    <thead>
	                    <tr>
	                        <th style="font-weight:normal;">评测者姓名</th>
	                        <th class="am-hide-sm-only" style="font-weight:normal;">评测开始时间</th>
	                        <th class="am-hide-sm-only" style="font-weight:normal;">评测结束时间</th>
	                        <th style="font-weight:normal;">得分</th>
	                        <th style="font-weight:normal;">查看</th>
	                    </tr>
	                    </thead>
	                    <tbody>
	                    <?php //pr($users_list); ?>
	                    <?php if(isset($user_evaluation) && sizeof($user_evaluation)>0){foreach($user_evaluation as $k=>$v){ ?>
	                    <?php //pr($v); ?>
	                        <tr style="padding:10px 0!important;" >
	                            <?php //pr($v['UserEvaluationLog']['user_id']); ?>
	                            <td style="padding:10px 0!important;"><?php foreach ($users_list as $kk => $vv) {if($v['UserEvaluationLog']['user_id']==$vv['User']['id']){echo $vv['User']['name'];}} ?></td>
	                            <td class="am-hide-sm-only"  style="padding:10px 0!important;"><?php echo $v['UserEvaluationLog']['start_time']; ?></td>
	                            <td class="am-hide-sm-only" style="padding:10px 0!important;"><?php if($v['UserEvaluationLog']['submit_time']!=''){echo $v['UserEvaluationLog']['submit_time'];}else{echo $v['UserEvaluationLog']['end_time'];} ?></td>
	                            <td style="padding:10px 0!important;"><?php echo $v['UserEvaluationLog']['score']; ?></td>
	                            <td style="padding:10px 0!important;">
	                                <a class="mt am-btn am-btn-success am-btn-xs  am-seevia-btn-view" href="<?php echo $html->url('/user_evaluation_logs/view/'.$v['UserEvaluationLog']['id']); ?>"><span class="am-icon-eye"></span>查看</a>
	                            </td>
	                        </tr>
	                    <?php }}else{?>
	                        <tr><td colspan="6" align="center" style="text-align: center;padding:75px;">暂无评测记录</td></tr>
	                    <?php }?>
	                    </tbody>
	                </table>
	            </div>
	        </div>
	    </div>
	</div>
</div>
<script>
function cla(btn){
    $(btn).prev().val('');
}
var organization = '';
getQueryString('organizations_id');
function getQueryString(name) { 
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
    var r = window.location.search.substr(1).match(reg); 
    if(r == null){
        organization = 0;
    }else{
        organization = 1;
    }
} 
console.log(organization)
function formsubmit(){
    var id = '<?php echo isset($evaluations_id)?$evaluations_id:''; ?>';
    var keyword=document.getElementById('keyword').value;
    //var score=document.getElementById('score').value;
    var start_score_time = document.getElementsByName('start_score_time')[0].value;
    var end_score_time = document.getElementsByName('end_score_time')[0].value;
    var start_date_time = document.getElementsByName('start_date_time')[0].value;
    var end_date_time = document.getElementsByName('end_date_time')[0].value;
    var url = "status="+status+"&keyword="+keyword+"&start_date_time="+start_date_time+"&end_date_time="+end_date_time+"&start_score_time="+start_score_time+"&end_score_time="+end_score_time;
    if(organization==0){
        window.location.href = encodeURI(web_base+"/evaluations/evaluation_study/"+id+"?"+url);
    }else{
        var organizations_id = '<?php echo isset($organizations_id)?$organizations_id:''; ?>'
        window.location.href = encodeURI(web_base+"/evaluations/evaluation_study/"+id+"?organizations_id="+organizations_id+'&'+url);
    }

}
</script>