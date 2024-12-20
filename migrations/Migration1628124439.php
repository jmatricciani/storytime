<?php
  namespace Migrations;
  use Core\Migration;

  class Migration1628124439 extends Migration {
    public function up() {
      $table = "media_stack";
      $this->addColumn($table,'inventory_key','varchar',['size'=>32,'after'=>'body']);
    }
  }
