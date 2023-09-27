<?php
if(!defined("SNAPE_VN")) die('No access');

require(dirname(__FILE__).'/http.php');

require(dirname(__FILE__).'/oauth_client.php');

require(dirname(__FILE__).'/openid.php');

// khoi tao class ket noi oauth2

class social_connect extends oauth_client_class {

	private $openid;

	public function __construct() {

		global $_config;

		//$this->userid = $uid;
		$this->offline = false;

    	$this->debug = false;

		$this->debug_http = true;

		$this->openid = new LightOpenID($_config['social']['yahoo']['domain']);
    }

    public function open_connect($type, $remote_url = null) {

    	global $_config, $_SERVER;

    	$this->redirect_uri = (($remote_url != null) ? $remote_url : full_url($_SERVER));

    	//die($this->redirect_uri);

    	switch ($type) {
    		case 'google':
    			$this->server = 'Google';
    			return $this->connect_google();
    			break;

    		case 'yahoo':
    			return $this->connect_yahoo();
    			break;

    		case 'facebook':
    			$this->server = 'Facebook';
    			return $this->connect_facebook();
    			break;
    		
    		default:
    			return null;
    			break;
    	}
    }

    private function connect_facebook() {

    	global $_config;

    	$return = array();

    	$this->client_id = $_config['social']['facebook']['id'];

		$this->client_secret = $_config['social']['facebook']['secret'];

		$this->scope = 'email';

		if(($success = $this->Initialize())) {
			if(($success = $this->Process())) {
				if(strlen($this->access_token)) {
					$success = $this->CallAPI(
						'https://graph.facebook.com/v2.3/me', 
						'GET', array(), array('FailOnAccessError'=>true), $user);
				}
			}

			$success = $this->Finalize($success);
		}

		if($this->exit) {

			$return['type'] = -109;

			$return['content'] = 'Người dùng hủy bỏ yêu cầu';

		} else if($success) {

			$return['type'] = 0;

			$return['content'] = 'Thành công';

			$emailFacebook = $user->id.'@facebook.com';

			$return['result'] = $this->create_social_info(HtmlSpecialChars($user->id), $emailFacebook, $user->name);

		} else {

			$return['type'] = -172;

			$return['content'] = 'Lấy thông tin Facebook thất bại. Vui lòng thử lại sau.';

			$this->ResetAccessToken();

			die(print_r($this->error));
		}

		return $return;
    }

    private function connect_google() {

    	global $_config;

    	$return = array();

    	$this->client_id = $_config['social']['google']['id'];

		$this->client_secret = $_config['social']['google']['secret'];

		if(strlen($this->client_id) == 0 || strlen($this->client_secret) == 0) {

			$return['type'] = -1958;

			$return['content'] = 'Lỗi đồng bộ kết nối Google';

		} else {

			$this->scope = 'https://www.googleapis.com/auth/userinfo.email '.
							'https://www.googleapis.com/auth/userinfo.profile';

			if(($success = $this->Initialize())) {
				if(($success = $this->Process())) {
					if(strlen($this->authorization_error)) {
						$this->error = $this->authorization_error;
						$success = false;
					}
					elseif(strlen($this->access_token)) {
						$success = $this->CallAPI(
							'https://www.googleapis.com/oauth2/v1/userinfo',
							'GET', array(), array('FailOnAccessError'=>true), $user);
					}
				}

				$success = $this->Finalize($success);
			}

			if($this->exit) {

				$return['type'] = -109;

				$return['content'] = 'Người dùng hủy bỏ yêu cầu';

			} else if($success) {

				$return['type'] = 0;

				$return['content'] = 'Thành công';

				$return['result'] = $this->create_social_info(HtmlSpecialChars($user->id), HtmlSpecialChars($user->email), HtmlSpecialChars($user->name));

			} else {

				$return['type'] = -172;

				$return['content'] = 'Lấy thông tin Google thất bại. Vui lòng thử lại sau.';
				
				$this->ResetAccessToken();

				echo HtmlSpecialChars($this->error);
				die();
			}
		}

		return $return;
    }

    private function connect_yahoo() {

    	global $_config;

    	$return = array();

    	if(!$this->openid->mode) {

    		$this->openid->identity = 'https://me.yahoo.com';

    		$this->openid->required = array('contact/email', 'namePerson');

    		header('Location: ' . $this->openid->authUrl());

    		die();

    	} else if($this->openid->mode == 'cancel') {

    		$return['type'] = -1029;

			$return['content'] = 'Người dùng đã hủy yêu cầu';

    	} else {

    		if($this->openid->validate()) {

				$returnServer = $this->openid->getAttributes();

				if(strlen($returnServer['contact/email']) <= 0) {
				
					$return['type'] = -102;

					$return['content'] = 'Lỗi nhận Email trả về. Vui lòng kiểm tra lại.';
					
				} else {
					// can find
					$return['type'] = 0;

					$return['content'] = 'Thành công';

					$return['result'] = $this->create_social_info(str_replace("@", "", $returnServer['contact/email']), $returnServer['contact/email'], $returnServer['namePerson']);
				}

			} else {

				$return['type'] = -1955;

				$return['content'] = 'Không thể nhận thông tin Yahoo.';
				
				$this->ResetAccessToken();

			}

    	}

    	return $return;

    }

    private function create_social_info($id, $email, $name) {

    	$userData = array();

		$userData['ID'] = strtolower($id);

		$userData['Email'] = strtolower($email);

		$userData['Name'] = strtolower($name);

		return $userData;
    }
}
?>