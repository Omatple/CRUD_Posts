<?php

use App\Database\PostEntity;
use App\Utils\Navigation;

session_start();

require __DIR__ . "/../vendor/autoload.php";

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
if (isset($_SESSION["user"]) && $id && $id > 0) {
    PostEntity::deletePost($id);
    $_SESSION["message"] = "Post deleted succesly";
}
Navigation::redirectTo("index.php");
