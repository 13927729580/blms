<?php
require_once("alipay_submit.php");
require_once("alipay_notify.php");
	
class alipay_support{
	/* *
	 * 配置文件
	 * 版本：3.2
	 * 日期：2011-03-25
	 * 说明：
	 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
	 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
		
	 * 提示：如何获取安全校验码和合作身份者id
	 * 1.用您的签约支付宝账号登录支付宝网站(www.alipay.com)
	 * 2.点击“商家服务”(https://b.alipay.com/order/myorder.htm)
	 * 3.点击“查询合作者身份(pid)”、“查询安全校验码(key)”
		
	 * 安全校验码查看时，输入支付密码后，页面呈灰色的现象，怎么办？
	 * 解决方法：
	 * 1、检查浏览器配置，不让浏览器做弹框屏蔽设置
	 * 2、更换浏览器或电脑，重新登录查询。
	 */
	 
	//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
	//合作身份者id，以2088开头的16位纯数字
	var $partner     = '2088502969361766';

	//安全检验码，以数字和字母组成的32位字符
	var $key          = 'o0nnfuabdwagtqpzqx0ngymopp9dcyt5';

	//页面跳转同步通知页面路径，要用 http://格式的完整路径，不允许加?id=123这类自定义参数
	//return_url的域名不能写成http://localhost/alipay.auth.authorize_php_utf8/return_url.php ，否则会导致return_url执行无效
	var $return_url   = 'http://www.ioco.dev/synchros/alipay_gate_return';

	//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


	//签名方式 不需修改
	var $sign_type   = 'MD5';

	//字符编码格式 目前支持 gbk 或 utf-8
	var $input_charset= 'utf-8';

	//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
	var $transport    = 'http';
	
	var $aliapy_config =array(
		'partner'=>'2088502969361766',
		'key'=>'o0nnfuabdwagtqpzqx0ngymopp9dcyt5',
		'return_url'=>'http://www.ioco.dev/synchros/alipay_gate_return',
		'sign_type'=> 'MD5',
		'input_charset'=>'utf-8',
		'transport'=>'http'
	);
	
	var $alipay_gateway_new = 'https://mapi.alipay.com/gateway.do?';

	
	function alipay_support(){
    	
    }

    function __construct(){
        $this->alipay_support();
    }
    
    function init(){
    	return $this->aliapy_config;
    }  
	/**
     * 构造快捷登录接口
     * @param $para_temp 请求参数数组
     * @return 表单提交HTML信息
     */
	function alipay_auth_authorize($para_temp) {
		//设置按钮名称
		$button_name = "确认";
		//生成表单提交HTML文本信息
		$this->aliapy_config['return_url']=$para_temp['return_url'];
		$alipaySubmit = new AlipaySubmit();
		$html_text = $alipaySubmit->buildForm($para_temp, $this->alipay_gateway_new, "get", $button_name,$this->aliapy_config);

		return $html_text;
	}
	
	/**
     * 用于防钓鱼，调用接口query_timestamp来获取时间戳的处理函数
	 * 注意：该功能PHP5环境及以上支持，因此必须服务器、本地电脑中装有支持DOMDocument、SSL的PHP配置环境。建议本地调试时使用PHP开发软件
     * return 时间戳字符串
	 */
	function query_timestamp() {
		$url = $this->alipay_gateway_new."service=query_timestamp&partner=".trim($this->aliapy_config['partner']);
		$encrypt_key = "";

		$doc = new DOMDocument();
		$doc->load($URL);
		$itemEncrypt_key = $doc->getElementsByTagName( "encrypt_key" );
		$encrypt_key = $itemEncrypt_key->item(0)->nodeValue;
		
		return $encrypt_key;
	}
	
	/**
     * 构造支付宝其他接口
     * @param $para_temp 请求参数数组
     * @return 表单提交HTML信息/支付宝返回XML处理结果
     */
	function alipay_interface($para_temp) {
		//获取远程数据
		$alipaySubmit = new AlipaySubmit();
		$html_text = "";
		//请根据不同的接口特性，选择一种请求方式
		//1.构造表单提交HTML数据:（$method可赋值为get或post）
		//$alipaySubmit->buildForm($para_temp, $this->alipay_gateway, "get", $button_name,$this->aliapy_config);
		//2.构造模拟远程HTTP的POST请求，获取支付宝的返回XML处理结果:
		//注意：若要使用远程HTTP获取数据，必须开通SSL服务，该服务请找到php.ini配置文件设置开启，建议与您的网络管理员联系解决。
		//$alipaySubmit->sendPostInfo($para_temp, $this->alipay_gateway, $this->aliapy_config);
		
		return $html_text;
	}


	//支援函数
	//改变code
	function changecode($config){ 
		$this->aliapy_config['partner']=$config['partner'];
		$this->aliapy_config['key']=$config['key'];
		$this->partner=$config['partner'];
		$this->key=$config['key'];
		
	}

}
?>