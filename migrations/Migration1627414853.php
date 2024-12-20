<?php
  namespace Migrations;
  use Core\Migration;

  class Migration1627414853 extends Migration {
    public function up() {

      // storytime edit

      $table = "options";
      $this->dropTable($table);
      $table = "product_option_refs";
      $this->dropTable($table);
      $table = "brands";
      $this->dropTable($table);

      $table = "inventory";
      $this->createTable($table);
      $this->addTimeStamps($table);
      $this->addColumn($table,'inventory_key','varchar',['size'=>32]);
      $this->addColumn($table,'option','varchar',['size'=>32]);
      $this->addColumn($table,'stock','int');
      $this->addIndex($table,'inventory_key');
      $this->addIndex($table,'option');

      $table = "products";
      $this->dropColumn($table,'has_options');
      $this->dropColumn($table,'inventory');
      $this->dropColumn($table,'featured');
      $this->dropColumn($table,'list');
      $this->dropColumn($table,'brand_id');
      $this->addColumn($table,'inventory_key','varchar',['size'=>32,'after'=>'body']);
      $this->addIndex($table,'inventory_key');


      $table = "cart_items";
      $this->dropColumn($table,'option_id');
      $this->dropColumn($table,'product_id');
      $this->addColumn($table,'inventory_id','int',['after'=>'cart_id']);
      $this->addIndex($table,'inventory_id');
    }
  }
