<?php
class alipay {
	
    var $gateway;           //网关地址
    var $_key;			  	//安全校验码
    var $partner;           //合作伙伴ID
    var $sign_type='MD5';         //签名方式 系统默认
    var $mysign;            //签名结果
    var $_input_charset='UTF-8';    //字符编码格式
    var $transport='https';         //访问模式
    var $ld;
    var $is_wechat="0";		//微信访问
	var $response=array();
	var $config;
	var $config_cn= array(
		"account"=>array(
			"name" => "支付宝帐户",
			"type" => "text"
		),
		"key"=>array(
			"name" => "交易安全校验码",
			"type" => "text"
		),
	
		"partner"=>array(
			"name" => "合作者身份ID",
			"type" => "text"
		),
			
		"login"=>array(
			"name" => "支付宝快捷登录",
			"type" => "radio"
		),
		"foo"=>array(
			"name" => "接口类型",
			"type" => "select",
			"value"=>array(
				'1'=>'标准双接口',
				'2'=>'即时到账'	
			
			)
		)
	);
	var $config_en= array(
		"account"=>array(
			"name" => "Alipay Account",
			"type" => "text"
		),
		"key"=>array(
			"name" => "Transaction Security Check Code",
			"type" => "text"
		),
	
		"partner"=>array(
			"name" => "Partner ID",
			"type" => "text"
		),
			
		"login"=>array(
			"name" => "Pay treasure quick logging",
			"type" => "radio"
		)

	);
    function alipay(){
    	
    }

    function __construct(){
        $this->alipay();
    }
}
?>