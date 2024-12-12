<?php

use App\Utils\Navigation;

session_start();
require __DIR__ . "/../vendor/autoload.php";

if (isset($_SESSION["user"])) {
    session_destroy();
}
Navigation::redirectTo("index.php");
