<?php

namespace Module;

use PDO;

class categories
{
    private $db;

    private $id;
    private $name;

    public function __construct() {
        $this->db = Database::getInstance();
    }


    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }


    public function setName($name) {
        $this->name = $name;
    }


    public function loadById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $cat = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cat) {
            $this->id = $cat['id'];
            $this->name = $cat['name'];
            return true;
        }
        return false;
    }


    public function save() {
        $stmt = $this->db->prepare("INSERT INTO categories (name) VALUES (:name)");
        if ($stmt->execute(['name' => $this->name])) {
            $this->id = $this->db->lastInsertId();
            return true;
        }
        return false;
    }

    public function update() {
        if (!$this->id) return false;
        $stmt = $this->db->prepare("UPDATE categories SET name = :name WHERE id = :id");
        return $stmt->execute(['name' => $this->name, 'id' => $this->id]);
    }


    public function delete() {
        if (!$this->id) return false;
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute(['id' => $this->id]);
    }


    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
