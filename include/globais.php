<?php
namespace raiz;
set_time_limit(2);
error_reporting(E_ALL ^ E_DEPRECATED ^E_NOTICE);
class Globais{

    public $env;
    public $banco;
    public $password ;

    function __construct( ){
        $this->banco = $this->env = "local";

        $servidor["UI"] = $servidor["frontend"] = "http://192.168.0.150:81";
        $servidor["autenticacao"] = "http://192.168.0.150:82";
        $servidor["players"] = "http://192.168.0.150:83";
        $servidor["times"] = "http://192.168.0.150:86";
        $servidor["campeonato"] = "http://192.168.0.150:81";

        $servidor["bancodados_campeonato"] = "192.168.0.150";
        $servidor["bancodados_players"] = "192.168.0.150";
        $servidor["rabbitmq"] = "192.168.0.150";
        $servidor["images"] = "http://192.168.0.150:85";

        $this->verbose=1;
        switch($this->banco){

            case("local");
                $this->banco = "Postgres";
                $this->localhost = $servidor["bancodados_players"];
                $this->username = "postgres";
                $this->password = "postgres";
                $this->db ="jogadores";
            break;

        }

        // extraindo configuracoes adicionais do arquivo config.json
       	$configuracoes_externas = file_get_contents('include/config.json');
       	$config_parsed = json_decode($configuracoes_externas,true);
       	$this->external_config = $config_parsed;


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
        $this->SaveImage = $servidor["images"]."/PaintballSocialNetwork-Images/Analyze/Image/:idjogador";


        $this->healthcheck = $servidor["players"]."/PaintballSocialNetwork-Players/healthcheck/"; //UNIT TEST
        $this->Players_UPDATE_endpoint = $servidor["players"]."/PaintballSocialNetwork-Players/Players/:idjogadorlogado";//UNIT TEST
        $this->Players_GET_endpoint = $servidor["players"]."/PaintballSocialNetwork-Players/Players/:idjogadorlogado";//UNIT TEST
        $this->Players_ADD_TEAM_endpoint = $servidor["players"]."/PaintballSocialNetwork-Players/Players/Experiences/"; //UNIT TEST
        $this->Players_ADD_TEAM_Experience = $servidor["players"]."/PaintballSocialNetwork-Players/Players/:idjogadorlogado/Experiences/"; //UNIT TEST
        $this->listar_times_de_um_jogador = $servidor["players"]."/PaintballSocialNetwork-Players/Players/:idjogadorlogado/Experiences"; //UNIT TEST
        $this->delete_experiencia = $servidor["players"]."/PaintballSocialNetwork-Players/Players/:idjogadorlogado/Experiences/:idexperiencia";//UNIT TEST
        $this->editar_experiencia = $servidor["players"]."/PaintballSocialNetwork-Players/Players/:idjogadorlogado/Experiences/:idexperiencia/";//UNIT TEST
        $this->ProcurarJogadores = $servidor["players"]."/PaintballSocialNetwork-Players/SearchPlayers/"; //UNIT TEST
        $this->jogadores_por_times = $servidor["players"]."/PaintballSocialNetwork-Players/Teams/Players/";  // UNIT TEST
        $this->adicionar_time = $servidor["times"]."/PaintballSocialNetwork-Teams/:idjogadorlogado/Teams/"; //UNIT TEST

    }

}
