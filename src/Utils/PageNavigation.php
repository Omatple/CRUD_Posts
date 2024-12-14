<?php

namespace App\Utils;

class PageNavigation
{
    public static function redirectToPage(string $url): void
    {
        header("Location: " . $url);
        exit();
    }

    public static function reloadCurrentPage(): void
    {
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit();
    }
}
