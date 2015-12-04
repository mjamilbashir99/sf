<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
function categoriesList()
{
	
}
function shopello($method, $parameters = array() ,$offset='0', $limit='0')
{
		
		$api_key='OHIjAmuZqyLMPK5urPj1RiXT5YcMMjbrRKAhXV7d';
		$country='se';
		if($limit > 0)
			$url = 'https://' . $country. '.shopelloapi.com/1/' . $method . '.json?limit='.$limit.'&offset='.$offset;
		else
			$url = 'https://' . $country. '.shopelloapi.com/1/' . $method . '.json?offset='.$offset;
		
        if (!empty($parameters)) {
            foreach ($parameters as $key => $val) {
                if (empty($val)) {
                    unset($parameters[$key]);
                }
            }

            $url .= '&' . http_build_query($parameters);
        }
echo   $url;
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_NOBODY, false);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array('X-API-KEY: ' . $api_key)
        );

        $result = curl_exec($curl);
        $error = curl_error($curl);

        if (!empty($error)) {
            throw new Exception($result->error . ' (HTTP CODE ' . curl_getinfo($curl, CURLINFO_HTTP_CODE) . ')');
        }

        $result = json_decode($result);

        if (isset($result->error)) {
            throw new Exception($result->error);
        }

        return $result;
    }
	
function getByCatId($method, $parameters = array() ,$offset='0', $limit='0' ,$catId='0')
{
		
		$api_key='OHIjAmuZqyLMPK5urPj1RiXT5YcMMjbrRKAhXV7d';
		$country='se';
		if($limit > 0)
			{
//			  $url = 'https://' . $country. '.shopelloapi.com/1/' . $method . '.json?limit='.$limit.'&offset='.$offset.'&category_id='.$catId;
			  $url = 'https://' . $country. '.shopelloapi.com/1/' . $method . '.json';
			}
		else
		   $url = 'https://' . $country. '.shopelloapi.com/1/' . $method . '.json?offset='.$offset;
		
        if (!empty($parameters)) {
            foreach ($parameters as $key => $val) {
                if (empty($val)) {
                    unset($parameters[$key]);
                }
            }

            $url .= '?' . http_build_query($parameters);
        }
	//echo	 $url;
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_NOBODY, false);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array('X-API-KEY: ' . $api_key)
        );

        $result = curl_exec($curl);
        $error = curl_error($curl);

        if (!empty($error)) {
            throw new Exception($result->error . ' (HTTP CODE ' . curl_getinfo($curl, CURLINFO_HTTP_CODE) . ')');
        }

        $result = json_decode($result);

        if (isset($result->error)) {
            throw new Exception($result->error);
        }

        return $result;
    }