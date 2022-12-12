<?php

namespace Core\Model;

use Core\Database\Database;

abstract class Model
{

    protected $table;
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
        if (is_null($this->table)) {
            $parts = explode('\\', get_class($this));
            $className = end($parts);
            $this->table = strtolower(str_replace('Model', '', $className)) . 's';
        }
    }

    public function all()
    {
        return $this->query('SELECT * FROM ' . $this->table);
    }

    public function find($id)
    {
        return $this->query(
            "SELECT * FROM {$this->table} WHERE id = ?",
            [$id],
            true
        );
    }

    public function update($id, $fields)
    {
        $sqlParts = [];
        $attributes = [];
        foreach ($fields as $k => $v) {
            $sqlParts[] = "$k = ?";
            $attributes[] = $v;
        }
        $attributes[] = $id;
        $sqlParts = implode(', ', $sqlParts);
        return $this->query(
            "UPDATE {$this->table} SET $sqlParts WHERE id = ?",
            $attributes,
            true
        );
    }

    public function delete($id)
    {
        return $this->query(
            "DELETE FROM {$this->table} WHERE id = ?",
            [$id],
            true
        );
    }

    public function create($fields)
    {
        $sqlParts = [];
        $attributes = [];
        foreach ($fields as $k => $v) {
            $sqlParts[] = "$k = ?";
            $attributes[] = $v;
        }
        $sqlParts = implode(', ', $sqlParts);
        return $this->query(
            "INSERT INTO {$this->table} SET $sqlParts",
            $attributes,
            true
        );
    }

    /**
     * ??????????
     */
    public function extract($key, $value)
    {
        $records = $this->all();
        $return = [];
        foreach ($records as $v) {
            $return[$v->$key] = $v->$value;
        }
        return $return;
    }

    public function query($statement, $attributes = null, $one = false)
    {
        if ($attributes) {
            return $this->db->prepare(
                $statement,
                $attributes,
                str_replace('Model', 'Entity', get_class($this)),
                $one
            );
        } else {
            return $this->db->query(
                $statement,
                str_replace('Model', 'Entity', get_class($this)),
                $one
            );
        }
    }

    public function findByField($key, $value, $one = true)
    {
        return $this->query(
            "SELECT * FROM {$this->table} WHERE $key = ?",
            [$value],
            $one
        );
    }

}

