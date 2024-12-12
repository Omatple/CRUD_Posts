<?php

namespace App\Database;

use \Exception;
use \PDOException;
use \PDOStatement;

class QueryExecutor
{
    private static function getSingularTableName(string $tableName): string
    {
        return substr($tableName, 0, strlen($tableName) - 1);
    }

    protected static function executeQuery(string $query, ?string $errorMessage = null, array $placeholders = []): PDOStatement|false
    {
        $connection = Connection::getInstance();
        $pdo = $connection->getConnection()->prepare($query);
        try {
            $pdo->execute($placeholders);
            return $pdo;
        } catch (PDOException $e) {
            $message = is_null($errorMessage) ? "Failed query" : $errorMessage;
            throw new Exception($message . ":" . $e->getMessage(), (int) $e->getCode());
        } finally {
            $connection->closeConnection();
        }
    }

    protected static function read(string $tableName, ?int $id = null): array|false
    {
        $idQuery = is_null($id) ? "" : " WHERE id = :i";
        $idPlaceholders = is_null($id) ? [] : [":i" => $id];
        return self::executeQuery(
            "SELECT * FROM $tableName$idQuery",
            "Failed to retrieve " . self::getSingularTableName($tableName),
            $idPlaceholders
        )->fetchAll();
    }

    protected static function getEntityByUniqueAttribute(string $tableName, string $attribute, string $value): array|false
    {
        return self::executeQuery(
            "SELECT * FROM $tableName WHERE $attribute = :v",
            "Failed to check uniqueness of " . $attribute . " '" . $value . "' on " . self::getSingularTableName($tableName),
            [":v" => $value]
        )->fetch();
    }

    protected static function create(string $tableName, array $attributesName, array $attributesValues): void
    {
        $valuesString = array_map(fn($attribute) => ":" . $attribute, $attributesName);
        $placeholders = array_combine($valuesString, $attributesValues);
        self::executeQuery(
            "INSERT INTO " . $tableName . " (" . implode(",", $attributesName) . ") VALUES (" . implode(",", $valuesString) . ")",
            "Failed to create a " . self::getSingularTableName($tableName),
            $placeholders
        );
    }

    protected static function getIds(string $tableName): array
    {
        $result = self::executeQuery(
            "SELECT id FROM $tableName",
            "Failed to retrieve ids" . self::getSingularTableName($tableName),
        );
        $ids = [];
        while ($row = $result->fetchColumn()) {
            $ids[] = (int) $row;
        }
        return $ids;
    }

    protected static function isAttributeUnique(string $tableName, string $attributeName, string $value, ?int $id = null): bool
    {
        $idQuery = is_null($id) ? "" : " AND id <> :i";
        $idPlaceholders = is_null($id) ? [":v" => $value] : [":v" => $value, ":i" => $id];
        return !self::executeQuery(
            "SELECT COUNT(*) FROM $tableName WHERE $attributeName = :v$idQuery",
            "Failed to check if the $attributeName name '$value' is already in use on " . self::getSingularTableName($tableName),
            $idPlaceholders
        )->fetchColumn();
    }

    protected static function delete(string $tableName, int $id): void
    {
        self::executeQuery(
            "DELETE FROM " . $tableName . " WHERE id = :i",
            "Failed to delete on table " . self::getSingularTableName($tableName) . " with ID " . $id,
            [
                ":i" => $id,
            ]
        );
    }

    protected static function update(string $tableName, int $id, array $attributesName, array $attributesValues): void
    {
        $valuesString = array_map(fn($attribute) => $attribute . " = :" . $attribute, $attributesName);
        $placeholders = array_merge([":id" => $id], array_combine(array_map(fn($attribute) => ":" . $attribute, $attributesName), $attributesValues));
        self::executeQuery(
            "UPDATE " . $tableName . " SET " . implode(",", $valuesString) . " WHERE id = :id",
            "Failed to update " . self::getSingularTableName($tableName) . " with ID '$id'",
            $placeholders
        );
    }

    protected static function updateOnlyAttribute(string $tableName, int $id, string $attributeName, string $attributeValue): void
    {
        self::executeQuery(
            "UPDATE " . $tableName . " SET " . $attributeName . " = :" . $attributeName . " WHERE id = :id",
            "Failed to update " . $attributeName . " from " . self::getSingularTableName($tableName) . " with ID '$id'",
            [
                ":id" => $id,
                ":$attributeName" => $attributeValue,
            ]
        );
    }

    protected static function deleteAllRegistres(string $tableName): void
    {
        self::executeQuery(
            "DELETE FROM " . $tableName,
            "Failed to delete all registres from table " . self::getSingularTableName($tableName)
        );
    }

    protected static function resetIncremenntIds(string $tableName): void
    {
        self::executeQuery(
            "ALTER TABLE " . $tableName . " AUTO_INCREMENT = 1",
            "Failed to reset auto increment id from table " . self::getSingularTableName($tableName)
        );
    }
}
