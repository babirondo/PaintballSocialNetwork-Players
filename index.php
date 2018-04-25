<?php
namespace raiz;
//error_reporting(E_ALL ^ E_DEPRECATED);


use Slim\Views\PhpRenderer;

include "vendor/autoload.php";


$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

$container = $app->getContainer();
$container['renderer'] = new PhpRenderer("./templates");



$app->get('/healthcheck/', function ($request, $response, $args)  use ($app )   {
    require_once("healthcheck/healthcheck.php");

    $HealthCheck = new HealthCheck();

    $retorno = $HealthCheck->check($response, $request->getParsedBody() );
    return $retorno;
}  );


$app->put('/Players/{idusuario}', function ($request, $response, $args)  use ($app )   {
    require_once("include/class_Players.php");

    $cPlayer = new Players();
    $retorno = $cPlayer->Atualizar_Jogador($request, $response, $args, $request->getParsedBody() );

    return $retorno;

}  );

$app->get('/Players/{idusuario}', function ($request, $response, $args)  use ($app )   {
    require_once("include/class_Players.php");

    $cPlayer = new Players();
    $retorno = $cPlayer->getJogador($request, $response, $args  );

    return $retorno;

}  );



$app->post('/Players/Experiences/', function ($request, $response, $args)  use ($app )   {
    require_once("include/class_Players.php");

    $cPlayer = new Players();
    $retorno = $cPlayer->Adicionar_time_ao_jogador($request, $response, $args, $request->getParsedBody() );

    return $retorno;

}  );



$app->post('/Teams/Players/', function ($request, $response, $args)  use ($app )   {
    require_once("include/class_Players.php");

    $cPlayer = new Players();
    $retorno = $cPlayer->getJogadoresbyTeam($request, $response, $args ,  $request->getParsedBody() );

    return $retorno;

}  );

$app->get('/Players/{idusuario}/Experiences', function ($request, $response, $args)  use ($app )   {
    require_once("include/class_Players.php");

    $cPlayer = new Players();
    $retorno = $cPlayer->getJogadorExperiences($request, $response, $args  );

    return $retorno;

}  );

$app->delete('/Players/{idusuariologado}/Experiences/{idexperiencia}', function ($request, $response, $args)  use ($app )   {
    require_once("include/class_Players.php");

    $cPlayer = new Players();
    $retorno = $cPlayer->RemoverExperienciaJogador($request, $response, $args, $request->getParsedBody() );

    return $retorno;

}  );

$app->get('/{idusuario}/MySquads/', function ($request, $response, $args)  use ($app )   {
    require_once("include/class_Teams.php");

    $cTeam = new Teams();
    $retorno = $cTeam->getMyTeams($request, $response, $args  );

    return $retorno;

}  );

$app->post('/{idusuario}/Teams/', function ($request, $response, $args)  use ($app )   {
    require_once("include/class_Teams.php");

    $cTeam = new Teams();
    $retorno = $cTeam->Adicionar_time($request, $response, $args, $request->getParsedBody() );

    return $retorno;
}  );


$app->post('/Teams', function ($request, $response, $args)  use ($app )   {
    require_once("include/class_Teams.php");

    $cTeam = new Teams();
    $retorno = $cTeam->Adicionar_time($request, $response, $args, $request->getParsedBody() );

    return $retorno;
}  );

$app->get('/Teams', function ($request, $response, $args)  use ($app )   {
    require_once("include/class_Teams.php");

    $cTeam = new Teams();
    $retorno = $cTeam->getTimes($request, $response, $args, $request->getParsedBody() );

    return $retorno;
}  );

$app->run();

