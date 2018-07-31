<?php
//App::import('Vendor','support/qq/comm' ,array('file'=>'config.php'));
//App::import('Vendor','support/qq/comm' ,array('file'=>'utils.php'));
//require_once("../comm/config.php");
//require_once("../comm/utils.php");
//print_r ($_SESSION);
function get_user_info()
{
    $get_user_info = "https://graph.qq.com/user/get_user_info?"
        . "access_token=" . $_SESSION['access_token']
        . "&oauth_consumer_key=" . $_SESSION["appid"]
        . "&openid=" . $_SESSION["openid"]
        . "&format=json";

    $info = file_get_contents($get_user_info);
    $arr = json_decode($info, true);

    return $arr;
}

function get_user_info_beta($access_token,$appid,$openid)
{
    $get_user_info = "https://graph.qq.com/user/get_user_info?"
        . "access_token=" . $access_token
        . "&oauth_consumer_key=" . $appid
        . "&openid=" . $openid
        . "&format=json";

    $info = file_get_contents($get_user_info);
    $arr = json_decode($info, true);

    return $arr;
}
function get_user_info_beta2($access_token,$appid,$openid)
{
    $get_user_info = "https://graph.qq.com/user/get_info?"
        . "access_token=" . $access_token
        . "&oauth_consumer_key=" . $appid
        . "&openid=" . $openid
        . "&format=json";
	echo $get_user_info;
    $info = file_get_contents($get_user_info);
    $arr = json_decode($info, true);

    return $arr;
}
/*
error exp:
$arr["ret"]=2021;
$arr["msg"]='请先登录';

*/
//获取用户基本资料
//$arr = get_user_info();
//var_dump($arr);
//
//echo "<p>";
//echo "Gender:".$arr["gender"];
//echo "</p>";
//echo "<p>";
//echo "NickName:".$arr["nickname"];
//echo "</p>";
//echo "<p>";
//echo "<img src=\"".$arr['figureurl']."\">";
//echo "<p>";
//echo "<p>";
//echo "<img src=\"".$arr['figureurl_1']."\">";
//echo "<p>";
//echo "<p>";
//echo "<img src=\"".$arr['figureurl_2']."\">";
//echo "<p>";

?>
