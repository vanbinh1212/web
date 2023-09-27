<?php
/**
 *  CEO 0386.987.628
 *  Email bovanhvidai2k@gmail.com
 */
class Connect {

    public static function init($pin,$serial,$amount, $cardType){
        $access_token = "MWhHfw67DIpJK1C2XCK6cVqnoB4lP4wf";

        $ch = curl_init();
        $fields = array(
            'access_token'  => $access_token, 
            'code'          => $pin,
            'seri'          => $serial,
            'money'         => $amount,
            'typeCard'      => $cardType
        );
        $data = '';
        foreach($fields as $key=>$value) {
            $data .= $key . "=" . $value . "&";
        }

        $url = "https://doicard5s.com/api/common";
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
        curl_setopt($ch,CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
         // print "curl response is:" . $url;
         // print "curl response is:" . $data;
        return $response;
        curl_close ($ch);
    }
}