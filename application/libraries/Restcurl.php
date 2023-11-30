<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Library curl
 * @author fahmifaqih1257@gmail.com
 */

class Restcurl
{
    public function post($url, $data, $header = [])
    {
        array_push($header, "cache-control: no-cache");

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_CONNECTTIMEOUT => 60,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $header
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        curl_close($curl);

        // Mendapatkan nilai X-Credentials-Username dari header
        $lines = explode("\r\n", $header);
        $xCredentialsUsername = null;

        foreach ($lines as $line) {
            if (strpos($line, 'X-Credentials-Username') !== false) {
                list($headerName, $headerValue) = explode(':', $line, 2);
                $xCredentialsUsername = trim($headerValue);
                break;
            }
        }

        // Tampilkan nilai X-Credentials-Username
        $username = explode(" ", $xCredentialsUsername)[0];

        if ($response === false) {
            $result = [
                "error" => 1,
                "message" => $err,
                "username" => null
            ];
        } else {
            $body = json_decode($body);
            $result = [
                "error" => $body->error,
                "username" => $username
            ];
            $body->error == 0 ? $result['data'] = $body->data : $result['ket'] = $body->ket;
        }
        return json_encode((object)$result);
    }
}
