                    <div class="am-form-group">
                        <?php if($evaluation_condition_info['EvaluationCondition']['params']=="cycle"){?>
                            <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">条件值</label>
                            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="value" name="data[EvaluationCondition][value]" value="<?php echo @$evaluation_condition_info['EvaluationCondition']['value'];?>"/></div>
                        <?php }elseif($evaluation_condition_info['EvaluationCondition']['params']=="ability_level"){
                            foreach($level_list as $lv_k=>$lv_v){?>
                                <label class="am-checkbox am-success" style="padding-top:0px">
                                    <input type="checkbox" class="checkbox" name="data[EvaluationCondition][value][]" value="<?php echo $lv_v["AbilityLevel"]["id"];?>"  data-am-ucheck <?php if(in_array($lv_v["AbilityLevel"]["id"],explode(",",$evaluation_condition_info['EvaluationCondition']['value']))) echo 'checked';?>/>
                                    <?php echo $lv_v["Ability"]["name"].$lv_v["AbilityLevel"]["name"];?>
                                </label>
                            <?php }
                        }elseif($evaluation_condition_info['EvaluationCondition']['params']=="parent_evaluation"){?>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-12 am-text-center">
                            <div id="relative_evaluation">
                                <?php if(isset($evaluation_list) && sizeof($evaluation_list)>0)foreach($evaluation_list as $k=>$v){
                                    if(isset($v['Evaluation'])){?>
                                        <div class="am-u-lg-12 am-u-md-12 am-u-sm-12 relative_evaluation_data">
                                            <div class="am-u-lg-10 am-u-md-10 am-u-sm-10"><?php echo $v['Evaluation']['name']; ?></div>
                                            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                                                <span class="am-icon-close am-no" onMouseout="onMouseout_deleteimg(this)" onmouseover="onmouseover_deleteimg(this)" onclick="delete_relation_evaluation('<?php echo $v['Evaluation']['id'];?>','<?php echo $evaluation_condition_info["EvaluationCondition"]["id"];?>')"></span>
                                            </div>
                                        </div>
                                    <?php }
                                }?>
                            </div>
                            <table class="am-table">
                                <tr>
                                    <td colspan="3">
                                        <input style="width:200px;float:left;margin-right:5px;" type="text" name="evaluation_keyword" id="evaluation_keyword" /> <input  type="button" class="am-btn am-btn-success am-radius am-btn-sm " value="<?php echo $ld['search']?>" onclick="searchevaluation();" />
                                    </td>
                                </tr>
                            </table>
                            <div class="am-u-lg-10 am-u-md-6 am-u-sm-6 am-text-center">
                                <label class='am-show-sm-only'><?php echo $ld['option_products']?></label>
                                <div id="evaluation_select" class="related_dt"></div>
                            </div>
                        </div>
                        <?php }?>
                    </div>