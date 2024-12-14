<?php

namespace App\Database;

use \Exception;
use \PDOException;
use \PDOStatement;

class QueryExecutor
{
    private static function singularizeTableName(string $tableName): string
    {
        return substr($tableName, 0, strlen($tableName) - 1);
    }

    protected static function executeStatement(string $sql, ?string $errorMessage = null, array $parameters = []): PDOStatement|false
    {
        $databaseConnection = Connection::getInstance();
        $preparedStatement = $databaseConnection->getConnection()->prepare($sql);
        try {
            $preparedStatement->execute($parameters);
            return $preparedStatement;
        } catch (PDOException $exception) {
            $errorText = is_null($errorMessage) ? "Query execution failed" : $errorMessage;
            throw new Exception($errorText . ": " . $exception->getMessage(), (int)$exception->getCode());
        } finally {
            $databaseConnection->closeConnection();
        }
    }

    protected static function countTableEntries(string $tableName): int
    {
        return self::executeStatement(
            "SELECT COUNT(*) FROM " . $tableName,
            "Failed to retrieve the number of entries in the table."
        )->fetchColumn();
    }

    protected static function fetchRecords(string $tableName, ?int $recordId = null): array|false
    {
        $condition = is_null($recordId) ? "" : " WHERE id = :id";
        $parameters = is_null($recordId) ? [] : [":id" => $recordId];
        return self::executeStatement(
            "SELECT * FROM $tableName$condition",
            "Failed to fetch records from " . self::singularizeTableName($tableName),
            $parameters
        )->fetchAll();
    }

    protected static function fetchByUniqueAttribute(string $tableName, string $attributeName, string $attributeValue): array|false
    {
        return self::executeStatement(
            "SELECT * FROM $tableName WHERE $attributeName = :value",
            "Failed to check uniqueness of $attributeName = '$attributeValue' in " . self::singularizeTableName($tableName),
            [":value" => $attributeValue]
        )->fetch();
    }

    protected static function insertRecord(string $tableName, array $columns, array $values): void
    {
        $placeholders = array_map(fn($column) => ":" . $column, $columns);
        $parameters = array_combine($placeholders, $values);
        self::executeStatement(
            "INSERT INTO $tableName (" . implode(",", $columns) . ") VALUES (" . implode(",", $placeholders) . ")",
            "Failed to insert record into " . self::singularizeTableName($tableName),
            $parameters
        );
    }

    protected static function fetchAllIds(string $tableName): array
    {
        $statement = self::executeStatement(
            "SELECT id FROM $tableName",
            "Failed to retrieve IDs from " . self::singularizeTableName($tableName),
        );
        $ids = [];
        while ($id = $statement->fetchColumn()) {
            $ids[] = (int)$id;
        }
        return $ids;
    }

    protected static function isValueUnique(string $tableName, string $columnName, string $value, ?int $excludeId = null): bool
    {
        $condition = is_null($excludeId) ? "" : " AND id <> :excludeId";
        $parameters = is_null($excludeId) ? [":value" => $value] : [":value" => $value, ":excludeId" => $excludeId];
        return !self::executeStatement(
            "SELECT COUNT(*) FROM $tableName WHERE $columnName = :value$condition",
            "Failed to validate uniqueness of $columnName = '$value' in " . self::singularizeTableName($tableName),
            $parameters
        )->fetchColumn();
    }

    protected static function deleteRecordById(string $tableName, int $recordId): void
    {
        self::executeStatement(
            "DELETE FROM $tableName WHERE id = :id",
            "Failed to delete record from " . self::singularizeTableName($tableName) . " with ID $recordId",
            [":id" => $recordId]
        );
    }

    protected static function updateRecord(string $tableName, int $recordId, array $columns, array $values): void
    {
        $setClause = array_map(fn($column) => "$column = :" . $column, $columns);
        $parameters = array_merge(
            [":id" => $recordId],
            array_combine(array_map(fn($column) => ":" . $column, $columns), $values)
        );
        self::executeStatement(
            "UPDATE $tableName SET " . implode(",", $setClause) . " WHERE id = :id",
            "Failed to update record in " . self::singularizeTableName($tableName) . " with ID $recordId",
            $parameters
        );
    }

    protected static function updateSingleColumn(string $tableName, int $recordId, string $columnName, string $value): void
    {
        self::executeStatement(
            "UPDATE $tableName SET $columnName = :value WHERE id = :id",
            "Failed to update $columnName in " . self::singularizeTableName($tableName) . " with ID $recordId",
            [":id" => $recordId, ":value" => $value]
        );
    }

    protected static function deleteAllRecords(string $tableName): void
    {
        self::executeStatement(
            "DELETE FROM $tableName",
            "Failed to delete all records from " . self::singularizeTableName($tableName)
        );
    }

    protected static function resetAutoIncrement(string $tableName): void
    {
        self::executeStatement(
            "ALTER TABLE $tableName AUTO_INCREMENT = 1",
            "Failed to reset auto-increment for " . self::singularizeTableName($tableName)
        );
    }
}
