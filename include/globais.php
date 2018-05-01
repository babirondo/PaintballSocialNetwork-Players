<?php
namespace raiz;
set_time_limit(2);
//error_reporting(E_ALL ^ E_DEPRECATED ^E_NOTICE);
class Globais{



    function __construct( ){


        $env = "prod";
        //$env = "local";

        switch($env){
            case("local");
                $servidor= "http://localhost:81";
                $this->localhost = "localhost";
                $this->username = "spostgres";
                $this->password = "bruno";
                $this->db ="Usuarios";
                $this->verbose=1;
                break;

            case("prod");
                $servidor= "http://pb.mundivox.rio";
                $this->localhost = "localhost";
                $this->username = "pb";
                $this->password = "Rodr1gues";
                $this->db ="usuarios";
                $this->verbose=1;
                break;

        }

    }

}
