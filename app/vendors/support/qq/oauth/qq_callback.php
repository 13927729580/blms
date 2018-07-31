<?php 
//require_once("../comm/config.php");
//require_once("../comm/utils.php");
//App::import('Vendor','support/qq/comm' ,array('file'=>'config.php'));
//App::import('Vendor','support/qq/comm' ,array('file'=>'utils.php'));
function qq_callback()
{
    //debug
    //print_r($_REQUEST);
    //print_r($_SESSION);
	$rs=array();
	$rs['error']=0;
	$rs['return']='';

    if($_REQUEST['state'] == $_SESSION['state']) //csrf
    {
        $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
            . "client_id=" . $_SESSION["appid"]. "&redirect_uri=" . urlencode($_SESSION["callback"])
            . "&client_secret=" . $_SESSION["appkey"]. "&code=" . $_REQUEST["code"];

        $response = file_get_contents($token_url);
        
        if (strpos($response, "callback") !== false)
        {
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
            $msg = json_decode($response);
            if (isset($msg->error))
            {
//                echo "<h3>error:</h3>" . $msg->error;
//                echo "<h3>msg  :</h3>" . $msg->error_description;
//                exit;
				$rs['error']=$msg->error;
				$rs['return']=$msg->error_description;

            }
        }

        $params = array();
        parse_str($response, $params);

        //debug
        //print_r($params);

        //set access token to session
        $_SESSION["access_token"] = $params["access_token"];

    }
    else 
    {
    	$rs['error']=-1;
        $rs['return']="The state does not match. You may be a victim of CSRF.";
    }
    
    return $rs;
}

function get_openid()
{
	$rs=array();
	$rs['error']=0;
	$rs['return']='';
    $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" 
        . $_SESSION['access_token'];

    $str  = file_get_contents($graph_url);
    if (strpos($str, "callback") !== false)
    {
        $lpos = strpos($str, "(");
        $rpos = strrpos($str, ")");
        $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
    }

    $user = json_decode($str);
    if (isset($user->error))
    {
//        echo "<h3>error:</h3>" . $user->error;
//        echo "<h3>msg  :</h3>" . $user->error_description;
//        exit;
		$rs['error']=$user->error;
		$rs['return']=$user->error_description;

		
    }

    //debug
    //echo("Hello " . $user->openid);

    //set openid to session
    $rs['return']=$user->openid;
    return $rs;
}

//QQ登录成功后的回调地址,主要保存access token
//qq_callback();

//获取用户标示id
//get_openid();

//print_r($_SESSION);
//echo "<script>window.close();</script>";
?>
