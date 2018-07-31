<?php

/* 收取邮件主类 */
class pop3
{	
	var $hostname=""; // POP主机名 
	var $port=110; // 主机的POP3端口，一般是110号端口 
	var $timeout         = 5;
	var $connection=0; // 保存与主机的连接 
	var $state="DISCONNECTED"; // 保存当前的状态　
	var $debug=0;
	var $err_str='';
	var $err_no;
	var $resp;
	var $apop;
	var $messages;
	var $size;
	var $mail_list;
	var $head=array();
	var $body=array();

	Function pop3($server="192.100.100.1",$port=110,$time_out=5){
		$this->hostname=$server;
		$this->port=$port;
		$this->timeout=$time_out;
		return true;
	}
	Function open() {
		if($this->hostname==""){
			$this->err_str="无效的主机名!!"; 
			return false;
		}
		if ($this->debug) echo "正在打开　$this->hostname,$this->port,&$err_no, &$err_str, $this->timeout<BR>"; 
		if (!$this->connection=fsockopen($this->hostname,$this->port,&$err_no, &$err_str, $this->timeout)){
			$this->err_str="连接到POP服务器失败，错误信息：".$err_str."错误号：".$err_no; 
			return false; 
		}else{
			$this->getresp();
			if($this->debug)$this->outdebug($this->resp); 
			if (substr($this->resp,0,3)!="+OK"){
				$this->err_str="服务器返回无效的信息：".$this->resp."请检查POP服务器是否正确";
				return false; 
			}
			$this->state="AUTHORIZATION"; 
			return true; 
		}		
	}
	
	Function getresp(){
		for($this->resp="";;){
			if(feof($this->connection))return false; 
			$this->resp.=fgets($this->connection,100); 
			$length=strlen($this->resp);
			if($length>=2 && substr($this->resp,$length-2,2)=="\r\n"){
				$this->resp=strtok($this->resp,"\r\n");
				return true;
			}
		}
	}
	
	Function outdebug($message){
		echo htmlspecialchars($message)."<br>\n"; 
	}
	
	Function command($command,$return_lenth=1,$return_code='+') {
		if ($this->connection==0){
			$this->err_str="没有连接到任何服务器，请检查网络连接";
			return false; 
		}
		if ($this->debug) $this->outdebug(">>> $command"); 
		if (!fputs($this->connection,"$command\r\n")) {
			$this->err_str="无法发送命令".$command; 
			return false;
		}else{
			$this->getresp();
			if($this->debug) $this->outdebug($this->resp); 
			if (substr($this->resp,0,$return_lenth)!=$return_code) {
				$this->err_str=$command." 命令服务器返回无效:".$this->resp; 
				return false; 
			}else{
				return true; 
			}
		}		
	}
	
	Function Login($user,$password){
		if($this->state!="AUTHORIZATION"){
			$this->err_str="还没有连接到服务器或状态不对"; 
			return false; 
		}
		if (!$this->apop){
			if (!$this->command("USER $user",3,"+OK")) return false; 
			if (!$this->command("PASS $password",3,"+OK")) return false; 
		}else{
			if (!$this->command("APOP $user ".md5($this->greeting.$password),3,"+OK")) return false; 
		}
		$this->state="TRANSACTION"; 
		return true; 
	}
	
	Function stat(){
		if($this->state!="TRANSACTION"){
			$this->err_str="还没有连接到服务器或没有成功登录"; 
			return false; 
		}
		if (!$this->command("STAT",3,"+OK")) {
			return false; 
		}else{
			$this->resp=strtok($this->resp," "); 
			$this->messages=strtok(" ");
			$this->size=strtok(" ");
			return true;
		}
	}
	
	Function listmail($mess=null,$uni_id=null){
		if($this->state!="TRANSACTION"){
			$this->err_str="还没有连接到服务器或没有成功登录";
			return false;
		}
		if ($uni_id)$command="UIDL "; 
		else $command="LIST "; 
		if ($mess) $command.=$mess; 
		if (!$this->command($command,3,"+OK")){
			return false; 
		} else {
			$i=0;
			$this->mail_list=array(); 
			$this->getresp();
			while ($this->resp!="."){
				$i++; 
				if ($this->debug){
					$this->outdebug($this->resp); 
				}
				if ($uni_id){
					$this->mail_list[$i]['num']=strtok($this->resp," ");
					$this->mail_list[$i]['size']=strtok(" ");
				}else{
					$this->mail_list[$i]["num"]=intval(strtok($this->resp," "));
					$this->mail_list[$i]["size"]=intval(strtok(" ")); 
				}
				$this->getresp();
			}
			return true; 
		}
	}
	
	function getmail($num=2,$line=-1){
		if($this->state!="TRANSACTION"){
			$this->err_str="不能收取信件，还没有连接到服务器或没有成功登录"; 
			return false; 
		}
		if ($line<0) $command="RETR $num"; 	
		else $command="TOP $num $line"; 
		if (!$this->command("$command",3,"+OK"))  return false; 
		else {
			$this->getresp();
			$is_head=true;
			while ($this->resp!="."){
				if ($this->debug)
				$this->outdebug($this->resp);
				if (substr($this->resp,0,1)==".")
				$this->resp=substr($this->resp,1,strlen($this->resp)-1); 
				if (trim($this->resp)=="") 
				$is_head=false;	
				if ($is_head)	
				$this->head[]=$this->resp; 
				else $this->body[]=$this->resp; 
				$this->getresp(); 	
			}
			return true; 
		}
	}
	
	function dele($num){
		if($this->state!="TRANSACTION"){
			$this->err_str="不能删除远程信件，还没有连接到服务器或没有成功登录";
			return false; 
		}
		if (!$num){
			$this->err_str="删除的参数不对"; 
			return false; 
		}
		if ($this->command("DELE $num ",3,"+OK")) return true;
		else return false; 
	}
	
	Function Close() {
		if($this->connection!=0){
			if($this->state=="TRANSACTION")
			$this->command("QUIT",3,"+OK");
			fclose($this->connection);
			$this->connection=0; 
			$this->state="DISCONNECTED"; 
		}
	}

}
include("decode.php"); 

?>
