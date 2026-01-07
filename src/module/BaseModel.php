<?php
namespace Module;

use PDO;

abstract class BaseModel{


    protected $db;
    protected $id;

    public function __construct(){
        $this->db = Database::getInstance();
    }
    public function getId(){
        return $this->id;
    }

    public function LoadAll(){

        $stmt = $this->db->getConnection()->prepare("SELECT * FROM {$this->getTable()}");
        $stmt->execute();
       return $stmt->fetchAll(PDO::FETCH_ASSOC);
       
    }

    public function create(){
        $columns = $this->getColumns();
        $PlaceHolder = array_map(fn($col)=>":$col", $columns);
        $sql = "INSERT INTO {$this->getTable()} (" . implode(",", $columns) . ") VALUES (" . implode(",", $PlaceHolder) . ")";
        $stmt = $this->db->getConnection()->prepare($sql);

         $data = [];
        foreach ($columns as $col) {
            $data[$col] = $this->$col;
        }

        if ($stmt->execute($data)) {
            $this->id = $this->db->getConnection()->lastInsertId();
            return true;
        }
        return false;
    }

     public function update(): bool
    {
        if (!$this->id) return false;

        $columns = $this->getColumns();
        $set = implode(", ", array_map(fn($col) => "$col = :$col", $columns));

        $stmt = $this->db->getConnection()->prepare(
            "UPDATE {$this->getTable()} SET $set WHERE id = :id"
        );

        $data = [];
        foreach ($columns as $col) {
            $data[$col] = $this->$col;
        }
        $data['id'] = $this->id;

        return $stmt->execute($data);
    }

        public function delete(): bool
    {
        if (!$this->id) return false;
        $stmt = $this->db->getConnection()->prepare(
            "DELETE FROM {$this->getTable()} WHERE id = :id"
        );
        return $stmt->execute(['id' => $this->id]);
    }


        abstract protected function getTable(): string;
    abstract protected function getColumns(): array;
    abstract protected function fill(array $row): void;
}








?>