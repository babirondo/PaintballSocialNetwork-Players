<?php
namespace raiz;

class Players{
    function __construct( ){
      require_once 'vendor/autoload.php'; // Autoload files using Composer autoload

      require_once("include/globais.php");
      $this->Globais = new Globais();

      $this->con = new \babirondo\classbd\db();
      $this->con->conecta( $this->Globais->banco ,
                            $this->Globais->localhost,
                            $this->Globais->db,
                            $this->Globais->username,
                            $this->Globais->password,
                            $this->Globais->port);

        require_once("include/class_Experiences.php");
        $this->Experience = new Experiences();

        $this->API = new \babirondo\REST\RESTCall();
    }


    function getJogadoresbyTeam (  $request, $response, $args , $jsonRAW){

        if (!$this->con->conectado){
            $data =   array(	"resultado" =>  "ERRO",
                "erro" => "nao conectado - ".$this->con->erro );
            return $response->withStatus(509)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }



        $sql = "SELECT jt.id_time
                FROM jogador_times  jt

                WHERE jt.id_time  IN (  ". implode(",",$jsonRAW['idtimes'] ) .")
                group by jt.id_time";

        $this->con->executa($sql);

        if ( $this->con->nrw > 0 ){
            $contador = 0;

            $data =   array(	"resultado" =>  "SUCESSO" );

            $jsonRAW_novo = $jsonRAW;
            unset($jsonRAW_novo["idtimes"]);

            while ($this->con->navega(0)){
                $contador++;

                $jsonRAW_novo["idtime"] = $this->con->dados["id_time"];

                $this->conAuxiliar = new \babirondo\classbd\db();
                $this->conAuxiliar->conecta($this->Globais->banco ,
                                      $this->Globais->localhost,
                                      $this->Globais->db,
                                      $this->Globais->username,
                                      $this->Globais->password,
                                      $this->Globais->port);

                $data["TIMES"][$this->con->dados["id_time"]] = $this->getJogador(null,$jsonRAW_novo, $this->conAuxiliar);
                $this->conAuxiliar->fechar();
                /*
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
*/
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
            return $response->withStatus(203)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }


        $sql = "SELECT j.*, jt.*,
                        to_char(  jt.inicio, 'mm/yyyy') inicio_formatado, to_char(  jt.fim, 'mm/yyyy') fim_formatado,
                        to_char(  jt.inicio, 'dd/mm/yyyy') inicio_ddmmyyyy, to_char(  jt.fim, 'dd/mm/yyyy') fim_ddmmyyyy
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
                $data["EXPERIENCES"][$this->con->dados["id"]]["inicio_ddmmyyyy"] = $this->con->dados["inicio_ddmmyyyy"];
                $data["EXPERIENCES"][$this->con->dados["id"]]["fim_ddmmyyyy"] = $this->con->dados["fim_ddmmyyyy"];
                $data["EXPERIENCES"][$this->con->dados["id"]]["RESULTADOS"] = $this->Experience->getResultados($this->con->dados["id"]);//xxxxxx


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


    function getJogador(  $args , $jsonRAW, $conexaoBanco = null){

        if (!$conexaoBanco) $conexaoBanco = $this->con;
        //var_dump($jsonRAW);

        if (is_array($jsonRAW["idtimes"])) $filtros[] = "   jt.id_time IN (".implode(",",$jsonRAW["idtimes"]).") ";
        if ( ($jsonRAW["idtime"])) {
          //var_dump($jsonRAW);exit;
          $filtros[] = "  jt.id_time IN (".$jsonRAW["idtime"].") ";

        }

        //if ($jsonRAW["idtimes"]) $filtros[] = " id IN (".$jsonRAW["idtimes"].")";
        if ($args["idusuario"]) $filtros[] = " j.id_jogador = '".$args["idusuario"]."'";
        if ($args["idjogador"]) $filtros[] = "  j.id_jogador = '".$args["idjogador"]."'";
        if ($jsonRAW["id_jogador"]) $filtros[] = "  j.id_jogador = '".$jsonRAW["id_jogador"]."'";

        if ($jsonRAW["nome"]) $filtros[] = " j.nome ilike '%".$jsonRAW["nome"]."%'";
        if ($args["pesquisa"]) $filtros[] = "zzzzz time ilike '%".$args["pesquisa"]."%'"; //TODO: buscar nome do time
        if ($jsonRAW["localtreino"]) $filtros[] = " j.cidade ilike '%".$jsonRAW["localtreino"]."%'";
        if ($jsonRAW["nivelcompeticao"]) $filtros[] = " j.nivelcompeticao ilike '%".$jsonRAW["nivelcompeticao"]."%'";

        if ($jsonRAW["treino"]["Segunda"]) $filtros[] = " j.treino_segunda ilike '%".$jsonRAW["treino"]["Segunda"]."%'";
        if ($jsonRAW["treino"]["Terca"]) $filtros[] = " j.treino_terca ilike '%".$jsonRAW["treino"]["Terca"]."%'";
        if ($jsonRAW["treino"]["Quarta"]) $filtros[] = " j.treino_quarta ilike '%".$jsonRAW["treino"]["Quarta"]."%'";
        if ($jsonRAW["treino"]["Quinta"]) $filtros[] = " j.treino_quinta ilike '%".$jsonRAW["treino"]["Quinta"]."%'";
        if ($jsonRAW["treino"]["Sexta"]) $filtros[] = " j.treino_sexta ilike '%".$jsonRAW["treino"]["Sexta"]."%'";
        if ($jsonRAW["treino"]["Sabado"]) $filtros[] = " j.treino_sabado ilike '%".$jsonRAW["treino"]["Sabado"]."%'";
        if ($jsonRAW["treino"]["Domingo"]) $filtros[] = " j.treino_domingo ilike '%".$jsonRAW["treino"]["Domingo"]."%'";

        if ($jsonRAW["procurando"]["Snake"]) $filtros[] = " j.procurando_snake ilike '%".$jsonRAW["procurando"]["Snake"]."%'";
        if ($jsonRAW["procurando"]["SnakeCorner"]) $filtros[] = " j.procurando_snakecorner ilike '%".$jsonRAW["procurando"]["SnakeCorner"]."%'";
        if ($jsonRAW["procurando"]["BackCenter"]) $filtros[] = " j.procurando_backcenter ilike '%".$jsonRAW["procurando"]["BackCenter"]."%'";
        if ($jsonRAW["procurando"]["Doritos"]) $filtros[] = " j.procurando_doritos ilike '%".$jsonRAW["procurando"]["Doritos"]."%'";
        if ($jsonRAW["procurando"]["DoritosCorner"]) $filtros[] = " j.procurando_doritoscorner ilike '%".$jsonRAW["procurando"]["DoritosCorner"]."%'";
        if ($jsonRAW["procurando"]["Coach"]) $filtros[] = " j.procurando_coach ilike '%".$jsonRAW["procurando"]["Coach"]."%'";


        //var_dump($args);

        $sql = "SELECT j.* , jt.id_time
                FROM jogadores  j
                  LEFT JOIN jogador_times jt ON (jt.id_jogador = j.id_jogador)
                ".((is_array($filtros))?" WHERE ".implode( " or ",$filtros) :"") ;
        //echo $sql;
//        if ($args["nao_calcula_skill"] == 1) { echo $sql; exit; }
        $conexaoBanco->executa($sql);

        if ( $conexaoBanco->nrw > 0  ){

            if ($args["nao_calcula_skill"] == null ) {
                require_once("include/class_Score.php");
                $this->Score = new Score();
            }
            while ($conexaoBanco->navega(0)) {
              $data["TIMES"] [$conexaoBanco->dados["id_time"]] = $conexaoBanco->dados["id_time"];

              $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["TIMES"] [$conexaoBanco->dados["id_time"]]["inicio"] = $conexaoBanco->dados["inicio"];
              $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["TIMES"] [$conexaoBanco->dados["id_time"]]["fim"] = $conexaoBanco->dados["fim"];
              $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["TIMES"] [$conexaoBanco->dados["id_time"]]["resultados"] = $conexaoBanco->dados["resultados"];

                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["nome"] = $conexaoBanco->dados["nome"];
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["idade"] = $conexaoBanco->dados["idade"];
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["cidade"] = $conexaoBanco->dados["cidade"];
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["playsince"] = $conexaoBanco->dados["playsince"];
                //$data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["foto"] = $conexaoBanco->dados["foto"];

                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["snake"] = $conexaoBanco->dados["snake"];
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["snakecorner"] = $conexaoBanco->dados["snakecorner"];
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["backcenter"] = $conexaoBanco->dados["backcenter"];
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["coach"] = $conexaoBanco->dados["coach"];
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["doritos"] = $conexaoBanco->dados["doritos"];
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["doritoscorner"] = $conexaoBanco->dados["doritoscorner"];

                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["nivelcompeticao"] = $conexaoBanco->dados["nivelcompeticao"];

                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["procurando"]["Snake"] = trim($conexaoBanco->dados["procurando_snake"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["procurando"]["SnakeCorner"] = trim($conexaoBanco->dados["procurando_snakecorner"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["procurando"]["BackCenter"] = trim($conexaoBanco->dados["procurando_backcenter"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["procurando"]["Doritos"] = trim($conexaoBanco->dados["procurando_doritos"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["procurando"]["DoritosCorner"] = trim($conexaoBanco->dados["procurando_doritoscorner"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["procurando"]["Coach"] = trim($conexaoBanco->dados["procurando_coach"]);


                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["treino"]["Segunda"] = trim($conexaoBanco->dados["treino_segunda"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["treino"]["Terca"] = trim($conexaoBanco->dados["treino_terca"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["treino"]["Quarta"] = trim($conexaoBanco->dados["treino_quarta"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["treino"]["Quinta"] = trim($conexaoBanco->dados["treino_quinta"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["treino"]["Sexta"] = trim($conexaoBanco->dados["treino_sexta"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["treino"]["Sabado"] = trim($conexaoBanco->dados["treino_sabado"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["treino"]["Domingo"] = trim($conexaoBanco->dados["treino_domingo"]);


                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["procurando_Snake"] = trim($conexaoBanco->dados["procurando_snake"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["procurando_SnakeCorner"] = trim($conexaoBanco->dados["procurando_snakecorner"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["procurando_BackCenter"] = trim($conexaoBanco->dados["procurando_backcenter"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["procurando_Doritos"] = trim($conexaoBanco->dados["procurando_doritos"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["procurando_DoritosCorner"] = trim($conexaoBanco->dados["procurando_doritoscorner"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["procurando_Coach"] = trim($conexaoBanco->dados["procurando_coach"]);


                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["treino_Segunda"] = trim($conexaoBanco->dados["treino_segunda"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["treino_Terca"] = trim($conexaoBanco->dados["treino_terca"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["treino_Quarta"] = trim($conexaoBanco->dados["treino_quarta"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["treino_Quinta"] = trim($conexaoBanco->dados["treino_quinta"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["treino_Sexta"] = trim($conexaoBanco->dados["treino_sexta"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["treino_Sabado"] = trim($conexaoBanco->dados["treino_sabado"]);
                $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["treino_Domingo"] = trim($conexaoBanco->dados["treino_domingo"]);

                //tem que ser o ultimo
                if ($args["nao_calcula_skill"] == null ){
                    //  $skill = $this->Score->calculaskill($conexaoBanco->dados["id_jogador"],$data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]);
                      $data["JOGADORES"][$conexaoBanco->dados["id_jogador"]]["skill"] = $skill;
                }

            }
            return $data;
        }
        else {
            return false;
        }
    }


    function getJogadorAPI(  $request, $response, $args , $jsonRAW){

        if (!$this->con->conectado){
            $data =   array(	"resultado" =>  "ERRO",
                "erro" => "nao conectado - ".$this->con->erro );
            return $response->withStatus(509)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }

        $data = $this->getJogador(   $args , $jsonRAW);

        if (is_array($data)){
            $data["resultado"] = "SUCESSO";
            return $response->withJson($data, 200)->withHeader('Content-Type', 'application/json');
        }
        else{
            $data["resultado"] = "ERRO";
            return $response->withJson($data, 404)->withHeader('Content-Type', 'application/json');

        }
    }

    function Atualizar_Jogador(  $request, $response, $args,   $jsonRAW){

        if (!$this->con->conectado){
            $data =   array(	"resultado" =>  "ERRO",
                    "erro" => "nao conectado - ".$this->con->erro );
            return $response->withStatus(501)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }

        IF (!is_array ($jsonRAW)  ) {
            $data =  array(	"resultado" =>  "ERRO",
                    "erro" => "JSON zuado -  ".$request->getParsedBody().var_export($jsonRAW, true) );

            return $response->withStatus(502)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);


        }

        if ($jsonRAW["fotoSalvar"]){
            //TODO: deletar imagem anterior


            $trans=null;$trans = array(":idjogador" => $args['idusuario']  );
            $salvar_imagem_payload["imagem"] = "data:".$jsonRAW["foto"]["type"].";base64,".$jsonRAW["fotoSalvar"];//"binario da foto";
            $salvar_imagem_payload["TipoImagem"] = $jsonRAW["fotoSalvarTipoImagem"];//"Profile";
            $query_API = $this->API->CallAPI("POST", strtr( $this->Globais->SaveImage, $trans), json_encode($salvar_imagem_payload,1),'ERRO');
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

                        playsince = '".$jsonRAW["playsince"]."',

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


            $data =   array(	"resultado" =>  "SUCESSO"  );
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
            return $response->withStatus(509)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }

        /*
        IF (!is_array ($jsonRAW)  ) {
            $data =  array(	"resultado" =>  "ERRO",
                "erro" => "JSON zuado - ".var_export($jsonRAW, true) );

            return $response->withStatus(509)
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

    function CriarTime($idtime="", $time="", $idjogador=null ){

        if (!$idtime ){
            $array_post = null;
            $array_post['time'] = $time;


            $trans = null;$trans = array(":idjogadorlogado" => $idjogador);
            //xxxx
            $query_API = $this->API->CallAPI("POST",  strtr($this->Globais->adicionar_time, $trans)  , json_encode($array_post));

          //  var_dump($query_API);

            if ($query_API){
                if ($query_API["resultado"] == "SUCESSO") {
                    $mensagem_retorno =  "Dados Salvos com sucesso";
                    return $query_API["idtime"];
                }
                else{

                    $this->data =  array(	"resultado" =>  "ERRO",
                        "erro" => $query_API["erro"]." Nao foi possivel criar o time"  );

                    return false;
                }
            }
            else{
                $this->data =  array(	"resultado" =>  "ERRO",
                    "erro" => $query_API["erro"]."API nao retornou como esperado"  );

                return false;
            }
        }
        else
            return $idtime;

    }

    function Criar_Jogador($request, $response, $args,   $jsonRAW){
      if (!$this->con->conectado){
          $data =   array(	"resultado" =>  "ERRO",
                  "erro" => "nao conectado - ".$this->con->erro );
          return $response->withStatus(501)
              ->withHeader('Content-type', 'application/json;charset=utf-8')
              ->withJson($data);
      }

      IF (!is_array ($jsonRAW)  ) {
          $data =  array(	"resultado" =>  "ERRO",
                  "erro" => "JSON zuado -  ".$request->getParsedBody().var_export($jsonRAW, true) );

          return $response->withStatus(502)
              ->withHeader('Content-type', 'application/json;charset=utf-8')
              ->withJson($data);


      }



      $sql = "INSERT INTO jogadores (nome )
          VALUES('".$jsonRAW['nome']."')
          RETURNING id_jogador";
      $this->con->executa($sql, 1);

      if ( $this->con->res == 1 ){
          $idjogador = $this->con->dados["id_jogador"];

          $data =   array(	"resultado" =>  "SUCESSO" ,
                              "debug" =>  $sql,
                            "id_jogador" => $idjogador );
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
      //xxxxxxx
        if (!$this->con->conectado){
            $data =   array(	"resultado" =>  "ERRO",
                "erro" => "nao conectado - ".$this->con->erro );
            return $response->withStatus(201)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }

        IF (!is_array ($jsonRAW)  ) {
            $data =  array(	"resultado" =>  "ERRO",
                "erro" => "JSON zuado - ".var_export($jsonRAW, true) );

            return $response->withStatus(202)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }

        //TODO: criticar fim nulo e trim
        //TODO: criticar tipo data no campo inicio, formato BR ou gringo
        //time criado no form

        $idtime = $this->CriarTime($jsonRAW["idtime"],$jsonRAW["time"], $jsonRAW['idjogadorlogado']);
        if (!$idtime){
            $data =  array(	"resultado" =>  "ERRO",
                "erro" => $this->data["erro"]." .. nao foi possivel criar o time" );
            return $response->withStatus(203)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);

        }

        $inicio = (($jsonRAW["inicio"])?"'01/".$jsonRAW["inicio"]."'":" null ");
        $fim = (($jsonRAW["fim"])?"'01/".$jsonRAW["fim"]."'":" null ");

        $sql = "INSERT INTO jogador_times (id_jogador, id_time, inicio, fim, resultados)
                VALUES(".$jsonRAW['idjogadorlogado'].",".$idtime.",".$inicio.",$fim, '".$jsonRAW["resultados"]."')
                RETURNING id";
        //echo $sql;exit;
        $this->con->executa($sql, 1);

        if ( $this->con->res == 1 ){
            $data["idexperience"] = $this->con->dados["id"];
            $data["idtime"] = $idtime;
            $data["resultado"] = "SUCESSO";


            if (is_array($jsonRAW["idevento"])){
                //$data["ideventos"][] = $this->Experience->AdicionarExperience($event, $jsonRAW["posicao"], $jsonRAW["rank"], $data["idexperience"]);

                foreach ($jsonRAW["idevento"] as $idRes => $event){
                    //echo "<BR>-------------------  ".$idRes;
                    if ($idRes >= 0 ){
                        $data["ideventos"][] = $this->Experience->AdicionarExperience($event, $jsonRAW["posicao"][$idRes], $jsonRAW["rank"][$idRes], $data["idexperience"]);
                    }
                }

            }


          //  $data =   array(	"resultado" =>  "SUCESSO" );
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


    function AlterarExperience(  $request, $response, $args,   $jsonRAW){

        if (!$this->con->conectado){
            $data =   array(	"resultado" =>  "ERRO",
                "erro" => "nao conectado - ".$this->con->erro );
            return $response->withStatus(509)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }

        IF (!is_array ($jsonRAW)  ) {
            $data =  array(	"resultado" =>  "ERRO",
                "erro" => "JSON zuado - ".var_export($jsonRAW, true) );

            return $response->withStatus(509)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }
        //alterando time
        $idtime = $this->CriarTime($jsonRAW["idtime"],$jsonRAW["time"]);
        if (!$idtime){
            $data =  array(	"resultado" =>  "ERRO",
                "erro" => "nao foi possivel criar o time" );
            return $response->withStatus(509)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);

        }

        $inicio = (($jsonRAW["inicio"])?"'01/".$jsonRAW["inicio"]."'":" null ");
        $fim = (($jsonRAW["fim"])?"'01/".$jsonRAW["fim"]."'":" null ");

        $sql = "UPDATE jogador_times SET
                     id_time = $idtime,
                     inicio = $inicio,
                     fim = ".$fim.",
                     resultados = '".$jsonRAW["resultados"]."'
                 WHERE id = '".$args["idexperience"]."'";

        $this->con->executa($sql);

        if ( $this->con->res == 1 ){

            if (is_array($jsonRAW["idevento"]) ) {

                //var_dump($jsonRAW);

                foreach ($jsonRAW["idevento"] as $idResultado => $evento){
//                    echo "<BR>aqui ---------------- $evento $idResultado";
                    if ($evento){
                      if ( $idResultado > 0){
                          //echo "<BR> Alterando experience ";

                          $this->Experience->AlterarExperience( $evento, $jsonRAW["posicao"][$idResultado], $jsonRAW["rank"][$idResultado], $idResultado);
                        }
                      else{
                        //echo "<BR> Inlcuindo experience";
                          $this->Experience->AdicionarExperience( $evento, $jsonRAW["posicao"][$idResultado], $jsonRAW["rank"][$idResultado], $args["idexperience"]);
                        }

                    }
                }
            }

            $data =   array(	"resultado" =>  "SUCESSO" );
            return $response->withJson($data, 200)->withHeader('Content-Type', 'application/json');
        }
        else {

            // nao encontrado
            $data =    array(	"resultado" =>  "ERRO",
                "erro" => "Nao foi possivel alterar os dados - $mensagem_retorno - $sql");

            return $response->withStatus(200)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }
    }


    function deleteJogador(  $request, $response, $args,   $jsonRAW){

        if (!$this->con->conectado){
            $data =   array(	"resultado" =>  "ERRO",
                "erro" => "nao conectado - ".$this->con->erro );
            return $response->withStatus(201)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }

        IF (!is_array ($jsonRAW)  ) {
            $data =  array(	"resultado" =>  "ERRO",
                "erro" => "JSON zuado - ".var_export($jsonRAW, true) );

            return $response->withStatus(202)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }

        $sql = "DELETE FROM jogadores WHERE  id_jogador = '".$args['idusuario']."'";
        //echo $sql;
        $this->con->executa($sql, 1);

        if ( $this->con->res == 1 ){
            $data["resultado"] = "SUCESSO";

            return $response->withJson($data, 200)->withHeader('Content-Type', 'application/json');
        }
        else {
            // nao encontrado
            $data =    array(	"resultado" =>  "ERRO",
                "erro" => "Nao foi possivel deletar o jogador  - $mensagem_retorno");

            return $response->withStatus(200)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);
        }

    }

}
