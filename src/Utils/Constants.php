<?php

namespace App\Utils;

class Constants
{
    public const POSTS_TABLE_NAME = "posts";
    public const CATEGORIES_TABLE_NAME = "categorias";
    public const USERS_TABLE_NAME = "users";
    public const PASSWORD_ALLOWED_MIN_CHARS = 5;
    public const PASSWORD_ALLOWED_MAX_CHARS = 20;
    public const TITLE_ALLOWED_MIN_CHARS = 5;
    public const TITLE_ALLOWED_MAX_CHARS = 40;
    public const CONTENT_ALLOWED_MIN_CHARS = 5;
    public const CONTENT_ALLOWED_MAX_CHARS = 150;
    public const ATTEMPTS_ALLOWED_LOGIN = 3;
}
