<?php

namespace App\Utils;

class Navigation
{
    public static function redirectTo(string $pageName): void
    {
        header("Location: " . $pageName);
        exit();
    }

    public static function refresh(): void
    {
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit();
    }
}
