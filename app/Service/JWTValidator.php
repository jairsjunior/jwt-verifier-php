<?php

namespace App\Service;

use Exception;
use Firebase\JWT\JWK;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Log;

class JWTValidator
{

    public static function getPublicKey(): ?array
    {
        //Get jwks from URL, because the jwks may change.
        $ch = curl_init(env('JWKS_URL'));
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 3,
        ]); 
        $jwks = curl_exec($ch);
        if ($jwks) {
            return json_decode($jwks, true);
        }
        return null;
    }

    public static function checkClaim($claimValue, string $valueExpected): ?bool
    {
        if(is_null($claimValue) || is_null($valueExpected)){
            return false;
        }
        if(is_array($claimValue) && in_array($valueExpected, $claimValue)){
            return true;
        }
        if($claimValue == $valueExpected){
            return true;
        }
        return false;
    }
    
    public static function verifyToken(string $jwt): ?object
    {
        try{
            $jwks = static::getPublicKey();   
            if ($jwks) {
                $payload = JWT::decode($jwt, JWK::parseKeySet($jwks), array('RS256'));
                if(!static::checkClaim($payload->aud, env('JWT_AUDIENCE'))){
                    Log::info("Wrong audience at token");
                    return null;
                }
                if(!static::checkClaim($payload->iss, env('JWT_ISSUER'))){
                    Log::info("Wrong Issuer at token");
                    return null;
                }
                if (isset($payload->aud) )
                return $payload;
            }
        }catch(Exception $e){
            Log::debug("Failed to validate the token: " . $e->getMessage());
            throw new Exception($e->getMessage());
        }
        return null;
    }
}