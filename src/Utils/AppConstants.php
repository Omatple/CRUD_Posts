<?php

namespace App\Utils;

class AppConstants
{
    public const TABLE_NAME_POSTS = "posts";
    public const TABLE_NAME_CATEGORIES = "categories";
    public const TABLE_NAME_USERS = "users";
    public const PASSWORD_MIN_LENGTH = 5;
    public const PASSWORD_MAX_LENGTH = 20;
    public const TITLE_MIN_LENGTH = 5;
    public const TITLE_MAX_LENGTH = 100;
    public const CONTENT_MIN_LENGTH = 5;
    public const CONTENT_MAX_LENGTH = 120;
    public const MAX_LOGIN_ATTEMPTS = 3;
}
