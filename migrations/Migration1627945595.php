<?php
  namespace Migrations;
  use Core\Migration;

  class Migration1627945595 extends Migration {
    public function up() {
      $table = "gallery_images";
      $this->createTable($table);
      $this->addTimeStamps($table);
      $this->addColumn($table,'user_id','int');
      $this->addColumn($table,'name','varchar',['size'=>64]);
      $this->addColumn($table,'body','text');
      $this->addColumn($table,'url','varchar',['size'=>255]);
      $this->addSoftDelete($table);
    }
  }
