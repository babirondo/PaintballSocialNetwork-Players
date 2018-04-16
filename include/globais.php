<?php
namespace raiz;
set_time_limit(2);
//error_reporting(E_ALL ^ E_DEPRECATED ^E_NOTICE);
class Globais{



    function __construct( ){


        //$Authentication_folder = "PaintballSocialNetwork-AuthAPI/";
        //$Authentication_port = ":81/";
        //$this->Authentication_endpoint = "http://localhost".$Authentication_port.$Authentication_folder."Auth";
        $this->adicionar_time = "http://localhost:81/PaintballSocialNetwork-Players/Teams";

        $this->sourcecode = "local"; //local ou prod
        $this->banco = "prod";//dev ou prod
        $this->environment = "mac"; //mac ou windows

        if ($this->sourcecode == "prod"){
        }
        else if ($this->sourcecode == "local"){
        }

        if ($this->banco == "prod"){
            $this->localhost = "localhost";
            $this->username = "postgres";
            $this->password = "bruno";
            $this->db ="Usuarios";
            $this->verbose=1;
        }
        else if ($this->banco == "dev"){

        }

    }

}
