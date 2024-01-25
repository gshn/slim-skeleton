<?php

namespace App\Factory;

use InvalidArgumentException;
use PDO;
use PDOStatement;

final class QueryFactory
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function query(string $sql, array|null $data = []): PDOStatement|false
    {
        if (empty($data)) {
            return $this->pdo->query($sql);
        }

        $result = $this->pdo->prepare($sql);
        $result->execute($data);

        return $result;
    }

    public function insert(string $table, array $data): string|false
    {
        if (empty($data)) {
            throw new InvalidArgumentException();
        }

        $sql = "INSERT INTO {$table} ";
        $sql .= QueryFactory::buildSetString($data);

        $this->query($sql, array_values($data));

        return $this->pdo->lastInsertId();
    }

    public function select(string $table, array|null $select = [], array|null $where = []): PDOStatement|false
    {
        $cols = '*';
        if (!empty($select)) {
            $cols = implode(',', $select);
        }

        $sql = "SELECT {$cols} FROM {$table} ";

        if (empty($where)) {
            return $this->query($sql);
        }

        $sql .= QueryFactory::buildWhereString($where);

        return $this->query($sql, array_values($where));
    }

    public function update(string $table, array $data, array $where): PDOStatement|false
    {
        if (empty($data) || empty($where)) {
            throw new InvalidArgumentException();
        }

        $sql = "UPDATE {$table} ";
        $sql .= QueryFactory::buildSetString($data);
        $sql .= QueryFactory::buildWhereString($where);

        $values = array_merge(array_values($data), array_values($where));

        return $this->query($sql, $values);
    }

    public function delete(string $table, array $where): PDOStatement|false
    {
        if (empty($where)) {
            throw new InvalidArgumentException();
        }

        $sql = "DELETE FROM {$table} ";
        $sql .= QueryFactory::buildWhereString($where);

        return $this->query($sql, array_values($where));
    }

    private static function buildSetString(array $data): string
    {
        $sql = 'SET ';
        $last = array_key_last($data);

        foreach ($data as $key => $value) {
            $sql .= "{$key} = ? ";

            if ($key !== $last) {
                $sql .= ', ';
            }
        }

        return $sql;
    }

    private static function buildWhereString(array $where): string
    {
        $sql = 'WHERE ';
        $last = array_key_last($where);

        foreach ($where as $key => $value) {
            $sql .= "{$key} = ? ";

            if ($key !== $last) {
                $sql .= 'AND ';
            }
        }

        return $sql;
    }

    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    public function commit(): void
    {
        $this->pdo->commit();
    }

    public function rollBack(): void
    {
        $this->pdo->rollback();
    }
}
