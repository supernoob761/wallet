<?php

namespace Module;

use PDO;

class User extends BaseModel
{

    private $name;
    private $email;
    private $password;
    private $created_at;

public function __construct() {
    parent::__construct();
}

    public function GetId(){return $this->id;}
    public function GetName(){return $this->name;}
    public function GetEmail(){return $this->email;}
    public function GetPassword(){return $this->password;}
    public function GetDate(){return $this->created_at;}    

    protected function getTable(): string {
        return "users";
    }

    protected function getColumns(): array {
        return ['name', 'email', 'password', 'created_at'];
    }

    protected function fill(array $row): void{
       $this->id = $row['id'];
        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->password = $row['password'];
        $this->created_at = $row['created_at'];
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
