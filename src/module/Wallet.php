<?php

namespace Module;

use PDO;

class Wallet extends BaseMdel
{
  private $user_id;
    private $month;
    private $budget;
    private $created_at;

public function __construct() {
    parent::__construct();
}

    public function GetId(){return $this->id;}
    public function GetUserId(){return $this->user_id;}
    public function GetMonth(){return $this->month;}
    public function GetBudget(){return $this->budget;}
    public function GetDate(){return $this->created_at;}    

    protected function getTable(): string {
        return "wallets";
    }

    protected function getColumns(): array {
        return ['user_id', 'month', 'budget', 'created_at'];
    }

    protected function fill(array $row): void{
       $this->id = $row['id'];
        $this->name = $row['user_id'];
        $this->email = $row['month'];
        $this->password = $row['budget'];
        $this->created_at = $row['created_at'];
    }

}
?>
