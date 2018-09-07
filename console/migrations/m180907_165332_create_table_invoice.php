<?php

use yii\db\Migration;

class m180907_165332_create_table_invoice extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%invoice}}', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->notNull(),
            'balance' => $this->bigInteger()->unsigned()->notNull()->defaultValue('0'),
        ], $tableOptions);

        $this->createIndex('user_id', '{{%invoice}}', 'user_id');
        $this->addForeignKey('invoice_key', '{{%invoice}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%invoice}}');
    }
}
