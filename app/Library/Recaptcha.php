<?php
namespace App\Library;

class Recaptcha {

    public static function validate($token) {
    	$url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => config('settings.recaptcha_key_secret'),
            'response' => $token,
        ];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        $response = curl_exec($curl);
        $response = json_decode($response, true);
        if ($response['success'] === false || $response['score'] <= 0.5) {
            return false;
        }
        return true;
    }



    //Validar con file_get_contenst
    public static function _validateFC($token) {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => '_SECRET_KEY_',
            'response' => $token,
        ];
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ],
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $resultJson = json_decode($result);
        if ($resultJson != true) {
            return false;
        }
        return true;
    }



}