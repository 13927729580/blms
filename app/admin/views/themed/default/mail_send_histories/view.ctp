<style>
	.am-form-horizontal .am-form-label, .am-form-horizontal .am-checkbox{padding-top:0px;}
	.am-radio, .am-checkbox{display:inline-block;}
	.am-checkbox, .am-radio{padding-left: 18px;}
	.am-form-horizontal .am-form-label, .am-form-horizontal .am-radio, .am-form-horizontal .am-radio-inline, .am-form-horizontal .am-checkbox-inline{};
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
<div>
	<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;background: #5eb95e;">
		<ul >
		   	<li><a  href="#log_view"><?php echo $ld['view']; ?></a></li>
		</ul>
	</div>
	<div class="am-panel-group admin-content" id="accordion" style="width:100%;float:right;">
		
			<div id="log_view"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title"><?php echo $ld['view']; ?></h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['sender']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $mail_send_histories_data['MailSendHistory']['sender_name'];?></div>
			    		</div>
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['recipients']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $mail_send_histories_data['MailSendHistory']['receiver_email'];?></div>
			    		</div>
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['title']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $mail_send_histories_data['MailSendHistory']['title'];?></div>
			    		</div>
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['Cc']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $mail_send_histories_data['MailSendHistory']['cc_email'];?></div>
			    		</div>
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['Bcc']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $mail_send_histories_data['MailSendHistory']['bcc_email'];?></div>
			    		</div>
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left">HTML<?php echo $ld['content']; ?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><pre><?php echo $mail_send_histories_data['MailSendHistory']['html_body'];?></pre></div>
			    		</div>
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left">TEXT<?php echo $ld['content']; ?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><pre><?php echo $mail_send_histories_data['MailSendHistory']['text_body'];?></pre></div>
			    		</div>
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['status']; ?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<label class="am-checkbox am-success">
			    					<input type="radio" name="data[MailSendHistory][flag]" data-am-ucheck value="1" checked ><?php echo $ld['succeed']; ?></input>
			    				</label>&nbsp;&nbsp;
			    				<label class="am-checkbox am-success">
									<input type="radio" name="data[MailSendHistory][flag]" data-am-ucheck value="0" <?php if(isset($mail_send_histories_data['MailSendHistory'])&&$mail_send_histories_data['MailSendHistory']['flag'] == 0)echo 'checked';?>><?php echo $ld['failed']; ?></input>
							</label>
							<p><?php echo isset($mail_send_histories_data['MailSendHistory'])&&$mail_send_histories_data['MailSendHistory']['flag']==0?$mail_send_histories_data['MailSendHistory']['error_msg']:''; ?></p>
			    			</div>
			    		</div>
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['create_time']; ?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $mail_send_histories_data['MailSendHistory']['created'];?></div>
			    		</div>
					</div>
				</div>
			</div>
	</div>
</div>
