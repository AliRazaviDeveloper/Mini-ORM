<?php

namespace App\Database;

use App\Contract\DatabaseInterface;

class PDOQueryBuilder
{
    protected \PDO $connection;
    protected $table;

    protected array $condition;

    protected array $value;
    public function __construct(DatabaseInterface $connection)
    {
        $this->connection=$connection->getConnection();
    }

    public function table($table)
    {
         $this->table=$table;
         return $this;
    }

    public function create($data) :int
    {
        $field=array_keys($data);
        $placeholder=[];
        foreach ($field as $column){
            $placeholder[] = "?";
        }
        $field=implode(",",$field);
        $placeholder=implode(",",$placeholder);
        $sql="INSERT INTO $this->table ($field) VALUES ($placeholder)";
        $query=$this->connection->prepare($sql);
        $query->execute(array_values($data));
        return (int)$this->connection->lastInsertId();
    }

    public function where(string $column,string $value)
    {
        $this->condition[]="{$column}=?";
        $this->value[]=$value;
        return $this;
    }

    public function update(array $data) : int
    {
        $fields=[];
        foreach ($data as $column=>$value){
            $fields[]="{$column}='{$value}'";
        }
        $fields=implode(",",$fields);
        $condition=implode(" and ",$this->condition);
        $sql="UPDATE {$this->table} SET {$fields} WHERE ${condition}";
        $query=$this->connection->prepare($sql);
        $query->execute($this->value);
        return (int)$query->rowCount();
    }
}