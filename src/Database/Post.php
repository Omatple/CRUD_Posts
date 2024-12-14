<?php

namespace App\Database;

use App\Utils\AppConstants;
use App\Utils\PostStatus;
use \Faker\Factory;

class Post extends QueryExecutor
{
    private int $id;
    private string $title;
    private string $content;
    private string $status;
    private string $categoryId;

    public static function fetchAllPosts(): array|false
    {
        return parent::executeStatement(
            "SELECT " . AppConstants::TABLE_NAME_POSTS . ".*, name FROM " . AppConstants::TABLE_NAME_POSTS .
                " JOIN " . AppConstants::TABLE_NAME_CATEGORIES .
                " ON " . AppConstants::TABLE_NAME_CATEGORIES . ".id = category_id"
        )->fetchAll();
    }

    public static function generateFakePosts(int $count): void
    {
        $faker = Factory::create("es_ES");
        for ($i = 0; $i < $count; $i++) {
            do {
                //$title = ucwords($faker->unique()->words(random_int(2, 6), true));
                $title = ucwords($faker->unique()->realTextBetween(5, 40));
            } while (strlen($title) > AppConstants::TITLE_MAX_LENGTH);

            $content = $faker->realText(AppConstants::CONTENT_MAX_LENGTH);
            $status = $faker->randomElement(PostStatus::cases())->getStatusLabel();
            $categoryId = $faker->randomElement(Category::fetchCategoryIds());

            (new Post)
                ->setTitle($title)
                ->setContent($content)
                ->setStatus($status)
                ->setCategoryId($categoryId)
                ->savePost();
        }
    }

    public function savePost(): void
    {
        parent::insertRecord(
            AppConstants::TABLE_NAME_POSTS,
            ["title", "content", "status", "category_id"],
            [$this->title, $this->content, $this->status, $this->categoryId]
        );
    }

    public static function removePost(int $id): void
    {
        parent::deleteRecordById(AppConstants::TABLE_NAME_POSTS, $id);
    }

    public static function isTitleUnique(string $title, ?int $id = null): bool
    {
        return parent::isValueUnique(AppConstants::TABLE_NAME_POSTS, "title", $title, $id);
    }

    public function updatePost(int $id): void
    {
        parent::updateRecord(
            AppConstants::TABLE_NAME_POSTS,
            $id,
            ["title", "content", "status", "category_id"],
            [$this->title, $this->content, $this->status, $this->categoryId]
        );
    }

    public static function hasEntries(): bool
    {
        return parent::countTableEntries(AppConstants::TABLE_NAME_POSTS);
    }

    public static function updatePostStatus(int $id, string $status): void
    {
        parent::updateSingleColumn(AppConstants::TABLE_NAME_POSTS, $id, "status", $status);
    }

    public static function fetchPostById(int $id): array|false
    {
        return parent::fetchByUniqueAttribute(AppConstants::TABLE_NAME_POSTS, "id", $id);
    }

    public static function removeAllPosts(): void
    {
        parent::deleteAllRecords(AppConstants::TABLE_NAME_POSTS);
    }

    public static function resetPostAutoIncrement(): void
    {
        parent::resetAutoIncrement(AppConstants::TABLE_NAME_POSTS);
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
     * Get the value of title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get the value of content
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set the value of content
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get the value of categoryId
     */
    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    /**
     * Set the value of categoryId
     */
    public function setCategoryId(string $categoryId): self
    {
        $this->categoryId = $categoryId;
        return $this;
    }
}
