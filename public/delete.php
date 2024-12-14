<?php

use App\Database\Post;
use App\Utils\PageNavigation;

session_start();

require __DIR__ . "/../vendor/autoload.php";

$postId = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (isset($_SESSION["user"]) && $postId && $postId > 0) {
    Post::removePost($postId);
    $_SESSION["alertMessage"] = "Post deleted successfully.";
}

PageNavigation::redirectToPage("index.php");
