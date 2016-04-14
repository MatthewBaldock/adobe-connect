<?php
namespace App\Http\Controllers\AdobeConnect;

/**
 * Provides a method to execute a curl call and turn the xml response to an \SimpleXMLElement object.
 *
 * @author Gustavo Burgi <gustavoburgi@gmail.com>
 */
class CurlCall
{
    /**
     * @param string $url URL of the API endpoint
     *
     * @return  \SimpleXMLElement
     *
     * @throws \Exception   if the endpoint return an empty result
     */
    public static function call($url)
    {
        $specialCookie = array();
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 25);
        curl_setopt($ch, CURLOPT_TIMEOUT, 45);
        curl_setopt($ch, CURLOPT_HEADER, true);

        $result = curl_exec($ch);
        if (! $result) {
            throw new \Exception(sprintf('The endpoint "%s" is not returning anything.', $url));
        }
        $explode = explode("\r\n\r\n",$result);
        preg_match('/BREEZESESSION\=([a-zA-Z0-9]+)/',$explode[0],$specialCookie); 
        $xml = simplexml_load_string($explode[1]);
        $xml->addChild('specialcookie',$specialCookie[1]);
        return $xml;
    }
} 