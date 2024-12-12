<?php

namespace App\Utils;

enum Categories: string
{
    case Art = "Arte";
    case Science = "Ciencia";
    case Fiction = "Ficción";
    case History = "Historia";
    case Environment = "Medio Ambiente";
    case Policy = "Politica";

    public function toString(): string
    {
        return $this->value;
    }

    public static function getColor(string $category): string
    {
        return match ($category) {
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
