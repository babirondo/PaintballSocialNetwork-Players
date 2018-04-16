<?php
namespace raiz;

class Teams{
    function __construct( ){
        require("include/class_db.php");
        $this->con = new db();
        $this->con->conecta();
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



        $sql = "INSERT INTO times (time )
                VALUES( '".$jsonRAW["time"]."' )
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
