<?php
namespace raiz;


class class_API
{

    function __construct( ){


    }

    function CallAPI($method, $url, $data = false)
    {
        GLOBAL $verbose;
        //$verbose=1;
        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data){
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                }
                if ($verbose) echo " <BR><FONT COLOR='red'> curl -H 'Content-Type: application/json' -X $method -d '$data' $url </FONT>";

            break;
            case "PUT":
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS,http_build_query(json_decode($data)));

                if ($verbose) echo " <BR><FONT COLOR='green'> curl -H 'Content-Type: application/json' -X $method -d '$data' $url </FONT> ";
                break;
            default:
                if ($verbose) echo " <BR> <FONT COLOR='#9acd32'>   $url </FONT> ";
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        // Optional Authentication:
        //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //curl_setopt($curl, CURLOPT_USERPWD, "username:password");
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);


        //$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);


       // echo "<PRE>"; var_dump($result);
        /*

        if($httpCode == 404) {
            return  false;
        }
        */

        curl_close($curl);
        return  json_decode( $result , true);
    }

}
