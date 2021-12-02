<?php 
require_once('constants.php');
class API_Call{
    protected $apiroot = ALT_ROOT.'api/';

    function api_get($page, $data){
        $curl = curl_init();
        $url = $this->apiroot.$page.'?'.http_build_query($data);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}