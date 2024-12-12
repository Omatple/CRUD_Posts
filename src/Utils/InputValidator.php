<?php

namespace App\Utils;

class InputValidator
{
    public static function sanitize(string $input): string
    {
        return htmlspecialchars(trim($input));
    }

    public static function isLengthWithInLimit(string $input, string $inputName, int $minChars, int $maxChars): bool
    {
        if (strlen($input) < $minChars || strlen($input) > $maxChars) {
            $_SESSION["error_$inputName"] = ucfirst($inputName) . " must be between " . $minChars . " and " . $maxChars . " characters.";
            return false;
        }
        return true;
    }
}
