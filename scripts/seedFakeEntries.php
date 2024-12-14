<?php

use App\Database\Category;
use App\Database\Post;
use App\Database\User;

require __DIR__ . "/../vendor/autoload.php";

if (
    User::hasEntries() ||
    Post::hasEntries() ||
    Category::hasEntries()
) $answer = readline("Existing entries detected in one or more tables. Do you want to reset and generate fake entries, or keep the database? (y/n): ");
if (!isset($answer) || strtolower(trim($answer)) === "y") {
    do {
        $userCount = (int) readline("Enter the number of USERS to create (1-10), or 0 to exit: ");
        if ($userCount === 0) {
            exit("\nExiting as requested by the user..." . PHP_EOL);
        }
        if ($userCount < 1 || $userCount > 10) {
            echo "\nERROR: Number of USERS must be between 1 and 10." . PHP_EOL;
        }
    } while ($userCount < 1 || $userCount > 10);

    do {
        $postCount = (int) readline("Enter the number of POSTS to create (10-50), or 0 to exit: ");
        if ($postCount === 0) {
            exit("\nExiting as requested by the user..." . PHP_EOL);
        }
        if ($postCount < 10 || $postCount > 50) {
            echo "\nERROR: Number of POSTS must be between 10 and 50." . PHP_EOL;
        }
    } while ($postCount < 10 || $postCount > 50);

    User::removeAllUsers();
    User::resetUserAutoIncrement();
    Post::removeAllPosts();
    Post::resetPostAutoIncrement();
    Category::removeAllCategories();
    Category::resetCategoryAutoIncrement();

    Category::createDefaultCategories();
    echo "\nDefault categories have been created successfully." . PHP_EOL;

    Post::generateFakePosts($postCount);
    echo "\n$postCount fake posts have been created successfully." . PHP_EOL;

    User::generateFakeUsers($userCount);
    echo "\n$userCount fake users have been created successfully." . PHP_EOL;
}
echo "\nEnd of script. Exiting now." . PHP_EOL;
