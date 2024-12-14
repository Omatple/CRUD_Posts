<?php

namespace App\Utils;

use App\Database\User;

class UserValidator
{
    public static function isEmailValid(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["error_email"] = "Please provide a valid email address.";
            return false;
        }
        return true;
    }

    public static function isPasswordLengthValid(string $password): bool
    {
        return FormInputValidator::validateLength(
            $password,
            "password",
            AppConstants::PASSWORD_MIN_LENGTH,
            AppConstants::PASSWORD_MAX_LENGTH
        );
    }

    public static function areCredentialsValid(string $email, string $password): bool
    {
        $user = User::fetchUserByEmail($email);
        if (!$user || !password_verify($password, $user["password"])) {
            $_SESSION["error_credentials"] = "Invalid email or password.";
            return false;
        }
        return true;
    }
}
