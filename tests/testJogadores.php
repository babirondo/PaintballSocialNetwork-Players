<?php

require('vendor/autoload.php');

class testPlayers extends PHPUnit\Framework\TestCase
{
    protected $client;

    protected function setUp()
    {
        $this->client = new GuzzleHttp\Client();

        require_once("include/globais.php");

        $this->Globais = new raiz\Globais();

    }

    public function testGet_Players_UPDATE_endpoint()
    {

        $JSON = json_decode( " {\"nome\":\"Bruno Siqueira\",\"playsince\":\"2001\" ,\"foto\":{\"name\":\"\",\"type\":\"\",\"tmp_name\":\"\",\"error\":4,\"size\":0},\"idade\":\"32\",\"cidade\":\"Dublin2\",\"Snake\":\" - \",\"SnakeCorner\":\" - \",\"BackCenter\":\" - \",\"Doritos\":\" - \",\"DoritosCorner\":\" - \",\"Coach\":\" > 5\",\"treino\":{\"Domingo\":\"Domingo\",\"Segunda\":\"Segunda\",\"Terca\":\"Terca\",\"Quarta\":\"Quarta\",\"Quinta\":\"Quinta\",\"Sexta\":\"Sexta\",\"Sabado\":\"Sabado\"},\"nivelcompeticao\":\"D1\",\"procurando\":{\"Snake\":\"Snake\",\"SnakeCorner\":\"SnakeCorner\",\"BackCenter\":\"BackCenter\",\"Coach\":\"Coach\",\"DoritosCorner\":\"DoritosCorner\",\"Doritos\":\"Doritos\"} }" , true);


        $idjogador = 10;
        $trans = null;$trans = array(":idjogadorlogado" => $idjogador);
        $response = $this->client->request('PUT', strtr($this->Globais->Players_UPDATE_endpoint, $trans)

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'form_params' => $JSON,
                'timeout' => 10,

            )
        );
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);

        $this->assertEquals('SUCESSO', $jsonRetorno["resultado"] );
    }

    public function testGet_Players_GET_endpoint()
    {

        $idjogador = 10;
        $trans = null;
        $trans = array(":idjogadorlogado" => $idjogador);
        $response = $this->client->request('GET', strtr($this->Globais->Players_GET_endpoint, $trans)

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),

                'timeout' => 10,

            )
        );
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);

        //var_dump(  $jsonRetorno );
        $this->assertEquals('Bruno Siqueira', $jsonRetorno["JOGADORES"][$idjogador]["nome"]);

    }


    public function testGet_HealthCheck()
    {

        $response = $this->client->request('GET', $this->Globais->healthcheck

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),

                'timeout' => 10,

            )
        );
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);

        //var_dump(  $jsonRetorno );
        $this->assertEquals('SUCESSO', $jsonRetorno["resultado"]);

    }

}