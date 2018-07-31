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
</style>

<?php echo $form->create('enquiries',array('action'=>'/'.$Enquiry_list['Enquiry']['id']))?>
<input id="enquiry_id" type="hidden" name="data[Enquiry][id]" value="<?php echo $Enquiry_list['Enquiry']['id'];?>" />
<input type="hidden" name="data[Enquiry][user_id]" value="<?php echo $Enquiry_list['Enquiry']['user_id'];?>" />
<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
    <ul>
        <li><a href="#details"><?php echo $ld['details_view']?></a></li>
    </ul>
</div>
<!-- 右上角按钮 -->
<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
    <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" style="margin-right:20px;" value="<?php echo $ld['submit'];?>">
    <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['offered_price'];?>" onclick="quote()">
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-detail-view" id="accordion"  style="width:100%;padding-left:0;padding-right:0;min-height:640px;">
    <div id="details" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title">
                <?php echo $ld['details_view']?>
            </h4>
        </div>
        <div id="details_view" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <div class="am-g">
                    <div class="am-form-group">
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['name'];?></div>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo isset($product_Info['ProductI18n']['name'])?$product_Info['ProductI18n']['name']:"";?></div>
                    </div>
                    <div class="am-form-group">
                        <div style="padding-top:0px;" class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['attribute'];?></div>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input style="" type="text" name="data[Enquiry][attribute]" value="<?php echo $Enquiry_list['Enquiry']['attribute'];?>" readonly /></div>
                    </div>
                    <div class="am-form-group">
                        <div style="padding-top:0px;" class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['price'];?></div>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input style="" type="text" name="data[Enquiry][target_price]" value="<?php echo $Enquiry_list['Enquiry']['target_price'];?>" readonly /></div>
                    </div>
                    <div class="am-form-group">
                        <div style="padding-top:0px;" class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['app_qty'];?></div>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input style="" type="text" name="data[Enquiry][qty]" value="<?php echo $Enquiry_list['Enquiry']['qty'];?>" readonly /></div>
                    </div>
                    <div class="am-form-group">
                        <div style="padding-top:0px;" class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['name_of_member'];?></div>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo isset($user_Info)?$user_Info['User']['name']:'' ?></div>
                    </div>
                    <div class="am-form-group">
                        <div style="padding-top:0px;" class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['contacter'];?></div>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input style="" type="text" name="data[Enquiry][contact_person]" value="<?php echo $Enquiry_list['Enquiry']['contact_person'];?>" readonly /></div>
                    </div>
                    <div class="am-form-group">
                        <div style="padding-top:0px;" class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['phone'];?></div>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input style="" type="text" name="data[Enquiry][tel1]" value="<?php echo $Enquiry_list['Enquiry']['tel1'];?>" readonly /></div>
                    </div>
                    <div class="am-form-group">
                        <div style="padding-top:0px;" class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['address'];?></div>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input style="" type="text" name="data[Enquiry][tel1]" value="<?php echo $Enquiry_list['Enquiry']['address'];?>" readonly /></div>
                    </div>
                    <div class="am-form-group">
                        <div style="padding-top:0px;" class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['status'];?></div>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><select data-am-selected="{noSelectedText:''}" name="data[Enquiry][status]" id="status">
                                <option value="0" <?php if (isset($enquiry_status)&&$enquiry_status == 0){?>selected<?php }?>><?php echo $ld['unrecognized']?></option>
                                <option value="1" <?php if (isset($enquiry_status)&&$enquiry_status == 1){?>selected<?php }?>><?php echo $ld['confirmed']?></option>
                                <option value="2" <?php if (isset($enquiry_status)&&$enquiry_status == 2){?>selected<?php }?>><?php echo $ld['canceled']?></option>
                                <option value="3" <?php if (isset($enquiry_status)&&$enquiry_status == 3){?>selected<?php }?>><?php echo $ld['complete']?></option>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <div style="padding-top:0px;" class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['email'];?></div>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input style="" type="text" name="data[Enquiry][email]" value="<?php echo $Enquiry_list['Enquiry']['email'];?>" readonly/></div>
                    </div>
                    <div class="am-form-group">
                        <div style="padding-top:0px;" class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['remarks_notes']?></div>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><textarea style="" name="data[Enquiry][remark]"  id="data_mailtemplate_code" readonly><?php echo $Enquiry_list['Enquiry']['remark'];?></textarea></divd>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
<?php echo $form->end();?>
<script>
    function quote(){
        var enquiry_id=document.getElementById("enquiry_id").value;
        //Ñ¯¼Ûid£¬´«Èë±¨¼ÛÖÐ
        window.location.href=encodeURI(admin_webroot+"quotes/view?enquiry_id="+enquiry_id);
    }
</script>