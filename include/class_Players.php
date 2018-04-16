<?php
namespace raiz;
set_time_limit( 2 );
class Players{
    function __construct( ){
        require("include/class_db.php");
        $this->con = new db();
        $this->con->conecta();
    }




    function getJogadorTimes(  $request, $response, $args ){

        if (!$this->con->conectado){
            $data =   array(	"resultado" =>  "ERRO",
                "erro" => "nao conectado - ".$this->con->erro );
            return $response->withStatus(500)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }


        $sql = "SELECT * FROM jogador_times  WHERE id_jogador = '".$args['idusuario']."'  ";
        $this->con->executa($sql);

        if ( $this->con->nrw > 0 ){
            $contador = 0;

            $data =   array(	"resultado" =>  "SUCESSO" );

            while ($this->con->navega(0)){
                $contador++;
                $data["TIMES"][$contador]["idtime"] = $this->con->dados["id_time"];
                $data["TIMES"][$contador]["inicio"] = $this->con->dados["inicio"];
                $data["TIMES"][$contador]["fim"] = $this->con->dados["fim"];
            }

            return $response->withJson($data, 200)->withHeader('Content-Type', 'application/json');
        }
        else {

            // nao encontrado
            $data =    array(	"resultado" =>  "ERRO",
                "erro" => "Nao foi possivel encontrar dados deste jogador ");

            return $response->withStatus(200)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);



        }

    }



    function getJogador(  $request, $response, $args ){

        if (!$this->con->conectado){
            $data =   array(	"resultado" =>  "ERRO",
                "erro" => "nao conectado - ".$this->con->erro );
            return $response->withStatus(500)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }


        $sql = "SELECT * FROM jogadores  WHERE id_jogador = '".$args['idusuario']."'  ";

        $this->con->executa($sql);

        if ( $this->con->nrw == 1 ){
            $this->con->navega(0);

            $data =   array(	"resultado" =>  "SUCESSO" );
            $data["nome"] = $this->con->dados["nome"];
            $data["idade"] = $this->con->dados["idade"];
            $data["cidade"] = $this->con->dados["cidade"];
            $data["snake"] = $this->con->dados["snake"];
            $data["snakecorner"] = $this->con->dados["snakecorner"];
            $data["backcenter"] = $this->con->dados["backcenter"];
            $data["doritos"] = $this->con->dados["doritos"];
            $data["doritoscorner"] = $this->con->dados["doritoscorner"];

            return $response->withJson($data, 200)->withHeader('Content-Type', 'application/json');
        }
        else {

            // nao encontrado
            $data =    array(	"resultado" =>  "ERRO",
                "erro" => "Nao foi possivel encontrar dados deste jogador ");

            return $response->withStatus(200)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);



        }

    }

    function Atualizar_Jogador(  $request, $response, $args,   $jsonRAW){

        if (!$this->con->conectado){
            $data =   array(	"resultado" =>  "ERRO",
                    "erro" => "nao conectado - ".$this->con->erro );
            return $response->withStatus(500)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }

        IF (!is_array ($jsonRAW)  ) {
            $data =  array(	"resultado" =>  "ERRO",
                    "erro" => "JSON zuado - ".var_export($jsonRAW, true) );

            return $response->withStatus(500)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);


        }
//var_dump($jsonRAW);
        //INICIO DA ROTINA DE ALTERACAO DE USUARIO
        $sql = "UPDATE jogadores SET
                      nome = '".$jsonRAW["nome"]."',         
                        idade = '".$jsonRAW["idade"]."', 
                        cidade = '".$jsonRAW["cidade"]."', 
                        snake = '".$jsonRAW["Snake"]."', 
                        snakecorner = '".$jsonRAW["SnakeCorner"]."', 
                        backcenter = '".$jsonRAW["BackCenter"]."', 
                        doritos = '".$jsonRAW["Doritos"]."', 
                        doritoscorner = '".$jsonRAW["DoritosCorner"]."'
                      
                WHERE  id_jogador = '".$args['idusuario']."'  ";

        $this->con->executa($sql);

        if ( $this->con->res == 1 ){


            $data =   array(	"resultado" =>  "SUCESSO" );
            return $response->withJson($data, 200)->withHeader('Content-Type', 'application/json');
        }
        else {

            // nao encontrado
            $data =    array(	"resultado" =>  "ERRO",
                    "erro" => "Nao foi possivel alterar os dados");

            return $response->withStatus(200)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);



        }

    }


    function Adicionar_time_ao_jogador(  $request, $response, $args,   $jsonRAW){

        if (!$this->con->conectado){
            $data =   array(	"resultado" =>  "ERRO",
                "erro" => "nao conectado - ".$this->con->erro );
            return $response->withStatus(500)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }

        IF (!is_array ($jsonRAW)  ) {
            $data =  array(	"resultado" =>  "ERRO",
                "erro" => "JSON zuado - ".var_export($jsonRAW, true) );

            return $response->withStatus(500)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }

        //TODO: criticar fim nulo e trim
        //TODO: criticar tipo data no campo inicio, formato BR ou gringo

                require_once("include/class_api.php");
                require_once("include/globais.php");

                $API = new class_API();
                $Globais = new Globais();


                $array_post = null;
                $array_post['time'] = $jsonRAW["time"];


                $query_API = $API->CallAPI("POST", $Globais->adicionar_time , json_encode($array_post));

                if ($query_API){
                    if ($query_API["resultado"] == "SUCESSO") {
                        $mensagem_retorno =  "Dados Salvos com sucesso";



                        $idtime = $query_API["idtime"];
                    }
                    else{

                            $data =  array(	"resultado" =>  "ERRO",
                                "erro" => "Nao foi possivel criar o time"  );

                            return $response->withStatus(500)
                                ->withHeader('Content-type', 'application/json;charset=utf-8')
                                ->withJson($data);

                    }

                }
                else{
                    $data =  array(	"resultado" =>  "ERRO",
                        "erro" => "Nao foi possivel criar o time"  );

                    return $response->withStatus(500)
                        ->withHeader('Content-type', 'application/json;charset=utf-8')
                        ->withJson($data);
                }



        $fim = (($jsonRAW["fim"])?"'".$jsonRAW["fim"]."'":" null ");

        $sql = "INSERT INTO jogador_times (id_jogador, id_time, inicio, fim)
                VALUES(".$args['idusuario'].",".$idtime.",'".$jsonRAW["inicio"]."',$fim)";
        $this->con->executa($sql);

        if ( $this->con->res == 1 ){

            $data =   array(	"resultado" =>  "SUCESSO" );
            return $response->withJson($data, 200)->withHeader('Content-Type', 'application/json');
        }
        else {

            // nao encontrado
            $data =    array(	"resultado" =>  "ERRO",
                "erro" => "Nao foi possivel alterar os dados - $mensagem_retorno");

            return $response->withStatus(200)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);



        }

    }

}
