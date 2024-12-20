<?php
  namespace Migrations;
  use Core\Migration;

  class Migration1626905961 extends Migration {
    public function up() {

      $table = "users";
      $this->addColumn($table,'artist_name','varchar',['size'=>150,'after'=>'lname']);
      $this->addIndex($table,'artist_name');
    }
  }
