<?php
require_once("alipay_core.function.php");

class alipay_go {

	var $gateway = "https://mapi.alipay.com/gateway.do?";         //支付接口
	var $parameter;       //全部需要传递的参数
	var $security_code;   //安全校验码
	var $mysign;          //签名
	var $sign_type;


	//构造支付宝外部服务接口控制
	function init($parameter,$security_code,$sign_type = "MD5",$transport= "https") {
		$this->parameter=$parameter;
		$this->security_code  = $security_code;
		$this->sign_type      = $sign_type;
		$this->transport      = $transport;
		if($parameter['_input_charset'] == "")
		$this->parameter['_input_charset']='GBK';
		if($this->transport == "https") {
			$this->gateway = "https://mapi.alipay.com/gateway.do?";
		} else $this->gateway = "http://mapi.alipay.com/gateway.do?";
		$sort_array  = array();
		$arg         = "";
	}

	function buildRequestPara($para_temp,$aliapy_config) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter = paraFilter($para_temp);

		//对待签名参数数组排序
		$para_sort = argSort($para_filter);
		//生成签名结果
		$mysign = buildMysign($para_sort, trim($aliapy_config['key']), strtoupper(trim($aliapy_config['sign_type'])));
		//签名结果与签名方式加入请求提交参数组中
		$para_sort['sign'] = $mysign;
		$para_sort['sign_type'] = strtoupper(trim($aliapy_config['sign_type']));
		
		return $para_sort;
	}

	/**
     * 生成要请求给支付宝的参数数组
     * @param $para_temp 请求前的参数数组
	 * @param $aliapy_config 基本配置信息数组
     * @return 要请求的参数数组字符串
     */
	function buildRequestParaToString($para_temp,$aliapy_config) {
		//待请求参数数组
		$para = $this->buildRequestPara($para_temp,$aliapy_config);
		
		//把参数组中所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$request_data = createLinkstring($para);
		
		return $request_data;
	}
	
	
	/**
     * 构造模拟远程HTTP的POST请求，获取支付宝的返回XML处理结果
	 * 注意：该功能PHP5环境及以上支持，因此必须服务器、本地电脑中装有支持DOMDocument、SSL的PHP配置环境。建议本地调试时使用PHP开发软件
     * @param $para_temp 请求参数数组
     * @param $gateway 网关地址
	 * @param $aliapy_config 基本配置信息数组
     * @return 支付宝返回XML处理结果
     */
	function create_url($para_temp) {
		//$xml_str = '';
		$aliapy_config=array();
		
		$aliapy_config['partner']=$para_temp['partner'];
		$aliapy_config['key']=$this->security_code;
		$aliapy_config['sign_type']=$this->sign_type;
		$aliapy_config['input_charset']=$this->parameter['_input_charset'];
		$aliapy_config['transport']='https';
		//待请求参数数组字符串
		$request_data = $this->buildRequestParaToString($para_temp,$aliapy_config);
		//请求的url完整链接
		$url = $this->gateway . $request_data;
		//echo $url;die();
		//远程获取数据
		return $url;
	}
}
	
?>