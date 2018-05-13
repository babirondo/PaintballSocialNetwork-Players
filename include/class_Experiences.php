<?php
namespace raiz;
set_time_limit( 2 );
class Experiences{
    function __construct( ){
        require_once("include/class_db.php");
        $this->con = new db();
        $this->con->conecta();
    }

    function formataPosicao($posicao)
    {
        switch ($posicao){
            case("1"):  $retorno = "1st Place";       break;
            case("2"):  $retorno = "2nd Place";       break;
            case("3"):  $retorno = "3rd Place";       break;

            default:
                $retorno = $posicao."th Place ";
        }

        return $retorno;
    }

    function getResultados(  $idevento ){

        if ( $idevento ) $filtros[] = " idexperience = '$idevento'";

        $sql = "SELECT r.*  
                FROM resultados r
                  
                ".((is_array($filtros))?" WHERE ".implode( " or ",$filtros) :"") ."
                 ORDER BY r.rank";
        $this->con->executa($sql);

        if ( $this->con->nrw > 0  ){


            $data = null;

            while ($this->con->navega(0)) {
                $data[$this->con->dados["id"]]["idevento"] = $this->con->dados["idevento"];
                $data[$this->con->dados["id"]]["evento"] = $this->con->dados["idevento"];
                $data[$this->con->dados["id"]]["rank_formatado"] = $this->formataPosicao($this->con->dados["rank"]);
                $data[$this->con->dados["id"]]["rank"] =  $this->con->dados["rank"];
                $data[$this->con->dados["id"]]["posicao"] = $this->con->dados["mainposicao"];

            }
            return $data;
        }
        else {
           return false;
        }
    }


    function AlterarExperience( $idevento, $posicao, $rank, $idexperience){



        if (!$idexperience){
            $data =  array(	"resultado" =>  "ERRO",
                "erro" => "Experience don't registered" );
            return $response->withStatus(500)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);

        }

        $sql = "UPDATE resultados SET
                      idevento = $idevento,
                      rank = $rank,
                      mainposicao =  '$posicao'
                 WHERE id = $idexperience" ;
      //  echo $sql;
        $this->con->executa($sql);

        if ( $this->con->res == 1 ){

            return true;
        }
        else {
            return false;
        }
    }

    function AdicionarExperience( $idevento, $posicao, $rank, $idexperience){



        if (!$idexperience){
            $data =  array(	"resultado" =>  "ERRO",
                "erro" => "Experience don't registered" );
            return $response->withStatus(500)
                ->withHeader('Content-type', 'application/json;charset=utf-8')
                ->withJson($data);

        }

        $sql = "INSERT INTO resultados (idexperience, idevento, rank, mainposicao )
                VALUES($idexperience, $idevento, $rank, '$posicao')
                RETURNING id ";
        $this->con->executa($sql, 1);

        if ( $this->con->res == 1 ){
            return $this->con->dados["id"];

        }
        else {
            return false;
        }
    }
}
