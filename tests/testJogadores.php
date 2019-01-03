<?php
require('vendor/autoload.php');

class testPlayers extends PHPUnit\Framework\TestCase
{
    protected $client;
    public $idexperience    ;

    public function OpenConf(){

      $configuracoes_externas = file_get_contents('include/config.json');
      $config_parsed = json_decode($configuracoes_externas,true);
      return $config_parsed;
    }

      public function SaveConf($conf){

           $fp = fopen('include/config.json', "w");
           if (fwrite($fp, json_encode($conf,true)))
                $sucesso = 1;
           else
                $sucesso = 0;
           fclose($fp);

          return $sucesso;
      }
    protected function setUp()
    {

        $conf['timeout'] = 5;
        $conf['connect_timeout'] = 5;
        $conf['read_timeout'] = 5;
        $this->client = new GuzzleHttp\Client(   $conf );

        require_once("include/globais.php");

        $this->Globais = new raiz\Globais();
    }

    public function testGet_HealthCheck()
    {
//echo $this->Globais->healthcheck;
        $response = $this->client->request('GET', $this->Globais->healthcheck

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10 // Connection timeout

            )
        );
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);

    //    var_dump(  $jsonRetorno );
        $this->assertEquals('UP', $jsonRetorno["status"]);

    }

    public function testGet_Players_GET_endpoint()
    {

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
        //var_dump($jsonRetorno);

        $this->assertEquals('SUCESSO', $jsonRetorno["resultado"] );
    }

    public function testPOST_associartimeaocurriculo()
    {

        $idjogador = 10;
        $novotime="test Novo Time".rand(1000,3000);
        $idtime="216";
        $JSON = json_decode( " {\"time\":\"$novotime\",\"inicio\":\"02\/1998\",\"idtime\":\"$idtime\",\"posicao\":[\"Snake Corner\"],\"rank\":[\"3\"],\"idevento\":[\"9\"],\"division\":[\"Division 1\"],\"fim\":\"\",\"resultados\":null,\"idjogadorlogado\":$idjogador} " , true);

      //  var_dump($JSON);

        $trans = null;$trans = array(":idjogadorlogado" => $idjogador);
      //  var_dump(strtr($this->Globais->Players_ADD_TEAM_Experience, $trans));

        $response = $this->client->request('POST', strtr($this->Globais->Players_ADD_TEAM_Experience, $trans)

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'form_params' => $JSON,
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10 // Connection timeout


            )
        );

        //echo $response->getBody()->getContents();
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);
        //var_dump( ($this->Globais->Players_ADD_TEAM_endpoint )  );exit;
        //var_dump(strtr($this->Globais->Players_ADD_TEAM_endpoint, $trans)  );exit;
        //var_dump($jsonRetorno);//exit;

        $Conf = $this->OpenConf();
        $Conf["idexperience"] = $jsonRetorno["idexperience"];
        $Conf["idresultado"] = $jsonRetorno["idresultado"][0];

        if ($this->SaveConf($Conf) == 0){
          echo " Nao foipossivel salvar o arqvuio de conf";
          exit;
        }

      //  var_dump($this->idexperience);
        $this->assertEquals('SUCESSO', $jsonRetorno["resultado"] );
    }


    public function testPUT_EditarExperiences()
    {


        $idjogador = 10;
        $Conf = $this->OpenConf();

        $JSON = json_decode( "  {\"time\":\"Legiao Carioca\",\"inicio\":\"05/2015\",\"fim\":\"\",\"idtime\":\"216\",\"resultados\":null,\"idjogadorlogado\":$idjogador,\"rank\":{\"".$Conf["idresultado"]."\":\"1\" },\"posicao\":{\"".$Conf["idresultado"]."\":\"Snake\" },\"idevento\":{\"".$Conf["idresultado"]."\":\"5c17c0f5fe4f8200730bb476\" },\"division\":null }" , true);

        $trans = null;$trans = array(":idjogadorlogado" => $idjogador,":idexperiencia" =>   $Conf["idexperience"]  );
        //     echo strtr($this->Globais->editar_experiencia, $trans);exit;

        //var_dump($JSON);
        //var_dump(strtr($this->Globais->editar_experiencia, $trans));

        $response = $this->client->request('PUT', strtr($this->Globais->editar_experiencia, $trans)

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'timeout' => 10, // Response timeout
                'form_params' => $JSON,
                'connect_timeout' => 10 // Connection timeout


            )
        );
        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);
        $jsonRetorno["idexperience"] = $Conf["idexperience"] ;
        //var_dump($jsonRetorno);
        $this->assertEquals('SUCESSO', $jsonRetorno["resultado"] );
    }

    public function testPOST_JogadoresMeusTimes()
    {

        set_time_limit(10);
        $idjogador = 10;


        $JSON = json_decode( " {\"idtimes[]\":\"226\",\"idtimes[]\":\"227\",\"idtimes[]\":\"221\",\"idtimes[]\":\"216\" } " , true);

        $trans = null;$trans = array(":idjogadorlogado" => $idjogador );
        //var_dump(strtr($this->Globais->jogadores_por_times, $trans));

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




    public function testPOST_SearchPlayers()
    {


      //  set_time_limit(30);
        $idjogador = 10;
        $idexperiencia= 117;

        $JSON = json_decode( " {\"nome\":\"a\",\"treino\":null,\"localtreino\":\"\",\"nivelcompeticao\":\"\",\"procurando\":null,\"filtro\":1} " , true);

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

    public function testDELETE_Experiences()
    {

      $Conf = $this->OpenConf();

        $idjogador = 10;
      //  $idexperiencia= 12;

        //var_dump($JSON);

        $trans = null;$trans = array(":idjogadorlogado" => $idjogador,":idexperiencia" => $Conf["idexperience"] );
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
        $JSON = json_decode( " {\"time\":\"$novotime\",\"inicio\":\"02\/1998\",\"idtime\":\"$idtime\",\"posicao[0]\":\"Snake Corner\",\"rank[0]\":\"3\",\"idevento[0]\":\"9\",\"division[0]\":\"Division 1\",\"fim\":\"\",\"resultados\":null } " , true);

//        var_dump($JSON);

        $trans = null;$trans = array(":idjogadorlogado" => $idjogador);

      //  var_dump(strtr($this->Globais->Players_ADD_TEAM_Experience, $trans));
        $response = $this->client->request('POST', strtr($this->Globais->Players_ADD_TEAM_Experience, $trans)

            , array(
                'headers' => array('Content-type' => 'application/x-www-form-urlencoded'),
                'form_params' => $JSON,
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10 // Connection timeout


            )
        );
        //var_dump(strtr($this->Globais->Players_ADD_TEAM_endpoint, $trans));

        $jsonRetorno = json_decode($response->getBody()->getContents(), 1);

       // var_dump($jsonRetorno);
        $this->assertEquals('SUCESSO', $jsonRetorno["resultado"] );
    }


}
