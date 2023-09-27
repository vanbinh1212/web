<?php
class Http
{
    var $target;
	var $proxy;
    var $method;
    var $params;
    var $cookies = array();
    var $cookiesString;
    var $timeout;
    var $referer;
    var $userAgent;
    var $username;
    var $password;
    var $result;
    var $headers;
    var $status;
    var $redirect;
    var $error;
    var $useCookie;

    function Http()
    {
        $this->clear();
    }

    function clear()
    {
        $this->target       = '';
        $this->method       = 'GET';
        $this->params       = array();
        $this->headers      = array();
        $this->error        = '';
        $this->status       = 0;
        $this->timeout      = '60';
        $this->referrer     = '';
        $this->username     = '';
        $this->password     = '';
        $this->userAgent    = 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.5) Gecko/2008120122 Firefox/3.0.5 (.NET CLR 3.5.30729)';
        $this->useCookie    = TRUE;
    }
    function setTarget($url)
    {
        if ($url)
        {
            $this->target = $url;
        }
    }
    function setProxy($ip)
    {
        if ($ip)
        {
            $this->proxy = $ip;
        }
    }
    function setMethod($method)
    {
        if ($method == 'GET' || $method == 'POST')
        {
            $this->method = $method;
        }
    }

    function setCookies($cookies)
    {
        // Get the cookie header as array
        if(gettype($cookies) == "array")
        {
            $this->cookies = $cookies;
        }
        else
        {
            $cookieHeaders = array($cookies);
        }
    }

    function setReferrer($referer)
    {
        if ($referer)
        {
            $this->referrer = $referer;
        }
    }

    function setUseragent($agent)
    {
        if ($agent)
        {
            $this->userAgent = $agent;
        }
    }

    function setTimeout($seconds)
    {
        if ($seconds > 0)
        {
            $this->timeout = $seconds;
        }
    }

    function setParams($dataArray)
    {
        if (is_array($dataArray))
        {
            $this->params = array_merge($this->params, $dataArray);
        }
    }
	
	function setHeaders($dataArray)
    {
        if (is_array($dataArray))
        {
            $this->add_headers = $dataArray;
        }
    }

    function setAuth($username, $password)
    {
        if (!empty($username) && !empty($password))
        {
            $this->username = $username;
            $this->password = $password;
        }
    }

    function addParam($name, $value)
    {
        if (!empty($name) && !empty($value))
        {
            $this->params[$name] = $value;
        }
    }

    function getResult()
    {
        return $this->result;
    }

    function getHeaders()
    {
        return $this->headers;
    }

    function getCookies()
    {
        $cookiesString = "";
        if(is_array($this->cookies) && count($this->cookies) > 0)
        {
            $tempString   = array();
            foreach ($this->cookies as $key => $value)
            {
                $tempString[] = $key . "=" . $value;
            }
            $cookiesString = join(';', $tempString);
        }
        return $cookiesString;
    }

    function getStatus()
    {
        return $this->status;
    }


    function getError()
    {
        return $this->error;
    }

    function execute()
    {
        if(is_array($this->params) && count($this->params) > 0)
        {
            $tempString = array();
            foreach ($this->params as $key => $value)
            {
                if(strlen(trim($value))>0)
                {
                    $tempString[] = $key . "=" . $value;
                }
            }
            $queryString = join('&', $tempString);
        }
        if($this->method == 'GET')
        {
            if(isset($queryString))
            {
                $this->target = $this->target . "?" . $queryString;
            }
        }
        $ch = curl_init();
        if($this->method == 'GET')
        {
            curl_setopt ($ch, CURLOPT_HTTPGET, TRUE);
            curl_setopt ($ch, CURLOPT_POST, FALSE);
        }
        else if($this->method == 'POST')
        {
            curl_setopt ($ch, CURLOPT_POST, TRUE);
            curl_setopt ($ch, CURLOPT_POSTFIELDS, $queryString);
        }
        if ($this->username && $this->password)
        {
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
        }
        if(is_array($this->cookies) && count($this->cookies) > 0)
        {
            $tempString   = array();
            foreach ($this->cookies as $key => $value)
            {
                $tempString[] = $key . "=" . $value;
            }
            $this->cookiesString = join(';', $tempString);
            if($this->useCookie && isset($this->cookiesString))
            {
                curl_setopt ($ch, CURLOPT_COOKIE, $this->cookiesString);
            }
        }
		//setheaders
		if(is_array($this->add_headers) && count($this->add_headers) > 0)
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->add_headers);
		}
        curl_setopt($ch, CURLOPT_HEADER,         TRUE);                 // No need of headers
        curl_setopt($ch, CURLOPT_NOBODY,         FALSE);                // Return body

        curl_setopt($ch, CURLOPT_TIMEOUT,        $this->timeout);       // Timeout
        curl_setopt($ch, CURLOPT_USERAGENT,      $this->userAgent);     // Webbot name
        if(!empty($this->proxy))
        {
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0); 
			curl_setopt($ch, CURLOPT_PROXY, 	 $this->proxy);					// Set Proxy IP
        }
        curl_setopt($ch, CURLOPT_URL,            $this->target);        // Target site
        if(!empty($this->referrer))
        {
            curl_setopt($ch, CURLOPT_REFERER,    $this->referrer);      // Referer value
        }
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');			// gzip, deflate
        curl_setopt($ch, CURLOPT_VERBOSE,        FALSE);                // Minimize logs
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);                // No certificate
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);                 // Return in string

        $content = curl_exec($ch);
        $separator_position = strpos($content, "\r\n\r\n");
        $resHeader = substr($content, 0, $separator_position);
        $this->_parseHeaders($resHeader);
        $this->result = substr($content, $separator_position + 4);
        $this->_setError(curl_error($ch));
        curl_close($ch);
    }
    function _parseHeaders($responseHeader)
    {
        $headers = explode("\r\n", $responseHeader);
        $this->_clearHeaders();
        if($this->status == 0)
        {
            //if(!eregi($match = "^http/[0-9]+\\.[0-9]+[ \t]+([0-9]+)[ \t]*(.*)\$", $headers[0], $matches))
			if(!preg_match("#^http/[0-9]+\\.[0-9]+[ \t]+([0-9]+)[ \t]*(.*)\$#i", $headers[0], $matches))
            {
                $this->_setError('Unexpected HTTP response status');
                return FALSE;
            }
            $this->status = $matches[1];
            array_shift($headers);
        }
        foreach ($headers as $header)
        {
            // Get name and value
            $headerName  = strtolower($this->_tokenize($header, ':'));
            $headerValue = trim(chop($this->_tokenize("\r\n")));

            // If its already there, then add as an array. Otherwise, just keep there
            if(isset($this->headers[$headerName]))
            {
                if(gettype($this->headers[$headerName]) == "string")
                {
                    $this->headers[$headerName] = array($this->headers[$headerName]);
                }

                $this->headers[$headerName][] = $headerValue;
            }
            else
            {
                $this->headers[$headerName] = $headerValue;
            }
        }

        if (isset($this->headers['set-cookie']))
        {
            $this->_parseCookie();
        }

    }
    function _parseCookie()
    {
        // Get the cookie header as array
        if(gettype($this->headers['set-cookie']) == "array")
        {
            $cookieHeaders = $this->headers['set-cookie'];
        }
        else
        {
            $cookieHeaders = array($this->headers['set-cookie']);
        }

        // Loop through the cookies
        for ($cookie = 0; $cookie < count($cookieHeaders); $cookie++)
        {
            $cookieName  = trim($this->_tokenize($cookieHeaders[$cookie], "="));
            $cookieValue = $this->_tokenize(";");
            $this->cookies[$cookieName] = $cookieValue;
        }
    }
    function _clearHeaders()
    {
        $this->headers = array();
    }

    function _tokenize($string, $separator = '')
    {
        if(!strcmp($separator, ''))
        {
            $separator = $string;
            $string = $this->nextToken;
        }

        for($character = 0; $character < strlen($separator); $character++)
        {
            if(gettype($position = strpos($string, $separator[$character])) == "integer")
            {
                $found = (isset($found) ? min($found, $position) : $position);
            }
        }

        if(isset($found))
        {
            $this->nextToken = substr($string, $found + 1);
            return(substr($string, 0, $found));
        }
        else
        {
            $this->nextToken = '';
            return($string);
        }
    }

    function _setError($error)
    {
        if ($error != '')
        {
            $this->error = $error;
            return $error;
        }
    }
	
	function find($string, $start, $end){
		$p1	= strpos($string, $start);
		if($p1 === FALSE) return FALSE;
		$p1 += strlen ($start);
		$p2	= strpos($string, $end, $p1);
		if($p2 === FALSE) return FALSE;
		return substr($string, $p1, $p2-$p1);
	}
}
?>