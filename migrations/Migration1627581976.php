<?php
  namespace Migrations;
  use Core\Migration;

  class Migration1627581976 extends Migration {
    public function up() {

      $table = "media";
      $this->createTable($table);
      $this->addTimeStamps($table);

      $this->addColumn($table,'user_id','int');
      $this->addColumn($table,'stack_id','int');
      $this->addColumn($table,'name','varchar',['size'=>64]);
      $this->addColumn($table,'type','varchar',['size'=>32]);
      $this->addColumn($table,'label','varchar',['size'=>32]);
      $this->addColumn($table,'body','text');
      $this->addColumn($table,'sort','int');
      $this->addSoftDelete($table);

      $this->addIndex($table,'user_id');
      $this->addIndex($table,'stack_id');
      $this->addIndex($table,'name');
      $this->addIndex($table,'type');
      $this->addIndex($table,'sort');


      $table = "media_images";
      $this->createTable($table);
      $this->addColumn($table,'media_id','int');
      $this->addColumn($table,'name','varchar',['size'=>155]);
      $this->addColumn($table,'url','varchar',['size'=>155]);
      $this->addSoftDelete($table);

      $this->addIndex($table,'media_id');

      $table = "media_audio";
      $this->createTable($table);
      $this->addColumn($table,'media_id','int');
      $this->addColumn($table,'name','varchar',['size'=>155]);
      $this->addColumn($table,'url','varchar',['size'=>155]);
      $this->addSoftDelete($table);

      $this->addIndex($table,'media_id');

      $table = "media_stack";
      $this->createTable($table);
      $this->addTimeStamps($table);

      $this->addColumn($table,'user_id','int');
      $this->addColumn($table,'name','varchar',['size'=>64]);
      $this->addColumn($table,'type','varchar',['size'=>32]);
      $this->addColumn($table,'catalog','varchar',['size'=>32]);
      $this->addColumn($table, 'price', 'decimal', ['precision'=>10,'scale'=>2]);
      $this->addColumn($table, 'shipping', 'decimal', ['precision'=>10,'scale'=>2]);
      $this->addColumn($table,'body','text');
      $this->addSoftDelete($table);

      $this->addIndex($table,'user_id');
      $this->addIndex($table,'name');
      $this->addIndex($table,'type');

      $table = "media_stack_images";
      $this->createTable($table);
      $this->addColumn($table,'stack_id','int');
      $this->addColumn($table,'name','varchar',['size'=>155]);
      $this->addColumn($table,'url','varchar',['size'=>155]);
      $this->addColumn($table,'sort','int');
      $this->addSoftDelete($table);

      $this->addIndex($table,'stack_id');
      $this->addIndex($table,'sort');

    }
  }
