<?php

namespace App\Utils;

class FormInputValidator
{
    public static function sanitizeInput(string $value): string
    {
        return htmlspecialchars(trim($value));
    }

    public static function validateLength(string $value, string $fieldName, int $minLength, int $maxLength): bool
    {
        if (strlen($value) < $minLength || strlen($value) > $maxLength) {
            $_SESSION["error_$fieldName"] = ucfirst($fieldName) . " must be between $minLength and $maxLength characters.";
            return false;
        }
        return true;
    }
}
