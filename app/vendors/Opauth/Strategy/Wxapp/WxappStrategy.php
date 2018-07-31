<?php
/**
 * Wechat strategy for Opauth
 * based on https://developers.facebook.com/docs/authentication/server-side/
 * 
 * More information on Opauth: http://opauth.org
 * 
 * @link         http://opauth.org
 * @package      Opauth.WechatStrategy
 * @license      MIT License
 */

class WxappStrategy extends OpauthStrategy{
	
	/**
	 * Compulsory config keys, listed as unassociative arrays
	 */
	public $expects = array('key', 'secret');
	
	/**
	 * Optional config keys with respective default values, listed as associative arrays
	 */
	public $defaults = array(
		'redirect_uri' => '{complete_url_to_strategy}wechat_callback'
	);

	/**
	 * Auth request
	 */
	public function request(){
		
	}
	
	/**
	 * Internal callback, after Wechat's OAuth
	 */
	public function wxapp_callback(){
		$this->env['callback_transport']="ajax";
		if(isset($_REQUEST['code'])){
			$code=$_REQUEST['code'];
			$encryptedData=$_REQUEST['encryptedData'];
			$iv=$_REQUEST['iv'];
			$code=$_REQUEST['code'];
			$appid=$this->strategy['key'];
			$secret=$this->strategy['secret'];
			$get_token_url="https://api.weixin.qq.com/sns/jscode2session";
			$params = array(
				'appid' =>$this->strategy['key'],
				'secret' => $this->strategy['secret'],
				'js_code' => $_REQUEST['code'], 
				'grant_type' => 'authorization_code'
			);
			$apiData = json_decode($this->serverGet($get_token_url,$params,null,$headers));
			if(!isset($apiData->errcode)){
		            $sessionKey = $apiData->session_key;
		            $userinfo = new WXBizDataCrypt($appid, $sessionKey);
		            $errCode = $userinfo->decryptData($encryptedData, $iv, $data );
		            $wxappuser= json_decode($data);
		            if ($errCode == 0) {
		            	$this->auth = array(
						'provider' => 'wxapp',
						'appkey'=>$this->strategy['key'],
						'uid' => $wxappuser->openId,
						'info' => array(
							'name' => $wxappuser->nickName,
							'sex' => $wxappuser->gender,
							'nickname' => $wxappuser->nickName,
							'image' => $wxappuser->avatarUrl,
							'unionId'=> (isset($wxappuser->unionId))?$wxappuser->unionId:''
						),
						'credentials' => array(
							'token' => $sessionKey,
							'expires' => date('c', time() +3600)
						),
						'raw' => $wxappuser
					);
					$this->callback();
		            } else {
		            	$this->auth = array();
		            	$this->callback();
		            }
			}else{
				$this->auth = array(
					'errcode'=>$apiData->errcode,
					'errmsg'=>$apiData->errmsg
				);
		            $this->callback();
			}
		}else{
			$this->auth = array();
		       $this->callback();
		}
	}
	
}


class ErrorCode
{
	public static $OK = 0;
	public static $IllegalAesKey = -41001;
	public static $IllegalIv = -41002;
	public static $IllegalBuffer = -41003;
	public static $DecodeBase64Error = -41004;
}


class WXBizDataCrypt
{
    	private $appid;
	private $sessionKey;

	/**
	 * 构造函数
	 * @param $sessionKey string 用户在小程序登录后获取的会话密钥
	 * @param $appid string 小程序的appid
	 */
	public function __construct( $appid, $sessionKey)
	{
		$this->sessionKey = $sessionKey;
		$this->appid = $appid;
	}


	/**
	 * 检验数据的真实性，并且获取解密后的明文.
	 * @param $encryptedData string 加密的用户数据
	 * @param $iv string 与用户数据一同返回的初始向量
	 * @param $data string 解密后的原文
     *
	 * @return int 成功0，失败返回对应的错误码
	 */
	public function decryptData( $encryptedData, $iv, &$data )
	{
		if (strlen($this->sessionKey) != 24) {
			return ErrorCode::$IllegalAesKey;
		}
		$aesKey=base64_decode($this->sessionKey);

        
		if (strlen($iv) != 24) {
			return ErrorCode::$IllegalIv;
		}
		$aesIV=base64_decode($iv);

		$aesCipher=base64_decode($encryptedData);

		$result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

		$dataObj=json_decode( $result );
		if( $dataObj  == NULL )
		{
			return ErrorCode::$IllegalBuffer;
		}
		if( $dataObj->watermark->appid != $this->appid )
		{
			return ErrorCode::$IllegalBuffer;
		}
		$data = $result;
		return ErrorCode::$OK;
	}

}

