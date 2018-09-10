<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property int $invoice_id
 * @property int $user_id
 * @property string $stamp
 * @property int $type
 * @property string $amount
 * @property string $balance_after
 *
 * @property Invoice $invoice
 */
class Transaction extends ActiveRecord
{
    const TYPE_DEPOSIT = 0;
    const TYPE_WITHDRAWAL = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id'], 'required'],
            [['invoice_id', 'user_id', 'type', 'amount', 'balance_after'], 'default', 'value' => null],
            [['invoice_id', 'user_id', 'type', 'amount', 'balance_after'], 'integer'],
            [['stamp'], 'safe'],
            [['invoice_id', 'user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::className(), 'targetAttribute' => ['invoice_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'balance_after' => [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'balance_after',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'balance_after',
                ],
                'value' => function ($event) {
                    $transaction = $event->sender;
                    $amount = ($transaction->type == static::TYPE_DEPOSIT ? -1 : 1) * $transaction->amount;
                    $transaction->user->balance += $amount;
                    $transaction->user->save();
                    return $transaction->user->balance;
                },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_id' => 'Invoice ID',
            'user_id' => 'User ID',
            'stamp' => 'Stamp',
            'type' => 'Type',
            'amount' => 'Amount',
            'balance_after' => 'Balance After',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeValidate()
    {
        if ($this->stamp != null) {
            $formattedStamp = date('Y-m-d H:i:s', strtotime($this->stamp));
            $this->stamp = $formattedStamp;
        }
        return parent::beforeValidate();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoice_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->type == 0 ? 'Deposit' : 'Withdrawal';
    }
}
