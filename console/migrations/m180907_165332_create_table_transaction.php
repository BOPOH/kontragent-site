<?php

use yii\db\Migration;

class m180907_165332_create_table_transaction extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%transaction}}', [
            'id' => $this->primaryKey()->unsigned(),
            'invoice_id' => $this->integer()->unsigned()->notNull(),
            'stamp' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'type' => $this->tinyInteger()->unsigned()->notNull()->defaultValue('0'),
            'amount' => $this->bigInteger()->unsigned()->notNull()->defaultValue('0'),
            'balance_after' => $this->bigInteger()->unsigned()->notNull()->defaultValue('0'),
        ], $tableOptions);

        $this->addForeignKey('invoice', '{{%transaction}}', 'invoice_id', '{{%invoice}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%transaction}}');
    }
}
