<?php 

class One {

	var $service_url="http://localhost:2633/RPC2";
	var $session="";
	
	/**
	 * send xml data to OpenNebula RPC server
	 * @param $method
	 * @param $argument
	 */
	function rpc_send($method,$argument){		
		//Using the XML-RPC extension to format the XML package
		$request = xmlrpc_encode_request($method,$argument);
		$req = curl_init($this->service_url);

		//Using the cURL extension to send it off,  first creating a custom header block
		$headers = array();
		array_push($headers,"Content-Type: text/xml");
		array_push($headers,"Content-Length: ".strlen($request));
		array_push($headers,"\r\n");

		//URL to post to
		curl_setopt($req, CURLOPT_URL,$this->service_url);

		//Setting options for a secure SSL based xmlrpc server
		curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($req, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($req, CURLOPT_CUSTOMREQUEST, "POST" );
		curl_setopt($req, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($req, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($req, CURLOPT_POSTFIELDS, $request );

		//Finally run
		$response = curl_exec($req);
				
		//Close the cURL connection
		curl_close($req);
				
		//Decoding the response to be displayed
		$result=xmlrpc_decode($response);	
		
		return $result;	
	}

	/**
	 * 
	 * add user to pool,  return 1 is success return null or 0 is fail. 
	 * @param $username
	 * @param $passwd
	 */	
	function UserAllocate($username,$passwd) {
		$result=$this->rpc_send("one.user.allocate", array($this->session,$username,sha1($passwd)));
		if ($result[0]=true) {
			if ((count($result) > 1) AND ($result[1]>0)) {
				$result=true;
			} else {
				$result=false;
			}
		}
		return $result;
	}
	
	/**
	 * Change password, return 1 is success return null is fail. 
	 * @param int $uid
	 * @param string $password
	 */	
	function UserPasswd($uid,$passwd) {
		$result=$this->rpc_send("one.user.passwd", array($this->session,$uid,sha1($passwd)));	

		if ($result[0]=true) {
			if (count($result) > 1) {
				$result=false;
			} else {
				$result=true;
			}		
		}
		return $result;
		
	}

	/**
	 * Delete user in pool, return 1 is success return null is fail.
	 * @param int $uid
	 */
	function UserDelete($uid) {
		$result=$this->rpc_send("one.user.delete", array($this->session,$uid));		
		if ($result[0]=true) {
			if (count($result) > 1) {
				$result=false;
			} else {
				$result=true;
			}		
		}
		return $result;
	}	
	
	/**
	 * List all users in pool, return is SimpleXML object.
	 */
	function UserPool() {
		$result=$this->rpc_send("one.userpool.info", array($this->session));		
		if (($result[0]==true)) {
			$xml=simplexml_load_string($result[1]);
		}		
		return $xml;
	}
	
	/**
	 * User authentication, return 1 is success return null is fail.
	 * @param string $username
	 * @param string $password
	 */	
	function UserAuthen($username,$password) {
		$result=$this->rpc_send("one.userpool.info",array($this->session));
		$salt=$username.":".sha1($password);
		if (($result[0]==true)) {
			$xml=simplexml_load_string($result[1]);
			foreach ($xml->USER as $item) {	
				$saltorig=$item->NAME.":".$item->PASSWORD;
				if ($salt==$saltorig) {
					$result=true;
					return $result;
				} 
			}
		}		
		
	}
	

	
}

?>