<?php

namespace App\Database;

use App\Utils\BookCategories;
use App\Utils\AppConstants;

class Category extends QueryExecutor
{
    private int $id;
    private string $name;

    public static function createDefaultCategories(): void
    {
        foreach (BookCategories::cases() as $category) {
            (new Category)
                ->setName($category->getCategoryName())
                ->saveCategory();
        }
    }

    public function saveCategory(): void
    {
        parent::insertRecord(AppConstants::TABLE_NAME_CATEGORIES, ["name"], [$this->name]);
    }

    public static function hasEntries(): bool
    {
        return parent::countTableEntries(AppConstants::TABLE_NAME_CATEGORIES);
    }

    public static function fetchCategories(?int $id = null): array|false
    {
        return parent::fetchRecords(AppConstants::TABLE_NAME_CATEGORIES, $id);
    }

    public static function fetchCategoryIds(): array
    {
        return parent::fetchAllIds(AppConstants::TABLE_NAME_CATEGORIES);
    }

    public static function removeAllCategories(): void
    {
        parent::deleteAllRecords(AppConstants::TABLE_NAME_CATEGORIES);
    }

    public static function resetCategoryAutoIncrement(): void
    {
        parent::resetAutoIncrement(AppConstants::TABLE_NAME_CATEGORIES);
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
     * Get the value of name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}
