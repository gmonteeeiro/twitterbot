<?php
class CreateAccount{
    function getGuestToken(){
        $ch = curl_init('https://twitter.com/i/flow/signup');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        
        $gt = explode('("gt=', $result)[1];
        $gt = explode(';', $gt)[0];

        return $gt;
    }

    function getFlowToken(){
        $ch = curl_init('https://api.twitter.com/1.1/onboarding/task.json?flow_name=signup');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"input_flow_data":{"flow_context":{"debug_overrides":{},"start_location":{"location":"manual_link"}}},"subtask_versions":{"contacts_live_sync_permission_prompt":0,"email_verification":1,"topics_selector":1,"wait_spinner":1}}');

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'X-Guest-Token: ' . $this->getGuestToken();
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
}