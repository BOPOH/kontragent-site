<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property int $invoice_id
 * @property string $stamp
 * @property int $type
 * @property string $amount
 * @property string $balance_after
 *
 * @property Invoice $invoice
 */
class Transaction extends \yii\db\ActiveRecord
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
            [['invoice_id', 'type', 'amount', 'balance_after'], 'default', 'value' => null],
            [['invoice_id', 'type', 'amount', 'balance_after'], 'integer'],
            [['stamp'], 'safe'],
            [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::className(), 'targetAttribute' => ['invoice_id' => 'id']],
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
            'stamp' => 'Stamp',
            'type' => 'Type',
            'amount' => 'Amount',
            'balance_after' => 'Balance After',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoice_id']);
    }
}
