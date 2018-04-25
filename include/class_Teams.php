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
        $filtros = null;
        if ($jsonRAW["time"]) $filtros[] = " time ilike '%".$jsonRAW["time"]."%'";
        if ($jsonRAW["localtreino"]) $filtros[] = " localtreino ilike '%".$jsonRAW["localtreino"]."%'";
        if ($jsonRAW["nivelcompeticao"]) $filtros[] = " nivelcompeticao ilike '%".$jsonRAW["nivelcompeticao"]."%'";
        if ($jsonRAW["treino"]["Segunda"]) $filtros[] = " treino_segunda ilike '%".$jsonRAW["treino"]["Segunda"]."%'";
        if ($jsonRAW["treino"]["Terca"]) $filtros[] = " treino_terca ilike '%".$jsonRAW["treino"]["Terca"]."%'";
        if ($jsonRAW["treino"]["Quarta"]) $filtros[] = " treino_quarta ilike '%".$jsonRAW["treino"]["Quarta"]."%'";
        if ($jsonRAW["treino"]["Quinta"]) $filtros[] = " treino_quinta ilike '%".$jsonRAW["treino"]["Quinta"]."%'";
        if ($jsonRAW["treino"]["Sexta"]) $filtros[] = " treino_sexta ilike '%".$jsonRAW["treino"]["Sexta"]."%'";
        if ($jsonRAW["treino"]["Sabado"]) $filtros[] = " treino_sabado ilike '%".$jsonRAW["treino"]["Sabado"]."%'";
        if ($jsonRAW["treino"]["Domingo"]) $filtros[] = " treino_domingo ilike '%".$jsonRAW["treino"]["Domingo"]."%'";
        if ($jsonRAW["procurando"]["Snake"]) $filtros[] = " procurando_snake ilike '%".$jsonRAW["procurando"]["Snake"]."%'";
        if ($jsonRAW["procurando"]["SnakeCorner"]) $filtros[] = " procurando_snakecorner ilike '%".$jsonRAW["procurando"]["SnakeCorner"]."%'";
        if ($jsonRAW["procurando"]["BackCenter"]) $filtros[] = " procurando_backcenter ilike '%".$jsonRAW["procurando"]["BackCenter"]."%'";
        if ($jsonRAW["procurando"]["Doritos"]) $filtros[] = " procurando_doritos ilike '%".$jsonRAW["procurando"]["Doritos"]."%'";
        if ($jsonRAW["procurando"]["DoritosCorner"]) $filtros[] = " procurando_doritoscorner ilike '%".$jsonRAW["procurando"]["DoritosCorner"]."%'";
        if ($jsonRAW["procurando"]["Coach"]) $filtros[] = " procurando_coach ilike '%".$jsonRAW["procurando"]["Coach"]."%'";



        $sql = "SELECT * FROM times ".((is_array($filtros))?" WHERE ".implode( " and ",$filtros) :"") ;

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
                $data["procurando_coach"]  = $this->con->dados["procurando_coach"];
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

      //  echo "<PRE>";var_dump($jsonRAW); echo "</PRE>";


        $sql = "INSERT INTO times (time , idowner, localtreino, 
                                    nivelcompeticao, treino_segunda, treino_terca, 
                                    treino_quarta, treino_quinta, treino_sexta,
                                    treino_sabado, treino_domingo, procurando_snake, 
                                    procurando_snakecorner, procurando_backcenter, procurando_doritoscorner,
                                    procurando_doritos , procurando_coach                              
                                    )
                VALUES( 
                                '".$jsonRAW["time"]."', ".(($args["idusuario"])?$args["idusuario"]:"null").",'".$jsonRAW["localtreino"]."',
                                '".$jsonRAW["nivelcompeticao"]."',".(($jsonRAW["treino"]["Segunda"])? "'".$jsonRAW["treino"]["Segunda"]."'" :"null").",".(($jsonRAW["treino"]["Terca"])? "'".$jsonRAW["treino"]["Terca"] ."'":"null").",
                                ".(($jsonRAW["treino"]["Quarta"])? "'".$jsonRAW["treino"]["Quarta"]."'" :"null").",".(($jsonRAW["treino"]["Quinta"])? "'".$jsonRAW["treino"]["Quinta"]."'" :"null").",".(($jsonRAW["treino"]["Sexta"])? "'".$jsonRAW["treino"]["Sexta"]."'" :"null").",
                                ".(($jsonRAW["treino"]["Sabado"])? "'".$jsonRAW["treino"]["Sabado"]."'" :"null").",".(($jsonRAW["treino"]["Domingo"])? "'".$jsonRAW["treino"]["Domingo"]."'" :"null").",".(($jsonRAW["procurando"]["Snake"])? "'".$jsonRAW["procurando"]["Snake"]."'" :"null").",
                                ".(($jsonRAW["procurando"]["SnakeCorner"])? "'".$jsonRAW["procurando"]["SnakeCorner"]."'" :"null").",".(($jsonRAW["procurando"]["BackCenter"])? "'".$jsonRAW["procurando"]["BackCenter"]."'" :"null").",".(($jsonRAW["procurando"]["DoritosCorner"])? "'".$jsonRAW["procurando"]["DoritosCorner"]."'" :"null").",
                                ".(($jsonRAW["procurando"]["Doritos"])? "'".$jsonRAW["procurando"]["Doritos"]."'" :"null").",".(($jsonRAW["procurando"]["Coach"])? "'".$jsonRAW["procurando"]["Coach"]."'" :"null")."
                                                 
                 )
                RETURNING id";
//        echo "<PRE>$sql</PRE>";
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
