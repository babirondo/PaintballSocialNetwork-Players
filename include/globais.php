<?php
namespace raiz;
set_time_limit(2);
//error_reporting(E_ALL ^ E_DEPRECATED ^E_NOTICE);
class Globais{

    public $env;
    public $banco;

    function __construct( ){

        if ( $_SERVER["CONTEXT_DOCUMENT_ROOT"] == "/var/www/html")
            $this->banco = $this->env = "prod";
        else
            $this->banco = $this->env = "local";
        //$this->banco = "prod";
        switch($this->env){

            case("local");
                $servidor= "http://localhost:81";
                $this->verbose=1;
                break;

            case("prod");
                $servidor= "http://pb.mundivox.rio";
                $this->verbose=1;
                break;

        }
        switch($this->banco){

            case("local");
                $this->localhost = "localhost";
                $this->username = "postgres";
                $this->password = "bruno";
                $this->db ="Usuarios";
                break;

            case("prod");
                $this->localhost = "pb.mundivox.rio";
                $this->username = "pb";
                $this->password = "Rodr1gues";
                $this->db ="usuarios";
                break;

        }

    }

}
