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
                        doritoscorner = '".$jsonRAW["DoritosCorner"]."'
                      
                WHERE  id_jogador = '".$args['idusuario']."'  ";

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
