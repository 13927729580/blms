<?php
class KoudaiComponent extends Component
{
	public $name = 'Koudai';
    public $components = array('RequestHandler');
	const VERSION = '1.0';
	
	private static $apiEntry = 'https://open.koudaitong.com/api/entry';
	
	private $appId;
	private $appSecret;
	private $format = 'json';
	private $signMethod = 'md5';
	const APP_ID_KEY = 'app_id';
	const METHOD_KEY = 'method';
	const TIMESTAMP_KEY = 'timestamp';
	const FORMAT_KEY = 'format';
	const VERSION_KEY = 'v';
	const SIGN_KEY = 'sign';
	const SIGN_METHOD_KEY = 'sign_method';
	const ALLOWED_DEVIATE_SECONDS = 600;
	const ERR_SYSTEM = -1;
	const ERR_INVALID_APP_ID = 40001;
	const ERR_INVALID_APP = 40002;
	const ERR_INVALID_TIMESTAMP = 40003;
	const ERR_EMPTY_SIGNATURE = 40004;
	const ERR_INVALID_SIGNATURE = 40005;
	const ERR_INVALID_METHOD_NAME = 40006;
	const ERR_INVALID_METHOD = 40007;
	const ERR_INVALID_TEAM = 40008;
	const ERR_PARAMETER = 41000;
	const ERR_LOGIC = 50000;
	private static $boundary = '';
	
	public static function client_get($url, $params) {
		$url = $url . '?' . http_build_query($params);
		return self::http($url, 'GET');
	}

	public static function client_post($url, $params, $files = array()) {
		$headers = array();
		if (!$files) {
			$body = http_build_query($params);
		} else {
			$body = self::build_http_query_multi($params, $files);
			$headers[] = "Content-Type: multipart/form-data; boundary=" . self::$boundary;
		}
		return self::http($url, 'POST', $body, $headers);
	}
	
	/**
	 * Make an HTTP request
	 *
	 * @return string API results
	 * @ignore
	 */
	private static function http($url, $method, $postfields = NULL, $headers = array()) {
		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($ci, CURLOPT_USERAGENT, 'KdtApiSdk Client v0.1');
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ci, CURLOPT_TIMEOUT, 30);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_ENCODING, "");
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 1);
		//curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
		curl_setopt($ci, CURLOPT_HEADER, FALSE);
		switch ($method) {
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, TRUE);
				if (!empty($postfields)) {
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
				}
				break;
		}
		curl_setopt($ci, CURLOPT_URL, $url );
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );
		$response = curl_exec($ci);
		$httpCode = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$httpInfo = curl_getinfo($ci);
		curl_close ($ci);
		return $response;
	}
	
	public static function sign($appSecret, $params, $method = 'md5') {
		if (!is_array($params)) $params = array();
		ksort($params);
		$text = '';
		foreach ($params as $k => $v) {
			$text .= $k . $v;
		}
		return self::hash($method, $appSecret . $text . $appSecret);
	}
	
	private static function hash($method, $text) {
		switch ($method) {
			case 'md5':
			default:
				$signature = md5($text);
				break;
		}
		return $signature;
	}
	
	public static function allowedSignMethods() {
		return array('md5');
	}
	
	public static function allowedFormat() {
		return array('json');
	}

	public static function doc() {
		return array(
			'params' => array(
				self::APP_ID_KEY => array(
					'type' => 'String',
					'required' => true,
					'desc' => 'App ID',
				),
				self::METHOD_KEY => array(
					'type' => 'String',
					'required' => true,
					'desc' => 'API�ӿ�����',
				),
				self::TIMESTAMP_KEY => array(
					'type' => 'String',
					'required' => true,
					'desc' => 'ʱ�������ʽΪyyyy-mm-dd HH:mm:ss�����磺2013-05-06 13:52:03�����������ͻ�������ʱ�����Ϊ' . intval(self::ALLOWED_DEVIATE_SECONDS / 60) . '���ӡ�',
				),
				self::FORMAT_KEY => array(
					'type' => 'String',
					'required' => false,
					'desc' => '��ѡ��ָ����Ӧ��ʽ��Ĭ��json,Ŀǰ֧�ָ�ʽΪjson',
				),
				self::VERSION_KEY => array(
					'type' => 'String',
					'required' => true,
					'desc' => 'APIЭ��汾����ѡֵ:1.0',
				),
				self::SIGN_KEY => array(
					'type' => 'String',
					'required' => true,
					'desc' => '�� API ����������� md5 ���ܻ�ã���ϸ�ο�ǩ���½�',
				),
				self::SIGN_METHOD_KEY => array(
					'type' => 'String',
					'required' => false,
					'desc' => '��ѡ�������ļ��ܷ���ѡ��Ĭ��Ϊmd5����ѡֵ�ǣ�md5',
				),
			),
			
		);
	}
	
	public static function errors() {
		return array(
			'response' => array (
				'code' => array (
					'type' => 'Number',
					'desc' => '������',
					'example' => 40002,
					'required' => true,
				),
				'msg' => array (
					'type' => 'String',
					'desc' => '������Ϣ',
					'example' => 'invalid app',
					'required' => true,
				),
				'params' => array (
					'type' => 'List',
					'desc' => '��������б�',
					'example' => array(
						'app_id' => 'ac9aaepv37d2a5guc',
						'method' => 'kdt.trades.sold.get',
						'timestamp' => '2014-01-20 20:38:42',
						'format' => 'json',
						'sign_method' => 'md5',
						'v' => '1.0',
						'sign' => 'wi93n31d034a9207ert7d3971e3vno10',
					),
					'required' => true,
				),
			),
			'errors' => array(
				self::ERR_SYSTEM => array(
					'desc' => 'ϵͳ����',
					'suggest' => '',
				),
				self::ERR_INVALID_APP_ID => array(
					'desc' => 'δָ�� AppId',
					'suggest' => '����ʱ���� AppId',
				),
				self::ERR_INVALID_APP => array(
					'desc' => '��Ч��App',
					'suggest' => '������Ч�� AppId',
				),
				self::ERR_INVALID_TIMESTAMP => array(
					'desc' => '��Ч��ʱ�����',
					'suggest' => '�Ե�ǰʱ�����·����������ϵͳʱ��ͷ�����ʱ������10���ӣ������ϵͳʱ��',
				),
				self::ERR_EMPTY_SIGNATURE => array(
					'desc' => '����û��ǩ��',
					'suggest' => '��ʹ��Э��淶�������еĲ�������ǩ��',
				),
				self::ERR_INVALID_SIGNATURE => array(
					'desc' => 'ǩ��У��ʧ��',
					'suggest' => '��� AppId �� AppSecret �Ƿ���ȷ����������п�����Э���װ���������',
				),
				self::ERR_INVALID_METHOD_NAME => array(
					'desc' => 'δָ������� Api ����',
					'suggest' => 'ָ�� Api ����',
				),
				self::ERR_INVALID_METHOD => array(
					'desc' => '����Ƿ��ķ���',
					'suggest' => '�������ķ�����ֵ',
				),
				self::ERR_INVALID_TEAM => array(
					'desc' => 'У���Ŷ���Ϣʧ��',
					'suggest' => '����Ŷ��Ƿ���Ч���Ƿ��΢��',
				),
				self::ERR_PARAMETER => array(
					'desc' => '���󷽷��Ĳ�������',
					'suggest' => '',
				),
				self::ERR_LOGIC => array(
					'desc' => '���󷽷�ʱҵ���߼���������',
					'suggest' => '',
				),
			),
		);
	}
	
	public function set_appId($appId) {
		if($appId!=""){
			$this->appId = $appId;
		}
	}
	
	public function set_appSecret($appSecret) {
		if($appSecret!=""){
			$this->appSecret = $appSecret;
		}
	}
	
	public function get($method, $params = array()) {
		return $this->parseResponse(
			self::client_get(self::$apiEntry, $this->buildRequestParams($method, $params))
		);
	}
	
	public function post($method, $params = array(), $files = array()) {
		return $this->parseResponse(
			self::client_post(self::$apiEntry, $this->buildRequestParams($method, $params), $files)
		);
	}
	
	
	
	public function setFormat($format) {
		if (!in_array($format, self::allowedFormat()))
			throw new Exception('���õ����ݸ�ʽ����');
		
		$this->format = $format;
		
		return $this;
	}
	
	public function setSignMethod($method) {
		if (!in_array($method, self::allowedSignMethods()))
			throw new Exception('���õ�ǩ����������');
		
		$this->signMethod = $method;
		
		return $this;
	}
	
	

	private function parseResponse($responseData) {
		$data = json_decode($responseData, true);
		if (null === $data) throw new Exception('response invalid, data: ' . $responseData);
		return $data;
	}
	
	private function buildRequestParams($method, $apiParams) {
		if (!is_array($apiParams)) $apiParams = array();
		$pairs = $this->getCommonParams($method);
		foreach ($apiParams as $k => $v) {
			if (isset($pairs[$k])) throw new Exception('��������ͻ');
			$pairs[$k] = $v;
		}
		
		$pairs[self::SIGN_KEY] = self::sign($this->appSecret, $pairs, $this->signMethod);
		return $pairs;
	}
	
	private function getCommonParams($method) {
		$params = array();
		$params[self::APP_ID_KEY] = $this->appId;
		$params[self::METHOD_KEY] = $method;
		$params[self::TIMESTAMP_KEY] = date('Y-m-d H:i:s');
		$params[self::FORMAT_KEY] = $this->format;
		$params[self::SIGN_METHOD_KEY] = $this->signMethod;
		$params[self::VERSION_KEY] = self::VERSION;
		return $params;
	}
	
	private static function build_http_query_multi($params, $files) {
		if (!$params) return '';

		$pairs = array();

		self::$boundary = $boundary = uniqid('------------------');
		$MPboundary = '--'.$boundary;
		$endMPboundary = $MPboundary. '--';
		$multipartbody = '';

		foreach ($params as $key => $value) {
			$multipartbody .= $MPboundary . "\r\n";
			$multipartbody .= 'content-disposition: form-data; name="' . $key . "\"\r\n\r\n";
			$multipartbody .= $value."\r\n";
		}
		foreach ($files as $key => $value) {
			if (!$value) {continue;}
			
			if (is_array($value)) {
				$url = $value['url'];
				if (isset($value['name'])) {
					$filename = $value['name'];
				} else {
					$parts = explode( '?', basename($value['url']));
					$filename = $parts[0];
				}
				$field = isset($value['field']) ? $value['field'] : $key;
			} else {
				$url = $value;
				$parts = explode( '?', basename($url));
				$filename = $parts[0];
				$field = $key;
			}
			$content = file_get_contents($url);
		
			$multipartbody .= $MPboundary . "\r\n";
			$multipartbody .= 'Content-Disposition: form-data; name="' . $field . '"; filename="' . $filename . '"'. "\r\n";
			$multipartbody .= "Content-Type: image/unknown\r\n\r\n";
			$multipartbody .= $content. "\r\n";
		}

		$multipartbody .= $endMPboundary;
		return $multipartbody;
	}
}
