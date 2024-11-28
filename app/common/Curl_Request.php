<?php

namespace common;

class Curl_Request
{
    var string $db_url = 'http://admin:password@127.0.0.1:80/couchdb/';

    public function get(string $url, $data = null) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this -> db_url. $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data))
            ]);
        }
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
    public function put( string $dbName, $document = null)
    {

        $ch = curl_init($this -> db_url.'/'.$dbName);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Referer: localhost'
        ]);

        if($document){
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($document));
        }
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);

    }
    public function post( string $dbName, $query) {
        $ch = curl_init($this -> db_url.'/'.$dbName);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($query));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

}