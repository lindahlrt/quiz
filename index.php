<?php
session_start();

define("RACINE", __DIR__);
define("BASE_URL", "/linda-hillairet/Quiz_v2");

require_once RACINE . "/app/helpers.php";
require_once RACINE . "/config/Database.php";
require_once RACINE . "/app/models/userModel.php";
require_once RACINE . "/app/models/gameModel.php";
require_once RACINE . "/app/models/questionModel.php";
require_once RACINE . "/config/routes.php";

$route = new Route;
$currentAction = $route->action;
$route->route();
