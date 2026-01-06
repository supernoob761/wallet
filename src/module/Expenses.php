<?php

namespace Module;


class User
{
    private $db;

    private $id;
    private $name;
    private $email;
    private $password;
    private $created_at;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }


    public function setName($name) {
        $this->name = $name;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }


    public function loadById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $this->id = $user['id'];
            $this->name = $user['name'];
            $this->email = $user['email'];
            $this->password = $user['password'];
            $this->created_at = $user['created_at'];
            return true;
        }
        return false;
    }


    public function CreateUser() {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, created_at) VALUES (:name, :email, :password, NOW())");
        return $stmt->execute([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password
        ]);
    }


    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }

    public function loadByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $this->id = $user['id'];
            $this->name = $user['name'];
            $this->email = $user['email'];
            $this->password = $user['password'];
            $this->created_at = $user['created_at'];
            return true;
        }
        return false;
    }
}
