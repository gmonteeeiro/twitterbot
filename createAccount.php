<?php
class CreateAccount{
    private $guestToken = null;
    private $flowToken  = null;

    private $displayName = null;
    private $email = null;

    function __construct($displayName, $email){
        $this->displayName = $displayName;
        $this->email = $email;

        $this->guestToken = $this->getGuestToken();
        $this->flowToken  = $this->getFlowToken();
    }

    private function getGuestToken(){
        $ch = curl_init('https://twitter.com/i/flow/signup');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        
        $gt = explode('("gt=', $result)[1];
        $gt = explode(';', $gt)[0];

        return $gt;
    }

    private function getFlowToken(){
        $ch = curl_init('https://api.twitter.com/1.1/onboarding/task.json?flow_name=signup');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"input_flow_data":{"flow_context":{"debug_overrides":{},"start_location":{"location":"manual_link"}}},"subtask_versions":{"contacts_live_sync_permission_prompt":0,"email_verification":1,"topics_selector":1,"wait_spinner":1}}');

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'X-Guest-Token: ' . $this->guestToken;
        $headers[] = 'Authorization: Bearer AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = json_decode(curl_exec($ch));
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        if(isset($result->flow_token)){
            return $result->flow_token;
        }
        else return false;
    }

    private function getPostFieldsOfActivationCode($activationCode){
        return array(
            "flow_token" => $this->flowToken,
            "subtask_inputs" => array(
                array(
                    "subtask_id"=> "Signup",
                    "sign_up" => array(
                        "email" => $this->email,
                        "js_instrumentation" => array(
                            "response" => array(
                                "rf" => array(
                                    "a56d82284882e5c2e504f4944617cb99fb001ece1e236e84e32310edf51f0f95" => 0,
                                    "ac4dfd750241d796ad61af937e1590f0ad9641e9ad7b18f18846c23b4bfbb5b2" => 211,
                                    "af5c5a92b20a0bd317c93333bd6279f695d773711ab1f5c0f4979e5dab7a1244" => -12,
                                    "a2ef718060968654997f9bedf9cfefb3eff2399387a2b80ad03eb75143b3863d" => -144
                                ),
                                "s" => "mr4Q-N0A--P8Bq_I_OcRLn50nTVry7TYNUe0qXA8i3svKTz6wNyAizJkUL2gnCGWSty-UHa52ZCI24-MFvKtPD1gxqEekYVIqC4-GxgeK5rviIB1MOnohsHRnWl1c5BrF-MXTzRLLhLC50MBlQvAFZfuxkfNrfOrxCzE8rRHTKp9oa6Z_E_X3NRendNazLWbGglrbiahQCh5Di_d39ku-Oia2rteb0u1lNj4gQNCKcNqqfJozQK66lnW7sLb9o42aCo-5XM2h7VvInwBHEVoxHK672_D7is3T1pgPB8s6i5ZdKgw2Js9h6v38xO0bfVylzIl0_oPvzzj5cfhaRsAiwAAAXGDeGb-"
                            )
                        ),
                        "link" => "next_link",
                        "name" => $this->displayName,
                        "personalization_settings" => array(
                            "allow_cookie_use" => true,
                            "allow_device_personalization" => true,
                            "allow_partnerships" => true,
                            "allow_ads_personalization" => true
                        )
                    )
                ),
                array(
                    "subtask_id" => "SignupSettingsListNonEU",
                    "settings_list" => array(
                        "setting_responses" => array(
                            array(
                                "key"=> "twitter_for_web",
                                "response_data"=> array(
                                    "boolean_data" => array(
                                        "result"=> false
                                    )
                                )
                            )
                        ),
                        "link" => "next_link"
                    )
                ),
                array(
                    "subtask_id" => "SignupReview",
                    "sign_up_review" => array(
                        "link" => "signup_with_email_next_link"
                    )
                ),
                array(
                    "subtask_id" => "EmailVerification",
                    "email_verification" => array(
                        "code" => "$activationCode",
                        "email" => $this->email,
                        "link" => "next_link"
                    )
                )
            )
        );
    }

    private function curlTaskJson($postFields){
        $ch = curl_init('https://api.twitter.com/1.1/onboarding/task.json');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));

        $headers = array();
        $headers[] = 'X-Guest-Token: ' . $this->guestToken;
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $result;
    }

    function beginVerification(){
        $postFields = array(
            'email'        => $this->email,
            'display_name' => $this->displayName,
            'flow_token'   => $this->flowToken,
        );

        $ch = curl_init('https://api.twitter.com/1.1/onboarding/begin_verification.json');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));

        $headers = array();
        $headers[] = 'X-Guest-Token: ' . $this->guestToken;
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        echo $result;
    }

    function sendActivationCode($code){
        $postFields = $this->getPostFieldsOfActivationCode($code);

        $this->curlTaskJson($postFields);
    }

    function setAccountPassword($password){
        $postFields = array (
            'flow_token' => $this->flowToken,
            'subtask_inputs' => 
            array (
                array (
                    'subtask_id' => 'EnterPassword',
                    'enter_password' => array (
                        'password' => $password,
                        'link' => 'next_link',
                    ),
                ),
            )
        );

        $this->curlTaskJson($postFields);
    }

}
