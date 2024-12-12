<?php

namespace App\Utils;

class CookieManager
{
    public static function create(string $name, string $value, int $expiresHours = 1, string $path = "/"): bool
    {
        return setcookie($name, $value, time() + $expiresHours * 60 * 60, $path);
    }

    public static function set(string $name, string $newValue, ?int $expiresSeconds = (60 * 60)): bool
    {
        return setcookie(name: $name, value: $newValue, expires_or_options: time() + $expiresSeconds, path: "/");
    }

    public static function delete(string $name): bool
    {
        return setcookie($name, "", time() - 24 * 60 * 60, "/");
    }

    public static function getAttemptsLogin(): int
    {
        return isset($_COOKIE["attempts"]) ? (int) $_COOKIE["attempts"] : 0;
    }

    public static function deleteAttempts(): bool
    {
        return self::delete("attempts");
    }

    public static function incrementAttempts(): void
    {
        if (!$attempts = self::getAttemptsLogin()) {
            $attempts = 1;
            self::create("attempts", $attempts);
        } else {
            if (++$attempts === Constants::ATTEMPTS_ALLOWED_LOGIN) {
                self::set("attempts", Constants::ATTEMPTS_ALLOWED_LOGIN, 30);
                $_SESSION["error_block"] = "The login has been blocked for 30 seconds. Please try again later.";
            } elseif ($attempts < Constants::ATTEMPTS_ALLOWED_LOGIN) {
                self::set("attempts", $attempts);
            }
        }
        $_SESSION["error_attempts"] = "Failed attempt " . $attempts . "/" . Constants::ATTEMPTS_ALLOWED_LOGIN . ".";
    }

    public static function showLoginButton(): bool
    {
        $attempts = self::getAttemptsLogin();
        return $attempts < Constants::ATTEMPTS_ALLOWED_LOGIN;
    }
}
