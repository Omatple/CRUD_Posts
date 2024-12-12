<?php

use App\Database\CategoryEntity;
use App\Database\PostEntity;
use App\Database\UserEntity;

require __DIR__ . "/../vendor/autoload.php";

do {
    $amountUsers = (int) readline("Write amount that you want to create USERS(1-10), or 0 to exit: ");
    if ($amountUsers === 0) exit("\nExiting for request of user..." . PHP_EOL);
    if ($amountUsers < 1 || $amountUsers > 10) echo "\nERROR: Amount of USERS should be between 1 and 10 includes" . PHP_EOL;
} while ($amountUsers < 1 || $amountUsers > 10);
do {
    $amountPosts = (int) readline("Write amount that you want to create POSTS, or 0 to exit: ");
    if ($amountPosts === 0) exit("\nExiting for request of user..." . PHP_EOL);
    if ($amountPosts < 10 || $amountPosts > 50) echo "\nERROR: Amount of POSTS(10-50) should be between 10 and 50 includes" . PHP_EOL;
} while ($amountPosts < 10 || $amountPosts > 50);

UserEntity::deleteAllUsers();
UserEntity::resetAutoIncrementId();
PostEntity::deleteAllPosts();
PostEntity::resetAutoIncrementId();
CategoryEntity::deleteAllCategories();
CategoryEntity::resetAutoIncrementId();

CategoryEntity::generateDefaultCategories();
echo "\nDefault categories are been created successly." . PHP_EOL;

PostEntity::generateFakePosts($amountPosts);
echo "\n$amountPosts fake posts are been created successly." . PHP_EOL;

UserEntity::generateFakeUsers($amountUsers);
echo "\n$amountUsers fake users are been created successly." . PHP_EOL;
