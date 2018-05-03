<?php
namespace raiz;
set_time_limit( 2 );
class Players{
    function __construct( ){
        require("include/class_db.php");
        $this->con = new db();
        $this->con->conecta();
    }

    function getJogadoresbyTeam (  $request, $response, $args , $jsonRAW){

        if (!$this->con->conectado){
            $data =   array(	"resultado" =>  "ERRO",
                "erro" => "nao conectado - ".$this->con->erro );
            return $response->withStatus(500)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }


        $sql = "SELECT jt.id_time,j.*
                FROM jogador_times  jt
                     inner join jogadores j ON (j.id_jogador = jt.id_jogador)
                WHERE jt.id_time     IN (  ".$jsonRAW['idtime']." )    ";
//echo $sql;
        $this->con->executa($sql);

        if ( $this->con->nrw > 0 ){
            $contador = 0;

            $data =   array(	"resultado" =>  "SUCESSO" );

            while ($this->con->navega(0)){
                $contador++;
                $data["TIMES"][$this->con->dados["id_time"]]["JOGADORES"][ $this->con->dados["id_jogador"] ]["nome"] = $this->con->dados["nome"];
                $data["TIMES"][$this->con->dados["id_time"]]["JOGADORES"][ $this->con->dados["id_jogador"] ]["idade"] = $this->con->dados["idade"];
                $data["TIMES"][$this->con->dados["id_time"]]["JOGADORES"][ $this->con->dados["id_jogador"] ]["cidade"] = $this->con->dados["cidade"];
                $data["TIMES"][$this->con->dados["id_time"]]["JOGADORES"][ $this->con->dados["id_jogador"] ]["foto"] = $this->con->dados["foto"];
                $data["TIMES"][$this->con->dados["id_time"]]["JOGADORES"][ $this->con->dados["id_jogador"] ]["snake"] = $this->con->dados["snake"];
                $data["TIMES"][$this->con->dados["id_time"]]["JOGADORES"][ $this->con->dados["id_jogador"] ]["snakecorner"] = $this->con->dados["snakecorner"];
                $data["TIMES"][$this->con->dados["id_time"]]["JOGADORES"][ $this->con->dados["id_jogador"] ]["backcenter"] = $this->con->dados["backcenter"];
                $data["TIMES"][$this->con->dados["id_time"]]["JOGADORES"][ $this->con->dados["id_jogador"] ]["coach"] = $this->con->dados["coach"];
                $data["TIMES"][$this->con->dados["id_time"]]["JOGADORES"][ $this->con->dados["id_jogador"] ]["doritos"] = $this->con->dados["doritos"];
                $data["TIMES"][$this->con->dados["id_time"]]["JOGADORES"][ $this->con->dados["id_jogador"] ]["doritoscorner"] = $this->con->dados["doritoscorner"];

                $data["TIMES"][$this->con->dados["id_time"]]["JOGADORES"][ $this->con->dados["id_jogador"] ]["posicoes"] =
                                                            (($this->con->dados["snake"]!="-")?"Snake":""). " "
                                                            . (($this->con->dados["snakecorner"]!="-")?"Snake Corner":""). " "
                                                            . (($this->con->dados["backcenter"]!="-")?"Back Center":""). " "
                                                            . (($this->con->dados["coach"]!="-")?"Coach":""). " "
                                                            . (($this->con->dados["doritos"]!="-")?"Doritos":""). " "
                                                            . (($this->con->dados["doritoscorner"]!="-")?"Doritos Corner":"");

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


    function getJogadorExperiences(  $request, $response, $args ){

        if (!$this->con->conectado){
            $data =   array(	"resultado" =>  "ERRO",
                "erro" => "nao conectado - ".$this->con->erro );
            return $response->withStatus(500)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }


        $sql = "SELECT jt.*, to_char(  jt.inicio, 'mm/yyyy') inicio_formatado, to_char(  jt.fim, 'mm/yyyy') fim_formatado, j.*
                FROM jogador_times jt  
                  INNER JOIN jogadores j ON (j.id_jogador = jt.id_jogador)
                WHERE jt.id_jogador = '".$args['idusuario']."' 
                ORDER BY jt.fim  DESC  ";
        $this->con->executa($sql);

        if ( $this->con->nrw > 0 ){
            $contador = 0;

            $data =   array(	"resultado" =>  "SUCESSO" );

            while ($this->con->navega(0)){
                $contador++;
                $data["EXPERIENCES"][$this->con->dados["id"]]["idtime"] = $this->con->dados["id_time"];
                $data["EXPERIENCES"][$this->con->dados["id"]]["inicio"] = $this->con->dados["inicio_formatado"];
                $data["EXPERIENCES"][$this->con->dados["id"]]["periodo"] = $this->con->dados["inicio_formatado"] . " - " . $this->con->dados["fim_formatado"];
                $data["EXPERIENCES"][$this->con->dados["id"]]["Resultados"] = $this->con->dados["resultados"];
                $data["EXPERIENCES"][$this->con->dados["id"]]["fim"] = $this->con->dados["fim_formatado"];
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



    function getJogador(  $request, $response, $args , $jsonRAW){

        if (!$this->con->conectado){
            $data =   array(	"resultado" =>  "ERRO",
                "erro" => "nao conectado - ".$this->con->erro );
            return $response->withStatus(500)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }

        //if ($jsonRAW["idtimes"]) $filtros[] = " id IN (".$jsonRAW["idtimes"].")";
        if ($args["idusuario"]) $filtros[] = " id_jogador = '".$args["idusuario"]."'";
        if ($jsonRAW["nome"]) $filtros[] = " nome ilike '%".$jsonRAW["nome"]."%'";
        if ($args["pesquisa"]) $filtros[] = " time ilike '%".$args["pesquisa"]."%'";
        //if ($jsonRAW["time"]) $filtros[] = " time ilike '%".$jsonRAW["time"]."%'";
        if ($jsonRAW["localtreino"]) $filtros[] = " cidade ilike '%".$jsonRAW["localtreino"]."%'";
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




        $sql = "SELECT * FROM jogadores  ".((is_array($filtros))?" WHERE ".implode( " or ",$filtros) :"") ;

        $this->con->executa($sql);

        if ( $this->con->nrw > 0  ){


            $data =   array(	"resultado" =>  "SUCESSO" );

            while ($this->con->navega(0)) {
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["nome"] = $this->con->dados["nome"];
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["idade"] = $this->con->dados["idade"];
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["cidade"] = $this->con->dados["cidade"];
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["foto"] = $this->con->dados["foto"];
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["snake"] = $this->con->dados["snake"];
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["snakecorner"] = $this->con->dados["snakecorner"];
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["backcenter"] = $this->con->dados["backcenter"];
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["coach"] = $this->con->dados["coach"];
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["doritos"] = $this->con->dados["doritos"];
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["doritoscorner"] = $this->con->dados["doritoscorner"];

                $data["JOGADORES"][$this->con->dados["id_jogador"]]["nivelcompeticao"] = $this->con->dados["nivelcompeticao"];

                $data["JOGADORES"][$this->con->dados["id_jogador"]]["procurando"]["Snake"] = trim($this->con->dados["procurando_snake"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["procurando"]["SnakeCorner"] = trim($this->con->dados["procurando_snakecorner"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["procurando"]["BackCenter"] = trim($this->con->dados["procurando_backcenter"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["procurando"]["Doritos"] = trim($this->con->dados["procurando_doritos"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["procurando"]["DoritosCorner"] = trim($this->con->dados["procurando_doritoscorner"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["procurando"]["Coach"] = trim($this->con->dados["procurando_coach"]);


                $data["JOGADORES"][$this->con->dados["id_jogador"]]["treino"]["Segunda"] = trim($this->con->dados["treino_segunda"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["treino"]["Terca"] = trim($this->con->dados["treino_terca"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["treino"]["Quarta"] = trim($this->con->dados["treino_quarta"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["treino"]["Quinta"] = trim($this->con->dados["treino_quinta"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["treino"]["Sexta"] = trim($this->con->dados["treino_sexta"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["treino"]["Sabado"] = trim($this->con->dados["treino_sabado"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["treino"]["Domingo"] = trim($this->con->dados["treino_domingo"]);


                $data["JOGADORES"][$this->con->dados["id_jogador"]]["procurando_Snake"] = trim($this->con->dados["procurando_snake"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["procurando_SnakeCorner"] = trim($this->con->dados["procurando_snakecorner"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["procurando_BackCenter"] = trim($this->con->dados["procurando_backcenter"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["procurando_Doritos"] = trim($this->con->dados["procurando_doritos"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["procurando_DoritosCorner"] = trim($this->con->dados["procurando_doritoscorner"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["procurando_Coach"] = trim($this->con->dados["procurando_coach"]);


                $data["JOGADORES"][$this->con->dados["id_jogador"]]["treino_Segunda"] = trim($this->con->dados["treino_segunda"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["treino_Terca"] = trim($this->con->dados["treino_terca"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["treino_Quarta"] = trim($this->con->dados["treino_quarta"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["treino_Quinta"] = trim($this->con->dados["treino_quinta"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["treino_Sexta"] = trim($this->con->dados["treino_sexta"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["treino_Sabado"] = trim($this->con->dados["treino_sabado"]);
                $data["JOGADORES"][$this->con->dados["id_jogador"]]["treino_Domingo"] = trim($this->con->dados["treino_domingo"]);

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
                    "erro" => "JSON zuado -  ".$request->getParsedBody().var_export($jsonRAW, true) );

            return $response->withStatus(500)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);


        }
        if ($jsonRAW["foto"]["tmp_name"]){
            $fotoSalvar = base64_encode(file_get_contents( $jsonRAW["foto"]["tmp_name"] ));
            $sql_complemento = " foto = 'data:".$jsonRAW["foto"]["type"].";base64,".$fotoSalvar."', ";
        }


        //INICIO DA ROTINA DE ALTERACAO DE USUARIO
        $sql = "UPDATE jogadores SET
                      nome = '".$jsonRAW["nome"]."',         
                        idade = '".$jsonRAW["idade"]."', 
                        cidade = '".$jsonRAW["cidade"]."', 
                        snake = '".$jsonRAW["Snake"]."', 
                        snakecorner = '".$jsonRAW["SnakeCorner"]."', 
                        $sql_complemento 
                        backcenter = '".$jsonRAW["BackCenter"]."', 
                        doritos = '".$jsonRAW["Doritos"]."', 
                        coach = '".$jsonRAW["Coach"]."', 
                        doritoscorner = '".$jsonRAW["DoritosCorner"]."',

                        nivelcompeticao = '".$jsonRAW["nivelcompeticao"]."',
                         
                        treino_segunda = '".$jsonRAW["treino"]["Segunda"]."', 
                        treino_terca = '".$jsonRAW["treino"]["Terca"]."', 
                        treino_quarta = '".$jsonRAW["treino"]["Quarta"]."', 
                        treino_quinta = '".$jsonRAW["treino"]["Quinta"]."', 
                        treino_sexta = '".$jsonRAW["treino"]["Sexta"]."', 
                        treino_sabado = '".$jsonRAW["treino"]["Sabado"]."', 
                        treino_domingo = '".$jsonRAW["treino"]["Domingo"]."', 

                        procurando_doritos = '".$jsonRAW["procurando"]["Doritos"]."', 
                        procurando_doritoscorner = '".$jsonRAW["procurando"]["DoritosCorner"]."', 
                        procurando_backcenter = '".$jsonRAW["procurando"]["BackCenter"]."', 
                        procurando_snakecorner  = '".$jsonRAW["procurando"]["SnakeCorner"]."', 
                        procurando_snake = '".$jsonRAW["procurando"]["Snake"]."', 
                        procurando_coach = '".$jsonRAW["procurando"]["Coach"]."' 
                        
                      
                WHERE  id_jogador = '".$args['idusuario']."'  ";
        //echo "<PRE>$sql</PRE>";
        $this->con->executa($sql);

        if ( $this->con->res == 1 ){


            $data =   array(	"resultado" =>  "SUCESSO" ,
                                "debug" =>  $sql );
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



    function RemoverExperienciaJogador(  $request, $response, $args,   $jsonRAW){

        if (!$this->con->conectado){
            $data =   array(	"resultado" =>  "ERRO",
                "erro" => "nao conectado - ".$this->con->erro );
            return $response->withStatus(500)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }

        /*
        IF (!is_array ($jsonRAW)  ) {
            $data =  array(	"resultado" =>  "ERRO",
                "erro" => "JSON zuado - ".var_export($jsonRAW, true) );

            return $response->withStatus(500)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }
        */

        //INICIO DA ROTINA DE ALTERACAO DE USUARIO
        $sql = "DELETE FROM jogador_times
                WHERE  id = '".$args['idexperiencia']."' and id_jogador =  '".$args['idusuariologado']."' ";

        $this->con->executa($sql,1);

        if ( $this->con->res == 1 ){

            $data =   array(	"resultado" =>  "SUCESSO" );
            return $response->withJson($data, 200)->withHeader('Content-Type', 'application/json');
        }
        else {

            // nao encontrado
            $data =    array(	"resultado" =>  "ERRO",
                "erro" => "Nao foi possivel excluir a experiencia ");

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

                if (!$jsonRAW["idtime"]){
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


                }
                else
                    $idtime = $jsonRAW["idtime"];


        $fim = (($jsonRAW["fim"])?"'".$jsonRAW["fim"]."'":" null ");

        $sql = "INSERT INTO jogador_times (id_jogador, id_time, inicio, fim, resultados)
                VALUES(".$jsonRAW['idjogadorlogado'].",".$idtime.",'".$jsonRAW["inicio"]."',$fim, '".$jsonRAW["resultados"]."')";
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
