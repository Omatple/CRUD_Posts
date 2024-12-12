<?php

namespace App\Utils;

use App\Database\UserEntity;

class UserValidator
{
    public static function isValidEmail(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["error_email"] = "Please provide a valid email address.";
            return false;
        }
        return true;
    }

    public static function isValidLength(string $password): bool
    {
        return InputValidator::isLengthWithInLimit($password, "password", Constants::PASSWORD_ALLOWED_MIN_CHARS, Constants::PASSWORD_ALLOWED_MAX_CHARS);
    }

    public static function isValidCredentials(string $email, string $password): bool
    {
        if ((!$user = UserEntity::getUserByEmail($email)) || !password_verify($password, $user["pass"])) {
            $_SESSION["error_credentials"] = "Invalid email or password.";
            return false;
        }
        return true;
    }
}
