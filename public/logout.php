<?php

use App\Utils\PageNavigation;

session_start();
require __DIR__ . "/../vendor/autoload.php";

if (isset($_SESSION["user"])) {
    session_destroy();
}

PageNavigation::redirectToPage("index.php");
