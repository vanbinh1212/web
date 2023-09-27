<?php
class Api_8Pay_CardCharging {
    
    public $uid;
    public $user_api;
    public $pass_api;
    public $pin;
    public $seri;
    public $card_type;
    public $msg;
    public $info_card;
    public $note;

	public function charging() {
        $fields = array(
            'merchant_id' => $this->uid,
            'pin' => $this->pin,
            'seri' => $this->seri,
            'card_type' => $this->card_type,
            'note' => $this->note
        );
        
        $ch = curl_init("http://sv.8pay.vn:8181/api/CardCharging");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_USERPWD, $this->user_api . ":" . $this->pass_api);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $result = json_decode($result);

        $this->msg = $result->msg;
        $this->info_card = $result->info_card;
    }
}

?>