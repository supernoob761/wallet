<?php

namespace Module;

use PDO;

class Categories extends BaseModel
{
private $name;

public function __construct() {
    parent::__construct();
}

    public function GetId(){return $this->id;}
    public function GetName(){return $this->name;} 

    protected function getTable(): string {
        return "categories";
    }

    protected function getColumns(): array {
        return ['name'];
    }

    protected function fill(array $row): void{
       $this->id = $row['id'];
        $this->name = $row['name'];
    }

}
?>
