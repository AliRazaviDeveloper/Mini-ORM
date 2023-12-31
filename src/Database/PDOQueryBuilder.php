<?php

namespace App\Database;

use App\Contract\DatabaseInterface;

class PDOQueryBuilder
{
    protected \PDO $connection;
    protected string $table;
    protected array $condition;
    protected array $value;
    public function __construct(DatabaseInterface $connection)
    {
        $this->connection = $connection->getConnection();
    }

    public function table($table): static
    {
        $this->table = $table;
        return $this;
    }

    public function create($data): int
    {
        $field = array_keys($data);
        $placeholder = [];
        foreach ($field as $column) {
            $placeholder[] = "?";
        }
        $field = implode(",", $field);
        $placeholder = implode(",", $placeholder);
        $sql = "INSERT INTO $this->table ($field) VALUES ($placeholder)";
        $query = $this->connection->prepare($sql);
        $query->execute(array_values($data));
        return (int)$this->connection->lastInsertId();
    }

    public function where(string $column, string $value): static
    {
        $this->condition[] = "{$column}=?";
        $this->value[] = $value;
        return $this;
    }

    public function update(array $data): int
    {
        $fields = [];
        foreach ($data as $column => $value) {
            $fields[] = "{$column}='{$value}'";
        }
        $fields = implode(",", $fields);
        $condition = implode(" and ", $this->condition);
        $sql = "UPDATE {$this->table} SET {$fields} WHERE ${condition}";
        $query = $this->connection->prepare($sql);
        $query->execute($this->value);
        return (int)$query->rowCount();
    }

    public function delete(): int
    {
        $condtion = implode(" and ", $this->condition);
        $sql = "DELETE FROM {$this->table} WHERE {$condtion}";
        $query = $this->connection->prepare($sql);
        $query->execute($this->value);
        return (int)$query->rowCount();
    }

    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }

    public function rollback(): void
    {
        $this->connection->commit();
        $this->connection->rollBack();
    }

    public function get($option = ["*"]): array
    {
        $condtion = implode(" and ", $this->condition);
        $column = implode(",", $option);

        $sql = "SELECT {$column} FROM {$this->table} WHERE {$condtion}";

        $query = $this->connection->prepare($sql);
        $query->execute($this->value);
        return $query->fetchAll();
    }

    public function first()
    {
        $condtion = implode(" and ", $this->condition);
        $sql = "SELECT * FROM {$this->table} WHERE {$condtion}";
        $query = $this->connection->prepare($sql);
        $query->execute($this->value);
        return $query->fetch();
    }

    public function find(int $id)
    {
        return $this->where("id", $id)->first();
    }

    public function findBy(string $column, $value)
    {
        return $this->where($column, $value)->first();
    }


    public function trancateAll(): void
    {
        $query = $this->connection->prepare("show tables");
        $query->execute();
        foreach ($query->fetchAll(\PDO::FETCH_COLUMN) as $table) {
            $this->connection->prepare("TRUNCATE TABLE `{$table}`")->execute();
        }
    }
}
