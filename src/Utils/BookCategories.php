<?php

namespace App\Utils;

enum BookCategories: string
{
    case Art = "Arte";
    case Science = "Ciencia";
    case Fiction = "Ficción";
    case History = "Historia";
    case Environment = "Medio Ambiente";
    case Policy = "Política";

    public function getCategoryName(): string
    {
        return $this->value;
    }

    public static function getCategoryColor(string $categoryName): string
    {
        return match ($categoryName) {
            self::Art->value => "orange",
            self::Science->value => "lime",
            self::Fiction->value => "cyan",
            self::History->value => "purple",
            self::Environment->value => "rose",
            self::Policy->value => "yellow",
            default => "slate"
        };
    }
}
