<?php
error_reporting(E_ALL);  
class gmail
{		    
	

	function getData($username,$pw){
		 $user = $username;  
		 $password = $pw;  
		    
		 // ref: http://code.google.com/apis/accounts/docs/AuthForInstalledApps.html  
		    
		 // step 1: login  
		 $login_url = "https://www.google.com/accounts/ClientLogin";  
		 $fields = array(  
		     'Email' => $user,  
		     'Passwd' => $password,  
		     'service' => 'cp', // <== contact list service code  
		     'source' => 'test-google-contact-grabber',  
		     'accountType' => 'GOOGLE',  
		 );  
		    
		 $curl = curl_init();  
		 curl_setopt($curl, CURLOPT_URL,$login_url);  
		 curl_setopt($curl, CURLOPT_POST, 1);  
		 curl_setopt($curl, CURLOPT_POSTFIELDS,$fields);  
		 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);  
		 curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);   
		 $result = curl_exec($curl);  
		    
		 $returns = array();  
		    
		 foreach (explode("\n",$result) as $line)  
		 {  
		     $line = trim($line);  
		     if (!$line) continue;  
		     list($k,$v) = explode("=",$line,2);  
		    
		     $returns[$k] = $v;  
		 }  
		    
		 curl_close($curl);  
		 if(!isset($returns['Auth']))
		 	 return 3;
		 // step 2: grab the contact list  
		 $feed_url = "http://www.google.com/m8/feeds/contacts/$user/full?alt=json&max-results=250";  
		    
		 $header = array(  
		     'Authorization: GoogleLogin auth=' . $returns['Auth'],  
		 );  
		    
		 $curl = curl_init();  
		 curl_setopt($curl, CURLOPT_URL, $feed_url);  
		 curl_setopt($curl, CURLOPT_HTTPHEADER, $header);  
		 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);  
		 curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
		 curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);   
		    
		 $result = curl_exec($curl);  
		 curl_close($curl);  
		 if(empty($result))  
		 	 return 0; 
		 $data = json_decode($result);  
		 
		 $contacts = array();  
		 
		 foreach ($data->feed->entry as $entry)  
		 {  
		     $contact = array();  
		     @$contact['name'] = $entry->title->{'$t'};  
		     @$contact['email'] = $entry->{"gd".'$email'}[0]->address;  
		    
		     $contacts[] = $contact;  
		 }  
		     
		 return $contacts;  
	}


}

