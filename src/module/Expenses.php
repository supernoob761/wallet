<?php

namespace Module;

use PDO;

class Expenses extends BaseModel
{
    protected $wallet_id;
    protected $category_id;
    protected $title;
    protected $amount;
    protected $expense_date;
    protected $is_automatic;
    protected $created_at;

    public function __construct() {
        parent::__construct();
    }


    public function getId() {
        return $this->id;
    }

    public function getWalletId() {
        return $this->wallet_id;
    }

    public function getCategoryId() {
        return $this->category_id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getExpenseDate() {
        return $this->expense_date;
    }

    public function isAutomatic() {
        return $this->is_automatic;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }



    protected function getTable(): string {
        return "expenses";
    }

    protected function getColumns(): array {
        return [
            'wallet_id',
            'category_id',
            'title',
            'amount',
            'expense_date',
            'is_automatic',
            'created_at'
        ];
    }

    protected function fill(array $row): void {
        $this->id = $row['id'];
        $this->wallet_id = $row['wallet_id'];
        $this->category_id = $row['category_id'];
        $this->title = $row['title'];
        $this->amount = $row['amount'];
        $this->expense_date = $row['expense_date'];
        $this->is_automatic = $row['is_automatic'];
        $this->created_at = $row['created_at'];
    }
}
