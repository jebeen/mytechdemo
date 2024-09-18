<?php

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
require_once APPPATH. '/vendor/autoload.php';

class JWTAuth {

    public function decodeJWTtoken($token=NULL) {
        $key = SECRET_KEY;
        $decodedtoken = JWT::decode($token, new Key($key, 'HS512'));
        return $decodedtoken;
    }
    
    public function getToken($userid) {
        $date   = new DateTimeImmutable();
        $expire_at     = $date->modify('+60 minutes')->getTimestamp(); 
        $domainName = "http://localhost:8080/CI/ecomsite";
        $key = SECRET_KEY;
        $request_data = [
        'iat'  => $date->getTimestamp(),        
        'iss'  => $domainName,                   
        'nbf'  => $date->getTimestamp(),        
        'exp'  => $expire_at,                    
        'userName' => $userid,                  
    ];
    $token = JWT::encode(
        $request_data,
        $key,
        'HS512'
    );
    return $token;
    }
    
}

?>