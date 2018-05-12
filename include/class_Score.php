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

        require_once("include/globais.php");
        $this->Globais = new Globais();

        require_once("include/class_api.php");
        $this->API = new class_API();
    }


    function calculaskill($idjogador){

        $debug = 0;
        $args["nao_calcula_skill"]=1;
        $args["idjogador"] = $idjogador;

        $dados_jogador = $this->Players->getJogador($args ,$jsonRAW);

        $dados_jogador["JOGADORES"][$idjogador]["playsince"] = 2009;
        //var_dump($dados_jogador ); exit;

        $trans=null;$trans = array(":idjogadorlogado" => $idjogador);
        $CampeonatosEventos = $this->API->CallAPI("GET", strtr(  $this->Globais->listar_times_de_um_jogador, $trans)  );
        //var_dump($CampeonatosEventos["EXPERIENCES"][49]); exit;

        //CALCULOS
        $tempo_jogando_paintball = intval ( date("Y") ) - intval ( $dados_jogador["JOGADORES"][$idjogador]["playsince"] );
        $XP_competicao=0;

        if ($debug == 1)
            echo "\n  DIVISAO DO JOGADOR ". $dados_jogador["JOGADORES"][$idjogador]["nivelcompeticao"] ." =(XP)> ".$this->Globais->XP_Por_Divisao_TempoJogo[ $dados_jogador["JOGADORES"][$idjogador]["nivelcompeticao"] ];

        if (is_array($CampeonatosEventos["EXPERIENCES"] )){
            foreach ($CampeonatosEventos["EXPERIENCES"] as $time ){
                if (is_array($time["RESULTADOS"]) ){
                    foreach ($time["RESULTADOS"] as $idresultado => $competicao_jogada){
                        if ($debug == 1)
                            echo "\n  ";
                        $XP_esta_competicao=0;
                        $competicao_jogada["divisao"] = "D3";
                        $competicao_jogada["TipoLiga"] = "superior";

                        if ($debug == 1)
                            echo "\n RANK ". $competicao_jogada["rank"] ;
                        if ($debug == 1)
                            echo "\n divisao ". $competicao_jogada["divisao"] ;

                        if (!$this->Globais->XP_Por_Divisao_Resultados[$competicao_jogada["divisao"]][  $competicao_jogada["rank"] ])
                            $XP_esta_competicao += $this->Globais->XP_Por_Divisao_Resultados[$competicao_jogada["divisao"]][ "consolacao" ];
                        else
                            $XP_esta_competicao += $this->Globais->XP_Por_Divisao_Resultados[$competicao_jogada["divisao"] ][ $competicao_jogada["rank"] ];

                        if ($debug == 1)
                            echo " \n XP_esta_competicao: " .$XP_esta_competicao;

                        $XP_esta_competicao_calculado = $XP_esta_competicao * $this->Globais->XP_Por_Divisao_TempoJogo[  $competicao_jogada["divisao"] ] * $this->Globais->XP_Peso_Liga[  $competicao_jogada["TipoLiga"]  ];

                        $XP_competicao += $XP_esta_competicao_calculado;
                        if ($debug == 1)
                            echo "\n XP COMPETICAO $idresultado: ($XP_esta_competicao_calculado) " .$XP_esta_competicao."  * ".
                                $this->Globais->XP_Por_Divisao_TempoJogo[  $competicao_jogada["divisao"] ]." * ".
                                $this->Globais->XP_Peso_Liga[  $competicao_jogada["TipoLiga"]  ];

                    }
                }

            }
        }


        //SOMATORIA
        $somatoria += $tempo_jogando_paintball*$this->Globais->XP_Por_Ano * $this->Globais->XP_Por_Divisao_TempoJogo[ $dados_jogador["JOGADORES"][$idjogador]["nivelcompeticao"] ];
        if ($debug == 1)
            echo "\n\n\n\n somatoria XP TEMPO: ($somatoria) ".$tempo_jogando_paintball ." * ".$this->Globais->XP_Por_Ano." * ".$this->Globais->XP_Por_Divisao_TempoJogo[ $dados_jogador["JOGADORES"][$idjogador]["nivelcompeticao"] ];

        $somatoria += $XP_competicao;

        if ($debug == 1)
            echo "\n\n\n\n SKILL FINAL : $somatoria";
        //var_dump($somatoria);exit;
        return $somatoria;
    }

}
