<?php

    function push_notification_android($device_id, $title, $message)
    {
        //API URL of FCM
        $url = 'https://fcm.googleapis.com/fcm/send';
    
        /*api_key available in:
        Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    
        $api_key = 'AAAA3-OGvig:APA91bFMh7ZJSKGPqlle1M5FSHHoL9wJ_2q0djMRDqlcauU1Y3NupVEWEzCw3D-gYBLdxYEZuvqEHCJ7XCdhD8AuGYgEgKJ8rg20YVsXh9CVUVuHGr7nLH4Q88O7aZ8sZZzXA870Gdz-'; //Replace with yours
        
        $target = $device_id;
        
        $fields = array();
        $fields['priority'] = "high";
        $fields['notification'] = [ "title" => $title, 
                        "body" => $message, 
                        "icon" => "https://truckwale.co.in/assets/img/truck-logo-sm.png",
                        'data' => ['message' => $message],
                        "sound" => "default"];
        if (is_array($target)){
            $fields['registration_ids'] = $target;
        } else{
            $fields['to'] = $target;
        }
    
        //header includes Content type and api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$api_key
        );
                    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

?>