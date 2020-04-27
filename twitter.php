<?php
require_once 'htmlDOM.php';

class Twitter{

    public $status;

    private $cookieDir;
    
    public function __construct(){
        $this->cookieDir = dirname(__FILE__) . '/twitter.cookie';
        
    }

    private function getAuthenticityToken(){
        $ch = curl_init('https://twitter.com/login');

        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieDir);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieDir);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);

        $oDom = new simple_html_dom();
        $oDom->load($result);
        return $oDom->find('[name="authenticity_token"]', 0)->value;
    }

    public function login($user, $password){
        $authenticityToken = $this->getAuthenticityToken();

        $postFields  =  "'session[username_or_email]':'$user'";
        $postFields .= "&'session[password]': '$password'";
        $postFields .= "&'remember_me': 1";
        $postFields .= "&'return_to_ssl': 'true'";
        $postFields .= "&'authenticity_token': '$authenticityToken'";

        $ch = curl_init('https://twitter.com/sessions');

        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieDir);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieDir);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, "{$postFields}");

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        // $headers[] = 'X-Guest-Token: ' . $this->guestToken;
        // $headers[] = 'Authorization: Bearer AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        echo $result;
    }
}