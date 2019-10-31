<?php namespace App\Helpers;

class Curl
{

    public static function post($url , $params = [] , $headers = [] , $auth = [] , $json = false, $execute = true)
    {   

      //   $ch = curl_init('https://www.howsmyssl.com/a/check');
      // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      // $data = curl_exec($ch);
      // curl_close($ch);

      // $json = json_decode($data);
      // dd($json->tls_version);

        $req = curl_init($url);
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($req, CURLOPT_VERBOSE , true);
        curl_setopt($req, CURLOPT_POST, true );
        
        if($json)
            curl_setopt($req, CURLOPT_POSTFIELDS, json_encode($params));
        else
            curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt($req, CURLOPT_SSL_VERIFYHOST, 2 );
        // curl_setopt($req, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2 );
        curl_setopt($req, CURLINFO_HEADER_OUT, true);
        curl_setopt($req, CURLOPT_HTTPHEADER, $headers);
        
        if(!empty($auth))
            curl_setopt($req, CURLOPT_USERPWD, $auth['username'] . ':' . $auth['password']); 

        if(empty($execute)){
          return $req;
        } 
        $resp = curl_exec($req);
        $respCode = curl_getinfo($req);
        curl_close($req);
        return $resp;
    }

    public static function get($url , $params = [] , $headers = [] , $auth = [])
    {
        $req = curl_init($url);
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($req, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt($req, CURLOPT_SSL_VERIFYHOST, 2 );
        curl_setopt($req, CURLINFO_HEADER_OUT, true);
        curl_setopt($req, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($req, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        if(!empty($auth))
            curl_setopt($req, CURLOPT_USERPWD, $auth['username'] . ':' . $auth['password']);
        $resp = curl_exec($req);
        $respCode = curl_getinfo($req);
        return json_decode($resp , true);
    }
}