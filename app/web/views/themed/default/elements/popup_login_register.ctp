<!-- Login/Register -->
<script src="https://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
<div class="am-modal am-modal-no-btn ajax_login_register" tabindex="-1" id="ajax_login_register">
  <div class="am-modal-dialog">
    <div class="am-modal-hd"><font><?php echo $ld['login'] ?></font>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
      	
    </div>
  </div>
</div>

<!-- Purchase Order -->
<div class="am-modal am-modal-no-btn" id="order_pay">
	<div class="am-modal-dialog">
		<div class="am-modal-hd"><?php echo '确认支付'; ?>
			<a href='javascript: void(0)' data-am-modal-close class="am-close">&times;</a>
		</div>
	    	<div class='am-modal-bd' id="order_pay_content">
	    		
	    	</div>
 	</div>
</div>