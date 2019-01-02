<?php
//set_time_limit(10);


require('vendor/autoload.php');


class testPlayers extends PHPUnit\Framework\TestCase
{
    protected $client;

    protected function setUp()
    {

        $conf['timeout'] = 5;
        $conf['connect_timeout'] = 5;
        $conf['read_timeout'] = 5;
        $this->client = new GuzzleHttp\Client(   $conf );

        require_once("include/globais.php");

        $this->Globais = new raiz\Globais();
    }

/*
    public function testPUT_AlterandoMeuTime()
    {

        set_time_limit(10);
        $idjogador = 10;

        $time = "testAAA meu novo time ;) ALTERADO ".rand(500,8500);
        $idtime=410;

        $JSON = json_decode( " {\"time\":\"$time\",\"treino\":{\"Domingo\":\"Domingo\"},\"nivelcompeticao\":\"D2\",\"procurando\":{\"Doritos\":\"Doritos\"},\"localtreino\":\"Dublin\",\"foto\":{\"name\":\"\",\"type\":\"\",\"tmp_name\":\"\",\"error\":4,\"size\":0},\"idtime\":\"$idtime\"} " , true);
        if ($JSON == NULL ) die(" JSON erro de formacao");

        $trans = null;$trans = array(":idjogadorlogado" => $idjogador );
        //var_dump(strtr($this->Globais->MeusTimesRemoto, $trans));

        $response = $this->client->request('PUT', strtr($this->Globais->CriarMeuTimeSalvar, $trans)

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'timeout' => 10, // Response timeout
                'form_params' => $JSON,
                'connect_timeout' => 10 ,// Connection timeout,



            )
        );
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);


        $this->assertEquals('SUCESSO', $jsonRetorno["resultado"] );
    }

    public function testPOST_CriarMeutimeSalvar()
    {

        set_time_limit(10);
        $idjogador = 10;

        $time = "testAAA".rand(500,8500);

        $JSON = json_decode( " {\"time\":\"$time\",\"treino\":{\"Quarta\":\"Quarta\"},\"nivelcompeticao\":\"D2\",\"procurando\":{\"BackCenter\":\"BackCenter\"},\"localtreino\":\"dublin\",\"foto\":{\"name\":\"\",\"type\":\"\",\"tmp_name\":\"\",\"error\":4,\"size\":0}} " , true);
        if ($JSON == NULL ) die(" JSON erro de formacao");

        $trans = null;$trans = array(":idjogadorlogado" => $idjogador );
        //var_dump(strtr($this->Globais->MeusTimesRemoto, $trans));

        $response = $this->client->request('POST', strtr($this->Globais->CriarMeuTimeSalvar, $trans)

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'timeout' => 10, // Response timeout
                'form_params' => $JSON,
                'connect_timeout' => 10 // Connection timeout


            )
        );
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);


        $this->assertEquals('SUCESSO', $jsonRetorno["resultado"] );
    }

    public function testPOST_ProcurarTimes()
    {

        set_time_limit(10);
        $idjogador = 10;

        $JSON = json_decode( " {\"time\":\"\",\"treino\":null,\"localtreino\":\"\",\"nivelcompeticao\":\"\",\"procurando\":null,\"filtro\":1} " , true);

        $trans = null;$trans = array(":idjogadorlogado" => $idjogador );
        //var_dump(strtr($this->Globais->MeusTimesRemoto, $trans));

        $response = $this->client->request('POST', strtr($this->Globais->ProcurarTimes, $trans)

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'timeout' => 10, // Response timeout
                'form_params' => $JSON,
                'connect_timeout' => 10 // Connection timeout


            )
        );
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);

        //   var_dump($jsonRetorno);
        $this->assertEquals('SUCESSO', $jsonRetorno["resultado"] );
    }


    public function testPOST_JogadoresMeusTimes()
    {

        set_time_limit(10);
        $idjogador = 10;


        $JSON = json_decode( " {\"idtime\":\"226,227,221,216,230,231,232,233,234,235,236,237,238,239,240,241,242,243,244,245,246,247,248,249,250,251,252,253,254,255,256,257,258,259,260,261,262,263,264,265,266,267,268,269,270,271,272,273\"} " , true);

        $trans = null;$trans = array(":idjogadorlogado" => $idjogador );
        //var_dump(strtr($this->Globais->MeusTimesRemoto, $trans));

        $response = $this->client->request('POST', strtr($this->Globais->jogadores_por_times, $trans)

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'timeout' => 10, // Response timeout
                'form_params' => $JSON,
                'connect_timeout' => 10 // Connection timeout


            )
        );
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);

        //   var_dump($jsonRetorno);
        $this->assertEquals('SUCESSO', $jsonRetorno["resultado"] );
    }

    public function testGET_MeusTimes()
    {

        set_time_limit(10);
        $idjogador = 10;


        $JSON = json_decode( " {\"nome\":\"\",\"treino\":null,\"localtreino\":\"\",\"nivelcompeticao\":\"\",\"procurando\":null,\"filtro\":1} " , true);

        $trans = null;$trans = array(":idjogadorlogado" => $idjogador );
        //var_dump(strtr($this->Globais->MeusTimesRemoto, $trans));

        $response = $this->client->request('GET', strtr($this->Globais->MeusTimesRemoto, $trans)

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'timeout' => 10, // Response timeout
                'form_params' => $JSON,
                'connect_timeout' => 10 // Connection timeout


            )
        );
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);

        //   var_dump($jsonRetorno);
        $this->assertEquals('SUCESSO', $jsonRetorno["resultado"] );
    }

    public function testPOST_SearchPlayers()
    {
        //TODO: dando timeout 10 seconds

        set_time_limit(30);
        $idjogador = 10;
        $idexperiencia= 117;

        $JSON = json_decode( " {\"nome\":\"\",\"treino\":null,\"localtreino\":\"\",\"nivelcompeticao\":\"\",\"procurando\":null,\"filtro\":1} " , true);

        $trans = null;$trans = array(":idjogadorlogado" => $idjogador,":idexperiencia" => $idexperiencia );
        //   echo strtr($this->Globais->listar_times_de_um_jogador, $trans);exit;


        //  var_dump(strtr($this->Globais->editar_experiencia, $trans));

        $response = $this->client->request('POST', strtr($this->Globais->ProcurarJogadores, $trans)

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'timeout' => 30, // Response timeout
                'form_params' => $JSON,
                'connect_timeout' => 30 // Connection timeout


            )
        );
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);

        //   var_dump($jsonRetorno);
        $this->assertEquals('SUCESSO', $jsonRetorno["resultado"] );
    }
    public function testPUT_NovaExperiences()
    {

        set_time_limit(5);
        $idjogador = 10;
        $idexperiencia= 117;

        $JSON = json_decode( "  {\"time\":\"Legiao Carioca\",\"inicio\":\"01\/1998\",\"fim\":\"\",\"idtime\":\"216\",\"resultados\":null,\"idjogadorlogado\":$idjogador,\"rank\":{\"-2\":\"3\" },\"posicao\":{\"-2\":\"Snake Corner\" },\"idevento\":{\"-2\":\"9\" },\"division\":null }" , true);

        $trans = null;$trans = array(":idjogadorlogado" => $idjogador,":idexperiencia" => $idexperiencia );
        //   echo strtr($this->Globais->listar_times_de_um_jogador, $trans);exit;


        //  var_dump(strtr($this->Globais->editar_experiencia, $trans));

        $response = $this->client->request('PUT', strtr($this->Globais->editar_experiencia, $trans)

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'timeout' => 10, // Response timeout
                'form_params' => $JSON,
                'connect_timeout' => 10 // Connection timeout


            )
        );
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);

        //   var_dump($jsonRetorno);
        $this->assertEquals('SUCESSO', $jsonRetorno["resultado"] );
    }
    public function testPUT_EditarExperiences()
    {

        set_time_limit(5);
        $idjogador = 10;
        $idexperiencia= 117;

        $JSON = json_decode( "  {\"time\":\"Legiao Carioca\",\"inicio\":\"01\/1998\",\"fim\":\"\",\"idtime\":\"216\",\"resultados\":null,\"idjogadorlogado\":$idjogador,\"rank\":{\"74\":\"3\" },\"posicao\":{\"74\":\"Snake Corner\" },\"idevento\":{\"74\":\"9\" },\"division\":null }" , true);

        $trans = null;$trans = array(":idjogadorlogado" => $idjogador,":idexperiencia" => $idexperiencia );
        //   echo strtr($this->Globais->listar_times_de_um_jogador, $trans);exit;


      //  var_dump(strtr($this->Globais->editar_experiencia, $trans));

        $response = $this->client->request('PUT', strtr($this->Globais->editar_experiencia, $trans)

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'timeout' => 10, // Response timeout
                'form_params' => $JSON,
                'connect_timeout' => 10 // Connection timeout


            )
        );
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);

        //   var_dump($jsonRetorno);
        $this->assertEquals('SUCESSO', $jsonRetorno["resultado"] );
    }
    public function testDELETE_Experiences()
    {

        set_time_limit(5);
        $idjogador = 10;
        $idexperiencia= 12;

        //var_dump($JSON);

        $trans = null;$trans = array(":idjogadorlogado" => $idjogador,":idexperiencia" => $idexperiencia );
        //   echo strtr($this->Globais->listar_times_de_um_jogador, $trans);exit;
        $response = $this->client->request('DELETE', strtr($this->Globais->delete_experiencia, $trans)

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10 // Connection timeout


            )
        );
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);

        //   var_dump($jsonRetorno);
        $this->assertEquals('SUCESSO', $jsonRetorno["resultado"] );
    }
    public function testGET_Listarexperiences()
    {

        set_time_limit(10);
        $idjogador = 10;

        //var_dump($JSON);

        $trans = null;$trans = array(":idjogadorlogado" => $idjogador);
     //   echo strtr($this->Globais->listar_times_de_um_jogador, $trans);exit;
        $response = $this->client->request('GET', strtr($this->Globais->listar_times_de_um_jogador, $trans)

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10 // Connection timeout


            )
        );
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);

        //   var_dump($jsonRetorno);
        $this->assertEquals('SUCESSO', $jsonRetorno["resultado"] );
    }

    public function testPOST_associar_criando_timeaocurriculo()
    {

        $idjogador = 10;
        $novotime="test Novo Time".rand(1000,3000);
        $idtime="";
        $JSON = json_decode( " {\"time\":\"$novotime\",\"inicio\":\"02\/1998\",\"idtime\":\"$idtime\",\"posicao\":[\"Snake Corner\"],\"rank\":[\"3\"],\"idevento\":[\"9\"],\"division\":[\"Division 1\"],\"fim\":\"\",\"resultados\":null,\"idjogadorlogado\":$idjogador} " , true);

        //var_dump($JSON);

        $trans = null;$trans = array(":idjogadorlogado" => $idjogador);
        $response = $this->client->request('POST', strtr($this->Globais->Players_ADD_TEAM_endpoint, $trans)

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'form_params' => $JSON,
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10 // Connection timeout


            )
        );
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);

        //   var_dump($jsonRetorno);
        $this->assertEquals('SUCESSO', $jsonRetorno["resultado"] );
    }
    public function testPOST_associartimeaocurriculo()
    {

        $idjogador = 10;
        $novotime="test Novo Time".rand(1000,3000);
        $idtime="216";
        $JSON = json_decode( " {\"time\":\"$novotime\",\"inicio\":\"02\/1998\",\"idtime\":\"$idtime\",\"posicao\":[\"Snake Corner\"],\"rank\":[\"3\"],\"idevento\":[\"9\"],\"division\":[\"Division 1\"],\"fim\":\"\",\"resultados\":null,\"idjogadorlogado\":$idjogador} " , true);

        //var_dump($JSON);

        $trans = null;$trans = array(":idjogadorlogado" => $idjogador);
        $response = $this->client->request('POST', strtr($this->Globais->Players_ADD_TEAM_endpoint, $trans)

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'form_params' => $JSON,
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10 // Connection timeout


            )
        );
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);

     //   var_dump($jsonRetorno);
        $this->assertEquals('SUCESSO', $jsonRetorno["resultado"] );
    }

    public function testGet_Players_UPDATE_endpoint()
    {

        $idjogador = 10;
        $JSON = json_decode( " {\"nome\":\"Bruno Siqueira\",\"playsince\":\"2001\" ,\"foto\":{\"name\":\"\",\"type\":\"\",\"tmp_name\":\"\",\"error\":4,\"size\":0},\"idade\":\"32\",\"cidade\":\"Dublin2\",\"Snake\":\" - \",\"SnakeCorner\":\" - \",\"BackCenter\":\" - \",\"Doritos\":\" - \",\"DoritosCorner\":\" - \",\"Coach\":\" > 5\",\"treino\":{\"Domingo\":\"Domingo\",\"Segunda\":\"Segunda\",\"Terca\":\"Terca\",\"Quarta\":\"Quarta\",\"Quinta\":\"Quinta\",\"Sexta\":\"Sexta\",\"Sabado\":\"Sabado\"},\"nivelcompeticao\":\"D1\",\"procurando\":{\"Snake\":\"Snake\",\"SnakeCorner\":\"SnakeCorner\",\"BackCenter\":\"BackCenter\",\"Coach\":\"Coach\",\"DoritosCorner\":\"DoritosCorner\",\"Doritos\":\"Doritos\"} }" , true);
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
        set_time_limit(30);
        $idjogador = 10;
        $trans = null;
        $trans = array(":idjogadorlogado" => $idjogador);
        $response = $this->client->request('GET', strtr($this->Globais->Players_GET_endpoint, $trans)

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10 // Connection timeout

            )
        );
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);


        //var_dump(  $jsonRetorno );
        $this->assertEquals('Bruno Siqueira', $jsonRetorno["JOGADORES"][$idjogador]["nome"]);

    }
*/

    public function testGet_HealthCheck()
    {

        $response = $this->client->request('GET', $this->Globais->healthcheck

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10 // Connection timeout

            )
        );
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);

        //var_dump(  $jsonRetorno );
        $this->assertEquals('SUCESSO', $jsonRetorno["resultado"]);

    }

}
