<?php
/*
  * Warning! Read and use at your own risk!
  *
  * This tiny proxy script is completely transparent and it passes
  * all requests and headers without any checking of any kind.
  * The same happens with JSON data. They are simply forwarded.
  *
  * This is just an easy and convenient solution for the AJAX
  * cross-domain request issue, during development.
  * No sanitization of input is made either, so use this only
  * if you are sure your requests are made correctly and
  * your urls are valid.
  *
	* ============================================================
	* Copyright 2020, Sergio Casizzone
	* ============================================================
	* @author   Sergio Casizzone (http://sergiocasizzone.altervista.org)
	* @version  0.1
 */

class proxyCurl {

	/**
	 * @param URL Path $url
	 * @param REFERER Referer Path $ref
	 * @param POST Data $req
	 * @param METHOD Method of request $verb
	 * @param POST Data $req
	 * @param CONNECT boolean Force a new connection to be used $fresh
	 *
	 * @return Html page containing data returned from the path
	 */

	protected $proxytunnel = false;	// set proxy
 	protected $proxyurl = null;		// set proxy
 	protected $proxyuserpwd = null;	// set proxy


	/**
	 * questa funzione imposta il proxy
		 * I parametri vanno inviati in formato array
		 *
		 * @param address Url del proxy
		 * @param port Porta del proxy
		 * @param user Nome utente per accedere al proxy
		 * @param pass Password
	*/
	public function setProxy($array){
		$this->proxytunnel = true;
		$this->proxyurl = $array['proxyURI'];
		$this->proxyuserpwd = $array['username'].':'.$array['password'];
	}

	public function isLocalhost($whitelist = ['127.0.0.1', '::1']) {
	  return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
	}

	public function getUrl($url, array $req = array()){
		$timeout = 15; // wait 15 seconds

		// generate the POST data string
    $post_data = http_build_query($req, '', '&');

		$headers = getallheaders();
    $headers_str = [];

		// recreate originale headers
		foreach ( $headers as $key => $value){
      if($key == 'Host')
        continue;
      $headers_str[]=$key.":".$value;
    }

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; InfoPath.2; .NET CLR 2.0.50727)');

		curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout);

		// set proxy
		if ($this->proxytunnel == true){
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, $this->proxytunnel);
			curl_setopt($ch, CURLOPT_PROXY, $this->proxyurl);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxyuserpwd);
			curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_1);
		}

		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // man-in-the-middle defense by verifying ssl cert.
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);  // man-in-the-middle defense by verifying ssl cert.
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);

		curl_setopt($ch, CURLOPT_REFERER, $url);

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_str);

		// run the query
		$res = curl_exec($ch);

		// check timeout error
		if ($error_number = curl_errno($ch))
    	if (in_array($error_number, array(CURLE_OPERATION_TIMEDOUT, CURLE_OPERATION_TIMEOUTED)))
      	return ( json_encode(array("success"=>false,"error_number"=>524,"info"=>"A timeout occurred!")) );


		curl_close($ch);
		return $res;
	}


}
?>
