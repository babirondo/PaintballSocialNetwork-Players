<?php
namespace raiz;
set_time_limit( 2 );

class Score{
    function __construct( ){
        /*
        require_once("include/class_db.php");
        $this->con = new db();
        $this->con->conecta();
        */

        require_once("include/class_Players.php");
        $this->Players = new Players();
    }


    function calculaskill($idjogador){

        $args["nao_calcula_skill"]=1;
        $args["idjogador"] = $idjogador;
        $dados_jogador = $this->Players->getJogador($args ,$jsonRAW);

        return rand(0,5000);
    }

}
