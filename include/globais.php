<?php
namespace raiz;
set_time_limit(2);
error_reporting(E_ALL ^ E_DEPRECATED ^E_NOTICE);
class Globais{

    public $env;
    public $banco;

    function __construct( ){


        $this->banco = $this->env = "prod";

        $servidor["UI"] = $servidor["frontend"] = "http://34.247.245.249";
        $servidor["autenticacao"] = "http://34.242.188.167";
        $servidor["players"] = "http://54.171.155.88";
        $servidor["campeonato"] = "http://34.242.140.31";

        $this->verbose=1;
        switch($this->banco){

            case("local");
                $this->localhost = "localhost";
                $this->username = "postgres";
                $this->password = "bruno";
                $this->db ="usuarios_local";
                break;

            case("prod");
                $this->localhost = "localhost";
                $this->username = "postgres";
                $this->password = "bruno";
                $this->db ="usuarios";
                break;

        }

        $this->XP_Peso_Liga["inferior"] = 0.3;
        $this->XP_Peso_Liga["superior"] = 1;

        $this->XP_Por_Ano = 100;

        //resultados
        $this->XP_Por_Divisao_Resultados["PRO"]["1"] = 100;
        $this->XP_Por_Divisao_Resultados["PRO"]["2"] = 85;
        $this->XP_Por_Divisao_Resultados["PRO"]["3"] = 70;
        $this->XP_Por_Divisao_Resultados["PRO"]["consolacao"] = 15;
        $this->XP_Por_Divisao_Resultados["D1"]["1"] = 100;
        $this->XP_Por_Divisao_Resultados["D1"]["2"] = 85;
        $this->XP_Por_Divisao_Resultados["D1"]["3"] = 70;
        $this->XP_Por_Divisao_Resultados["D1"]["consolacao"] = 15;
        $this->XP_Por_Divisao_Resultados["D2"]["1"] = 100;
        $this->XP_Por_Divisao_Resultados["D2"]["2"] = 85;
        $this->XP_Por_Divisao_Resultados["D2"]["3"] = 70;
        $this->XP_Por_Divisao_Resultados["D2"]["consolacao"] = 15;
        $this->XP_Por_Divisao_Resultados["D3"]["1"] = 100;
        $this->XP_Por_Divisao_Resultados["D3"]["2"] = 85;
        $this->XP_Por_Divisao_Resultados["D3"]["3"] = 70;
        $this->XP_Por_Divisao_Resultados["D3"]["consolacao"] = 15;
        $this->XP_Por_Divisao_Resultados["D4"]["1"] = 100;
        $this->XP_Por_Divisao_Resultados["D4"]["2"] = 85;
        $this->XP_Por_Divisao_Resultados["D4"]["3"] = 70;
        $this->XP_Por_Divisao_Resultados["D4"]["consolacao"] = 15;
        $this->XP_Por_Divisao_Resultados["D5"]["1"] = 100;
        $this->XP_Por_Divisao_Resultados["D5"]["2"] = 85;
        $this->XP_Por_Divisao_Resultados["D5"]["3"] = 70;
        $this->XP_Por_Divisao_Resultados["D5"]["consolacao"] = 15;


        //tempo de jogo
        $this->XP_Por_Divisao_TempoJogo["PRO"] = 1;
        $this->XP_Por_Divisao_TempoJogo["D1"] = 0.7;
        $this->XP_Por_Divisao_TempoJogo["D2"] = 0.5;
        $this->XP_Por_Divisao_TempoJogo["D3"] = 0.4;
        $this->XP_Por_Divisao_TempoJogo["D4"] = 0.3;
        $this->XP_Por_Divisao_TempoJogo["D5"] = 0.2;


        //ROTAS
        $this->healthcheck = $servidor["players"]."/PaintballSocialNetwork-Players/healthcheck/"; //UNIT TEST

        $this->Players_UPDATE_endpoint = $servidor["players"]."/PaintballSocialNetwork-Players/Players/:idjogadorlogado";//UNIT TEST
        $this->Players_GET_endpoint = $servidor["players"]."/PaintballSocialNetwork-Players/Players/:idjogadorlogado";//UNIT TEST
        $this->Players_ADD_TEAM_endpoint = $servidor["players"]."/PaintballSocialNetwork-Players/Players/Experiences/"; //UNIT TEST
        $this->listar_times_de_um_jogador = $servidor["players"]."/PaintballSocialNetwork-Players/Players/:idjogadorlogado/Experiences"; //UNIT TEST
        $this->delete_experiencia = $servidor["players"]."/PaintballSocialNetwork-Players/Players/:idjogadorlogado/Experiences/:idexperiencia";//UNIT TEST
        $this->editar_experiencia = $servidor["players"]."/PaintballSocialNetwork-Players/Players/:idjogadorlogado/Experiences/:idexperiencia/";//UNIT TEST
        $this->ProcurarJogadores = $servidor["players"]."/PaintballSocialNetwork-Players/SearchPlayers/"; //UNIT TEST
        $this->jogadores_por_times = $servidor["players"]."/PaintballSocialNetwork-Players/Teams/Players/";  // UNIT TEST
        $this->ProcurarTimes = $servidor["players"]."/PaintballSocialNetwork-Players/SearchTeams/"; // UNIT TEST
        $this->CriarMeuTimeSalvar = $servidor["players"]."/PaintballSocialNetwork-Players/:idjogadorlogado/Teams/";// UNIT TEST
        $this->MeusTimesRemoto = $servidor["players"]."/PaintballSocialNetwork-Players/:idjogadorlogado/MySquads/"; //UNIT TEST
        $this->adicionar_time = $servidor["players"]."/PaintballSocialNetwork-Players/:idjogadorlogado/Teams/"; //UNIT TEST

    }

}
