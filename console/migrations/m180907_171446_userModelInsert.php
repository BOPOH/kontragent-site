<?php

use yii\db\Exception;
use yii\db\Migration;
use yii\helpers\VarDumper;
use yii\helpers\Console;
use \yii\base\Event;
use common\models\User;

class m180907_171446_userModelInsert extends Migration
{
    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        /**
        Uncomment this block for detach model behaviors
        Event::on(User::className(), User::EVENT_INIT,
                 function(Event $event ){
                     $event->sender->detachBehavior('someBehaviorName');
        });
        **/
        $model = new User();
        $model->setAttributes(
            [
    'username' => 'admin',
    'auth_key' => '',
    'password_hash' => '$2y$13$bTD4BR57RgQqfCbhrB5eZ.uDRuLJMMEyBAHOhdo9Yp7kOP58UNStu',
    'password_reset_token' => '4OkTH05miPsvZ80dmXE2wJeVI0gBnodj_1535474581',
    'email' => 'admin@localhost',
    'status' => '10',
    'created_at' => '0',
    'updated_at' => '1535474581',
],
        false);
        if(!$model->save()){
            $this->stderr('Fail save model with attributes '
                .VarDumper::dumpAsString($model->getAttributes()).' with errors '
                .VarDumper::dumpAsString($model->getErrors()));
                throw new Exception('Fail save $model');
        } else {
            $auth = Yii::$app->authManager;
            $adminRole = $auth->getRole('admin');
            $auth->assign($adminRole, $model->getId());
        }
    }

    public function safeDown()
    {
        //$this->truncateTable('{{%user}} CASCADE');
        //User::deleteAll([]);
    }

    protected function stderr($message)
    {
        Console::output(Console::ansiFormat($message, [Console::FG_PURPLE]));
    }
}
