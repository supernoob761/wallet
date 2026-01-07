<?php

namespace Module;

use PDO;

class Expenses
{
    private $db;

    private $id;
    private $wallet_id;
    private $category_id;
    private $title;
    private $amount;
    private $expense_date;
    private $is_automatic;
    private $created_at;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getWalletId()
    {
        return $this->wallet_id;
    }

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getExpenseDate()
    {
        return $this->expense_date;
    }

    public function isAutomatic()
    {
        return $this->is_automatic;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setWalletId($wallet_id)
    {
        $this->wallet_id = $wallet_id;
    }

    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function setExpenseDate($expense_date)
    {
        $this->expense_date = $expense_date;
    }

    public function setIsAutomatic($is_automatic)
    {
        $this->is_automatic = $is_automatic;
    }

    public function loadById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM expenses WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $expense = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($expense) {
            $this->id = $expense['id'];
            $this->wallet_id = $expense['wallet_id'];
            $this->category_id = $expense['category_id'];
            $this->title = $expense['title'];
            $this->amount = $expense['amount'];
            $this->expense_date = $expense['expense_date'];
            $this->is_automatic = $expense['is_automatic'];
            $this->created_at = $expense['created_at'];
            return true;
        }

        return false;
    }

    public function save()
    {
        $stmt = $this->db->prepare(
            "INSERT INTO expenses (wallet_id, category_id, title, amount, expense_date, is_automatic, created_at)
             VALUES (:wallet_id, :category_id, :title, :amount, :expense_date, :is_automatic, NOW())"
        );

        if ($stmt->execute([
            'wallet_id' => $this->wallet_id,
            'category_id' => $this->category_id,
            'title' => $this->title,
            'amount' => $this->amount,
            'expense_date' => $this->expense_date,
            'is_automatic' => $this->is_automatic
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
            "UPDATE expenses
             SET wallet_id = :wallet_id,
                 category_id = :category_id,
                 title = :title,
                 amount = :amount,
                 expense_date = :expense_date,
                 is_automatic = :is_automatic
             WHERE id = :id"
        );

        return $stmt->execute([
            'wallet_id' => $this->wallet_id,
            'category_id' => $this->category_id,
            'title' => $this->title,
            'amount' => $this->amount,
            'expense_date' => $this->expense_date,
            'is_automatic' => $this->is_automatic,
            'id' => $this->id
        ]);
    }

    public function delete()
    {
        if (!$this->id) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM expenses WHERE id = :id");
        return $stmt->execute(['id' => $this->id]);
    }

    public function getByWallet($wallet_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM expenses WHERE wallet_id = :wallet_id ORDER BY expense_date DESC");
        $stmt->execute(['wallet_id' => $wallet_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
