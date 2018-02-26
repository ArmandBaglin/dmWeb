<?php
require_once "../config.php";
set_include_path('./src');
require_once "Router.php";
require_once "function.php";

$router = new Router();

$router->main($db);