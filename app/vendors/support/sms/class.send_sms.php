<?php

require_once('class.transport.php');

/* 短信模块主类 */
class send_sms
{
	
     // 存放提供远程服务的URL。
	var $api_urls   = array('send'=>'http://mb345.com/WS/Send.aspx',
							'get'=>'http://mb345.com/WS/Get.aspx'
							);
//	var $uid		="LINKT00024";
//	var $pwd		="489852";

    //存放transport对象
    var $t          = null;

    /**
     * 存放程序执行过程中的错误信息，这样做的一个好处是：程序可以支持多语言。
     * 程序在执行相关的操作时，error_no值将被改变，可能被赋为空或大等0的数字.
     * 为空或0表示动作成功；大于0的数字表示动作失败，该数字代表错误号。
     *
     * @access  public
     * @var     array       $errors
     */
    var $errors  = array('api_errors'       => array('error_no' => -1, 'error_msg' => ''),
                         'server_errors'    => array('error_no' => -1, 'error_msg' => ''));


    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
    function __construct()
    {
        $this->send_sms();
    }

    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
    function send_sms()
    {       
    	/* 此处最好不要从$GLOBALS数组里引用，防止出错 */
        $this->t = new transport(-1, -1, -1, false);
    }

    /**
     * 返回指定键名的URL
     *
     * @access  public
     * @param   string      $key        URL的名字，即数组的键名
     * @return  string or boolean       如果由形参指定的键名对应的URL值存在就返回该URL，否则返回false。
     */
    function get_url($key)
    {
        $url = $this->api_urls[$key];

        if (empty($url))
        {
            return false;
        }

        return $url;
    }


    /**
     * 发送短消息
     *
     * @access  public
     * @param   string  $phone          要发送到哪些个手机号码，多个号码用半角逗号隔开
     * @param   string  $msg            发送的消息内容
     * @param   string  $send_date      定时发送时间
     * @return  boolean                 发送成功返回true，失败返回false。
     */
    function send($uid,$pwd,$phone, $msg, $send_date)
    {
        /* 获取API URL */
        $url = $this->get_url('send');
		$new_msg=$this->make_semiangle($msg);
	    $params = array('CorpID' => $uid,
			        	'Pwd' => $pwd,
						'Mobile' => $phone,
                        'Content' => iconv('utf-8','gb2312',$new_msg),
                        'Cell'=> '',
            			'SendTime'=> $send_date);
     				
		
        /* 发送HTTP请求 */
        $this->t = new transport(-1, -1, -1, false);
        $response = $this->t->request($url, $params);
        return $response['body'];
    }
    //cronjob使用
     function send_auto($uid,$pwd,$phone, $msg, $send_date)
    {
        /* 获取API URL */
        $url = $this->get_url('send');
		$this->cht=new Chinese();
		$new_msg=$this->make_semiangle($msg);
	    $params = array('CorpID' => $uid,
			        	'Pwd' => $pwd,
						'Mobile' => $phone,
                        'Content' => $this->cht->Convert('utf-8', 'GB2312',$new_msg),  
                        'Cell'=> '',
            			'SendTime'=> $send_date);
     				
		
        /* 发送HTTP请求 */
        $this->t = new transport(-1, -1, -1, false);
        $response = $this->t->request($url, $params);
        return $response['body'];
    }   
    
    
    /*接收短信*/
	function receive($uid,$pwd){
        /* 获取API URL */
        $url = $this->get_url('get');

       $params = array('CorpID' => $uid,
			        	'Pwd' => $pwd
						);
     						
        /* 发送HTTP请求 */
        $this->t = new transport(-1, -1, -1, false);
        $response = $this->t->request($url, $params);
        return $response['body'];
	}

	function make_semiangle($str)
	{
	    $arr = array('０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
	                 '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
	                 'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
	                 'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
	                 'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
	                 'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
	                 'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
	                 'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
	                 'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
	                 'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
	                 'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
	                 'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
	                 'ｙ' => 'y', 'ｚ' => 'z',
	                 '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[',
	                 '】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']',
	                 '‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<',
	                 '》' => '>','·'=>'.',
	                 '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
	                 '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.',
	                 '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
	                 '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"',
	                 '　' => ' ');

	    return strtr($str, $arr);
	}

}

?>