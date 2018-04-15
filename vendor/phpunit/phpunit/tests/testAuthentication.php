<?php

require('../../../../vendor/autoload.php');

class AuthenticationTest extends PHPUnit\Framework\TestCase
{
    protected $client;

    protected function setUp()
    {
        $this->client = new GuzzleHttp\Client( );
    }

    public function testPost_invalidAuthentication()
    {

        $response = $this->client -> request('POST', 'http://localhost:81/PaintballSocialNetwork-AuthAPI/Auth'

                             ,array(
                                 'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                                 'form_params' => array(
                                     'login' => 'bruno',
                                     'senha' => 'brsuno'
                                 ),
                                 'timeout' => 10
                             )
        );

        $this -> assertEquals( 204, $response->getStatusCode() );

    }



    public function testPost_validAuthentication()
    {

        $response = $this->client -> request('POST', 'http://localhost:81/PaintballSocialNetwork-AuthAPI/Auth'

            ,array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'form_params' => array(
                    'login' => 'bruno',
                    'senha' => 'bruno'
                ),
                'timeout' => 10
            )
        );

        $this -> assertEquals( 200, $response->getStatusCode() );

    }
}