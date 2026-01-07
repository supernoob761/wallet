<?php

namespace Module;

use PDO;

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

    public function getAll() {
        return $this->db
            ->query("SELECT * FROM users")
            ->fetchAll();
    }

    public function loadById($id): bool {
        $stmt = $this->db->getConnection()->prepare(
            "SELECT * FROM users WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);

        if ($user = $stmt->fetch()) {
            $this->fill($user);
            return true;
        }
        return false;
    }

    public function loadByEmail($email): bool {
        $stmt = $this->db->getConnection()->prepare(
            "SELECT * FROM users WHERE email = :email"
        );
        $stmt->execute(['email' => $email]);

        if ($user = $stmt->fetch()) {
            $this->fill($user);
            return true;
        }
        return false;
    }

    public function createUser(string $name, string $email, string $password): bool {
    $this->name = $name;
    $this->email = $email;
    // Hash the password
    $this->password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $this->db->getConnection()->prepare(
        "INSERT INTO users (name, email, password, created_at)
         VALUES (:name, :email, :password, NOW())"
    );

    return $stmt->execute([
        'name' => $this->name,
        'email' => $this->email,
        'password' => $this->password
    ]);
}


    private function fill(array $user): void {
        $this->id = $user['id'];
        $this->name = $user['name'];
        $this->email = $user['email'];
        $this->password = $user['password'];
        $this->created_at = $user['created_at'];
    }
    public function logout(): void
{
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();
}
 public function login(string $email, string $password): bool
{
    $stmt = $this->db->getConnection()->prepare(
        "SELECT * FROM users WHERE email = :email"
    );
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check password using correct column
    if ($user && password_verify($password, $user['password'])) {
        // Fill object properties
        $this->fill($user);

        // Set session
        $_SESSION['user_id'] = $this->id;
        $_SESSION['user_name'] = $this->name;
        $_SESSION['user_email'] = $this->email;

        session_regenerate_id(true);

        return true;
    }

    return false;
}

}
