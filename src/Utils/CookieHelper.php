<?php

namespace App\Utils;

class CookieHelper
{
    public static function createCookie(string $name, string $value, int $expiresInHours = 1, string $path = "/"): bool
    {
        return setcookie($name, $value, time() + $expiresInHours * 60 * 60, $path);
    }

    public static function updateCookie(string $name, string $value, ?int $expiresInSeconds = 3600): bool
    {
        return setcookie(name: $name, value: $value, expires_or_options: time() + $expiresInSeconds, path: "/");
    }

    public static function deleteCookie(string $name): bool
    {
        return setcookie($name, "", time() - 86400, "/");
    }

    public static function getLoginAttempts(): int
    {
        return isset($_COOKIE["login_attempts"]) ? (int) $_COOKIE["login_attempts"] : 0;
    }

    public static function resetLoginAttempts(): bool
    {
        return self::deleteCookie("login_attempts");
    }

    public static function incrementLoginAttempts(): void
    {
        $currentAttempts = self::getLoginAttempts();

        if ($currentAttempts === 0) {
            $currentAttempts = 1;
            self::createCookie("login_attempts", $currentAttempts);
        } else {
            $currentAttempts++;
            if ($currentAttempts === AppConstants::MAX_LOGIN_ATTEMPTS) {
                self::updateCookie("login_attempts", AppConstants::MAX_LOGIN_ATTEMPTS, 30);
                $_SESSION["error_block"] = "Login has been temporarily blocked for 30 seconds. Please try again later.";
            } elseif ($currentAttempts < AppConstants::MAX_LOGIN_ATTEMPTS) {
                self::updateCookie("login_attempts", $currentAttempts);
            }
        }

        $_SESSION["error_attempts"] = "Failed login attempt $currentAttempts/" . AppConstants::MAX_LOGIN_ATTEMPTS . ".";
    }

    public static function canShowLoginButton(): bool
    {
        $currentAttempts = self::getLoginAttempts();
        return $currentAttempts < AppConstants::MAX_LOGIN_ATTEMPTS;
    }
}
