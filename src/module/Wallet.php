<?php

namespace Module;

use PDO;

class Wallet
{
    private $db;

    private $id;
    private $user_id;
    private $month;
    private $budget;
    private $created_at;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function getBudget()
    {
        return $this->budget;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function setMonth($month)
    {
        $this->month = $month;
    }

    public function setBudget($budget)
    {
        $this->budget = $budget;
    }

    public function loadById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM wallets WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $wallet = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($wallet) {
            $this->id = $wallet['id'];
            $this->user_id = $wallet['user_id'];
            $this->month = $wallet['month'];
            $this->budget = $wallet['budget'];
            $this->created_at = $wallet['created_at'];
            return true;
        }

        return false;
    }

    public function save()
    {
        $stmt = $this->db->prepare(
            "INSERT INTO wallets (user_id, month, budget, created_at)
             VALUES (:user_id, :month, :budget, NOW())"
        );

        if ($stmt->execute([
            'user_id' => $this->user_id,
            'month' => $this->month,
            'budget' => $this->budget
        ])) {
            $this->id = $this->db->lastInsertId();
            return true;
        }

        return false;
    }

    public function update()
    {
        if (!$this->id) {
            return false;
        }

        $stmt = $this->db->prepare(
            "UPDATE wallets
             SET user_id = :user_id,
                 month = :month,
                 budget = :budget
             WHERE id = :id"
        );

        return $stmt->execute([
            'user_id' => $this->user_id,
            'month' => $this->month,
            'budget' => $this->budget,
            'id' => $this->id
        ]);
    }

    public function delete()
    {
        if (!$this->id) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM wallets WHERE id = :id");
        return $stmt->execute(['id' => $this->id]);
    }

    public function getByUserAndMonth($user_id, $month)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM wallets WHERE user_id = :user_id AND month = :month LIMIT 1"
        );
        $stmt->execute([
            'user_id' => $user_id,
            'month' => $month
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
