<?php

namespace App\Database;

use App\Utils\Constants;
use App\Utils\StatusPost;
use \Faker\Factory;

class PostEntity extends QueryExecutor
{
    private int $id;
    private string $titulo;
    private string $contenido;
    private string $status;
    private string $categoria_id;

    public static function readPosts(): array|false
    {
        return parent::executeQuery("SELECT " . Constants::POSTS_TABLE_NAME . ".*, nombre FROM " . Constants::POSTS_TABLE_NAME . ", " . Constants::CATEGORIES_TABLE_NAME . " WHERE " . Constants::CATEGORIES_TABLE_NAME . ".id = categoria_id")->fetchAll();
    }

    public static function generateFakePosts(int $amount): void
    {
        $faker = Factory::create("es_ES");
        for ($i = 0; $i < $amount; $i++) {
            do {
                $title = ucwords($faker->unique()->words(random_int(2, 6), true));
            } while (strlen($title) > Constants::TITLE_ALLOWED_MAX_CHARS);
            $content = $faker->realText(Constants::CONTENT_ALLOWED_MAX_CHARS);
            $status = $faker->randomElement(StatusPost::cases())->toString();
            $category_id = $faker->randomElement(CategoryEntity::getIdsCategory());
            (new PostEntity)
                ->setTitulo($title)
                ->setContenido($content)
                ->setStatus($status)
                ->setCategoriaId($category_id)
                ->createPost();
        }
    }

    public function createPost(): void
    {
        parent::create(
            Constants::POSTS_TABLE_NAME,
            ["titulo", "contenido", "status", "categoria_id"],
            [
                $this->titulo,
                $this->contenido,
                $this->status,
                $this->categoria_id,
            ]
        );
    }

    public static function deletePost(int $id): void
    {
        parent::delete(Constants::POSTS_TABLE_NAME, $id);
    }

    public static function isTitleUnique(string $title, ?int $id = null): bool
    {
        return parent::isAttributeUnique(Constants::POSTS_TABLE_NAME, "titulo", $title, $id);
    }

    public function updatePost(int $id): void
    {
        parent::update(
            Constants::POSTS_TABLE_NAME,
            $id,
            ["titulo", "contenido", "status", "categoria_id"],
            [
                $this->titulo,
                $this->contenido,
                $this->status,
                $this->categoria_id,
            ]
        );
    }

    public static function updateStatus(int $id, string $status): void
    {
        parent::updateOnlyAttribute(
            Constants::POSTS_TABLE_NAME,
            $id,
            "status",
            $status
        );
    }

    public static function getPostById(int $id): array|false
    {
        return parent::getEntityByUniqueAttribute(Constants::POSTS_TABLE_NAME, "id", $id);
    }

    public static function deleteAllPosts(): void
    {
        parent::deleteAllRegistres(Constants::POSTS_TABLE_NAME);
    }

    public static function resetAutoIncrementId(): void
    {
        parent::resetIncremenntIds(Constants::POSTS_TABLE_NAME);
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
     * Get the value of titulo
     */
    public function getTitulo(): string
    {
        return $this->titulo;
    }

    /**
     * Set the value of titulo
     */
    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get the value of contenido
     */
    public function getContenido(): string
    {
        return $this->contenido;
    }

    /**
     * Set the value of contenido
     */
    public function setContenido(string $contenido): self
    {
        $this->contenido = $contenido;

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
     * Get the value of categoria_id
     */
    public function getCategoriaId(): string
    {
        return $this->categoria_id;
    }

    /**
     * Set the value of categoria_id
     */
    public function setCategoriaId(string $categoria_id): self
    {
        $this->categoria_id = $categoria_id;

        return $this;
    }
}
