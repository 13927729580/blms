<style>
    .am-radio, .am-checkbox{display:inline;}
    .am-checkbox {margin-top:0px; margin-bottom:0px;}
    label{font-weight:normal;}
    .am-form-horizontal .am-radio{padding-top:0;position:relative;top:5px;}
    .am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
</style>
<?php echo $form->create('OpenUser',array('action'=>'sendMsg/'.$this->data['OpenUser']['id'],'id'=>'OpenUserReplyForm','name'=>'OpenUserReplyForm','onsubmit'=>'return form_checks();'));?>
<input id="openid" name="openid" type="hidden" value="<?php echo $this->data['OpenUser']['openid'];?>">
<input id="open_type" name="open_type" type="hidden" value="<?php echo $this->data['OpenUser']['open_type'];?>">
<input id="open_type_id" name="open_type_id" type="hidden" value="<?php echo $this->data['OpenUser']['open_type_id'];?>">
<?php if(isset($_POST['keyword_error_id'])){ ?>
    <input name="keyword_error_id" type="hidden" value="<?php echo $_POST['keyword_error_id']; ?>">
<?php } ?>
<div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
    <table class="am-table" id="replyinfo">
        <tr>
            <td style="padding-top:15px;font-size:16px;width:110px;text-align:left"><?php echo $ld['reply'].$ld['type']?></td>
            <td>
                <select style="width:220px;" id="reply_type" name="reply_type">
                    <option value="text"><?php echo $ld['word']; ?></option>
                    <option value="picture"><?php echo $ld['graphics']; ?></option>
                    <option value="article"><?php echo $ld['article']; ?></option>
                    <option value="product"><?php echo $ld['product']; ?></option>
                </select>
            </td>
        </tr>
        <tr class="reply_text">
            <td style="font-size:16px;width:110px;text-align:left"><?php echo $ld['content']?></td>
            <td><textarea style="width:80%;height:100px;float:left;" name="reply_content" id="reply_content"></textarea><em>*</em></td>
        </tr>
        <tr class="replyinfo reply_picture">
            <td style="padding-top:15px;font-size:16px;width:110px;text-align:left"><?php echo $ld['graphic_resources']?></td>
            <td><select style="width:220px;float:left;" id="reply_picture_list" name="reply_picture">
                    <option value="0"><?php echo $ld['please_select']?></option>
                    <?php if(isset($open_element_list)&&!empty($open_element_list)){ ?>
                        <?php foreach($open_element_list as $kk=>$vv){?>
                            <option value="<?php echo $kk; ?>"><?php echo $vv;?></option>
                        <?php }}?>
                </select><em>*</em>
            </td>
        </tr>
        <tr class="replyinfo reply_article">
            <td style="padding-top:15px;font-size:16px;width:110px;text-align:left"><?php echo $ld['article']?></td>
            <td>
                <div class="am-form-group">
            	<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-padding-0">
                    <input type="text" id="reply_article_keyword" value="">
                </div>
            	<div class="am-u-lg-4 am-u-md-2 am-u-sm-3 am-padding-0">
                    <input style="float:left;margin: 0px 0 0 10px;" type="button" class="am-btn am-btn-success am-radius am-btn-sm" id="reply_article_search" value="<?php echo $ld['search'] ?>">
                </div>
            	<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-padding-0 am-margin-top-xs">
            		<select id="reply_article_list" name="reply_article">
                    	<option value="0"><?php echo $ld['please_select']?></option>
                	</select>
                	</div>
                	<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-margin-top-xs"><em style="line-height:37px;display:block;top:0;">*</em></div>
                	</div>
            </td>
        </tr>
        <tr class="replyinfo reply_product">
            <td style="padding-top:15px;font-size:16px;width:110px;text-align:left"><?php echo $ld['product']?></td>
            <td><div class="am-form-group">
            	<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-padding-0">
                    <input type="text" id="reply_product_keyword" value="">
                </div>
            	<div class="am-u-lg-4 am-u-md-2 am-u-sm-3 am-padding-0">
                    <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" id="reply_product_search" value="<?php echo $ld['search'] ?>">
                </div>
            	<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-padding-0 am-margin-top-xs">
            		<select id="reply_product_list" name="reply_product">
                    	<option value="0"><?php echo $ld['please_select']?></option>
                	</select>
                </div>
                <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-margin-top-xs"><em style="line-height:37px;display:block;top:0;">*</em></div>
                    </div>
                	</div>
            </td>
        </tr>
    </table>
    <div class="btnouter">
        <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo '发送'?>" /> <input class="am-btn am-btn-success am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
    </div>
    <?php echo $form->end();?>
</div>
<style type="text/css">
    #replyinfo{display:table;}
    #replyinfo tr.replyinfo{display:none;}
</style>
<script type="text/javascript">
    $("#reply_type").on("change",function(){
        var reply_type=$(this).val();
        $("#replyinfo tr").addClass("replyinfo");
        $("#replyinfo tr:eq(0)").removeClass("replyinfo");
        $("#replyinfo tr.reply_"+reply_type).removeClass("replyinfo");
    });

    $("#reply_article_search").on("click",function(){
        var keyword=$("#reply_article_keyword").val();
        if(keyword!=""){
            $.ajax({ url: admin_webroot+"open_users/quickreply/0/select_article",
                type:"POST",
                data:{keyword:keyword},
                dataType:"json",
                success: function(data){
                    $("#reply_article_list").find("option").remove();
                    $("<option></option>").val(0).text("<?php echo $ld['please_select'] ?>").appendTo($("#reply_article_list"));
                    $.each(data, function (i, item) {
                        $("<option></option>").val(item["key"]).text(item["value"]).appendTo($("#reply_article_list"));
                    });
                }
            });
        }else{
            alert("<?php printf($ld['name_not_be_empty'],$ld['keyword']); ?>");
        }
    });

    $("#reply_product_search").on("click",function(){
        var keyword=$("#reply_product_keyword").val();
        if(keyword!=""){
            $.ajax({ url: admin_webroot+"open_users/quickreply/0/select_product",
                type:"POST",
                data:{keyword:keyword},
                dataType:"json",
                success: function(data){
                    $("#reply_product_list").find("option").remove();
                    $("<option></option>").val(0).text("<?php echo $ld['please_select'] ?>").appendTo($("#reply_product_list"));
                    $.each(data, function (i, item) {
                        $("<option></option>").val(item["key"]).text(item["value"]).appendTo($("#reply_product_list"));
                    });
                }
            });
        }else{
            alert("<?php printf($ld['name_not_be_empty'],$ld['keyword']); ?>");
        }
    });

    function form_checks(){
        var reply_type=$("#reply_type").val();
        var content;
        if(reply_type=="picture"){
            content=$("#reply_picture_list").val();
        }else if(reply_type=="article"){
            content=$("#reply_article_list").val();
        }else if(reply_type=="product"){
            content=$("#reply_product_list").val();
        }else{
            content=$("#reply_content").val();
        }
        if(reply_type!="text"&&content=="0"){
            alert("<?php printf($ld['name_not_be_empty'],$ld['reply'].$ld['content']); ?>");return false;
        }else if(reply_type=="text"&&content==""){
            alert("<?php printf($ld['name_not_be_empty'],$ld['reply'].$ld['content']); ?>");return false;
        }
        $("#OpenUserReplyForm input[type=submit]").button('loading');
        $.ajax({ url: admin_webroot+"open_users/sendMsg/<?php echo $this->data['OpenUser']['id'] ?>",
            type:"POST",
            data:$("#OpenUserReplyForm").serialize(),
            dataType:"json",
            success: function(data){
                alert(data.msg);
                if(document.getElementById("quick_reply")){
                    btnClose();
                }
                $("#OpenUserReplyForm textarea").val('');
            },
            complete:function(XMLHttpRequest, textStatus){
            		$("#OpenUserReplyForm input[type=submit]").button('reset');
            }
        });
        return false;
    }
</script>