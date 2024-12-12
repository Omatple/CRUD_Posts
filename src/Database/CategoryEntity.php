<?php

namespace App\Database;

use App\Utils\Categories;
use App\Utils\Constants;

class CategoryEntity extends QueryExecutor
{
    private int $id;
    private string $nombre;

    public static function generateDefaultCategories(): void
    {
        foreach (Categories::cases() as $value) {
            (new CategoryEntity)
                ->setNombre($value->toString())
                ->createCategory();
        }
    }

    public function createCategory(): void
    {
        parent::create(Constants::CATEGORIES_TABLE_NAME, ["nombre"], [$this->nombre]);
    }

    public static function readCategory(?int $id = null): array|false
    {
        return parent::read(Constants::CATEGORIES_TABLE_NAME, $id);
    }

    public static function getIdsCategory(): array
    {
        return parent::getIds(Constants::CATEGORIES_TABLE_NAME);
    }

    public static function deleteAllCategories(): void
    {
        parent::deleteAllRegistres(Constants::CATEGORIES_TABLE_NAME);
    }

    public static function resetAutoIncrementId(): void
    {
        parent::resetIncremenntIds(Constants::CATEGORIES_TABLE_NAME);
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of nombre
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }
}
