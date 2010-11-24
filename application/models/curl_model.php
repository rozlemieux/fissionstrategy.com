<?php

class curl_model extends Model {

        function post($fields, $url) {

                //Initialize CURL connection
                $ch = curl_init();

                //Set basic connection parameters:
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 100);

                //Set Parameters to maintain cookies across sessions
                curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
                curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies_file');
                curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies_file');

                //Execute query 
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

                $response = curl_exec($ch);

                //Close the connection
                curl_close($ch);

                return $response;
        }

}
