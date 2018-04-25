<?php
namespace raiz;

class Teams{
    function __construct( ){
        require("include/class_db.php");
        $this->con = new db();
        $this->con->conecta();
    }



    function getTimes(  $request, $response, $args , $jsonRAW){

        if (!$this->con->conectado){
            $data =   array(	"resultado" =>  "ERRO",
                "erro" => "nao conectado - ".$this->con->erro );
            return $response->withStatus(500)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }


        $sql = "SELECT * FROM times    ";
        $this->con->executa($sql);

        if ( $this->con->nrw > 0 ){
            $contador = 0;

            $data_inicio =   array(	"resultado" =>  "SUCESSO" );

            while ($this->con->navega(0)){
                $contador++;
                $data=null;
                $data["nome"]  = $this->con->dados["time"];
                $data["id"]  = $this->con->dados["id"];
                $data["idowner"]  = $this->con->dados["idowner"];
                $data["localtreino"]  = $this->con->dados["localtreino"];
                $data["nivelcompeticao"]  = $this->con->dados["nivelcompeticao"];
                $data["treino_segunda"]  = $this->con->dados["treino_segunda"];
                $data["treino_terca"]  = $this->con->dados["treino_terca"];
                $data["treino_quarta"]  = $this->con->dados["treino_quarta"];
                $data["treino_quinta"]  = $this->con->dados["treino_quinta"];
                $data["treino_sexta"]  = $this->con->dados["treino_sexta"];
                $data["treino_sabado"]  = $this->con->dados["treino_sabado"];
                $data["treino_domingo"]  = $this->con->dados["treino_domingo"];
                $data["procurando_snake"]  = $this->con->dados["procurando_snake"];
                $data["procurando_snakecorner"]  = $this->con->dados["procurando_snakecorner"];
                $data["procurando_backcenter"]  = $this->con->dados["procurando_backcenter"];
                $data["procurando_doritos"]  = $this->con->dados["procurando_doritos"];
                $data["procurando_doritoscorner"]  = $this->con->dados["procurando_doritoscorner"];
                $data["qtde_jogadores"]  = 4;

                $output["TIMES"][] = $data;

            }

            return $response->withJson(array_merge($data_inicio, $output), 200)->withHeader('Content-Type', 'application/json');
        }
        else {

            // nao encontrado
            $data =    array(	"resultado" =>  "ERRO",
                "erro" => "Nenhum time encontrado");

            return $response->withStatus(200)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }

    }
    function getMyTeams(  $request, $response, $args ){

        if (!$this->con->conectado){
            $data =   array(	"resultado" =>  "ERRO",
                "erro" => "nao conectado - ".$this->con->erro );
            return $response->withStatus(500)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }


        $sql = "SELECT * FROM times  WHERE idowner = '".$args['idusuario']."'  ";

        $this->con->executa($sql);

        if ( $this->con->nrw > 0 ){
            $contador = 0;

            $data =   array(	"resultado" =>  "SUCESSO" );

            while ($this->con->navega(0)){
                $contador++;
                $data["TIMES"][$this->con->dados["id"]]["time"] = $this->con->dados["time"];

            }

            return $response->withJson($data, 200)->withHeader('Content-Type', 'application/json');
        }
        else {

            // nao encontrado
            $data =    array(	"resultado" =>  "ERRO",
                "erro" => "Nao foi possivel encontrar nenhum time deste jogador ");

            return $response->withStatus(200)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);



        }

    }



    function Adicionar_time(  $request, $response, $args,   $jsonRAW){




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
 //todo: checar se o campo inicio e fim eh date

        if ( strlen(trim($jsonRAW["time"])) < 1 ){
            $data =  array(	"resultado" =>  "ERRO",
                "erro" => "Time Invalido - ".var_export($jsonRAW, true) );

            return $response->withStatus(500)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }



        $sql = "INSERT INTO times (time , idowner, localtreino, 
                                    nivelcompeticao, treino_segunda, treino_terca, 
                                    treino_quarta, treino_quinta, treino_sexta,
                                    treino_sabado, treino_domingo, procurando_snake, 
                                    procurando_snakecorner, procurando_backcenter, procurando_doritoscorner,
                                    procurando_doritos                                    
                                    )
                VALUES( 
                                '".$jsonRAW["time"]."', ".(($args["idusuario"])?$args["idusuario"]:"null").",'".$jsonRAW["localtreino"]."',
                                '".$jsonRAW["nivelcompeticao"]."',".(($args["treino"]["Segunda"])? $args["treino"]["Segunda"] :"null").",".(($args["treino"]["Terca"])? $args["treino"]["Terca"] :"null").",
                                ".(($args["treino"]["Quarta"])? $args["treino"]["Quarta"] :"null").",".(($args["treino"]["Quinta"])? $args["treino"]["Quinta"] :"null").",".(($args["treino"]["Sexta"])? $args["treino"]["Sexta"] :"null")."
                                ,".(($args["treino"]["Sabado"])? $args["treino"]["Sabado"] :"null").",".(($args["treino"]["Domingo"])? $args["treino"]["Domingo"] :"null").",".(($args["procurando"]["Snake"])? $args["procurando"]["Snake"] :"null")."
                                ,".(($args["procurando"]["SnakeCorner"])? $args["procurando"]["SnakeCorner"] :"null").",".(($args["procurando"]["BackCenter"])? $args["procurando"]["BackCenter"] :"null").",".(($args["procurando"]["DoritosCorner"])? $args["procurando"]["DoritosCorner"] :"null")."
                                ,".(($args["procurando"]["Doritos"])? $args["procurando"]["Doritos"] :"null").",,".(($args["procurando"]["Coach"])? $args["procurando"]["Coach"] :"null")."
                                                 
                 )
                RETURNING id";

        $this->con->executa($sql, 1);


        if ( $this->con->res == 1 ){


            $data =   array(	"resultado" =>  "SUCESSO" );
            $data["idtime"] = $this->con->dados["id"];
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

}
